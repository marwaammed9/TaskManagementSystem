<?php
require_once __DIR__ . '/Database.php';

class Task
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function save(int $userId, string $title, string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $title, $description);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update(int $id, string $title, string $description, string $status, int $userId, bool $isAdmin): bool
    {
        if ($isAdmin) {
            $stmt = $this->db->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=?");
            $stmt->bind_param("sssi", $title, $description, $status, $id);
        } else {
            $stmt = $this->db->prepare("UPDATE tasks SET title=?, description=?, status=? WHERE id=? AND user_id=?");
            $stmt->bind_param("sssii", $title, $description, $status, $id, $userId);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete(int $id, int $userId, bool $isAdmin): bool
    {
        if ($isAdmin) {
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id=?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
            $stmt->bind_param("ii", $id, $userId);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id=? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $tasks;
    }

    public function getAllTasks(): array
    {
        $stmt = $this->db->prepare(
            "SELECT tasks.*, users.first_name, users.last_name 
         FROM tasks JOIN users ON tasks.user_id = users.id 
         ORDER BY tasks.created_at DESC"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $tasks;
    }

    public function getRecentPending(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT tasks.*, users.first_name, users.last_name 
             FROM tasks JOIN users ON tasks.user_id = users.id 
             WHERE tasks.status='pending' 
             ORDER BY tasks.created_at DESC LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $tasks;
    }

    public function getById(int $id): array|false|null
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }
}
