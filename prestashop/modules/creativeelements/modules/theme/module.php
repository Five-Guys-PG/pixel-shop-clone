<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\CoreXBaseXModule as BaseModule;

class ModulesXThemeXModule extends BaseModule
{
    public function getName()
    {
        return 'theme';
    }

    public function registerDocuments($documents)
    {
        $class_prefix = 'CE\ModulesXThemeXDocumentsX';

        $documents->registerDocumentType('header', $class_prefix . 'Header');
        $documents->registerDocumentType('footer', $class_prefix . 'Footer');
        $documents->registerDocumentType('page-index', $class_prefix . 'PageIndex');
        $documents->registerDocumentType('page-contact', $class_prefix . 'PageContact');
        $documents->registerDocumentType('page-not-found', $class_prefix . 'PageNotFound');
    }

    public function registerWidgets($widgets_manager)
    {
        foreach ([
            'SiteLogo',
            'SiteTitle',
            // 'PageTitle',
            'NavMenu',
            'ShoppingCart',
            'AjaxSearch',
            'SignIn',
            'LanguageSelector',
            'CurrencySelector',
            'Breadcrumb',
        ] as $class_suffix) {
            $widget_class = 'CE\ModulesXThemeXWidgetsX' . $class_suffix;

            $widgets_manager->registerWidgetType(new $widget_class());
        }
    }

    public function __construct()
    {
        add_action('elementor/documents/register', [$this, 'registerDocuments']);
        add_action('elementor/widgets/widgets_registered', [$this, 'registerWidgets']);
    }
}
