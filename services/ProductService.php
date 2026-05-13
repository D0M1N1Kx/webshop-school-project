<?php
class ProductService
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }
}
