<?php 
  $grouped_sel_ins_arr = array();
  foreach($tpl["selected_ins_arr"] as $selected_arr) {
    $grouped_sel_ins_arr[$selected_arr['qid']] = $selected_arr;
  }
?>
<div id="pos_si_qty">
  <?php for($i=1, $sel_counter=0; $i <= $tpl['qty']; $i++, $sel_counter++) { 
    $current_qty_id = 'qty_'.$i;
    if ($tpl['selected_ins_arr'] && array_key_exists($current_qty_id, $grouped_sel_ins_arr)) {
      $arr= true;
      $selected_ins_arr = explode(',', $grouped_sel_ins_arr[$current_qty_id]['ids']);
    } else {
      $arr = false;
      $selected_ins_arr = array();
    }
    ?>
    <div id="qty_<?php echo $i; ?>" <?php if ($i>1) echo "class='d-none'"?>>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Type</th>
            <th scope="col">Instructions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tpl['special_instructions'] as $spcl_ins) { ?>
          <tr>
            <td><?php echo $spcl_ins['instruction_type'] ?></td>
            <td class="col-special-instructions"><?php foreach($tpl['special_instructions_children'] as $spcl_ins_child) { 
                if($spcl_ins['id'] == $spcl_ins_child['parent_id']) {
                ?>
              <img src="<?php echo $spcl_ins_child['image'] ?>" 
                alt="" data-qty_id="qty_<?php echo $i; ?>" 
                class="img-fluid img_<?php echo $spcl_ins_child['id']; ?> 
                <?php
                 echo in_array($spcl_ins_child['id'], $selected_ins_arr) ? 'spcl_ins_selected': '' ?>" 
                data-id="<?php echo $spcl_ins_child['id']; ?>">
              <?php }
          } ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <div class="form-group">
      <label for="custom_special-instruction">Special Instruction</label>
      <textarea id="custom_special_qty_<?php echo $i; ?>" data-id="qty_<?php echo $i; ?>" placeholder="Type Here..."  class="js-kioskboard-input form-control custom_inst"  data-kioskboard-specialcharacters="true"><?php echo $arr ? $grouped_sel_ins_arr[$current_qty_id]['cus_ins'] : ""; ?></textarea>
    </div>
  </div>
    <input type="hidden" id="arr_qty_<?php echo $i; ?>" name="qty_<?php echo $i; ?>" value data-images>
    
  <?php } ?>
</div>
<div id='spl_pagination_container'>
<?php for($i =1; $i <= $tpl['qty']; $i++) {
  if ($i == 1) {
     echo "<a href='#' data-page='qty_{$i}' class='page-link btn btn-primary'>{$i}</a>";
  } else {
     echo "<a href='#' data-page='qty_{$i}' class='page-link btn btn-default'>{$i}</a>";
  }
} ?>
</div>
