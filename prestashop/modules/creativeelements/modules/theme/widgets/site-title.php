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

class ModulesXThemeXWidgetsXSiteTitle extends WidgetHeading
{
    public function getName()
    {
        return 'theme-site-title';
    }

    public function getTitle()
    {
        return __('Shop Title');
    }

    public function getIcon()
    {
        return 'eicon-site-title';
    }

    public function getCategories()
    {
        return ['theme-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'title', 'name'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('title', [
            'dynamic' => [
                'active' => true,
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, 'site-title'),
            ],
            'default' => \Configuration::get('PS_SHOP_NAME'),
        ]);

        $this->updateControl('link', [
            'dynamic' => [
                'active' => true,
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, 'site-url'),
            ],
            'default' => ['url' => __PS_BASE_URI__],
        ]);

        $this->updateControl('title_color', ['scheme' => '']);

        $this->updateControl('typography_font_family', ['scheme' => '']);
        $this->updateControl('typography_font_weight', ['scheme' => '']);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading';
    }
}
