
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
}

?>
<table  class="table no-margins pj-extra-table">              
  <tbody id="fdExtraTable_show_<?php echo $index; ?>">
    <?php foreach($selected_arr as $selected_value) { ?>
    <tr>
      <td>
        <button class="btn btn-default"><?php echo $selected_value['extra_name']." X".$selected_value['extra_count']; ?></button>
        <a href="#" class="btn btn-xs btn-danger btn-outline pj-remove-extra" data-id="<?php echo $selected_value['id']; ?>" data-index="<?php echo $index; ?>"><i class="fa fa-times"></i></a>
      </td>
    </tr>

    <?php } ?>
  </tbody>
</table>

<table class="table no-margins">
  <tbody>
    <tr>
    <?php
      foreach($tpl['extra_arr'] as $key => $extra) { ?>
        <td><button data-val="<?php echo $extra['id']; ?>" data-name="<?php echo stripslashes($extra['name']); ?>" data-price="<?php echo $extra['price'];?>" data-id="<?php echo $key+1; ?>" data-index="<?php echo $index; ?>" class="btn btn-primary extra_item"><?php echo stripslashes($extra['name']); ?>: <?php echo pjCurrency::formatPrice($extra['price']);?></button></td>
      <?php }
    ?>
    </tr>
  </tbody>
</table>