<div class="row wrapper border-bottom white-bg page-heading">
    
  <div class="col-sm-12">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-sm-6">
        <h2> Edit Config<?php //__('infoAddProductTitle');?></h2>
      </div>
    </div><!-- /.row -->
    <?php //echo "<pre>"; print_r($tpl['company_arr']); echo "</pre>"; ?>
    <?php //echo "<pre>"; print_r($tpl['category_arr']); echo "</pre>"; ?>
    <?php //echo "<pre>"; print_r($tpl['arr']); echo "</pre>"; ?>
    <!-- <p class="m-b-none"><i class="fa fa-info-circle"></i><?php //__('infoAddProductDesc');?></p> -->
  </div><!-- /.col-md-12 -->
</div> 
<div class="row wrapper wrapper-content animated fadeInRight">
  <div class="col-sm-12 col-lg-offset-3 col-lg-6">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminConfigs&amp;action=pjActionUpdate" method="post" id="frmUpdateConfig" autocomplete="off">
          <input type="hidden" name="config_id" value="<?php echo $tpl['arr']['config_id']; ?>">
          <?php
          include PJ_VIEWS_PATH . 'pjAdminConfigs/elements/pjConfigFormElement.php';
          ?>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
    table .form-group{
      margin-bottom: 0px !important;
    }
</style>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.size = <?php x__encode('lblSize'); ?>;
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
</script>
