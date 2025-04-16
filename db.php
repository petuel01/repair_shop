<!-- filepath: repair_shop/db.php -->
<?php
$host = 'localhost';
$user = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password (empty)
$database = 'repair_shop';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>