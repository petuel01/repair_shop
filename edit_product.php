<!-- filepath: c:\xampp\htdocs\repair_shop\edit_product.php -->
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
        ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" name="image">
        </div>
        <?php
    } else {
        echo "Product not found.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>