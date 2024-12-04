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

use CE\CoreXResponsiveXResponsive as Responsive;

$document = Plugin::$instance->documents->get(Plugin::$instance->editor->getPostId());
?>
<script type="text/template" id="tmpl-elementor-panel">
    <div id="elementor-mode-switcher"></div>
    <div id="elementor-panel-state-loading">
        <i class="eicon-loading eicon-animation-spin"></i>
    </div>
    <header id="elementor-panel-header-wrapper"></header>
    <main id="elementor-panel-content-wrapper"></main>
    <footer id="elementor-panel-footer">
        <div class="elementor-panel-container">
        </div>
    </footer>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu">
    <div id="elementor-panel-page-menu-content"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu-group">
    <div class="elementor-panel-menu-group-title">{{{ title }}}</div>
    <div class="elementor-panel-menu-items"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu-item">
    <div class="elementor-panel-menu-item-icon">
        <i class="{{ icon }}"></i>
    </div>
    <# if ( 'undefined' === typeof type || 'link' !== type ) { #>
        <div class="elementor-panel-menu-item-title">{{{ title }}}</div>
    <# } else {
        var target = ( 'undefined' !== typeof newTab && newTab ) ? '_blank' : '_self'; #>
        <a href="{{ link }}" target="{{ target }}"><div class="elementor-panel-menu-item-title">{{{ title }}}</div></a>
    <# } #>
</script>

<script type="text/template" id="tmpl-elementor-panel-header">
    <div id="elementor-panel-header-menu-button" class="elementor-header-button">
        <i class="elementor-icon eicon-menu-bar tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e('Menu'); ?>"></i>
        <span class="elementor-screen-only"><?php _e('Menu'); ?></span>
    </div>
    <div id="elementor-panel-header-title"></div>
    <div id="elementor-panel-header-add-button" class="elementor-header-button">
        <i class="elementor-icon eicon-apps tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e('Widgets Panel'); ?>"></i>
        <span class="elementor-screen-only"><?php _e('Widgets Panel'); ?></span>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-footer-content">
    <div id="elementor-panel-footer-settings" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?php esc_attr_e('Settings'); ?>">
        <i class="eicon-cog" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?php printf(__('%s Settings'), $document::getTitle()); ?></span>
    </div>
<?php if ((count($langs = \Language::getLanguages(true, false)) > 1 || \Shop::isFeatureActive()) && ($uid = get_the_ID()) && $uid->id_type !== UId::TEMPLATE) { ?>
    <div id="elementor-panel-footer-lang" class="elementor-panel-footer-tool elementor-toggle-state">
        <i class="ceicon-flag tooltip-target" data-tooltip="<?php esc_attr_e('Language'); ?>" aria-hidden="true"></i>
        <span class="elementor-screen-only">
            <?php _e('Language'); ?>
        </span>
        <div class="elementor-panel-footer-sub-menu-wrapper">
        <?php if (\Shop::isFeatureActive() && count($shops = \Shop::getShops()) > 1) {
            $active_shop = \Shop::getContextShopID();
            $active_group = \Shop::getContextShopGroupID();

            $shop_ids = $uid->getShopIdList(true);
            $group_ids = [];
            $groups = [];

            foreach (\ShopGroup::getShopGroups() as $group) {
                $groups[$group->id] = $group->name;
            }
            foreach ($shop_ids as $id_shop) {
                $id_group = $shops[$id_shop]['id_shop_group'];
                $group_ids[$id_group] = $id_group;
            }
            list($star, $tab1, $tab2) = [' ★ ', '🖿&ensp;', '&ensp;●&ensp;']; ?>
            <form class="elementor-panel-footer-sub-menu" id="ce-context-wrapper" name="context" method="post">
                <select name="setShopContext" id="ce-context">
                    <option value=""><?php _e('All Shops') . (!$active_group ? $star : ''); ?></option>
                    <?php foreach ($group_ids as $id_group) { ?>
                        <?php $active = !$active_shop && $id_group == $active_group ? $star : ''; ?>
                        <option value="g-<?php echo $id_group; ?>"<?php $active && print ' selected'; ?>><?php echo "$tab1{$groups[$id_group]}$active"; ?></option>
                        <?php foreach ($shop_ids as $id_shop) { ?>
                            <?php if ($shops[$id_shop]['id_shop_group'] == $id_group) { ?>
                                <?php $active = $id_shop == $active_shop ? $star : ''; ?>
                                <option value="s-<?php echo $id_shop; ?>"<?php $active && print ' selected'; ?>><?php echo "$tab2{$shops[$id_shop]['name']}$active"; ?></option>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </select>
            </form>
        <?php } ?>
            <div class="elementor-panel-footer-sub-menu" id="ce-langs" data-lang="<?php echo $uid->id_lang; ?>" data-built='<?php echo json_encode(UId::getBuiltList($uid->id, $uid->id_type)); ?>'>
                <?php foreach ($langs as &$lang) { ?>
                    <div class="elementor-panel-footer-sub-menu-item ce-lang" data-lang="<?php echo $lang['id_lang']; ?>" data-shops='<?php echo json_encode(array_keys($lang['shops'])); ?>'>
                        <i class="elementor-icon"><?php echo $lang['iso_code']; ?></i>
                        <span class="elementor-title"><?php echo $lang['name']; ?></span>
                        <span class="elementor-description">
                            <button class="elementor-button elementor-button-success">
                                <i class="eicon-file-download"></i>
                                <?php _e('Insert'); ?>
                            </button>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
    <div id="elementor-panel-footer-navigator" class="elementor-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e('Navigator'); ?>">
        <i class="eicon-navigator" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?php _e('Navigator'); ?></span>
    </div>
    <div id="elementor-panel-footer-history" class="elementor-panel-footer-tool elementor-leave-open tooltip-target elementor-toggle-state" data-tooltip="<?php esc_attr_e('History'); ?>">
        <i class="eicon-history" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?php _e('History'); ?></span>
    </div>
    <div id="elementor-panel-footer-responsive" class="elementor-panel-footer-tool elementor-toggle-state">
        <i class="eicon-device-desktop tooltip-target" aria-hidden="true" data-tooltip="<?php esc_attr_e('Responsive Mode'); ?>"></i>
        <span class="elementor-screen-only">
            <?php _e('Responsive Mode'); ?>
        </span>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <div class="elementor-panel-footer-sub-menu">
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="desktop">
                    <i class="elementor-icon eicon-device-desktop" aria-hidden="true"></i>
                    <span class="elementor-title"><?php _e('Desktop'); ?></span>
                    <span class="elementor-description"><?php _e('Default Preview'); ?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="tablet">
                    <i class="elementor-icon eicon-device-tablet" aria-hidden="true"></i>
                    <span class="elementor-title"><?php _e('Tablet'); ?></span>
                    <?php $breakpoints = Responsive::getBreakpoints(); ?>
                    <span class="elementor-description"><?php printf(__('Preview for %s'), $breakpoints['md'] . 'px'); ?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="mobile">
                    <i class="elementor-icon eicon-device-mobile" aria-hidden="true"></i>
                    <span class="elementor-title"><?php _e('Mobile'); ?></span>
                    <span class="elementor-description"><?php printf(__('Preview for %s'), '360px'); ?></span>
                </div>
            </div>
        </div>
    </div>
    <div id="elementor-panel-footer-saver-preview" class="elementor-panel-footer-tool tooltip-target" data-tooltip="<?php esc_attr_e('Preview Changes'); ?>">
        <span id="elementor-panel-footer-saver-preview-label">
            <i class="eicon-preview-medium" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e('Preview Changes'); ?></span>
        </span>
    </div>
    <div id="elementor-panel-footer-saver-publish" class="elementor-panel-footer-tool">
        <button id="elementor-panel-saver-button-publish" class="elementor-button elementor-button-success elementor-disabled">
            <span class="elementor-state-icon">
                <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
            </span>
            <span id="elementor-panel-saver-button-publish-label">
                <?php _e('Publish'); ?>
            </span>
        </button>
    </div>
    <div id="elementor-panel-footer-saver-options" class="elementor-panel-footer-tool elementor-toggle-state">
        <button id="elementor-panel-saver-button-save-options" class="elementor-button elementor-button-success tooltip-target elementor-disabled" data-tooltip="<?php esc_attr_e('Save Options'); ?>">
            <i class="eicon-caret-up" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e('Save Options'); ?></span>
        </button>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <p class="elementor-last-edited-wrapper">
                <span class="elementor-state-icon">
                    <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
                </span>
                <span class="elementor-last-edited"></span>
            </p>
            <div class="elementor-panel-footer-sub-menu">
                <div id="elementor-panel-footer-sub-menu-item-save-draft" class="elementor-panel-footer-sub-menu-item elementor-disabled">
                    <i class="elementor-icon eicon-save" aria-hidden="true"></i>
                    <span class="elementor-title"><?php _e('Save Draft'); ?></span>
                </div>
                <div id="elementor-panel-footer-sub-menu-item-save-template" class="elementor-panel-footer-sub-menu-item">
                    <i class="elementor-icon eicon-folder" aria-hidden="true"></i>
                    <span class="elementor-title"><?php _e('Save as Template'); ?></span>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-mode-switcher-content">
    <input id="elementor-mode-switcher-preview-input" type="checkbox">
    <label for="elementor-mode-switcher-preview-input" id="elementor-mode-switcher-preview">
        <i class="eicon" aria-hidden="true" title="<?php esc_attr_e('Hide Panel'); ?>"></i>
        <span class="elementor-screen-only"><?php _e('Hide Panel'); ?></span>
    </label>
</script>

<script type="text/template" id="tmpl-editor-content">
    <div class="elementor-panel-navigation">
    <# _.each( elementData.tabs_controls, function( tabTitle, tabSlug ) {
        if ( 'content' !== tabSlug && ! elementor.userCan( 'design' ) ) {
            return;
        }
        $e.bc.ensureTab( 'panel/editor', tabSlug );
        #>
        <div class="elementor-component-tab elementor-panel-navigation-tab elementor-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
            <a href="#">{{{ tabTitle }}}</a>
        </div>
    <# } ); #>
    </div>
    <# if ( elementData.reload_preview ) { #>
        <div class="elementor-update-preview">
            <div class="elementor-update-preview-title"><?php _e('Update changes to page'); ?></div>
            <div class="elementor-update-preview-button-wrapper">
                <button class="elementor-update-preview-button elementor-button elementor-button-success"><?php _e('Apply'); ?></button>
            </div>
        </div>
    <# } #>
    <div id="elementor-controls"></div>
    <# if ( elementData.help_url ) { #>
        <div id="elementor-panel__editor__help">
            <a id="elementor-panel__editor__help__link" href="{{ elementData.help_url }}" target="_blank">
                <?php _e('Need Help'); ?>
                <i class="eicon-help-o"></i>
            </a>
        </div>
    <# } #>
</script>

<script type="text/template" id="tmpl-elementor-panel-schemes-disabled">
    <img class="elementor-nerd-box-icon" src="<?php echo _CE_ASSETS_URL_ . 'img/information.svg'; ?>">
    <div class="elementor-nerd-box-title">{{{ '<?php _e('%s are disabled'); ?>'.replace( '%s', disabledTitle ) }}}</div>
    <div class="elementor-nerd-box-message"><?php printf(__('You can enable it from the <a href="%s" target="_blank">module settings page</a>.'), Helper::getSettingsLink()); ?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-scheme-color-item">
    <div class="elementor-panel-scheme-color-picker-placeholder"></div>
    <div class="elementor-panel-scheme-color-title">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-scheme-typography-item">
    <div class="elementor-panel-heading">
        <div class="elementor-panel-heading-toggle">
            <i class="eicon" aria-hidden="true"></i>
        </div>
        <div class="elementor-panel-heading-title">{{{ title }}}</div>
    </div>
    <div class="elementor-panel-scheme-typography-items elementor-panel-box-content">
    <?php [
        $scheme_fields_keys = GroupControlTypography::getSchemeFieldsKeys(),
        $typography_group = Plugin::$instance->controls_manager->getControlGroups('typography'),
        $typography_fields = $typography_group->getFields(),
        $scheme_fields = array_intersect_key($typography_fields, array_flip($scheme_fields_keys)),
    ]; ?>
    <?php foreach ($scheme_fields as $option_name => $option) { ?>
        <div class="elementor-panel-scheme-typography-item elementor-control elementor-control-type-select">
            <div class="elementor-panel-scheme-item-title elementor-control-title"><?php echo $option['label']; ?></div>
            <div class="elementor-panel-scheme-typography-item-value elementor-control-input-wrapper">
            <?php if ('select' === $option['type']) { ?>
                <select name="<?php echo esc_attr($option_name); ?>" class="elementor-panel-scheme-typography-item-field">
                <?php foreach ($option['options'] as $field_key => $field_value) { ?>
                    <option value="<?php echo esc_attr($field_key); ?>"><?php echo $field_value; ?></option>
                <?php } ?>
                </select>
            <?php } elseif ('font' === $option['type']) { ?>
                <select name="<?php echo esc_attr($option_name); ?>" class="elementor-panel-scheme-typography-item-field">
                    <option value=""><?php _e('Default'); ?></option>
                <?php foreach (Fonts::getFontGroups() as $group_type => $group_label) { ?>
                    <optgroup label="<?php echo esc_attr($group_label); ?>">
                    <?php foreach (Fonts::getFontsByGroups([$group_type]) as $font_title => $font_type) { ?>
                        <option value="<?php echo esc_attr($font_title); ?>"><?php echo $font_title; ?></option>
                    <?php } ?>
                    </optgroup>
                <?php } ?>
                </select>
            <?php } elseif ('text' === $option['type']) { ?>
                <input name="<?php echo esc_attr($option_name); ?>" class="elementor-panel-scheme-typography-item-field">
            <?php } ?>
            </div>
        </div>
    <?php } ?>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-responsive-switchers">
    <div class="elementor-control-responsive-switchers">
        <div class="elementor-control-responsive-switchers__holder">
        <#
        var devices = responsive.devices || [ 'desktop', 'tablet', 'mobile' ];

        _.each( devices, function( device ) {
            var deviceLabel = device.charAt(0).toUpperCase() + device.slice(1),
                tooltipDir = "<?php echo is_rtl() ? 'e' : 'w'; ?>";
            #>
            <a class="elementor-responsive-switcher tooltip-target elementor-responsive-switcher-{{ device }}" data-device="{{ device }}" data-tooltip="{{ deviceLabel }}" data-tooltip-pos="{{ tooltipDir }}">
                <i class="eicon-device-{{ device }}"></i>
            </a>
        <# } ); #>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-dynamic-switcher">
    <div class="elementor-control-dynamic-switcher elementor-control-unit-1" data-tooltip="<?php _e('Dynamic Tags'); ?>">
        <i class="eicon-database"></i>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-dynamic-cover">
    <div class="elementor-dynamic-cover__settings">
        <i class="eicon-{{ hasSettings ? 'wrench' : 'database' }}"></i>
    </div>
    <div class="elementor-dynamic-cover__title ce-{{ hasSettings ? 'active' : 'inactive' }}" title="{{{ title + (' ' + content).replace(/\s+/, ' ') }}}">
        <?php /* content: attr(title) */ ?>
    </div>
    <# if ( isRemovable ) { #>
        <div class="elementor-dynamic-cover__remove">
            <i class="eicon-close-circle"></i>
        </div>
    <# } else if (hasSettings) { #>
        <div class="elementor-dynamic-cover__drop-down">
            <i class="eicon-sort-down"></i>
        </div>
    <# } #>
</script>
