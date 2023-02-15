<!-- Start Postal codes model -->
<div class="modal fade" id="postalCodesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="width:100%">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Postal codes</h2>
      </div>
      <div class="modal-body">
            <ul>
                <?php  
                foreach($tpl['postal_codes'] as $p => $postal_code) { ?>
                    <li><?php echo $postal_code['postal_code'] ?></li>
                <?php }
                ?>
            </ul>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->