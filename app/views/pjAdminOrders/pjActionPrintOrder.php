<div class="ticket">
	<div>
		<h3 style="margin-bottom: 0px;">CHEF ID :</h3>
		<span><?php 
				date_default_timezone_set('Asia/Kolkata');
				echo $tpl['arr']['chef_id']; ?>
		</span>
	</div>
	<div>
		<h3 style="margin-top: 0px;">TIME :</h3>
		<span><?php echo date('H:i:s', time()); ?></span><br>
	</div>
	
	<table class="table table-borderless">
		<thead>
			<th class="kitchen">KITCHEN</th>
		    <th class="nani">CATEGORY ID</th>
		</thead>
		<tbody>
			
			<?php   
                    
              //       print_r("<pre>");print_r($tpl["arr"]);
    		        // exit;

			       foreach ($tpl['categories'] as $cs => $c) {
			       	$i = 0;
                     
					foreach ($tpl['product_arr'] as $product)

					{

					    foreach ($tpl['oi_arr'] as $k => $oi)
					    {
					        if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'] && $c['id'] == $product['category_id'])
					        {
					            $has_extra = false;
					            $i = $i+1;

					            if ($i == 1) {
					            	# code...
					            //}
					            	// if ($c['id'] == $product['category_id']) {
					            		
					            	
            ?>
            <tr class="rowHead">
            	<input type="hidden" id="me" name="name" value="<?php echo $i; ?>">
            	<td><hr></td>
            	<td><?php echo $product['category_id'];?></td>
            </tr>
             <tr>
            	<input type="hidden" id="kprint_" name="" value="<?php echo $kprint; ?>">

                <td class="kitchen"><?php echo $oi['cnt']. " ";?><?php echo strtoupper($product['name']); ?><br><?php if($oi['special_instruction'] != '') { echo '#'.$oi['special_instruction']; }else{ echo '';} ?></td>
                <td class="nani"></td>
                
            </tr>

        <?php } else { ?>

            <tr>
            	<input type="hidden" id="kprint_" name="" value="<?php echo $kprint; ?>">

                <td class="kitchen"><?php echo $oi['cnt']. " ";?><?php echo $product['name']; ?><br><?php if($oi['special_instruction'] != '') { echo '#'.$oi['special_instruction']; }else{ echo '';} ?></td>
                <td class="nani"></td>
                
            </tr>
            <?php
               }
		    }
           } 
       }
   }
?>
		</tbody>
	</table>
	<h5>SPECIAL INSTRUCTION:</h5><span><?php echo $tpl['arr']['d_notes']; ?></span>
</div>
	
