<div class="modal fade" id="editModal<?= $row->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">EDIT PRODUCT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="">
                        <input type="hidden" name="edit_id" value="<?php echo $row->id; ?>">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="inputproductname" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="inputproductname" name="productname"
                                    value="<?php echo $row->prod_name; ?>" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col">
                                <label for="inputState" class="form-label">Category</label>
                                <select id="inputState" class="form-select" name="category" required>
                                    <option value="<?php echo $row->cat; ?>" selected disabled><?php echo $row->cat; ?></option>
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
                                    value="<?php echo $row->quan; ?>" required>
                            </div>
                            <div class="col">
                                <label for="inputDate" class="form-label">Purchased Date</label>
                                <input type="date" class="form-control" id="inputDate" name="purchasedate"
                                    value="<?php echo $row->date; ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="editproduct">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
