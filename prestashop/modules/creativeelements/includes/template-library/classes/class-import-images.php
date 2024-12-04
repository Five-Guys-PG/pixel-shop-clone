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

class TemplateLibraryXClassesXImportImages
{
    const DIR = 'cms/';
    const PLACEHOLDER = 'placeholder.png';
    const ALLOWED_EXT = ['jpg', 'jpe', 'jpeg', 'png', 'gif', 'bmp', 'tif', 'tiff', 'svg', 'webp'];

    private static $imported = [];

    public function import($attachment)
    {
        $url = $attachment['url'];

        if (isset(self::$imported[$url])) {
            // Image was already imported
            return self::$imported[$url];
        }

        if (count($cms = explode('/img/cms/', $url)) > 1) {
            // Get filename with subdir
            $filename = $cms[1];
        } else {
            $filename = basename($url);

            if (self::PLACEHOLDER == $filename) {
                // Do not import placeholder
                return self::$imported[$url] = false;
            }
        }

        $file_content = wp_remote_get($url);
        if (empty($file_content)) {
            // Image isn't available
            return self::$imported[$url] = false;
        }

        $file_info = pathinfo($filename);
        if (empty($file_info['extension'])) {
            // URL doesn't have extendsion
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_buffer($finfo, $file_content);
            finfo_close($finfo);

            if ($mime_type && $pos = strpos($mime_type, '/')) {
                $file_info['extension'] = explode('+', substr($mime_type, ++$pos))[0];
                $filename = $file_info['basename'] .= '.' . $file_info['extension'];
            } else {
                $file_info['extension'] = '';
            }
        }

        if (!in_array(\Tools::strToLower($file_info['extension']), self::ALLOWED_EXT)) {
            // Image extension isn't allowed
            return self::$imported[$url] = false;
        }

        if ($file_info['dirname'] !== '.' && !is_dir(_PS_IMG_DIR_ . self::DIR . $file_info['dirname'])) {
            // Create subdir
            if (!@mkdir(_PS_IMG_DIR_ . self::DIR . $file_info['dirname'], 0775, true)) {
                // Can not create subdir
                $filename = $file_info['basename'];
            }
        }

        $file_path = _PS_IMG_DIR_ . self::DIR . $filename;
        if (file_exists($file_path)) {
            // Filename already exists
            $existing_content = @call_user_func('file_get_contents', $file_path);

            if ($file_content === $existing_content) {
                // Same image already exists
                return self::$imported[$url] = [
                    'id' => 0,
                    'url' => basename(_PS_IMG_) . '/' . self::DIR . $filename,
                ];
            }

            // Add unique filename
            $dirname = $file_info['dirname'] !== '.' && $filename !== $file_info['basename'] ? $file_info['dirname'] . '/' : '';
            $filename = $dirname . $file_info['filename'] . '_' . Utils::generateRandomString() . '.' . $file_info['extension'];
            $file_path = _PS_IMG_DIR_ . self::DIR . $filename;
        }

        if (@call_user_func('file_put_contents', $file_path, $file_content)) {
            // Image saved successfuly
            return self::$imported[$url] = [
                'id' => 0,
                'url' => basename(_PS_IMG_) . '/' . self::DIR . $filename,
            ];
        }

        // Fallback
        return $attachment;
    }
}
