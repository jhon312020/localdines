<!-- Start of Payment Modal -->
<?php
$si = pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false);
if(isset($tpl['arr']['total'])) {
  $total = pjCurrency::formatPrice($tpl['arr']['total']);
  $total_val = ltrim(pjCurrency::formatPrice($tpl['arr']['total']),pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false));
} else {
  $total_val = "";
}
?>
<div id="paymentModal">

  <div class="row ">
    <div class="col-sm-6 text-left">
      <div class="keyboard-container d-none">
        <button type="button" class="close-keyboard" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="num-keyboard-pos"></div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 col-sm-7 text-left">
      <div class="money-container">
        <a href="javascript:;" data-rs = "50.00" class="btn"><?php echo $si; ?>50</a>
        <a href="javascript:;" data-rs = "20.00" class="btn"><?php echo $si; ?>20</a>
        <a href="javascript:;" data-rs = "10.00" class="btn"><?php echo $si; ?>10</a>
        <a href="javascript:;" data-rs = "5.00" class="btn"><?php echo $si; ?>5</a>
        <span id="payment_btn_val">
          <?php if(isset($tpl['arr']['total'])) { ?>
            <a href="javascript:;" data-rs = "<?php echo $total_val; ?>" class="btn"><?php echo $total;?></a>
          <?php } ?>
        </span>
      </div>
    </div>
    <div class="col-lg-3 col-sm-3 text-right"><b>Cash: </b> <span id="" class="payment_bal"></span></div>
    <div class="col-lg-3 col-sm-2 text-right">
      <div class="input-group money-container" >
        <input type="text" id="payment_cash_amount" name="payment_cash_amount" class="jsVK-price form-control pull-right input cus-w-150" value="0.00" />
        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4 col-sm-4 text-left"> <b>Return Balance: </b> <span id="payment_modal_return_bal" class="payment_bal"></span></div>
    <div class="col-lg-4 col-sm-4 text-left"> <b>Recive Balance: </b> <span id="payment_modal_recive_bal" class="payment_bal"></span></div>
    <div class="col-lg-1 col-sm-1 text-right"> <b>Card: </b> <span id="" class="payment_bal"></span></div>
    <div class="col-lg-3 col-sm-3 text-right">
      <div class="input-group money-container" >
        <input type="text" id="payment_card_amount" class="jsVK-price form-control pull-right input cus-w-150" value="0.00">
        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4 col-sm-7 text-left"></div>
    <div class="col-lg-5 col-sm-3 text-right"> <b>Total: </b> <span id="payment_modal_total" class="payment_bal"></span></div>
    <div class="col-lg-3 col-sm-2 text-right">
      <div class="input-group money-container" >
        <span id="payment_modal_curr" class="d-none"><?php echo $si; ?> </span>
        <span id="payment_modal_tot" class="d-none" style="padding-right:20px;"><?php echo $total_val; ?></span>
        
        <input type="text" id="payment_modal_pay" name="payment_modal_pay" class="form-control pull-right input cus-w-150" value="0.00" readonly />
        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
      </div>
      <div class="col-sm-12 text-right">
        <span id="error-msg" class="text-danger d-none" style="font-size:15px">Please enter a valid amount</span>
      </div>
    </div>
  </div>

  <div class="row text-right">
    <p></p>
  </div>

</div>