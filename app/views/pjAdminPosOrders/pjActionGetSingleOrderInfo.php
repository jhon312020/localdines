<?php /*echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
			  echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
			  echo '<pre>'; print_r($tpl['oi_extras']); echo '</pre>';
			  echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";*/ ?>
<div>
	<table style='width: 100%;'>
		<tbody>
			<tr>
				<th>Name:</th>
				<td></td>
				<th>Customer Type:</th>
				<td></td>
			</tr>
			<tr>
				<th>SMS/Email:</th>
				<td></td>
				<th>Phone No:</th>
				<td></td>
			</tr>
			<tr>
				<th>SMS Sent Time:</th>
				<td></td>
				<th>Estimated Delivery:</th>
				<td></td>
			</tr>
			<tr>
				<th>Pickup Time:</th>
				<td></td>
				<th>Status</th>
				<td></td>
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
