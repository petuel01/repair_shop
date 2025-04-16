<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!-- filepath: repair_shop/sell_product.php -->
<?php include 'db.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Fetch product details
    $product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();

    if ($product && $product['stock'] >= $quantity) {
        $total_price = $product['price'] * $quantity;

        // Insert into sales table
        $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, total_price) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $product_id, $quantity, $total_price);
        $stmt->execute();

        // Update product stock
        $new_stock = $product['stock'] - $quantity;
        $conn->query("UPDATE products SET stock = $new_stock WHERE id = $product_id");

        echo "<div class='alert alert-success'>Product sold successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Insufficient stock!</div>";
    }
}

// Fetch all products
$products = $conn->query("SELECT id, name, stock FROM products WHERE stock > 0");
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
    <h1 class="text-center mb-4">Sell Product</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="product_id" class="form-label">Product</label>
                    <select name="product_id" class="form-select" id="product_id" required>
                        <option value="" disabled selected>Select a product</option>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= htmlspecialchars($row['name']) ?> (Stock: <?= $row['stock'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <div class="invalid-feedback">Please select a product.</div>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" id="quantity" required>
                    <div class="invalid-feedback">Please enter a valid quantity.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sell Product</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Bootstrap form validation
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
</body>
</html>