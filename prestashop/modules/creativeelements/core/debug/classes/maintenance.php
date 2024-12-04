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

use CE\CoreXDebugXClassesXInspectionBase as InspectionBase;

class CoreXDebugXClassesXMaintenance extends InspectionBase
{
    public function run()
    {
        return !\CreativeElements::isMaintenance();
    }

    public function getName()
    {
        return 'maintenance';
    }

    public function getMessage()
    {
        return __('The shop is in maintenance mode, please whitelist your IP') . '. ';
    }

    public function getHelpDocText()
    {
        return __('Configure');
    }

    public function getHelpDocUrl()
    {
        return \Context::getContext()->link->getAdminLink('AdminMaintenance');
    }
}
