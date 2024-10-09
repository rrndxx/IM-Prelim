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

    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="exampleModalLabel">ADD PRODUCT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form method="POST" action="">
                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <label for="inputproductname" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="inputproductname" name="productname"
                                        required>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col">
                                    <label for="inputState" class="form-label">Category</label>
                                    <select id="inputState" class="form-select" name="category" required>
                                        <option selected disabled>Select Category</option>
                                        <option value="Vegetables">Vegetables</option>
                                        <option value="Fruits">Fruits</option>
                                        <option value="Drinks">Drinks</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col">
                                    <label for="inputQuantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="inputQuantity" name="quantity"
                                        required>
                                </div>
                                <div class="col">
                                    <label for="inputDate" class="form-label">Purchased Date</label>
                                    <input type="date" class="form-control" id="inputDate" name="purchasedate" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success" name="addproduct">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER MODAL -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="filterModalLabel">Filter Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Choose a Category</label>
                            <select class="form-select" name="selectedCategory" required>
                                <option selected disabled>Select Category</option>
                                <option value="Vegetables">Vegetables</option>
                                <option value="Fruits">Fruits</option>
                                <option value="Drinks">Drinks</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purchased Date Range</label>
                            <div class="d-flex">
                                <input type="date" class="form-control me-2" name="startDate">
                                <input type="date" class="form-control" name="endDate">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info" name="filterProducts">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Include the Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>
