<?php
// @phpstan-ignore-next-line
require_once __DIR__ . '/../config.php';

class Database
{
    private static ?Database $instance = null;
    private mysqli $connection;

    private function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public function prepare(string $sql): mysqli_stmt|false
    {
        return $this->connection->prepare($sql);
    }

    public function getLastId(): int
    {
        return $this->connection->insert_id;
    }
}
