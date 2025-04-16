<!-- filepath: repair_shop/index.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch today's sales
$date = date('Y-m-d');
$stmt = $conn->prepare("SELECT SUM(total_price) AS daily_sales FROM sales WHERE DATE(created_at) = ?");
if ($stmt) {
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $daily_sales = $result->fetch_assoc()['daily_sales'] ?? 0;
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch total amount of money in the system
$total_money_stmt = $conn->prepare("SELECT SUM(total_price) AS total_money FROM sales");
if ($total_money_stmt) {
    $total_money_stmt->execute();
    $total_money_result = $total_money_stmt->get_result();
    $total_money = $total_money_result->fetch_assoc()['total_money'] ?? 0;
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch total products
$total_products_stmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products");
if ($total_products_stmt) {
    $total_products_stmt->execute();
    $total_products_result = $total_products_stmt->get_result();
    $total_products = $total_products_result->fetch_assoc()['total_products'] ?? 0;
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch products below 2 in stock
$low_stock_stmt = $conn->prepare("SELECT COUNT(*) AS low_stock FROM products WHERE stock < 2");
if ($low_stock_stmt) {
    $low_stock_stmt->execute();
    $low_stock_result = $low_stock_stmt->get_result();
    $low_stock = $low_stock_result->fetch_assoc()['low_stock'] ?? 0;
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch total daily repairs
$daily_repairs_stmt = $conn->prepare("SELECT COUNT(*) AS daily_repairs FROM repairs WHERE DATE(created_at) = ?");
if ($daily_repairs_stmt) {
    $daily_repairs_stmt->bind_param("s", $date);
    $daily_repairs_stmt->execute();
    $daily_repairs_result = $daily_repairs_stmt->get_result();
    $daily_repairs = $daily_repairs_result->fetch_assoc()['daily_repairs'] ?? 0;
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch total products sold
$total_products_sold_stmt = $conn->prepare("SELECT SUM(quantity) AS total_products_sold FROM sales");
if ($total_products_sold_stmt) {
    $total_products_sold_stmt->execute();
    $total_products_sold_result = $total_products_sold_stmt->get_result();
    $total_products_sold = $total_products_sold_result->fetch_assoc()['total_products_sold'] ?? 0;
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch recent sales as activities
$activities_stmt = $conn->prepare("SELECT CONCAT('Sale of $', total_price) AS description, created_at FROM sales ORDER BY created_at DESC LIMIT 5");
if ($activities_stmt) {
    $activities_stmt->execute();
    $activities_result = $activities_stmt->get_result();
    $recent_activities = $activities_result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch sales data for the chart
$sales_chart_stmt = $conn->prepare("SELECT DATE(created_at) AS sale_date, SUM(total_price) AS total_sales FROM sales GROUP BY DATE(created_at) ORDER BY sale_date DESC LIMIT 7");
if ($sales_chart_stmt) {
    $sales_chart_stmt->execute();
    $sales_chart_result = $sales_chart_stmt->get_result();
    $sales_data = [];
    while ($row = $sales_chart_result->fetch_assoc()) {
        $sales_data[] = $row;
    }
} else {
    die("Error preparing statement for sales chart: " . $conn->error);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include "sidebar.php"; ?>

<div class="container mt-4">

    <!-- Summary Boxes -->
    <div class="row text-center mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Money</h5>
                    <h3>$<?php echo number_format($total_money, 2); ?></h3>
                </div>
            </div>
        </div>
        <br>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Products</h5>
                    <h3><?php echo $total_products; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>FINISHING</h5>
                    <h3><?php echo $low_stock; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Total Daily Repairs</h5>
                    <h3><?php echo $daily_repairs; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Total Products Sold</h5>
                    <h3><?php echo $total_products_sold; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5>Total Amount Sold Today</h5>
                    <h3>$<?php echo number_format($daily_sales, 2); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="mt-5">
        <h3 class="text-center">Sales Overview (Last 7 Days)</h3>
        <canvas id="salesChart"></canvas>
    </div>

    <!-- Recent Activities Section -->
    <div class="mt-5">
        <h3 class="text-center">Recent Activities</h3>
        <ul class="list-group">
            <?php foreach ($recent_activities as $activity): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($activity['description']); ?></strong>
                    <span class="text-muted float-end"><?php echo htmlspecialchars($activity['created_at']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Prepare sales data for the chart
    const salesData = <?php echo json_encode(array_reverse($sales_data)); ?>;
    const labels = salesData.map(data => data.sale_date);
    const data = salesData.map(data => data.total_sales);

    // Render the chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales ($)',
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
</body>
</html>