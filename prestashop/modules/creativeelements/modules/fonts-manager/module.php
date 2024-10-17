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
use CE\CoreXCommonXModulesXAjaxXModule as Ajax;

class ModulesXFontsManagerXModule extends BaseModule
{
    private $enqueued_fonts = [];

    public function getName()
    {
        return 'fonts-manager';
    }

    private function getFontTypes()
    {
        static $font_types;

        if (null === $font_types) {
            $font_types = json_decode(\Configuration::getGlobalValue('elementor_fonts_manager_font_types'), true) ?: [];
        }

        return $font_types;
    }

    public function getFonts($family = null)
    {
        static $fonts;

        if (null === $fonts) {
            $fonts = json_decode(\Configuration::getGlobalValue('elementor_fonts_manager_fonts'), true) ?: [];
        }

        if ($family) {
            return isset($fonts[$family]) ? $fonts[$family] : false;
        }

        return $fonts;
    }

    public function registerFontsGroups($font_groups)
    {
        $new_groups = [
            'custom' => __('Custom'),
        ];

        return array_merge($new_groups, $font_groups);
    }

    public function registerFontsInControl($fonts)
    {
        return array_merge($this->getFontTypes(), $fonts);
    }

    public function preloadCustom($font)
    {
        if ($preloads = $this->getFonts($font)['preloads']) {
            foreach ($preloads as $url => $ext) {
                echo '<link rel="preload" as="font" type="font/' . $ext . '" href="' . Helper::getMediaLink($url) . '" crossorigin>';
            }
        }
    }

    public function enqueueFonts($post_css)
    {
        $stylesheet = $post_css->getStylesheet();
        $used_fonts = $post_css->getFonts();
        $custom_fonts = $this->getFonts();

        foreach ($used_fonts as $font_family) {
            if (!isset($custom_fonts[$font_family]['font_face']) || in_array($font_family, $this->enqueued_fonts)) {
                continue;
            }
            $font_faces = str_replace('{{BASE}}', __PS_BASE_URI__, $custom_fonts[$font_family]['font_face']);

            $stylesheet->addRawCss("/* Start Custom Fonts CSS */ $font_faces /* End Custom Fonts CSS */");

            $this->enqueued_fonts[] = $font_family;
        }
    }

    public function fontsManagerPanelActionData(array $data)
    {
        if (empty($data['type'])) {
            throw new \Exception('font_type_is_required');
        }

        if (empty($data['font'])) {
            throw new \Exception('font_is_required');
        }

        $font_family = preg_replace('/[^\w \-]+/', '', $data['font']);

        $font = $this->getFonts($font_family);

        if (empty($font['font_face'])) {
            $error_message = sprintf(__('Font %s was not found.'), $font_family);

            throw new \Exception($error_message);
        }

        return str_replace('{{BASE}}', __PS_BASE_URI__, $font);
    }

    public function registerAjaxActions(Ajax $ajax)
    {
        $ajax->registerAjaxAction('assets_manager_panel_action_data', [$this, 'fontsManagerPanelActionData']);
    }

    public function registerIconLibrariesControl($additional_sets)
    {
        $link = \Context::getContext()->link;
        $icon_sets = \CEIconSet::getCustomIconsConfig();

        foreach ($icon_sets as &$icon_set) {
            $icon_set['url'] = $link->getMediaLink(__PS_BASE_URI__ . $icon_set['url']);

            empty($icon_set['fetchJson']) || $icon_set['fetchJson'] = __PS_BASE_URI__ . $icon_set['fetchJson'];
        }

        return array_merge($additional_sets, $icon_sets);
    }

    public function __construct()
    {
        add_filter('elementor/icons_manager/additional_tabs', [$this, 'registerIconLibrariesControl']);

        add_filter('elementor/fonts/groups', [$this, 'registerFontsGroups']);
        add_filter('elementor/fonts/additional_fonts', [$this, 'registerFontsInControl']);

        add_action('elementor/fonts/print_font_links/custom', [$this, 'preloadCustom']);

        add_action('elementor/css-file/post/parse', [$this, 'enqueueFonts']);
        add_action('elementor/css-file/global/parse', [$this, 'enqueueFonts']);
        // Ajax
        add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);

        do_action('elementor/fonts_manager_loaded', $this);
    }
}
