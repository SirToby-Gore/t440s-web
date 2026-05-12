<?php

require_once '_classes.php';

class Report
{
    public function __construct(
        public string $report_id,
        public string $manager_id,
        public string $title,
        public string $type,
        public string $content,
        public string $generated_at,
    ) {}

    public static function from_id(mysqli $connection, string $report_id): ?self
    {
        $statement = $connection->prepare('SELECT * FROM `Reports` WHERE `report_id` = ?');
        $statement->bind_param('s', $report_id);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(mysqli $connection): bool
    {
        $statement = $connection->prepare('INSERT INTO `Reports` (`report_id`, `manager_id`, `title`, `type`, `content`, `generated_at`) VALUES (?, ?, ?, ?, ?, ?)');
        $statement->bind_param('ssssss', $this->report_id, $this->manager_id, $this->title, $this->type, $this->content, $this->generated_at);
        return $statement->execute();
    }

    public function update(mysqli $connection): bool
    {
        $statement = $connection->prepare('UPDATE `Reports` SET `manager_id` = ?, `title` = ?, `type` = ?, `content` = ?, `generated_at` = ? WHERE `report_id` = ?');
        $statement->bind_param('ssssss', $this->manager_id, $this->title, $this->type, $this->content, $this->generated_at, $this->report_id);
        return $statement->execute();
    }

    public function delete(mysqli $connection): bool
    {
        $statement = $connection->prepare('DELETE FROM `Reports` WHERE `report_id` = ?');
        $statement->bind_param('s', $this->report_id);
        return $statement->execute();
    }
}
