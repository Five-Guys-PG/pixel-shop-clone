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

use CE\ModulesXThemeXDocumentsXThemeDocument as ThemeDocument;

class ModulesXCatalogXDocumentsXListingXNoResults extends ThemeDocument
{
    public function getName()
    {
        return 'listing-no-results';
    }

    public static function getTitle()
    {
        return __('No Results');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = 'no results';

        return $config;
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        return $link->getPageLink('search', null, $id_lang, $args, false, $id_shop, $relative);
    }

    public static function registerTags($dynamic_tags)
    {
        $dynamic_tags->registerTag('CE\ModulesXCatalogXTagsXListingClearUrl');
    }

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        did_action('elementor/dynamic_tags/register_tags')
            ? static::registerTags(Plugin::$instance->dynamic_tags)
            : add_action('elementor/dynamic_tags/register_tags', [static::class, 'registerTags']);
    }
}
