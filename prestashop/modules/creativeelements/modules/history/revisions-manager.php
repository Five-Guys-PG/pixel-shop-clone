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

use CE\CoreXBaseXDocument as Document;
use CE\CoreXCommonXModulesXAjaxXModule as Ajax;
use CE\CoreXFilesXCSSXPost as PostCSS;

/**
 * Elementor history revisions manager.
 *
 * Elementor history revisions manager handler class is responsible for
 * registering and managing Elementor revisions manager.
 *
 * @since 1.7.0
 */
class ModulesXHistoryXRevisionsManager
{
    // const MAX_REVISIONS_TO_DISPLAY = 100;

    /**
     * Authors list.
     *
     * Holds all the authors.
     *
     * @var array
     */
    private static $authors = [];

    /**
     * History revisions manager constructor.
     *
     * Initializing Elementor history revisions manager.
     *
     * @since 1.7.0
     */
    public function __construct()
    {
        self::registerActions();
    }

    // public static function handleRevision()

    // public static function avoidDeleteAutoSave($post_content, $post_id)

    // public static function removeTempPostContent()

    /**
     * @since 1.7.0
     * @static
     *
     * @param int $post_id
     * @param array $query_args
     * @param bool $parse_result
     *
     * @return array
     */
    public static function getRevisions($post_id = 0, $query_args = [], $parse_result = true)
    {
        $post = get_post($post_id);

        if (!$post || !$post->ID) {
            return [];
        }

        $revisions = [];

        $default_query_args = [
            'posts_per_page' => \Configuration::get('elementor_max_revisions'),
            'meta_key' => '_elementor_data',
        ];

        if (empty($default_query_args['posts_per_page'])) {
            return [];
        }

        $query_args = array_merge($default_query_args, $query_args);

        $posts = wp_get_post_revisions($post->ID, $query_args);

        $autosave = Utils::getPostAutosave($post->ID);
        if ($autosave) {
            if ($parse_result) {
                array_unshift($posts, $autosave);
            } else {
                array_unshift($posts, $autosave->ID);
            }
        }

        if ($parse_result) {
            empty($post->post_modified) && $post->post_modified = get_post_meta($post->uid, '_ce_date_upd', true);

            array_unshift($posts, $post);
        } else {
            array_unshift($posts, $post->ID);

            return $posts;
        }

        // $current_time = current_time('timestamp');
        $current_time = time();
        $profile_url = 'https://profile.prestashop.com/';

        /* @var WPPost $revision */
        foreach ($posts as $revision) {
            // $date = date_i18n(_x('M j @ H:i', 'revision date format'), strtotime($revision->post_modified));

            if ($revision->ID === $post->ID) {
                $type = 'current';
            } elseif (!$revision->_obj->active) {
                $type = 'autosave';
            } else {
                $type = 'revision';
            }

            if (!isset(self::$authors[$revision->post_author])) {
                $author = new \Employee($revision->post_author);
                $unknown = empty($author->id);

                self::$authors[$revision->post_author] = [
                    'avatar' => sprintf(
                        '<img src="%s" width="22" height="22">',
                        $unknown || version_compare(_PS_VERSION_, '1.7.8', '<')
                        ? \Tools::getAdminImageUrl('prestashop-avatar.png')
                        : $author->getImage()
                    ),
                    'display_name' => $unknown ? '' : "{$author->firstname} {$author->lastname}",
                ];
            }

            $revisions[] = [
                'id' => $revision->ID,
                'author' => self::$authors[$revision->post_author]['display_name'],
                'timestamp' => strtotime($revision->post_modified),
                'date' => empty($revision->post_modified) ? '' : sprintf(
                    /* translators: 1: Human readable time difference, 2: Date */
                    __('%1$s ago<br>%2$s'),
                    human_time_diff(strtotime($revision->post_modified), $current_time),
                    \Tools::dateFormat(['date' => $revision->post_modified, 'full' => true], $null)
                ),
                'type' => $type,
                'gravatar' => self::$authors[$revision->post_author]['avatar'],
            ];
        }

        return $revisions;
    }

    /**
     * @since 1.9.2
     * @static
     */
    public static function updateAutosave($autosave_data)
    {
        self::saveRevision($autosave_data['ID']);
    }

    /**
     * @since 1.7.0
     * @static
     */
    public static function saveRevision($revision_id)
    {
        $parent_id = wp_is_post_revision($revision_id);

        if ($parent_id) {
            Plugin::$instance->db->safeCopyElementorMeta($parent_id, $revision_id);
        }
    }

    /**
     * @since 1.7.0
     * @static
     */
    public static function restoreRevision($parent_id, $revision_id)
    {
        $is_built_with_elementor = Plugin::$instance->db->isBuiltWithElementor($revision_id);

        Plugin::$instance->db->setIsElementorPage($parent_id, $is_built_with_elementor);

        if (!$is_built_with_elementor) {
            return;
        }

        Plugin::$instance->db->copyElementorMeta($revision_id, $parent_id);

        $post_css = PostCSS::create($parent_id);

        $post_css->update();
    }

    /**
     * @since 2.3.0
     * @static
     *
     * @param $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public static function ajaxGetRevisionData(array $data)
    {
        if (!isset($data['id'])) {
            throw new \Exception('You must set the revision ID.');
        }

        $revision = Plugin::$instance->documents->get($data['id']);

        if (!$revision) {
            throw new \Exception('Invalid revision.');
        }

        if (!current_user_can('edit', $revision->getId())) {
            throw new \Exception(__('Access denied.'));
        }

        $revision_data = [
            'settings' => $revision->getSettings(),
            'elements' => $revision->getElementsData(),
        ];

        return $revision_data;
    }

    // public static function addRevisionSupportForAllPostTypes()

    /**
     * @since 2.0.0
     * @static
     *
     * @param array $return_data
     * @param Document $document
     *
     * @return array
     */
    public static function onAjaxSaveBuilderData($return_data, $document)
    {
        $post_id = $document->getMainId();

        $latest_revisions = self::getRevisions($post_id, [
            'posts_per_page' => 1,
        ]);

        $all_revision_ids = self::getRevisions($post_id, [
            'fields' => 'ids',
        ], false);

        // Send revisions data only if has revisions.
        if (!empty($latest_revisions)) {
            $current_revision_id = self::currentRevisionId($post_id);

            $return_data = array_replace_recursive($return_data, [
                'config' => [
                    'document' => [
                        'revisions' => [
                            'current_id' => $current_revision_id,
                        ],
                    ],
                ],
                'latest_revisions' => $latest_revisions,
                'revisions_ids' => $all_revision_ids,
            ]);
        }

        return $return_data;
    }

    // public static function dbBeforeSave($status, $has_changes)

    public static function documentConfig($settings, $post_id)
    {
        $settings['revisions'] = [
            'enabled' => \Configuration::get('elementor_max_revisions'),
            'current_id' => (string) self::currentRevisionId($post_id),
        ];

        return $settings;
    }

    /**
     * Localize settings.
     *
     * Add new localized settings for the revisions manager.
     *
     * Fired by `elementor/editor/localize_settings` filter.
     *
     * @since 1.7.0
     * @static
     */
    public static function editorSettings($settings)
    {
        $settings = array_replace_recursive($settings, [
            'i18n' => [
                'edit_draft' => __('Edit Draft'),
                'edit_published' => __('Edit Published'),
                'no_revisions_1' => __('Revision history lets you save your previous versions of your work, and restore them any time.'),
                'no_revisions_2' => __('Start designing your page and you\'ll be able to see the entire revision history here.'),
                'current' => __('Current Version'),
                'restore' => __('Restore'),
                'restore_auto_saved_data' => __('Restore Auto Saved Data'),
                'restore_auto_saved_data_message' => __('There is an autosave of this post that is more recent than the version below. You can restore the saved data fron the Revisions panel'),
                'revision' => __('Revision'),
                'revision_history' => __('Revision History'),
                'revisions_disabled_1' => __('It looks like the revision feature is turned off.'),
                'revisions_disabled_2' => sprintf(
                    __('You can enable it in the <a href="%s" target="_blank">Settings page</a>'),
                    Helper::getSettingsLink()
                ),
            ],
        ]);

        return $settings;
    }

    public static function ajaxGetRevisions()
    {
        return self::getRevisions();
    }

    /**
     * @since 2.3.0
     * @static
     */
    public static function registerAjaxActions(Ajax $ajax)
    {
        $ajax->registerAjaxAction('get_revisions', [__CLASS__, 'ajaxGetRevisions']);
        $ajax->registerAjaxAction('get_revision_data', [__CLASS__, 'ajaxGetRevisionData']);
    }

    /**
     * @since 1.7.0
     * @static
     */
    private static function registerActions()
    {
        add_action('wp_restore_post_revision', [__CLASS__, 'restoreRevision'], 10, 2);
        // add_action('init', [__CLASS__, 'addRevisionSupportForAllPostTypes'], 9999);
        add_filter('elementor/editor/localize_settings', [__CLASS__, 'editorSettings']);
        add_filter('elementor/document/config', [__CLASS__, 'documentConfig'], 10, 2);
        // add_action('elementor/db/before_save', [__CLASS__, 'dbBeforeSave'], 10, 2);
        add_action('_wp_put_post_revision', [__CLASS__, 'saveRevision']);
        add_action('wp_creating_autosave', [__CLASS__, 'updateAutosave']);
        add_action('elementor/ajax/register_actions', [__CLASS__, 'registerAjaxActions']);

        // add_filter('edit_post_content', [__CLASS__, 'avoidDeleteAutoSave'], 10, 2);
        // add_action('edit_form_after_title', [__CLASS__, 'removeTempPostContent']);

        if (wp_doing_ajax()) {
            add_filter('elementor/documents/ajax_save/return_data', [__CLASS__, 'onAjaxSaveBuilderData'], 10, 2);
        }
    }

    /**
     * @since 1.9.0
     * @static
     */
    private static function currentRevisionId($post_id)
    {
        $current_revision_id = $post_id;
        $autosave = Utils::getPostAutosave($post_id);

        if (is_object($autosave)) {
            $current_revision_id = $autosave->ID;
        }

        return $current_revision_id;
    }
}
