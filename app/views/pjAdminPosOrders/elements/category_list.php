<div id="js-categories" class="row wrapper wrapper-content animated">
  <?php foreach($tpl['category_list'] as $key=>$category)  { 
    $category = strlen($category) > 8 ? substr($category, 0, 7)."..." : $category;
  ?>
  <div class="col-sm-2 col-lg-1 cus-category" data-id="<?php echo $key; ?>" data-category="<?php echo $category; ?>">
    <div class="category-container cus-pt-2 cus-pb-2" data-id="<?php echo $key; ?>">
      <div class="content"><h4><?php echo $category; ?></h4></div>
    </div>
  </div>
  <?php } ?>
</div>