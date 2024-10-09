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
