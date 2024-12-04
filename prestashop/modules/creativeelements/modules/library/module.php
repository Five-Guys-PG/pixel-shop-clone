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

use CE\CoreXBaseXModule as BaseModule;
use CE\ModulesXLibraryXDocumentsXNotSupported as NotSupported;
use CE\ModulesXLibraryXDocumentsXPage as Page;
use CE\ModulesXLibraryXDocumentsXSection as Section;

/**
 * Elementor library module.
 *
 * Elementor library module handler class is responsible for registering and
 * managing Elementor library modules.
 *
 * @since 2.0.0
 */
class ModulesXLibraryXModule extends BaseModule
{
    /**
     * Get module name.
     *
     * Retrieve the library module name.
     *
     * @since 2.0.0
     *
     * @return string Module name
     */
    public function getName()
    {
        return 'library';
    }

    /**
     * Library module constructor.
     *
     * Initializing Elementor library module.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        Plugin::$instance->documents
            ->registerDocumentType('not-supported', NotSupported::getClassFullName())
            ->registerDocumentType('page', Page::getClassFullName())
            ->registerDocumentType('section', Section::getClassFullName());
    }
}
