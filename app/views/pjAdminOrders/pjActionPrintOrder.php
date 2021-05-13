<div class="ticket">
	<table class="table table-borderless">
		<thead>

			<!-- <tr> -->
				<th class="kitchen">KITCHEN</th>
			    <th class="nani">NANI</th>
		    <!-- </tr> -->
		    <!-- <tr>
		    	<th>Table</th>
		    	<th>Row</th>
		    	<th>Covers</th>
		    	<th>Time</th>
		    </tr> -->
		    
			
		</thead>
		<tbody>
			<!-- <tr>
				<table>
					<thead>
						<th>table</th>
						<th>room</th>
						<th>covers</th>
						<th>time</th>
					</thead>
					<tbody>
						<tr>
							<td>101</td>
							<td>3</td>
							<td>1</td>
							<td>15:11</td>
						</tr>
					</tbody>
				
			    </table>
			</tr> -->
			
			<?php   
                    
              //       print_r("<pre>");print_r($tpl["categories"]);
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

                <td class="kitchen"><?php echo $oi['cnt']. " ";?><?php echo $product['name']; ?><br><?php echo '#'.$oi['special_instruction']; ?></td>
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
</div>
	
