<div class="modal fade" id="cancelReturnModal" tabindex="-1" role="dialog" aria-labelledby="custom_product" aria-hidden="true" style="width:100%">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title" id="modalTitle">Cancel/Return Product <i class="fa fa-info-circle" aria-hidden="true"></i></h2>
      </div>
      <div class="modal-body" id="modalBody">
        <form name="ProductCancelReturnForm" id="ProductCancelReturnForm">
          <input type="hidden" class="form-control" name="return" value="Cancel" id="CancelOrReturn" readonly required />
          <input type="hidden" class="form-control" name="row_id" value="" id="CancelReturnID" readonly  />
          <div class="form-group">
            <label for="product" class="control-label">Type:</label>
            <span class="btn_container">
              <button type="button" id="productCanceled" class="btn jsProductMethodBtn product-method-btn selected">Cancel</button>
              <button type="button" id="prodcutReturned" class="btn jsProductMethodBtn product-method-btn">Return</button>
            </span>
          </div>
          <div class="form-group">
            <label for="reason" class="control-label">Reason:</label>
            <textarea class="form-control" id="CancelOrReturnReason" name="reason" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" id="jsBtnCancelOrReturnProduct">Save</button>
        <button type="button" class="btn btn-primary btn-w-m-75 btn-h-m-40" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



 