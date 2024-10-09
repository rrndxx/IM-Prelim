<?php
require_once('connection.php');

$newConnection->addProduct();
$newConnection->editProduct();
$newConnection->deleteProduct();
$products = [];

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    body {
        margin: 0;
        padding: 20px;
        background-color: white;
    }

    .navbar-brand {
        font-size: 30px;
    }

    .table-responsive {
        margin-top: 20px;
    }
</style>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <p class="navbar-brand">Gaisano Bogo
                <button class="navbar-toggler bg-success" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form class="d-flex ms-auto" method="POST">
                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                        data-bs-target="#addModal">
                        Add
                    </button>
                    <button type="button" class="btn btn-info me-2" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        Filter
                    </button>
                    <input type="search" class="form-control me-2" placeholder="Input product name" name="search"
                        required>
                    <button class="btn btn-primary" type="submit" name="searchbutton">Search</button>
                    <button class="btn btn-warning ms-3" type="button"
                        onclick="window.location.href='main.php'">Reload</button>
                </form>
            </div>
        </div>
    </nav>

    <form action="" method="POST">
        <button class="btn btn-success me-2" type="submit" name="instock">In Stock</button>
        <button class="btn btn-danger" type="submit" name="outofstock">Out of Stock</button>
    </form>
    <hr class="mb-4">

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Purchased Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($products) {
                    foreach ($products as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->id; ?></td>
                            <td><?php echo $row->prod_name; ?></td>
                            <td><?php echo $row->cat; ?></td>
                            <td><?php echo $row->quan; ?></td>
                            <td><?php echo $row->date; ?></td>
                            <td>
                                <form action="" method="POST">
                                    <button type="button" class="btn btn-outline-primary me-4 w-25" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $row->id ?>">
                                        Edit
                                    </button>
                                    <button type="submit" class="btn btn-outline-danger w-25" name="deletebutton"
                                        value="<?php echo $row->id; ?>">Delete
                                    </button>
                                </form>
                            </td>
                            <?php include 'modal.php'; ?>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">No products found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'addmodal.php'; ?>
    <?php include 'filtermodal.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>
