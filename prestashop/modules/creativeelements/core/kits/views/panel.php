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
<script type="text/template" id="tmpl-elementor-kit-panel">
    <main id="elementor-kit__panel-content__wrapper" class="elementor-panel-content-wrapper"></main>
</script>

<script type="text/template" id="tmpl-elementor-kit-panel-content">
    <div id="elementor-kit-panel-content-controls"></div>
    <#
    const tabConfig = $e.components.get( 'panel/global' ).getActiveTabConfig();
    if ( tabConfig.helpUrl ) { #>
    <div id="elementor-panel__editor__help">
        <a id="elementor-panel__editor__help__link" href="{{ tabConfig.helpUrl }}" target="_blank">
            <?php _e('Need Help'); ?>
            <i class="eicon-help-o"></i>
        </a>
    </div>
    <# } #>
</script>
