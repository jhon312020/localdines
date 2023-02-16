<?php
$index = "new_" . mt_rand(0, 999999); 
?>   
<tr class="fdLine new" data-index="<?php echo $index;?>" data-prepTime="<?php echo $tpl['arr']['preparation_time'] != '' ? $tpl['arr']['preparation_time'] : 0; ?>">
<!-- <input type="hidden" value="<?php //echo $tpl['arr'];?>"> -->
    <!-- <?php //print_r($tpl['arr']); ?>  -->
    <td class="tdProductName">
        <input type="hidden" id="fdProduct_<?php echo $index; ?>" name="product_id[<?php echo $index; ?>]" value="<?php echo $tpl['product_arr'][0]['id']; ?>">
        <?php echo $tpl['product_arr'][0]['name']; ?>
        <i class="fa fa-info-circle product_desc" aria-hidden="true"></i>
        <div id="fdSpecialInstructionImgs_<?php echo $index;?>" class="special_instruction_imgs">
            <input type="hidden" data-imgs="" >
        </div>  
        <!-- <i data-index = "<?php //echo $index; ?>" class="fa fa-align-right product_spcl_ins" aria-hidden="true"></i> -->
        <!-- <div id="fdSpecialInstructionCustom_<?php echo $index;?>">
            <input type="hidden" value="" >
        </div> -->
    </td>
    <td>
        <div class="business-<?php echo $index;?>">
            <input type="text" id="fdProductQty_<?php echo $index;?>" name="cnt[<?php echo $index;?>]" class="form-control pj-field-count" value="1" style="width: 50px;" />
        </div>
    </td>
    <td>
        <div class="business-<?php echo $index;?>">
            <?php if ($tpl['product_arr'][0]['cnt_extras'] > 0) { ?>
            <table id="fdExtraTable_<?php echo $index;?>" class="table no-margins pj-extra-table">							
                <tbody>
                </tbody>
            </table>
            <div class="p-w-xs">
                <a href="#" class="btn btn-primary btn-xs btn-outline pj-add-extra fdExtraBusiness_<?php echo $index;?> fdExtraButton_<?php echo $index;?>" data-index="<?php echo $index;?>"><i class="fa fa-plus"></i> <?php __('btnAddExtra');?></a>
            </div><!-- /.p-w-xs -->
            <?php } else { ?>
            <span class="fdExtraBusiness_<?php echo $index;?> fdExtraNA_<?php echo $index;?>"><?php __('lblNA');?></span>
            <?php } ?>
        </div>
    </td>

    <!-- MEGAMIND -->

    <!-- <td>
        <span id="fdCategory_<?php echo $index;?>"><?php echo $tpl['category_list'][$tpl['arr']['category_id']] ?></span>

    </td> -->

    <!-- MEGAMIND -->

    <td id="fdPriceTD_<?php echo $index;?>">
        
        <?php	
	if($tpl['arr']['set_different_sizes'] == 'F')
    {
        ?>
		<span class="fdPriceLabel"><?php echo pjCurrency::formatPrice($tpl['arr']['price']);?></span>
		<input type="hidden" id="fdPrice_<?php echo $index;?>" data-type="input" name="price_id[<?php echo $index;?>]" value="<?php echo $tpl['arr']['price'];?>" />
		<?php
	}else{
		?>
		<!-- <select id="fdPrice_<?php echo $index;?>" name="price_id[<?php echo $index;?>]" data-type="select" class="fdSize form-control">
			<option value="">-- <?php __('lblChoose'); ?>--</option> -->
			<?php
            
			foreach($tpl['price_arr'] as $v)
			{
                if($tpl['size_id'] ==  $v['id']) {
			    ?>
                <input type="hidden" value="<?php echo $v['id']?>" id="fdPrice_<?php echo $index;?>" name="price_id[<?php echo $index;?>]" data-price="<?php echo $v['price'];?>" data-type="select" class="fdSize form-control">
                <span class="fdPriceLabel" data-price="<?php echo $v['price'];?>"><?php echo pjSanitize::clean($v['price_name'])?>: <?php echo pjCurrency::formatPrice($v['price']); ?></span>
                <!-- <option value="<?php echo $v['id']?>" data-price="<?php echo $v['price'];?>" <?php echo $tpl['size_id'] != '' && $tpl['size_id'] ==  $v['id'] ? 'selected' : null; ?>><?php echo pjSanitize::clean($v['price_name'])?>: <?php echo pjCurrency::formatPrice($v['price']); ?> </option> -->
                <?php
                }
			} 
			?>
		<!-- </select> -->
		<?php
	} ?>
        
    </td>

    

    <!-- MEGAMIND --> 

    <!-- <td>
        <span id="fdPrepTime_<?php echo $index;?>"><?php echo $tpl['arr']['preparation_time']; ?></span>
    </td> -->

    <!-- MEGAMIND -->
    
    <td>
        <?php if($tpl['arr']['set_different_sizes'] == 'F')
        { ?>
        <strong><span id="fdTotalPrice_<?php echo $index;?>"><?php echo pjCurrency::formatPrice($tpl['arr']['price']);?></span></strong>
        <?php } else { ?>
        <strong><span id="fdTotalPrice_<?php echo $index;?>"><?php echo pjCurrency::formatPrice(0);?></span></strong>
        <?php } ?>
    </td>
                
    <td>
        <span class="fa fa-comment-o spcl_ins" data-pdname="<?php echo $tpl['product_arr'][0]['name']; ?>" aria-hidden="true" data-index = "<?php echo $index;?>"></span>
        <input type="hidden" id="fdSpecialInstruction_<?php echo $index;?>" name="special_instruction[<?php echo $index;?>]" class="form-control special-instruction" value="" />
        <input type="hidden" id="fdCustomSpecialInstruction_<?php echo $index;?>" name="custom_special_instruction[<?php echo $index;?>]" class="form-control custom-special-instruction" value="" />  
        
    </td>
    <td>
        <input type="hidden" id="jsIndex" value="<?php echo $index;?>">
        <div class="text-right d-inline" id="productDelete_rowOne<?php //echo $index;?>">
            <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-product"><i class="fa fa-trash"></i></a>
        </div>
    </td>
</tr>