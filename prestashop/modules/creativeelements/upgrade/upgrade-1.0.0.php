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

function upgrade_module_1_0_0($module)
{
    require_once _CE_PATH_ . 'classes/CEDatabase.php';
    require_once _CE_PATH_ . 'classes/CEMigrate.php';

    Shop::isFeatureActive() && Shop::setContext(Shop::CONTEXT_ALL);

    $res = $module->uninstallOverrides() && $module->installOverrides();

    CEDatabase::createTables();

    foreach (CEDatabase::getHooks(false) as $hook) {
        $res = $res && $module->registerHook($hook);
    }

    CEMigrate::moveConfigs();

    if ($res && CEMigrate::storeIds()) {
        ob_start(function ($json) use ($module) {
            $data = json_decode($json, true);

            if (!empty($data[$module->name]['status'])) {
                // Upgrade
                $data[$module->name]['msg'] .= CEMigrate::renderJavaScripts();

                $json = json_encode($data);
            } elseif (!empty($data['status'])) {
                // Upload
                $data['msg'] .= CEMigrate::renderJavaScripts();
                $data['status'] = false;

                $json = json_encode($data);
            }

            return $json;
        });
    }

    return $res;
}
