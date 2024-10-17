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

use CE\CoreXCommonXModulesXAjaxXModule as Ajax;

/**
 * Elementor user.
 *
 * Elementor user handler class is responsible for checking if the user can edit
 * with Elementor and displaying different admin notices.
 *
 * @since 1.0.0
 */
class User
{
    /**
     * The admin notices key.
     */
    const ADMIN_NOTICES_KEY = 'elementor_admin_notices';

    const INTRODUCTION_KEY = 'elementor_introduction';

    // const BETA_TESTER_META_KEY = 'elementor_beta_tester';

    // const BETA_TESTER_API_URL

    /**
     * Init.
     *
     * Initialize Elementor user.
     *
     * @since 1.0.0
     * @static
     */
    public static function init()
    {
        add_action('wp_ajax_elementor_set_admin_notice_viewed', [__CLASS__, 'ajaxSetAdminNoticeViewed']);
        // add_action('admin_post_elementor_set_admin_notice_viewed', [__CLASS__, 'ajaxSetAdminNoticeViewed']);

        add_action('elementor/ajax/register_actions', [__CLASS__, 'registerAjaxActions']);
    }

    /**
     * @since 2.1.0
     * @static
     */
    public static function registerAjaxActions(Ajax $ajax)
    {
        $ajax->registerAjaxAction('introduction_viewed', [__CLASS__, 'setIntroductionViewed']);
        // $ajax->registerAjaxAction('beta_tester_signup', [__CLASS__, 'registerAsBetaTester']);
    }

    /**
     * Is current user can edit.
     *
     * Whether the current user can edit the post.
     *
     * @since 1.0.0
     * @static
     *
     * @param int $post_id Optional. The post ID. Default is `0`
     *
     * @return bool Whether the current user can edit the post
     */
    public static function isCurrentUserCanEdit($post_id = 0)
    {
        $post = get_post($post_id);

        if (!$post) {
            return false;
        }

        // if ('trash' === get_post_status($post_id)) {
        //     return false;
        // }

        // if (!self::isCurrentUserCanEditPostType($post->post_type)) {
        //     return false;
        // }

        $post_type_object = get_post_type_object($post->post_type);

        if (!isset($post_type_object->cap->edit_post)) {
            return false;
        }

        $edit_cap = $post_type_object->cap->edit_post;

        if (!current_user_can($edit_cap, $post_id)) {
            return false;
        }

        // if (get_option('page_for_posts') === $post_id) {
        //     return false;
        // }

        return true;
    }

    // public static function isCurrentUserInEditingBlackList()

    /**
     * Is current user can edit post type.
     *
     * Whether the current user can edit the given post type.
     *
     * @since 1.9.0
     * @static
     *
     * @param string $post_type the post type slug to check
     *
     * @return bool True if can edit, False otherwise
     */
    public static function isCurrentUserCanEditPostType($post_type)
    {
        // if (!self::isCurrentUserInEditingBlackList()) {
        //     return false;
        // }

        if (!Utils::isPostTypeSupport($post_type)) {
            return false;
        }

        $post_type_object = get_post_type_object($post_type);

        if (!current_user_can($post_type_object->cap->edit_posts)) {
            return false;
        }

        return true;
    }

    /**
     * Get user notices.
     *
     * Retrieve the list of notices for the current user.
     *
     * @since 2.0.0
     * @static
     *
     * @return array A list of user notices
     */
    private static function getUserNotices()
    {
        return get_user_meta(get_current_user_id(), self::ADMIN_NOTICES_KEY, true);
    }

    /**
     * Is user notice viewed.
     *
     * Whether the notice was viewed by the user.
     *
     * @since 1.0.0
     * @static
     *
     * @param int $notice_id The notice ID
     *
     * @return bool Whether the notice was viewed by the user
     */
    public static function isUserNoticeViewed($notice_id)
    {
        $notices = self::getUserNotices();

        if (empty($notices) || empty($notices[$notice_id])) {
            return false;
        }

        return true;
    }

    /**
     * Set admin notice as viewed.
     *
     * Flag the user admin notice as viewed using an authenticated ajax request.
     *
     * Fired by `wp_ajax_elementor_set_admin_notice_viewed` action.
     *
     * @since 1.0.0
     * @static
     */
    public static function ajaxSetAdminNoticeViewed()
    {
        if (empty($_REQUEST['notice_id'])) {
            exit;
        }

        $notices = self::getUserNotices();
        if (empty($notices)) {
            $notices = [];
        }

        $notices[$_REQUEST['notice_id']] = 'true';
        update_user_meta(get_current_user_id(), self::ADMIN_NOTICES_KEY, $notices);

        // if (!wp_doing_ajax()) {
        //     wp_safe_redirect(admin_url());
        //     die;
        // }

        exit;
    }

    /**
     * @since 2.1.0
     * @static
     */
    public static function setIntroductionViewed(array $data)
    {
        $user_introduction_meta = self::getIntroductionMeta();

        $user_introduction_meta[$data['introductionKey']] = true;

        update_user_meta(get_current_user_id(), self::INTRODUCTION_KEY, $user_introduction_meta);
    }

    // public static function register_as_beta_tester(array $data)

    /**
     * @param string $key
     *
     * @return array|mixed|string
     *
     * @since  2.1.0
     * @static
     */
    public static function getIntroductionMeta($key = '')
    {
        $user_introduction_meta = get_user_meta(get_current_user_id(), self::INTRODUCTION_KEY, true);

        if (!$user_introduction_meta) {
            $user_introduction_meta = [];
        }

        if ($key) {
            return empty($user_introduction_meta[$key]) ? '' : $user_introduction_meta[$key];
        }

        return $user_introduction_meta;
    }
}
