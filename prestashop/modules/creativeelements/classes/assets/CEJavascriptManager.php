<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class CEJavascriptManager extends JavascriptManager
{
    public function __construct(array $directories, ConfigurationInterface $configuration, $list = null)
    {
        parent::__construct($directories, $configuration);

        null !== $list && $this->list = $list;
    }

    public function getFullPath(string $relativePath)
    {
        if (strrpos($relativePath, '?') !== false) {
            $path = explode('?', $relativePath, 2);
            $fullPath = parent::getFullPath($path[0]);

            return $fullPath ? "$fullPath?$path[1]" : false;
        }

        return parent::getFullPath($relativePath);
    }

    public function getList()
    {
        return parent::getDefaultList();
    }

    public function listAll()
    {
        return parent::getList();
    }
}
