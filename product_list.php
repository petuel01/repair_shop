<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!-- filepath: repair_shop/product_list.php -->
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
    <h1 class="text-center mb-4">Product List</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()) {
            $imageData = base64_encode($row['image']); // Encode the blob data
            $imageSrc = "data:image/jpeg;base64,{$imageData}"; // Create the image source
            echo "<tr>
                <td>{$row['id']}</td>
                <td><img src='{$imageSrc}' alt='{$row['name']}' class='img-thumbnail' style='width: 100px; height: 100px;' onclick='viewProduct({$row['id']})'></td>
                <td>{$row['name']}</td>
                <td>{$row['category']}</td>
                <td>{$row['stock']}</td>
                <td>\${$row['price']}</td>
                <td>
                <button class='btn btn-primary btn-sm' onclick='editProduct({$row['id']})'>Edit</button>
                <button class='btn btn-danger btn-sm' onclick='deleteProduct({$row['id']})'>Delete</button>
                </td>
              </tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- View Product Modal -->
        <div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewProductContent">
                <!-- Product details will be loaded here -->
                </div>
            </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="editProductForm">
                    <!-- Form fields will be loaded here -->
                </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">Save Changes</button>
                </div>
            </div>
            </div>
        </div>

        <script>
            function viewProduct(id) {
            fetch(`view_product.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Product details not found');
                    }
                    return response.text();
                })
                .then(data => {
                document.getElementById('viewProductContent').innerHTML = data;
                new bootstrap.Modal(document.getElementById('viewProductModal')).show();
                })
                .catch(error => {
                    alert(error.message);
                });
            }

            function editProduct(id) {
            fetch(`edit_product.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Edit product page not found');
                    }
                    return response.text();
                })
                .then(data => {
                document.getElementById('editProductForm').innerHTML = data;
                new bootstrap.Modal(document.getElementById('editProductModal')).show();
                })
                .catch(error => {
                    alert(error.message);
                });
            }

            function saveProduct() {
            const form = document.getElementById('editProductForm');
            const formData = new FormData(form);
            fetch('save_product.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
            }

            function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                fetch(`delete_product.php?id=${id}`, { method: 'POST' })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Delete product failed');
                    }
                    return response.text();
                })
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => {
                    alert(error.message);
                });
            }
            }
        </script>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>