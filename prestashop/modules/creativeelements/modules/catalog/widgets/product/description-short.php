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

class ModulesXCatalogXWidgetsXProductXDescriptionShort extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-description-short';
    }

    public function getTitle()
    {
        return __('Short Description');
    }

    public function getIcon()
    {
        return 'eicon-product-description';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'text', 'short', 'description', 'summary', 'product'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_style',
            [
                'label' => __('Short Description'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'text_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .ce-product-description-short',
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-description-short' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'min_height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-description-short' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'line_clamp',
            [
                'label' => __('Max Lines'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-description-short' => '-webkit-line-clamp: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-text-editor';
    }

    protected function render()
    {
        $document = Plugin::$instance->documents->getCurrent();
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;

        if ($document && $document->getTemplateType() === 'product-miniature') {
            $product['description_short'] = strip_tags($product['description_short']);
        } ?>
        <div class="ce-product-description-short"><?php echo $product['description_short']; ?></div>
        <?php
    }

    protected function renderSmarty()
    {
        ?>
        <div class="ce-product-description-short">{$product.description_short|strip_tags:0}</div>
        <?php
    }

    public function renderPlainContent()
    {
    }
}
