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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #707D7C;
            margin-top: 150px;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;
            border-radius: 20px;
        }

        .register-link {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <a href="index.php" class="register-link btn btn-secondary">Customer</a>
    <div class="wews d-flex justify-content-center">
        <div class="container bg-light p-4 text-start" style="height: 350px; width: 400px;">
            <h3 class="mt-4 text-center mb-3">Admin Login</h3>
            <form action="adminlogin.php" method="POST" class="mt-3">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="form-group mt-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success mt-4" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>