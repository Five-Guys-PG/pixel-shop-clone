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

/**
 * Elementor base tag.
 *
 * An abstract class to register new Elementor tags.
 *
 * @since 2.0.0
 * @abstract
 */
abstract class CoreXDynamicTagsXBaseTag extends ControlsStack
{
    const REMOTE_RENDER = false;

    /**
     * @since 2.0.0
     * @static
     */
    final public static function getType()
    {
        return 'tag';
    }

    /**
     * @since 2.0.0
     * @abstract
     */
    abstract public function getCategories();

    /**
     * @since 2.0.0
     * @abstract
     */
    abstract public function getGroup();

    /**
     * @since 2.0.0
     * @abstract
     */
    abstract public function getTitle();

    /**
     * @since 2.0.0
     * @abstract
     *
     * @param array $options
     */
    abstract public function getContent(array $options = []);

    /**
     * @since 2.0.0
     * @abstract
     */
    abstract public function getContentType();

    /**
     * @since 2.0.0
     */
    public function getPanelTemplateSettingKey()
    {
        return '';
    }

    /**
     * @since 2.0.0
     */
    public function isSettingsRequired()
    {
        return true;
    }

    /**
     * @since 2.0.9
     */
    public function getEditorConfig()
    {
        ob_start();

        $this->printPanelTemplate();

        $panel_template = ob_get_clean();

        return [
            'name' => $this->getName(),
            'title' => $this->getTitle(),
            'panel_template' => &$panel_template,
            'categories' => $this->getCategories(),
            'group' => $this->getGroup(),
            'controls' => $this->getControls(),
            'content_type' => $this->getContentType(),
            'settings_required' => $this->isSettingsRequired(),
            'editable' => $this->isEditable(),
        ];
    }

    /**
     * @since 2.0.0
     */
    public function printPanelTemplate()
    {
        $panel_template_setting_key = $this->getPanelTemplateSettingKey();

        if (!$panel_template_setting_key) {
            return;
        } ?>
        <#
        var key = <?php echo esc_html($panel_template_setting_key); ?>,
            settingsKey = "<?php echo esc_html($panel_template_setting_key); ?>";
        /*
         * If the tag has controls,
         * and key is an existing control (and not an old one),
         * and the control has options (select/select2/choose),
         * and the key is an existing option (and not in a group or an old one).
         */
        if (controls && controls[settingsKey]) {
            var controlSettings = controls[settingsKey];
            if (controlSettings.options && controlSettings.options[key]) {
                var label = controlSettings.options[key];
                key = label.title || label;
            } else if (controlSettings.groups) {
                var label = controlSettings.groups[key];
                if ('string' === typeof label) {
                    key = label;
                } else {
                    label = _.filter(_.pluck(_.pluck(controlSettings.groups, 'options'), key));
                    if (label[0]) {
                        key = label[0];
                    }
                }
            }
        }
        key && print('(' + key.replace(/\(|\)/g, '') + ')'); // CE fix
        #>
        <?php
    }

    /**
     * @since 2.0.0
     */
    final public function getUniqueName()
    {
        return 'tag-' . $this->getName();
    }

    /**
     * @since 2.0.0
     */
    protected function registerAdvancedSection()
    {
    }

    /**
     * @since 2.0.0
     */
    final protected function initControls()
    {
        Plugin::$instance->controls_manager->openStack($this);

        $this->startControlsSection('settings', [
            'label' => __('Settings'),
        ]);

        $this->_registerControls();

        $this->endControlsSection();

        // If in fact no controls were registered, empty the stack
        if (1 === count(Plugin::$instance->controls_manager->getStacks($this->getUniqueName())['controls'])) {
            Plugin::$instance->controls_manager->openStack($this);
        }

        $this->registerAdvancedSection();
    }
}
