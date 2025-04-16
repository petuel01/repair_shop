<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php include 'db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                $image_path = $target_file; // Save the file path
            } else {
                echo "<div class='alert alert-danger'>Error uploading the image.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid file type. Only JPG, PNG, and GIF are allowed.</div>";
        }
    }

    // Check if the product already exists
    $stmt = $conn->prepare("SELECT id, stock FROM products WHERE name = ? AND category = ?");
    $stmt->bind_param("ss", $name, $category);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product exists, update the stock
        $row = $result->fetch_assoc();
        $new_stock = $row['stock'] + $stock;

        $update_stmt = $conn->prepare("UPDATE products SET stock = ?, price = ?, image = ? WHERE id = ?");
        $update_stmt->bind_param("dssi", $new_stock, $price, $image_path, $row['id']);

        if ($update_stmt->execute()) {
            echo "<div class='alert alert-success'>Product stock updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $update_stmt->error . "</div>";
        }
        $update_stmt->close();
    } else {
        // Product does not exist, insert a new record
        $insert_stmt = $conn->prepare("INSERT INTO products (name, category, stock, price, image) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssids", $name, $category, $stock, $price, $image_path);

        if ($insert_stmt->execute()) {
            echo "<div class='alert alert-success'>Product added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $insert_stmt->error . "</div>";
        }
        $insert_stmt->close();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLINTECH ENTERPRISE</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script><!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">Add Product</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category:</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock:</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price:</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image:</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100">Add</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>