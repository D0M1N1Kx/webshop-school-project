<?php
class UserService
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function register(string $username, string $password): bool
    {
        if (strlen($password) < 6) return false;

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $username, $hash);

        if (!$stmt->execute()) return false;

        $userId = $stmt->insert_id;
        // create cart
        return true;
    }
}
