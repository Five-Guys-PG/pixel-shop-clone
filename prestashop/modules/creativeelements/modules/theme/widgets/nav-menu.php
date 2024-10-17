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

use CE\CoreXResponsiveXResponsive as Responsive;
use CE\ModulesXCatalogXControlsXSelectCategory as SelectCategory;
use CE\ModulesXThemeXWidgetsXTraitsXNav as NavTrait;

class ModulesXThemeXWidgetsXNavMenu extends WidgetBase
{
    use NavTrait;

    protected $context;

    protected $show_category_thumbs;

    public function getName()
    {
        return 'nav-menu';
    }

    public function getTitle()
    {
        return __('Nav Menu');
    }

    public function getIcon()
    {
        return 'eicon-nav-menu';
    }

    public function getCategories()
    {
        return ['theme-elements'];
    }

    public function getKeywords()
    {
        return ['menu', 'nav', 'link', 'list', 'category', 'tree', 'accordion', 'burger'];
    }

    public function onExport($element)
    {
        unset($element['settings']['menu'], $element['settings']['linklist_hook']);

        return $element;
    }

    private function getMenuOptions()
    {
        $options = [
            'mainmenu' => __('Main Menu'),
            'categorytree' => __('Category Tree'),
            'linklist' => __('Link List'),
        ];
        \Tools::file_exists_cache(_PS_MODULE_DIR_ . 'prestablog/class/categories.php')
            && $options['prestablog'] = 'PrestaBlog';

        return $options;
    }

    private function getAvailableHooks()
    {
        $hooks = [];
        $rows = \Db::getInstance()->executeS('
            SELECT h.`name` FROM ' . _DB_PREFIX_ . 'link_block lb
            INNER JOIN ' . _DB_PREFIX_ . 'hook h ON h.`id_hook` = lb.`id_hook`
            ORDER BY h.`name`
        ');
        if ($rows) {
            foreach ($rows as &$row) {
                $hooks[$row['name']] = $row['name'];
            }
        }

        return $hooks;
    }

    public function getCategoryTree(\Category $category, $maxdepth, $sort, $desc)
    {
        $range = '';

        if (\Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = ' AND nleft >= ' . (int) $category->nleft . ' AND nright <= ' . (int) $category->nright;
        }
        $resultIds = [];
        $resultParents = [];
        $groups = \Customer::getGroupsStatic((int) $this->context->customer->id);
        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT c.`id_parent`, c.`id_category`, cl.`name`, cl.`link_rewrite` FROM ' . _DB_PREFIX_ . 'category c
            INNER JOIN ' . _DB_PREFIX_ . 'category_lang cl ON c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . \Shop::addSqlRestrictionOnLang('cl') . '
            INNER JOIN ' . _DB_PREFIX_ . 'category_shop cs ON cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . '
            WHERE (c.`active` = 1 OR c.`id_category` = ' . (int) \Configuration::get('PS_HOME_CATEGORY') . ')
            AND c.`id_category` <> ' . (int) \Configuration::get('PS_ROOT_CATEGORY') .
            ((int) $maxdepth !== 0 ? ' AND `level_depth` <= ' . (int) $maxdepth : '') .
            $range . '
            AND c.`id_category` IN (
                SELECT `id_category` FROM ' . _DB_PREFIX_ . 'category_group WHERE `id_group` IN (' . implode(',', array_map('intval', $groups)) . ')
            )
            ORDER BY `level_depth` ASC, ' . ($sort ? 'cl.`name`' : 'cs.`position`') . ' ' . ($desc ? 'DESC' : 'ASC')
        ) ?: [];

        foreach ($rows as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }

        return $this->getTree($resultParents, $resultIds, $maxdepth, $category->id);
    }

    private function getTree($resultParents, $resultIds, $maxDepth, $id_category, $currentDepth = 0)
    {
        $children = [];

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
        } else {
            $link = $name = '';
        }

        return [
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'children' => &$children,
        ];
    }

    public function getRootCategory(array &$settings)
    {
        $root_category = (int) $settings['root_category'];
        $id_lang = $this->context->language->id;

        if (4 === $root_category && $id_category = (int) $settings['id_category']) {
            return new \Category($id_category, $id_lang);
        }
        $this->setLastVisitedCategory();

        if ($root_category && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category) {
            $category = new \Category($this->context->cookie->last_visited_category, $id_lang);

            if (2 === $root_category && !$category->is_root_category && $category->id_parent
                || 3 === $root_category && !$category->is_root_category && !\Category::hasChildren($category->id, $id_lang)
            ) {
                $category = new \Category($category->id_parent, $id_lang);
            }
        } else {
            $category = new \Category((int) \Configuration::get('PS_HOME_CATEGORY'), $id_lang);
        }

        return $category;
    }

    protected function setLastVisitedCategory()
    {
        static $isset;

        if ($isset) {
            return;
        }
        $isset = true;

        if (method_exists($this->context->controller, 'getCategory') && $category = $this->context->controller->getCategory()) {
            $this->context->cookie->last_visited_category = $category->id;
        } elseif (method_exists($this->context->controller, 'getProduct') && $product = $this->context->controller->getProduct()) {
            if (!isset($this->context->cookie->last_visited_category)
                || !\Product::idIsOnCategoryId($product->id, [['id_category' => $this->context->cookie->last_visited_category]])
                || !\Category::inShopStatic($this->context->cookie->last_visited_category, $this->context->shop)
            ) {
                $this->context->cookie->last_visited_category = (int) $product->id_category_default;
            }
        }
    }

    protected function registerLayoutSection(array $args = [])
    {
        $is_admin = is_admin();

        $this->startControlsSection(
            'section_layout',
            $args + [
                'label' => __('Layout'),
            ]
        );

        $this->addControl(
            'menu',
            [
                'label' => __('Menu'),
                'type' => ControlsManager::SELECT,
                'options' => $is_admin ? $this->getMenuOptions() : [],
                'default' => 'mainmenu',
                'save_default' => true,
            ]
        );

        if ($is_admin && \Module::getInstanceByName('ps_mainmenu')) {
            $this->addControl(
                'mainmenu_description',
                [
                    'raw' => sprintf(
                        __("Go to the <a href='%s' target='_blank'>%s module</a> to manage your menu items."),
                        $this->context->link->getAdminLink('AdminModules', true, [], ['configure' => 'ps_mainmenu']),
                        __('Main Menu')
                    ),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'condition' => [
                        'menu' => 'mainmenu',
                    ],
                ]
            );
        } elseif ($is_admin) {
            $this->addControl(
                'mainmenu_description',
                [
                    'raw' => sprintf(__('%s module (%s) must be installed!'), __('Main Menu'), 'ps_mainmenu'),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => [
                        'menu' => 'mainmenu',
                    ],
                ]
            );
        }

        $this->addControl(
            'show_category_thumbs',
            [
                'label' => __('Category Menu Thumbnails'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'condition' => [
                    'menu' => ['mainmenu', 'categorytree'],
                ],
            ]
        );

        $ps_linklist = $is_admin && \Module::isEnabled('ps_linklist');

        $this->addControl(
            'linklist_hook',
            [
                'label' => __('Hook'),
                'type' => ControlsManager::SELECT,
                'default' => 'displayFooter',
                'options' => $ps_linklist ? $this->getAvailableHooks() : [],
                'condition' => [
                    'menu' => 'linklist',
                ],
            ]
        );

        if ($ps_linklist) {
            $this->addControl(
                'linklist_description',
                [
                    'raw' => sprintf(
                        __('Go to the <a href="%s" target="_blank">%s module</a> to manage your menu items.'),
                        $this->context->link->getAdminLink('AdminModules', true, [], ['configure' => 'ps_linklist']),
                        __('Link List')
                    ),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-descriptor',
                    'condition' => [
                        'menu' => 'linklist',
                    ],
                ]
            );
        } elseif ($is_admin) {
            $this->addControl(
                'linklist_description',
                [
                    'raw' => sprintf(__('%s module (%s) must be installed!'), __('Link List'), 'ps_linklist'),
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                    'condition' => [
                        'menu' => 'linklist',
                    ],
                ]
            );
        }

        $this->registerNavContentControls([
            'layout_options' => [
                'horizontal' => __('Horizontal'),
                'vertical' => __('Vertical'),
                'dropdown' => __('Dropdown'),
            ],
        ]);

        $this->addControl(
            'heading_mobile_dropdown',
            [
                'label' => __('Mobile Dropdown'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'layout!' => 'dropdown',
                ],
            ]
        );

        $breakpoints = Responsive::getBreakpoints();

        $this->addControl(
            'dropdown',
            [
                'label' => __('Breakpoint'),
                'type' => ControlsManager::SELECT,
                'default' => 'tablet',
                'options' => [
                    'mobile' => __('Mobile') . " (< {$breakpoints['md']}px)",
                    'tablet' => __('Tablet') . " (< {$breakpoints['lg']}px)",
                ],
                'prefix_class' => 'elementor-nav--dropdown-',
                'condition' => [
                    'layout!' => 'dropdown',
                ],
            ]
        );

        $this->addControl(
            'text_align',
            [
                'label' => __('Align'),
                'type' => ControlsManager::SELECT,
                'default' => 'aside',
                'options' => [
                    'aside' => __('Aside'),
                    'center' => __('Center'),
                ],
                'prefix_class' => 'elementor-nav--text-align-',
            ]
        );

        $this->addControl(
            'animation_dropdown',
            [
                'label' => __('Animation'),
                'type' => ControlsManager::SELECT,
                'default' => 'toggle',
                'options' => [
                    'toggle' => __('Toggle'),
                    'accordion' => __('Accordion'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'toggle',
            [
                'label' => __('Toggle Button'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'default' => 'burger',
                'options' => [
                    '' => [
                        'title' => __('None'),
                        'icon' => 'eicon-ban',
                    ],
                    'burger' => [
                        'title' => __('Hamburger'),
                        'icon' => 'eicon-menu-bar',
                    ],
                ],
                'prefix_class' => 'elementor-nav--toggle elementor-nav--',
                'render_type' => 'template',
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'toggle_align',
            [
                'label' => __('Toggle Align'),
                'type' => ControlsManager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'margin-right: auto',
                    'center' => 'margin: 0 auto',
                    'right' => 'margin-left: auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-toggle' => '{{VALUE}}',
                ],
                'condition' => [
                    'toggle!' => '',
                ],
                'label_block' => false,
            ]
        );

        $this->addControl(
            'full_width',
            [
                'label' => __('Full Width'),
                'type' => ControlsManager::SWITCHER,
                'description' => __('Stretch the dropdown of the menu to full width.'),
                'prefix_class' => 'elementor-nav--',
                'return_value' => 'stretch',
                'frontend_available' => true,
                'condition' => [
                    'toggle!' => '',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function registerCategoryTreeSection(array $args = [])
    {
        $this->startControlsSection(
            'section_category_tree',
            $args + [
                'label' => __('Category Tree'),
            ]
        );

        $this->addControl(
            'root_category',
            [
                'label' => __('Category Root'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => __('Home Category'),
                    '1' => __('Current Category'),
                    '2' => __('Parent Category'),
                    '3' => __('Current Category') . ' / ' . __('Parent Category'),
                    '4' => __('Custom Category'),
                ],
            ]
        );

        $this->addControl(
            'id_category',
            [
                'show_label' => false,
                'label_block' => true,
                'type' => SelectCategory::CONTROL_TYPE,
                'select2options' => [
                    'allowClear' => false,
                ],
                'default' => \Context::getContext()->shop->id_category,
                'condition' => [
                    'root_category' => '4',
                ],
            ]
        );

        $this->addControl(
            'max_depth',
            [
                'label' => __('Maximum Depth'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'default' => 4,
            ]
        );

        $this->addControl(
            'sort',
            [
                'label' => __('Sort'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => __('By Position'),
                    '1' => __('By Name'),
                ],
            ]
        );

        $this->addControl(
            'sort_way',
            [
                'label' => __('Sort Order'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => __('Ascending'),
                    '1' => __('Descending'),
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function registerToggleStyleSection(array $args = [])
    {
        $this->startControlsSection(
            'style_toggle',
            $args + [
                'label' => __('Toggle Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'toggle!' => '',
                ],
            ]
        );

        $this->startControlsTabs('tabs_toggle_style');

        $this->startControlsTab(
            'tab_toggle_style_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'toggle_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} div.elementor-menu-toggle' => 'color: {{VALUE}}', // Harder selector to override text color control
                ],
            ]
        );

        $this->addControl(
            'toggle_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-toggle' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_toggle_style_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'toggle_color_hover',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} div.elementor-menu-toggle:hover' => 'color: {{VALUE}}', // Harder selector to override text color control
                ],
            ]
        );

        $this->addControl(
            'toggle_background_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-toggle:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'toggle_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 15,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-toggle' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'toggle_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-toggle' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'toggle_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-menu-toggle' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function _registerControls()
    {
        $this->registerLayoutSection();

        $this->registerCategoryTreeSection([
            'condition' => [
                'menu' => 'categorytree',
            ],
        ]);

        $this->registerNavStyleSection([
            'devices' => ['desktop', 'tablet'],
            'scheme' => true,
            'condition' => [
                'layout!' => 'dropdown',
            ],
        ]);

        $this->registerDropdownStyleSection([
            'scheme' => true,
            'show_description' => true,
        ]);

        $this->registerToggleStyleSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $children = 'children';
        $this->indicator = isset($settings['indicator']) && !isset($settings['__fa4_migrated']['submenu_icon'])
            ? $settings['indicator']
            : $settings['submenu_icon']['value'];
        $this->show_category_thumbs = !empty($settings['show_category_thumbs']);

        if ('categorytree' === $settings['menu']) {
            $render_method = 'psCategoryTree';

            $menu = $this->getCategoryTree($this->getRootCategory($settings), $settings['max_depth'], $settings['sort'], $settings['sort_way']);
        } elseif ('mainmenu' === $settings['menu']) {
            $render_method = 'psMainMenu';

            if (!$module = \Module::getInstanceByName('ps_mainmenu')) {
                return;
            }
            $menu = $module->getWidgetVariables('displayCE', []);
        } elseif ('linklist' === $settings['menu']) {
            $render_method = 'psLinkList';
            $children = 'linkBlocks';

            if (!$module = \Module::getInstanceByName('ps_linklist')) {
                return;
            }
            $menu = $module->getWidgetVariables($settings['linklist_hook'], []);
        } elseif ('prestablog' === $settings['menu']) {
            $render_method = 'prestaBlogCategories';

            if (!\Tools::file_exists_cache(_PS_MODULE_DIR_ . 'prestablog/class/categories.php')) {
                return;
            }
            require_once _PS_MODULE_DIR_ . 'prestablog/class/categories.php';

            $menu = ['children' => \CategoriesClass::getListe($this->context->language->id, true)];
        }

        if (empty($menu[$children])) {
            return;
        }

        $ul_class = 'elementor-nav';

        if ('vertical' === $settings['layout']) {
            $ul_class .= ' sm-vertical';
        }

        ob_start();
        $this->$render_method($menu[$children], 0, $ul_class);
        $menu_html = ob_get_clean();

        $this->addRenderAttribute('menu-toggle', 'class', [
            'elementor-menu-toggle',
        ]);

        if (Plugin::$instance->editor->isEditMode()) {
            $this->addRenderAttribute('menu-toggle', [
                'class' => 'elementor-clickable',
            ]);
        }

        if ('dropdown' !== $settings['layout']) {
            $this->addRenderAttribute('main-menu', 'class', [
                'elementor-nav-menu',
                'elementor-nav--main',
                'elementor-nav__container',
                'elementor-nav--layout-' . $settings['layout'],
            ]);

            if ('none' !== $settings['pointer']) {
                $animation_type = self::getPointerAnimationType($settings['pointer']);

                $this->addRenderAttribute('main-menu', 'class', [
                    'e--pointer-' . $settings['pointer'],
                    'e--animation-' . $settings[$animation_type],
                ]);
            } ?>
            <nav <?php $this->printRenderAttributeString('main-menu'); ?>><?php echo $menu_html; ?></nav>
            <?php
        }
        // Don't render mobile menu when widget isn't visible on mobile
        if ('mobile' === $settings['dropdown'] && $settings['hide_mobile'] || 'tablet' === $settings['dropdown'] && $settings['hide_mobile'] && $settings['hide_tablet']) {
            return;
        } ?>
        <div <?php $this->printRenderAttributeString('menu-toggle'); ?>>
            <i class="fa" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e('Menu'); ?></span>
        </div>
        <nav class="elementor-nav--dropdown elementor-nav__container"><?php echo str_replace('menu-1-', 'menu-2-', $menu_html); ?></nav>
        <?php
    }

    protected function psMainMenu(array &$nodes, $depth = 0, $ul_class = '')
    {
        ?>
        <ul <?php echo $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="menu-1-' . $this->getId() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) { ?>
            <li class="<?php printf(self::$li_class, $node['type'], $node['page_identifier'], $node['current'] ? ' current-menu-item' : '', $node['children'] ? ' menu-item-has-children' : ''); ?>">
                <a class="<?php echo ($depth ? 'elementor-sub-item' : 'elementor-item') . (strpos($node['url'], '#') !== false ? ' elementor-item-anchor' : '') . ($node['current'] ? ' elementor-item-active' : ''); ?>" href="<?php echo esc_attr($node['url']); ?>"<?php $node['open_in_new_window'] && print ' target="_blank"'; ?>>
                <?php if ($this->show_category_thumbs && 'category' === $node['type'] && \Tools::file_exists_cache(_PS_CAT_IMG_DIR_ . ($id = explode('-', $node['page_identifier'])[1]) . '-0_thumb.jpg')) { ?>
                    <img class="cat-menu" src="<?php echo $this->context->link->getCatImageLink('', "$id-0_thumb"); ?>" alt="">
                <?php } ?>
                    <?php echo $node['label']; ?>
                <?php if ($this->indicator && $node['children']) { ?>
                    <span class="sub-arrow <?php echo esc_attr($this->indicator); ?>"></span>
                <?php } ?>
                </a>
                <?php empty($node['children']) || $this->psMainMenu($node['children'], $node['depth']); ?>
            </li>
        <?php } ?>
        </ul>
        <?php
    }

    protected function psCategoryTree(array &$nodes, $depth = 0, $ul_class = '')
    {
        $isProductOrCategoryController = $this->context->controller instanceof \ProductController || $this->context->controller instanceof \CategoryController;
        ?>
        <ul <?php echo $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="menu-1-' . $this->getId() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) {
            $current = $isProductOrCategoryController && $node['id'] == $this->context->cookie->last_visited_category; ?>
            <li class="<?php printf(self::$li_class, 'category', "category-{$node['id']}", $current ? ' current-menu-item' : '', $node['children'] ? ' menu-item-has-children' : ''); ?>">
                <a class="<?php echo ($depth ? 'elementor-sub-item' : 'elementor-item') . ($current ? ' elementor-item-active' : ''); ?>" href="<?php echo esc_attr($node['link']); ?>">
                <?php if ($this->show_category_thumbs && \Tools::file_exists_cache(_PS_CAT_IMG_DIR_ . "{$node['id']}-0_thumb.jpg")) { ?>
                    <img class="cat-menu" src="<?php echo $this->context->link->getCatImageLink('', "{$node['id']}-0_thumb"); ?>" alt="">
                <?php } ?>
                    <?php echo $node['name']; ?>
                <?php if ($this->indicator && $node['children']) { ?>
                    <span class="sub-arrow <?php echo esc_attr($this->indicator); ?>"></span>
                <?php } ?>
                </a>
                <?php empty($node['children']) || $this->psCategoryTree($node['children'], $depth + 1); ?>
            </li>
        <?php } ?>
        </ul>
        <?php
    }

    protected function psLinkList(array &$nodes, $depth = 0, $ul_class = '')
    {
        ?>
        <ul <?php echo $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="menu-1-' . $this->getId() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) { ?>
            <li class="<?php printf(self::$li_class, 'link', isset($node['url']) ? $node['id'] : "link-{$node['id']}", '', !empty($node['links']) ? ' menu-item-has-children' : ''); ?>">
                <a class="<?php echo $depth ? 'elementor-sub-item' : 'elementor-item'; ?>" href="<?php echo isset($node['url']) ? esc_attr($node['url']) : 'javascript:;'; ?>">
                    <?php echo $node['title']; ?>
                <?php if ($this->indicator && !empty($node['links'])) { ?>
                    <span class="sub-arrow <?php echo esc_attr($this->indicator); ?>"></span>
                <?php } ?>
                </a>
                <?php empty($node['links']) || $this->psLinkList($node['links'], $depth + 1); ?>
            </li>
        <?php } ?>
        </ul>
        <?php
    }

    protected function prestaBlogCategories(array &$nodes, $depth = 0, $ul_class = '')
    {
        ?>
        <ul <?php echo $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="menu-1-' . $this->getId() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) {
            $current = isset($this->context->smarty->tpl_vars['news']->value->categories[$node['id']])
                || isset($this->context->smarty->tpl_vars['prestablog_categorie_obj']) && $this->context->smarty->tpl_vars['prestablog_categorie_obj']->value->id == $node['id'];
            $link = \PrestaBlog::prestablogUrl([
                'c' => $node['id'],
                'titre' => $node['link_rewrite'] ?: $node['title'],
            ]); ?>
            <li class="<?php printf(self::$li_class, 'prestablog-category', "prestablog-category-{$node['id']}", '', $node['children'] ? ' menu-item-has-children' : ''); ?>">
                <a class="<?php echo ($depth ? 'elementor-sub-item' : 'elementor-item') . ($current ? ' elementor-item-active' : ''); ?>" href="<?php echo esc_attr($link); ?>">
                    <?php echo $node['title']; ?>
                <?php if ($this->indicator && $node['children']) { ?>
                    <span class="sub-arrow <?php echo esc_attr($this->indicator); ?>"></span>
                <?php } ?>
                </a>
                <?php empty($node['children']) || $this->prestaBlogCategories($node['children'], $depth + 1); ?>
            </li>
        <?php } ?>
        </ul>
        <?php
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
