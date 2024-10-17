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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\CoreXDynamicTagsXTag as Tag;
use CE\CoreXFilesXCSSXBase as Base;
use CE\CoreXFilesXCSSXPost as Post;
use CE\CoreXSettingsXManager as SettingsManager;

class ModulesXCatalogXFilesXCSSXMiniature extends Post
{
    private $handle_id;

    private $preview_id;

    public function __construct($post_id, $preview_id = false)
    {
        $this->handle_id = $preview_id ?: $post_id;

        $this->preview_id = $preview_id;

        parent::__construct($post_id);
    }

    public function getName()
    {
        return 'miniature';
    }

    protected function getFileHandleId()
    {
        return 'ce-miniature-' . $this->handle_id;
    }

    protected function getData()
    {
        if (!$this->preview_id) {
            return parent::getData();
        }

        $document = Plugin::$instance->documents->get($this->preview_id);

        return $document ? $document->getElementsData() : [];
    }

    public function enqueue()
    {
        Base::enqueue();
    }

    public function getMeta($property = null)
    {
        if (!$this->preview_id) {
            return parent::getMeta($property);
        }
        // Add Page Settings related styles for Preview
        $model = SettingsManager::getSettingsManagers('page')->getModel($this->preview_id);

        $this->addControlsStackStyleRules(
            $model,
            $model->getStyleControls(),
            $model->getSettings(),
            ['{{WRAPPER}}'],
            [$model->getCssWrapperSelector()]
        );
        // Parse CSS first, to get the fonts list.
        $css = $this->getContent();

        $meta = [
            'status' => self::CSS_STATUS_INLINE,
            'fonts' => $this->getFonts(),
            'css' => &$css,
        ];

        if ($property) {
            return isset($meta[$property]) ? $meta[$property] : null;
        }

        return $meta;
    }

    protected function parseContent()
    {
        Tag::setRenderMethod('renderSmarty');
        DataTag::setGetterMethod('getSmartyValue');

        $content = parent::parseContent();

        Tag::setRenderMethod('render');
        DataTag::setGetterMethod('getValue');

        return $content;
    }
}
