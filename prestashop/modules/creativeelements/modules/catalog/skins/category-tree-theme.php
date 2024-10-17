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

class ModulesXCatalogXSkinsXCategoryTreeTheme extends SkinBase
{
    public function getId()
    {
        return 'theme';
    }

    public function getTitle()
    {
        return __('Theme');
    }

    protected function _registerControlsActions()
    {
        add_action('elementor/element/category-tree/section_style_nav/before_section_start', [$this, 'registerStyleSection']);
    }

    public function registerStyleSection()
    {
        $this->startControlsSection(
            'section_category_tree_style',
            [
                'label' => __('Category Tree'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'info',
            [
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'raw' => __('The style of default skin is affected by your theme.'),
            ]
        );

        $this->endControlsSection();
    }

    public function render()
    {
        $settings = $this->parent->getSettingsForDisplay();
        $category = $this->parent->getRootCategory($settings);
        $tpl = 'ps_categorytree/views/templates/hook/ps_categorytree.tpl';
        $theme_tpl = _PS_THEME_DIR_ . 'modules/' . $tpl;

        echo $GLOBALS['smarty']->fetch(file_exists($theme_tpl) ? $theme_tpl : _PS_MODULE_DIR_ . $tpl, null, null, [
            'currentCategory' => $category->id,
            'categories' => $this->parent->getCategoryTree($category, $settings['max_depth'], $settings['sort'], $settings['sort_way']),
        ]);
    }
}
