<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\ModulesXCatalogXWidgetsXProductXName as ProductName;

class ModulesXCatalogXWidgetsXProductXMiniatureXName extends ProductName
{
    public function getName()
    {
        return 'product-miniature-name';
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('link_to', [
            'default' => 'custom',
        ]);

        $this->updateControl('header_size', [
            'default' => 'h3',
        ]);

        $this->updateControl('title_multiline', [
            'default' => '',
        ]);
    }
}
