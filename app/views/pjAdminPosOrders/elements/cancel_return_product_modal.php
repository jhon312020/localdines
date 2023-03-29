<div class="modal fade" id="cancelReturnModal" tabindex="-1" role="dialog" aria-labelledby="custom_product" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">Add Custom Product <i class="fa fa-info-circle" aria-hidden="true"></i></h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form name="custom_product_form" id="CustomProductForm">
          <div class="form-group">
            <label for="recipient-name" class="control-label">Type:</label>
            <span class="btn_container">
              <button type="button" id="productCanceled" class="btn product-method-btn selected">Cancel</button>
              <button type="button" id="prodcutReturned" class="btn product-method-btn">Return</button>
            </span>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Reason:</label>
            <textarea class="form-control" id="message-text" name="description"></textarea>
          </div>
          <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40">Save</button>
          <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>