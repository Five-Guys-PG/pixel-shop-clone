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

use CE\CoreXBaseXPageBase as PageBase;
use CE\ModulesXThemeXDocumentsXThemeDocument as ThemeDocument;

abstract class ModulesXThemeXDocumentsXThemePageDocument extends ThemeDocument
{
    public function getCssWrapperSelector()
    {
        return 'body.ce-theme-' . substr($this->getMainId(), 0, -6);
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        PageBase::registerPostFieldsControl($this);

        PageBase::registerStyleControls($this);
    }
}
