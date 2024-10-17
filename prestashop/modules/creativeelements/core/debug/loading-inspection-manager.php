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

use CE\CoreXDebugXClassesXMaintenance as Maintenance;

class CoreXDebugXLoadingInspectionManager
{
    public static $_instance;

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /** @var InspectionBase[] */
    private $inspections = [];

    public function registerInspections()
    {
        $this->inspections['maintenance'] = new Maintenance();
    }

    /**
     * @param InspectionBase $inspection
     */
    public function registerInspection($inspection)
    {
        $this->inspections[$inspection->getName()] = $inspection;
    }

    public function runInspections()
    {
        $debug_data = [
            'message' => __('We\'re sorry, but something went wrong. Click on \'Learn more\' and follow each of the steps to quickly solve it.'),
            'header' => __('The preview could not be loaded'),
            'doc_url' => 'https://go.elementor.com/preview-not-loaded/',
        ];
        foreach ($this->inspections as $inspection) {
            if (!$inspection->run()) {
                $debug_data = [
                    'message' => $inspection->getMessage(),
                    'header' => $inspection->getHeaderMessage(),
                    'doc_url' => $inspection->getHelpDocUrl(),
                    'doc_text' => $inspection->getHelpDocText(),
                    'error' => true,
                ];
                break;
            }
        }

        return $debug_data;
    }
}
