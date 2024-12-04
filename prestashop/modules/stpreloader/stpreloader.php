<?php
/**
*  @author ST-themes https://www.sunnytoo.com
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class StPreLoader extends Module
{
    public $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_prefix_st = 'ST_';
    public  static $spinner_style = array();
    private $_pages = array(); 
    private $_st_is_16;

    public function __construct()
    {
        $this->name          = 'stpreloader';
        $this->tab           = 'front_office_features';
        $this->version       = '1.1.0';
        $this->author        = 'SUNNYTOO.COM';
        $this->need_instance = 0;
        $this->bootstrap     = true;
        
        parent::__construct();
        
        $this->initPages();
        $this->displayName = $this->l('Beautiful Preloader');
        $this->description = $this->l('Add a beautiful preloader to your site.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->_st_is_16      = Tools::version_compare(_PS_VERSION_, '1.7');
        self::$spinner_style = array(
            0 => array('id' => 0, 'name'=>$this->l('Spinner')),
            1 => array('id' => 1, 'name'=>$this->l('Ring')),
        );
    }

    private function initPages()
    {
        $this->_pages = array(
                array(
                    'id' => 'index',
                    'val' => '1',
                    'name' => $this->l('Homepage')
                ),
                array(
                    'id' => 'category',
                    'val' => '2',
                    'name' => $this->l('Category')
                ),
                array(
                    'id' => 'product',
                    'val' => '4',
                    'name' => $this->l('Product')
                ),
            );
    }
    public function install()
    {
        $result = true;
        if (!parent::install()
            || !Configuration::updateValue($this->_prefix_st.'PL_ENABLE', 1)
            || !Configuration::updateValue($this->_prefix_st.'PL_SPINNER_STYLE', 0)
            || !Configuration::updateValue($this->_prefix_st.'PL_SPINNER_COLOR', '#444444')
            || !Configuration::updateValue($this->_prefix_st.'PL_SPINNER_SIZE', '60')
            || !Configuration::updateValue($this->_prefix_st.'PL_SPINNER_OPACITY', 1)
            || !Configuration::updateValue($this->_prefix_st.'PL_SPINNER_POSITION', 0)
            || !Configuration::updateValue($this->_prefix_st.'PL_HIDE_ON_CLICK', 1)
            || !Configuration::updateValue($this->_prefix_st.'PL_OVERLAY', 0)
            || !Configuration::updateValue($this->_prefix_st.'PL_OVERLAY_COLOR', '#ffffff')
            || !Configuration::updateValue($this->_prefix_st.'PL_OVERLAY_OPACITY', 0.2)
            || !Configuration::updateValue($this->_prefix_st.'PL_HIDING_SPEED', 500)
            || !Configuration::updateValue($this->_prefix_st.'PL_CUSTOM_CONTENT', '')
            || !Configuration::updateValue($this->_prefix_st.'PL_DISPLAY_ON', 7)
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayAfterBodyOpeningTag')
        ) {
             $result = false;
        }
        return $result;
    }
    
    public function uninstall()
    {
        if (!parent::uninstall()
        ) {
            return false;
        }
        return true;
    }
    public function getContent()
    {
        $this->initFieldsForm();
        if (isset($_POST['savestpreloader']))
        {
            foreach($this->fields_form as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $ishtml = ($field['validation']=='isAnything') ? true : false;
                        $errors = array();       
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value==false && (string)$value != '0')
                                $errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        elseif($value)
                        {
                            $field_validation = $field['validation'];
                            if (!Validate::$field_validation($value))
                                $errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                        }
                        // Set default value
                        if ($value === false && isset($field['default_value']))
                            $value = $field['default_value'];
                        
                        if(count($errors))
                        {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        }
                        elseif($value==false)
                        {
                            switch($field['validation'])
                            {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                break;
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue($this->_prefix_st.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue($this->_prefix_st.strtoupper($field['name']), $value, $ishtml);
                    }
            //
            Configuration::updateValue($this->_prefix_st.'PL_SPINNER_STYLE', Tools::getValue('pl_spinner_style'));
            $display_on = 0;
            foreach($this->_pages as $v)
                $display_on += (int)Tools::getValue('pl_display_on_'.$v['id']);
            Configuration::updateValue($this->_prefix_st.'PL_DISPLAY_ON', $display_on);
            //                                     
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));

            $this->_clearCache('*');
        }

        $helper = $this->initForm();
        
        return $this->_html.$helper->generateForm($this->fields_form).'<div class="alert alert-info">This free module was created by <a href="https://www.sunnytoo.com" target="_blank">ST-THEMES</a>, it\'s not allow to sell it, it\'s also not allow to create new modules based on this one. Check more <a href="https://www.sunnytoo.com/blogs?term=743&orderby=date&order=desc" target="_blank">free modules</a>, <a href="https://www.sunnytoo.com/product-category/prestashop-modules" target="_blank">advanced paid modules</a> and <a href="https://www.sunnytoo.com/product-category/prestashop-themes" target="_blank">themes(transformer theme and panda  theme)</a> created by ST-THEMES.</div>';
    }

    protected function initFieldsForm()
    {
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->displayName,
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                'pl_enable' => array(
                    'type' => 'radio',
                    'label' => $this->l('How to display:'),
                    'name' => 'pl_enable',
                    'values' => array(
                        array(
                            'id' => 'enable_page',
                            'value' => 1,
                            'label' => $this->l('Display a loading image when a page is loading and when a filtering request is running')),
                        array(
                            'id' => 'enable_all',
                            'value' => 0,
                            'label' => $this->l('Display a loading image when a page is loading')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'checkbox',
                    'label' => $this->l('Special pages you need to have preloader'),
                    'name' => 'pl_display_on',
                    'lang' => true,
                    'values' => array(
                        'query' => $this->_pages,
                        'id' => 'id',
                        'name' => 'name'
                    ),
                ), 
                'spinner' => array(
                    'type' => 'html',
                    'id' => 'style',
                    'label' => $this->l('Spinner style:'),
                    'name' => $this->BuildRadioUI('pl_spinner_style', (int)Configuration::get($this->_prefix_st.'PL_SPINNER_STYLE')),
                    'desc' => '',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Custom preloader:'),
                    'name' => 'pl_custom_content',
                    'cols' => 20,
                    'rows' => 12,
                    'validation' => 'isAnything',
                    'desc' => array(
                        $this->l('1. If you put a custom preloader here, then spinner style option and color settings would not work for it.'),
                        $this->l('2. HTML codes allowed. Turn off the "Use HTMLPurifier Library" setting on the Shop parameters > General page if you want to put html codes into this field.'),
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Size:'),
                    'name' => 'pl_spinner_size',
                    'prefix' => 'px',
                    'class' => 'fixed-width-lg',
                    'validation' => 'isUnsignedInt',
                ),
                 array(
                    'type' => 'color',
                    'label' => $this->l('Spinner color:'),
                    'name' => 'pl_spinner_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                /*array(
                    'type' => 'text',
                    'label' => $this->l('Spinner opacity:'),
                    'name' => 'pl_spinner_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),*/
                array(
                    'type' => 'radio',
                    'label' => $this->l('Spinner position:'),
                    'name' => 'pl_spinner_position',
                    'values' => array(
                        array(
                            'id' => 'pl_spinner_position_tr',
                            'value' => 1,
                            'label' => $this->l('Top right corner')),
                        array(
                            'id' => 'pl_spinner_position_cm',
                            'value' => 0,
                            'label' => $this->l('Center')),
                    ),
                    'validation' => 'isUnsignedInt',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Disable overlay:'),
                    'name' => 'pl_overlay',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'enable_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'enable_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hide preloader when clicking on overlay:'),
                    'name' => 'pl_hide_on_click',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'hide_on_click_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'hide_on_click_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                ), 
                 array(
                    'type' => 'color',
                    'label' => $this->l('Overlay color:'),
                    'name' => 'pl_overlay_color',
                    'class' => 'color',
                    'size' => 20,
                    'validation' => 'isColor',
                 ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Overlay opacity:'),
                    'name' => 'pl_overlay_opacity',
                    'validation' => 'isFloat',
                    'class' => 'fixed-width-lg',
                    'desc' => $this->l('From 0.0 (fully transparent) to 1.0 (fully opaque).'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Hiding speed:'),
                    'name' => 'pl_hiding_speed',
                    'validation' => 'isInt',
                    'prefix' => 'ms',
                    'class' => 'fixed-width-lg',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save   ')
            )
        );
        if($this->_st_is_16)
            unset($this->fields_form[0]['form']['input']['pl_enable']);
    }
    protected function initForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $helper->module = $this;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savestpreloader';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        foreach($this->_pages as $v)
            $helper->tpl_vars['fields_value']['pl_display_on_'.$v['id']] = (int)$v['val']&(int)Configuration::get($this->_prefix_st.'PL_DISPLAY_ON'); 

        return $helper;
    }
    
    private function getDisplayOn($value = 0)
    {
        $ret = array();
        if (!$value)
            return $ret;
        foreach($this->_pages AS $v)
            if ((int)$v['val']&(int)$value)
                $ret[] = $v['id'];
        return $ret;
    }
    public function BuildRadioUI($name, $checked_value = 0)
    {
        $html = '';
        foreach(self::$spinner_style AS $key => $value)
        {
            $html .= '<label><input type="radio"'.($checked_value==$key ? ' checked="checked"' : '').' value="'.$key.'" id="'.$name.'_'.$key.'" name="'.$name.'">'.$key.'<img src="'.$this->_path.'views/img/'.$key.'.png" />'.'</label>';
            if (($key+1) % 6 == 0)
                $html .= '<br />';
        }
        return $html;
    }
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'pl_enable' => Configuration::get($this->_prefix_st.'PL_ENABLE'),
            'pl_spinner_style' => Configuration::get($this->_prefix_st.'PL_SPINNER_STYLE'),
            'pl_spinner_color' => Configuration::get($this->_prefix_st.'PL_SPINNER_COLOR'),
            'pl_spinner_position' => Configuration::get($this->_prefix_st.'PL_SPINNER_POSITION'),
            'pl_hide_on_click' => Configuration::get($this->_prefix_st.'PL_HIDE_ON_CLICK'),
            'pl_spinner_size' => Configuration::get($this->_prefix_st.'PL_SPINNER_SIZE'),
            'pl_spinner_opacity' => Configuration::get($this->_prefix_st.'PL_SPINNER_OPACITY'),
            'pl_overlay' => Configuration::get($this->_prefix_st.'PL_OVERLAY'),
            'pl_overlay_color' => Configuration::get($this->_prefix_st.'PL_OVERLAY_COLOR'),
            'pl_overlay_opacity' => Configuration::get($this->_prefix_st.'PL_OVERLAY_OPACITY'),
            'pl_hiding_speed' => Configuration::get($this->_prefix_st.'PL_HIDING_SPEED'),
            'pl_custom_content' => Configuration::get($this->_prefix_st.'PL_CUSTOM_CONTENT'),
            'pl_display_on' => Configuration::get($this->_prefix_st.'PL_DISPLAY_ON'),
        );
        
        return $fields_values;
    }
    public function hookDisplayHeader($params)
    {
        $this->context->controller->addJS($this->_path.'views/js/stpreloader.js');
        if($this->_st_is_16)
            $templateFile = 'header.tpl';
        else
            $templateFile = 'module:stpreloader/views/templates/hook/header.tpl';
        if (!$this->isCached($templateFile, $this->getCacheId())) {
            $custom_css = '';
            $overlay_opacity = Configuration::get($this->_prefix_st.'PL_OVERLAY_OPACITY');
            if($overlay_opacity>=0 && $overlay_opacity<1){
                if($overlay_color = Configuration::get($this->_prefix_st.'PL_OVERLAY_COLOR'))
                {
                    $overlay_color_hex = self::hex2rgb($overlay_color);
                    if(is_array($overlay_color_hex)){
                        $custom_css .= '#st_preloader_wrap{background-color: '.$overlay_color.';background:rgba('.$overlay_color_hex[0].','.$overlay_color_hex[1].','.$overlay_color_hex[2].','.$overlay_opacity.');}';
                    }
                }
            }

            $spinner_color = Configuration::get($this->_prefix_st.'PL_SPINNER_COLOR');
            $spinner_size = (int)Configuration::get($this->_prefix_st.'PL_SPINNER_SIZE');
            $spinner_size = $spinner_size ? $spinner_size : 60;
            $hiding_speed = (int)Configuration::get($this->_prefix_st.'PL_HIDING_SPEED');
            $overlay = (int)Configuration::get($this->_prefix_st.'PL_OVERLAY');
            $custom_content = Configuration::get($this->_prefix_st.'PL_CUSTOM_CONTENT');
            $spinner_position = (int)Configuration::get($this->_prefix_st.'PL_SPINNER_POSITION');

            if($overlay && !$custom_content){
                $custom_css .= '#st_preloader{width:'.$spinner_size.'px;height:'.$spinner_size.'px;}';
            }
            if(!$custom_content){
                $custom_css .= '#st_preloader{background-position:'.($spinner_position ? 'top right' : 'center center').';}';
            }

            $custom_css .= '.st_preloader_cm{margin-left:-'.ceil($spinner_size/2).'px;margin-top:-'.ceil($spinner_size/2).'px;}';

            $custom_css = preg_replace('/\s\s+/', ' ', $custom_css);

            $this->context->smarty->assign('stpreloader', array(
                'custom_css' => html_entity_decode($custom_css),
                'custom_content' => $custom_content,
                'spinner_style' => (int)Configuration::get($this->_prefix_st.'PL_SPINNER_STYLE'),
                'overlay' => $overlay,
                'spinner_color' => $spinner_color ? ltrim($spinner_color,'#') : '444444',
                'spinner_size' => $spinner_size,
                'hiding_speed' => $hiding_speed ? $hiding_speed : 500,
            ));
        }
        Media::addJsDef(array('stpreloader' => array(
            'enable' => (int)Configuration::get($this->_prefix_st.'PL_ENABLE'),
        )));
        if($this->_st_is_16)
            return $this->display(__FILE__, $templateFile, $this->getCacheId());
        else
            return $this->fetch($templateFile, $this->getCacheId());
    }
    public static function hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);
    
       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       return $rgb;
    }
    public function hookDisplayAfterBodyOpeningTag($params)
    {
        if($this->_st_is_16)
            $templateFile = 'preloader.tpl';
        else
            $templateFile = 'module:stpreloader/views/templates/hook/preloader.tpl';

        $page = Dispatcher::getInstance()->getController();
        $pl_display_on = (int)Configuration::get($this->_prefix_st.'PL_DISPLAY_ON');
        $key = '';
        if($pl_display_on){
            $page_array = $this->getDisplayOn($pl_display_on);
            if (!in_array($page, $page_array))
                $key = $page;
        }
                
        if (!$this->isCached($templateFile, $this->stGetCacheId($key))) {
            $this->context->smarty->assign('stpreloader', array(
                'custom_content' => Configuration::get($this->_prefix_st.'PL_CUSTOM_CONTENT'),
                'overlay' => Configuration::get($this->_prefix_st.'PL_OVERLAY'),
                'spinner_position' => (int)Configuration::get($this->_prefix_st.'PL_SPINNER_POSITION'),
                'hide_on_click' => (int)Configuration::get($this->_prefix_st.'PL_HIDE_ON_CLICK'),
                'hide' => $key,
            ));
        }
        if($this->_st_is_16)
            return $this->display(__FILE__, $templateFile, $this->stGetCacheId($key));
        else
            return $this->fetch($templateFile, $this->stGetCacheId($key));
    }
    protected function stGetCacheId($key, $name = null)
    {
        $cache_id = parent::getCacheId($name);
        return $cache_id.'_'.$key;
    }
}
