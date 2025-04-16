<!-- filepath: c:\xampp\htdocs\repair_shop\save_product.php -->
<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $category = htmlspecialchars($_POST['category']);
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            }
        }
    }

    // Update product in the database
    if ($image_path) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, stock = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssidsi", $name, $category, $stock, $price, $image_path, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, stock = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssidi", $name, $category, $stock, $price, $id);
    }

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?><!-- filepath: c:\xampp\htdocs\repair_shop\delete_product.php -->
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