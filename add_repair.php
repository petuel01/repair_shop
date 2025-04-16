<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!-- filepath: repair_shop/add_repair.php -->
<?php include 'db.php'; ?>

<?php
$amount_left = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = htmlspecialchars($_POST['customer_name']);
    $contact = htmlspecialchars($_POST['contact']);
    $device = htmlspecialchars($_POST['device']);
    $issue_description = htmlspecialchars($_POST['issue_description']);
    $amount_to_be_paid = floatval($_POST['amount_to_be_paid']);
    $amount_paid = floatval($_POST['amount_paid']);
    $repair_cost = $amount_to_be_paid; // Set repair cost to the amount to be paid

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Calculate the amount left
    $amount_left = $amount_to_be_paid - $amount_paid;

    // Automatically set payment status
    $payment_status = ($amount_paid == $repair_cost) ? 'Completed' : 'Not Completed';

    $stmt = $conn->prepare("INSERT INTO repairs (customer_name, contact, image, device, issue_description, repair_cost, amount_paid, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssbssds", $customer_name, $contact, $image, $device, $issue_description, $repair_cost, $amount_paid, $payment_status);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Repair request added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
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
    <script>
        function calculateAmountLeft() {
            const amountToBePaid = parseFloat(document.getElementById('amount_to_be_paid').value) || 0;
            const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
            const amountLeft = amountToBePaid - amountPaid;
            document.getElementById('amount_left').textContent = amountLeft.toFixed(2);
        }
    </script>
</head>
<body>
<?php include "sidebar.php"; ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">Add Repair Request</h1>
    <div class="card shadow">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" name="contact" id="contact" required>
                </div>
                <div class="mb-3">
                    <label for="device" class="form-label">Device</label>
                    <input type="text" class="form-control" name="device" id="device" required>
                </div>
                <div class="mb-3">
                    <label for="issue_description" class="form-label">Issue Description</label>
                    <textarea class="form-control" name="issue_description" id="issue_description" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="amount_to_be_paid" class="form-label">Amount to Be Paid</label>
                    <input type="number" step="0.01" class="form-control" name="amount_to_be_paid" id="amount_to_be_paid" oninput="calculateAmountLeft()" required>
                </div>
                <div class="mb-3">
                    <label for="amount_paid" class="form-label">Amount Paid</label>
                    <input type="number" step="0.01" class="form-control" name="amount_paid" id="amount_paid" oninput="calculateAmountLeft()" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount Left</label>
                    <p id="amount_left" class="form-control bg-light">0.00</p>
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Repair</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
