<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}

require_once('connection.php');
$newConnection->removeFromCart();

$cartprods = [];
$categories = $newConnection->getCategories();

$user_id = $_SESSION['user_id'];

if (isset($_POST['addToCart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $connection = $newConnection->openConnection();
    $stmnt = $connection->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmnt->execute([$user_id, $product_id]);
    $existingCartItem = $stmnt->fetch();

    $prodStmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
    $prodStmt->execute([$product_id]);
    $product = $prodStmt->fetch();

    if ($product && $quantity <= $product->quan) {
        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $quantity;
            $updateStmt = $connection->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $updateStmt->execute([$newQuantity, $existingCartItem->id]);

            // $updateProducts = $connection->prepare("UPDATE products SET quantity = ? WHERE id = ?");
        } else {
            $insertStmt = $connection->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insertStmt->execute([$user_id, $product_id, $quantity]);
        }
    } else {
        echo "<script>alert('Insufficient stock or invalid quantity');</script>";
    }
}

$connection = $newConnection->openConnection();
$stmnt = $connection->prepare("SELECT cart.id, products.prod_name, cart.quantity FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?");
$stmnt->execute([$user_id]);
$cartprods = $stmnt->fetchAll();

$categories = $newConnection->getCategories();
$connection = $newConnection->openConnection();
$stmnt = $connection->prepare("SELECT * FROM products");
$stmnt->execute();
$products = $stmnt->fetchAll();

if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
    exit();
}
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<style>
    body {
        margin: 0;
        padding: 20px;
        background-color: #808D7C;
        font-family: Montserrat;
    }

    .navbar-brand {
        font-size: 30px;
    }

    .table-responsive {
        margin-top: 20px;
        box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;
    }

    button,
    .tb,
    .modal {
        box-shadow: rgba(0, 0, 0, 0.44) 0px 3px 8px;
    }

    .card {
        margin-bottom: 20px;
        background-color: white;
        border-radius: 20px;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .card-title {
        font-size: 30px;
    }

    .card-title,
    .card-subtitle,
    .card-text {
        margin-bottom: 10px;
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand"><?php echo "Welcome, " . $_SESSION['user'] . "!"; ?></h1>
            <form class="d-flex ms-auto" method="POST">
                <button class="btn btn-danger" type="submit" name="logout">Logout</button>
            </form>
        </div>
    </nav>

    <hr class="mb-4">

    <div>
        <h2 class="text-center mb-2">PRODUCTS</h2>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="mt-4">
        <div class="container">
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-12 col-md-3">
                        <div class="card m-4" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title text-center"><?php echo $product->prod_name; ?></h5>
                                <h6 class="card-subtitle mb-2 text-center">(<?php echo $product->cat_id; ?>) <?php echo $product->cat; ?></h6>
                                <p class="card-text text-center">Stock: <?php echo $product->quan; ?></p>
                                <form action="" method="POST">
                                    <button type="button" class="btn btn-success mt-4" data-bs-toggle="modal"
                                        data-bs-target="#addToCartModal<?= $product->id ?>" data-product-id="<?= $product->id ?>"
                                        data-prod-name="<?= $product->prod_name ?>" data-prod-cat="<?= $product->cat ?>"
                                        data-prod-stock="<?= $product->quan ?>">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Modal for Adding to Cart -->
                        <div class="modal fade" id="addToCartModal<?= $product->id ?>" tabindex="-1"
                            aria-labelledby="addToCartModalLabel<?= $product->id ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addToCartModalLabel<?= $product->id ?>">Add to Cart</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Product Name:</strong> <?= $product->prod_name ?></p>
                                        <p><strong>Category:</strong> <?= $product->cat ?></p>
                                        <p><strong>Stock Available:</strong> <?= $product->quan ?></p>

                                        <form action="" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $product->id ?>" />
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantity</label>
                                                <input type="number" class="form-control" id="quantity" name="quantity"
                                                    min="1" max="<?= $product->quan ?>" value="1" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="addToCart"
                                                <?php if ($product->quan == 0) echo 'disabled'; ?>>
                                                Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <hr class="mb-4">

    <div>
        <h2 class="text-center mb-2">CART</h2>
    </div>

    <!-- CARTS TABLE -->
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
    <br><br><br><br><br><br><br>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>