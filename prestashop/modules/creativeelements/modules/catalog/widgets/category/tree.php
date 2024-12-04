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

use CE\ModulesXCatalogXSkinsXCategoryTreeTheme as ThemeSkin;
use CE\ModulesXThemeXWidgetsXNavMenu as NavMenu;

class ModulesXCatalogXWidgetsXCategoryXTree extends NavMenu
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'category-tree';
    }

    public function getTitle()
    {
        return __('Category Tree');
    }

    public function getIcon()
    {
        return 'eicon-toggle eicon-flip-horizontal';
    }

    public function getCategories()
    {
        return ['catalog', 'listing-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'menu', 'nav', 'category', 'tree', 'accordion', 'burger'];
    }

    protected function _registerSkins()
    {
        $this->addSkin(new ThemeSkin($this));
    }

    protected function registerSkinControl()
    {
        $skin_options = [
            '' => __('Classic'),
        ];
        foreach ($this->getSkins() as $skin_id => $skin) {
            $skin_options[$skin_id] = $skin->getTitle();
        }

        $this->addControl(
            '_skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'options' => $skin_options,
                // BC Fix
                'default' => $this->getId() ? 'theme' : '',
                'save_default' => true,
                'separator' => 'after',
            ],
            ['overwrite' => true]
        );
    }

    protected function _registerControls()
    {
        $this->registerCategoryTreeSection();

        $this->registerLayoutSection([
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->updateControl('menu', [
            'type' => ControlsManager::HIDDEN,
            'default' => 'categorytree',
            'save_default' => false,
        ]);

        $this->updateControl('layout', [
            'default' => 'dropdown',
            'separator' => '',
        ]);

        $this->updateControl('pointer', [
            'default' => 'background',
        ]);

        $this->updateControl('toggle', [
            'default' => '',
        ]);

        $this->registerNavStyleSection([
            'devices' => ['desktop', 'tablet'],
            'condition' => [
                '_skin' => '',
                'layout!' => 'dropdown',
            ],
        ]);

        $this->registerDropdownStyleSection([
            'show_description' => true,
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->registerToggleStyleSection([
            'condition' => [
                '_skin' => '',
                'toggle!' => '',
            ],
        ]);
    }
}
