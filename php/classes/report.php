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

    public static function from_id(string $report_id): ?self
    {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM `Reports` WHERE `report_id` = ?');
        $stmt->bind_param('s', $report_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? new self(...$result) : null;
    }

    public function create(): bool
    {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO `Reports` (`report_id`, `manager_id`, `title`, `type`, `content`, `generated_at`) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $this->report_id, $this->manager_id, $this->title, $this->type, $this->content, $this->generated_at);
        return $stmt->execute();
    }

    public function update(): bool
    {
        global $conn;
        $stmt = $conn->prepare('UPDATE `Reports` SET `manager_id` = ?, `title` = ?, `type` = ?, `content` = ?, `generated_at` = ? WHERE `report_id` = ?');
        $stmt->bind_param('ssssss', $this->manager_id, $this->title, $this->type, $this->content, $this->generated_at, $this->report_id);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        global $conn;
        $stmt = $conn->prepare('DELETE FROM `Reports` WHERE `report_id` = ?');
        $stmt->bind_param('s', $this->report_id);
        return $stmt->execute();
    }
}
