<?php
$controller_name = $controller->_get->toString('controller');
$action_name = $controller->_get->toString('action');

// Dashboard
$isScriptDashboard = in_array($controller_name, array('pjAdmin')) && in_array($action_name, array('pjActionIndex'));

// Reservations
$isScriptBookingsController = in_array($controller_name, array('pjAdminOrders'));
$isScriptBookingsIndex = $isScriptBookingsController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));

// Clients
$isScriptClientsController = in_array($controller_name, array('pjAdminClients'));
$isScriptClientsIndex = $isScriptClientsController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));

// Vouchers
$isScriptVouchersController = in_array($controller_name, array('pjVouchers'));

// Payments
$isScriptPaymentsController = in_array($controller_name, array('pjPayments'));

// Menu
$isScriptProductsController       = in_array($controller_name, array('pjAdminProducts'));
$isScriptCategoriesController     = in_array($controller_name, array('pjAdminCategories'));
$isScriptExtrasController         = in_array($controller_name, array('pjAdminExtras'));

$isScriptProductsIndex      = $isScriptProductsController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));
$isScriptCategoriesIndex      = $isScriptCategoriesController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));
$isScriptExtrasIndex    = $isScriptExtrasController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));

// Locations
$isScriptLocationsController = in_array($controller_name, array('pjAdminLocations'));
$isScriptLocationsIndex = $isScriptLocationsController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));

// Reports
$isScriptReportsController = in_array($controller_name, array('pjAdminReports'));
$isScriptReportsIndex = $isScriptReportsController && in_array($action_name, array('pjActionIndex'));

// Settings
$isScriptOptionsController = in_array($controller_name, array('pjAdminOptions')) && !in_array($action_name, array('pjActionPreview', 'pjActionInstall'));

$isScriptOptionsBooking         = $isScriptOptionsController && in_array($action_name, array('pjActionOrders'));
$isScriptOptionsBookingForm     = $isScriptOptionsController && in_array($action_name, array('pjActionClientDetails', 'pjActionOrderForm', 'pjActionDeliveryForm'));
$isScriptOptionsTerm            = $isScriptOptionsController && in_array($action_name, array('pjActionTerm'));
$isScriptOptionsNotifications   = $isScriptOptionsController && in_array($action_name, array('pjActionNotifications'));
$isScriptOptionsPrintOrder      = $isScriptOptionsController && in_array($action_name, array('pjActionPrintOrder'));

// Permissions - Dashboard
$hasAccessScriptDashboard = pjAuth::factory('pjAdmin', 'pjActionIndex')->hasAccess();

// Permissions - Reservations
$hasAccessScriptBookings            = pjAuth::factory('pjAdminOrders')->hasAccess();
$hasAccessScriptBookingsIndex       = pjAuth::factory('pjAdminOrders', 'pjActionIndex')->hasAccess();

// Permissions - Clients
$hasAccessScriptClients            = pjAuth::factory('pjAdminClients')->hasAccess();
$hasAccessScriptClientsIndex       = pjAuth::factory('pjAdminClients', 'pjActionIndex')->hasAccess();

// Permissions - Menu
$hasAccessScriptMenu        = pjAuth::factory('pjAdminProducts')->hasAccess();
$hasAccessScriptMenuIndex   = pjAuth::factory('pjAdminProducts', 'pjActionIndex')->hasAccess();
$hasAccessScriptCategories  = pjAuth::factory('pjAdminCategories')->hasAccess();
$hasAccessScriptCategoriesIndex  = pjAuth::factory('pjAdminCategories', 'pjActionIndex')->hasAccess();
$hasAccessScriptExtras       = pjAuth::factory('pjAdminExtras')->hasAccess();
$hasAccessScriptExtrasIndex  = pjAuth::factory('pjAdminExtras', 'pjActionIndex')->hasAccess();

// Permissions - Vouchers
$hasAccessScriptVouchers = pjAuth::factory('pjVouchers')->hasAccess();

// Permissions - Payments
$hasAccessScriptPayments = pjAuth::factory('pjPayments', 'pjActionIndex')->hasAccess();

// Permissions - Locations
$hasAccessScriptLocations            = pjAuth::factory('pjAdminLocations')->hasAccess();
$hasAccessScriptLocationsIndex       = pjAuth::factory('pjAdminLocations', 'pjActionIndex')->hasAccess();

// Permissions - Reports
$hasAccessScriptReportsIndex       = pjAuth::factory('pjAdminReports', 'pjActionIndex')->hasAccess();

// Permissions - Settings
$hasAccessScriptOptions                 = pjAuth::factory('pjAdminOptions')->hasAccess();
$hasAccessScriptOptionsBooking          = pjAuth::factory('pjAdminOptions', 'pjActionOrders')->hasAccess();
$hasAccessScriptOptionsPayments         = pjAuth::factory('pjAdminOptions', 'pjActionPayments')->hasAccess();
$hasAccessScriptOptionsBookingForm      = pjAuth::factory('pjAdminOptions', 'pjActionOrderForm')->hasAccess() || pjAuth::factory('pjAdminOptions', 'pjActionDeliveryForm')->hasAccess();
$hasAccessScriptOptionsTerm             = pjAuth::factory('pjAdminOptions', 'pjActionTerm')->hasAccess();
$hasAccessScriptOptionsNotifications    = pjAuth::factory('pjAdminOptions', 'pjActionNotifications')->hasAccess();
$hasAccessScriptOptionsPrintOrder         = pjAuth::factory('pjAdminOptions', 'pjActionPrintOrder')->hasAccess();
?>

<?php if ($hasAccessScriptDashboard): ?>
    <li<?php echo $isScriptDashboard ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex"><i class="fa fa-th-large"></i> <span class="nav-label"><?php __('plugin_base_menu_dashboard');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptBookings): ?>
    <li<?php echo $isScriptBookingsIndex ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOrders&amp;action=pjActionIndex"><i class="fa fa-list"></i> <span class="nav-label"><?php __('menuOrders');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptClients): ?>
    <li<?php echo $isScriptClientsIndex ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionIndex"><i class="fa fa-user"></i> <span class="nav-label"><?php __('menuClients');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptMenu || $hasAccessScriptMenuIndex || $hasAccessScriptCategories || $hasAccessScriptCategoriesIndex || $hasAccessScriptExtras || $hasAccessScriptExtrasIndex): ?>
    <li<?php echo $isScriptProductsController || $isScriptCategoriesController || $isScriptExtrasController ? ' class="active"' : NULL; ?>>
        <a href="#"><i class="fa fa-cutlery"></i> <span class="nav-label"><?php __('menuMenu');?></span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            
            <?php if ($hasAccessScriptMenu): ?>
                <li<?php echo $isScriptProductsIndex ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminProducts&amp;action=pjActionIndex"><?php __('menuProducts');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptCategories): ?>
                <li<?php echo $isScriptCategoriesIndex ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCategories&amp;action=pjActionIndex"><?php __('menuCategories');?></a></li>
            <?php endif; ?>
            
            <?php if ($hasAccessScriptExtras): ?>
                <li<?php echo $isScriptExtrasIndex ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminExtras&amp;action=pjActionIndex"><?php __('menuExtras');?></a></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if (pjObject::getPlugin('pjVouchers') !== NULL): ?>
    <?php if ($hasAccessScriptVouchers): ?>
        <li<?php echo $isScriptVouchersController ? ' class="active"' : NULL; ?>>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjVouchers&amp;action=pjActionIndex"><i class="fa fa-gift"></i> <span class="nav-label"><?php __('plugin_vouchers_menu_vouchers');?></span></a>
        </li>
    <?php endif; ?>
<?php endif; ?>

<?php if ($hasAccessScriptLocations): ?>
    <li<?php echo $isScriptLocationsIndex ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionIndex"><i class="fa fa-map-marker"></i> <span class="nav-label"><?php __('menuLocations');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptReportsIndex): ?>
    <li<?php echo $isScriptReportsIndex ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionIndex"><i class="fa fa-files-o"></i> <span class="nav-label"><?php __('menuReports');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptOptions || $hasAccessScriptPayments): ?>
    <li<?php echo $isScriptOptionsController || $isScriptPaymentsController ? ' class="active"' : NULL; ?>>
        <a href="#"><i class="fa fa-cogs"></i> <span class="nav-label"><?php __('script_menu_settings');?></span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if ($hasAccessScriptOptionsBooking): ?>
                <li<?php echo $isScriptOptionsBooking ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionOrders"><?php __('menuOrders');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptPayments): ?>
                <li<?php echo $isScriptPaymentsController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjPayments&amp;action=pjActionIndex"><?php __('script_menu_payments');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptOptionsTerm): ?>
                <li<?php echo $isScriptOptionsTerm ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionTerm"><?php __('menuTerms');?></a></li>
            <?php endif; ?>
            
            <?php if ($hasAccessScriptOptionsBookingForm): ?>
                <li<?php echo $isScriptOptionsBookingForm ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionOrderForm"><?php __('tabClientDetails');?></a></li>
            <?php endif; ?>
            
            <?php if ($hasAccessScriptOptionsNotifications): ?>
                <li<?php echo $isScriptOptionsNotifications ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionNotifications"><?php __('menuNotifications');?></a></li>
            <?php endif; ?>
            
            <?php if ($hasAccessScriptOptionsPrintOrder): ?>
                <li<?php echo $isScriptOptionsPrintOrder ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionPrintOrder"><?php __('menuPrintOrder');?></a></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>