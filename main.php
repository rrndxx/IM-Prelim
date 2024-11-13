    <?php
    session_start();

    if (!isset($_SESSION['admin'])) {
        header('location: adminlogin.php');
    }
    require_once('connection.php');

    $newConnection->addProduct();
    $newConnection->editProduct();
    $newConnection->deleteProduct();
    $newConnection->addCategory();
    $products = [];
    $users = [];
    $categories = $newConnection->getCategories();

    if (isset($_POST['filterProducts'])) {
        $selectedCategory = $_POST['selectedCategory'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        $products = $newConnection->filterProducts($selectedCategory, $startDate, $endDate);
    } elseif (isset($_POST['searchbutton'])) {
        $products = $newConnection->searchProduct();
    } elseif (isset($_POST['instock'])) {
        $products = $newConnection->inStock();
    } elseif (isset($_POST['outofstock'])) {
        $products = $newConnection->outofStock();
    } else {
        $connection = $newConnection->openConnection();
        $stmnt = $connection->prepare("SELECT * FROM products");
        $stmnt->execute();
        $products = $stmnt->fetchAll();
    }

    $connection = $newConnection->openConnection();
    $stmnt = $connection->prepare("SELECT * FROM users WHERE role = 'Customer'");
    $stmnt->execute();
    $users = $stmnt->fetchAll();

    if (isset($_POST['logout'])) {
        session_start();
        session_destroy();
        header('location: adminlogin.php');
        exit();
    }

    $connection = $newConnection->openConnection();
    $stmnt = $connection->prepare(
        "SELECT CONCAT(users.first_name, ' ', users.last_name) AS customer_name, 
                products.prod_name, 
                cart.quantity 
         FROM cart 
         JOIN products ON cart.product_id = products.id
         JOIN users ON cart.user_id = users.id 
         GROUP BY users.first_name, users.last_name, products.prod_name"
    );
    $stmnt->execute();
    $orders = $stmnt->fetchAll();

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Products Website</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    </head>

    <style>
        body {
            margin: 0;
            padding: 20px;
            background-color: #F4F6F7;
            /* Very light gray background for the body */
            font-family: 'Montserrat', sans-serif;
            color: #34495E;
            /* Darker gray text for better contrast */
        }

        .navbar {
            background-color: #2C3E50;
            /* Midnight blue for navbar */
        }

        .navbar-brand {
            font-size: 30px;
            color: #ECF0F1;
            /* Light gray text */
            font-weight: 600;
        }

        .navbar-toggler {
            background-color: #ECF0F1;
            /* Light gray navbar toggler */
        }

        .navbar-nav .nav-link {
            color: #ECF0F1;
            /* Light gray navbar links */
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #BDC3C7;
            /* Slightly lighter gray on hover */
        }

        .table-responsive {
            margin-top: 20px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            /* White background for table container */
        }

        button,
        .tb,
        .modal {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 3px 8px;
        }

        h2 {
            color: #2C3E50;
            /* Midnight blue for headings */
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .table {
            color: #34495E;
            /* Darker gray text for table */
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }

        .table th,
        .table td {
            border: 1px solid #BDC3C7;
            /* Light gray border */
            padding: 15px 20px;
            text-align: center;
        }

        .table thead {
            background-color: #2C3E50;
            /* Midnight blue for table header */
            color: #ECF0F1;
            /* Light gray text for header */
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #ECF0F1;
            /* Light gray background on row hover */
        }

        .btn-primary {
            background-color: #2C3E50;
            /* Midnight blue for primary buttons */
            border-color: #2C3E50;
            transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1A2632;
            /* Darker blue on hover */
            border-color: #1A2632;
        }

        .btn-success {
            background-color: #ECF0F1;
            /* Light gray for success buttons */
            border-color: #ECF0F1;
            transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-success:hover {
            background-color: #BDC3C7;
            /* Slightly darker gray for hover */
            border-color: #BDC3C7;
        }

        .btn-danger {
            background-color: #E74C3C;
            /* Red for danger buttons */
            border-color: #E74C3C;
            transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-danger:hover {
            background-color: #C0392B;
            /* Darker red for hover */
            border-color: #C0392B;
        }

        .btn-warning {
            background-color: #BDC3C7;
            /* Lighter gray for warning buttons */
            border-color: #BDC3C7;
            transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
            color: black;
        }

        .btn-warning:hover {
            background-color: #A6ACB3;
            /* Darker gray for hover */
            border-color: #A6ACB3;
        }

        .text-end form button {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 3px 8px;
        }

        .modal-content {
            background-color: #F4F6F7;
            /* Light gray for modal background */
        }

        .modal-header,
        .modal-footer {
            border-color: #2C3E50;
            /* Midnight blue borders for modal header/footer */
        }

        .d-flex {
            margin-bottom: 30px;
        }

        .table-hover tbody tr {
            transition: background-color 0.3s ease-in-out;
        }

        .table-hover tbody tr:hover {
            background-color: #D5DBDB;
            /* Lighter gray hover effect */
        }

        /* Additional Media Queries for better responsiveness */
        @media (max-width: 768px) {
            .table-responsive {
                margin-top: 10px;
            }

            .navbar-brand {
                font-size: 25px;
            }

            .btn-primary,
            .btn-success,
            .btn-danger,
            .btn-warning {
                padding: 8px 15px;
            }

            .table th,
            .table td {
                padding: 12px 10px;
            }
        }
    </style>

    <body>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <p class="navbar-brand"><?php echo "Welcome, " . $_SESSION['admin'] . "!"; ?>
                    <button class="navbar-toggler bg-success" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </p>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <form class="d-flex ms-auto" method="POST">
                        <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#addCat">Add Category</button>
                        <button type="button" class="btn btn-warning     me-2" data-bs-toggle="modal"
                            data-bs-target="#addModal">Add Product</button>
                        <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal"
                            data-bs-target="#filterModal">Filter</button>
                        <input type="search" class="tb form-control me-2" placeholder="Input product name" name="search"
                            required>
                        <button class="btn btn-warning" type="submit" name="searchbutton">Search</button>
                    </form>
                </div>
            </div>
        </nav>

        <hr class="mb-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-start">PRODUCTS</h2>
            <div class="text-end">
                <form action="" method="POST">
                    <button class="btn btn-warning me-2" type="button"
                        onclick="window.location.href='main.php'">All Products</button>
                    <button class="btn btn-warning me-2" type="submit" name="instock">In Stock</button>
                    <button class="btn btn-danger" type="submit" name="outofstock">Out of Stock</button>
                </form>
            </div>
        </div>

        <!-- PRODUCTS TABLE -->
        <div class="table-responsive mb-4">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category ID</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Purchased Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr class="text-center">
                            <th scope="row"><?php echo $product->id; ?></th>
                            <td><?php echo $product->prod_name; ?></td>
                            <td><?php echo $product->cat_id; ?></td>
                            <td><?php echo $product->cat; ?></td>
                            <td><?php echo $product->quan; ?></td>
                            <td><?php echo $product->date; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <button type="button" class="btn btn-primary me-4 w-25" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $product->id ?>">Edit</button>
                                    <button type="submit" class="btn btn-danger w-25" name="deletebutton"
                                        value="<?php echo $product->id; ?>">Delete</button>
                                </form>
                            </td>
                            <?php include 'modals.php'; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <br>
        <hr class="my-4">

        <div class="mt-4 text-center">
            <h2>USERS</h2>
        </div>

        <!-- USERS TABLE -->
        <div class="table-responsive mt-4">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Birthdate</th>
                        <th>Gender</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Date Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="text-center">
                            <th scope="row"><?php echo $user->first_name; ?></th>
                            <td><?php echo $user->last_name; ?></td>
                            <td><?php echo $user->address; ?></td>
                            <td><?php echo $user->birthdate; ?></td>
                            <td><?php echo $user->gender; ?></td>
                            <td><?php echo $user->username; ?></td>
                            <td><?php echo $user->role; ?></td>
                            <td><?php echo $user->date_created; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <br>
        <hr class="my-4">

        <div class="mt-4 text-center">
            <h2>ORDERS</h2>
        </div>

        <!-- ORDERS TABLE -->
        <div class="table-responsive mt-4 text-center">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order->customer_name; ?></td>
                            <td><?php echo $order->prod_name; ?></td>
                            <td><?php echo $order->quantity; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <form action="" method="POST" class="mt-5">
            <div class="text-end">
                <button class="btn btn-danger" type="submit" name="logout">Logout</button>
            </div>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    </body>

    </html>