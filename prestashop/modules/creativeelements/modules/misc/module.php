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

use CE\CoreXBaseXModule as BaseModule;

class ModulesXMiscXModule extends BaseModule
{
    public function getName()
    {
        return 'misc';
    }

    public function init()
    {
        $context = \Context::getContext();
        $filter = 'filter';
        $context->smarty->registerPlugin('modifier', 'ce' . $filter, function ($str) {
            echo $str;
        });

        if ($uid_preview = \CreativeElements::getPreviewUId()) {
            if (UId::TEMPLATE === $uid_preview->id_type && 'kit' === (new \CETemplate($uid_preview->id))->type) {
                \Configuration::set('elementor_active_kit', $uid_preview->id);
            }

            header_register_callback(function () {
                header_remove('Content-Security-Policy');
                header_remove('X-Content-Type-Options');
                header_remove('X-Frame-Options');
                header_remove('X-Xss-Protection');
            });
        }

        if ($context->controller instanceof \IndexController && \Configuration::get('CE_PAGE_INDEX') && \Cache::isStored('hook_idsbyname')) {
            $hooks = \Cache::retrieve('hook_idsbyname');
            unset($hooks['displayhome']);
            \Cache::store('hook_idsbyname', $hooks);
        }

        if ($context->controller instanceof \Ets_blogBlogModuleFrontController
            || $context->controller instanceof \Ybc_blogBlogModuleFrontController && version_compare($context->controller->module->version, '4.4.8', '>')
        ) {
            $context->smarty->registerFilter('pre', function ($src, $tpl) {
                $basename = basename($tpl->template_resource);

                if ('singlepost.tpl' === $basename || 'single_post.tpl' === $basename) {
                    $src = str_replace('{$blog_post.description nofilter}', '<!--CE-POST-->', $src);
                }

                return $src;
            });
        }

        \CEAssetManager::instance();
    }

    public function addElementCacheControl(ElementBase $element, array $args)
    {
        $element->addControl(
            '_element_cache',
            [
                'label' => __('Element Cache'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'yes' => __('Inactive'),
                    'no' => __('Active'),
                ],
                'description' => __('Activating cache improves loading times by storing a static version of this element.'),
                'separator' => 'before',
            ],
            [
                'position' => [
                    'of' => '_css_classes',
                ],
            ]
        );
    }

    public function filterNativeIcons($tabs)
    {
        if (!is_admin() && !\Configuration::get('elementor_load_fontawesome')) {
            unset($tabs['fa-regular'], $tabs['fa-solid'], $tabs['fa-brands']);
        }
        $min = _PS_MODE_DEV_ ? '' : '.min';

        return [
            'ce-icons' => [
                'name' => 'ce-icons',
                'label' => 'Creative Elements - ' . __('Default'),
                'url' => _CE_ASSETS_URL_ . "lib/ceicons/ceicons$min.css",
                'enqueue' => [],
                'prefix' => 'ceicon-',
                'displayPrefix' => '',
                'labelIcon' => 'eicon-icons-solid',
                'ver' => _CE_VERSION_,
                'fetchJson' => _CE_ASSETS_URL_ . 'lib/ceicons/ceicons.js',
                'native' => true,
            ],
        ] + $tabs;
    }

    public function enqueuePreviewScripts()
    {
        // Redirect to preview page when edited hook is missing
        $uid = isset($_REQUEST['preview_id']) ? $_REQUEST['preview_id'] : '';

        if ($uid && UId::CONTENT === UId::parse($uid)->id_type) {
            $tab = 'AdminCEContent';
            $id_employee = (int) \Tools::getValue('id_employee');

            wp_register_script(
                'editor-preview',
                _CE_ASSETS_URL_ . 'js/editor-preview.js',
                [],
                _CE_VERSION_,
                true
            );

            wp_localize_script(
                'editor-preview',
                'cePreview',
                \Context::getContext()->link->getModuleLink('creativeelements', 'preview', [
                    'id_employee' => $id_employee,
                    'cetoken' => \Tools::getAdminToken($tab . (int) \Tab::getIdFromClassName($tab) . $id_employee),
                    'preview_id' => $uid,
                    'ctx' => (int) \Tools::getValue('ctx'),
                ], null, null, null, true)
            );

            wp_enqueue_script('editor-preview');
        }
    }

    public function enqueueFrontendScripts()
    {
        // Add Quick Edit button on frontend when employee has active session
        if ($editor = $this->getEditorLink()) {
            wp_register_script('frontend-edit', _CE_ASSETS_URL_ . 'js/frontend-edit.js', [], _CE_VERSION_);
            wp_localize_script('frontend-edit', 'ceFrontendEdit', [
                'editor_url' => $editor,
                'edit_title' => __('Edit with Creative Elements'),
            ]);
            wp_enqueue_script('frontend-edit');
            wp_enqueue_style('frontend-edit', _CE_ASSETS_URL_ . 'css/frontend-edit.css', [], _CE_VERSION_);
        }
    }

    private function getEditorLink()
    {
        static $link;

        if (null === $link) {
            $link = '';

            if (\Configuration::get('elementor_frontend_edit', null, null, null, true)
                && ($id_employee = $this->getCurrentEmployeeId())
                && ($dir = glob(_PS_ROOT_DIR_ . '/*/filemanager', GLOB_ONLYDIR))
            ) {
                $tab = 'AdminCEEditor';
                $link = __PS_BASE_URI__ . basename(dirname($dir[0])) . '/index.php?' . http_build_query([
                    'controller' => $tab,
                    'token' => \Tools::getAdminToken($tab . (int) \Tab::getIdFromClassName($tab) . $id_employee),
                ]);
            }
        }

        return $link;
    }

    private function getCurrentEmployeeId()
    {
        $id_employee = get_current_user_id();

        if (!$id_employee && !is_admin()) {
            $lifetime = max((int) \Configuration::get('PS_COOKIE_LIFETIME_BO'), 1);
            $cookie = new \Cookie('psAdmin', '', time() + $lifetime * 3600);
            $id_employee = isset($cookie->id_employee) ? (int) $cookie->id_employee : 0;
        }

        return $id_employee;
    }

    public function preloadFonts()
    {
        if (_MEDIA_SERVER_1_) {
            return;
        }
        $lib = _MODULE_DIR_ . 'creativeelements/views/lib'; ?>
        <link rel="preload" href="<?php echo esc_attr("$lib/ceicons/fonts/ceicons.woff2?8goggd"); ?>" as="font" type="font/woff2" crossorigin>
        <?php
    }

    public function __construct()
    {
        add_filter('elementor/icons_manager/native', [$this, 'filterNativeIcons']);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueuePreviewScripts']);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueueFrontendScripts']);

        \Configuration::get('elementor_element_cache_ttl')
            && add_action('elementor/element/common/_section_style/after_section_end', [$this, 'addElementCacheControl']);

        if (!is_admin()) {
            add_action('template_redirect', [$this, 'init'], 0);
            add_action('wp_head', [$this, 'preloadFonts']);

            if (\Configuration::get('ce_disable_google_fonts')) {
                add_filter('elementor/frontend/print_google_fonts', function ($print_google_fonts) {
                    return [];
                }, 999999);
            }
        }

        add_action('elementor/frontend/section/before_render', function ($section) {
            array_unshift(Helper::$section_stack, $section);
        });
        add_action('elementor/frontend/section/after_render', function ($section) {
            array_shift(Helper::$section_stack);
        });
        add_filter('elementor/frontend/column/should_render', function ($should_render, $column) {
            if ($section = Helper::$section_stack[0]) {
                isset($section->render_tabs) || $section->render_tabs = [];

                $section->render_tabs[] = $should_render;
            }

            return $should_render;
        }, 999999);
    }
}
