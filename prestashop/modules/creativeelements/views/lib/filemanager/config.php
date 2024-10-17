<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

const FIELD_IMAGE_ALL = 0xCE;
const FIELD_IMAGE_SVG = 0xCE + 1;
const FIELD_VIDEO_ALL = 0xCE + 2;

$field_id = (int) Tools::getValue('field_id');

if ($field_id < FIELD_IMAGE_ALL && basename($_SERVER['SCRIPT_NAME']) === 'dialog.php') {
    return;
}

switch ($field_id) {
    case FIELD_IMAGE_ALL:
        $ext = $ext_img = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', 'webp'];
        break;
    case FIELD_IMAGE_SVG:
        $ext = $ext_img = ['svg'];
        break;
    case FIELD_VIDEO_ALL:
        $ext = $ext_video = ['mov', 'mp4', 'webm'];
        break;
    default:
        $ext[] = 'webp';
        $mime[] = 'image/webp';
        $mime[] = 'video/quicktime';
}

if (in_array(Tools::getValue('action'), ['rename_file', 'duplicate_file', 'delete_file'])) {
    // Path fix for actions
    ${'_POST'}['path'] = Tools::substr(${'_POST'}['path_thumb'], Tools::strlen($thumbs_base_path));
}

if ($attachment = Tools::fileAttachment('file')) {
    if (!strcasecmp(pathinfo($attachment['name'], PATHINFO_EXTENSION), 'svg') && class_exists('DOMDocument') && class_exists('SimpleXMLElement')) {
        require _PS_MODULE_DIR_ . 'creativeelements/core/files/assets/files-upload-handler.php';
        require _PS_MODULE_DIR_ . 'creativeelements/core/files/assets/svg/svg-handler.php';

        $svg_handler = new CE\CoreXFilesXAssetsXSvgXSvgHandler();
        $svg_handler->sanitizeSvg($attachment['tmp_name']);
    }

    register_shutdown_function(function () {
        $error = error_get_last();

        if ($error && strpos($error['message'], '.webp is missing or invalid') !== false) {
            // Ignore WEBP thumbnail generation error
            http_response_code(200);
        }
    });
}
