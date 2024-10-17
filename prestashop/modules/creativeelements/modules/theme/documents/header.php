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

use CE\ModulesXThemeXDocumentsXHeaderFooterBase as HeaderFooterBase;

class ModulesXThemeXDocumentsXHeader extends HeaderFooterBase
{
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['location'] = 'header';

        return $properties;
    }

    public function getName()
    {
        return 'header';
    }

    public static function getTitle()
    {
        return __('Header');
    }
}
