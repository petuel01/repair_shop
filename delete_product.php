<!-- filepath: c:\xampp\htdocs\repair_shop\delete_product.php -->
<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Product deleted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>