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
 * Elementor shortcode widget.
 *
 * Elementor widget that insert any shortcodes into the page.
 *
 * @since 1.0.0
 */
class WidgetShortcode extends WidgetBase
{
    const REMOTE_RENDER = true;

    /**
     * Get widget name.
     *
     * Retrieve shortcode widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name
     */
    public function getName()
    {
        return 'shortcode';
    }

    /**
     * Get widget title.
     *
     * Retrieve shortcode widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title
     */
    public function getTitle()
    {
        return __('Shortcode');
    }

    /**
     * Get widget icon.
     *
     * Retrieve shortcode widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon
     */
    public function getIcon()
    {
        return 'eicon-shortcode';
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
        return ['shortcode', 'code', 'hook'];
    }

    /**
     * Register shortcode widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_shortcode',
            [
                'label' => __('Shortcode'),
            ]
        );

        $this->addControl(
            'shortcode',
            [
                'label' => __('Enter your shortcode'),
                'type' => ControlsManager::TEXTAREA,
                'dynamic' => [
                    // 'active' => true,
                ],
                'placeholder' => "{hook h='displayShortcode'}",
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render shortcode widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        echo do_shortcode($this->getSettings('shortcode'));
    }

    protected function renderSmarty()
    {
        echo $this->getSettings('shortcode');
    }

    /**
     * Render shortcode widget as plain content.
     *
     * Override the default behavior by printing the shortcode instead of rendering it.
     *
     * @since 1.0.0
     */
    public function renderPlainContent()
    {
        // In plain mode, render without shortcode
        echo $this->getSettings('shortcode');
    }
}
