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
    ) {}

    public static function from_id(mysqli $connection, string $transaction_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Transactions` WHERE `transaction_id` = ?');
        $statement->bind_param('s', $transaction_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Transactions` (`transaction_id`, `user_id`, `amount`, `type`, `category`, `description`, `transaction_date`, `receipt_image_url`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->bind_param('ssdsssss', $this->transaction_id, $this->user_id, $this->amount, $this->type, $this->category, $this->description, $this->transaction_date, $this->receipt_image_url);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Transactions` SET `user_id` = ?, `amount` = ?, `type` = ?, `category` = ?, `description` = ?, `transaction_date` = ?, `receipt_image_url` = ? WHERE `transaction_id` = ?');
        $statement->bind_param('sdssssss', $this->user_id, $this->amount, $this->type, $this->category, $this->description, $this->transaction_date, $this->receipt_image_url, $this->transaction_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Transactions` WHERE `transaction_id` = ?');
        $statement->bind_param('s', $this->transaction_id);
        return $statement->execute();
    }
}
