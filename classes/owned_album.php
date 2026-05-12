<?php

require_once __DIR__ . '/_classes.php';

class OwnedAlbum {
    public function __construct(
        public string $ownership_id,
        public string $user_id,
        public string $album_id,
        public DateTime $acquisition_date,
    ) {}

    public static function new_uuid(): string {
        while (true) {
            $uuid = generate_uuid();

            if (!self::is_id_in_use($uuid)) {
                return $uuid;
            }
        }
    }

    public static function is_id_in_use(string $id): bool {
        global $db;
        
        $stmt = $db->prepare("SELECT * FROM `OwnedAlbum` WHERE `ownership_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No OwnedAlbum found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `OwnedAlbum` WHERE `ownership_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['ownership_id'],
            $result['user_id'],
            $result['album_id'],
            new DateTime($result['acquisition_date']),
        );
    }

    public function create(): void {
        global $db;

        $acquisition_date_str = $this->acquisition_date->format('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO `OwnedAlbum` (`ownership_id`, `user_id`, `album_id`, `acquisition_date`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param(
            'ssss',
            $this->ownership_id,
            $this->user_id,
            $this->album_id,
            $acquisition_date_str,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;
        
        $acquisition_date_str = $this->acquisition_date->format('Y-m-d H:i:s');

        $stmt = $db->prepare("UPDATE `OwnedAlbum` SET `user_id`=?, `album_id`=?, `acquisition_date`=? WHERE `ownership_id`=?");
        $stmt->bind_param(
            'ssss',
            $this->user_id,
            $this->album_id,
            $acquisition_date_str,
            $this->ownership_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `OwnedAlbum` WHERE `ownership_id`=?");
        $stmt->bind_param('s', $this->ownership_id);
        $stmt->execute();
    }
}