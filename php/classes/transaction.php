<?php

require_once '_classes.php';

class Transaction
{
    public function __construct(
        public string $transaction_id,
        public string $user_id,
        public float $amount,
        public string $type,
        public string $category,
        public ?string $description,
        public string $transaction_date,
        public ?string $receipt_image_url,
    ) {
    }

    public static function from_id(string $transaction_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `Transactions` WHERE `transaction_id` = ?');
        $stmt->bind_param('s', $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `Transactions` (`transaction_id`, `user_id`, `amount`, `type`, `category`, `description`, `transaction_date`, `receipt_image_url`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssdsssss', $this->transaction_id, $this->user_id, $this->amount, $this->type, $this->category, $this->description, $this->transaction_date, $this->receipt_image_url);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `Transactions` SET `user_id` = ?, `amount` = ?, `type` = ?, `category` = ?, `description` = ?, `transaction_date` = ?, `receipt_image_url` = ? WHERE `transaction_id` = ?');
        $stmt->bind_param('sdssssss', $this->user_id, $this->amount, $this->type, $this->category, $this->description, $this->transaction_date, $this->receipt_image_url, $this->transaction_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `Transactions` WHERE `transaction_id` = ?');
        $stmt->bind_param('s', $this->transaction_id);
        return $stmt->execute();
    }

    public static function get_recent_for_user(string $user_id, int $limit = 10): array
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `Transactions` WHERE `user_id` = ? ORDER BY `transaction_date` DESC LIMIT ?');
        $stmt->bind_param('si', $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = new self(...$row);
        }
        return $transactions;
    }
}
