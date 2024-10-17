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

class ModulesXCatalogXWidgetsXListingXPageTitle extends WidgetHeading
{
    private $properties;

    public function getName()
    {
        return 'listing-page-title';
    }

    public function getTitle()
    {
        return isset($this->properties['title']) ? $this->properties['title'] : __('Page Title');
    }

    public function getIcon()
    {
        return isset($this->properties['icon']) ? $this->properties['icon'] : 'eicon-archive-title';
    }

    public function getCategories()
    {
        return isset($this->properties['categories']) ? $this->properties['categories'] : ['theme-elements'];
    }

    public function getKeywords()
    {
        return isset($this->properties['keywords']) ? $this->properties['keywords'] : ['listing', 'title', 'name'];
    }

    protected function getDynamicTagName()
    {
        return isset($this->properties['dynamic_tag_name']) ? $this->properties['dynamic_tag_name'] : 'page-title';
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
                'default' => Plugin::$instance->dynamic_tags->tagDataToTagText(null, $this->getDynamicTagName()),
            ],
            'default' => '',
        ]);

        $this->updateControl('header_size', ['default' => 'h1']);

        $this->updateControl('title_color', ['scheme' => '']);

        $this->updateControl('typography_font_family', ['scheme' => '']);
        $this->updateControl('typography_font_weight', ['scheme' => '']);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-heading';
    }

    protected function render()
    {
        parent::render();
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
