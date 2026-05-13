<?php
class ProductService
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM product_by_category");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByCategory(string $category): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM product_by_category WHERE category = ?"
        );
        $stmt->bind_param("s", $category);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
