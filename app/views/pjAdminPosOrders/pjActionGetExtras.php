
<?php
mt_srand();
$extra_index = 'x_' . mt_rand();
$index = $controller->_get->toString('index');
// print_r($tpl['extra_arr']);
// print_r($tpl['extra_count']);
// print_r($tpl['extra_val']);
if ($tpl['extra_val'] == 1) {
  $selected_arr = [];
} else {
  $selected_arr = json_decode($tpl['extra_val'], true);
  // $counter = 1;
  // foreach($selected_arr as $selected_value) {
  //   $selected_arr[$counter] = $selected_value;
  //   $counter++;
  // }
  // echo "<pre>"; print_r($selected_arr); echo "</pre>";
  // echo $tpl['qty'];
}
 ?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th style="width: 40px;">#</th>
      <th>Extras</th>
    </tr>
  </thead>
  <tbody id="fdExtraTable_show_<?php echo $index; ?>">
    <?php for ($i=1; $i <= $tpl['qty'] ; $i++) { ?>
      <tr <?php if(!$tpl['edit']) { ?> class="page-link <?php echo $i == 1 ? "table-bg-primary" : "table-bg-default"; ?>" <?php } ?> data-page_container="#fdExtraTable_show_<?php echo $index; ?>" data-qty_container="#extra_qty" data-page="<?php echo "qty_".$i; ?>">
        <td scope="row" class="index"><?php echo $i; ?></td>
        <td id="load_data_<?php echo $index; ?>_<?php echo "qty_".$i; ?>" class="load_data row">
          <?php foreach($selected_arr as $selected_value) { ?>
            <?php if ($selected_value['qty_no'] == "qty_".$i) { ?>
            <div class="form-group col-sm-5">
              <div class="input-group">
                <div class="input-group-addon font-20 <?php echo $tpl['edit'] ? "cus-w-70" : "cus-w-50" ?> cus-text-left"><?php echo $selected_value['extra_name']; ?></div>
                <input type="text" class="form-control cus-extra" value="<?php echo " X ".$selected_value['extra_count']; ?>" disabled placeholder="qty">
                <?php if (!$tpl['edit']) { ?>
                <div class="input-group-addon btn btn-xs btn-danger btn-outline pj-remove-extra" data-qty="<?php echo "qty_".$i; ?>" data-id="<?php echo $selected_value['id']; ?>" data-index="<?php echo $index; ?>"><i class="fa fa-times fa-3x"></i></div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
          <?php } ?>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<?php if (!$tpl['edit']) { ?>
<div id="extra_qty">
  <?php for ($i=1; $i <= $tpl['qty'] ; $i++) { ?>
    <div id="qty_<?php echo $i; ?>" <?php if ($i>1) echo "class='d-none'"?>>
      <table class="table no-margins">
        <tbody>
          <tr>
            <td>
          <?php
            foreach($tpl['extra_arr'] as $key => $extra) { ?>
              <button data-val="<?php echo $extra['id']; ?>" data-name="<?php echo stripslashes($extra['name']); ?>" data-price="<?php echo $extra['price'];?>" data-id="<?php echo $key+1; ?>" data-index="<?php echo $index; ?>" data-page="<?php echo "qty_".$i; ?>" class="btn btn-primary extra_item"><?php echo stripslashes($extra['name']); ?>: <?php echo pjCurrency::formatPrice($extra['price']);?></button>
            <?php }
          ?>
          </td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php } ?>
</div>
<?php } ?>



