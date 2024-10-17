<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\ModulesXCatalogXWidgetsXProductXVariants as ProductVariants;

class ModulesXCatalogXWidgetsXProductXMiniatureXVariants extends ProductVariants
{
    public function getName()
    {
        return 'product-miniature-variants';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_variations_style',
            [
                'label' => __('Product Variations'),
            ]
        );

        $this->addControl(
            'limit',
            [
                'label' => __('Limit'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => 5,
            ]
        );

        $this->addResponsiveControl(
            'align',
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__patterns' => 'justify-content: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_pattern_style',
            [
                'label' => __('Color/Texture Buttons'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'pattern_space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-variants__pattern' => 'margin: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
                    'body:not(.lang-rtl) {{WRAPPER}} .ce-product-variants' => 'margin: 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0',
                    'body.lang-rtl {{WRAPPER}} .ce-product-variants__pattern' => 'margin: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}} .ce-product-variants' => 'margin: 0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'count_typography',
                'exclude' => ['line_height'],
                'selector' => '{{WRAPPER}} .ce-product-variants__count',
            ]
        );

        $this->addControl(
            'count_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__count' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->startControlsTabs('tabs_style_pattern');

        $this->startControlsTab(
            'tab_pattern_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'pattern_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#818a91',
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_pattern_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'pattern_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_border_width_hover',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern:hover' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern:hover' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'pattern_padding_hover',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern:hover' => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addResponsiveControl(
            'pattern_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-product-variants__pattern' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'pattern_box_shadow',
                'selector' => '{{WRAPPER}} .ce-product-variants__pattern',
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;

        if (!$product['main_variants']) {
            return;
        }
        $limit = (int) $this->getSettings('limit');
        $count = count($product['main_variants']);
        ?>
        <div class="ce-product-variants ce-product-variants__patterns">
        <?php for ($i = 0; $i < $count && $i < $limit; ++$i) {
            $variant = $product['main_variants'][$i]; ?>
            <a class="ce-product-variants__pattern ce-product-variants__<?php echo $variant['texture'] ? 'texture' : 'color'; ?>"
                href="<?php echo esc_attr($variant['url']); ?>" title="<?php echo esc_attr($variant['name']); ?>"
            <?php if ($variant['texture']) { ?>
                style="background-image: url(<?php echo esc_attr($variant['texture']); ?>)"
            <?php } else { ?>
                style="background-color: <?php echo esc_attr($variant['html_color_code']); ?>"
            <?php } ?>></a>
        <?php } ?>
        <?php if ($count > $limit) { ?>
            <span class="ce-product-variants__pattern ce-product-variants__count">
                +<?php echo $count - $limit; ?>
            </span>
        <?php } ?>
        </div>
        <?php
    }

    protected function renderSmarty()
    {
        $limit = (int) $this->getSettings('limit');

        echo '{if $product.main_variants}';
        ?>
        <div class="ce-product-variants ce-product-variants__patterns">
        {foreach $product.main_variants as $i => $variant}
            {if $i < <?php echo $limit; ?>}
                <a class="ce-product-variants__pattern ce-product-variants__{if $variant.texture}texture{else}color{/if}"
                    href="{$variant.url}" title="{$variant.name}"
                {if $variant.texture}
                    style="background-image: url({$variant.texture})"
                {else}
                    style="background-color: {$variant.html_color_code}"
                {/if}></a>
            {else}
                <span class="ce-product-variants__pattern ce-product-variants__count">
                    +{$product.main_variants|count - <?php echo $limit; ?>}
                </span>
                {break}
            {/if}
        {/foreach}
        </div>
        <?php
        echo '{/if}';
    }
}
