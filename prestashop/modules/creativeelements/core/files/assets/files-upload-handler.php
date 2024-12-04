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

abstract class CoreXFilesXAssetsXFilesUploadHandler
{
    // const OPTION_KEY = 'elementor_unfiltered_files_upload';

    // public function __construct()

    abstract public function getMimeType();

    abstract public function getFileType();

    // private function isElementorMediaUpload()

    /**
     * @return bool
     */
    final public static function isEnabled()
    {
        // $enabled = get_option(self::OPTION_KEY) && self::fileSanitizerCanRun();

        /*
         * Allow Unfiltered Files Upload.
         *
         * Determines weather to enable unfiltered files upload.
         *
         * @since 3.0.0
         *
         * @param bool $enabled Weather upload is enabled or not
         */
        // $enabled = apply_filters('elementor/files/allow_unfiltered_upload', $enabled);

        // return $enabled;
        return self::fileSanitizerCanRun();
    }

    // final public function supportUnfilteredFilesUpload($existing_mimes)

    // public function handleUploadPrefilter($file)

    // protected function isFileShouldHandled($file)

    /**
     * File sanitizer can run
     *
     * @return bool
     */
    public static function fileSanitizerCanRun()
    {
        return class_exists('DOMDocument') && class_exists('SimpleXMLElement');
    }

    // public function checkFiletypeAndExt($data, $file, $filename, $mimes)
}
