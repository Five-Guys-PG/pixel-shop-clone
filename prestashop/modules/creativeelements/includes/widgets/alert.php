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

/**
 * Elementor alert widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class WidgetAlert extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve alert widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'alert';
    }

    /**
     * Get widget title.
     *
     * Retrieve alert widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Alert');
    }

    /**
     * Get widget icon.
     *
     * Retrieve alert widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-alert';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     *
     * @return array Widget keywords
     */
    public function getKeywords()
    {
        return ['alert', 'notice', 'message'];
    }

    protected function isDynamicContent()
    {
        return false;
    }

    /**
     * Register alert widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_alert',
            [
                'label' => __('Alert'),
            ]
        );

        $this->addControl(
            'alert_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'info',
                'options' => [
                    'info' => __('Info'),
                    'success' => __('Success'),
                    'warning' => __('Warning'),
                    'danger' => __('Danger'),
                ],
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'alert_title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title'),
                'default' => __('This is an Alert'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->addControl(
            'alert_description',
            [
                'label' => __('Content'),
                'type' => ControlsManager::TEXTAREA,
                'placeholder' => __('Enter your description'),
                'default' => __('I am a description. Click the edit button to change this text.'),
                'separator' => 'none',
                'show_label' => false,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->addControl(
            'show_dismiss',
            [
                'label' => __('Dismiss Button'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'return_value' => 'show',
                'default' => 'show',
            ]
        );

        $this->addControl(
            'dismiss_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['none'],
                'recommended' => [
                    'ce-icons' => [
                        'times',
                        'close',
                    ],
                    'fa-solid' => [
                        'xmark',
                        'circle-xmark',
                    ],
                    'fa-regular' => [
                        'circle-xmark',
                    ],
                ],
                'default' => [
                    'value' => 'ceicon-times',
                    'library' => 'ce-icons',
                ],
                'condition' => [
                    'show_dismiss' => 'show',
                ],
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_type',
            [
                'label' => __('Alert'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'background',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'border_left-width',
            [
                'label' => __('Left Border Width'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'alert_title',
                'selector' => '{{WRAPPER}} .elementor-alert-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .elementor-alert-title',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_description',
            [
                'label' => __('Description'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'alert_description',
                'selector' => '{{WRAPPER}} .elementor-alert-description',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'description_shadow',
                'selector' => '{{WRAPPER}} .elementor-alert-description',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_dismiss_icon',
            [
                'label' => __('Dismiss Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_dismiss' => 'show',
                ],
            ]
        );

        $this->addResponsiveControl(
            'dismiss_icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert-dismiss i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-alert-dismiss svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'dismiss_icon_normal_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert-dismiss i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-alert-dismiss svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render alert widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if ('' === $settings['alert_title']) {
            return;
        }
        $this->addRenderAttribute('wrapper', 'class', 'elementor-alert elementor-alert-' . $settings['alert_type']);
        $this->addRenderAttribute('wrapper', 'role', 'alert');
        $this->addRenderAttribute('alert_title', 'class', 'elementor-alert-title');
        $this->addInlineEditingAttributes('alert_title', 'none');
        ?>
        <div <?php $this->printRenderAttributeString('wrapper'); ?>>
            <span <?php $this->printRenderAttributeString('alert_title'); ?>><?php echo $settings['alert_title']; ?></span>
        <?php if ('' !== $settings['alert_description']) {
            $this->addRenderAttribute('alert_description', 'class', 'elementor-alert-description');
            $this->addInlineEditingAttributes('alert_description'); ?>
            <span <?php $this->printRenderAttributeString('alert_description'); ?>>
                <?php echo $settings['alert_description']; ?>
            </span>
        <?php } ?>
        <?php if ('show' === $settings['show_dismiss']) { ?>
            <button type="button" class="elementor-alert-dismiss">
                <?php IconsManager::renderIcon($settings['dismiss_icon'], ['aria-hidden' => 'true']); ?>
                <span class="elementor-screen-only"><?php _e('Dismiss alert'); ?></span>
            </button>
        <?php } ?>
        </div>
        <?php
    }

    /**
     * Render alert widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 2.9.0
     */
    protected function contentTemplate()
    {
        ?>
        <# if ( settings.alert_title ) {
            view.addRenderAttribute( {
                alert_title: { class: 'elementor-alert-title' },
                alert_description: { class: 'elementor-alert-description' }
            } );

            view.addInlineEditingAttributes( 'alert_title', 'none' );
            view.addInlineEditingAttributes( 'alert_description' );
            #>
            <div class="elementor-alert elementor-alert-{{ settings.alert_type }}" role="alert">
                <span {{{ view.getRenderAttributeString( 'alert_title' ) }}}>{{{ settings.alert_title }}}</span>
                <span {{{ view.getRenderAttributeString( 'alert_description' ) }}}>
                    {{{ settings.alert_description }}}
                </span>
            <# if ( 'show' === settings.show_dismiss ) { #>
                <button type="button" class="elementor-alert-dismiss">
                    {{{ elementor.helpers.renderIcon( view, settings.dismiss_icon ) }}}
                    <span class="elementor-screen-only"><?php _e('Dismiss alert'); ?></span>
                </button>
            <# } #>
            </div>
        <# } #>
        <?php
    }
}
