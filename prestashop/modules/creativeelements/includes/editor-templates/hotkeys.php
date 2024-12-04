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

?>
<script type="text/template" id="tmpl-elementor-hotkeys">
    <# var ctrlLabel = environment.mac ? 'Cmd' : 'Ctrl'; #>
    <div id="elementor-hotkeys__content">
        <div id="elementor-hotkeys__actions" class="elementor-hotkeys__col">

            <div class="elementor-hotkeys__header">
                <h3><?php _e('Actions'); ?></h3>
            </div>
            <div class="elementor-hotkeys__list">
                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Undo'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Z</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Redo'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>Z</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Copy'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>C</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Paste'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>V</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Paste Style'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>V</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Delete'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>Delete</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Duplicate'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>D</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Save'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>S</span>
                    </div>
                </div>

            </div>
        </div>

        <div id="elementor-hotkeys__navigation" class="elementor-hotkeys__col">

            <div class="elementor-hotkeys__header">
                <h3><?php _e('Go To'); ?></h3>
            </div>
            <div class="elementor-hotkeys__list">
                <!--div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Finder'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>E</span>
                    </div>
                </div-->

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Show / Hide Panel'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>P</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Responsive Mode'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>M</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('History'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>H</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Navigator'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>I</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Template Library'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>Shift</span>
                        <span>L</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Keyboard Shortcuts'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>{{{ ctrlLabel }}}</span>
                        <span>?</span>
                    </div>
                </div>

                <div class="elementor-hotkeys__item">
                    <div class="elementor-hotkeys__item--label"><?php _e('Quit'); ?></div>
                    <div class="elementor-hotkeys__item--shortcut">
                        <span>Esc</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
