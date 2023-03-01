<?php
  $index = "new_" . mt_rand(0, 999999);
?>  
<style>
  tbody::-webkit-scrollbar {
    width: 20px;
  }
</style>
<table id="fdOrderList_1" class="table table-striped table-hover">
  <thead>
    <tr>
      <th><?php __('lblProduct');?></th>
      <th><?php __('lblQty');?></th>
      <th>
        <div class="p-w-xs"><?php //__('lblExtra');?>Extras</div>
      </th>
      <th><?php __('lblSizeAndPrice');?></th>
      <th><?php __('lblTotal');?></th>
      <th><?php echo 'S.I';?></th>
      <th></th>
    </tr>
  </thead>
  <tbody class="main-body" style="display: block; height: 200px; overflow-x: hidden; overflow-y: auto;">
    <?php
    if (array_key_exists('oi_arr', $tpl) && count($tpl['oi_arr']) > 0) {
      // echo '<pre>';
      // print_r($tpl['oi_arr']);
      foreach ($tpl['product_arr'] as $product) {
        foreach ($tpl['oi_arr'] as $k => $oi) {
          if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'])  {
          $has_extra = false;
          $oi_extras_array = array();
    ?>
      <tr class="fdLine jsKprintDone" data-index="<?php echo $oi['hash']; ?>" data-preptime = "<?php echo $product['preparation_time']; ?>">
        <td class="tdProductName">
          <?php
            if ($product['cnt_extras'] > 0) {
              $has_extra = true;
            }
            if ($product['status'] == 1) {
          ?>
          <input type="hidden" data-index="<?php echo $oi['hash']; ?>" data-extra="<?php echo $product['cnt_extras']; ?>" id="fdProduct_<?php echo $oi['hash']; ?>" name="product_id[<?php echo $oi['hash']; ?>]" value="<?php echo $product['id']; ?>">
          <?php echo stripslashes($product['name']); ?>
          <i class="fa fa-info-circle" aria-hidden="true"></i>
          <?php } ?>
        </td>                       
        <td>
          <div class="business-<?php echo $oi['hash']; ?>">
            <input type="hidden" id="fdProductQty_<?php echo $oi['hash']; ?>" name="cnt[<?php echo $oi['hash']; ?>]" class="form-control input-disabled" value="<?php echo $oi['cnt']; ?>" style="width: 45px;" /> 
            <span><?php echo $oi['cnt']; ?></span>
          </div>
        </td>                                 
        <td>
          <div class="business-<?php echo $oi['hash']; ?>">
            <?php
              $sel_extra_cnt = 1;
              foreach ($tpl['extra_arr'] as $extra) {
                foreach ($tpl['oi_arr'] as $oi_sub) {
                  if ($oi_sub['type'] == 'extra' && $oi_sub['hash'] == $oi['hash'] && $oi_sub['foreign_id'] == $extra['id']) {
                    $oi_extras_array[] = array('id'=>$sel_extra_cnt,'extra_sel_id'=>$oi_sub['foreign_id'],'extra_count'=>$oi_sub['cnt'], 'extra_price'=>$extra['price'],"extra_name"=>$extra['name']);
                    $sel_extra_cnt++;
                    $has_extra = true;
                  }
                }
              }
              if ($has_extra) {
            ?>
               <a href="#" class="btn btn-primary btn-xs btn-outline pj-add-extra btn-has-extra" data-index="<?php echo $oi['hash']?>"><i class="fa fa-plus"></i> <?php //__('btnAddExtra');?></a>
             <input type='hidden' id="extra-<?php echo $oi['hash']; ?>" name="extras[<?php echo $oi['hash']; ?>]" data-index="<?php echo $oi['hash']; ?>_<?php echo $oi_sub['id']; ?>" class="fdExtra fdExtra_<?php echo $oi['hash']; ?> form-control" value='<?php echo json_encode($oi_extras_array); ?>'/>

            <?php } else { ?>
              <a class="btn btn-danger btn-outline btn-xs"><i class="fa fa-times"></i></a>
            <?php } ?>
           
            
          </div>
        </td>
        <td id="fdPriceTD_<?php echo $oi['hash']; ?>">
          <?php $productData = json_encode($product, JSON_HEX_APOS); ?>
          <input type="hidden" id="prdInfo_<?php echo $oi['hash']; ?>" data-type="input" name="prdInfo_[<?php echo $oi['hash']; ?>]" value='<?php echo $productData; ?>' />
          <div class="business-<?php echo $oi['hash']; ?>">
            <?php
              if (empty($oi['price_id'])) {
            ?>
            <span class="fdPriceLabel"><?php echo pjCurrency::formatPrice($product['price']); ?></span>
            <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" data-type="input" name="price_id[<?php echo $oi['hash']; ?>]" value="<?php echo $product['price']; ?>" />
            <?php
            } else {
              if (isset($oi['price_arr']) && $oi['price_arr']) {
                foreach ($oi['price_arr'] as $pr) {
                  //echo '<pre>'; print_r($pr); echo '</pre>';
                  if ($pr['id'] == $oi['price_id']) {
               ?>
                  <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" name="price_id[<?php echo $oi['hash']; ?>]" value="<?php echo $pr['id']; ?>" data-price="<?php echo $pr['price']; ?>"/>
                  <span class="fdPriceLabel" data-price="<?php echo $pr['price']; ?>">
                    <?php echo stripslashes($pr['price_name']) . ": " . pjCurrency::formatPrice($pr['price']); ?>
                  </span>
              <?php
                  }
                }
              } else { ?>
                <input type="hidden" id="fdPrice_<?php echo $oi['hash']; ?>" name="price_id[<?php echo $oi['hash']; ?>]" value="" />
            <?php 
                }
            }
            ?>
          </div>
        </td>
        <td>
          <strong><span id="fdTotalPrice_<?php echo $oi['hash']; ?>"></span></strong>
        </td>                                                
        <td>
          <?php if ($oi['special_instruction']) { ?>
            <span class="fa fa-comment-o product_spcl_ins btn-has-si" aria-hidden="true" data-index = "<?php echo $oi['hash']; ?>"></span>
          <?php } else { ?>
            <span class="fa fa-comment-o" aria-hidden="true" data-index = "<?php echo $oi['hash']; ?>"></span>
          <?php } ?>
          
          <input type="hidden" id="fdSpecialInstruction_<?php echo $oi['hash']; ?>" name="special_instruction[<?php echo $oi['hash']; ?>]" class="form-control special-instruction" value='<?php echo $oi['special_instruction']; ?>' />
          <input type="hidden" id="fdCustomSpecialInstruction_<?php echo $oi['hash']; ?>" name="custom_special_instruction[<?php echo $oi['hash']; ?>]" class="form-control custom-special-instruction" value='<?php echo $oi['custom_special_instruction']; ?>' />
        </td>
        <td>
          <div class="text-right" id="productDelete_<?php echo $oi['hash']; ?>">
            <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
          </div>
        </td>
      </tr>
        <?php
          }
        }
      }
    }
    ?>
  </tbody>
</table>
