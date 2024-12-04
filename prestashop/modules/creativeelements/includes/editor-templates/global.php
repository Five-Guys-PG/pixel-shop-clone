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
<script type="text/template" id="tmpl-elementor-empty-preview">
    <div class="elementor-first-add">
        <div class="elementor-icon eicon-plus"></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-preview">
    <div class="elementor-section-wrap"></div>
</script>

<script type="text/template" id="tmpl-elementor-add-section">
    <div class="elementor-add-section-inner">
        <div class="elementor-add-section-close">
            <i class="eicon-close" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e('Close'); ?></span>
        </div>
        <div class="elementor-add-new-section">
            <div class="elementor-add-section-area-button elementor-add-section-button"
                title="<?php _e('Add New Section'); ?>">
                <i class="eicon-plus"></i>
            </div>
            <div class="elementor-add-section-area-button elementor-add-template-button"
                title="<?php _e('Add Template'); ?>">
                <i class="eicon-folder"></i>
            </div>
            <div class="elementor-add-section-drag-title"><?php _e('Drag widget here'); ?></div>
        </div>
        <div class="elementor-select-preset">
            <div class="elementor-select-preset-title"><?php _e('Select your Structure'); ?></div>
            <ul class="elementor-select-preset-list">
            <#
            var structures = [ 10, 20, 30, 40, 21, 22, 31, 32, 33, 50, 60, 34 ];

            _.each( structures, function( structure ) {
                var preset = elementor.presetsFactory.getPresetByStructure( structure ); #>

                <li class="elementor-preset elementor-column elementor-col-16" data-structure="{{ structure }}">
                    {{{ elementor.presetsFactory.getPresetSVG( preset.preset ).outerHTML }}}
                </li>
                <#
            } ); #>
            </ul>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-tag-controls-stack-empty">
    <?php _e('This tag has no settings.'); ?>
</script>
