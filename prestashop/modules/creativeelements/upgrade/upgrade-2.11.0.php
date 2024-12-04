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

function upgrade_module_2_11_0($module)
{
    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    // Regenerate fonts list
    CEFont::generateFontsList();

    // Fix: DisplayOverrideTemplate hook does not run on PS 1.7.8.4
    if (_PS_VERSION_ === '1.7.8.4') {
        Db::getInstance()->update('hook', [
            'name' => 'DisplayOverrideTemplate',
        ], '`name` = "displayOverrideTemplate"', 1);
    }

    return $module->registerHook('actionClearCompileCache');
}
