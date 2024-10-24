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
 * Elementor repeater element.
 *
 * Elementor repeater handler class is responsible for initializing the repeater.
 *
 * @since 1.0.0
 */
class Repeater extends ElementBase
{
    /**
     * Repeater counter.
     *
     * Holds the Repeater counter data. Default is `0`.
     *
     * @since 1.0.0
     * @static
     *
     * @var int Repeater counter
     */
    private static $counter = 0;

    /**
     * Repeater constructor.
     *
     * Initializing Elementor repeater element.
     *
     * @since 1.0.7
     *
     * @param array $data Optional. Element data. Default is an empty array
     * @param array|null $args Optional. Element default arguments. Default is null
     */
    public function __construct(array $data = [], $args = null)
    {
        ++self::$counter;

        parent::__construct($data, $args);
    }

    /**
     * Get repeater name.
     *
     * Retrieve the repeater name.
     *
     * @since 1.0.7
     *
     * @return string Repeater name
     */
    public function getName()
    {
        return 'repeater-' . self::$counter;
    }

    /**
     * Get repeater type.
     *
     * Retrieve the repeater type.
     *
     * @since 1.0.0
     * @static
     *
     * @return string Repeater type
     */
    public static function getType()
    {
        return 'repeater';
    }

    /**
     * Add new repeater control to stack.
     *
     * Register a repeater control to allow the user to set/update data.
     *
     * This method should be used inside `_register_controls()`.
     *
     * @since 1.0.0
     *
     * @param string $id Repeater control ID
     * @param array $args Repeater control arguments
     * @param array $options Optional. Repeater control options. Default is an
     *                       empty array.
     *
     * @return bool True if repeater control added, False otherwise
     */
    public function addControl($id, array $args, $options = [])
    {
        $current_tab = $this->getCurrentTab();

        if (null !== $current_tab) {
            $args = array_merge($args, $current_tab);
        }

        return Plugin::$instance->controls_manager->addControlToStack($this, $id, $args, $options);
    }

    /**
     * Get default child type.
     *
     * Retrieve the repeater child type based on element data.
     *
     * Note that repeater does not support children, therefore it returns false.
     *
     * @since 1.0.0
     *
     * @param array $element_data Element ID
     *
     * @return false Repeater default child type or False if type not found
     */
    protected function _getDefaultChildType(array $element_data)
    {
        return false;
    }
}
