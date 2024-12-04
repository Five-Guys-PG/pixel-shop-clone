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
* @copyright 2007-2023 Shahab-FK Enterprises
* @license   Prestashop Commercial Module License
*/

if (!defined('_PS_VERSION_')) { exit; }

class AdminSfkpreloaderControllerCore extends AdminController {

    public function __construct() 
    {
        $this->bootstrap = true;
        $this->table = 'sfkpreloader';
        $this->className = 'Sfkpreloader';
        $this->lang = false;
        $this->allow_export     = true;
        $this->bootstrap        = true;
        $this->deleted          = false;        
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->context = Context::getContext();
        if (!Tools::getValue('realedit'))
            $this->deleted = false;
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            )
        );
        $this->fields_list = array(
            'id_sfkpreloader' => array(
                'title' => $this->l('ID'),
                'align' => 'left',
                'width' => 'auto'
            ),
            'sfk_title' =>
            array('title' => $this->l('Preloader Page Loading Image Title'), 'filter_key' => 'sfk_title', 'align' => 'left', 'width' => 'auto'),
            'sfk_url' =>  array('title' => $this->l('Loading Image URL'), 'filter_key' => 'sfk_url', 'align' => 'left', 'width' => 'auto'),
            'sfk_dates' => array('title' => $this->l('Date'), 'filter_key' => 'sfk_dates', 'align' => 'left', 'width' => 'auto')
        );
        
        if(!$this->ajax && !isset($this->display)){
            $this->context->smarty->assign(array(
                'modules_dir' => _MODULE_DIR_
            ));
            $this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_.'sfkpreloader/views/templates/admin/sfkpreloader.tpl');
        }
        
        parent::__construct();
    }

    public function renderForm() 
    {
		   
        if (_PS_VERSION_ < '1.6') {
            $type = 'radio' ;
        } else {
            $type = 'switch' ;
        }
		
		   $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('SFK Preloader - Page Loading Image - Page Loading Animation - Preloading Screen - Loading Page Management'),
                'image' => '../img/admin/tab-sfkpreloader.gif'
            ),
            'input' => array
                (
                array(
                    'type' => 'text',
                    'label' => $this->l('Preloader Page Loading Image Title'),
                    'name' => 'sfk_title',
                    'size' => 33,
                    'hint' => $this->l('Invalid characters:') . ' 0-9!<>,;?=+()@#"�{}_$%:',
                    'required' => true,
                    'desc' => $this->l('The title for the sidebar.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Preloader Page Loading Image URL'),
                    'name' => 'sfk_url',
                    'size' => 33,
                    'hint' => $this->l('Invalid characters:').' /^[~:#,%&_=\(\)\.\? \+\-@\/a-zA-Z0-9]+$/',
                    'required' => true,
                    'desc' => $this->l('Only valid url values allowed.For example https://www.hrms-systems.com/tr/sfkloader2.gif'),
                ),
                
                array(
                    'type' => "$type",
                    'label' => $this->l('Active:'),
                    'name' => 'sfk_status',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'sfk_status_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'sfk_status_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                    'hint' => $this->l('Invalid characters:') . ' 0-9!<>,;?=+()@#"�{}_$%:',
                    'required' => true,
                    'desc' => $this->l('Enable or disable module to showcase in front-office.')
                ),
                array(
                    'type' => 'date',
                    'label' => $this->l('Date'),
                    'name' => 'sfk_dates',
                    'size' => 20,
                    'search' => false,
                    'desc' => $this->l('The date added or updated.')
                    ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );
        
       return parent::renderForm();    
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
