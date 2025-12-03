<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'itws2110-fall2025-quiz2';

try {
    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    }

    catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>