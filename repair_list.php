<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!-- filepath: repair_shop/repair_list.php -->
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script><!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="container mt-5">
    <h1 class="mb-4">Repair List</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Device</th>
                <th>Issue Description</th>
                <th>Status</th>
                <th>Repair Cost</th>
                <th>Date</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM repairs");
            while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['device']}</td>
                <td>{$row['issue_description']}</td>
                <td>";
            if ($row['payment_status'] === 'Completed') {
                echo "<button class='btn btn-success btn-sm' disabled>Completed</button>";
            } else {
                echo "<form method='POST' action='update_status.php' style='display:inline;'>
                    <input type='hidden' name='repair_id' value='{$row['id']}'>
                    <button type='submit' class='btn btn-primary btn-sm'>Mark as Completed</button>
                  </form>";
            }
            echo "</td>
                <td>{$row['repair_cost']}</td>
                <td>{$row['created_at']}</td>";
            if (!empty($row['image'])) {
                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='Repair Image' class='img-thumbnail' style='width: 100px; height: auto;'></td>";
            } else {
                echo "<td>No image available</td>";
            }
            echo "<td>
                <a href='manage_record.php?table=repairs&action=view&id={$row['id']}' class='btn btn-info btn-sm'>
                    <i class='fas fa-eye'></i>
                </a>
                <a href='manage_record.php?table=repairs&action=edit&id={$row['id']}' class='btn btn-warning btn-sm'>
                    <i class='fas fa-edit'></i>
                </a>
                <a href='manage_record.php?table=repairs&action=delete&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'>
                    <i class='fas fa-trash'></i>
                </a>
            </td>";
            echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>