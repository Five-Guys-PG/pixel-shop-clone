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

use CE\CoreXDocumentTypesXPost as Post;
use CE\ModulesXLibraryXDocumentsXLibraryDocument as LibraryDocument;

/**
 * Elementor page library document.
 *
 * Elementor page library document handler class is responsible for
 * handling a document of a page type.
 *
 * @since 2.0.0
 */
class ModulesXLibraryXDocumentsXPage extends LibraryDocument
{
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

        $properties['support_wp_page_templates'] = true;
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
        return 'page';
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
        return __('Page');
    }

    /**
     * @since 2.1.3
     */
    public function getCssWrapperSelector()
    {
        return 'body.elementor-page-' . $this->getMainId();
    }

    /**
     * @since 2.0.0
     */
    protected function _registerControls()
    {
        parent::_registerControls();

        Post::registerHideTitleControl($this);

        Post::registerStyleControls($this);
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['type'] = 'page';
        $config['default_route'] = 'templates/pages';
        $config['category'] = '';

        return $config;
    }
}
