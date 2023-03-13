<?php $paperWidth = "290px"; ?>
<div id="receiptContainer">
<div class="ticket" style="margin: 5px 10px;width: <?php echo $paperWidth;?>;">
  <div style="margin: auto; width: <?php echo $paperWidth;?>; text-align: center;">
    <span><strong><?php echo $tpl['location_arr'][0]['name'];?></strong></span><br/>
    <span style="text-align: center; font-size:15px"><?php echo $tpl['location_arr'][0]['address'];?></span><br/>
    <span style="font-size:15px">TEL : <?php echo WEB_CONTACT_NO; ?></span><br/>
    <span style="font-size:15px">OrderID : <?php echo $tpl['arr']['order_id']; ?></span><br/>
    <span style="margin-top: 0px;margin-bottom: 0px; font-size:14px">DATE : <?php echo date('d-m-Y H:i:s', time()); ?></span>
  </div>
  <table class="table table-borderless" style="margin: 5px 3px; text-align: center; width: <?php echo $paperWidth;?>;">
    <thead>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td class="kitchen" width="70%">Name</th>
        <td class="kitchen" width="30%" style="float: right; margin-right: 45px">Amount</th>
      </tr>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
    </thead>
    <tbody>   
      <?php
        //foreach ($tpl['categories'] as $cs => $c) {
          $i = 0;
          //echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
          foreach ($tpl['product_arr'] as $product) {
            foreach ($tpl['oi_arr'] as $k => $oi) {
              //if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'] && $c['id'] == $product['category_id']) {
              if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id']) {
                $has_extra = false;
                $i = $i + 1; 
                ?>
                  <tr>
                    <td class="kitchen"><strong>
                    <?php echo $oi['cnt']. ' x '; ?> <?php echo $product['name']; ?></strong>
                    <?php echo $oi['size']; ?>
                     <?php if (array_key_exists($oi['hash'], $tpl['oi_extras'])) { 
                      $proExtra = $tpl['oi_extras'][$oi['hash']]; 
                      foreach($proExtra as $extra) {
                      ?>
                      <br/><span style="margin-left: 5px"><?php   echo $extra['extra_name'] ." x ".$extra['cnt']; ?></span> 
                    <?php } } ?>
                   </td>
                   <td class="nani" style="padding: 2px 5px; float: right; margin-right: 10px">
                    <?php 
                      echo pjCurrency::formatPrice($oi['cnt'] * $oi['price']); 
                      if (array_key_exists($oi['hash'], $tpl['oi_extras'])) { 
                      $proExtra = $tpl['oi_extras'][$oi['hash']]; 
                      foreach($proExtra as $extra) { 
                    ?>
                    <br/><?php echo pjCurrency::formatPrice($extra['cnt'] * $extra['price']);?>
                    <?php } } ?>
                   </td>
                  </tr>
                  <?php
              }
            }
          }
        //}
      ?>
      <tr>
       <td colspan="2"><hr/></td>
     </tr>
      <tr>
       <td><strong>Total</strong></td>
       <td style="padding: 2px 15px; float: right;"><strong><?php echo pjCurrency::formatPrice($tpl['arr']['total']); ?><strong></td>
      </tr>
      <?php if ($tpl['arr']['payment_method'] != 'bank') { ?>
      <tr>
        <td><strong>Cash Tendered</strong></td>
        <td style="padding: 2px 15px; float: right;"><strong>
          <?php echo pjCurrency::formatPrice($tpl['arr']['customer_paid']); ?>
          <strong>
        </td>
      </tr>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td><strong>Balance</strong></td>
        <td style="padding: 2px 15px; float: right;"><strong><?php echo pjCurrency::formatPrice($tpl['arr']['customer_paid'] - $tpl['arr']['total']); ?><strong></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td colspan="2"><strong>
          <?php echo "Card Payment"; ?>
          <strong>
        </td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="2"><hr/></td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:14px"><img height="150" width="150" src='<?php echo APP_URL."/app/web/upload/qrcode/QrCodeScanImage.png" ?>'><br/>Click & Collect<br/><br/>Thank you for ordering from <?php echo $tpl['location_arr'][0]['name'];?>!</td>
      </tr>
    </tbody>
  </table>
</div>
</div>
<div id="noPrint">
  <div class="ticket">
    <div>
      <span>&nbsp;</span><br/><br/>
    </div>
  </div>
</div>
<br/><br/>
<div class="hidden-print" style="margin: 5px 10px; width: 500px;">
  <button class="btn btn-primary printbutton" onClick="printDivLocal('receiptContainer')">Receipt Print</button>
  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&action=pjActionPrintOrder&source=actionIntialPrint&id=<?php echo $tpl['arr']['id'] ?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "KPrint" ?></a>
  <button class="btn btn-primary printbutton" id="btn-openDrawer">Topen</button>
  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=<?php echo $tpl['action']; ?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
<script type="text/javascript">
  function printDivLocal(divName) {
    $("#btn-openDrawer").trigger("click");
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
  }
  $(document).ready(function(){
    $("#btn-openDrawer").click(function(){
       $.ajax({
        type: "POST",
        async: false,
        url: "index.php?controller=pjAdminPosOrders&action=pjActionOpenDrawer",
        success: function (msg) {
          console.log(msg);
        },
      });
    });
  });
 
</script>
