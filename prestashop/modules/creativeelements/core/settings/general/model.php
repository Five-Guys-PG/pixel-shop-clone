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

use CE\CoreXSettingsXBaseXCssModel as CSSModel;
use CE\CoreXSettingsXGeneralXManager as Manager;

/**
 * Elementor global settings model.
 *
 * Elementor global settings model handler class is responsible for registering
 * and managing Elementor global settings models.
 *
 * @since 1.6.0
 */
class CoreXSettingsXGeneralXModel extends CSSModel
{
    /**
     * Get model name.
     *
     * Retrieve global settings model name.
     *
     * @since 1.6.0
     *
     * @return string Model name
     */
    public function getName()
    {
        return 'global-settings';
    }

    /**
     * Get CSS wrapper selector.
     *
     * Retrieve the wrapper selector for the global settings model.
     *
     * @since 1.6.0
     *
     * @return string CSS wrapper selector
     */
    public function getCssWrapperSelector()
    {
        return '';
    }

    /**
     * Get panel page settings.
     *
     * Retrieve the panel setting for the global settings model.
     *
     * @since 1.6.0
     *
     * @return array Panel settings {
     *
     * @var string $title The panel title
     * @var array $menu   The panel menu
     *
     * }
     */
    public function getPanelPageSettings()
    {
        return [
            'title' => __('Global Settings'),
        ];
    }

    /**
     * Get controls list.
     *
     * Retrieve the global settings model controls list.
     *
     * @since 1.6.0
     * @static
     *
     * @return array Controls list
     */
    public static function getControlsList()
    {
        return [
            ControlsManager::TAB_STYLE => [
                'style' => [
                    'label' => __('Style'),
                    'controls' => [
                        'elementor_default_generic_fonts' => [
                            'label' => __('Default Generic Fonts'),
                            'type' => ControlsManager::TEXT,
                            'default' => 'Sans-serif',
                            'description' => __('The list of fonts used if the chosen font is not available.'),
                            'label_block' => true,
                        ],
                        'elementor_stretched_section_container' => [
                            'label' => __('Stretched Section Fit To'),
                            'type' => ControlsManager::TEXT,
                            'placeholder' => 'body',
                            'description' => __('Enter parent element selector to which stretched sections will fit to (e.g. #primary / .wrapper / main etc). Leave blank to fit to page width.'),
                            'label_block' => true,
                            'frontend_available' => true,
                        ],
                        'elementor_page_title_selector' => [
                            'label' => __('Page Title Selector'),
                            'type' => ControlsManager::TEXTAREA,
                            'rows' => 1,
                            'placeholder' => 'header.page-header',
                            'description' => sprintf(
                                __("You can hide the title at document settings. This works for themes that have ”%s” selector. If your theme's selector is different, please enter it above."),
                                'header.page-header'
                            ),
                            'label_block' => true,
                        ],
                        'elementor_page_wrapper_selector' => [
                            'label' => __('Content Wrapper Selector'),
                            'type' => ControlsManager::TEXTAREA,
                            'rows' => 3,
                            'placeholder' => '#content, #wrapper, #wrapper .container',
                            'description' => sprintf(
                                __("You can clear margin, padding, max-width from content wrapper at document settings. This works for themes that have ”%s” selector. If your theme's selector is different, please enter it above."),
                                '#content, #wrapper, #wrapper .container'
                            ),
                            'label_block' => true,
                        ],
                    ],
                ],
            ],
            Manager::PANEL_TAB_LIGHTBOX => [
                'lightbox' => [
                    'label' => __('Lightbox'),
                    'controls' => [
                        'elementor_global_image_lightbox' => [
                            'label' => __('Image Lightbox'),
                            'type' => ControlsManager::SWITCHER,
                            'return_value' => '1',
                            'default' => '1',
                            'description' => __('Open all image links in a lightbox popup window. The lightbox will automatically work on any link that leads to an image file.'),
                            'frontend_available' => true,
                        ],
                        'elementor_lightbox_enable_counter' => [
                            'label' => __('Counter'),
                            'type' => ControlsManager::SWITCHER,
                            'default' => 'yes',
                            'frontend_available' => true,
                        ],
                        'elementor_lightbox_enable_fullscreen' => [
                            'label' => __('Fullscreen'),
                            'type' => ControlsManager::SWITCHER,
                            'default' => 'yes',
                            'frontend_available' => true,
                        ],
                        'elementor_lightbox_enable_zoom' => [
                            'label' => __('Zoom'),
                            'type' => ControlsManager::SWITCHER,
                            'default' => 'yes',
                            'frontend_available' => true,
                        ],
                        'elementor_lightbox_title_src' => [
                            'label' => __('Title'),
                            'type' => ControlsManager::SELECT,
                            'options' => [
                                '' => __('None'),
                                'title' => __('Title'),
                                'caption' => __('Caption'),
                                'alt' => __('Alt'),
                                'description' => __('Description'),
                            ],
                            'default' => 'title',
                            'frontend_available' => true,
                        ],
                        'elementor_lightbox_description_src' => [
                            'label' => __('Description'),
                            'type' => ControlsManager::SELECT,
                            'options' => [
                                '' => __('None'),
                                'title' => __('Title'),
                                'caption' => __('Caption'),
                                'alt' => __('Alt'),
                                'description' => __('Description'),
                            ],
                            'default' => 'caption',
                            'frontend_available' => true,
                        ],
                        'lightbox_style' => [
                            'type' => ControlsManager::RAW_HTML,
                            'raw' => __('Style Settings') . ': ' .
                                '<a href="javascript:void $e.run(\'panel/global/open\')' .
                                '.then(a=>$(\'.elementor-control-section_lightbox\').click())">' .
                                __('Theme Style') . ' ≫ ' . __('Lightbox') . '</a>',
                            'content_classes' => 'elementor-control-field-description',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Register model controls.
     *
     * Used to add new controls to the global settings model.
     *
     * @since 1.6.0
     */
    protected function _registerControls()
    {
        $controls_list = self::getControlsList();

        foreach ($controls_list as $tab_name => $sections) {
            foreach ($sections as $section_name => $section_data) {
                $this->startControlsSection(
                    $section_name,
                    [
                        'label' => $section_data['label'],
                        'tab' => $tab_name,
                    ]
                );

                foreach ($section_data['controls'] as $control_name => $control_data) {
                    $this->addControl($control_name, $control_data);
                }

                $this->endControlsSection();
            }
        }
    }
}
