<!-- Added header strip -->
<div id="header-copyright" class="copyright text-center white-bg fixed" style="padding: 10px 0px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <p style="text-transform: lowercase;"><i class="fa fa-phone"></i> <?php echo WEB_CONTACT_NO; ?><span style="margin-left: 20px;"></span><i class="fa fa-envelope"></i> <?php echo WEB_CONTACT_EMAIL; ?></P>
                                
                        </div>
                    </div>
                </div>
            </div>    
            <!--Header section start-->
            <div class="header sticky-header fixed">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2 col-sm-3 col-6">
                            <div class="logo"><a href="index.php"><img src="images/logo/logo.png" alt=""></a></div>
                        </div>
                        <div class="col-md-10 col-sm-9 col-6">
                            <div class="mgea-full-width">
                                <div class="header-right">
                                    <div class="header-menu hidden-sm hidden-xs">
                                        <div class="menu">
                                            <ul>
                                                <li><a href="index.php">Home</a></li>
                                        
                                                <li><a href="menu.php">Menu</a></li> 
                                                <li><a href="index.php#forSpecials">Specials</a></li>
                                               <!--  <li><a href="blog.html">blog</a></li> -->
                                                <li><a href="contact-us.php">contact</a></li>
                                                <?php
                                                //if (isset($_SESSION['is_login']) && $_SESSION['is_login'] == 'true') { ?>
                                                <!-- <li><a href="review.php?logoutUser=1">Logout</a></li> -->
                                                <?php //} else { ?>
                                                <!-- <li><a href="loginPage.php">Login</a></li> -->
                                                <?php //}
                                                ?>
                                                <?php
                                                if($controller->isFrontLogged() && !isset($_SESSION['guest']))
                                                { ?> 
                                                <li><a href="#!/loadProfile">Profile</a></li>
                                                <?php }
                                                ?>
                                                <?php
                                                if($controller->isFrontLogged())
                                                { ?> 
                                                <li><a class="fdBtnLogout" href="javascript:;">Logout</a></li>
                                                <?php } 
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="search">
                                        <div class="search-inner">
                                            <a href="#"><i class="mdi mdi-magnify"></i></a>
                                        </div>
                                    </div>  
                                </div>
                                <div class="search-inside" style="display: none;">
                                    <a href="#" class="search-close"><i class="mdi mdi-close"></i></a>
                                    <div class="search-overlay"></div>
                                    <div class="searchbar-inner">
                                        <div class="search">
                                            <form action="#">
                                                <input type="search" placeholder="Search here"><button type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile menu start -->
                <div class="mobile-menu-area hidden-lg hidden-md">
                    <div class="container">
                        <div class="col-md-12">
                            <nav id="dropdown">
                                <ul>
                                    <li><a href="index.php">Home</a>
                                        
                                    </li>
                                     
                                    <li><a href="menu.php">Menu</a></li>
                                     <li><a href="index.php#forSpecials">Specials</a></li>
                                   
                                    <li><a href="contact-us.php">contact</a></li>
                                    <?php
                                    if($controller->isFrontLogged() && !isset($_SESSION['guest']))
                                    { ?> 
                                    <li><a href="#!/loadProfile">Profile</a></li>
                                    <?php }
                                    ?>
                                    <?php
                                    if($controller->isFrontLogged())
                                    { ?> 
                                    <li><a class="fdBtnLogout" href="javascript:;">Logout</a></li>
                                    <?php } 
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Mobile menu end -->
            </div>