<div class="modal fade" id="editModal<?= $row->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">EDIT PRODUCT</h5>  <!-- Changed title for clarity -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form method="POST" action="">
                        <input type="hidden" name="edit_id" value="<?= $row->id ?>">
                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label for="inputproductname<?= $row->id ?>" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="inputproductname<?= $row->id ?>"
                                       name="productname" value="<?= $row->prod_name ?>" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col">
                                <label for="inputState<?= $row->id ?>" class="form-label">Category</label>
                                <select id="inputState<?= $row->id ?>" class="form-select" name="category" required>
                                    <option value="Vegetable" <?= $row->cat == 'Vegetable' ? 'selected' : '' ?>>Vegetable</option>
                                    <option value="Fruit" <?= $row->cat == 'Fruit' ? 'selected' : '' ?>>Fruit</option>
                                    <option value="Drink" <?= $row->cat == 'Drink' ? 'selected' : '' ?>>Drink</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="inputquantity<?= $row->id ?>" class="form-label">Quantity</label>
                                <input type="text" class="form-control" id="inputquantity<?= $row->id ?>"
                                       name="quantity" value="<?= $row->quan ?>" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col">
                                <label for="inputpurchasedate<?= $row->id ?>" class="form-label">Date</label>
                                <input type="date" class="form-control" id="inputpurchasedate<?= $row->id ?>"
                                       name="purchasedate" value="<?= $row->date ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="editproduct">Edit Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
