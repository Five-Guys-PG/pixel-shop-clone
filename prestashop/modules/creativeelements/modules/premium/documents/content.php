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

use CE\CoreXBaseXDocument as Document;
use CE\CoreXDocumentTypesXPost as Post;

class ModulesXPremiumXDocumentsXContent extends Document
{
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['support_kit'] = true;

        return $properties;
    }

    public function getName()
    {
        return 'content';
    }

    public static function getTitle()
    {
        return __('Content');
    }

    public function getCssWrapperSelector()
    {
        return '.elementor.elementor-' . uidval($this->getMainId())->toDefault();
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->addControl(
            'full_width',
            [
                'label' => __('Clear Content Wrapper'),
                'type' => ControlsManager::SWITCHER,
                'description' => sprintf(__(
                    'Not working? You can set a different selector for the content wrapper ' .
                    'in the <a href="%s" target="_blank">Settings page</a>.'
                ), Helper::getSettingsLink()),
                'selectors' => [
                    \Configuration::get('elementor_page_wrapper_selector') => 'min-width: 100%; margin: 0; padding: 0;',
                ],
            ],
            [
                'position' => [
                    'of' => 'post_status',
                ],
            ]
        );

        Post::registerStyleControls($this);

        $this->updateControl(
            'section_page_style',
            [
                'label' => __('Style'),
            ]
        );
    }

    public static function unregisterNonMaintenanceWidgets($widgets_manager)
    {
        foreach ([
            // Premium
            'product-grid',
            'product-carousel',
            'product-box',
            'layer-slider',
            'contact-form',
            'email-subscription',
            'image-slider',
            'category-tree',
            'ps-widget-module',
            // Site
            'nav-menu',
            'shopping-cart',
            'ajax-search',
            'sign-in',
            'language-selector',
            'currency-selector',
            'breadcrumb',
        ] as $widget_type) {
            $widgets_manager->unregisterWidgetType($widget_type);
        }
    }

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        if (isset($this->post->_obj->hook) && !strcasecmp($this->post->_obj->hook, 'displayMaintenance')) {
            add_action('elementor/dynamic_tags/register_tags', function ($dynamic_tags) {
                $dynamic_tags->unregisterTag('page-title');
            });
            did_action('elementor/widgets/widgets_registered')
                ? static::unregisterNonMaintenanceWidgets(Plugin::$instance->widgets_manager)
                : add_action('elementor/widgets/widgets_registered', [static::class, 'unregisterNonMaintenanceWidgets'])
            ;
        }
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = '';
        $config['type'] = 'page';
        $config['default_route'] = 'templates/pages';

        return $config;
    }
}
