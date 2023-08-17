<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center fixed-top bg-black">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-center justify-content-lg-start">
      <i class="bi bi-phone d-flex align-items-center"><span><?php echo WEB_CONTACT_NO; ?></span></i>
      <!-- <i class="bi bi-clock ms-4 d-none d-lg-flex align-items-center"><span>Mon-Sat: 11:00 AM - 23:00 PM</span></i> -->
    </div>
    <?php
			if($controller->isFrontLogged() && !isset($_SESSION['guest']))
			{ 
				?>
      <!-- <div class="container-fluid container-xl d-flex align-items-center justify-content-end">

      <a href="#!/loadProfile" class="bi bi-person nav-profile" aria-hidden="true"></a>
      </div> -->
      <div class="dropdown">
        <a role="button" class="bi bi-person nav-profile dropdown-toggle" data-bs-toggle="dropdown" id="dropdownMenuLink">
          
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <li><a class="dropdown-item" href="#!/loadProfile">Profile</a></li>
          <li><a class="dropdown-item" href="#!/loadMyOrders">My Orders</a></li>
        </ul>
      </div>  
    <?php } ?>
  </div>
  </section>

  <!-- ======= Header ======= -->

  <header id="header" class="fixed-top d-flex align-items-center bg-black">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <div class="logo me-auto">

        <!-- <h1><a href="<?php //echo APP_HOME_URL;?>index.php"><?php //__('script_name') ?> </a></h1> -->

        <!-- Uncomment below if you prefer to use an image logo -->

         <a href="<?php echo APP_HOME_URL.'index.php';?>"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>

      </div>


      <nav id="navbar" class="navbar order-last order-lg-0">

        <ul>

          <!-- li><a class="nav-link scrollto" href="index.php#hero">Home</a></li>

          <li><a class="nav-link scrollto" href="index.php#about">About</a></li> -->

          <li><a class="nav-link active" href="menu.php">Menu</a></li>

         <!--  <li><a class="nav-link scrollto" href="index.php#specials">Specials</a></li>

          <li><a class="nav-link scrollto" href="index.php#events">Events</a></li>

          <li><a class="nav-link scrollto" href="index.php#chefs">Chefs</a></li>

          <li><a class="nav-link scrollto" href="index.php#gallery">Gallery</a></li> -->
          
          <!-- <li><a class="nav-link scrollto pjFdBtnAcc fdBtnLogout" href="#" >Logout</a></li> -->

          <!-- <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a> 

            <ul>

              <li><a href="#">Drop Down 1</a></li>

              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>

                <ul>

                  <li><a href="#">Deep Drop Down 1</a></li>

                  <li><a href="#">Deep Drop Down 2</a></li>

                  <li><a href="#">Deep Drop Down 3</a></li>

                  <li><a href="#">Deep Drop Down 4</a></li>

                  <li><a href="#">Deep Drop Down 5</a></li>

                </ul>

              </li>

              <li><a href="#">Drop Down 2</a></li>

              <li><a href="#">Drop Down 3</a></li>

              <li><a href="#">Drop Down 4</a></li> 

            </ul>-->
          </li>
          <li><a class="nav-link scrollto d-block d-sm-none" href="<?php echo APP_HOME_URL.'index.php#why-us';?>">What we offer</a></li>
          <li><a class="nav-link scrollto d-block d-sm-none" href="<?php echo APP_HOME_URL.'index.php#gallery';?>">Gallery</a></li>
          <li><a class="nav-link scrollto d-block d-sm-none" href="<?php echo APP_HOME_URL.'index.php#specials';?>">Signature Dishes</a></li>
          <li><a class="nav-link scrollto d-block d-sm-none" href="<?php echo APP_HOME_URL.'index.php#book-a-table';?>">Book a Table</a></li>
          <li><a class="nav-link scrollto d-block d-sm-none" href="<?php echo APP_HOME_URL.'index.php#events';?>">Book An Event</a></li>
          <li><a class="nav-link scrollto" href="<?php echo APP_HOME_URL. 'index.php#contact';?>">Contact Us</a></li>

          <?php
      			if($controller->isFrontLogged())
      			{ 
      				?>
      				<li class="nav-logout"><a class="pjFdBtnAcc fdBtnLogout" href="" title="<?php __('front_logout', false, false);?>">Logout</a></li>
      				<?php
      			} else { ?>
              <li class="nav-login"><a class="pjFdBtnAcc fdBtnLogin" href="#!/loadLogin">Login</a></li>
            <?php }
      			?>

        </ul>

        <i class="bi bi-list mobile-nav-toggle"></i>

      </nav><!-- .navbar -->

     <!-- <a href="index.php#book-a-table" class="book-a-table-btn scrollto">Book a table</a> -->
       <!-- <i class="fa fa-search search-me" style="margin-left: 10px;font-size: 25px;color: #fff;"></i>
      <div id="searchInput-group" class="input-group rounded" style="display:none;width:0px;margin-left: 10px;">
        <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
          aria-describedby="search-addon" />
        <span class="input-group-text border-0 search-btn" id="search-addon" style="background-color: #ffb03b;color: #fff;">
          <i class="fa fa-search"></i>
        </span>
        <div><i class="fa fa-close" style="color: #fff;margin: 3px;"></i></div>
      </div> -->


    </div>

  </header><!-- End Header -->