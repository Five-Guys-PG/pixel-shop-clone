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

use CE\CoreXDocumentsManager as DocumentsManager;
use CE\CoreXFilesXCSSXPost as PostCSS;
use CE\CoreXFilesXCSSXPostPreview as PostPreview;
use CE\CoreXKitsXDocumentsXKit as Kit;
use CE\TemplateLibraryXSourceLocal as SourceLocal;

class CoreXKitsXManager
{
    const OPTION_ACTIVE = 'elementor_active_kit';

    private $id_kit;

    public function getActiveId()
    {
        $kit_post = null;
        $id_kit = &$this->id_kit;
        $id = new UId($id_kit, UId::TEMPLATE);

        if ($id_kit) {
            $kit_post = get_post($id);
        }

        if (!$id_kit || !$kit_post || 'trash' === $kit_post->post_status) {
            $id = $this->createDefault();
            $id_kit = substr($id, 0, -6);

            \Configuration::getGlobalValue(self::OPTION_ACTIVE)
                ? \Configuration::updateValue(self::OPTION_ACTIVE, $id_kit)
                : \Configuration::updateGlobalValue(self::OPTION_ACTIVE, $id_kit)
            ;
        }

        return $id;
    }

    public function getActiveKit()
    {
        $id = $this->getActiveId();

        return Plugin::$instance->documents->get($id);
    }

    private function createDefault()
    {
        if (!is_admin()) {
            return 0;
        }

        $kit = Plugin::$instance->documents->create('kit', [
            'post_type' => SourceLocal::CPT,
            'post_title' => __('Default'),
            'post_status' => 'publish',
        ]);

        return $kit->getId();
    }

    /**
     * @param DocumentsManager $documents_manager
     */
    public function registerDocument($documents_manager)
    {
        $documents_manager->registerDocumentType('kit', Kit::getClassFullName());
    }

    public function localizeSettings($settings)
    {
        $kit = $this->getActiveKit();

        $settings = array_replace_recursive($settings, [
            'kit_id' => $kit->getMainId(),
            'user' => [
                'can_edit_kit' => $kit->isEditableByCurrentUser(),
            ],
            'i18n' => [
                'Close' => __('Close'),
                'Back' => __('Back'),
                'Theme Style' => __('Theme Style'),
            ],
        ]);

        return $settings;
    }

    public function previewEnqueueStyles()
    {
        $kit = $this->getKitForFrontend();

        if ($kit) {
            // On preview, the global style is not enqueued.
            $this->frontendBeforeEnqueueStyles();

            Plugin::$instance->frontend->printFontsLinks();
        }
    }

    public function frontendBeforeEnqueueStyles()
    {
        $kit = $this->getKitForFrontend();

        if ($kit) {
            if ($kit->isAutosave()) {
                $css_file = PostPreview::create($kit->getId());
            } else {
                $css_file = PostCSS::create($kit->getId());
            }
            $css_file->enqueue();

            // Plugin::$instance->frontend->addBodyClass('elementor-kit-' . $kit->getMainId());
            $vars = &$GLOBALS['smarty']->tpl_vars;
            isset($vars['page']) && $vars['page']->value['body_classes']["ce-kit-{$this->id_kit}"] = 1;
        }
    }

    public function renderPanelHtml()
    {
        require __DIR__ . '/views/panel.php';
    }

    public function getKitForFrontend()
    {
        $kit = false;

        if (!$active_kit = $this->getActiveKit()) {
            return $kit;
        }
        $is_kit_preview = is_preview() && $active_kit->getMainId() == (int) $_REQUEST['preview_id'];

        if ($is_kit_preview) {
            $kit = Plugin::$instance->documents->getDocOrAutoSave($active_kit->getMainId());
        } elseif ('publish' === $active_kit->getMainPost()->post_status) {
            $kit = $active_kit;
        }

        return $kit;
    }

    public function __construct()
    {
        $this->id_kit = (int) \Configuration::get(self::OPTION_ACTIVE);

        add_action('elementor/documents/register', [$this, 'registerDocument']);
        add_filter('elementor/editor/localize_settings', [$this, 'localizeSettings']);
        add_action('elementor/editor/footer', [$this, 'renderPanelHtml']);
        // add_action('elementor/frontend/after_enqueue_global', [$this, 'frontendBeforeEnqueueStyles'], 0);
        add_action('elementor/preview/enqueue_styles', [$this, 'previewEnqueueStyles'], 0);
    }
}
