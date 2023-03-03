<div class="row" style="height: 25px; background-color: #5bc0de; color: white; border-color: #46b8da;">
  <div class="col-sm-5">
    <div class="row">
      <div class="col-sm-4"><?php echo "Date: ". date($tpl['option_arr']['o_date_format']);  ?></div>
      <div class="col-sm-4 text-center"><?php echo "Day: ". date("l"); ?></div>
      <div id="currentTimeUpdate" class="col-sm-4 text-right"></div>
    </div>
  </div>
  <div class="col-sm-7">
    <div class="row">
      <div class="col-sm-7 text-right">
        <?php 
        $orderTitle = strtolower($tpl['order_title']); 
        if (in_array($orderTitle, HAS_TABLE_SELECTION)) { ?>
          <strong><?php echo $tpl['order_title']; ?></strong> <span id="sel_table_name"> <a href="#tableModal" id="sel_table_name_modal" class="btn btn-primary"><?php echo $tpl['selTableName']; ?></a>
        <?php } else { ?>
        <strong><?php echo $tpl['order_title']; ?></strong>
        <?php } ?>
      </div>
      <div class="col-sm-5 text-right"><strong><?php echo $_SESSION[$controller->defaultUser]['name']; ?></strong></div>
    </div>
  </div>
</div>