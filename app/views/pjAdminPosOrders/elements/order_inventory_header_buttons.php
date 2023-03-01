<div class="col-sm-12 mt-5">
  <div class="row">
    <div class="col-lg-4 col-sm-5 text-left">
      <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionIndex" class="btn btn-primary">
        <i class="fa fa-chevron-circle-left fa-3x" aria-hidden="true"></i>
      </a>
      <a href="#" class="btn btn-primary" id="btn-pause">
        <i class="fa fa-pause" aria-hidden="true"></i>
      </a>
      <span id="sel_table_name" class="d-none"> <a href="#tableModal" id="sel_table_name_modal" class="btn btn-primary"></a></span>
    </div>
    <div class="col-lg-4 col-sm-5 text-right">
      <form class="form-inline" style="" onkeydown="return event.key != 'Enter';">
        <div id="product_input" class="input-group d-none">
          <input id="inputSearch" type="text" class="form-control" type="search" placeholder="Search Products..." aria-label="Search">
          <div id="productSearch" class="input-group-addon btn btn-outline-success my-2 my-sm-0" type="button" style="background-color: #fff;color: #000;"><i class="fa fa-search" aria-hidden="true"></i></div>
        </div>
        <button id="productSearchHide" class="btn btn-outline-success my-2 my-sm-0" type="button" style="background-color: #0a5114;color: white;"><i class="fa fa-search" aria-hidden="true"></i></button>
      </form>
    </div>
    <div class="col-lg-4 col-sm-2 text-right">
      <a href="#" class="btn btn-primary" id="showPostalCodes">
        <i class="fa fa-map-marker" aria-hidden="true"></i>
      </a>
      <a href="#" class="btn btn-primary" id="showCart">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span id="cartPriceBottom"><?php echo pjCurrency::formatPrice(0); ?></span>
      </a>
    </div>
  </div>
</div>