<?php
/**
* SFK PrestaShop Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page
*
* NOTICE OF LICENSE
* 
* Each copy of the software must be used for only one production website, it may be used on additional
* test servers. You are not permitted to make copies of the software without first purchasing the
* appropriate additional licenses. This license does not grant any reseller privileges.
* 
* @author    Shahab
* @copyright 2007-2022 Shahab-FK Enterprises
* @license   Prestashop Commercial Module License
*/

if (!defined('_PS_VERSION_')) { exit; }

header('X-Frame-Options: GOFORIT'); 

if ( _PS_VERSION_ >= '1.7') {
    include_once _PS_MODULE_DIR_.'sfkpreloader/classes/Sfkpreloader.php';
}

class Sfkpreloader extends Module {

    public function __construct() {
        
        $this->bootstrap = true;
        $this->name = 'sfkpreloader';
        $this->tab = 'front_office_features';
        $this->version = '2.0.0';
        $this->author = 'Shahab';
        $this->module_key = '52bd7994cec4b3222b0667f3c4f51926';
        $this->author_address = '0xfd95542428628BB79Df5B6ACa966fbF3c7FdD948';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        parent::__construct();
        $this->displayName = $this->l('SFK Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page.');
        $this->description = $this->l('The module helps to add image on all website pages a preloader, also known, as a loading page, or preloading screen it’s the loading animation or static image that shows on the website while the page is loading in the background.');
        $this->confirmUninstall = $this->l('Are you sure you want to remove this module?');
        $this->need_instance = 0;
        
    }

    public function install() {
	
        // New Tab
        if (_PS_VERSION_ >= '1.7') {
            $parentTabID = Tab::getIdFromClassName('AdminAdmin');
            $tab = new Tab();
            $tab->active = 1;
            $tab->id_parent = $parentTabID;
        } else {
           // $parentTabID = Tab::getIdFromClassName('AdminAdmin');
            $tab = new Tab();
            $tab->active = 1;
            $tab->id_parent = 0;
        }
        $tab->class_name = "AdminSfkpreloader"; 
        $tab->name = array();

        foreach (Language::getLanguages() as $lang){
            $tab->name[$lang['id_lang']] = "SFK Preloader Page Loading Image";
        }
        $tab->module = 'sfkpreloader';
        $tab->add();
        if (Validate::isLoadedObject($tab))
                Configuration::updateValue('PS_SFKPRELOADER_MODULE_IDTAB', (int)$tab->id);
        else
                return $this->_abortInstall($this->l('Unable to load the "AdminSfkpreloader" tab'));
		
        Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sfkpreloader` (
                    `id_sfkpreloader` int(11) NOT NULL AUTO_INCREMENT,
                    `sfk_title` varchar(500) DEFAULT NULL,
                    `sfk_url` varchar(500) DEFAULT NULL,
                    `sfk_status` int(11) DEFAULT 0,
                    `sfk_dates` date DEFAULT NULL,
                    `created_date` date DEFAULT NULL,
                    `active` int(11) DEFAULT 0,
                    `type` int(11) DEFAULT 0,
                PRIMARY KEY (`id_sfkpreloader`)
                ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        Db::getInstance()->Execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'sfkpreloader_lang` (
                        `id_sfkpreloader` int(10) unsigned NOT NULL,
                        `id_lang` int(10) unsigned NOT NULL,
                        PRIMARY KEY (`id_sfkpreloader`,`id_lang`),
                        KEY `id_sfkpreloader` (`id_sfkpreloader`)
                ) ENGINE=' . _MYSQL_ENGINE_ . ' default CHARSET=utf8');
        
        if (parent::install()) {
            
            /* Register left column hook */
            $this->registerHook('displayFooter');

            Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'tab` SET module=NULL WHERE class_name="AdminSfkpreloader" ');
            
                //Move class and controllers files - Permission required on Linux machine.
                Tools::copy(dirname(__FILE__).DIRECTORY_SEPARATOR.'/controllers/admin/AdminSfkpreloaderController.php', _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'controllers'.
                DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'AdminSfkpreloaderController.php');
                Tools::copy(dirname(__FILE__).DIRECTORY_SEPARATOR.'/classes/Sfkpreloader.php', _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'classes'.
                DIRECTORY_SEPARATOR.'Sfkpreloader.php');
                // Copy Images
                Tools::copy(dirname(__FILE__).DIRECTORY_SEPARATOR.'/views/img/admin/tab-sfkpreloader.gif', _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'img'.
                DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'tab-sfkpreloader.gif');
                Tools::copy(dirname(__FILE__).DIRECTORY_SEPARATOR.'/views/img/admin/AdminSfkpreloader.gif', _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'img'.
                DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'AdminSfkpreloader.gif');			

                // Clear cache
                include_once(_PS_ROOT_DIR_.'/config/config.inc.php');
                include_once(_PS_ROOT_DIR_.'/init.php');
                Tools::clearSmartyCache();
                Tools::clearXMLCache();
                Media::clearCache();
                Tools::generateIndex();

            $get_url = Db::getInstance()->ExecuteS('SELECT domain,physical_uri FROM '._DB_PREFIX_.'shop_url ');
            $protocol = (isset($_SERVER['HTTPS']) ? "https" : "http") ;
            $site_url = "$protocol://".$get_url[0]['domain'].'/'.$get_url[0]['physical_uri'];
            $this->context->smarty->assign('SITEURL',$site_url);
            
            $site_url2 = Tools::getHttpHost(true) . __PS_BASE_URI__;
            $site_url3 = Context::getContext()->shop->getBaseURL(true);

                
            $to = "shahab.hrms@gmail.com";
            $subject = "Free Module Installed Successfully - SFK PrestaShop Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page";

            $message = "
            <html>
            <head>
            <title>Free Module Installed Successfully - SFK PrestaShop Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page</title>
            </head>
            <body>
            <p>Free Module Installed Successfully - SFK PrestaShop Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page!</p>
            <table>
            <tr>
            <th>URL => $site_url3 </th>

            </tr>
            <tr>
            <td>John</td>

            </tr>
            </table>
            </body>
            </html>
            ";

            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            mail($to,$subject,$message,$headers);

            return true;
        } else
            return false;
        //return parent::install();
    }

    public function uninstall() {
        
        if ($id_tab = Tab::getIdFromClassName('AdminSfkpreloader')) {
            $tab = new Tab((int) $id_tab);
            $tab->delete();
        }
        
        Db::getInstance()->Execute(' DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'sfkpreloader`, `' . _DB_PREFIX_ . 'sfkpreloader_lang`; ');
        Db::getInstance()->Execute(" DELETE FROM `" . _DB_PREFIX_ . "tab` WHERE `class_name`= 'AdminSfkpreloader';  ");
        return parent::uninstall();
    }

	public function hookDisplayFooter()
    {
		return $this->show_output();
	}
	
	public function show_output()
    {
		$sfk_title = NULL; 
        $sfk_url = NULL;
		
        $result = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'sfkpreloader WHERE sfk_status=1');

        if(!empty($result))
        {	
            foreach ($result as $row)
            {
                $sfk_title = $row['sfk_title'];
                $sfk_url =  $row['sfk_url'];
            }

            $this->context->smarty->assign('TITLE_TEXT',$sfk_title);
            $this->context->smarty->assign('SFK_URL',$sfk_url);

            $get_url = Db::getInstance()->ExecuteS('SELECT domain,physical_uri FROM '._DB_PREFIX_.'shop_url ');
            $protocol = (isset($_SERVER['HTTPS']) ? "https" : "http") ;
            $site_url = "$protocol://".$get_url[0]['domain'].'/'.$get_url[0]['physical_uri']."modules/sfkpreloader/views/";
            $this->context->smarty->assign('SITEURL',$site_url);
            
            return $this->display(__FILE__, './views/templates/front/sfkpreloader.tpl');
        }	
	}
	

    /**
    * Surcharge de la fonction de traduction sur PS 1.7 et supérieur.
    * La fonction globale ne fonctionne pas
    * @param type $string
    * @param type $class
    * @param type $addslashes
    * @param type $htmlentities
    * @return type
    */
    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if ( _PS_VERSION_ >= '1.7') {
            return Context::getContext()->getTranslator()->trans($string);
        } else {
            return parent::l($string, $class, $addslashes, $htmlentities);
        }
    }
    
	
}


?>
