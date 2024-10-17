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
<script type="text/template" id="tmpl-elementor-repeater-row">
    <div class="elementor-repeater-row-tools">
        <# if ( itemActions.drag_n_drop ) {  #>
            <div class="elementor-repeater-row-handle-sortable">
                <i class="eicon-ellipsis-v" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php _e('Drag & Drop'); ?></span>
            </div>
        <# } #>
        <div class="elementor-repeater-row-item-title"></div>
        <# if ( itemActions.duplicate ) {  #>
            <div class="elementor-repeater-row-tool elementor-repeater-tool-duplicate">
                <i class="eicon-copy" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php _e('Duplicate'); ?></span>
            </div>
        <# }
        if ( itemActions.remove ) {  #>
            <div class="elementor-repeater-row-tool elementor-repeater-tool-remove">
                <i class="eicon-close" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php _e('Remove'); ?></span>
            </div>
        <# } #>
    </div>
    <div class="elementor-repeater-row-controls"></div>
</script>
