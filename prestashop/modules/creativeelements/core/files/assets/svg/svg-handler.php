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

use CE\CoreXFilesXAssetsXFilesUploadHandler as FilesUploadHandler;

class CoreXFilesXAssetsXSvgXSvgHandler extends FilesUploadHandler
{
    // const META_KEY = '_elementor_inline_svg';

    const SCRIPT_REGEX = '/(?:\w+script|data):/xi';

    /**
     * @var \DOMDocument
     */
    private $svg_dom;

    // private $attachment_id;

    public static function getName()
    {
        return 'svg-handler';
    }

    // protected function getMeta()

    // protected function updateMeta($meta)

    // protected function deleteMeta()

    public function getMimeType()
    {
        return 'image/svg+xml';
    }

    public function getFileType()
    {
        return 'svg';
    }

    // public function deleteMetaCache()

    // public function readFromFile()

    /**
     * get_inline_svg
     *
     * @param $url
     *
     * @return bool|mixed|string
     */
    public static function getInlineSvg($url)
    {
        static $instance;

        $path = urldecode($url);

        if (strpos($path, 'img/cms/') !== 0 || strcasecmp(pathinfo($path, PATHINFO_EXTENSION), 'svg')) {
            return '';
        }
        $tmp = _PS_ROOT_DIR_ . '/img/tmp' . substr($path, 3);

        if (realpath($tmp) === $tmp) {
            return @call_user_func('file_get_contents', $tmp);
        }
        $svg = _PS_ROOT_DIR_ . "/$path";

        if (realpath($svg) !== $svg) {
            return '';
        }
        $content = @call_user_func('file_get_contents', $svg);

        if (null === $instance) {
            $instance = new self();
        }
        @call_user_func('file_put_contents', $tmp, $content = $instance->sanitizer($content));

        return $content;
    }

    /**
     * decode_svg
     *
     * @param $content
     *
     * @return string
     */
    private function decodeSvg($content)
    {
        return gzdecode($content);
    }

    /**
     * encode_svg
     *
     * @param $content
     *
     * @return string
     */
    private function encodeSvg($content)
    {
        return gzencode($content);
    }

    /**
     * sanitize_svg
     *
     * @param $filename
     *
     * @return bool
     */
    public function sanitizeSvg($filename)
    {
        if (realpath($filename) !== $filename) {
            return false;
        }

        $original_content = @call_user_func('file_get_contents', $filename);
        $is_encoded = $this->isEncoded($original_content);

        if ($is_encoded) {
            $decoded = $this->decodeSvg($original_content);
            if (false === $decoded) {
                return false;
            }
            $original_content = $decoded;
        }

        $valid_svg = $this->sanitizer($original_content);

        if (false === $valid_svg) {
            return false;
        }

        // If we were gzipped, we need to re-zip
        // if ($is_encoded) {
        //     $valid_svg = $this->encodeSvg($valid_svg);
        // }
        @call_user_func('file_put_contents', $filename, $valid_svg);

        return true;
    }

    /**
     * Check if the contents are gzipped
     *
     * @see http://www.gzip.org/zlib/rfc-gzip.html#member-format
     *
     * @param $contents
     *
     * @return bool
     */
    private function isEncoded($contents)
    {
        return 0 === \Tools::strpos($contents, "\x1f\x8b\x08");
    }

    /**
     * is_allowed_tag
     *
     * @param $element
     *
     * @return bool
     */
    private function isAllowedTag($element)
    {
        static $allowed_tags = false;
        if (false === $allowed_tags) {
            $allowed_tags = $this->getAllowedElements();
        }

        $tag_name = $element->tagName;

        if (!in_array(strtolower($tag_name), $allowed_tags)) {
            $this->removeElement($element);

            return false;
        }

        return true;
    }

    private function removeElement($element)
    {
        $element->parentNode->removeChild($element);
    }

    /**
     * is_a_attribute
     *
     * @param $name
     * @param $check
     *
     * @return bool
     */
    private function isAAttribute($name, $check)
    {
        return 0 === strpos($name, $check . '-');
    }

    /**
     * is_remote_value
     *
     * @param $value
     *
     * @return string
     */
    private function isRemoteValue($value)
    {
        $value = trim(preg_replace('/[^ -~]/xu', '', $value));
        $wrapped_in_url = preg_match('/^url\(\s*[\'"]\s*(.*)\s*[\'"]\s*\)$/xi', $value, $match);
        if (!$wrapped_in_url) {
            return false;
        }

        $value = trim($match[1], '\'"');

        return preg_match('`^(https?:|ftp:|file:)?//`xi', $value);
    }

    /**
     * has_js_value
     *
     * @param $value
     *
     * @return false|int
     */
    private function hasJsValue($value)
    {
        return preg_match('/base64|data|(?:java)?script|alert\(|window\.|document/i', $value);
    }

    /**
     * get_allowed_attributes
     *
     * @return array
     */
    private function getAllowedAttributes()
    {
        return [
            'class',
            'clip-path',
            'clip-rule',
            'fill',
            'fill-opacity',
            'fill-rule',
            'filter',
            'id',
            'mask',
            'opacity',
            'stroke',
            'stroke-dasharray',
            'stroke-dashoffset',
            'stroke-linecap',
            'stroke-linejoin',
            'stroke-miterlimit',
            'stroke-opacity',
            'stroke-width',
            'style',
            'systemlanguage',
            'transform',
            'href',
            'xlink:href',
            'xlink:title',
            'cx',
            'cy',
            'r',
            'requiredfeatures',
            'clippathunits',
            'type',
            'rx',
            'ry',
            'color-interpolation-filters',
            'stddeviation',
            'filterres',
            'filterunits',
            'height',
            'primitiveunits',
            'width',
            'x',
            'y',
            'font-size',
            'display',
            'font-family',
            'font-style',
            'font-weight',
            'text-anchor',
            'marker-end',
            'marker-mid',
            'marker-start',
            'x1',
            'x2',
            'y1',
            'y2',
            'gradienttransform',
            'gradientunits',
            'spreadmethod',
            'markerheight',
            'markerunits',
            'markerwidth',
            'orient',
            'preserveaspectratio',
            'refx',
            'refy',
            'viewbox',
            'maskcontentunits',
            'maskunits',
            'd',
            'patterncontentunits',
            'patterntransform',
            'patternunits',
            'points',
            'fx',
            'fy',
            'offset',
            'stop-color',
            'stop-opacity',
            'xmlns',
            'xmlns:se',
            'xmlns:xlink',
            'xml:space',
            'method',
            'spacing',
            'startoffset',
            'dx',
            'dy',
            'rotate',
            'textlength',
        ];
        // return apply_filters('elementor/files/svg/allowed_attributes', $allowed_attributes);
    }

    /**
     * get_allowed_elements
     *
     * @return array
     */
    private function getAllowedElements()
    {
        return [
            'a',
            'circle',
            'clippath',
            'defs',
            'style',
            'desc',
            'ellipse',
            'fegaussianblur',
            'filter',
            'foreignobject',
            'g',
            'image',
            'line',
            'lineargradient',
            'marker',
            'mask',
            'metadata',
            'path',
            'pattern',
            'polygon',
            'polyline',
            'radialgradient',
            'rect',
            'stop',
            'svg',
            'switch',
            'symbol',
            'text',
            'textpath',
            'title',
            'tspan',
            'use',
        ];
        // return apply_filters('elementor/files/svg/allowed_elements', $allowed_elements);
    }

    /**
     * validate_allowed_attributes
     *
     * @param \DOMElement $element
     */
    private function validateAllowedAttributes($element)
    {
        static $allowed_attributes = false;
        if (false === $allowed_attributes) {
            $allowed_attributes = $this->getAllowedAttributes();
        }

        for ($index = $element->attributes->length - 1; $index >= 0; --$index) {
            // get attribute name
            $attr_name = $element->attributes->item($index)->name;
            $attr_name_lowercase = strtolower($attr_name);
            // Remove attribute if not in whitelist
            if (!in_array($attr_name_lowercase, $allowed_attributes) && !$this->isAAttribute($attr_name_lowercase, 'aria') && !$this->isAAttribute($attr_name_lowercase, 'data')) {
                $element->removeAttribute($attr_name);
                continue;
            }

            $attr_value = $element->attributes->item($index)->value;

            // Remove attribute if it has a remote reference or js or data-URI/base64
            if (!empty($attr_value) && ($this->isRemoteValue($attr_value) || $this->hasJsValue($attr_value))) {
                $element->removeAttribute($attr_name);
                continue;
            }
        }
    }

    /**
     * strip_xlinks
     *
     * @param \DOMElement $element
     */
    private function stripXlinks($element)
    {
        $xlinks = $element->getAttributeNS('http://www.w3.org/1999/xlink', 'href');

        if (!$xlinks) {
            return;
        }

        $allowed_links = [
            'data:image/png', // PNG
            'data:image/gif', // GIF
            'data:image/jpg', // JPG
            'data:image/jpe', // JPEG
            'data:image/pjp', // PJPEG
        ];
        if (1 === preg_match(self::SCRIPT_REGEX, $xlinks)) {
            if (!in_array(substr($xlinks, 0, 14), $allowed_links)) {
                $element->removeAttributeNS('http://www.w3.org/1999/xlink', 'href');
            }
        }
    }

    /**
     * validate_use_tag
     *
     * @param $element
     */
    private function validateUseTag($element)
    {
        $xlinks = $element->getAttributeNS('http://www.w3.org/1999/xlink', 'href');
        if ($xlinks && '#' !== substr($xlinks, 0, 1)) {
            $element->parentNode->removeChild($element); // phpcs:ignore -- php DomNode
        }
    }

    /**
     * strip_docktype
     */
    private function stripDoctype()
    {
        foreach ($this->svg_dom->childNodes as $child) {
            if (XML_DOCUMENT_TYPE_NODE === $child->nodeType) { // phpcs:ignore -- php DomDocument
                $child->parentNode->removeChild($child); // phpcs:ignore -- php DomDocument
            }
        }
    }

    /**
     * sanitize_elements
     */
    private function sanitizeElements()
    {
        $elements = $this->svg_dom->getElementsByTagName('*');
        // loop through all elements
        // we do this backwards so we don't skip anything if we delete a node
        // see comments at: http://php.net/manual/en/class.domnamednodemap.php
        for ($index = $elements->length - 1; $index >= 0; --$index) {
            /*
             * @var \DOMElement $current_element
             */
            $current_element = $elements->item($index);
            // If the tag isn't in the whitelist, remove it and continue with next iteration
            if (!$this->isAllowedTag($current_element)) {
                continue;
            }

            // validate element attributes
            $this->validateAllowedAttributes($current_element);

            $this->stripXlinks($current_element);

            if ('use' === strtolower($current_element->tagName)) {
                // phpcs:ignore -- php DomDocument
                $this->validateUseTag($current_element);
            }
        }
    }

    /**
     * sanitizer
     *
     * @param $content
     *
     * @return bool|string
     */
    public function sanitizer($content)
    {
        // Strip php tags
        $content = $this->stripComments($content);
        $content = $this->stripPhpTags($content);

        // Find the start and end tags so we can cut out miscellaneous garbage.
        $start = strpos($content, '<svg');
        $end = strrpos($content, '</svg>');
        if (false === $start || false === $end) {
            return false;
        }

        $content = substr($content, $start, $end - $start + 6);

        // Make sure to Disable the ability to load external entities
        (int) _PS_VERSION_ < 8 && $libxml_disable_entity_loader = call_user_func('libxml_disable_entity_loader', true);
        // Suppress the errors
        $libxml_use_internal_errors = libxml_use_internal_errors(true);

        // Create DomDocument instance
        $this->svg_dom = new \DOMDocument();
        $this->svg_dom->formatOutput = false;
        $this->svg_dom->preserveWhiteSpace = false;
        $this->svg_dom->strictErrorChecking = false;

        $open_svg = $this->svg_dom->loadXML($content);
        if (!$open_svg) {
            return false;
        }

        $this->stripDoctype();
        $this->sanitizeElements();

        // Export sanitized svg to string
        // Useing documentElement to strip out <?xml version="1.0" encoding="UTF-8"...
        $sanitized = $this->svg_dom->saveXML($this->svg_dom->documentElement, LIBXML_NOEMPTYTAG);

        // Restore defaults
        isset($libxml_disable_entity_loader) && call_user_func('libxml_disable_entity_loader', $libxml_disable_entity_loader);
        libxml_use_internal_errors($libxml_use_internal_errors);

        return $sanitized;
    }

    /**
     * strip_php_tags
     *
     * @param $string
     *
     * @return string
     */
    private function stripPhpTags($string)
    {
        $string = preg_replace('/<\?(=|php)(.+?)\?>/i', '', $string);
        // Remove XML, ASP, etc.
        $string = preg_replace('/<\?(.*)\?>/Us', '', $string);
        $string = preg_replace('/<\%(.*)\%>/Us', '', $string);

        if (false !== strpos($string, '<?') || false !== strpos($string, '<%')) {
            return '';
        }

        return $string;
    }

    /**
     * strip_comments
     *
     * @param $string
     *
     * @return string
     */
    private function stripComments($string)
    {
        // Remove comments.
        $string = preg_replace('/<!--(.*)-->/Us', '', $string);
        $string = preg_replace('`/\*(.*)\*/`Us', '', $string);
        if (false !== strpos($string, '<!--') || false !== strpos($string, '/*')) {
            return '';
        }

        return $string;
    }

    // public function wpPrepareAttachmentForJs($attachment_data, $attachment, $meta)

    // public function setAttachmentId($attachment_id)

    // public function getAttachmentId()

    // public function handleUploadPrefilter($file)

    // public function __construct()
}
