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

class ModulesXFontsManagerXIconSetsXIcomoon extends IconSetBase
{
    protected $data_file = 'selection.json';
    protected $stylesheet_file = 'style.css';
    protected $allowed_zipped_files = ['selection.json', 'style.css', 'fonts/'];

    protected function prepare()
    {
        return [];
    }

    public function getType()
    {
        return __('Icomoon');
    }

    public function isValid()
    {
        if (!file_exists($this->directory . $this->data_file)) {
            return false; // missing data file
        }

        return true;
    }

    private function getJson()
    {
        return json_decode(@call_user_func('file_get_contents', $this->directory . $this->data_file));
    }

    protected function extractIconList()
    {
        $icons = [];
        $config = $this->getJson();

        if (!isset($config->icons)) {
            return false; //  missing icons list
        }
        foreach ($config->icons as $icon) {
            $icons[] = $icon->properties->name;
        }

        return $icons;
    }

    protected function getPrefix()
    {
        $config = $this->getJson();

        if (!isset($config->preferences->fontPref->prefix)) {
            return false; //  missing css_prefix_text
        }

        return $config->preferences->fontPref->prefix;
    }

    protected function getDisplayPrefix()
    {
        $config = $this->getJson();

        if (!isset($config->preferences->fontPref->classSelector)) {
            return false; //  missing css_prefix_text
        }

        return str_replace('.', '', $config->preferences->fontPref->classSelector);
    }

    public function getName()
    {
        $config = $this->getJson();

        return !empty($config->metadata->name) ? $config->metadata->name : 'icomoon';
    }
}
