<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ModulesXCatalogXWidgetsXListingXImage extends WidgetImage
{
    private $properties;

    public function getName()
    {
        return 'listing-image';
    }

    public function getTitle()
    {
        return isset($this->properties['title']) ? $this->properties['title'] : __('Listing Image');
    }

    public function getIcon()
    {
        return isset($this->properties['icon']) ? $this->properties['icon'] : 'eicon-image';
    }

    public function getCategories()
    {
        return ['listing-elements'];
    }

    public function getKeywords()
    {
        return isset($this->properties['keywords'])
            ? $this->properties['keywords']
            : ['shop', 'store', 'brand', 'manufacturer', 'image', 'picture', 'product', 'category'];
    }

    protected function getDynamicTagName()
    {
        return isset($this->properties['dynamic_tag_name']) ? $this->properties['dynamic_tag_name'] : 'listing-image';
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('image', [
            'show_label' => false,
            'dynamic' => [
                'active' => true,
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, $this->getDynamicTagName()),
            ],
            'default' => [],
        ]);

        $this->updateControl('link_to', [
            'options' => [
                'none' => __('None'),
                'custom' => __('Custom'),
            ],
        ]);

        $this->updateControl('text_color', ['scheme' => '']);

        $this->updateControl('caption_typography_font_family', ['scheme' => '']);
        $this->updateControl('caption_typography_font_weight', ['scheme' => '']);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-image';
    }

    public function renderPlainContent()
    {
    }

    public function __construct(array $data = [], $args = null, array $properties = [])
    {
        $this->properties = $properties;

        parent::__construct($data, $args);
    }
}
