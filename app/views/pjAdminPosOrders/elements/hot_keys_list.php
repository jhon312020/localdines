<?php
$products1 = array_slice($tpl['product_arr'], 0, 5);
$products2 = array_slice($tpl['product_arr'], 5, 5);
?>
<div id="jsHotItems" class="row wrapper wrapper-content animated">
  <div class='row'>
    <div class='col center' style="padding: 2px; display: list-item; text-align: center;">
    <?php foreach($products1 as $key=>$product)  { 
      $productName = $product['name'];
      $productName = strlen($productName) >13 ? substr($productName, 0, 10)."..." : $productName;
    ?>
        <button class='btn btn-warning btn-lg hot-item' data-id="<?php echo $product['id'];?>" data-extra="<?php echo $product['cnt_extras'];?>" data-hassize="<?php echo $product['set_different_sizes'];?>"><?php echo $productName; ?></button>

    <?php } ?>
    </div>
  </div> 
  <div class='row'>
    <div class='col center' style="padding: 2px; display: list-item; text-align: center;">
  <?php foreach($products2 as $key=>$product)  { 
    $productName = $product['name'];
    $productName = strlen($productName) >13 ? substr($productName, 0, 10)."..." : $productName;
  ?>
      <button class='btn btn-warning btn-lg hot-item' data-id="<?php echo $product['id'];?>" data-extra="<?php echo $product['cnt_extras'];?>" data-hassize="<?php echo $product['set_different_sizes'];?>"><?php echo $productName; ?></button>

  <?php } ?>
  </div>
  </div> 
</div>