
	<table class="table">
		<thead>

			<tr>
				<th class="w-75">KITCHEN</th>
			    <th class="w-25">NANI</th>
		    </tr>
		    <tr>
		    	<th>Table</th>
		    	<th>Row</th>
		    	<th>Covers</th>
		    	<th>Time</th>
		    </tr>
		    
			
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
                    
              //       print_r("<pre>");print_r($tpl["kprint_arr"]);
    		        // exit;
                     
					foreach ($tpl['product_arr'] as $product)
					{
					    foreach ($tpl['oi_arr'] as $k => $oi)
					    {
					        if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'])
					        {
					            $has_extra = false;
					            
            ?>
            <tr>
            	<input type="hidden" id="kprint_" name="" value="<?php echo $kprint; ?>">
                <td><?php echo $oi['cnt'];?><?php echo $product['name'];?><?php echo $oi['special_instruction'];?><td>
                <td><?php echo $product['category_id'];?></td>
            </tr>
            <?php
           } 
       }
   }
?>
		</tbody>
	</table>
	