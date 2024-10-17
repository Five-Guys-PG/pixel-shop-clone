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
<script type="text/template" id="tmpl-elementor-panel-elements">
    <div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
        <div class="elementor-component-tab elementor-panel-navigation-tab"
            data-tab="categories"><?php _e('Elements'); ?></div>
        <div class="elementor-component-tab elementor-panel-navigation-tab" data-tab="global"><?php _e('Global'); ?></div>
    </div>
    <div id="elementor-panel-elements-search-area"></div>
    <div id="elementor-panel-elements-wrapper"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-categories">
    <div id="elementor-panel-categories"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-elements-category">
    <div class="elementor-panel-category-title">{{{ title }}}</div>
    <div class="elementor-panel-category-items"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-element-search">
    <label for="elementor-panel-elements-search-input" class="screen-reader-text"><?php _e('Search Widget:'); ?></label>
    <input type="search" id="elementor-panel-elements-search-input"
        placeholder="<?php esc_attr_e('Search Widget...'); ?>" autocomplete="off">
    <i class="eicon-search-bold" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-elementor-element-library-element">
    <div class="elementor-element">
    <# if ( false === obj.editable ) { #>
        <i class="eicon-lock"></i>
    <# } #>
        <div class="icon">
            <i class="{{ icon }}" aria-hidden="true"></i>
        </div>
        <div class="elementor-element-title-wrapper">
            <div class="title">{{{ title }}}</div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-global"></script>
