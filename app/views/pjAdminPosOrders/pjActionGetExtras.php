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
  $counter = 1;
  foreach($selected_arr as $selected_value) {
    $selected_arr[$counter] = $selected_value;
    $counter++;
  }
  echo "<pre>"; print_r($selected_arr); echo "</pre>";
}

?>
<table id="fdExtraTable_show_<?php echo $index; ?>" class="table no-margins pj-extra-table">              
  <tbody>
    <?php for ($i = 0, $counter = 1; $i < $tpl['extra_count']; $i++, $counter ++ ) { ?>
    <tr>
      <td>
        <select name="extra_id[<?php echo $index; ?>][<?php echo $counter; ?>]" data-index-only='<?php echo $index; ?>' data-count="<?php echo $counter; ?>" data-index="<?php echo $index; ?>_<?php echo $counter; ?>" class="fdExtra fdExtra_<?php echo $index; ?> form-control" id="fdExtra_<?php echo $index; ?>_<?php echo $counter; ?>">
        <option value="">-- <?php __('lblChoose'); ?>--</option>
        <?php
        if (isset($tpl['extra_arr']))
        {
          foreach ($tpl['extra_arr'] as $e) { ?>
            <option value="<?php echo $e['id']; ?>" <?php if (array_key_exists($counter, $selected_arr)) { if($e['id'] == $selected_arr[$counter]['extra_sel_id']) { echo "selected"; } } ?> data-price="<?php echo $e['price'];?>"><?php echo stripslashes($e['name']); ?>: <?php echo pjCurrency::formatPrice($e['price']);?></option>
            <?php
          }
        }
        ?>
      </select>
      </td>
      
      <td>
        <input type="text" id="fdExtraQty_<?php echo $index; ?>_<?php echo $counter; ?>" data-index-only='<?php echo $index; ?>' data-count="<?php echo $counter; ?>" name="extra_cnt[<?php echo $index; ?>][<?php echo $counter; ?>]" class="form-control pj-field-count" value="<?php if(array_key_exists($counter, $selected_arr)) { echo $selected_arr[$counter]['extra_count']; } else { echo "1"; } ?>" placeholder="Qty"/>
      </td>

      <td>
        <a href="#" class="btn btn-xs btn-danger btn-outline pj-remove-extra" data-index="<?php echo $index; ?>" data-count="<?php echo $counter; ?>"><i class="fa fa-times"></i></a>
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
        <td><button id="<?php echo $extra['id']; ?>" data-id="<?php echo $key+1; ?>" data-index="<?php echo $index; ?>" class="btn btn-primary extra_item"><?php echo stripslashes($extra['name']); ?>: <?php echo pjCurrency::formatPrice($extra['price']);?></button></td>
      <?php }
    ?>
    </tr>
  </tbody>
</table>
