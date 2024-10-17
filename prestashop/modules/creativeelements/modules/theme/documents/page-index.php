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

class ModulesXThemeXDocumentsXPageIndex extends ThemePageDocument
{
    public function getName()
    {
        return 'page-index';
    }

    public static function getTitle()
    {
        return __('Home Page');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = '';
        $config['type'] = 'page';
        $config['default_route'] = 'templates/pages';

        return $config;
    }

    public function __construct(array $data = [])
    {
        if ($data && is_admin()) {
            $template = get_post_meta($data['post_id'], '_wp_page_template', true);
            $template || update_post_meta($data['post_id'], '_wp_page_template', 'elementor_header_footer');

            $data['settings']['template'] = $template ?: 'elementor_header_footer';
        }

        parent::__construct($data);
    }
}
