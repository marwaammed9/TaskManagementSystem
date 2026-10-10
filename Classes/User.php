<?php
require_once __DIR__ . '/Database.php';

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function save(string $firstName, string $lastName, string $email, string $password): bool
    {
        if ($this->emailExists($email)) return false;
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, 'user')");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashed);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function login(string $email, string $password): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($user && password_verify($password, $user['password'])) return $user;
        return false;
    }

    public function update(int $id, string $firstName, string $lastName, string $email): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $firstName, $lastName, $email, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getAllUsers(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, first_name, last_name, email, role, created_at 
         FROM users ORDER BY created_at DESC"
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $user;
    }
    public function updatePassword(int $id, string $currentPassword, string $newPassword): bool|string
    {
        //  الباسورد الحالي
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // تحقق من الباسورد الحالي
        if (!password_verify($currentPassword, $user['password'])) {
            return "كلمة المرور الحالية غير صحيحة.";
        }

        // حدّث الباسورد
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt   = $this->db->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
}
