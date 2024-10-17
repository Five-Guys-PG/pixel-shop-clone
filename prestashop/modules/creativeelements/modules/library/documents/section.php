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

use CE\ModulesXLibraryXDocumentsXLibraryDocument as LibraryDocument;

/**
 * Elementor section library document.
 *
 * Elementor section library document handler class is responsible for
 * handling a document of a section type.
 *
 * @since 2.0.0
 */
class ModulesXLibraryXDocumentsXSection extends LibraryDocument
{
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['support_kit'] = true;

        return $properties;
    }

    /**
     * Get document name.
     *
     * Retrieve the document name.
     *
     * @since 2.0.0
     *
     * @return string Document name
     */
    public function getName()
    {
        return 'section';
    }

    /**
     * Get document title.
     *
     * Retrieve the document title.
     *
     * @since 2.0.0
     * @static
     *
     * @return string Document title
     */
    public static function getTitle()
    {
        return __('Section');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = '';

        return $config;
    }
}
