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

use CE\ModulesXLibraryXDocumentsXLibraryDocument as LibraryDocument;

abstract class ModulesXThemeXDocumentsXThemeDocument extends LibraryDocument
{
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['admin_tab_group'] = 'theme';
        $properties['support_kit'] = true;

        return $properties;
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        $url = $link->getPageLink('index', null, $id_lang, null, false, $id_shop, $relative);

        \Configuration::get('PS_REWRITING_SETTINGS') && $url = substr($url, 0, strrpos($url, '/') + 1);

        return add_query_arg($args, $url);
    }

    public function getPermalink(array $args = [])
    {
        $uid = UId::parse($this->getMainId());
        $context = \Context::getContext();
        $id_lang = $uid->id_lang ?: $context->language->id;
        $id_shop = $uid->id_shop ?: ($uid->getDefaultShopId() ?: $context->shop->id);

        return $this->getPermalinkUrl($context->link, $id_lang, $id_shop, $args);
    }

    public function getWpPreviewUrl()
    {
        $main_post_id = $this->getMainId();

        return $this->getPermalink([
            'preview_id' => $main_post_id,
            'id_employee' => \Context::getContext()->employee->id,
            'cetoken' => \Tools::getAdminTokenLite(UId::parse($main_post_id)->getAdminController()),
        ]);
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->injectHtmlTagControl();
    }

    /**
     * @since 2.9.0
     *
     * If the implementing document uses optional wrapper HTML tags, this method injects the control to choose the tag
     */
    private function injectHtmlTagControl()
    {
        $wrapper_tags = $this->getWrapperTags();

        // Only proceed if the implementing document has optional wrapper HTML tags to replace 'div'
        if (!$wrapper_tags) {
            return;
        }

        // Add 'div' to the beginning of the list of wrapper tags
        array_unshift($wrapper_tags, 'div');

        /*
         * Inject the control that sets the HTML tag for the header/footer wrapper element
         */
        $this->startInjection([
            'of' => 'post_status',
            'fallback' => [
                'of' => 'post_title',
            ],
        ]);

        $this->addControl(
            'content_wrapper_html_tag',
            [
                'label' => __('HTML Tag'),
                'type' => ControlsManager::SELECT,
                'default' => 'div',
                'options' => array_combine($wrapper_tags, $wrapper_tags),
            ]
        );

        $this->endInjection();
    }

    /**
     * @param null $elements_data
     *
     * @since 2.9.0
     *
     * Overwrite method from document.php to check for user-selected tags to use as the document wrapper element
     */
    public function printElementsWithWrapper($elements_data = null)
    {
        $wrapper_tag = $this->getSettings('content_wrapper_html_tag') ?: 'div';

        if (!$elements_data) {
            $elements_data = $this->getElementsData();
        } ?>
        <<?php echo $wrapper_tag; ?> <?php echo Utils::renderHtmlAttributes($this->getContainerAttributes()); ?>>
            <div class="elementor-section-wrap">
                <?php $this->printElements($elements_data); ?>
            </div>
        </<?php echo $wrapper_tag; ?>>
        <?php
    }

    public function getWrapperTags()
    {
        // 'div' is added later, no need to include it in this list
        return [
            'main',
            'article',
            'header',
            'footer',
            'section',
            'aside',
            'nav',
        ];
    }
}
