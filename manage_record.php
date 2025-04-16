<!-- filepath: c:\xampp\htdocs\repair_shop\manage_record.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Get table name, action, and ID from the URL
$table = htmlspecialchars($_GET['table'] ?? '');
$action = htmlspecialchars($_GET['action'] ?? '');
$id = intval($_GET['id'] ?? 0);

// Validate inputs
if (empty($table) || empty($action) || $id <= 0) {
    die("Invalid request.");
}

// Fetch the record for view or edit
if ($action === 'view' || $action === 'edit') {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    if (!$record) {
        die("Record not found.");
    }
}

// Handle delete action
if ($action === 'delete') {
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Record deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting record: " . $stmt->error . "</div>";
    }
    $stmt->close();
    exit();
}

// Handle edit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $columns = array_keys($record);
    $update_query = "UPDATE $table SET ";
    $params = [];
    $types = '';

    foreach ($columns as $column) {
        if ($column !== 'id') {
            $update_query .= "$column = ?, ";
            $params[] = htmlspecialchars($_POST[$column] ?? $record[$column]);
            $types .= is_numeric($record[$column]) ? 'd' : 's';
        }
    }

    $update_query = rtrim($update_query, ', ') . " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Record updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating record: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if ($action === 'view'): ?>
        <h3>View Record</h3>
        <table class="table table-bordered">
            <?php foreach ($record as $key => $value): ?>
                <tr>
                    <th><?php echo htmlspecialchars($key); ?></th>
                    <td><?php echo htmlspecialchars($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
    <?php elseif ($action === 'edit'): ?>
        <h3>Edit Record</h3>
        <form method="POST">
            <?php foreach ($record as $key => $value): ?>
                <?php if ($key !== 'id'): ?>
                    <div class="mb-3">
                        <label for="<?php echo htmlspecialchars($key); ?>" class="form-label"><?php echo htmlspecialchars($key); ?></label>
                        <input type="text" class="form-control" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
        </form>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<a href="manage_record.php?table=products&action=view&id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
<a href="manage_record.php?table=products&action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="manage_record.php?table=products&action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>