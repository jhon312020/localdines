<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h2 id="heading-Product-list">Top Selling Products<?php //echo $tpl['loadData'];//__('infoExtrasTitle');?></h2>
      </div>
    </div><!-- /.row -->
  </div><!-- /.col-md-12 -->
</div>
<?php  
$updated_category = $controller->_get->toString('category');
 ?>

<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-4">
            <select name="type" id="filter_reports" class="form-control">
              <option <?php if($tpl['loadData'] == "pjActionGetTopProductsReport") { echo "selected";} ?> value="top-selling">Top Selling Products</option>
              <option <?php if($tpl['loadData'] == "pjActionGetNonProductsReport") { echo "selected";} ?> value="non-selling">Non Selling Products</option>
              <option value="cancel-return-Report">Cancel and Return Reports</option>
            </select>
          </div><!-- /.col-md-6 -->
          <div class="col-md-4">
            <!-- <form action="" method="get" class="form-horizontal frm-filter-TopProduct">
              <div class="input-group m-b-md">
                <input type="text" id="query" name="q" placeholder="<?php __('plugin_base_btn_search', false, true); ?>" class="form-control">
                <div class="input-group-btn">
                  <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form> -->
          </div>
          <div class="col-md-4">
            <select name="type" id="filter_category" class="form-control">
              <option value="all">-- <?php echo "All"; ?> --</option>
              <?php 
              foreach($tpl['categories'] as $category) { ?>
              <option value="<?php echo $category['id'] ?>" <?php if($updated_category > 0 && $updated_category == $category['id']) { ?> selected="selected" <?php }  ?>><?php echo $category['name']; ?></option>
              <?php }
              ?>
            </select>
          </div>
        </div><!-- /.row -->
        <div id="grid_products"></div>
      </div>
    </div>
  </div><!-- /.col-lg-12 -->
</div>
<script type="text/javascript">
  var pjGrid = pjGrid || {};
  pjGrid.queryString = "";
  <?php
  if ($controller->_get->toInt('category_id'))
  {
      ?>pjGrid.queryString += "&category_id=<?php echo $controller->_get->toInt('category_id'); ?>";<?php
  }
  ?>
  var myLabel = myLabel || {};
  myLabel.image = <?php x__encode('lblImage'); ?>;
  myLabel.name = <?php x__encode('lblName'); ?>;
  myLabel.count = "Count";
  // myLabel.price = <?php x__encode('lblPrice'); ?>;
  // myLabel.is_featured = "Hot Keys";
  //myLabel.is_featured = <?php echo "Hot Keys"; ?>;
  // myLabel.yes = <?php x__encode('_yesno_ARRAY_T'); ?>;
  // myLabel.no = <?php x__encode('_yesno_ARRAY_F'); ?>;
  // myLabel.delete_selected = <?php x__encode('delete_selected'); ?>;
  // myLabel.delete_confirmation = <?php x__encode('delete_confirmation'); ?>;
  var app_url = "<?php echo APP_URL;?>";
  var loadData = "<?php echo $tpl['loadData']; ?>";
</script>