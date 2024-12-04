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

$body_classes = [
    'elementor-editor-active',
    'ps-version-' . str_replace('.', '-', _PS_VERSION_),
];

if (is_rtl()) {
    $body_classes[] = 'rtl';
}

// if (!Plugin::$instance->role_manager->userCan('design')) {
//     $body_classes[] = 'elementor-editor-content-only';
// }

$favicon = _PS_IMG_ . \Configuration::get('PS_FAVICON') . '?' . \Configuration::get('PS_IMG_UPDATE_TIME');
$notice = Plugin::$instance->editor->notice_bar->getNotice();

ob_start();
?><!DOCTYPE html>
<html lang="<?php echo get_locale(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if (\Tools::usingSecureMode()) { ?>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<?php } ?>
    <title><?php _e('Creative Elements - live Theme & Page Builder'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo esc_attr($favicon); ?>">
    <?php do_action('wp_head'); ?>
    <script>
        var ajaxurl = '<?php echo Helper::getAjaxLink(); ?>';
    </script>
</head>
<body class="<?php echo implode(' ', $body_classes); ?>">
    <div id="elementor-editor-wrapper">
        <div id="elementor-panel" class="elementor-panel"></div>
        <div id="elementor-preview">
            <div id="elementor-loading">
                <div class="elementor-loader-wrapper">
                    <div class="elementor-loader">
                        <div class="elementor-loader-boxes">
                            <div class="elementor-loader-box"></div>
                            <div class="elementor-loader-box"></div>
                            <div class="elementor-loader-box"></div>
                            <div class="elementor-loader-box"></div>
                        </div>
                    </div>
                    <div class="elementor-loading-title"><?php _e('Loading'); ?></div>
                </div>
            </div>
            <div id="elementor-preview-responsive-wrapper"
                class="elementor-device-desktop elementor-device-rotate-portrait">
                <div id="elementor-preview-loading">
                <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
                </div>
            <?php if ($notice) { ?>
                <div id="elementor-notice-bar">
                    <i class="eicon-elementor-square"></i>
                    <div id="elementor-notice-bar__message"><?php printf($notice['message'], $notice['action_url']); ?>
                    </div>
                    <div id="elementor-notice-bar__action">
                        <a href="<?php echo $notice['action_url']; ?>" target="_blank"><?php echo $notice['action_title']; ?></a>
                    </div>
                    <i id="elementor-notice-bar__close" class="eicon-close"></i>
                </div>
            <?php } ?>
            </div>
        </div>
        <div id="elementor-navigator"></div>
    </div>
    <?php do_action('wp_footer'); ?>
    <?php do_action('admin_print_footer_scripts'); ?>
</body>
</html>
<?php
ob_flush();
