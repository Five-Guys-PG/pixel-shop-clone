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

class AdminCESettingsController extends ModuleAdminController
{
    protected $activate_url = 'https://pagebuilder.webshopworks.com/?connect=activate';

    protected $viewportChanged = false;

    protected $clearCss = false;

    protected $sub_tab;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->className = 'CESettings';
        $this->table = 'configuration';
        $this->sub_tab = Tools::getValue('subTab', 'general');

        parent::__construct();

        $this->fields_options['general'] = [
            'tab' => $this->l('General'),
            'icon' => 'icon-cog',
            'title' => $this->l('General Settings'),
            'fields' => [
                'subTab' => [
                    'type' => 'hidden',
                ],
                'elementor_frontend_edit' => [
                    'title' => $this->l('Show Edit Icon on Frontend'),
                    'desc' => $this->l('Displays an edit icon on frontend while employee has active session. By clicking on this icon the live editor will open.'),
                    'type' => 'bool',
                    'defaultValue' => self::zeroSafeDefault('elementor_frontend_edit', 1),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'elementor_max_revisions' => [
                    'title' => $this->l('Limit Revisions'),
                    'desc' => $this->l('Sets the maximum number of revisions per content.'),
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => [
                        ['value' => 0, 'name' => $this->l('Disable Revision History')],
                        ['value' => 1, 'name' => 1],
                        ['value' => 2, 'name' => 2],
                        ['value' => 3, 'name' => 3],
                        ['value' => 4, 'name' => 4],
                        ['value' => 5, 'name' => 5],
                        ['value' => 10, 'name' => "10 ({$this->l('Default')})"],
                        ['value' => 15, 'name' => 15],
                        ['value' => 20, 'name' => 20],
                        ['value' => 25, 'name' => 25],
                        ['value' => 30, 'name' => 30],
                    ],
                    'defaultValue' => self::zeroSafeDefault('elementor_max_revisions', 10),
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                ],
                'elementor_disable_color_schemes' => [
                    'title' => $this->l('Disable Default Colors'),
                    'desc' => $this->l('If you prefer to inherit the colors from your theme, you can disable this feature.'),
                    'type' => 'bool',
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'clearCss' => true,
                ],
                'elementor_disable_typography_schemes' => [
                    'title' => $this->l('Disable Default Fonts'),
                    'desc' => $this->l('If you prefer to inherit the fonts from your theme, you can disable this feature here.'),
                    'type' => 'bool',
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'clearCss' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        $this->fields_options['style'] = [
            'tab' => $this->l('Style'),
            'icon' => 'icon-adjust',
            'title' => $this->l('Style Settings'),
            'fields' => [
                'elementor_default_generic_fonts' => [
                    'title' => $this->l('Default Generic Fonts'),
                    'desc' => $this->l('The list of fonts used if the chosen font is not available.'),
                    'type' => 'text',
                    'defaultValue' => 'sans-serif',
                    'cast' => 'strval',
                    'class' => 'fixed-width-xxl',
                ],
                'elementor_stretched_section_container' => [
                    'title' => $this->l('Stretched Section Fit To'),
                    'desc' => $this->l('Enter parent element selector to which stretched sections will fit to (e.g. #primary / .wrapper / main etc). Leave blank to fit to page width.'),
                    'type' => 'text',
                    'cast' => 'strval',
                    'class' => 'fixed-width-xxl',
                ],
                'elementor_page_title_selector' => [
                    'title' => $this->l('Page Title Selector'),
                    'desc' => sprintf(
                        $this->l("You can hide the title at document settings. This works for themes that have ”%s” selector. If your theme's selector is different, please enter it above."),
                        'header.page-header'
                    ),
                    'type' => 'text',
                    'defaultValue' => 'header.page-header',
                    'cast' => 'strval',
                ],
                'elementor_page_wrapper_selector' => [
                    'title' => $this->l('Content Wrapper Selector'),
                    'desc' => sprintf(
                        $this->l("You can clear margin, padding, max-width from content wrapper at document settings. This works for themes that have ”%s” selector. If your theme's selector is different, please enter it above."),
                        '#content, #wrapper, #wrapper .container'
                    ),
                    'type' => 'text',
                    'defaultValue' => '#content, #wrapper, #wrapper .container',
                    'cast' => 'strval',
                ],
                'elementor_viewport_lg' => [
                    'title' => $this->l('Tablet Breakpoint'),
                    'desc' => sprintf($this->l('Sets the breakpoint between desktop and tablet devices. Below this breakpoint tablet layout will appear (Default: %dpx).'), 1025),
                    'type' => 'text',
                    'defaultValue' => 1025,
                    'suffix' => 'px',
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                    'class' => 'fixed-width-sm',
                ],
                'elementor_viewport_md' => [
                    'title' => $this->l('Mobile Breakpoint'),
                    'desc' => sprintf($this->l('Sets the breakpoint between tablet and mobile devices. Below this breakpoint mobile layout will appear (Default: %dpx).'), 768),
                    'type' => 'text',
                    'defaultValue' => 768,
                    'suffix' => 'px',
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                    'class' => 'fixed-width-sm',
                ],
                'elementor_global_image_lightbox' => [
                    'title' => $this->l('Image Lightbox'),
                    'desc' => $this->l('Open all image links in a lightbox popup window. The lightbox will automatically work on any link that leads to an image file.'),
                    'hint' => $this->l('You can customize the lightbox design by going to: Top-left hamburger icon > Global Settings > Lightbox.'),
                    'type' => 'bool',
                    'defaultValue' => self::zeroSafeDefault('elementor_global_image_lightbox', 1),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        $this->fields_options['advanced'] = [
            'tab' => $this->l('Advanced'),
            'icon' => 'icon-cogs',
            'title' => $this->l('Advanced Settings'),
            'info' => CESmarty::sprintf(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_alert', 'warning', $this->l(
                'Do not change these options without experience, incorrect settings might break your site.'
            )),
            'fields' => [
                'elementor_element_cache_ttl' => [
                    'title' => $this->l('Element Cache'),
                    'desc' => $this->l('Specify the duration for which data is stored in the cache. Elements caching speeds up loading by serving pre-rendered copies of elements, rather than rendering them fresh each time. This control ensures efficient performance and up-to-date content.'),
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => [
                        ['value' => 0, 'name' => $this->l('Disable')],
                        ['value' => 1, 'name' => sprintf($this->l('%s hour'), 1)],
                        ['value' => 6, 'name' => sprintf($this->l('%s hours'), 6)],
                        ['value' => 12, 'name' => sprintf($this->l('%s hours'), 12)],
                        ['value' => 24, 'name' => sprintf($this->l('%s day'), 1) . " ({$this->l('Default')})"],
                        ['value' => 72, 'name' => sprintf($this->l('%s days'), 3)],
                        ['value' => 168, 'name' => sprintf($this->l('%s week'), 1)],
                        ['value' => 336, 'name' => sprintf($this->l('%s weeks'), 2)],
                        ['value' => 720, 'name' => sprintf($this->l('%s month'), 1)],
                        ['value' => 8760, 'name' => sprintf($this->l('%s year'), 1)],
                    ],
                    'cast' => 'intval',
                ],
                'elementor_css_print_method' => [
                    'title' => $this->l('CSS Print Method'),
                    'desc' => $this->l('Use external CSS files for all generated stylesheets. Choose this setting for better performance (recommended).'),
                    'type' => 'select',
                    'identifier' => 'value',
                    'list' => [
                        ['value' => 'external', 'name' => $this->l('External File')],
                        ['value' => 'internal', 'name' => $this->l('Internal Embedding')],
                    ],
                    'cast' => 'strval',
                    'clearCss' => true,
                ],
                'elementor_assets_priority' => [
                    'title' => $this->l('CSS & JS Priority'),
                    'desc' => $this->l('0 is the highest priority (Default: 50).'),
                    'type' => 'text',
                    'defaultValue' => self::zeroSafeDefault('elementor_assets_priority', 50),
                    'validation' => 'isUnsignedInt',
                    'cast' => 'intval',
                    'class' => 'fixed-width-sm',
                ],
                'elementor_exclude_modules' => [
                    'title' => $this->l('Exclude Categories from Module widget'),
                    'type' => 'multiselect',
                    'identifier' => 'value',
                    'list' => [
                        ['value' => 'administration', 'name' => $this->trans('Administration', [], 'Admin.Modules.Feature')],
                        ['value' => 'advertising_marketing', 'name' => $this->trans('Advertising & Marketing', [], 'Admin.Modules.Feature')],
                        ['value' => 'analytics_stats', 'name' => $this->trans('Analytics & Stats', [], 'Admin.Modules.Feature')],
                        ['value' => 'billing_invoicing', 'name' => $this->trans('Taxes & Invoicing', [], 'Admin.Modules.Feature')],
                        ['value' => 'checkout', 'name' => $this->trans('Checkout', [], 'Admin.Modules.Feature')],
                        ['value' => 'content_management', 'name' => $this->trans('Content Management', [], 'Admin.Modules.Feature')],
                        ['value' => 'customer_reviews', 'name' => $this->trans('Customer Reviews', [], 'Admin.Modules.Feature')],
                        ['value' => 'export', 'name' => $this->trans('Export', [], 'Admin.Actions')],
                        ['value' => 'front_office_features', 'name' => $this->trans('Front office Features', [], 'Admin.Modules.Feature')],
                        ['value' => 'i18n_localization', 'name' => $this->trans('Internationalization & Localization', [], 'Admin.Modules.Feature')],
                        ['value' => 'merchandizing', 'name' => $this->trans('Merchandising', [], 'Admin.Modules.Feature')],
                        ['value' => 'migration_tools', 'name' => $this->trans('Migration Tools', [], 'Admin.Modules.Feature')],
                        ['value' => 'payments_gateways', 'name' => $this->trans('Payments & Gateways', [], 'Admin.Modules.Feature')],
                        ['value' => 'payment_security', 'name' => $this->trans('Site certification & Fraud prevention', [], 'Admin.Modules.Feature')],
                        ['value' => 'pricing_promotion', 'name' => $this->trans('Pricing & Promotion', [], 'Admin.Modules.Feature')],
                        ['value' => 'quick_bulk_update', 'name' => $this->trans('Quick / Bulk update', [], 'Admin.Modules.Feature')],
                        ['value' => 'seo', 'name' => $this->trans('SEO', [], 'Admin.Catalog.Feature')],
                        ['value' => 'shipping_logistics', 'name' => $this->trans('Shipping & Logistics', [], 'Admin.Modules.Feature')],
                        ['value' => 'slideshows', 'name' => $this->trans('Slideshows', [], 'Admin.Modules.Feature')],
                        ['value' => 'smart_shopping', 'name' => $this->trans('Comparison site & Feed management', [], 'Admin.Modules.Feature')],
                        ['value' => 'market_place', 'name' => $this->trans('Marketplace', [], 'Admin.Modules.Feature')],
                        ['value' => 'others', 'name' => $this->trans('Other Modules', [], 'Admin.Modules.Feature')],
                        ['value' => 'mobile', 'name' => $this->trans('Mobile', [], 'Admin.Global')],
                        ['value' => 'dashboard', 'name' => $this->trans('Dashboard', [], 'Admin.Global')],
                        ['value' => 'emailing', 'name' => $this->trans('Emailing & SMS', [], 'Admin.Modules.Feature')],
                        ['value' => 'social_networks', 'name' => $this->trans('Social Networks', [], 'Admin.Modules.Feature')],
                        ['value' => 'social_community', 'name' => $this->trans('Social & Community', [], 'Admin.Modules.Feature')],
                    ],
                    'auto_value' => false,
                    'cast' => 'json_encode',
                    'class' => 'chosen',
                ],
                'elementor_load_animate' => [
                    'title' => $this->l('Load Animate.css Library'),
                    'desc' => $this->l('Animate.css is a library of ready-to-use, cross-browser animations for use in your web projects.'),
                    'type' => 'bool',
                    'defaultValue' => self::zeroSafeDefault('elementor_load_animate', 1),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'elementor_load_fontawesome' => [
                    'title' => $this->l('Load FontAwesome Library'),
                    'desc' => $this->l('FontAwesome gives you scalable vector icons that can instantly be customized - size, color, drop shadow, and anything that can be done with the power of CSS.'),
                    'type' => 'bool',
                    'defaultValue' => self::zeroSafeDefault('elementor_load_fontawesome', 1),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'elementor_load_fa4_shim' => [
                    'title' => $this->l('Load Font Awesome 4 Support'),
                    'desc' => $this->l('Font Awesome 4 support is a backward compatibility that makes sure all previously selected Font Awesome 4 icons are displayed correctly while using Font Awesome 6 library.'),
                    'type' => 'bool',
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'elementor_load_sticky' => [
                    'title' => $this->l('Load jQuery Sticky Plugin'),
                    'desc' => $this->l('A jQuery plugin to easily add functionality to fix elements to the top of the page on scroll.'),
                    'type' => 'bool',
                    'defaultValue' => self::zeroSafeDefault('elementor_load_sticky', 1),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'elementor_load_swiper' => [
                    'title' => $this->l('Load Swiper Library'),
                    'desc' => $this->l('Swiper is the most modern mobile touch slider with hardware accelerated transitions and amazing native behavior.'),
                    'type' => 'bool',
                    'defaultValue' => self::zeroSafeDefault('elementor_load_swiper', 1),
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];

        $this->fields_options['experiments'] = [
            'tab' => $this->l('Experiments'),
            'icon' => 'icon-magic',
            'title' => $this->l('Experiments'),
            'info' => CESmarty::sprintf(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_alert', 'info', $this->l(
                "Access new and experimental features from Creative Elements before they're officially released. As these features are still in development, they are likely to change, evolve or even be removed altogether."
            )),
            'fields' => [
                'elementor_remove_hidden' => [
                    'title' => $this->l('Remove Hidden Elements'),
                    'desc' => $this->l('When you hide elements on "Advanced tab / Visibility section" their markup will be removed from DOM.'),
                    'type' => 'bool',
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'elementor_visibility' => [
                    'title' => $this->l('Extended Visibility Section'),
                    'desc' => $this->l('If you would like to schedule elements or filter them by selected customer groups, then this feature will be handy.'),
                    'type' => 'bool',
                    'validation' => 'isBool',
                    'cast' => 'intval',
                ],
                'ce_disable_google_fonts' => [
                    'title' => $this->l('Disable Google Fonts'),
                    'desc' => $this->l('Prevent loading files from fonts.googleapis.com'),
                    'type' => 'bool',
                    'validation' => 'isBool',
                    'cast' => 'intval',
                    'clearCss' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ];
        if (Tools::getValue('elementor_element_cache_ttl', Configuration::get('elementor_element_cache_ttl'))) {
            unset($this->fields_options['experiments']['fields']['elementor_remove_hidden']);
        }
    }

    public function initPageHeaderToolbar()
    {
        $this->page_header_toolbar_btn['license'] = [
            'icon' => 'process-icon-file icon-file-text',
            'desc' => $this->l('License'),
            'js' => "$('#modal_license').modal()",
        ];
        $this->page_header_toolbar_btn['regenerate-css'] = [
            'icon' => 'process-icon-reload icon-rotate-right',
            'desc' => $this->l('Regenerate CSS'),
            'js' => '//' . Tools::safeOutput(
                $this->l('Styles set in Creative Elements are saved in CSS files. Recreate those files, according to the most recent settings.')
            ),
        ];
        if (Shop::getContext() === Shop::CONTEXT_SHOP) {
            $this->page_header_toolbar_btn['replace-url'] = [
                'icon' => 'process-icon-refresh',
                'desc' => $this->l('Replace URL'),
                'js' => "$('#modal_replace_url').modal()",
            ];
        }

        parent::initPageHeaderToolbar();
    }

    public function initModal()
    {
        $ce_license = Configuration::getGlobalValue('CE_LICENSE');

        $this->modals[] = [
            'modal_id' => 'modal_license',
            'modal_class' => 'modal-md',
            'modal_title' => $ce_license
                ? CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_modal_license_status')
                : $this->l('Activate License'),
            'modal_content' => CESmarty::sprintf(
                _CE_TEMPLATES_ . 'admin/admin.tpl',
                $ce_license ? 'ce_modal_license_switch' : 'ce_modal_license',
                Tools::safeOutput($this->context->link->getAdminLink('AdminCESettings', true, [], ['action' => 'activate']))
            ),
        ];
        $this->modals[] = [
            'modal_id' => 'modal_replace_url',
            'modal_class' => 'modal-md',
            'modal_title' => $this->l('Update Site Address (URL)'),
            'modal_content' => CESmarty::sprintf(
                _CE_TEMPLATES_ . 'admin/admin.tpl',
                'ce_modal_replace_url',
                $this->l('It is strongly recommended that you backup your database before using Replace URL.'),
                $this->l('http://old-url.com'),
                $this->l('http://new-url.com'),
                $this->l('Enter your old and new URLs for your PrestaShop installation, to update all Creative Elements data (Relevant for domain transfers or move to \'HTTPS\').'),
                $this->l('Replace URL')
            ),
        ];
    }

    public function initHeader()
    {
        parent::initHeader();

        $tabs = &$this->context->smarty->tpl_vars['tabs']->value;

        foreach ($tabs as &$tab0) {
            foreach ($tab0['sub_tabs'] as &$tab1) {
                if ('AdminParentCEContent' !== $tab1['class_name']) {
                    continue;
                }
                foreach ($tab1['sub_tabs'] as &$tab2) {
                    if ('AdminCESettings' !== $tab2['class_name']) {
                        continue;
                    }
                    $id = 0;
                    $url = $this->context->link->getAdminLink('AdminCESettings');
                    $sub_tabs = &$tab2['sub_tabs'];

                    foreach ($this->fields_options as $tab => &$options) {
                        $sub_tabs[] = [
                            'id_tab' => --$id,
                            'active' => true,
                            'class_name' => $tab,
                            'current' => $tab === $this->sub_tab,
                            'href' => "$url&subTab=$tab",
                            'name' => $options['tab'],
                        ];
                        $options['class'] = $tab === $this->sub_tab ? '' : 'hidden';
                    }

                    return;
                }
            }
        }
    }

    public function initContent()
    {
        $this->context->smarty->assign('current_tab_level', 3);

        return parent::initContent();
    }

    protected function processActivate()
    {
        $url = $this->context->link->getAdminLink('AdminCESettings');

        if (Tools::getIsset('license')) {
            Configuration::updateGlobalValue('CE_LICENSE', Tools::getValue('license'));
            $url .= '#license';
        } else {
            list($p, $r) = explode('://', CE\wp_referer());
            $encode = 'base64_encode';
            $url = $this->activate_url . '&' . http_build_query([
                'response_type' => 'code',
                'client_id' => substr($encode(_COOKIE_KEY_), 0, 32),
                'auth_secret' => rtrim($encode("$r?" . Tools::passwdGen(23 - strlen($r))), '='),
                'state' => substr($encode($this->module->module_key), 0, 12),
                'redirect_uri' => urlencode($url),
            ]);
        }
        Tools::redirectAdmin($url);
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->css_files[_MODULE_DIR_ . 'creativeelements/views/css/settings.css?v=' . _CE_VERSION_] = 'all';
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/js/settings.js?v=' . _CE_VERSION_;
    }

    protected function processUpdateOptions()
    {
        unset(${'_POST'}['subTab']);

        foreach ($this->fields_options as $tab => &$panel) {
            foreach ($panel['fields'] as $option => &$field) {
                if (('style' === $tab || !empty($field['clearCss'])) && Configuration::get($option) != Tools::getValue($option)) {
                    $this->clearCss = true;
                    break 2;
                }
            }
        }

        parent::processUpdateOptions();

        if ($this->viewportChanged) {
            CE\Plugin::instance();
            CE\CoreXResponsiveXResponsive::compileStylesheetTemplates();
        }
        $this->clearCss && CE\Plugin::instance()->files_manager->clearCache();
    }

    protected function updateOptionElementorViewportLg($val)
    {
        if (Configuration::get('elementor_viewport_lg') != $val) {
            Configuration::updateValue('elementor_viewport_lg', $val);

            $this->viewportChanged = true;
        }
    }

    protected function updateOptionElementorViewportMd($val)
    {
        if (Configuration::get('elementor_viewport_md') != $val) {
            Configuration::updateValue('elementor_viewport_md', $val);

            $this->viewportChanged = true;
        }
    }

    public function ajaxProcessRegenerateCss()
    {
        CE\Plugin::instance()->files_manager->clearCache();

        CE\wp_send_json_success();
    }

    public function ajaxProcessReplaceUrl()
    {
        $from = trim(Tools::getValue('from'));
        $to = trim(Tools::getValue('to'));

        $is_valid_urls = filter_var($from, FILTER_VALIDATE_URL) && filter_var($to, FILTER_VALIDATE_URL);

        if (!$is_valid_urls) {
            CE\wp_send_json_error(CE\__("The `from` and `to` URL's must be a valid URL"));
        }

        if ($from === $to) {
            CE\wp_send_json_error(CE\__("The `from` and `to` URL's must be different"));
        }

        $db = Db::getInstance();
        $id = sprintf('%02d', $this->context->shop->id);

        $result = $db->execute(
            'UPDATE ' . _DB_PREFIX_ . 'ce_meta SET `value` = REPLACE(`value`, "' . pSQL($from) . '", "' . pSQL($to) . '") ' .
            'WHERE `name` = "_elementor_data" AND `id` LIKE "%' . pSQL($id) . '" AND `value` LIKE "%' . pSQL($from) . '%"'
        );

        if (false === $result) {
            CE\wp_send_json_error(CE\__('An error occurred'));
        } else {
            CE\wp_send_json_success(sprintf(CE\__('%d Rows Affected'), $db->affected_rows()));
        }
    }

    protected function l($string, $module = 'creativeelements', $addslashes = false, $htmlentities = true)
    {
        $js = $addslashes || !$htmlentities;
        $str = Translate::getModuleTranslation($module, $string, '', null, $js, _CE_LOCALE_);

        return $htmlentities ? $str : stripslashes($str);
    }

    public static function zeroSafeDefault($key, $default)
    {
        return '0' === Tools::getValue($key) || Configuration::hasKey($key) ? 0 : $default;
    }
}
