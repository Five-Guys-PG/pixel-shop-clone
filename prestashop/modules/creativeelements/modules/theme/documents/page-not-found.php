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

use CE\ModulesXThemeXDocumentsXThemePageDocument as ThemePageDocument;

class ModulesXThemeXDocumentsXPageNotFound extends ThemePageDocument
{
    public function getName()
    {
        return 'page-not-found';
    }

    public static function getTitle()
    {
        return __('404 Page');
    }

    protected function getPermalinkUrl(\Link $link, $id_lang, $id_shop, array $args, $relative = true)
    {
        return $link->getPageLink('pagenotfound', null, $id_lang, $args, false, $id_shop, $relative);
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = '404 error';

        return $config;
    }
}
