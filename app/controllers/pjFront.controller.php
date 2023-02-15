<?php
if (!defined("ROOT_PATH"))
{
    header("HTTP/1.1 403 Forbidden");
    exit;
}
class pjFront extends pjAppController
{
    public $defaultCaptcha = 'pjFoodDelivery_Captcha';
    
    public $defaultLocale = 'pjFoodDelivery_LocaleId';
    
    public $defaultClient = 'pjFoodDelivery_Client';
    
    public $defaultLangMenu = 'pjFoodDelivery_LangMenu';
    
    public $defaultStore = 'pjFoodDelivery_Store';
    
    public $defaultForm = 'pjFoodDelivery_Form';
    
    public function __construct()
    {
        $this->setLayout('pjActionFront');
        
        self::allowCORS();
    }
    
    public function _get($key)
    {
        if ($this->_is($key))
        {
            return $_SESSION[$this->defaultStore][$key];
        }
        return false;
    }
    
    public  function _is($key)
    {
        return isset($_SESSION[$this->defaultStore]) && isset($_SESSION[$this->defaultStore][$key]);
    }
    
    public  function _set($key, $value)
    {
        $_SESSION[$this->defaultStore][$key] = $value;
        return $this;
    }
    
    public  function _unset($key)
    {
        if ($this->_is($key))
        {
            unset($_SESSION[$this->defaultStore][$key]);
        }
    }
    
    public function isFrontLogged()
    {
        if (isset($_SESSION[$this->defaultClient]) && count($_SESSION[$this->defaultClient]) > 0)
        {
            $user = pjAuth::init(array('id' => $this->getClientId()))->getUser();
            if($user['status'] == 'F')
            {
                $this->session->unsetData($this->defaultClient);
                return false;
            }
            return true;
        }
        if (isset($_SESSION['social_login'])) {
            return true;
        }
        if (isset($_SESSION['guest'])) {
            return true;
        }
        return false;
    }

    public function hasPostcode()
    {
        if (isset($_SESSION[$this->defaultClient]) && count($_SESSION[$this->defaultClient]) > 0)
        {
            $client = $_SESSION[$this->defaultClient];
            if($client['c_postcode'] != '')
            {
                return true;
            } else {
                return false;
            }
            
        } 

        if (isset($_SESSION['guest'])) {
            return false;
        }
        
        return false;
    }
    
    public function getClientId()
    {
        return isset($_SESSION[$this->defaultClient]) && array_key_exists('id', $_SESSION[$this->defaultClient]) ? $_SESSION[$this->defaultClient]['id'] : FALSE;
    }
    
    public function afterFilter()
    {
        if (!$this->_get->check('hide') || ($this->_get->check('hide') && $this->_get->toInt('hide') !== 1) &&
            in_array($this->_get->toString('action'), array('pjActionMain', 'pjActionTypes', 'pjActionLogin', 'pjActionVouchers','pjActionForgot', 'pjActionProfile', 'pjActionCheckout', 'pjActionPreview')))
        {
            $locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
            ->join('pjBaseLocaleLanguage', 't2.iso=t1.language_iso', 'left')
            ->where('t2.file IS NOT NULL')
            ->orderBy('t1.sort ASC')->findAll()->getData();
            
            $this->set('locale_arr', $locale_arr);
        }
    }
    
    public function beforeFilter()
    {
        return parent::beforeFilter();
    }
    
    public function beforeRender()
    {
        if ($this->_get->check('iframe'))
        {
            $this->setLayout('pjActionIframe');
        }
    }
    
    public function pjActionGetLocale()
    {
        return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : FALSE;
    }
    
    public function isXHR()
    {
        return parent::isXHR() || isset($_SERVER['HTTP_ORIGIN']);
    }
    
    protected static function allowCORS()
    {
        $install_url = parse_url(PJ_INSTALL_URL);
        if($install_url['scheme'] == 'https'){
            header('Set-Cookie: '.session_name().'='.session_id().'; SameSite=None; Secure');
        }
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
        header('P3P: CP="ALL DSP COR CUR ADM TAI OUR IND COM NAV INT"');
        header("Access-Control-Allow-Origin: $origin");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            exit;
        }
    }
    
    protected function pjActionSetLocale($locale)
    {
        if ((int) $locale > 0)
        {
            $_SESSION[$this->defaultLocale] = (int) $locale;
        }
        return $this;
    }
}
?>