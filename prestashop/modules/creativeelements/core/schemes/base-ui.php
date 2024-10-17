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

use CE\CoreXSchemesXBase as Base;

abstract class CoreXSchemesXBaseUI extends Base
{
    /**
     * System schemes.
     *
     * Holds the list of all the system schemes.
     *
     * @since 2.8.0
     *
     * @var array System schemes
     */
    private $_system_schemes;

    /**
     * Get scheme title.
     *
     * Retrieve the scheme title.
     *
     * @since 2.8.0
     */
    abstract public function getTitle();

    /**
     * Get scheme disabled title.
     *
     * Retrieve the scheme disabled title.
     *
     * @since 2.8.0
     */
    abstract public function getDisabledTitle();

    /**
     * Get scheme titles.
     *
     * Retrieve the scheme titles.
     *
     * @since 2.8.0
     */
    abstract public function getSchemeTitles();

    /**
     * Print scheme content template.
     *
     * Used to generate the HTML in the editor using Underscore JS template. The
     * variables for the class are available using `data` JS object.
     *
     * @since 2.8.0
     */
    abstract public function printTemplateContent();

    /**
     * Init system schemes.
     *
     * Initialize the system schemes.
     *
     * @since 2.8.0
     * @abstract
     */
    abstract protected function _initSystemSchemes();

    /**
     * Get system schemes.
     *
     * Retrieve the system schemes.
     *
     * @since 1.0.0
     *
     * @return array System schemes
     */
    final public function getSystemSchemes()
    {
        if (null === $this->_system_schemes) {
            $this->_system_schemes = $this->_initSystemSchemes();
        }

        return $this->_system_schemes;
    }

    /**
     * Print scheme template.
     *
     * Used to generate the scheme template on the editor using Underscore JS
     * template.
     *
     * @since 2.8.0
     */
    final public function printTemplate()
    {
        ?>
        <script type="text/template" id="tmpl-elementor-panel-schemes-<?php echo static::getType(); ?>">
            <div class="elementor-panel-scheme-buttons">
                <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-reset">
                    <button class="elementor-button">
                        <i class="eicon-undo" aria-hidden="true"></i>
                        <?php _e('Reset'); ?>
                    </button>
                </div>
                <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-discard">
                    <button class="elementor-button">
                        <i class="eicon-close" aria-hidden="true"></i>
                        <?php _e('Discard'); ?>
                    </button>
                </div>
                <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-save">
                    <button class="elementor-button elementor-button-success" disabled>
                        <?php _e('Apply'); ?>
                    </button>
                </div>
            </div>
            <?php $this->printTemplateContent(); ?>
        </script>
        <?php
    }

    /**
     * Get scheme.
     *
     * Retrieve the scheme.
     *
     * @since 2.8.0
     *
     * @return array The scheme
     */
    public function getScheme()
    {
        $scheme = [];

        $titles = $this->getSchemeTitles();

        foreach ($this->getSchemeValue() as $scheme_key => $scheme_value) {
            $scheme[$scheme_key] = [
                'title' => isset($titles[$scheme_key]) ? $titles[$scheme_key] : '',
                'value' => $scheme_value,
            ];
        }

        return $scheme;
    }
}
