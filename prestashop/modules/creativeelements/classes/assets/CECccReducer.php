<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class CECccReducer extends CccReducer
{
    public function reduceCss($cssFileList)
    {
        return empty($cssFileList['external']) ? $cssFileList : parent::reduceCss($cssFileList);
    }

    protected function getPathFromUri($fullUri)
    {
        $fullUri = explode('?', $fullUri, 2)[0];

        if (__PS_BASE_URI__ !== '/' && stripos($fullUri, __PS_BASE_URI__) === 0) {
            return _PS_ROOT_DIR_ . substr($fullUri, strlen(__PS_BASE_URI__) - 1);
        }

        return _PS_ROOT_DIR_ . $fullUri;
    }
}
