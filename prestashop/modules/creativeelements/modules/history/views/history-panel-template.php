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
<script type="text/template" id="tmpl-elementor-panel-history-page">
    <div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
        <div class="elementor-component-tab elementor-panel-navigation-tab"
            data-tab="actions"><?php _e('Actions'); ?></div>
        <div class="elementor-component-tab elementor-panel-navigation-tab"
            data-tab="revisions"><?php _e('Revisions'); ?></div>
    </div>
    <div id="elementor-panel-history-content"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-tab">
    <div id="elementor-history-list"></div>
    <div class="elementor-history-revisions-message"><?php _e('Switch to Revisions tab for older versions'); ?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-no-items">
    <img class="elementor-nerd-box-icon" src="<?php echo _CE_ASSETS_URL_; ?>img/information.svg">
    <div class="elementor-nerd-box-title"><?php _e('No History Yet'); ?></div>
    <div class="elementor-nerd-box-message">
        <?php _e('Once you start working, you\'ll be able to redo / undo any action you make in the editor.'); ?>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-item">
    <div class="elementor-history-item__details">
        <span class="elementor-history-item__title">{{{ title }}}</span>
        <span class="elementor-history-item__subtitle">{{{ subTitle }}}</span>
        <span class="elementor-history-item__action">{{{ action }}}</span>
    </div>
    <div class="elementor-history-item__icon">
        <span class="eicon" aria-hidden="true"></span>
    </div>
</script>
