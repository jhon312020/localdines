<div id="js-categories" class="row wrapper wrapper-content animated">
  <?php foreach($tpl['category_list'] as $key=>$category)  { 
    if ($key == 10) break;
    $category = strlen($category) >13 ? substr($category, 0, 10)."..." : $category;
  ?>
  <div class="col-lg-3 cus-category" data-id="<?php echo $key; ?>" data-category="<?php echo $category; ?>">
    <div class="category-container cus-pt-2 cus-pb-2" data-id="<?php echo $key; ?>">
      <div class="content"><h4><?php echo $category;?></h4></div>
    </div>
  </div>
  <?php } ?>
</div>