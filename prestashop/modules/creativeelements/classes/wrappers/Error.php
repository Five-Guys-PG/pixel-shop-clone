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

class WPError
{
    private $code;

    private $message;

    public function __construct($code = '', $message = '', $data = '')
    {
        if (!$code) {
            return;
        }
        if ($data) {
            throw new \RuntimeException('todo');
        }
        $this->code = $code;
        $this->message = $message;
    }

    public function getErrorMessage($code = '')
    {
        if (!$this->code) {
            return '';
        }
        if ($code) {
            throw new \RuntimeException('todo');
        }

        return $this->code . ($this->message ? " - {$this->message}" : '');
    }
}

function is_wp_error($error)
{
    return $error instanceof WPError;
}

function _doing_it_wrong($function, $message = '', $version = '')
{
    exit(\Tools::displayError($function . ' was called incorrectly. ' . $message . ' ' . $version));
}
