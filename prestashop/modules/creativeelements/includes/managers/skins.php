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
 * Elementor skins manager.
 *
 * Elementor skins manager handler class is responsible for registering and
 * initializing all the supported skins.
 *
 * @since 1.0.0
 */
class SkinsManager
{
    /**
     * Registered Skins.
     *
     * Holds the list of all the registered skins for all the widgets.
     *
     * @since 1.0.0
     *
     * @var array Registered skins
     */
    private $_skins = [];

    /**
     * Add new skin.
     *
     * Register a single new skin for a widget.
     *
     * @since 1.0.0
     *
     * @param WidgetBase $widget Elementor widget
     * @param SkinBase $skin Elementor skin
     *
     * @return true True if skin added
     */
    public function addSkin(WidgetBase $widget, SkinBase $skin)
    {
        $widget_name = $widget->getName();

        if (!isset($this->_skins[$widget_name])) {
            $this->_skins[$widget_name] = [];
        }

        $this->_skins[$widget_name][$skin->getId()] = $skin;

        return true;
    }

    /**
     * Remove a skin.
     *
     * Unregister an existing skin from a widget.
     *
     * @since 1.0.0
     *
     * @param WidgetBase $widget Elementor widget
     * @param string $skin_id Elementor skin ID
     *
     * @return true|WPError True if skin removed, `WP_Error` otherwise
     */
    public function removeSkin(WidgetBase $widget, $skin_id)
    {
        $widget_name = $widget->getName();

        if (!isset($this->_skins[$widget_name][$skin_id])) {
            return new WPError('Cannot remove not-exists skin.');
        }

        unset($this->_skins[$widget_name][$skin_id]);

        return true;
    }

    /**
     * Get skins.
     *
     * Retrieve all the skins assigned for a specific widget.
     *
     * @since 1.0.0
     *
     * @param WidgetBase $widget Elementor widget
     *
     * @return false|array Skins if the widget has skins, False otherwise
     */
    public function getSkins(WidgetBase $widget)
    {
        $widget_name = $widget->getName();

        if (!isset($this->_skins[$widget_name])) {
            return false;
        }

        return $this->_skins[$widget_name];
    }

    /**
     * Skins manager constructor.
     *
     * Initializing Elementor skins manager by requiring the skin base class.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        require _CE_PATH_ . 'includes/base/skin-base.php';
    }
}
