SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET default_storage_engine = INNODB;

CREATE TABLE `User` (
  `user_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID user identifier (VARCHAR 128).',
  `username`
    varchar(50) NOT NULL
    UNIQUE
    COMMENT 'User login name.',
  `email`
    varchar(100) NOT NULL
    UNIQUE
    COMMENT 'User''s email address.',
  `password_hash`
    varchar(255) NOT NULL
    COMMENT 'Hashed password for security.',
  `silver_credits`
    int(11) NOT NULL
    DEFAULT 0
    COMMENT 'Balance for Silver virtual currency.',
  `gold_credits`
    int(11) NOT NULL
    DEFAULT 0
    COMMENT 'Balance for Gold virtual currency.',
  `is_subscribed`
    tinyint(1) NOT NULL
    DEFAULT 0
    COMMENT 'Status of the monthly subscription.',
  `subscription_date`
    date DEFAULT NULL
    COMMENT 'Date the subscription started/renewed.',
  `created_at`
    timestamp NOT NULL
    DEFAULT current_timestamp()
    COMMENT 'Time stamp of the account creation'
);

CREATE TABLE `Artist` (
  `artist_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID artist identifier (VARCHAR 128).',
  `name`
    varchar(100) NOT NULL
    UNIQUE
    COMMENT 'Artist name.',
  `bio`
    text NOT NULL
    COMMENT 'Biography or description.',
  `created_at`
    timestamp NOT NULL
    DEFAULT current_timestamp()
    COMMENT 'Timestamp when the artist record was created.',
  `cover_image_path`
    varchar(255) NOT NULL
    COMMENT 'The path to the artists image'
);

CREATE TABLE `Album` (
  `album_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID album identifier (VARCHAR 128).',
  `title`
    varchar(255) NOT NULL
    COMMENT 'Album name.',
  `artist_id`
    varchar(128) NOT NULL
    COMMENT 'Foreign key to the Artist this album belongs to (VARCHAR 128).',
  `gold_price`
    int(11) NOT NULL
    COMMENT 'Price in Gold Credits (for album purchase).',
  `release_date`
    date NOT NULL
    COMMENT 'Year the album was released.',
  `cover_image_path`
    varchar(255) NOT NULL
    COMMENT 'File path to the extracted album cover image.',

  FOREIGN KEY (`artist_id`) REFERENCES `Artist` (`artist_id`) ON DELETE CASCADE
);

CREATE TABLE `Song` (
  `song_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID song track identifier (VARCHAR 128).',
  `title`
    varchar(255) NOT NULL
    COMMENT 'Song name.',
  `duration_sec`
    int(11) NOT NULL
    COMMENT 'Length of the track in seconds.',
  `album_id`
    varchar(128) NOT NULL
    COMMENT 'Foreign key to the Album this song belongs to (VARCHAR 128).',
  `artist_id`
    varchar(128) NOT NULL
    COMMENT 'Foreign key to the Artist this song belongs to (VARCHAR 128).',
  `silver_price`
    int(11) NOT NULL
    COMMENT 'Price in Silver Credits (for single song purchase).',
  `file_path`
    varchar(255) NOT NULL
    COMMENT 'Path to the actual music file (for download/stream).',
  `track_number`
    int(11) NOT NULL
    COMMENT 'The sequential track number in the album.',
  `genre`
    varchar(50) NOT NULL
    COMMENT 'The genre of the music track.',
  `cover_image_path`
    varchar(255) NOT NULL
    COMMENT 'File path to the extracted album cover image.',

  FOREIGN KEY (`artist_id`) REFERENCES `Artist` (`artist_id`) ON DELETE CASCADE,
  FOREIGN KEY (`album_id`) REFERENCES `Album` (`album_id`) ON DELETE CASCADE
);

CREATE TABLE `OwnedAlbum` (
  `ownership_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID ownership record ID (VARCHAR 128).',
  `user_id`
    varchar(128) NOT NULL
    COMMENT 'The user who owns the album (VARCHAR 128).',
  `album_id`
    varchar(128) NOT NULL
    COMMENT 'The music track that is owned (VARCHAR 128).',
  `acquisition_date`
    datetime NOT NULL
    COMMENT 'Timestamp of the purchase.',
  UNIQUE KEY `idx_unique_ownership` (`user_id`,`album_id`),
  FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`album_id`) REFERENCES `Album` (`album_id`) ON DELETE CASCADE
);

CREATE TABLE `OwnedSong` (
  `ownership_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID ownership record ID (VARCHAR 128).',
  `user_id`
    varchar(128) NOT NULL
    COMMENT 'The user who owns the song (VARCHAR 128).',
  `song_id`
    varchar(128) NOT NULL
    COMMENT 'The music track that is owned (VARCHAR 128).',
  `acquisition_date`
    datetime NOT NULL
    COMMENT 'Timestamp of the purchase.',
  UNIQUE KEY `idx_unique_ownership` (`user_id`,`song_id`),
  FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`song_id`) REFERENCES `Song` (`song_id`) ON DELETE CASCADE
);

CREATE TABLE `BorrowedAccess` (
  `access_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID access record ID (VARCHAR 128).',
  `lender_user_id`
    varchar(128) NOT NULL
    COMMENT 'The user who owns and lent the song (VARCHAR 128).',
  `borrower_user_id`
    varchar(128) NOT NULL
    COMMENT 'The user who is streaming the song (VARCHAR 128).',
  `song_id`
    varchar(128) NOT NULL
    COMMENT 'The song being borrowed (VARCHAR 128).',
  `expiry_date`
    datetime NOT NULL
    COMMENT 'When streaming access is automatically revoked (1 week after lending).',
  UNIQUE KEY `idx_unique_borrowing` (`lender_user_id`,`borrower_user_id`,`song_id`),
  FOREIGN KEY (`lender_user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`borrower_user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`song_id`) REFERENCES `Song` (`song_id`) ON DELETE CASCADE
);

CREATE TABLE `Transaction` (
  `txn_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID transaction identifier (VARCHAR 128).',
  `user_id`
    varchar(128) NOT NULL
    COMMENT 'The user involved in the transaction (VARCHAR 128).',
  `txn_type`
    enum('TOPUP','BUY_SONG','BUY_ALBUM','SUBSCRIPTION') NOT NULL
    COMMENT 'Type of transaction.',
  `currency`
    enum('GOLD','SILVER','CASH') NOT NULL
    COMMENT 'The currency involved in the transaction.',
  `amount`
    int(11) NOT NULL
    COMMENT 'The amount of currency involved (positive for credit, negative for debit).',
  `related_item_id`
    varchar(128) DEFAULT NULL
    COMMENT 'ID of the Song or Album involved (VARCHAR 128).',
  `txn_timestamp`
    datetime NOT NULL
    DEFAULT current_timestamp()
    COMMENT 'When the transaction occurred.',

  KEY `user_id_idx` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`)
);

CREATE TABLE `Token` (
  `token_id`
    varchar(128) NOT NULL
    PRIMARY KEY
    COMMENT 'Unique UUID token identifier (VARCHAR 128).',
  `user_id`
    varchar(128) NOT NULL
    COMMENT 'The user to whom this token belongs (VARCHAR 128).',
  `token_hash`
    varchar(255) NOT NULL
    UNIQUE
    COMMENT 'Hashed value of the token for secure storage.',
  `token_type`
    enum(
      'SESSION',
      'API_KEY',
      'PASSWORD_RESET'
    ) NOT NULL
    COMMENT 'Purpose of the token.',
  `expires_at`
    datetime NOT NULL
    COMMENT 'When the token should be considered invalid.',
  `created_at`
    timestamp NOT NULL
    DEFAULT current_timestamp()
    COMMENT 'When the token record was initialised.',

  KEY `user_id_idx` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE
);

