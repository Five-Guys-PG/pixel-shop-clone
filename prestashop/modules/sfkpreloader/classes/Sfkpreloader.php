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

class SfkpreloaderCore extends ObjectModel {

    public $sfk_title;
    public $sfk_url;
    public $sfk_status;
    public $sfk_dates;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array('table' => 'sfkpreloader', 'primary' => 'id_sfkpreloader', 'multilang' => false, 'fields' =>
        array(
            'sfk_title' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isGenericName', 'required' => true, 'size' => 300), 
            'sfk_url' => array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isAbsoluteUrl', 'required' => false, 'size' => 500),
            'sfk_status' => array('type' => self::TYPE_BOOL, 'lang' => false, 'validate' => 'isBool', 'required' => false), 
            'sfk_dates' => array('type' => self::TYPE_DATE, 'lang' => false, 'validate' => 'isDateFormat', 'copy_post' => false)));

    public static function getSfkpreloader($id_lang = null) {
        if (is_null($id_lang))
            $id_lang = Context::getContext()->language->id;
        $sfkpreloader = new Collection('Sfkpreloader', $id_lang);
        return $sfkpreloader;
    }

    public function __construct($id = null, $id_lang = null, $id_shop = null) {
        parent::__construct($id, $id_lang, $id_shop);
        $this->image_dir = _PS_GENDERS_DIR_;
    }

}
