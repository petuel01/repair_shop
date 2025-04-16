<!-- filepath: c:\xampp\htdocs\repair_shop\view_product.php -->
<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo "<p><strong>Name:</strong> " . htmlspecialchars($product['name']) . "</p>";
        echo "<p><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</p>";
        echo "<p><strong>Stock:</strong> " . htmlspecialchars($product['stock']) . "</p>";
        echo "<p><strong>Price:</strong> $" . htmlspecialchars($product['price']) . "</p>";
        if (!empty($product['image'])) {
            echo "<img src='" . htmlspecialchars($product['image']) . "' alt='Product Image' style='width:150px;height:auto;'>";
        } else {
            echo "<p>No image available</p>";
        }
    } else {
        echo "Product not found.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>