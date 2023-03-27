<?php /*echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
			  echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
			  echo '<pre>'; print_r($tpl['oi_extras']); echo '</pre>';
			  echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";*/ 
			  //echo "<pre>"; print_r($tpl['order_details']); echo "</pre>";
			  $order_details = $tpl['order_details'];
			  ?>
<div>
	<table style='width: 100%;'>
		<tbody>
			<tr>
				<th>Name:</th>
				<td><?php echo $order_details['surname']; ?></td>
				<th>Customer Type:</th>
				<td><?php echo $order_details['c_type']; ?></td>
			</tr>
			<tr>
				<th>SMS/Email:</th>
				<td><?php echo $order_details['sms_email']; ?></td>
				<th>Phone No:</th>
				<td><?php echo $order_details['phone_no']; ?></td>
			</tr>
			<tr>
				<th>SMS Sent Time:</th>
				<td><?php echo $order_details['sms_sent_time']; ?></td>
				<th>Estimated Delivery:</th>
				<td><?php echo $order_details['delivered_time']; ?></td>
			</tr>
			<tr>
				<th>Pickup Time:</th>
				<td><?php //echo $order_details['']; ?></td>
				<th>Status</th>
				<td><?php echo $order_details['status']; ?></td>
			</tr>
		</tbody>
	</table>
	<br/>
<fieldset>
	<legend>Order Items</legend>
	<table style='margin: auto; width: 100%;'>
		<tbody>
			<tr>
				<th style="text-align: center;">Quantity</th>
				<th></th>
				<th style="text-align: center;">Product</th>
				<th style="text-align: center;">Amount</th>
			</tr>
			<?php foreach($tpl['oi_arr'] as $k => $oi) { ?>
			<tr>
				<td><?php echo $oi['cnt'] ?></td>
				<td>X</td>
				<td><?php echo $oi['product_name']; ?></td>
				<td><?php echo pjCurrency::formatPrice($oi['cnt'] * $oi['price']); ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</fieldset>
</div>
