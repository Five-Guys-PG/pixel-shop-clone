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

class ModulesXFontsManagerXIconSetsXFontello extends IconSetBase
{
    protected $data_file = 'config.json';
    protected $stylesheet_file = 'css/fontello.css';
    protected $allowed_zipped_files = ['config.json', 'LICENSE.txt', 'css/', 'font/'];

    protected function prepare()
    {
        $this->removeFontelloStyling();
    }

    public function getType()
    {
        return __('Fontello');
    }

    public function isValid()
    {
        if (!file_exists($this->directory . $this->data_file)) {
            return false; // missing data file
        }

        return true;
    }

    private function removeFontelloStyling()
    {
        $filename = $this->directory . $this->stylesheet_file;
        $stylesheet = @call_user_func('file_get_contents', $filename);
        $stylesheet = str_replace(['margin-left: .2em;', 'margin-right: .2em;'], ['', ''], $stylesheet);
        @call_user_func('file_put_contents', $filename, $stylesheet);
    }

    private function getJson()
    {
        return json_decode(@call_user_func('file_get_contents', $this->directory . $this->data_file));
    }

    protected function extractIconList()
    {
        $icons = [];
        $config = $this->getJson();

        if (!isset($config->glyphs)) {
            return false; // missing icons list
        }
        foreach ($config->glyphs as $icon) {
            $icons[] = $icon->css;
        }

        return $icons;
    }

    protected function getPrefix()
    {
        $config = $this->getJson();

        if (!isset($config->css_prefix_text)) {
            return false; // missing css_prefix_text
        }

        return $config->css_prefix_text;
    }

    public function getName()
    {
        $config = $this->getJson();

        return !empty($config->name) ? $config->name : 'fontello';
    }
}
