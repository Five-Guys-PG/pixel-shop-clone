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

abstract class CoreXDebugXClassesXInspectionBase
{
    /**
     * @return bool
     */
    abstract public function run();

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getMessage();

    /**
     * @return string
     */
    public function getHeaderMessage()
    {
        return __('The preview could not be loaded');
    }

    /**
     * @return string
     */
    public function getHelpDocText()
    {
        return __('Learn More');
    }

    /**
     * @return string
     */
    abstract public function getHelpDocUrl();
}
