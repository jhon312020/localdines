<div class="modal fade" id="customProductModal" tabindex="-1" role="dialog" aria-labelledby="custom_product" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">Add Custom Product <i class="fa fa-info-circle" aria-hidden="true"></i></h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form name="custom_product_form" id="CustomProductForm">
          <div class="form-group">
            <label for="recipient-name" class="control-label">Name:</label>
            <input type="text" class="form-control" name="product_name" value="" id="ProductName" readonly />
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Quantity:</label>
            <input type="number" class="form-control" name="product_quantity" value=""/>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Price:</label>
            <input type="number" class="form-control" name="product_price" value="">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Description:</label>
            <textarea class="form-control" id="message-text" name="description"></textarea>
          </div>
          <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" id="d_msg">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>