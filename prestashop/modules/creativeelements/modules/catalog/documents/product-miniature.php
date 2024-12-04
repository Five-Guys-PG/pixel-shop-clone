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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\CoreXDynamicTagsXTag as Tag;
use CE\CoreXFilesXCSSXPost as PostCSS;
use CE\ModulesXCatalogXDocumentsXProduct as ProductDocument;

class ModulesXCatalogXDocumentsXProductMiniature extends ProductDocument
{
    public function getName()
    {
        return 'product-miniature';
    }

    public static function getTitle()
    {
        return __('Product Miniature');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = 'miniature';
        $config['autoImportSettings'] = true;

        return $config;
    }

    public function getCssWrapperSelector()
    {
        return '.elementor.elementor-' . uidval($this->getMainId())->toDefault();
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        $category = new \Category(\Context::getContext()->shop->id_category, $id_lang);

        if (isset($args['preview_id'])) {
            $args = ['id_miniature' => UId::parse($args['preview_id'])->id] + $args;
            unset($args['preview_id']);
        }

        return add_query_arg($args, $link->getCategoryLink($category));
    }

    public function getPreviewUrl()
    {
        static $url;

        if (null === $url) {
            $context = \Context::getContext();
            $settings = $this->getData('settings');
            $main_post_id = $this->getMainId();
            $uid = UId::parse($main_post_id);

            empty($settings['preview_id'])
                || $product = new \Product($settings['preview_id'], false, $uid->id_lang ?: $context->language->id);

            $url = $context->link->getModuleLink('creativeelements', 'preview', [
                'id_product' => !empty($product->id) ? $product->id : Helper::getLastUpdatedProductId($uid->getDefaultShopId() ?: $context->shop->id),
                'preview_id' => $main_post_id,
                'id_employee' => $context->employee->id,
                'cetoken' => \Tools::getAdminTokenLite($uid->getAdminController()),
                'ctx' => \Shop::getContext(),
                'ver' => time(),
            ], null, null, null, true);
        }

        return $url;
    }

    public static function getWidgetClasses()
    {
        return [
            'ProductXMiniatureXName',
            'ProductXBadges',
            'ProductXMiniatureXImage',
            'ProductXMiniatureXPrice',
            'ProductXMiniatureXRating',
            'ProductXSaleCountdown',
            'ManufacturerXImage',
            'ProductXMeta',
            'ProductXDescriptionShort',
            'ProductXMiniatureXVariants',
            'ProductXStock',
            'ProductXMiniatureXAddToCart',
            'ProductXFeatures',
            'ProductXAddToWishlist',
            'ProductXShare',
            'ProductXMiniatureXBox',
        ];
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->startInjection([
            'of' => 'preview_id',
            'at' => 'before',
        ]);

        $this->addResponsiveControl(
            'preview_width',
            [
                'label' => __('Width') . ' (px)',
                'type' => ControlsManager::NUMBER,
                'min' => 150,
                'default' => 360,
                'tablet_default' => 360,
                'mobile_default' => 360,
            ]
        );

        $this->endInjection();

        $this->startControlsSection(
            'section_style',
            [
                'label' => __('Background'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_background');

        $this->startControlsTab(
            'tab_background_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background',
                'selector' => '{{WRAPPER}} .elementor-section-wrap',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_background_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_hover',
                'selector' => '{{WRAPPER}} .elementor-section-wrap:hover',
            ]
        );

        $this->addControl(
            'background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-section-wrap' => '--e-background-transition-duration: {{SIZE}}s;',
                ],
                'condition' => [
                    'background_hover_background!' => '',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_border',
            [
                'label' => __('Border'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_border');

        $this->startControlsTab(
            'tab_border_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-section-wrap',
            ]
        );

        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-section-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementor-section-wrap',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_border_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_hover',
                'selector' => '{{WRAPPER}} .elementor-section-wrap:hover',
            ]
        );

        $this->addResponsiveControl(
            'border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-section-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}} .elementor-section-wrap:hover',
            ]
        );

        $this->addControl(
            'border_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'separator' => 'before',
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'background_hover_background',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'border_hover_border',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-section-wrap' => '--e-border-transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_advanced',
            [
                'label' => __('Advanced'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder' => [
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'css_classes',
            [
                'label' => __('CSS Classes'),
                'type' => ControlsManager::TEXT,
                'label_block' => false,
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class'),
            ]
        );

        $this->addControl(
            'article_classes',
            [
                'label' => __('Article Classes'),
                'type' => ControlsManager::TEXT,
                'label_block' => false,
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class'),
            ]
        );

        $this->addControl(
            'overflow',
            [
                'label' => __('Overflow'),
                'type' => ControlsManager::SELECT,
                'default' => 'hidden',
                'options' => [
                    '' => __('Default'),
                    'hidden' => __('Hidden'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-section-wrap' => 'overflow: {{VALUE}}; -webkit-backface-visibility: hidden; -webkit-transform: translate3d(0, 0, 0);',
                ],
            ]
        );

        $this->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this);

        if ($motionFx = Plugin::$instance->modules_manager->getModules('motion-effects')) {
            remove_action('elementor/element/section/section_effects/after_section_start', [$motionFx, 'addFxControls']);
            remove_action('elementor/element/column/section_effects/after_section_start', [$motionFx, 'addFxControls']);
            remove_action('elementor/element/common/section_effects/after_section_start', [$motionFx, 'addFxControls']);

            remove_action('elementor/element/section/section_background/before_section_end', [$motionFx, 'addBackgroundFxControls']);
            remove_action('elementor/element/column/section_style/before_section_end', [$motionFx, 'addBackgroundFxControls']);

            remove_action('elementor/element/section/section_effects/after_section_start', [$motionFx, 'addStickyControls']);
            remove_action('elementor/element/common/section_effects/after_section_start', [$motionFx, 'addStickyControls']);
        }
    }

    public static function filterSmartyCallback($match)
    {
        return preg_match(
            '/^{(' .
                '\$\w+(\.\w+|->\w+)*(\s+nofilter)?|' .
                '(hook|widget|include)(\s+\w+\s*=\s*("(\\\\.|[^"])*"|\'(\\\\.|[^\'])*\'|\$\w+(\.\w+)*|\w+))+' .
            ')\s*}$/s',
            $match[0]
        ) ? $match[0] : str_replace('{', '&#123;', $match[0]);
    }

    public static function filterElementsData(array &$data)
    {
        foreach ($data as &$value) {
            if (!$value) {
                continue;
            } elseif (is_array($value)) {
                self::filterElementsData($value);
            } elseif (is_string($value) && strpos($value, '{') !== false) {
                $value = preg_replace_callback('/{\S[^}]*}?/', [__CLASS__, 'filterSmartyCallback'], $value);
            }
        }
    }

    public static function filterAttributes($element)
    {
        $settings = $element->getRenderAttributes('_wrapper', 'data-settings');

        if ($settings && strpos($settings[0], '{') !== false) {
            $element->setRenderAttribute('_wrapper', 'data-settings', "{literal}$settings[0]{/literal}");
        }
    }

    public function save($data)
    {
        $result = parent::save($data);

        if ($result && !empty($data['elements']) && !defined('DOING_AUTOSAVE')) {
            $result &= $this->saveTpl($data['elements']);
        }

        return $result;
    }

    public function saveTpl($elements_data = null)
    {
        null === $elements_data && $elements_data = $this->getJsonMeta('_elementor_data');

        if (!did_action('elementor/widgets/widgets_registered')) {
            // Trigger init widgets
            Plugin::$instance->widgets_manager->getWidgetTypes('heading');
        }

        if ($elements_data && UId::THEME === uidval($this->post->ID)->id_type) {
            self::filterElementsData($elements_data);

            // Multishop compatibility
            $orig_id = $this->getMainId();
            $uid = UId::parse($orig_id);
            $main_id = &\Closure::bind(function &() {
                return $this->main_id;
            }, $this, 'CE\CoreXBaseXDocument')->__invoke();

            add_action('elementor/element/after_add_attributes', [__CLASS__, 'filterAttributes']);

            WidgetBase::setRenderMethod('renderSmarty');
            Tag::setRenderMethod('renderSmarty');
            DataTag::setGetterMethod('getSmartyValue');

            foreach (\Shop::getContextListShopID() as $id_shop) {
                $uid->id_shop = $id_shop;
                $main_id = (string) $uid;

                ob_start();
                $this->printSmartyElementsWithWrapper($elements_data);
                $result = @call_user_func(
                    'file_put_contents',
                    _CE_TEMPLATES_ . "front/theme/catalog/_partials/miniatures/product-$main_id.tpl",
                    preg_replace('/\s\s+/', "\n", Helper::minifyHtml(ob_get_clean()))
                );
                $post_css = new PostCSS($uid);
                $post_css->update();
            }
            $main_id = $orig_id;

            remove_action('elementor/element/after_add_attributes', [__CLASS__, 'filterAttributes']);

            WidgetBase::setRenderMethod('render');
            Tag::setRenderMethod('render');
            DataTag::setGetterMethod('getValue');
        }

        return !empty($result);
    }

    public function printSmartyElementsWithWrapper(&$elements_data)
    {
        $wrapper_tag = $this->getSettings('content_wrapper_html_tag') ?: 'div';
        $container = $this->getContainerAttributes();

        if ($css_classes = $this->getSettings('css_classes')) {
            $container['class'] .= " $css_classes";
        } else {
            $container['class'] .= '{if !empty($productClasses)} {$productClasses}{/if}';
        }
        $uid = $this->getMainId();
        $article = [
            'class' => trim('elementor-section-wrap ' . $this->getSettings('article_classes')),
            'data-id-product' => '{$product.id_product}',
            'data-id-product-attribute' => '{$product.id_product_attribute}',
        ]; ?>
        {* Generated by Creative Elements, do not modify it *}
        {ce_enqueue_miniature(<?php echo $uid; ?>)}
        <<?php echo $wrapper_tag; ?> <?php echo Utils::renderHtmlAttributes($container); ?>>
            <article <?php echo Utils::renderHtmlAttributes($article); ?>>
            <?php
            foreach ($elements_data as &$element_data) {
                if ($element = Plugin::$instance->elements_manager->createElementInstance($element_data)) {
                    $element->printElement();
                }
            } ?>
            </article>
        </<?php echo $wrapper_tag; ?>>
        <?php
    }

    public static function registerTags($dynamic_tags)
    {
        parent::registerTags($dynamic_tags);

        is_admin() && $dynamic_tags->unregisterTag('shortcode');
    }

    public static function registerWidgets($widgets_manager)
    {
        parent::registerWidgets($widgets_manager);

        if (is_admin()) {
            $widgets_manager->unregisterWidgetType('breadcrumb');
            $widgets_manager->unregisterWidgetType('product-box');
            $widgets_manager->unregisterWidgetType('product-grid');
            $widgets_manager->unregisterWidgetType('product-carousel');
        }
    }

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        add_action('elementor/element/common/_section_transform/after_section_end', function (ControlsStack $element) {
            $element->updateControl('_transform_trigger_hover', [
                'options' => [
                    '' => __('Widget'),
                    'column' => __('Column'),
                    'section' => __('Section'),
                    'miniature' => __('Miniature'),
                ],
            ]);

            $devices = ['', '_tablet', '_mobile'];
            $methods = ['perspective', 'rotateZ', 'rotateX', 'rotateY', 'translateX', 'translateY', 'scale', 'scaleX', 'scaleY', 'skewX', 'skewY'];

            foreach (['', '_hover'] as $tab) {
                foreach ($methods as $method) {
                    foreach ($devices as $device) {
                        $element->updateControl("_transform_{$method}_effect{$tab}{$device}", [
                            'frontend_available' => null,
                        ]);
                    }

                    $element->updateControl("_transform_flipX_effect$tab", [
                        'frontend_available' => null,
                    ]);

                    $element->updateControl("_transform_flipY_effect$tab", [
                        'frontend_available' => null,
                    ]);
                }
            }

            $element->updateControl('_position', [
                'frontend_available' => null,
            ]);

            \Configuration::get('elementor_element_cache_ttl') && $element->removeControl('_element_cache');
        });

        if (\CreativeElements::getPreviewUId(false) == (string) $this->getMainId()) {
            add_action('wp_footer', [__CLASS__, 'printPreviewFooter']);

            add_filter('template_include', function () {
                return _CE_TEMPLATES_ . 'front/theme/layouts/layout-canvas.tpl';
            }, 12);
        }
    }

    public static function printPreviewFooter()
    {
        ?>
        <style>
        html.elementor-html {
            background: #333;
        }
        body.ui-resizable {
            position: relative;
            background: transparent;
            height: auto;
            min-height: 0;
            max-width: calc(100% - 20px);
        }
        html.elementor-html,
        body > .ui-resizable-handle {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        body > .ui-resizable-e {
            right: -7px;
            justify-content: flex-end;
        }
        body > .ui-resizable-w {
            left: -7px;
            justify-content: flex-start;
        }
        body > .ui-resizable-handle:after {
            content: '';
            background: rgba(255, 255, 255, 0.2);
            height: 50px;
            width: 4px;
            border-radius: 3px;
            transition: all .2s ease-in-out;
        }
        body > .ui-resizable-handle:hover:after {
            background-color: rgba(255, 255, 255, 0.6);
            height: 100px;
        }
        body.ui-resizable-resizing > .ui-resizable-handle:after {
            background-color: rgba(255, 255, 255, 0.8);
        }
        body.ui-resizable > main {
            display: flex;
            flex-direction: column;
            position: relative;
            max-height: 100vh;
            overflow: auto;
        }
        .elementor[data-elementor-type="product-miniature"] {
            background: #fff;
        }
        .elementor-editor-column-settings {
            left: 0;
        }
        .elementor-editor-widget-settings {
            right: 0;
        }
        </style>
        <?php
    }
}
