<!-- EDIT PRODUCT -->
<div class="modal fade" id="editModal<?= $product->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">EDIT PRODUCT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="">
                        <input type="hidden" name="edit_id" value="<?php echo $product->id; ?>">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="inputproductname" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="inputproductname" name="productname" value="<?php echo $product->prod_name; ?>" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col">
                                <label for="inputState" class="form-label">Category</label>
                                <select id="inputState" class="form-select" name="category" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->catname; ?>" <?php echo ($category->id == $product->cat_id) ? 'selected' : ''; ?>><?php echo $category->catname; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col">
                                <label for="inputQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="inputQuantity" name="quantity" value="<?php echo $product->quan; ?>" required>
                            </div>
                            <div class="col">
                                <label for="inputDate" class="form-label">Purchased Date</label>
                                <input type="date" class="form-control" id="inputDate" name="purchasedate" value="<?php echo $product->date; ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="editproduct">Update Product</button>
                        <input type="hidden" name="catid" value="<?php echo $product->cat_id; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                                    <?php foreach ($categories as $row): ?>
                                        <option value="<?php echo $row->catname; ?>"><?php echo $row->catname; ?></option>
                                    <?php endforeach; ?>
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

<!-- ADD CATEGORY -->
<div class="modal fade" id="addCat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">ADD CATEGORY</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="inputcategoryname" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="inputproductname" name="catname"
                                    required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="addcategory">Add Category</button>
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
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->id; ?>"><?php echo $category->catname; ?></option>
                            <?php endforeach; ?>
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
