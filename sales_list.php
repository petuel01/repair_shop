<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!-- filepath: repair_shop/sales_list.php -->
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLINTECH ENTERPRISE</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="display-4">Sales List</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM sales");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['product_id']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['total_price']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <a href='manage_record.php?table=sales&action=view&id={$row['id']}' class='btn btn-info btn-sm'>
                                    <i class='fas fa-eye'></i>
                                </a>
                                <a href='manage_record.php?table=sales&action=edit&id={$row['id']}' class='btn btn-warning btn-sm'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='manage_record.php?table=sales&action=delete&id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this record?');\">
                                    <i class='fas fa-trash'></i>
                                </a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>