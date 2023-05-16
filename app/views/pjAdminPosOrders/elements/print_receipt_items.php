<?php
  $i = 0;
  foreach ($tpl['oi_arr'] as $k => $oi) {
    //if ($oi['type'] == 'product') {
    $strikeThroughStart = '';
            $strikeThroughEnd = '';
    if (in_array($oi['status'], RETURN_TYPES))  { 
      $strikeThroughStart = '<s>';
      $strikeThroughEnd = '</s>';
    }
    if (in_array($oi['type'], PRODUCT_TYPES))  {
      $has_extra = false;
      $i = $i + 1; 
      $counter = 0;
      $product_count = $oi['cnt'];
?>

  <tr>
    <td class="kitchen">

    <?php
    echo $strikeThroughStart;
      if ($oi['type'] == 'custom') {
        echo '<strong>'.$oi['cnt']. ' x '; ?> <?php echo $oi['custom_name'].'</strong>'; 
      } else {
        echo '<strong>'.$oi['cnt']. ' x '; ?> <?php echo $oi['product_name'].'</strong>'; 
        echo $oi['size'];
      }
     ?>
    
    <?php 
      if (array_key_exists($oi['hash'], $tpl['oi_extras']) ) { 
        $extras_count = count($tpl['oi_extras'][$oi['hash']]);
        while($counter < $extras_count) {
          $extra = $tpl['oi_extras'][$oi['hash']][$counter]; //echo 'came here';
          echo '<br/><span style="margin-left: 20px">'.$extra->extra_name ." x ".$extra->extra_count.'</span>';
          $counter++;
        }
      }
      echo $strikeThroughEnd;
    ?>

   </td>
   <td class="nani" style="padding: 5px 5px; float: right; margin-right: 10px">
    <?php 
      echo $strikeThroughStart;
      echo pjCurrency::formatPrice($oi['cnt'] * $oi['price']); 
      if (array_key_exists($oi['hash'], $tpl['oi_extras']) ) { 
        $counter = 0;
        $extras_count = count($tpl['oi_extras'][$oi['hash']]);
        while($counter < $extras_count) {
          $extra = $tpl['oi_extras'][$oi['hash']][$counter]; //echo 'came here';
          echo '<br/><span>&nbsp;&nbsp;'.pjCurrency::formatPrice($extra->extra_count * $extra->extra_price).'</span>';
          $counter++;
        }
      }
      echo $strikeThroughEnd;
   ?>
   </td>
  </tr>
<?php
    }
  }
?>