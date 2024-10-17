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

use CE\CoreXDynamicTagsXBaseTag as BaseTag;
use CE\CoreXDynamicTagsXDynamicCSS as DynamicCSS;
use CE\CoreXDynamicTagsXTag as Tag;
use CE\CoreXFilesXCSSXPost as Post;
use CE\CoreXFilesXCSSXPostPreview as PostPreview;

class CoreXDynamicTagsXManager
{
    // const TAG_LABEL = 'elementor-tag';

    const MODE_RENDER = 'render';

    const MODE_REMOVE = 'remove';

    const DYNAMIC_SETTING_KEY = '__dynamic__';

    private $tags_groups = [];

    private $tags_info = [];

    private $parsing_mode = self::MODE_RENDER;

    /**
     * Dynamic tags manager constructor.
     *
     * Initializing Elementor dynamic tags manager.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        $this->addActions();
    }

    /**
     * Localize settings.
     *
     * Add new localized settings for the dynamic module.
     *
     * Fired by `elementor/editor/localize_settings` filter.
     *
     * @param array $settings Localized settings
     *
     * @return array Localized settings
     */
    public function localizeSettings($settings)
    {
        $settings = array_replace_recursive($settings, [
            'i18n' => [
                'dynamic' => __('Dynamic'),
            ],
        ]);

        return $settings;
    }

    /**
     * Parse dynamic tags text.
     *
     * Receives the dynamic tag text, and returns a single value or multiple values
     * from the tag callback function.
     *
     * @since 2.0.0
     *
     * @param string $text Dynamic tag text
     * @param array $settings The dynamic tag settings
     * @param callable $parse_callback The functions that renders the dynamic tag
     *
     * @return string|string[]|mixed a single string or an array of strings with
     *                               the return values from each tag callback
     *                               function
     */
    public function parseTagsText($text, array $settings, callable $parse_callback)
    {
        if (!empty($settings['returnType']) && 'object' === $settings['returnType']) {
            $value = $this->parseTagText($text, $settings, $parse_callback);
        } else {
            $value = preg_replace_callback('/\[elementor-tag.+?(?=\])\]/', function ($tag_text_match) use ($settings, $parse_callback) {
                return $this->parseTagText($tag_text_match[0], $settings, $parse_callback);
            }, $text);
        }

        return $value;
    }

    /**
     * Parse dynamic tag text.
     *
     * Receives the dynamic tag text, and returns the value from the callback
     * function.
     *
     * @since 2.0.0
     *
     * @param string $tag_text Dynamic tag text
     * @param array $settings The dynamic tag settings
     * @param callable $parse_callback The functions that renders the dynamic tag
     *
     * @return string|array|mixed if the tag was not found an empty string or an
     *                            empty array will be returned, otherwise the
     *                            return value from the tag callback function
     */
    public function parseTagText($tag_text, array $settings, callable $parse_callback)
    {
        $tag_data = $this->tagTextToTagData($tag_text);

        if (!$tag_data) {
            if (!empty($settings['returnType']) && 'object' === $settings['returnType']) {
                return [];
            }

            return '';
        }

        return $parse_callback(...array_values($tag_data));
    }

    /**
     * @since 2.0.0
     *
     * @param string $tag_text
     *
     * @return array|null
     */
    public function tagTextToTagData($tag_text)
    {
        preg_match('/id="(.*?(?="))"/', $tag_text, $tag_id_match);
        preg_match('/name="(.*?(?="))"/', $tag_text, $tag_name_match);
        preg_match('/settings="(.*?(?="]))/', $tag_text, $tag_settings_match);

        if (!$tag_id_match || !$tag_name_match || !$tag_settings_match) {
            return null;
        }

        return [
            'id' => $tag_id_match[1],
            'name' => $tag_name_match[1],
            'settings' => json_decode(urldecode($tag_settings_match[1]), true),
        ];
    }

    /**
     * Dynamic tag to text.
     *
     * Retrieve the shortcode that represents the dynamic tag.
     *
     * @since 2.0.0
     *
     * @param BaseTag $tag An instance of the dynamic tag
     *
     * @return string The shortcode that represents the dynamic tag
     */
    public function tagToText(BaseTag $tag)
    {
        return "[elementor-tag id=\"{$tag->getId()}\" name=\"{$tag->getName()}\" settings=\"" . urlencode(json_encode($tag->getSettings(), JSON_FORCE_OBJECT)) . '"]';
    }

    /**
     * @since 2.0.0
     *
     * @param string $tag_id
     * @param string $tag_name
     * @param array $settings
     *
     * @return string
     */
    public function tagDataToTagText($tag_id, $tag_name, array $settings = [])
    {
        $tag = $this->createTag($tag_id, $tag_name, $settings);

        if (!$tag) {
            return '';
        }

        return $this->tagToText($tag);
    }

    /**
     * @since 2.0.0
     *
     * @param string $tag_id
     * @param string $tag_name
     * @param array $settings
     *
     * @return Tag|null
     */
    public function createTag($tag_id, $tag_name, array $settings = [])
    {
        $tag_info = $this->getTagInfo($tag_name);

        if (!$tag_info) {
            return null;
        }

        $tag_class = $tag_info['class'];

        return new $tag_class([
            'settings' => $settings,
            'id' => $tag_id,
        ]);
    }

    /**
     * @since 2.0.0
     *
     * @param $tag_id
     * @param $tag_name
     * @param array $settings
     *
     * @return string|null
     */
    public function getTagDataContent($tag_id, $tag_name, array $settings = [])
    {
        if (self::MODE_REMOVE === $this->parsing_mode) {
            return null;
        }

        $tag = $this->createTag($tag_id, $tag_name, $settings);

        if (!$tag) {
            return null;
        }

        return $tag->getContent();
    }

    /**
     * @since 2.0.0
     *
     * @param $tag_name
     *
     * @return mixed|null
     */
    public function getTagInfo($tag_name)
    {
        $tags = $this->getTags();

        if (empty($tags[$tag_name])) {
            return null;
        }

        return $tags[$tag_name];
    }

    /**
     * @since 2.0.9
     */
    public function getTags()
    {
        if (!did_action('elementor/dynamic_tags/register_tags')) {
            /*
             * Register dynamic tags.
             *
             * Fires when Elementor registers dynamic tags.
             *
             * @since 2.0.9
             *
             * @param Manager $this Dynamic tags manager
             */
            do_action('elementor/dynamic_tags/register_tags', $this);
        }

        return $this->tags_info;
    }

    /**
     * @since 2.0.0
     *
     * @param string $class
     */
    public function registerTag($class)
    {
        /* @var Tag $tag */
        $tag = new $class();

        $this->tags_info[$tag->getName()] = [
            'class' => $class,
            'instance' => $tag,
        ];
    }

    /**
     * @since 2.0.9
     *
     * @param string $tag_name
     */
    public function unregisterTag($tag_name)
    {
        unset($this->tags_info[$tag_name]);
    }

    /**
     * @since 2.0.0
     *
     * @param $group_name
     * @param array $group_settings
     */
    public function registerGroup($group_name, array $group_settings)
    {
        $default_group_settings = [
            'title' => '',
        ];

        $group_settings = array_merge($default_group_settings, $group_settings);

        $this->tags_groups[$group_name] = $group_settings;
    }

    /**
     * @since 2.0.0
     */
    public function printTemplates()
    {
        foreach ($this->getTags() as $tag_name => $tag_info) {
            $tag = $tag_info['instance'];

            if (!$tag instanceof Tag) {
                continue;
            }

            $tag->printTemplate();
        }
    }

    /**
     * @since 2.0.0
     */
    public function getTagsConfig()
    {
        $config = [];

        foreach ($this->getTags() as $tag_name => $tag_info) {
            /* @var Tag $tag */
            $tag = $tag_info['instance'];

            $config[$tag_name] = $tag->getEditorConfig();
        }

        return $config;
    }

    /**
     * @since 2.0.0
     */
    public function getConfig()
    {
        return [
            'tags' => $this->getTagsConfig(),
            'groups' => $this->tags_groups,
        ];
    }

    /**
     * @since 2.0.0
     *
     * @throws \Exception if post ID is missing
     * @throws \Exception if current user don't have permissions to edit the post
     */
    public function ajaxRenderTags()
    {
        $data = json_decode(${'_POST'}['data'], true);

        if (empty($data['post_id'])) {
            throw new \Exception('Missing post id.');
        }

        if (!User::isCurrentUserCanEdit($data['post_id'])) {
            throw new \Exception('Access denied.');
        }

        $documents = Plugin::$instance->documents;
        $documents->switchToDocument($documents->get($data['post_id']));

        // do_action('elementor/dynamic_tags/before_render');

        $tags_data = [];

        foreach ($data['tags'] as $tag_key) {
            $tag_key_parts = explode('-', $tag_key);

            $tag_name = base64_decode($tag_key_parts[0]);

            $tag_settings = json_decode(urldecode(base64_decode($tag_key_parts[1])), true);

            $tag = $this->createTag(null, $tag_name, $tag_settings);

            $tags_data[$tag_key] = $tag->getContent();
        }

        // do_action('elementor/dynamic_tags/after_render');

        return $tags_data;
    }

    /**
     * @since 2.0.0
     *
     * @param $mode
     */
    public function setParsingMode($mode)
    {
        $this->parsing_mode = $mode;
    }

    /**
     * @since 2.0.0
     */
    public function getParsingMode()
    {
        return $this->parsing_mode;
    }

    /**
     * @since 2.1.0
     *
     * @param Post $css_file
     */
    public function afterEnqueuePostCss($css_file)
    {
        $post_id = $css_file->getPostId();

        if ($css_file instanceof PostPreview) {
            $post_id_for_data = $css_file->getPreviewId();
        } else {
            $post_id_for_data = $post_id;
        }

        $css_file = DynamicCSS::create($post_id, $post_id_for_data);

        $css_file->enqueue();
    }

    // public function registerAjaxActions(Ajax $ajax)

    /**
     * @since 2.0.0
     */
    private function addActions()
    {
        // add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);
        add_action('elementor/css-file/post/enqueue', [$this, 'afterEnqueuePostCss']);
        add_filter('elementor/editor/localize_settings', [$this, 'localizeSettings']);
    }
}
