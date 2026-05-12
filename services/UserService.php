<?php
class UserService
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }
}
