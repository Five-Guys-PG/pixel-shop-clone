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

use CE\CoreXBaseXModule as BaseModule;
use CE\CoreXDynamicTagsXDynamicCSS as DynamicCSS;

class ModulesXCustomCssXModule extends BaseModule
{
    public function getName()
    {
        return 'custom-css';
    }

    /**
     * @param $post_css Post
     * @param $element ElementBase
     */
    public function addPostCss($post_css, $element)
    {
        if ($post_css instanceof DynamicCSS) {
            return;
        }

        $element_settings = $element->getSettings();

        if (empty($element_settings['custom_css'])) {
            return;
        }

        $css = trim($element_settings['custom_css']);

        if (empty($css)) {
            return;
        }
        $css = str_replace('selector', $post_css->getElementUniqueSelector($element), $css);

        // Add a css comment
        $css = "/* Start custom CSS for {$element->getName()}, class: {$element->getUniqueSelector()} */{$css}/* End custom CSS */";

        $post_css->getStylesheet()->addRawCss($css);
    }

    /**
     * @param $post_css Post
     */
    public function addPageSettingsCss($post_css)
    {
        $document = Plugin::$instance->documents->get($post_css->getPostId());

        if ($document->getTemplateType() === 'kit') {
            $stylesheet = $post_css->getStylesheet();

            // Clear element related styles
            \Closure::bind(function () {
                foreach ($this->rules as $rule => &$selectors) {
                    foreach ($selectors as $selector => &$styles) {
                        if (strpos($selector, '.elementor-element-') !== false) {
                            unset($selectors[$selector]);
                        }
                    }
                    if (!$selectors) {
                        unset($this->rules[$rule]);
                    }
                }
            }, $stylesheet, $stylesheet)->__invoke();

            // Clear all icon fonts
            \Closure::bind(function () {
                $this->icons_fonts = [];
            }, $post_css, 'CE\CoreXFilesXCSSXBase')->__invoke();
        }

        $custom_css = $document->getSettings('custom_css');

        $custom_css = trim($custom_css);

        if (empty($custom_css)) {
            return;
        }

        $custom_css = str_replace('selector', $document->getCssWrapperSelector(), $custom_css);

        // Add a css comment
        $custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

        $post_css->getStylesheet()->addRawCss($custom_css);
    }

    public function __construct()
    {
        add_action('elementor/element/parse_css', [$this, 'addPostCss'], 10, 2);
        add_action('elementor/css-file/post/parse', [$this, 'addPageSettingsCss']);
    }
}
