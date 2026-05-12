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

    public function login(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, username, password FROM users WHERE username = ?"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result || !password_verify($password, $result['password'])) {
            return null;
        }
        return $result;
    }
}
