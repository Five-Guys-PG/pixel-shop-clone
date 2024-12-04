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

use CE\CoreXSettingsXBaseXModel as BaseModel;

abstract class CoreXSettingsXBaseXCssModel extends BaseModel
{
    /**
     * Get CSS wrapper selector.
     *
     * Retrieve the wrapper selector for the current panel.
     *
     * @since 1.6.0
     * @abstract
     */
    abstract public function getCssWrapperSelector();
}
