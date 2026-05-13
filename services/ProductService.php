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
}
