<?php
session_start();
require_once('connection.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $connection = $newConnection->openConnection();
    $stmnt = $connection->prepare("SELECT * FROM users WHERE username = ? AND role = 'Admin'");
    $stmnt->execute([$username]);
    $user = $stmnt->fetch();

    if ($user) {
        if ($user->password === $password) {
            $_SESSION['admin'] = $user->role;
            header('Location: main.php');
            exit;
        }
    }
    header('Location: adminlogin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F4F6F7;
            /* Light gray background for consistency */
            font-family: 'Montserrat', sans-serif;
            padding-bottom: 100px;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 6px 15px;
            border-radius: 12px;
            background-color: #fff;
            width: 400px;
            padding: 30px;
        }

        .container h3 {
            font-size: 28px;
            color: #2C3E50;
            /* Midnight blue for heading */
            font-weight: 600;
            text-align: center;
        }

        .form-label {
            color: #34495E;
            /* Slightly lighter gray for labels */
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            color: #34495E;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #2C3E50;
            /* Midnight blue for buttons */
            border-color: #2C3E50;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-success:hover {
            background-color: #1A2632;
            /* Darker blue for hover effect */
            border-color: #1A2632;
        }

        .register-link {
            font-size: 14px;
            text-align: center;
            position: absolute;
            bottom: 20px;
            width: 100%;
            color: #BDC3C7;
        }

        .register-link a {
            color: #2C3E50;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            color: #34495E;
        }
    </style>
</head>

<body>

    <!-- Admin Login Form -->
    <div class="container">
        <h3>Admin Login</h3>
        <form action="adminlogin.php" method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="login">Login</button>
            </div>
        </form>
    </div>

    <!-- Register Link (for customer page) -->
    <div class="register-link">
        <p>Not an admin? <a href="index.php">Go to customer page</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>