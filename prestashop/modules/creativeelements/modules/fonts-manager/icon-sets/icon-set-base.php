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

abstract class ModulesXFontsManagerXIconSetsXIconSetBase
{
    protected $dir_name = '';
    protected $directory = '';
    protected $data_file = '';
    protected $stylesheet_file = '';
    protected $allowed_zipped_files = [];
    protected $files_to_save = [];

    /**
     * Webfont extensions.
     *
     * @var array
     */
    protected $allowed_webfont_extensions = ['woff2', 'woff', 'ttf', 'svg', 'otf', 'eot'];

    abstract protected function extractIconList();

    abstract protected function prepare();

    abstract protected function getType();

    abstract public function getName();

    private function isPathDir($path)
    {
        return '/' === substr($path, -1);
    }

    private function isFileAllowed($path_name)
    {
        $check = $this->directory . $path_name;
        if (!file_exists($check)) {
            return false;
        }
        if ($this->isPathDir($path_name)) {
            return is_dir($check);
        }

        return true;
    }

    /**
     * is icon set
     *
     * validate that the current uploaded zip is in this icon set format
     *
     * @return bool
     */
    public function isIconSet()
    {
        foreach ($this->allowed_zipped_files as $file) {
            if (!$this->isFileAllowed($file)) {
                return false;
            }
        }

        return true;
    }

    public function isValid()
    {
        return false;
    }

    protected function getDisplayPrefix()
    {
        return '';
    }

    protected function getPrefix()
    {
        return '';
    }

    public function handleNewIconSet()
    {
        return $this->prepare();
    }

    /**
     * cleanup_temp_files
     */
    protected function cleanupTempFiles()
    {
        \Tools::deleteDirectory($this->directory);
    }

    /**
     * Gets the URL to uploaded file.
     *
     * @param $file_name
     *
     * @return string
     */
    protected function getFileUrl($file_name)
    {
        return 'modules/creativeelements/views/lib/custom-icons/' . $file_name;
    }

    protected function getIconSetsDir()
    {
        return _CE_PATH_ . 'views/lib/custom-icons';
    }

    protected function getEnsureUploadDir($dir = '')
    {
        $path = $this->getIconSetsDir() . ($dir ? "/$dir" : '');

        if (file_exists($path . '/index.php')) {
            return $path;
        }
        $files = [
            '.htaccess' => [
                'Options -Indexes',
                '<ifModule mod_headers.c>',
                '   <Files *.*>',
                '       Header set Content-Disposition attachment',
                '   </Files>',
                '</IfModule>',
            ],
            'index.php' => [
                '<?php',
                "header('Expires: Thu, 28 Feb 2019 00:00:00 GMT');",
                "header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');",
                "header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');",
                "header('Cache-Control: post-check=0, pre-check=0', false);",
                "header('Pragma: no-cache');",
                "header('Location: ../');",
                'die;',
            ],
        ];
        mkdir($path, 0755, true);

        foreach ($files as $file => $content) {
            if (!file_exists("$path/$file")) {
                @call_user_func('file_put_contents', "$path/$file", implode(PHP_EOL, $content));
            }
        }

        return $path;
    }

    public function moveFiles()
    {
        $unique_name = $this->getUniqueName();
        $to = $this->getEnsureUploadDir($unique_name) . '/';

        foreach ($this->allowed_zipped_files as $file) {
            $full_path = $this->directory . $file;

            if (is_dir($full_path)) {
                mkdir($to . $file, 0755);

                foreach (scandir($full_path) as $sub_file) {
                    $ext = strtolower(pathinfo($sub_file, PATHINFO_EXTENSION));

                    if ('css' === $ext || in_array($ext, $this->allowed_webfont_extensions)) {
                        rename($full_path . $sub_file, $to . $file . $sub_file);
                    }
                }
            } else {
                rename($full_path, $to . $file);
            }
        }
        $this->cleanupTempFiles();

        $this->dir_name = $unique_name;
        $this->directory = $to;
    }

    public function getUniqueName()
    {
        $name = $this->getName();
        $basename = $name;
        $counter = 0;

        while (!$this->isNameUnique($name)) {
            $name = $basename . '-' . ++$counter;
        }

        return $name;
    }

    private function isNameUnique($name)
    {
        return !is_dir($this->getIconSetsDir() . '/' . $name);
    }

    protected function getUrl($filename = '')
    {
        return $this->getFileUrl($this->dir_name . $filename);
    }

    protected function getStylesheet()
    {
        $name = $this->dir_name;

        if (!$name) {
            return false; //  missing name
        }

        return $this->getUrl('/' . $this->stylesheet_file);
    }

    protected function getVersion()
    {
        return '1.0.0';
    }

    protected function getEnqueue()
    {
        return false;
    }

    public function buildConfig()
    {
        $icon_set_config = [
            'name' => $this->dir_name,
            'label' => \Tools::getValue('name') ?: ucwords(str_replace(['-', '_'], ' ', $this->dir_name)),
            'url' => $this->getStylesheet(),
            'enqueue' => $this->getEnqueue(),
            'prefix' => $this->getPrefix(),
            'displayPrefix' => $this->getDisplayPrefix(),
            'labelIcon' => 'eicon eicon-folder',
            'ver' => $this->getVersion(),
            'custom_icon_type' => $this->getType(),
        ];
        $icons = $this->extractIconList();
        $icon_set_config['count'] = count($icons);
        $icon_set_config['icons'] = $icons;

        if (25 < $icon_set_config['count']) {
            $icon_set_config['fetchJson'] = $this->storeIconListJson($icons);
        }

        return $icon_set_config;
    }

    private function storeIconListJson($icons)
    {
        @call_user_func('file_put_contents', "{$this->getIconSetsDir()}/{$this->dir_name}/e_icons.js", json_encode(['icons' => $icons]));

        return $this->getUrl() . '/e_icons.js';
    }

    /**
     * Icon Set Base constructor.
     *
     * @param $directory
     */
    public function __construct($directory)
    {
        $this->directory = $directory;

        return $this->isIconSet() ? $this : false;
    }
}
