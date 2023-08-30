<?php
if (!defined("ROOT_PATH")) {
  header("HTTP/1.1 403 Forbidden");
  exit;
}
class pjAdminConfigs extends pjAdmin {

  public function pjActionIndex() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isGet()) {
      $date_from = date('Y-m-d');
      $date_to = date('Y-m-d');
      $this->set('date_from', $date_from);
      $this->set('date_to', $date_to);
    }
    $this->setLocalesData();
    $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
    $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
    $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
    $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
    $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
    $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
    $this->appendJs('pjAdminConfig.js');
  }

  public function pjActionGetConfig() {
    $this->setAjax(true);
    if ($this->isXHR()) {
      $pjConfigModel = pjConfigModel::factory();
      $pjConfigModel = $pjConfigModel
        ->select("t1.*,date_format(t1.created_at, '%m-%d-%Y') as date,t1.config_id as id,t1.key as name,t1.value,t1.is_active");

      if ($q = $this->_get->toString('q')) {
        $pjConfigModel = $pjConfigModel->where("(t1.key LIKE '%$q%' OR t1.value LIKE '%$q%')");
      }
        
      $column = 'name';
      $direction = 'ASC';
      if ($this->_get->toString('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC'))) {
        $column = $this->_get->toString('column');
        $direction = strtoupper($this->_get->toString('direction'));
      }

      $total = $pjConfigModel->findCount()->getData();
      $rowCount = $this->_get->toInt('rowCount') ?: 10;
      $pages = ceil($total / $rowCount);
      if ($this->_get->toInt('page')) {
        $page = $this->_get->toInt('page') ?: 1;
      } else {
        $page = 1;
      }
        
      $offset = ((int) $page - 1) * $rowCount;
      if ($page > $pages) {
        $page = $pages;
      }

      $data = $pjConfigModel
        ->orderBy("$column $direction")
        ->limit($rowCount, $offset)
        ->findAll()
        ->getData();
        
      pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
    }
    exit;
  }

  public function pjActionCreate() {
    $post_max_size = pjUtil::getPostMaxSize();
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > $post_max_size) {
      pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminConfigs&action=pjActionIndex&err=AP05");
    }
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost()) {
      $pjConfigModel = pjConfigModel::factory();
      $data = array();
      $post = $this->_post->raw();
      if ($post) {
        $data['key'] = $post['key'];
        $data['value'] = $post['value'];
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjConfigModel->setAttributes($data)->insert()->getInsertId();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminConfigs&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $this->setLocalesData();
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminConfig.js');
      $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
      $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
      $this->appendCss('VirtualKeyboard/keyboard.css');
      $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
      $this->appendJs('VirtualKeyboard/keyboard.js');
    }
  }

  public function pjActionDeleteConfig() {
    $this->setAjax(true);
    if (!$this->isXHR()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
    }
    if (!self::isPost()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
    }
    if (!pjAuth::factory()->hasAccess()) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
    }
    if (!($this->_get->toInt('id'))) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
    }
    $pjConfigModel = pjConfigModel::factory();
    $arr = $pjConfigModel->find($this->_get->toInt('id'))->getData();
    if (!$arr) {
      self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Config not found.'));
    }
    $id = $this->_get->toInt('id');
    if ($pjConfigModel->setAttributes(array('config_id' => $id))->erase()->getAffectedRows() == 1) {
      self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Config has been deleted'));
    } else {
      self::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => 'Config has not been deleted.'));
    }
    exit;
  }

  public function pjActionUpdate() {
    $this->checkLogin();
    if (!pjAuth::factory()->hasAccess()) {
      $this->sendForbidden();
      return;
    }
    if (self::isPost() && $this->_post->toInt('config_id')) {
      $pjConfigModel = pjConfigModel::factory();
      $data = array();
      $post = $this->_post->raw();
      $id = $this->_post->toInt('config_id');
      if ($post) { 
        $data['key'] = $post['key'];
        $data['value'] = $post['value'];
        $data['is_active'] = $post['is_active'];
        $data['updated_at'] = date("Y-m-d H:i:s");
        $err = 'AP09';
        $pjConfigModel->reset()->where('config_id', $id)->limit(1)->modifyAll($data);
        $this->updateConfigFile();
      } else {
        $err = 'AP01';
      }
      pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminConfigs&action=pjActionIndex&err=$err");
    }
    if (self::isGet()) {
      $id = $this->_get->toInt('id');
      $arr = pjConfigModel::factory()->find($id)->getData();

      if (count($arr) === 0) {
        pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminConfigs&action=pjActionIndex&err=AP08");
      } 
      $this->set('arr', $arr);
      $this->setLocalesData();
      $this->appendCss('bootstrap-chosen.css', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('chosen.jquery.js', PJ_THIRD_PARTY_PATH . 'chosen/');
      $this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
      $this->appendJs('jquery.multilang.js', $this->getConstant('pjBase', 'PLUGIN_JS_PATH'), false, false);
      $this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
      $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
      $this->appendJs('pjAdminConfig.js');
      $this->appendJs('VirtualKeyboard/jquery.keyboard.js');
      $this->appendCss('VirtualKeyboard/custom_ui/jquery-ui.min.css');
      $this->appendCss('VirtualKeyboard/keyboard.css');
      $this->appendJs('VirtualKeyboard/custom_ui/jquery-ui-custom.min.js');
      $this->appendJs('VirtualKeyboard/keyboard.js');
    }
  }

  function updateConfigFile() {
    $adminConfigList = pjConfigModel::factory()->where('is_active', 1)->findAll()->getData();
    $configFile = dirname(__FILE__).'/../config/config.gen.php';
    $file = fopen($configFile, "w");
    $file = fwrite($file, "");
    $file = fclose($file);
    $file = fopen($configFile, "w");
    $configContent = '<?php ';
    if ($adminConfigList) {
      foreach($adminConfigList as $adminConfig) {
        $configContent .= 'define("'.$adminConfig['key'].'","'.$adminConfig['value'].'");';
        // $this->pr($adminConfig);
      }
      $configContent .= '?>';
    }
    fwrite($file, $configContent);
  }
}
?>