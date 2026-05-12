<?php

require_once __DIR__ . '/_classes.php';

class Transaction {
    public function __construct(
        public string $transaction_id,
        public string $user_id,
        public string $txn_type,
        public string $currency,
        public int $amount,
        public ?string $related_item_id,
        public DateTime $txn_timestamp,
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
        
        $stmt = $db->prepare("SELECT * FROM `Transaction` WHERE `txn_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
         
        return $stmt->get_result()->num_rows > 0;
    }

    public static function from_id(string $id): self {
        global $db;
        
        if (!self::is_id_in_use($id)) {
            die("No Transaction found with id $id");
        }

        $stmt = $db->prepare("SELECT * FROM `Transaction` WHERE `txn_id`=?");
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return new self(
            $result['txn_id'],
            $result['user_id'],
            $result['txn_type'],
            $result['currency'],
            $result['amount'],
            $result['related_item_id'],
            new DateTime($result['txn_timestamp']),
        );
    }

    public function create(): void {
        global $db;

        $txn_timestamp_str = $this->txn_timestamp->format('Y-m-d H:i:s');

        $stmt = $db->prepare("INSERT INTO `Transaction` (`txn_id`, `user_id`, `txn_type`, `currency`, `amount`, `related_item_id`, `txn_timestamp`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            'ssssiss',
            $this->transaction_id,
            $this->user_id,
            $this->txn_type,
            $this->currency,
            $this->amount,
            $this->related_item_id,
            $txn_timestamp_str,
        );
        $stmt->execute();
    }

    public function update(): void {
        global $db;
        
        $txn_timestamp_str = $this->txn_timestamp->format('Y-m-d H:i:s');

        $stmt = $db->prepare("UPDATE `Transaction` SET `user_id`=?, `txn_type`=?, `currency`=?, `amount`=?, `related_item_id`=?, `txn_timestamp`=? WHERE `txn_id`=?");
        $stmt->bind_param(
            'ssssiss',
            $this->user_id,
            $this->txn_type,
            $this->currency,
            $this->amount,
            $this->related_item_id,
            $txn_timestamp_str,
            $this->transaction_id,
        );
        $stmt->execute();
    }

    public function delete(): void {
        global $db;

        $stmt = $db->prepare("DELETE FROM `Transaction` WHERE `txn_id`=?");
        $stmt->bind_param('s', $this->transaction_id);
        $stmt->execute();
    }
}