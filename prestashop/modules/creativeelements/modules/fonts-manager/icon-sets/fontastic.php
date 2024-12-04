<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\ModulesXFontsManagerXIconSetsXIconSetBase as IconSetBase;

class ModulesXFontsManagerXIconSetsXFontastic extends IconSetBase
{
    protected $data = '';
    protected $data_file = 'icons-reference.html';
    protected $stylesheet_file = 'styles.css';
    protected $allowed_zipped_files = ['icons-reference.html', 'styles.css', 'fonts/'];

    protected function prepare()
    {
        $this->data = @call_user_func('file_get_contents', $this->directory . $this->stylesheet_file);
    }

    public function getType()
    {
        return __('Fontastic');
    }

    public function isValid()
    {
        if (!file_exists($this->directory . $this->data_file)) {
            return false; // missing data file
        }

        return true;
    }

    protected function extractIconList()
    {
        $icons = [];
        $pattern = '/\.' . $this->getPrefix() . '(.*)\:before\s\{/';
        preg_match_all($pattern, $this->data, $icons_matches);

        if (empty($icons_matches[1])) {
            return false; //  missing icons list
        }
        foreach ($icons_matches[1] as $icon) {
            $icons[] = $icon;
        }

        return $icons;
    }

    protected function getPrefix()
    {
        static $set_prefix;

        if (null === $set_prefix) {
            preg_match('/class\^="(.*)?"/', $this->data, $prefix);

            $set_prefix = isset($prefix[1]) ? $prefix[1] : false;
        }

        return $set_prefix;
    }

    public function getName()
    {
        static $set_name;

        if (null === $set_name) {
            preg_match('/font-family: "(.*)"/', $this->data, $name);

            $set_name = isset($name[1]) ? $name[1] : false;
        }

        return $set_name;
    }
}
