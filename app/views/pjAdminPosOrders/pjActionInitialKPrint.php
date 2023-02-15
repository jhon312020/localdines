<?php 
	$ed = $tpl['arr']['d_dt'] != '' ? $tpl['arr']['d_dt'] : $tpl['arr']['p_dt'];
	$ed_dt = explode(" ", $ed);
	$ed_dt_items = explode('-', $ed_dt[0]);
	$ed_dt[0] = $ed_dt_items[2] . '-' . $ed_dt_items[1] . '-' . $ed_dt_items[0];
	$paperWidth = "290px";
?>
<div id="kPrintContainer">
<div class="ticket" style="margin: 5px 10px;width: <?php echo $paperWidth;?>;">
	<table class="table table-borderless header" style="margin: auto; text-align: center; <?php echo $paperWidth;?>;">
		<!-- <tr><th class="top">CHEF NAME :&nbsp;</th><td><?php //echo $tpl['arr']['chef_name']; ?></td></tr> -->
		<?php if (strtolower($tpl['arr']['origin']) != 'pos') { ?>
		<tr><th class="top">&nbsp;</th><td><strong><?php echo substr($tpl['arr']['origin'], 0, 3); echo " - " .ucfirst($tpl['arr']['type']);?></strong></td></tr>
	<?php } ?>
		<tr><th class="top">TIME : </th><td><?php echo date('d-m-Y H:i:s', time()); ?></td></tr>
		<tr><th class="top">ED : </th><td><?php echo $ed_dt[0] . ' ' . $ed_dt[1]; ?></td></tr>
		<tr><th class="top">OrderID : </th><td><?php echo $tpl['arr']['order_id']; ?></td></tr>
	</table>
	<br/>
	<table class="table table-borderless" style="margin: auto; text-align: center; width: <?php echo $paperWidth;?>;">
		<thead>
			<th class="kitchen" style="text-align: center;"><strong><?php echo $tpl['arr']['table_ordered_name']?$tpl['arr']['table_ordered_name']:$tpl['arr']['table_name']; ?></strong></th>
		</thead>
		<tbody>
			<?php
			  $i = 0;
			  // echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
			  // echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
			  // echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";
			  foreach ($tpl['product_arr'] as $product) {
			    foreach ($tpl['oi_arr'] as $k => $oi) {
			      if ($oi['cnt'] != $oi['print'] && $oi['foreign_id'] == $product['id']) {
			        $i = $i + 1;
			        if ($i == 1) {
			?>
				        <tr class="rowHead">
				      		<td><hr/></td>
					      </tr>
				      	<?php } ?>
				       	<tr>
				          <td class="kitchen" style="font-size: 18pt;">
				          	<?php
				          	for ($i = 0, $counter = 0; $i <($oi['cnt'] - $oi['print']); $i++, $counter++) {
				          		echo 1 . " x ";
				          		echo strtoupper($product['name'])." ".$oi['size']." ";
				          		if ($oi['special_instruction']) {
				          			$obj = json_decode($oi['special_instruction'], true);
				          			
				          			if (isset($obj[$counter])) {
				          				if ($obj[$counter]['ids']) {
				          					$selected_ins_arr = explode(',', $obj[$counter]['ids']);
						          			foreach ($selected_ins_arr as $ins) {
						          				foreach ($tpl['special_instructions'] as $instruction) {
						          					if ($ins == $instruction['id']) {
						          						echo "<br><img src='".$instruction['image']."' style='height: 30px; width:30px;'/>";
						          					}
						          				}
						          			}
					          				echo "<br>";
				          				}
				          				
					          			if ($obj[$counter]['cus_ins']) {
						          			echo "#" . $obj[$counter]['cus_ins'];
					          			}
				          			}
				          			echo "<br>";
				          			echo "<br>";
				          		}
				          	}

				          	?>
										<br/>
					           <?php if (array_key_exists($oi['hash'], $tpl['oi_extras'])) { 
                      $proExtra = $tpl['oi_extras'][$oi['hash']]; 
                      foreach($proExtra as $extra) {
                      ?>
                      <br/><span style="margin-left: 20px"><?php   echo $extra['extra_name'] ." x ".($extra['cnt'] - $extra['print']); ?></span> 
                    <?php } } ?>
										
				          </td>
				        </tr>
        					
        <?php
      					} 
    					}
  					}
				?>
			 <tr class="rowHead">
	    	<td><hr></td>
	    </tr>
		</tbody>
	</table>
	<table class="table table-borderless" style="margin: auto; text-align: center; width:<?php echo $paperWidth;?>;">
		<thead>
			<th class="kitchen" style="text-align: center;"></th>
		</thead>
		<tbody>
			<?php
				  $i = 0;
				  foreach ($tpl['product_arr'] as $product) {
				    foreach ($tpl['oi_arr'] as $k => $oi) {
				      if ($oi['print'] && $oi['foreign_id'] == $product['id']) {
				        $i = $i + 1;
				        if ($i == 1) {
			?>
					        <tr class="rowHead">
						      	<td><hr></td>
						      </tr>
					       	<tr>
					       	<?php } ?>
					          <td class="kitchen" style="font-size: 16pt;">
					          	<?php 
					          		for ($i=0, $count= 0; $i <$oi['print'] ; $i++, $count++) { 
						          		echo 1 . " x ";
						          		echo strtoupper($product['name'])." ".$oi['size']." ";
						          		if ($oi['special_instruction']) {
						          			$obj = json_decode($oi['special_instruction'], true);
						          			
						          			if (isset($obj[$count])) {
						          				if ($obj[$count]['ids']) {
						          					$selected_ins_arr = explode(',', $obj[$count]['ids']);
								          			foreach ($selected_ins_arr as $ins) {
								          				foreach ($tpl['special_instructions'] as $instruction) {
								          					if ($ins == $instruction['id']) {
								          						echo "<br><img src='".$instruction['image']."' style='height: 30px; width:30px;'/>";
								          					}
								          				}
								          			}
								          			echo "<br>";
						          				}
							          			if ($obj[$count]['cus_ins']) {
								          			echo "#" . $obj[$count]['cus_ins'];
							          			}
						          			}
						          			echo "<br>";
						          			echo "<br>";
						          		}
						          		// code...
						          	}  
					          	?>
					           <?php if (array_key_exists($oi['hash'], $tpl['oi_extras'])) { 
                      $proExtra = $tpl['oi_extras'][$oi['hash']]; 
                      foreach($proExtra as $extra) {
                      ?>
                      <br/><span style="margin-left: 20px"><?php echo $extra['extra_name'] ." x ".$extra['cnt']; ?></span> 
                    <?php } } ?>
					          </td>
					          
					        </tr>
        <?php
      					} 
    					}
  					}
				?>
			<tr class="rowHead">
	    	<td><hr></td>
	    </tr>
		</tbody>
	</table>
	<?php
	if ($tpl['arr']['d_notes'] != '' || $tpl['arr']['p_notes'] != '') { ?>
	<h5>Spl. Instruction:</h5><span><?php echo $tpl['arr']['p_notes']? $tpl['arr']['p_notes']: $tpl['arr']['d_notes']; ?></span>
	<?php } ?>
</div>
</div>
<div class="hidden-print" style="margin: 5px 10px;width: <?php echo $paperWidth;?>;">
 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=<?php echo $tpl['action']; ?>&amp;origin=<?php echo ucfirst($tpl['arr']['origin']);?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	// your code
		function updateKitchenPrint() {
	    $.ajax({
	      type: "POST",
	      async: false,
	      url: "index.php?controller=pjAdminPosOrders&action=pjActionKitchenPrintUpdate",
	      data: {
	        order_id: <?php echo $tpl['order_id']; ?>
	      },
	      success: function (data) {
	      },
	      // !MEGAMIND
	    });
  	}
  	updateKitchenPrint();
  	printDiv('kPrintContainer');
	});
</script>