<?php

require_once __DIR__ . '/_classes.php';


class BorrowedAccess {
    public function __construct(
        public string $access_id,
        public string $lender_user_id,
        public string $borrower_user_id,
        public string $song_id,
        // Removed $accessGrantedDate as it is not in the database schema.
        public DateTime $expiry_date,
       
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
        
        $stmt = $db->prepare("SELECT * FROM `BorrowedAccess` WHERE `access_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No BorrowedAccess found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `BorrowedAccess` WHERE `access_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['access_id'],
            $result['lender_user_id'],
            $result['borrower_user_id'],
            $result['song_id'],
            new DateTime($result['expiry_date']),
        );
    }

    public function create(): void {
        global $db;

        $expiry_date_str = $this->expiry_date->format('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO `BorrowedAccess` (`access_id`, `lender_user_id`, `borrower_user_id`, `song_id`, `expiry_date`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            'sssss',
            $this->access_id,
            $this->lender_user_id,
            $this->borrower_user_id,
            $this->song_id,
            $expiry_date_str,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;

        $expiry_date_str = $this->expiry_date->format('Y-m-d H:i:s');

        $stmt = $db->prepare("UPDATE `BorrowedAccess` SET `lender_user_id`=?, `borrower_user_id`=?, `song_id`=?, `expiry_date`=? WHERE `access_id`=?");
        $stmt->bind_param(
            'sssss',
            $this->lender_user_id,
            $this->borrower_user_id,
            $this->song_id,
            $expiry_date_str,
            $this->access_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `BorrowedAccess` WHERE `access_id`=?");
        $stmt->bind_param('s', $this->access_id);
        $stmt->execute();
    }
}
