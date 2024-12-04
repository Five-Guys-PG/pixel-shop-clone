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

class ModulesXThemeXWidgetsXBreadcrumb extends WidgetBase
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'breadcrumb';
    }

    public function getTitle()
    {
        return __('Breadcrumb');
    }

    public function getIcon()
    {
        return 'eicon-product-breadcrumbs';
    }

    public function getCategories()
    {
        return [
            'theme-elements',
            'product-elements' => 0,
            'listing-elements' => 0,
        ];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_breadcrumb',
            [
                'label' => __('Breadcrumb'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'classic' => __('Classic'),
                    'theme' => __('Theme'),
                ],
                // BC Fix
                'default' => $this->getId() ? 'theme' : 'classic',
                'save_default' => true,
                'render_type' => 'template',
            ]
        );

        $this->addControl(
            'separator',
            [
                'type' => ControlsManager::DIVIDER,
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-row' => 'margin: 0 calc({{SIZE}}{{UNIT}}/-2);',
                    '{{WRAPPER}} .ce-breadcrumb__item > *' => 'padding: 0 calc({{SIZE}}{{UNIT}}/2)',
                ],
                'condition' => [
                    'skin' => 'classic',
                ],
            ]
        );

        $this->addControl(
            'breadcrumb_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li:not(#e), {{WRAPPER}} .ce-breadcrumb__item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'breadcrumb_link_color',
            [
                'label' => __('Link Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li a:not(#e), {{WRAPPER}} .ce-breadcrumb__item a:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'breadcrumb_link_color_hover',
            [
                'label' => __('Link Hover Color'),
                'type' => ControlsManager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li a:not(#e):hover, {{WRAPPER}} .ce-breadcrumb__item a:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'breadcrumb_typography',
                'selector' => '{{WRAPPER}} .breadcrumb li:not(#e), {{WRAPPER}} .ce-breadcrumb__item',
            ]
        );

        $this->addResponsiveControl(
            'breadcrumb_align',
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
                    '{{WRAPPER}} .breadcrumb:not(#e), {{WRAPPER}} .elementor-row' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'divider',
            [
                'label' => __('Divider'),
                'label_block' => true,
                'classes' => 'elementor-control-type-heading',
                'type' => ControlsManager::SELECT2,
                'select2options' => [
                    'allowClear' => false,
                    'tags' => true,
                ],
                'options' => [
                    '/' => ' &nbsp;/&nbsp; Slash',
                    '／' => '／ Fullwidth Slash',
                    '\\' => '&nbsp;\\&nbsp; Back Slash',
                    '＼' => '＼ Fullwidth Back Slash',
                    '〉' => '&nbsp;〉&nbsp; Medium Angle Bracket',
                    '❱' => '&nbsp;❱&nbsp; Heavy Angle Bracket',
                    '›' => '&nbsp;›&ensp; Angle Quotation Mark',
                    '»' => '&nbsp;»&nbsp; Double Angle Quotation Mark',
                    '❯' => '&nbsp;❯&nbsp; Heavy Angle Quotation Mark',
                    '>' => '&nbsp;>&nbsp; Greater-Than Sign',
                    '≫' => '≫ Much Greater-Than Sign',
                    '＞' => '＞ Fullwidth Greater-Than Sign',
                    '˃' => '&nbsp;˃&nbsp; Arrowhead',
                    '▸' => '&nbsp;▸ Small Triangle',
                    '▹' => '&nbsp;▹ Outline Small Triangle',
                    '⏵' => '⏵&nbsp; Medium Triangle',
                    '▶' => '▶&nbsp; Triangle',
                    '▷' => '▷&nbsp; Outline Triangle',
                    '►' => '►&nbsp; Pointer',
                    '▻' => '&nbsp;▻ Outline Pointer',
                    '→' => '→ Arrow',
                    '⇾' => '&nbsp;⇾ Open-Headed Arrow',
                    '⇒' => '&nbsp;⇒ Double Arrow',
                    '⇨' => '&nbsp;⇨ Outline Arrow',
                    '➔' => '➔ Heavy Wide-Headed Arrow',
                    '➜' => '➜ Heavy Round-Tipped Arrow',
                    '➝' => '➝ Triangle-Headed Arrow',
                    '➛' => '&nbsp;➛ Drafting Point Arrow',
                    '☛' => '&nbsp;☛ Pointing Index',
                    '☞' => '&nbsp;☞ Outline Pointing Index',
                ],
                'selectors_dictionary' => [
                    '\\' => '\005C',
                ],
                'default' => '/',
                'selectors' => [
                    '{{WRAPPER}} .ce-breadcrumb__item:not(:last-child):after' => 'content: "{{VALUE}}"',
                ],
                'separator' => 'before',
                'condition' => [
                    'skin' => 'classic',
                ],
            ]
        );

        $this->addControl(
            'divider_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-breadcrumb__item:not(:last-child):after' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'skin' => 'classic',
                ],
            ]
        );

        $this->addControl(
            'divider_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ddd',
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ce-breadcrumb__item:not(:last-child):after' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'skin' => 'classic',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if ('theme' === $settings['skin']) {
            return print smartyInclude(['file' => '_partials/breadcrumb.tpl']);
        }
        $breadcrumb = $GLOBALS['smarty']->tpl_vars['breadcrumb']->value;

        if (!$count = (int) $breadcrumb['count']) {
            return;
        } ?>
        <nav class="ce-breadcrumb" data-depth="<?php echo $count; ?>" aria-label="<?php esc_attr_e('Breadcrumb'); ?>">
            <ol class="elementor-row">
            <?php foreach ($breadcrumb['links'] as $i => $link) { ?>
                <li class="ce-breadcrumb__item">
                <?php if ($i + 1 < $count) { ?>
                    <a href="<?php echo esc_attr($link['url']); ?>"><?php echo $link['title']; ?></a>
                <?php } else { ?>
                    <span><?php echo $link['title']; ?></span>
                <?php } ?>
                </li>
            <?php } ?>
            </ol>
        </nav>
        <?php
    }
}
