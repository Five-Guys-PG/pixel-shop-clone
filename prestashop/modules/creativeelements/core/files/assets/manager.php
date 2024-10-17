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

use CE\CoreXFilesXAssetsXSvgXSvgHandler as SvgHandler;

/**
 * Elementor files manager.
 *
 * Elementor files manager handler class is responsible for creating files.
 *
 * @since 2.6.0
 */
class CoreXFilesXAssetsXManager
{
    /**
     * Holds registered asset types
     *
     * @var array
     */
    protected $asset_types = [];

    /**
     * Assets manager constructor.
     *
     * Initializing the Elementor assets manager.
     */
    public function __construct()
    {
        $this->registerAssetTypes();

        // do_action('elementor/core/files/assets/assets_registered', $this);
    }

    public function getAsset($name)
    {
        return isset($this->asset_types[$name]) ? $this->asset_types[$name] : false;
    }

    /**
     * Add Asset
     *
     * @param $instance
     */
    public function addAsset($instance)
    {
        $this->asset_types[$instance::getName()] = $instance;
    }

    /**
     * Register Asset Types
     *
     * Registers Elementor Asset Types
     */
    private function registerAssetTypes()
    {
        $this->addAsset(new SvgHandler());
    }
}
