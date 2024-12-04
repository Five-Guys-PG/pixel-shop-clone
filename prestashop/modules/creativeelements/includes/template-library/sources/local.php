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

use CE\CoreXBaseXDocument as Document;
use CE\CoreXSettingsXManager as SettingsManager;
use CE\CoreXSettingsXPageXModel as Model;

/**
 * Elementor template library local source.
 *
 * Elementor template library local source handler class is responsible for
 * handling local Elementor templates saved by the user locally on his site.
 *
 * @since 1.0.0
 */
class TemplateLibraryXSourceLocal extends TemplateLibraryXSourceBase
{
    /**
     * Elementor template-library post-type slug.
     */
    const CPT = 'CETemplate';

    // const TAXONOMY_TYPE_SLUG = 'elementor_library_type';
    // const TAXONOMY_CATEGORY_SLUG = 'elementor_library_category';
    // const TYPE_META_KEY = '_elementor_template_type';
    // const TEMP_FILES_DIR = 'elementor/tmp';
    // const BULK_EXPORT_ACTION = 'elementor_export_multiple_templates';
    // const ADMIN_MENU_SLUG = 'edit.php?post_type=elementor_library';
    // const ADMIN_SCREEN_ID = 'edit-elementor_library';

    /**
     * Template types.
     *
     * Holds the list of supported template types that can be displayed.
     *
     * @static
     *
     * @var array
     */
    private static $template_types = [];

    /**
     * @since 2.3.0
     * @static
     *
     * @return array
     */
    public static function getTemplateTypes()
    {
        return self::$template_types;
    }

    /**
     * Get local template type.
     *
     * Retrieve the template type from the post meta.
     *
     * @since 1.0.0
     * @static
     *
     * @param int $template_id The template ID
     *
     * @return mixed The value of meta data field
     */
    public static function getTemplateType($template_id)
    {
        $post = get_post($template_id);
        // return get_post_meta($template_id, Document::TYPE_META_KEY, true);
        return $post->template_type;
    }

    // public static function isBaseTemplatesScreen()

    /**
     * Add template type.
     *
     * Register new template type to the list of supported local template types.
     *
     * @since 1.0.3
     * @static
     *
     * @param string $type Template type
     */
    public static function addTemplateType($type)
    {
        self::$template_types[$type] = $type;
    }

    /**
     * Remove template type.
     *
     * Remove existing template type from the list of supported local template
     * types.
     *
     * @since 1.8.0
     * @static
     *
     * @param string $type Template type
     */
    public static function removeTemplateType($type)
    {
        if (isset(self::$template_types[$type])) {
            unset(self::$template_types[$type]);
        }
    }

    // public static function getAdminUrl($relative = false)

    /**
     * Get local template ID.
     *
     * Retrieve the local template ID.
     *
     * @since 1.0.0
     *
     * @return string The local template ID
     */
    public function getId()
    {
        return 'local';
    }

    /**
     * Get local template title.
     *
     * Retrieve the local template title.
     *
     * @since 1.0.0
     *
     * @return string The local template title
     */
    public function getTitle()
    {
        return __('Local');
    }

    // public function registerData()

    // public function adminMenuReorder()

    // public function adminMenu();

    // public function adminTitle($admin_title, $title);

    // public function replaceAdminHeading();

    /**
     * Get local templates.
     *
     * Retrieve local templates saved by the user on his site.
     *
     * @since 1.0.0
     *
     * @param array $args Optional. Filter templates based on a set of
     *                    arguments. Default is an empty array.
     *
     * @return array Local templates
     */
    public function getItems($args = [])
    {
        $templates = [];
        $rows = \Db::getInstance()->executeS('
            SELECT `id_ce_template`, `id_employee`, `title`, `type`, `date_add` FROM ' . _DB_PREFIX_ . 'ce_template
            WHERE `active` = 1 AND `type` <> "kit" ORDER BY `title`
        ');
        if ($rows) {
            foreach ($rows as &$row) {
                $post = new \stdClass();
                $post->ID = new UId($row['id_ce_template'], UId::TEMPLATE);
                $post->post_author = $row['id_employee'];
                $post->post_date = $row['date_add'];
                $post->post_title = $row['title'];
                $post->template_type = $row['type'];

                $templates[] = $this->getItem($post);
            }
        }

        return $templates;
    }

    /**
     * Save local template.
     *
     * Save new or update existing template on the database.
     *
     * @since 1.0.0
     *
     * @param array $template_data Local template data
     *
     * @return WPError|int The ID of the saved/updated template, `WP_Error` otherwise
     */
    public function saveItem($template_data)
    {
        if (!current_user_can('add_CETemplate')) {
            return new WPError('save_error', __('Access denied.'));
        }

        $defaults = [
            'title' => __('(no title)'),
            'page_settings' => [],
            'status' => 'publish',
        ];

        $template_data = array_merge($defaults, $template_data);

        $document = Plugin::$instance->documents->create(
            $template_data['type'],
            [
                'post_title' => $template_data['title'],
                'post_status' => $template_data['status'],
                'post_type' => self::CPT,
            ]
        );

        if (is_wp_error($document)) {
            /*
             * @var WPError $document
             */
            return $document;
        }

        if (!empty($template_data['content'])) {
            $template_data['content'] = $this->replaceElementsIds($template_data['content']);
        }

        $document->save([
            'elements' => $template_data['content'],
            'settings' => $template_data['page_settings'],
        ]);

        $template_id = $document->getMainId();

        /*
         * After template library save.
         *
         * Fires after Elementor template library was saved.
         *
         * @since 1.0.1
         *
         * @param int   $template_id   The ID of the template
         * @param array $template_data The template data
         */
        do_action('elementor/template-library/after_save_template', $template_id, $template_data);

        /*
         * After template library update.
         *
         * Fires after Elementor template library was updated.
         *
         * @since 1.0.1
         *
         * @param int   $template_id   The ID of the template
         * @param array $template_data The template data
         */
        do_action('elementor/template-library/after_update_template', $template_id, $template_data);

        return $template_id;
    }

    /**
     * Update local template.
     *
     * Update template on the database.
     *
     * @since 1.0.0
     *
     * @param array $new_data New template data
     *
     * @return WPError|true True if template updated, `WP_Error` otherwise
     */
    public function updateItem($new_data)
    {
        if (!current_user_can('edit', $new_data['id'])) {
            return new WPError('save_error', __('Access denied.'));
        }

        $document = Plugin::$instance->documents->get($new_data['id']);

        if (!$document) {
            return new WPError('save_error', __('Template not exist.'));
        }

        $document->save([
            'elements' => $new_data['content'],
        ]);

        /*
         * After template library update.
         *
         * Fires after Elementor template library was updated.
         *
         * @since 1.0.0
         *
         * @param int   $new_data_id The ID of the new template
         * @param array $new_data    The new template data
         */
        do_action('elementor/template-library/after_update_template', $new_data['id'], $new_data);

        return true;
    }

    /**
     * Get local template.
     *
     * Retrieve a single local template saved by the user on his site.
     *
     * @since 1.0.0
     *
     * @param int|object $template_id The template ID
     *
     * @return array Local template
     */
    public function getItem($template_id)
    {
        $post = is_object($template_id) ? $template_id : get_post($template_id);

        $user = get_user_by('id', $post->post_author);

        $page = SettingsManager::getSettingsManagers('page')->getModel($post->ID);

        $page_settings = $page->getData('settings');

        $data = [
            'template_id' => "$post->ID",
            'source' => $this->getId(),
            'type' => $post->template_type,
            'title' => $post->post_title,
            // 'thumbnail' => get_the_post_thumbnail_url($post),
            'date' => strtotime($post->post_date),
            'human_date' => \Tools::displayDate($post->post_date),
            'author' => $user ? $user->display_name : __('Unknown'),
            'hasPageSettings' => !empty($page_settings),
            'tags' => [],
            'export_link' => $this->getExportLink($post->ID),
            'url' => get_preview_post_link($post->ID),
        ];

        /*
         * Get template library template.
         *
         * Filters the template data when retrieving a single template from the
         * template library.
         *
         * @since 1.0.0
         *
         * @param array $data Template data
         */
        return apply_filters('elementor/template-library/get_template', $data);
    }

    /**
     * Get template data.
     *
     * Retrieve the data of a single local template saved by the user on his site.
     *
     * @since 1.5.0
     *
     * @param array $args Custom template arguments
     *
     * @return array Local template data
     */
    public function getData(array $args)
    {
        $db = Plugin::$instance->db;

        $template_id = $args['template_id'];

        // TODO: Validate the data (in JS too!).
        if (!empty($args['display'])) {
            $content = $db->getBuilder($template_id);
        } else {
            $document = Plugin::$instance->documents->get($template_id);
            $content = $document ? $document->getElementsData() : [];
        }

        if (!empty($content)) {
            $content = $this->replaceElementsIds($content);
        }

        $data = [
            'content' => $content,
        ];

        if (!empty($args['with_page_settings'])) {
            $page = SettingsManager::getSettingsManagers('page')->getModel($args['template_id']);

            $data['page_settings'] = $page->getData('settings');
        }

        return $data;
    }

    /**
     * Delete local template.
     *
     * Delete template from the database.
     *
     * @since 1.0.0
     *
     * @param int $template_id The template ID
     *
     * @return WPPost|WPError|false|null post data on success, false or null
     *                                   or 'WP_Error' on failure
     */
    public function deleteTemplate($template_id)
    {
        if (!current_user_can('delete', $template_id)) {
            return new WPError('template_error', __('Access denied.'));
        }

        return wp_delete_post($template_id, true) ? ['ID' => $template_id] : false;
    }

    /**
     * Export local template.
     *
     * Export template to a file.
     *
     * @since 1.0.0
     *
     * @param int $template_id The template ID
     *
     * @return WPError Wrapper error if template export failed
     */
    public function exportTemplate($template_id)
    {
        $file_data = $this->prepareTemplateExport($template_id);

        if (is_wp_error($file_data)) {
            return $file_data;
        }

        $this->sendFileHeaders($file_data['name'], strlen($file_data['content']));

        // Clear buffering just in case.
        @ob_end_clean();

        flush();

        // Output file contents.
        echo $file_data['content'];

        exit;
    }

    /**
     * Export multiple local templates.
     *
     * Export multiple template to a ZIP file.
     *
     * @since 1.6.0
     *
     * @param array $template_ids An array of template IDs
     *
     * @return WPError Wrapper error if export failed
     */
    public function exportMultipleTemplates(array $template_ids)
    {
        $files = [];

        $temp_path = _PS_UPLOAD_DIR_;

        // Create temp path if it doesn't exist
        @mkdir($temp_path, 0775, true);

        // Create all json files
        foreach ($template_ids as $template_id) {
            $file_data = $this->prepareTemplateExport($template_id);

            if (is_wp_error($file_data)) {
                continue;
            }

            $complete_path = $temp_path . $file_data['name'];

            if (!@call_user_func('file_put_contents', $complete_path, $file_data['content'])) {
                return new WPError('404', sprintf('Cannot create file "%s".', $file_data['name']));
            }

            $files[] = [
                'path' => $complete_path,
                'name' => $file_data['name'],
            ];
        }

        if (!$files) {
            return new WPError('empty_files', 'There is no files to export (probably all the requested templates are empty).');
        }

        // Create temporary .zip file
        $zip_archive_filename = 'CreativeElements_templates_' . date('Y-m-d') . '.zip';

        $zip_archive = new \ZipArchive();

        $zip_complete_path = $temp_path . $zip_archive_filename;

        $zip_archive->open($zip_complete_path, \ZipArchive::CREATE);

        foreach ($files as $file) {
            $zip_archive->addFile($file['path'], $file['name']);
        }

        $zip_archive->close();

        foreach ($files as $file) {
            \Tools::deleteFile($file['path']);
        }

        $this->sendFileHeaders($zip_archive_filename, filesize($zip_complete_path));

        @ob_end_flush();

        @readfile($zip_complete_path);

        \Tools::deleteFile($zip_complete_path);

        exit;
    }

    /**
     * Find temporary files.
     *
     * Recursively finds a list of temporary files from the extracted zip file.
     * Example return data:
     * [
     * '/path/to/tmp/5eb3a7a411d44/templates/block-2-col-marble-title.json',
     * '/path/to/tmp/5eb3a7a411d44/templates/block-2-col-text-and-photo.json',
     * ]
     *
     * @since 2.9.8
     *
     * @param string $temp_path - The temporary file path to scan for template files
     *
     * @return array An array of temporary files on the filesystem
     */
    private function findTempFiles($temp_path)
    {
        $file_names = [];

        $possible_file_names = array_diff(scandir($temp_path), ['.', '..']);

        // Find nested files in the unzipped path. This happens for example when the user imports a Template Kit.
        foreach ($possible_file_names as $possible_file_name) {
            $full_possible_file_name = $temp_path . '/' . $possible_file_name;

            if (is_dir($full_possible_file_name)) {
                $file_names += $this->findTempFiles($full_possible_file_name);
            } else {
                $file_names[] = $full_possible_file_name;
            }
        }

        return $file_names;
    }

    /**
     * Import local template.
     *
     * Import template from a file.
     *
     * @since 1.0.0
     *
     * @param string $name - The file name
     * @param string $path - The file path
     *
     * @return WPError|array An array of items on success, 'WP_Error' on failure
     */
    public function importTemplate($name, $path)
    {
        if (!$path) {
            return new WPError('file_error', 'Please upload a file to import');
        }

        $items = [];

        $file_extension = pathinfo($name, PATHINFO_EXTENSION);

        if ('zip' === $file_extension) {
            if (!class_exists('\ZipArchive')) {
                return new WPError('zip_error', 'PHP Zip extension not loaded');
            }

            $zip = new \ZipArchive();

            $temp_path = _PS_UPLOAD_DIR_ . uniqid();

            $zip->open($path);

            $valid_entries = [];

            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $zipped_file_name = $zip->getNameIndex($i);
                $zipped_extension = pathinfo($zipped_file_name, PATHINFO_EXTENSION);
                // Template Kit zip files contain a `manifest.json` file, this is not a valid Elementor template so ensure we skip it.
                if ('json' === $zipped_extension && 'manifest.json' !== $zipped_file_name) {
                    $valid_entries[] = $zipped_file_name;
                }
            }

            if ($valid_entries) {
                $zip->extractTo($temp_path, $valid_entries);
            }

            $zip->close();

            $file_names = $this->findTempFiles($temp_path);

            foreach ($file_names as $full_file_name) {
                $import_result = $this->importSingleTemplate($full_file_name);

                \Tools::deleteFile($full_file_name);

                if (is_wp_error($import_result)) {
                    return $import_result;
                }

                $items[] = $import_result;
            }

            rmdir($temp_path);
        } else {
            $import_result = $this->importSingleTemplate($path);

            if (is_wp_error($import_result)) {
                return $import_result;
            }

            $items[] = $import_result;
        }

        return $items;
    }

    // public function postRowActions($actions, WPPost $post)

    // public function adminImportTemplateForm();

    // public function blockTemplateFrontend()

    /**
     * Is template library supports export.
     *
     * whether the template library supports export.
     *
     * Template saved by the user locally on his site, support export by default
     * but this can be changed using a filter.
     *
     * @since 1.0.0
     *
     * @param int $template_id The template ID
     *
     * @return bool Whether the template library supports export
     */
    public function isTemplateSupportsExport($template_id)
    {
        $export_support = true;

        /*
         * Is template library supports export.
         *
         * Filters whether the template library supports export.
         *
         * @since 1.0.0
         *
         * @param bool $export_support Whether the template library supports export
         *                             Default is true.
         * @param int  $template_id    Post ID
         */
        $export_support = apply_filters('elementor/template_library/is_template_supports_export', $export_support, $template_id);

        return $export_support;
    }

    // public function removeElementorPostStateFromLibrary($post_states, $post)

    /**
     * Get template export link.
     *
     * Retrieve the link used to export a single template based on the template
     * ID.
     *
     * @since 2.0.0
     *
     * @param int $template_id The template ID
     *
     * @return string Template export URL
     */
    private function getExportLink($template_id)
    {
        return \Context::getContext()->link->getAdminLink('AdminCEEditor', true, [], [
            'ajax' => 1,
            'action' => 'elementor_library_direct_actions',
            'library_action' => 'export_template',
            'source' => $this->getId(),
            'template_id' => "$template_id",
        ]);
    }

    // public function onSavePost($post_id, WPPost $post)

    // private function saveItemType($post_id, $type)

    // public function adminAddBulkExportAction($actions)

    // public function adminExportMultipleTemplates($redirect_to, $action, $post_ids)

    // public function adminPrintTabs($views)

    // public function maybeRenderBlankState($which);

    // public function addFilterByCategory($post_type)

    /**
     * Import single template.
     *
     * Import template from a file to the database.
     *
     * @since 1.6.0
     *
     * @param string $file_name File name
     *
     * @return WPError|int|array Local template array, or template ID, or `WPError`
     */
    private function importSingleTemplate($file_name)
    {
        $data = json_decode(@call_user_func('file_get_contents', $file_name), true);

        if (!$data) {
            return new WPError('file_error', 'Invalid Content In File');
        }

        if (isset($data['content'])) {
            $content = $data['content'];
        } elseif (isset($data['data'])) {
            if (is_string($data['data'])) {
                // iqit compatibility
                $data['type'] = 'page';
                $content = json_decode($data['data'], true);
            } else {
                // retro compatibility
                $content = $data['data'];
            }
        }

        if (!isset($content) || !is_array($content)) {
            return new WPError('file_error', 'Invalid File');
        }

        $content = $this->processExportImportContent($content, 'onImport');

        $page_settings = [];

        if (!empty($data['page_settings'])) {
            // $page = new Model([
            //     'id' => 0,
            //     'settings' => $data['page_settings'],
            // ]);

            // $page_settings_data = $this->processElementExportImportContent($page, 'onImport');

            // if (!empty($page_settings_data['settings'])) {
            //     $page_settings = $page_settings_data['settings'];
            // }
            $page_settings = $data['page_settings'];
        }

        $template_id = $this->saveItem([
            'content' => $content,
            'title' => $data['title'],
            'type' => $data['type'],
            'page_settings' => $page_settings,
        ]);

        if (is_wp_error($template_id)) {
            return $template_id;
        }

        return $this->getItem($template_id);
    }

    /**
     * Prepare template to export.
     *
     * Retrieve the relevant template data and return them as an array.
     *
     * @since 1.6.0
     *
     * @param int $template_id The template ID
     *
     * @return WPError|array Exported template data
     */
    private function prepareTemplateExport($template_id)
    {
        $template_data = $this->getData([
            'template_id' => $template_id,
        ]);

        // if (empty($template_data['content'])) {
        //     return new WPError('empty_template', 'The template is empty');
        // }

        $template_data['content'] = $this->processExportImportContent($template_data['content'], 'onExport');

        if (get_post_meta($template_id, '_elementor_page_settings', true)) {
            $page = SettingsManager::getSettingsManagers('page')->getModel($template_id);

            $page_settings_data = $this->processElementExportImportContent($page, 'onExport');

            if (!empty($page_settings_data['settings'])) {
                $template_data['page_settings'] = $page_settings_data['settings'];
            }
        }

        $post = get_post($template_id);
        $export_data = [
            'version' => DB::DB_VERSION,
            'title' => $post->post_title,
            'type' => $post->template_type,
        ];

        $export_data += $template_data;

        return [
            'name' => 'CreativeElements_' . $post->uid->id . '_' . date('Y-m-d') . '.json',
            'content' => json_encode($export_data),
        ];
    }

    /**
     * Send file headers.
     *
     * Set the file header when export template data to a file.
     *
     * @since 1.6.0
     *
     * @param string $file_name File name
     * @param int $file_size File size
     */
    private function sendFileHeaders($file_name, $file_size)
    {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
    }

    /**
     * Get template label by type.
     *
     * Retrieve the template label for any given template type.
     *
     * @since 2.0.0
     *
     * @param string $template_type Template type
     *
     * @return string Template label
     */
    private function getTemplateLabelByType($template_type)
    {
        $document_types = Plugin::instance()->documents->getDocumentTypes();

        if (isset($document_types[$template_type])) {
            $template_label = call_user_func([$document_types[$template_type], 'getTitle']);
        } else {
            $template_label = ucwords(str_replace(['_', '-'], ' ', $template_type));
        }

        /*
         * Template label by template type.
         *
         * Filters the template label by template type in the template library .
         *
         * @since 2.0.0
         *
         * @param string $template_label Template label
         * @param string $template_type  Template type
         */
        $template_label = apply_filters('elementor/template-library/get_template_label_by_type', $template_label, $template_type);

        return $template_label;
    }

    // public function adminQueryFilterTypes(\WPQuery $query)

    // private function addActions()

    // public function adminColumnsContent($column_name, $post_id)

    // public function adminColumnsHeaders($posts_columns)

    // private function getCurrentTabGroup($default = '')

    // private function getLibraryTitle()

    // private function isCurrentScreen()

    /**
     * Template library local source constructor.
     *
     * Initializing the template library local source base by registering custom
     * template data and running custom actions.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        // $this->addActions();
    }
}
