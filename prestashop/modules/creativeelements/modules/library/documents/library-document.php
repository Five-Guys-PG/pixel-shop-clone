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

use CE\CoreXBaseXDocument as Document;

/**
 * Elementor library document.
 *
 * Elementor library document handler class is responsible for handling
 * a document of the library type.
 *
 * @since 2.0.0
 */
abstract class ModulesXLibraryXDocumentsXLibraryDocument extends Document
{
    // const TAXONOMY_TYPE_SLUG = 'elementor_library_type';

    /**
     * Get document properties.
     *
     * Retrieve the document properties.
     *
     * @since 2.0.0
     * @static
     *
     * @return array Document properties
     */
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['admin_tab_group'] = 'library';
        $properties['show_in_library'] = true;
        $properties['register_type'] = true;

        return $properties;
    }

    /**
     * Get initial config.
     *
     * Retrieve the current element initial configuration.
     *
     * Adds more configuration on top of the controls list and the tabs assigned
     * to the control. This method also adds element name, type, icon and more.
     *
     * @since 2.9.0
     *
     * @return array The initial config
     */
    public function getInitialConfig()
    {
        $config = parent::getInitialConfig();

        $config['library'] = [
            'save_as_same_type' => true,
        ];

        return $config;
    }

    public function getPermalink()
    {
        return \Context::getContext()->link->getModuleLink('creativeelements', 'preview', [], null, null, null, true);
    }

    // public function printAdminColumnType()

    // public function saveTemplateType()
}
