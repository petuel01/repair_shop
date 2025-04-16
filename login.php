<!-- filepath: repair_shop/login.php -->
<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = hash('sha256', $_POST['password']); // Hash the password

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #495057;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            background-color: #6c757d;
            color: #ffffff;
        }
        .form-control:focus {
            background-color: #6c757d;
            color: #ffffff;
            border-color: #adb5bd;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
<h1 class="text-center"> LOGIN TO CLINTECH SOLUTIONS</h1>
    <div class="login-container">
        <h1 class="text-center">Login</h1>
        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>