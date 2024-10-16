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

use CE\CoreXCommonXModulesXAjaxXModule as Ajax;
use CE\CoreXDocumentTypesXPost as Post;
use CE\TemplateLibraryXSourceLocal as SourceLocal;

/**
 * Elementor documents manager.
 *
 * Elementor documents manager handler class is responsible for registering and
 * managing Elementor documents.
 *
 * @since 2.0.0
 */
class CoreXDocumentsManager
{
    /**
     * Registered types.
     *
     * Holds the list of all the registered types.
     *
     * @since 2.0.0
     *
     * @var Document[]
     */
    protected $types = [];

    /**
     * Registered documents.
     *
     * Holds the list of all the registered documents.
     *
     * @since 2.0.0
     *
     * @var Document[]
     */
    protected $documents = [];

    /**
     * Current document.
     *
     * Holds the current document.
     *
     * @since 2.0.0
     *
     * @var Document
     */
    protected $current_doc;

    /**
     * Switched data.
     *
     * Holds the current document when changing to the requested post.
     *
     * @since 2.0.0
     *
     * @var array
     */
    protected $switched_data = [];

    protected $cpt = [];

    /**
     * Documents manager constructor.
     *
     * Initializing the Elementor documents manager.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        add_action('elementor/documents/register', [$this, 'registerDefaultTypes'], 0);
        add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);
        // add_filter('post_row_actions', [$this, 'filterPostRowActions'], 11, 2);
        // add_filter('page_row_actions', [$this, 'filterPostRowActions'], 11, 2);
        // add_filter('user_has_cap', [$this, 'removeUserEditCap'], 10, 3);
        add_filter('elementor/editor/localize_settings', [$this, 'localizeSettings']);
    }

    /**
     * Register ajax actions.
     *
     * Process ajax action handles when saving data and discarding changes.
     *
     * Fired by `elementor/ajax/register_actions` action.
     *
     * @since 2.0.0
     *
     * @param Ajax $ajax_manager An instance of the ajax manager
     */
    public function registerAjaxActions($ajax_manager)
    {
        $ajax_manager->registerAjaxAction('save_builder', [$this, 'ajaxSave']);
        $ajax_manager->registerAjaxAction('discard_changes', [$this, 'ajaxDiscardChanges']);
        $ajax_manager->registerAjaxAction('get_document_config', [$this, 'ajaxGetDocumentConfig']);
    }

    /**
     * Register default types.
     *
     * Registers the default document types.
     *
     * @since 2.0.0
     */
    public function registerDefaultTypes()
    {
        $default_types = [
            'post' => Post::getClassFullName(),
        ];

        foreach ($default_types as $type => $class) {
            $this->registerDocumentType($type, $class);
        }
    }

    /**
     * Register document type.
     *
     * Registers a single document.
     *
     * @since 2.0.0
     *
     * @param string $type Document type name
     * @param string $class the name of the class that registers the document type
     *                      Full name with the namespace
     *
     * @return DocumentsManager The updated document manager instance
     */
    public function registerDocumentType($type, $class)
    {
        $this->types[$type] = $class;

        $cpt = $class::getProperty('cpt');

        if ($cpt) {
            foreach ($cpt as $post_type) {
                $this->cpt[$post_type] = $type;
            }
        }

        if ($class::getProperty('register_type')) {
            SourceLocal::addTemplateType($type);
        }

        return $this;
    }

    /**
     * Get document.
     *
     * Retrieve the document data based on a post ID.
     *
     * @since 2.0.0
     *
     * @param int $post_id Post ID
     * @param bool $from_cache Optional. Whether to retrieve cached data. Default is true
     *
     * @return false|Document Document data or false if post ID was not entered
     */
    public function get($post_id, $from_cache = true)
    {
        $this->registerTypes();

        $post_id = absint($post_id);

        // if (!$post_id || !get_post($post_id)) {
        if (!$post_id || !$uid = UId::parse($post_id)) {
            return false;
        }

        // $post_id = apply_filters('elementor/documents/get/post_id', "$post_id");
        $post_id = "$post_id";

        if (!$from_cache || !isset($this->documents[$post_id])) {
            if ($parent = wp_is_post_autosave($post_id)) {
                $post_type = get_post_type($parent);
            } else {
                $post_type = get_post_type($post_id);
            }

            $doc_type = 'post';

            if (isset($this->cpt[$post_type])) {
                $doc_type = $this->cpt[$post_type];
            }

            // $meta_type = get_post_meta($post_id, Document::TYPE_META_KEY, true);
            $meta_type = get_post($uid)->template_type;

            if ($meta_type && isset($this->types[$meta_type])) {
                $doc_type = $meta_type;
            }

            $doc_type_class = $this->getDocumentType($doc_type);
            $this->documents[$post_id] = new $doc_type_class([
                'post_id' => $post_id,
            ]);
        }

        return $this->documents[$post_id];
    }

    /**
     * Get document or autosave.
     *
     * Retrieve either the document or the autosave.
     *
     * @since 2.0.0
     *
     * @param int $id Post ID
     * @param int $user_id User ID. Default is `0`
     *
     * @return false|Document The document if it exist, False otherwise
     */
    public function getDocOrAutoSave($id, $user_id = 0)
    {
        $document = $this->get($id);
        // if ($document && $document->getAutosaveId($user_id)) {
        //     $document = $document->getAutosave($user_id);
        // }
        $document && $document = $document->getAutosave($user_id) ?: $document;

        return $document;
    }

    /**
     * Get document for frontend.
     *
     * Retrieve the document for frontend use.
     *
     * @since 2.0.0
     *
     * @param int $post_id Optional. Post ID. Default is `0`
     *
     * @return false|Document The document if it exist, False otherwise
     */
    public function getDocForFrontend($post_id)
    {
        if (is_preview()) {
            $document = $this->getDocOrAutoSave($post_id);
        } else {
            $document = $this->get($post_id);
        }

        return $document;
    }

    /**
     * Get document type.
     *
     * Retrieve the type of any given document.
     *
     * @since  2.0.0
     *
     * @param string $type
     * @param string $fallback
     *
     * @return Document|bool The type of the document
     */
    public function getDocumentType($type, $fallback = 'post')
    {
        $types = $this->getDocumentTypes();

        if (isset($types[$type])) {
            return $types[$type];
        }

        if (isset($types[$fallback])) {
            return $types[$fallback];
        }

        return false;
    }

    /**
     * Get document types.
     *
     * Retrieve the all the registered document types.
     *
     * @since  2.0.0
     *
     * @param array $args Optional. An array of key => value arguments to match against
     *                    the properties. Default is empty array.
     * @param string $operator Optional. The logical operation to perform. 'or' means only one
     *                         element from the array needs to match; 'and' means all elements
     *                         must match; 'not' means no elements may match. Default 'and'.
     *
     * @return Document[] All the registered document types
     */
    public function getDocumentTypes($args = [], $operator = 'and')
    {
        $this->registerTypes();

        if (!empty($args)) {
            // $types_properties = $this->getTypesProperties();

            // $filtered = wp_filter_object_list($types_properties, $args, $operator);

            // return array_intersect_key($this->types, $filtered);
            throw new \RuntimeException('TODO');
        }

        return $this->types;
    }

    /**
     * Get document types with their properties.
     *
     * @return array A list of properties arrays indexed by the type
     */
    public function getTypesProperties()
    {
        $types_properties = [];

        foreach ($this->getDocumentTypes() as $type => $class) {
            $types_properties[$type] = $class::getProperties();
        }

        return $types_properties;
    }

    /**
     * Create a document.
     *
     * Create a new document using any given parameters.
     *
     * @since 2.0.0
     *
     * @param string $type Document type
     * @param array $post_data An array containing the post data
     * @param array $meta_data An array containing the post meta data
     *
     * @return Document The type of the document
     */
    public function create($type, $post_data = [], $meta_data = [])
    {
        $class = $this->getDocumentType($type, false);

        if (!$class) {
            return new WPError(500, sprintf('Type %s does not exist.', $type));
        }

        // if (empty($post_data['post_title'])) {
        //     $post_data['post_title'] = __('Elementor');
        //     if ('post' !== $type) {
        //         $post_data['post_title'] = sprintf(
        //             __('Elementor %s'),
        //             call_user_func([$class, 'get_title'])
        //         );
        //     }
        //     $update_title = true;
        // }

        // $meta_data['_elementor_edit_mode'] = 'builder';

        // $meta_data[Document::TYPE_META_KEY] = $type;
        $post_data['template_type'] = $type;

        // $post_data['meta_input'] = $meta_data;

        $post_id = wp_insert_post($post_data);

        // if (!empty($update_title)) {
        //     $post_data['ID'] = $post_id;
        //     $post_data['post_title'] .= ' #' . $post_id;

        //     // The meta doesn't need update.
        //     unset($post_data['meta_input']);

        //     wp_update_post($post_data);
        // }

        /* @var Document $document */
        $document = new $class([
            'post_id' => $post_id,
        ]);

        // Let the $document to re-save the template type by his way + version.
        $document->save([]);

        return $document;
    }

    // public function removeUserEditCap($allcaps, $caps, $args)

    // public function filterPostRowActions($actions, $post)

    /**
     * Save document data using ajax.
     *
     * Save the document on the builder using ajax, when saving the changes, and refresh the editor.
     *
     * @since 2.0.0
     *
     * @param $request Post ID
     *
     * @return array The document data after saving
     *
     * @throws \Exception if current user don't have permissions to edit the post or the post is not using Elementor
     */
    public function ajaxSave($request)
    {
        $document = $this->get($request['editor_post_id']);

        // if (!$document->isBuiltWithElementor() || !$document->isEditableByCurrentUser()) {
        if (!$document->isEditableByCurrentUser()) {
            throw new \Exception('Access denied.');
        }

        $this->switchToDocument($document);
        $post = $document->getPost();

        // Set the post as global post.
        Plugin::$instance->db->switchToPost($post->ID);

        $status = DB::STATUS_DRAFT;

        if (isset($request['status']) && in_array($request['status'], [DB::STATUS_PUBLISH, DB::STATUS_PRIVATE, DB::STATUS_AUTOSAVE], true)) {
            $status = $request['status'];
        }

        if (DB::STATUS_AUTOSAVE === $status) {
            // If the post is a draft - save the `autosave` to the original draft.
            // Allow a revision only if the original post is already published.
            if (in_array($post->post_status, [DB::STATUS_PUBLISH, DB::STATUS_PRIVATE], true)) {
                $document = $document->getAutosave(0, true);
            }
        }

        // Set default page template because the footer-saver doesn't send default values,
        // But if the template was changed from canvas to default - it needed to save.
        if (Utils::isCptCustomTemplatesSupported($post) && !isset($request['settings']['template'])) {
            $request['settings']['template'] = 'default';
        }

        $data = [
            'elements' => $request['elements'],
            'settings' => $request['settings'],
        ];

        $document->save($data);

        // Refresh after save.
        $document = $this->get($post->ID, false);

        $return_data = [
            'status' => $document->getPost()->post_status,
            'config' => [
                'document' => [
                    'last_edited' => $document->getLastEdited(),
                    'urls' => [
                        'wp_preview' => $document->getWpPreviewUrl(),
                    ],
                ],
            ],
        ];

        /*
         * Returned documents ajax saved data.
         *
         * Filters the ajax data returned when saving the post on the builder.
         *
         * @since 2.0.0
         *
         * @param array    $return_data The returned data
         * @param Document $document    The document instance
         */
        $return_data = apply_filters('elementor/documents/ajax_save/return_data', $return_data, $document);

        return $return_data;
    }

    /**
     * Ajax discard changes.
     *
     * Load the document data from an autosave, deleting unsaved changes.
     *
     * @since 2.0.0
     *
     * @param $request
     *
     * @return bool True if changes discarded, False otherwise
     */
    public function ajaxDiscardChanges($request)
    {
        $document = $this->get($request['editor_post_id']);

        $autosave = $document->getAutosave();

        if ($autosave) {
            $success = $autosave->delete();
        } else {
            $success = true;
        }

        return $success;
    }

    public function ajaxGetDocumentConfig($request)
    {
        $post_id = absint($request['id']);

        Plugin::$instance->editor->setPostId($post_id);

        $document = $this->getDocOrAutoSave($post_id);

        if (!$document) {
            throw new \Exception('Not Found.');
        }

        if (!$document->isEditableByCurrentUser()) {
            throw new \Exception('Access denied.');
        }

        // Set the global data like $post, $authordata and etc
        Plugin::$instance->db->switchToPost($post_id);

        $this->switchToDocument($document);

        // Change mode to Builder
        // Plugin::$instance->db->setIsElementorPage($post_id);

        $doc_config = $document->getConfig();

        return $doc_config;
    }

    /**
     * Switch to document.
     *
     * Change the document to any new given document type.
     *
     * @since 2.0.0
     *
     * @param Document $document The document to switch to
     */
    public function switchToDocument($document)
    {
        // If is already switched, or is the same post, return.
        if ($this->current_doc === $document) {
            $this->switched_data[] = false;

            return;
        }

        $this->switched_data[] = [
            'switched_doc' => $document,
            'original_doc' => $this->current_doc, // Note, it can be null if the global isn't set
        ];

        $this->current_doc = $document;
    }

    /**
     * Restore document.
     *
     * Rollback to the original document.
     *
     * @since 2.0.0
     */
    public function restoreDocument()
    {
        $data = array_pop($this->switched_data);

        // If not switched, return.
        if (!$data) {
            return;
        }

        $this->current_doc = $data['original_doc'];
    }

    /**
     * Get current document.
     *
     * Retrieve the current document.
     *
     * @since 2.0.0
     *
     * @return Document The current document
     */
    public function getCurrent()
    {
        return $this->current_doc;
    }

    public function localizeSettings($settings)
    {
        $translations = [];

        foreach ($this->getDocumentTypes() as $type => $class) {
            $translations[$type] = $class::getTitle();
        }

        return array_replace_recursive($settings, [
            'i18n' => $translations,
        ]);
    }

    private function registerTypes()
    {
        if (!did_action('elementor/documents/register')) {
            /*
             * Register Elementor documents.
             *
             * @since 2.0.0
             *
             * @param DocumentsManager $this The document manager instance
             */
            do_action('elementor/documents/register', $this);
        }
    }
}
