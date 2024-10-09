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
