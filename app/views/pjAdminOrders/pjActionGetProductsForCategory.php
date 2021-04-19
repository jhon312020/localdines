<table width="100%">
<tr>
<thead>
    <th>S.No.</th>
    <th>Product Name</th>
    <th>Price</th>
</thead>
</tr>
<?php if ($tpl['product_arr']) {
    $count = 1;
    foreach ($tpl['product_arr'] as $p){ 
        if ($p['status'] == 1 ) {
?>
<tr>
    <td>
    <?php echo $count++; ?>
    </td>
    <td><?php echo $p['name']; ?></td>
    <td>
        <?php echo pjCurrency::formatPrice($p['price']); ?>
    </td>
</tr>
<?php } } } else { ?>
    <tr><td colspan="3">Currently no products are available for the category</td></tr>
<?php } ?>
</table>