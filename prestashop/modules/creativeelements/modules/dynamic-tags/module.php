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
use CE\CoreXDynamicTagsXManager as Manager;

/**
 * Elementor dynamic tags module.
 *
 * Elementor dynamic tags module handler class is responsible for registering
 * and managing dynamic tags.
 *
 * @since 2.0.0
 */
class ModulesXDynamicTagsXModule extends BaseModule
{
    const SITE_GROUP = 'site';

    const CATALOG_GROUP = 'catalog';

    const MEDIA_GROUP = 'media';

    const ACTION_GROUP = 'action';

    const POST_GROUP = 'post';

    const AUTHOR_GROUP = 'author';

    const COMMENTS_GROUP = 'comments';

    /**
     * Dynamic tags text category.
     */
    const TEXT_CATEGORY = 'text';

    /**
     * Dynamic tags URL category.
     */
    const URL_CATEGORY = 'url';

    /**
     * Dynamic tags image category.
     */
    const IMAGE_CATEGORY = 'image';

    /**
     * Dynamic tags media category.
     */
    const MEDIA_CATEGORY = 'media';

    /**
     * Dynamic tags post meta category.
     */
    const POST_META_CATEGORY = 'post_meta';

    /**
     * Dynamic tags gallery category.
     */
    const GALLERY_CATEGORY = 'gallery';

    /**
     * Dynamic tags number category.
     */
    const NUMBER_CATEGORY = 'number';

    /**
     * Dynamic tags number category.
     */
    const COLOR_CATEGORY = 'color';

    /**
     * Dynamic tags date time category.
     */
    const DATE_TIME_CATEGORY = 'date_time';

    /**
     * Get module name.
     *
     * Retrieve the dynamic tags module name.
     *
     * @since 2.0.0
     *
     * @return string Module name
     */
    public function getName()
    {
        return 'dynamic_tags';
    }

    /**
     * Get classes names.
     *
     * Retrieve the dynamic tag classes names.
     *
     * @since 2.0.0
     *
     * @return array Tag dynamic tag classes names
     */
    public function getTagClassesNames()
    {
        return [
            'SiteLogo',
            'SiteTitle',
            'SiteContact',
            'SiteURL',
            'PageTitle',
            'InternalURL',
            'CurrentDateTime',
            'RequestParameter',
            'Carousel',
            'ContactURL',
            'Lightbox',
            'Toggle',
            'Shortcode',
            'CustomColors',
            'UserInfo',
        ];
    }

    /**
     * Get groups.
     *
     * Retrieve the dynamic tag groups.
     *
     * @since 2.0.0
     *
     * @return array Tag dynamic tag groups
     */
    public function getGroups()
    {
        return [
            self::SITE_GROUP => [
                'title' => __('Site'),
            ],
            self::CATALOG_GROUP => [
                'title' => __('Catalog'),
            ],
            self::MEDIA_GROUP => [
                'title' => __('Media'),
            ],
            self::ACTION_GROUP => [
                'title' => __('Actions'),
            ],
            self::POST_GROUP => [
                'title' => __('Post'),
            ],
            self::AUTHOR_GROUP => [
                'title' => __('Author'),
            ],
            self::COMMENTS_GROUP => [
                'title' => __('Comments'),
            ],
        ];
    }

    /**
     * Register groups.
     *
     * Add all the available tag groups.
     *
     * @since 2.0.0
     */
    private function registerGroups()
    {
        foreach ($this->getGroups() as $group_name => $group_settings) {
            Plugin::$instance->dynamic_tags->registerGroup($group_name, $group_settings);
        }
    }

    /**
     * Register tags.
     *
     * Add all the available dynamic tags.
     *
     * @since 2.0.0
     *
     * @param Manager $dynamic_tags
     */
    public function registerTags($dynamic_tags)
    {
        foreach ($this->getTagClassesNames() as $tag_class) {
            /* @var Tag $class_name */
            $class_name = 'CE\ModulesXDynamicTagsXTagsX' . $tag_class;

            $dynamic_tags->registerTag($class_name);
        }
    }

    /**
     * Dynamic tags module constructor.
     *
     * Initializing Elementor dynamic tags module.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        $this->registerGroups();

        add_action('elementor/dynamic_tags/register_tags', [$this, 'registerTags']);
    }
}
