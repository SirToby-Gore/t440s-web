<?php

ob_start();

require_once __DIR__ . '/init.php';

if (isset($_GET['logout']) && $account->logged_in) {
    // Ensure you are using the raw token UUID stored in $_SESSION['token']
    Token::delete_by_raw_token($_SESSION['token']);
    
    // Clear cookie and session
    $_SESSION['token'] = null;
    unset($_SESSION['token']);
    
    redirect($melody_root);
}

$play_queue = [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melody Vault</title>
    <style><?= file_get_contents(__DIR__ . '/styles.css') ?></style>
</head>
<body>
    <nav class="main-nav">
        <a class="home" href="./">
            <img src="favicon.png">
        </a>

        <form class="search-bar-form" action="index.php" method="get">
            <input type="text" name="search" class="search-bar-input" 
                   placeholder="🔎 Search Music..." 
                   value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
        </form>

        <?php

        if ($account->logged_in) {
            ?>
            <a class="account" href="./?account=<?= urlencode($account->user->user_id) ?>">
                <img src="<?= empty($account->user->profile_picture) ? "account placeholder.png" : $account->user->profile_picture ?>" alt="account">
            </a>
            <?php
        } else {
            ?>
            <a href="./?login">login</a>
            <?php
        }
        ?>
    </nav>

    <!-- Static Play-Bar -->
    <div class="play-bar">
        <div class="progress-bar" id="progress-bar">
            <div class="progress-bar-fill" id="progress-bar-fill"></div>
        </div>

        <!-- Left Section: Art and Song Details -->
        <div class="play-bar-details">
            <img id="play-bar-art" src="placeholder.png" alt="Album Art">
            <div class="text-info">
                <p id="play-bar-title"></p>
                <div>
                    <a id="play-bar-artist-link" class="album-link"></a>
                    <span id="play-bar-album-span"></span>
                </div>
            </div>
        </div>

        <!-- Middle Section: Controls -->
        <div class="play-bar-controls">
            <button id="previous-button" class="control-button" aria-label="Previous Track">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="19 20 9 12 19 4 19 20"></polygon><line x1="5" y1="19" x2="5" y2="5"></line></svg>
            </button>
            <button id="restart-button" class="control-button" aria-label="Play/Pause">
                <span id="play-pause-icon">
                    <!-- Play Icon Placeholder -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path fill="white" d="M10 8l6 4-6 4z"/></svg>
                </span>
            </button>
            <button id="next-button" class="control-button" aria-label="Next Track">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 4 15 12 5 20 5 4"></polygon><line x1="19" y1="5" x2="19" y2="19"></line></svg>
            </button>
        </div>

        <!-- Right Section: Volume (Hidden on Mobile) -->
        <div class="play-bar-right">
            <button id="volume-button" class="control-button" aria-label="Volume">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon></svg>
            </button>
            <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1" aria-label="Volume Slider">
        </div>
        
        <audio id="play-bar-audio">
            <source id="play-bar-source" src="" type="audio/flac">
        </audio>
    </div>

    <!-- Dynamic App Body, rendered by PHP -->
    <div id="app-body">
        <?php

        if (isset($_GET['album'])) {
            $album = Album::from_id($_GET['album']);
            $artist = Artist::from_id($album->artist_id);
            $songs = $album->get_songs();

            ?>
            <img class="album-page-art" src="<?= htmlspecialchars($album->cover_image_path) ?>">
                <h1 class="album-page-title"><?= htmlspecialchars($album->title) ?></h1>
                <a class="album-page-artist-link" href="?artist=<?= urlencode($artist->artist_id) ?>"><?= htmlspecialchars($artist->name) ?></a>
                <div class="album-action-bar">
                <button id="play-all-button" class="action-button action-button-play">▶️ Play All</button>
                <button id="shuffle-all-button" class="action-button action-button-shuffle">🔀 Shuffle All</button>
            </div>
            <ol class="album-page-song-list">
            <?php
            if (!empty($songs)) {
                foreach ($songs as $song) {
                    ?>
                    <li class="album-page-song-item"><?= $song->render(true) ?></li>
                    <?php
                    $play_queue[] = $song->to_assoc();
                }
            }
            ?>
            </ol>
            <?php

        } else if (isset($_GET['artist'])) {
            $artist = Artist::from_id($_GET['artist']);
            $albums = $artist->get_albums();
            $songs = $artist->get_songs();

            // Determine initial tab for Artist page
            $initial_artist_tab = !empty($songs) ? 'ArtistSongsTab' : (!empty($albums) ? 'ArtistAlbumsTab' : '');

            ?>
            <div class="artist-page-container">
                <img class="image" src="<?= empty($artist->cover_image_path) ? 'favicon.png' : $artist->cover_image_path ?>" alt="">
                <h1><?= htmlspecialchars($artist->name) ?></h1>
                <button id="shuffle-all-button" class="action-button action-button-play">▶️</button>

                <?php if (!empty($songs) || !empty($albums)) : ?>
                    <!-- Tab Buttons for Artist Page -->
                    <div class="tabs-container">
                        <button class="tab-button <?= $initial_artist_tab === 'ArtistSongsTab' ? 'active' : '' ?>" 
                                onclick="openTab(event, 'ArtistSongsTab')">Songs (<?= count($songs) ?>)</button>
                        <button class="tab-button <?= $initial_artist_tab === 'ArtistAlbumsTab' ? 'active' : '' ?>" 
                                onclick="openTab(event, 'ArtistAlbumsTab')">Albums (<?= count($albums) ?>)</button>
                    </div>

                    <!-- Tab Content: Songs -->
                    <div id="ArtistSongsTab" class="tab-content <?= $initial_artist_tab === 'ArtistSongsTab' ? 'active' : '' ?>">
                        <?php
                        if (!empty($songs)) {
                            foreach ($songs as $song) {
                                $play_queue[] = $song->to_assoc();
                                echo $song->render(false);
                            }
                        } else {
                            echo "<p>No songs found for this artist.</p>";
                        }
                        ?>
                    </div>

                    <!-- Tab Content: Albums -->
                    <div id="ArtistAlbumsTab" class="tab-content <?= $initial_artist_tab === 'ArtistAlbumsTab' ? 'active' : '' ?>">
                        <?php
                        if (!empty($albums)) {
                            foreach ($albums as $album) {
                                echo $album->render();
                            }
                        } else {
                            echo "<p>No albums found for this artist.</p>";
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        } else if (isset($_GET['account']) && isset($_GET['edit'])) {
            // --- ACCOUNT EDIT PAGE ---
            
            // 1. Check if logged in and if the owner is editing their own account
            if (!$account->logged_in || $account->user->user_id !== $_GET['account']) {
                redirect('./?login'); // Unauthorized access
            }
            
            $user_to_edit = $account->user; // The currently logged-in user object
            $success = null;
            $error = null;
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim($_POST['username'] ?? $user_to_edit->username);
                $email = trim($_POST['email'] ?? $user_to_edit->email);
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                
                // VALIDATION: Check uniqueness for username/email if they changed
                if ($username !== $user_to_edit->username && User::from_username($username)) {
                    $error = "Username is already taken.";
                } elseif ($email !== $user_to_edit->email && User::from_email($email)) {
                    $error = "Email is already registered.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email format.";
                } elseif (!password_verify($current_password, $user_to_edit->password_hash)) {
                    // VALIDATION: Must verify current password to make any changes
                    $error = "Incorrect current password.";
                } else {
                    // Update username and email on the object
                    $user_to_edit->username = $username;
                    $user_to_edit->email = $email;
                    
                    // PASSWORD LOGIC: Only update if a new password was provided and matches confirmation
                    if (!empty($new_password)) {
                        if ($new_password !== $confirm_password) {
                            $error = "New passwords do not match.";
                        } else {
                            $user_to_edit->password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        }
                    }
                    
                    // Attempt to update the database
                    if (!$error && $user_to_edit->update()) {
                        $success = "Account updated successfully!";
                        // Re-fetch the account to refresh the session object (important if username changed)
                        $account = Account::from_token($_SESSION['token']); 
                    } elseif (!$error) {
                        $error = "Database error: Failed to update account.";
                    }
                }
            }
            
            ?>
            <div class="account-page-container">
                <form class="account-form" method="POST">
                    <h2>Edit Account: <?= htmlspecialchars($user_to_edit->username) ?></h2>
                    
                    <?php if ($success) : ?>
                        <p class="form-success"><?= htmlspecialchars($success) ?></p>
                    <?php endif; ?>
                    <?php if ($error) : ?>
                        <p class="form-error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user_to_edit->username) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user_to_edit->email) ?>" required>
                    </div>
                    
                    <hr>
                    
                    <h3>Change Password (Optional)</h3>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Leave blank to keep current password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="current_password">⚠️ Current Password (Required to save changes)</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <button type="submit" class="action-button action-button-play">Save Changes</button>
                    
                    <p class="form-link">
                        <a href="./?account=<?= urlencode($user_to_edit->user_id) ?>">← Back to Library</a>
                    </p>
                </form>
            </div>
            <?php

        } else if (isset($_GET['account'])) {
            // --- ACCOUNT / LIBRARY PAGE ---
            
            $target_user_id = $_GET['account'];
            
            // Assuming User::from_id() handles not found users (e.g., by dying or throwing)
            $target_user = User::from_id($target_user_id);
            
            $is_owner = $account->logged_in && ($account->user->user_id === $target_user->user_id);
            
            // Fetch the user's library content
            $songs = $target_user->get_library_songs();
            $albums = $target_user->get_library_albums();
            $artists = $target_user->get_library_artists();

            $initial_tab = 'AccountSongsTab';
            if (empty($songs) && !empty($albums)) {
                $initial_tab = 'AccountAlbumsTab';
            } elseif (empty($songs) && empty($albums) && !empty($artists)) {
                $initial_tab = 'AccountArtistsTab';
            }

            ?>
            <div class="account-profile-header">
                <img class="image" src="account placeholder.png" alt="Profile Picture"> 
                <h1><?= htmlspecialchars($target_user->username) ?>'s Account</h1>
                
                <?php if ($is_owner) : ?>
                    <div class="owner-actions">
                        <a href="./?account=<?= urlencode($target_user->user_id) ?>&edit" class="action-button action-button-shuffle">⚙️ Edit Account</a>
                        <a href="./?logout" class="action-button action-button-play">👋 Logout</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="library-container">
                <h2>Library</h2>
                
                <?php if (empty($songs) && empty($albums) && empty($artists)) : ?>
                    <p class="search-message">This user's library is empty.</p>
                <?php else : ?>
                    <div class="tabs-container">
                        <button class="tab-button <?= $initial_tab === 'AccountSongsTab' ? 'active' : '' ?>" 
                                onclick="openTab(event, 'AccountSongsTab')">Songs (<?= count($songs) ?>)</button>
                        <button class="tab-button <?= $initial_tab === 'AccountAlbumsTab' ? 'active' : '' ?>" 
                                onclick="openTab(event, 'AccountAlbumsTab')">Albums (<?= count($albums) ?>)</button>
                    </div>

                    <div id="AccountSongsTab" class="tab-content <?= $initial_tab === 'AccountSongsTab' ? 'active' : '' ?>">
                        <?php
                        if (!empty($songs)) {
                            foreach ($songs as $song) {
                                echo $song->render(false);
                            }
                        } else {
                            echo "<p>No songs in the library.</p>";
                        }
                        ?>
                    </div>

                    <div id="AccountAlbumsTab" class="tab-content <?= $initial_tab === 'AccountAlbumsTab' ? 'active' : '' ?>">
                        <?php
                        if (!empty($albums)) {
                            foreach ($albums as $album) {
                                echo $album->render(); 
                            }
                        } else {
                            echo "<p>No albums in the library.</p>";
                        }
                        ?>
                    </div>
                <?php endif; ?>

            </div>
            <?php
        } else if (isset($_GET['login'])) {
            $error = null;
            
            if ($account->logged_in) {
                redirect($melody_root);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username_or_email = trim($_POST['username_or_email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (empty($username_or_email) || empty($password)) {
                    $error = "Username/Email and password are required.";
                } else {
                    $user = User::from_username_or_email($username_or_email);

                    if (!$user || !password_verify($password, $user->password_hash)) {
                        $error = "Invalid username/email or password.";
                    } else {
                        $uuid = Token::new_uuid();
                        $token_hash = Token::hash($uuid);
                        
                        $token = new Token(
                            $uuid,
                            $user->user_id,
                            $token_hash,
                            TokenType::SESSION,
                            (new DateTime())->modify('+1 day'),
                            new DateTime()
                        );
                        $token->create();

                        $_SESSION['token'] = $token->token_hash;

                        redirect($melody_root);
                    }
                }
            }
            
            ?>
            <div class="account-page-container">
                <form class="account-form" method="POST">
                    <h2>Login to Melody Vault</h2>
                    
                    <?php if ($error) : ?>
                        <p class="form-error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username_or_email">Username or Email</label>
                        <input type="text" id="username_or_email" name="username_or_email" value="<?= htmlspecialchars($_POST['username_or_email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="action-button action-button-play">Login</button>
                    
                    <p class="form-link">
                        Don't have an account? <a href="./?register">Register here</a>
                    </p>
                </form>
            </div>
            <?php
        } else if (isset($_GET['register'])) {
            $error = null;
            
            if ($account->logged_in) {
                redirect($melody_root);
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim($_POST['username'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';

                if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                    $error = "All fields are required.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email format.";
                } elseif ($password !== $confirm_password) {
                    $error = "Passwords do not match.";
                } elseif (User::from_username($username)) {
                    $error = "Username is already taken.";
                } elseif (User::from_email($email)) {
                    $error = "Email is already registered.";
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    $user = new User(
                        User::new_uuid(),
                        $username,
                        $email,
                        $password_hash,
                        0, 0, false, null, new DateTime(),
                    );
                    $user->create();

                    $uuid = Token::new_uuid();
                    $token_hash = Token::hash($uuid);
      
                    $token = new Token(
                        $uuid,
                        $user->user_id,
                        $token_hash,
                        TokenType::SESSION,
                        (new DateTime())->modify('+1 day'),
                        new DateTime()
                    );
                    $token->create();

                    $_SESSION['token'] = $token->token_hash;

                    redirect($melody_root);
                }
            }

            ?>
            <div class="account-page-container">
                <form class="account-form" method="POST">
                    <h2>Create a Melody Vault Account</h2>
                    
                    <?php if ($error) : ?>
                        <p class="form-error"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="action-button action-button-play">Register</button>
                    
                    <p class="form-link">
                        Already have an account? <a href="./?login">Login here</a>
                    </p>
                </form>
            </div>
            <?php

        } else {
            // Search Results View - Tabbed
            $query = $_GET['search'] ?? '';
            $emptyQuery = empty($query);

            if ($emptyQuery && isset($_GET['search'])) {
                redirect($melody_root);
            }
            
            $song_ids = search('Song', 'song_id', 'title', $emptyQuery, !$emptyQuery);
            $album_ids = search('Album', 'album_id', 'title', $emptyQuery, !$emptyQuery);
            $artist_ids = search('Artist', 'artist_id', 'name', $emptyQuery, !$emptyQuery);

            $initial_tab = 'SongsTab';
            if (empty($song_ids) && !empty($album_ids)) {
                $initial_tab = 'AlbumsTab';
            } elseif (empty($song_ids) && empty($album_ids) && !empty($artist_ids)) {
                $initial_tab = 'ArtistsTab';
            }

            if (empty($song_ids) && empty($album_ids) && empty($artist_ids) && !$emptyQuery) {
                ?>
                <b class="search-message">Sorry no results found for "<?= htmlspecialchars($query) ?>"</b>
                <?php
            } else {
                // --- TAB BUTTONS ---
                ?>
                <div class="tabs-container">
                    <?php if (!empty($song_ids)): ?>
                        <button class="tab-button <?= $initial_tab === 'SongsTab' ? 'active' : '' ?>" onclick="openTab(event, 'SongsTab')">Songs (<?= count($song_ids) ?>)</button>
                    <?php endif ?>
                    <?php if (!empty($album_ids)): ?>
                        <button class="tab-button <?= $initial_tab === 'AlbumsTab' ? 'active' : '' ?>" onclick="openTab(event, 'AlbumsTab')">Albums (<?= count($album_ids) ?>)</button>
                    <?php endif ?>
                    <?php if (!empty($artist_ids)): ?>
                        <button class="tab-button <?= $initial_tab === 'ArtistsTab' ? 'active' : '' ?>" onclick="openTab(event, 'ArtistsTab')">Artists (<?= count($artist_ids) ?>)</button>
                    <?php endif ?>
                </div>
                <?php
            }

            // --- TAB CONTENT: SONGS ---
            if (!empty($song_ids)) {
                ?>
                <div id="SongsTab" class="tab-content <?= $initial_tab === 'SongsTab' ? 'active' : '' ?>">
                    <h3>Songs</h3>
                <?php
                foreach ($song_ids as $id) {
                    $song = Song::from_id($id);
                    echo $song->render(false);
                    $play_queue[] = $song->to_assoc(); // Add song to cookie queue
                }
                ?>
                </div>
                <?php
            }

            // --- TAB CONTENT: ALBUMS ---
            if (!empty($album_ids)) {
            ?>
                <div id="AlbumsTab" class="tab-content <?= $initial_tab === 'AlbumsTab' ? 'active' : '' ?>">
                    <h3>Albums</h3>
                <?php
                foreach ($album_ids as $id) {
                    echo Album::from_id($id)->render();
                }
                ?>
                </div>
                <?php
            }

            // --- TAB CONTENT: ARTISTS ---
            if (!empty($artist_ids)) {
            ?>
                <div id="ArtistsTab" class="tab-content <?= $initial_tab === 'ArtistsTab' ? 'active' : '' ?>">
                    <h3>Artists</h3>
                <?php
                foreach ($artist_ids as $id) {
                    echo Artist::from_id($id)->render();
                }
                ?>
                </div>
                <?php
            }
        }

        ?>
    </div>
    <script><?= file_get_contents(__DIR__ . '/scripts.js') ?></script>
    <pre id="play-queue-data" style="display: none"><?= json_encode($play_queue, JSON_UNESCAPED_SLASHES | JSON_HEX_AMP) ?></pre>
</body>
</html>
