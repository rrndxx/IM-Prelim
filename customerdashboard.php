<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}

require_once('connection.php');

$cartprods = [];
$categories = $newConnection->getCategories();
$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['addToCart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $connection = $newConnection->openConnection();
    $prodStmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
    $prodStmt->execute([$product_id]);
    $product = $prodStmt->fetch();

    if ($product && $quantity <= $product->quan && $quantity >= 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'prod_name' => $product->prod_name,
                'quantity' => $quantity,
                'cat' => $product->cat
            ];
        }
    } else {
        echo "<script>alert('Insufficient stock or invalid quantity');</script>";
    }
}

if (isset($_POST['removeFromCart'])) {
    $product_id = $_POST['removeFromCart'];
    unset($_SESSION['cart'][$product_id]);
}

$cartprods = [];
foreach ($_SESSION['cart'] as $product_id => $cart_item) {
    $cartprods[] = (object) [
        'id' => $product_id,
        'prod_name' => $cart_item['prod_name'],
        'quantity' => $cart_item['quantity']
    ];
}

if (isset($_POST['checkout'])) {
    $connection = $newConnection->openConnection();

    foreach ($_SESSION['cart'] as $product_id => $cart_item) {
        $quantity = $cart_item['quantity'];

        $prodStmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
        $prodStmt->execute([$product_id]);
        $product = $prodStmt->fetch();

        if ($product && $product->quan >= $quantity) {
            $newStock = $product->quan - $quantity;
            $updateProductStmt = $connection->prepare("UPDATE products SET quan = ? WHERE id = ?");
            $updateProductStmt->execute([$newStock, $product_id]);

            $insertStmt = $connection->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insertStmt->execute([$user_id, $product_id, $quantity]);
        }
    }

    unset($_SESSION['cart']);

    echo "<script>alert('Checkout successful'); window.location = 'customerdashboard.php';</script>";
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
    exit();
}

$connection = $newConnection->openConnection();
$stmnt = $connection->prepare("SELECT * FROM products");
$stmnt->execute();
$products = $stmnt->fetchAll();
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
        font-family: 'Montserrat', sans-serif;
        color: #34495E;
    }

    .navbar {
        background-color: #2C3E50;
    }

    .navbar-brand {
        font-size: 30px;
        color: #ECF0F1;
        font-weight: 600;
    }

    .navbar-toggler {
        background-color: #ECF0F1;
    }

    .navbar-nav .nav-link {
        color: #ECF0F1;
        font-weight: 500;
    }

    .navbar-nav .nav-link:hover {
        color: #BDC3C7;
    }

    .table-responsive {
        margin-top: 20px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        border-radius: 8px;
        overflow: hidden;
        background-color: #fff;
    }

    button,
    .tb,
    .modal {
        box-shadow: rgba(0, 0, 0, 0.1) 0px 3px 8px;
    }

    h2 {
        color: #2C3E50;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .table {
        color: #34495E;
        border-collapse: separate;
        border-spacing: 0 10px;
        width: 100%;
    }

    .table th,
    .table td {
        border: 1px solid #BDC3C7;
        padding: 15px 20px;
        text-align: center;
    }

    .table thead {
        background-color: #2C3E50;
        color: #ECF0F1;
        font-weight: 600;
    }

    .table tbody tr:hover {
        background-color: #ECF0F1;
    }

    .btn-primary {
        background-color: #2C3E50;
        border-color: #2C3E50;
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
        color: white;
    }

    .btn-primary:hover {
        background-color: #1A2632;
        border-color: #1A2632;
    }

    .btn-success {
        background-color: #2C3E50;
        border-color: #ECF0F1;
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
    }

    .btn-success:hover {
        background-color: #BDC3C7;
        border-color: #BDC3C7;
    }

    .btn-danger {
        background-color: #E74C3C;
        border-color: #E74C3C;
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
    }

    .btn-danger:hover {
        background-color: #C0392B;
        border-color: #C0392B;
    }

    .btn-warning {
        background-color: #BDC3C7;
        border-color: #BDC3C7;
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
        color: black;
    }

    .btn-warning:hover {
        background-color: #A6ACB3;
        border-color: #A6ACB3;
    }

    .text-end form button {
        box-shadow: rgba(0, 0, 0, 0.1) 0px 3px 8px;
    }

    .modal-content {
        background-color: #F4F6F7;
    }

    .modal-header,
    .modal-footer {
        border-color: #2C3E50;
    }

    .d-flex {
        margin-bottom: 30px;
    }

    .table-hover tbody tr {
        transition: background-color 0.3s ease-in-out;
        color: black;
    }

    .table-hover tbody tr:hover {
        background-color: #D5DBDB;
        color: black;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        margin-bottom: 20px;
        background-color: #fff;
        /* Added white background for the cards */
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.3);
        background-color: #f8f9fa;
        /* Subtle change to the background color on hover */
    }

    .card-img-top {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        height: 200px;
        /* Consistent image height */
        object-fit: cover;
        /* Ensures the image maintains its aspect ratio */
    }

    .card-body {
        padding: 15px;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2C3E50;
        margin-bottom: 10px;
    }

    .card-text {
        color: #7F8C8D;
        margin-bottom: 15px;
    }

    .card-footer {
        background-color: transparent;
        border: none;
    }

    /* Add hover effect to the buttons inside the cards */
    .card .btn-success {
        background-color: #27ae60;
        border-color: #27ae60;
        transition: background-color 0.3s ease-in-out, border-color 0.3s ease-in-out;
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
    }

    .card .btn-success:hover {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }

    /* Box shadow effect for the product title and stock */
    .card-body .d-flex {
        box-shadow: rgba(0, 0, 0, 0.1) 0px 3px 8px;
        border-radius: 8px;
        padding: 10px;
    }

    .card-columns {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 15px;
    }

    .card-columns .col-md-4 {
        flex: 1 1 calc(33.333% - 15px);
        max-width: calc(33.333% - 15px);
    }

    .card-columns .col-md-4 .card {
        width: 100%;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .card-columns .col-md-4 {
            flex: 1 1 calc(50% - 15px);
            max-width: calc(50% - 15px);
        }
    }

    @media (max-width: 576px) {
        .card-columns .col-md-4 {
            flex: 1 1 100%;
            max-width: 100%;
        }
    }
</style>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <p class="navbar-brand"><?php echo "Welcome, " . $_SESSION['user']; ?>
                <button class="navbar-toggler bg-success" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </p>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex ms-auto" method="POST">
                    <button class="btn btn-danger" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <hr class="mb-4">

    <div class="mt-4">
        <h2 class="text-center">PRODUCTS</h2>
    </div>

    <!-- PRODUCT CARDS -->
    <div class="card-columns mt-4">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://e0.pxfuel.com/wallpapers/92/249/desktop-wallpaper-best-funny-dog-crazy-dog-thumbnail.jpg" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><?php echo $product->prod_name; ?> (<?php echo $product->cat; ?>)</h5>
                            <p class="card-text mb-0"><b style="color: <?php
                                                                        if ($product->quan > 50) {
                                                                            echo 'green';
                                                                        } elseif ($product->quan > 10) {
                                                                            echo 'orange';
                                                                        } else {
                                                                            echo 'red';
                                                                        }
                                                                        ?>">Stock: <?php echo $product->quan; ?></b></p>
                        </div>
                        <form method="POST" class="mt-2">
                            <div class="input-group mb-2">
                                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                            </div>
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <button type="submit" class="btn btn-success w-100" name="addToCart">Add to Cart</button>
                        </form>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <hr class="mb-4">

    <div>
        <h2 class="text-center mb-2">CART</h2>
    </div>

    <!-- CARTS TABLE -->
    <div class="col">
        <div class="table-responsive mt-4 text-center">
            <table class="table table-hover" style="color: white;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartprods as $cartprod): ?>
                        <tr>
                            <th scope="row"><?php echo $cartprod->id; ?></th>
                            <td><?php echo $cartprod->prod_name; ?></td>
                            <td><?php echo $cartprod->quantity; ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <button type="submit" class="btn btn-danger w-25" name="removeFromCart" value="<?php echo $cartprod->id; ?>">Remove from Cart</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-end">
            <form method="POST" class="mt-2">
                <button type="submit" class="btn btn-info mt-4" name="checkout">Checkout</button>
            </form>
        </div>
    </div>

    <br><br><br><br><br><br><br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>