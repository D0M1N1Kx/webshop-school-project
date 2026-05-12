<?php
define("HOST", "127.0.0.1");
define("USER", "dominik");
define("PASSWORD", "jelszo1234");
define("DATABASE", "webshop");

$connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

if ($connection->connect_error)
    die("Connection failed: " . $connection->connect_error);
