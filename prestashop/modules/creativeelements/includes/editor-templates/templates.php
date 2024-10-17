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
<script type="text/template" id="tmpl-elementor-template-library-header-actions">
    <div id="elementor-template-library-header-import" class="elementor-templates-modal__header__item">
        <i class="eicon-upload-circle-o" aria-hidden="true" title="<?php esc_attr_e('Import Template'); ?>"></i>
        <span class="elementor-screen-only"><?php _e('Import Template'); ?></span>
    </div>
    <div id="elementor-template-library-header-sync" class="elementor-templates-modal__header__item">
        <i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e('Sync Library'); ?>"></i>
        <span class="elementor-screen-only"><?php _e('Sync Library'); ?></span>
    </div>
    <div id="elementor-template-library-header-save" class="elementor-templates-modal__header__item">
        <i class="eicon-save-o" aria-hidden="true" title="<?php esc_attr_e('Save'); ?>"></i>
        <span class="elementor-screen-only"><?php _e('Save'); ?></span>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-preview">
    <div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
        {{{ elementor.templates.layout.getTemplateActionButton( obj ) }}}
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-back">
    <i class="eicon-" aria-hidden="true"></i>
    <span><?php _e('Back to Library'); ?></span>
</script>

<script type="text/template" id="tmpl-elementor-template-library-loading">
    <div class="elementor-loader-wrapper">
        <div class="elementor-loader">
            <div class="elementor-loader-boxes">
                <div class="elementor-loader-box"></div>
                <div class="elementor-loader-box"></div>
                <div class="elementor-loader-box"></div>
                <div class="elementor-loader-box"></div>
            </div>
        </div>
        <div class="elementor-loading-title"><?php _e('Loading'); ?></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-menu">
<# jQuery.each( tabs, ( tab, args ) => { #>
    <div class="elementor-component-tab elementor-template-library-menu-item" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
<# } ); #>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates">
    <# var activeSource = elementor.templates.getFilter('source'); #>
    <div id="elementor-template-library-toolbar">
        <# if ( 'remote' === activeSource ) {
            var activeType = elementor.templates.getFilter('type');
            #>
            <div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">
                <# if ( 'page' === activeType ) { #>
                    <div id="elementor-template-library-order">
                        <input type="radio" id="elementor-template-library-order-new" class="elementor-template-library-order-input" name="elementor-template-library-order" value="date">
                        <label for="elementor-template-library-order-new" class="elementor-template-library-order-label"><?php _e('New'); ?></label>
                        <input type="radio" id="elementor-template-library-order-trend" class="elementor-template-library-order-input" name="elementor-template-library-order" value="trendIndex">
                        <label for="elementor-template-library-order-trend" class="elementor-template-library-order-label"><?php _e('Trend'); ?></label>
                        <input type="radio" id="elementor-template-library-order-popular" class="elementor-template-library-order-input" name="elementor-template-library-order" value="popularityIndex">
                        <label for="elementor-template-library-order-popular" class="elementor-template-library-order-label"><?php _e('Popular'); ?></label>
                    </div>
                <# } else {
                    var config = elementor.templates.getConfig( activeType );
                    if ( config.categories ) { #>
                        <div id="elementor-template-library-filter">
                            <select id="elementor-template-library-filter-subtype" class="elementor-template-library-filter-select" data-elementor-filter="subtype">
                                <option></option>
                                <# config.categories.forEach( function( category ) {
                                    var selected = category === elementor.templates.getFilter( 'subtype' ) ? ' selected' : '';
                                    #>
                                    <option value="{{ category }}"{{{ selected }}}>{{{ category }}}</option>
                                <# } ); #>
                            </select>
                        </div>
                    <# }
                } #>
                <div id="elementor-template-library-my-favorites">
                    <# var checked = elementor.templates.getFilter( 'favorite' ) ? ' checked' : ''; #>
                    <input id="elementor-template-library-filter-my-favorites" type="checkbox"{{{ checked }}}>
                    <label id="elementor-template-library-filter-my-favorites-label" for="elementor-template-library-filter-my-favorites">
                        <i class="eicon" aria-hidden="true"></i>
                        <?php _e('My Favorites'); ?>
                    </label>
                </div>
            </div>
        <# } else { #>
            <div id="elementor-template-library-filter-toolbar-local" class="elementor-template-library-filter-toolbar"></div>
        <# } #>
        <div id="elementor-template-library-filter-text-wrapper">
            <label for="elementor-template-library-filter-text" class="elementor-screen-only"><?php _e('Search Templates:'); ?></label>
            <input id="elementor-template-library-filter-text" placeholder="<?php esc_attr_e('Search'); ?>">
            <i class="eicon-search"></i>
        </div>
    </div>
    <# if ( 'local' === activeSource ) { #>
        <div id="elementor-template-library-order-toolbar-local">
            <div class="elementor-template-library-local-column-1">
                <input type="radio" id="elementor-template-library-order-local-title" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="title" data-default-ordering-direction="asc">
                <label for="elementor-template-library-order-local-title" class="elementor-template-library-order-label"><?php _e('Name'); ?></label>
            </div>
            <div class="elementor-template-library-local-column-2">
                <input type="radio" id="elementor-template-library-order-local-type" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="type" data-default-ordering-direction="asc">
                <label for="elementor-template-library-order-local-type" class="elementor-template-library-order-label"><?php _e('Type'); ?></label>
            </div>
            <div class="elementor-template-library-local-column-3">
                <input type="radio" id="elementor-template-library-order-local-author" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="author" data-default-ordering-direction="asc">
                <label for="elementor-template-library-order-local-author" class="elementor-template-library-order-label"><?php _e('Created By'); ?></label>
            </div>
            <div class="elementor-template-library-local-column-4">
                <input type="radio" id="elementor-template-library-order-local-date" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="date">
                <label for="elementor-template-library-order-local-date" class="elementor-template-library-order-label"><?php _e('Creation Date'); ?></label>
            </div>
            <div class="elementor-template-library-local-column-5">
                <div class="elementor-template-library-order-label"><?php _e('Actions'); ?></div>
            </div>
        </div>
    <# } #>
    <div id="elementor-template-library-templates-container"></div>
    <# if ( 'remote' === activeSource ) { #>
        <div id="elementor-template-library-footer-banner">
            <img class="elementor-nerd-box-icon" src="<?php echo _CE_ASSETS_URL_ . 'img/information.svg'; ?>">
            <div class="elementor-excerpt"><?php _e('Stay tuned! More awesome templates coming real soon.'); ?></div>
        </div>
    <# } #>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-remote">
    <div class="elementor-template-library-template-body">
        <# if ( 'page' === type ) { #>
            <div class="elementor-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
        <# } else { #>
            <img src="{{ thumbnail }}">
        <# } #>
        <div class="elementor-template-library-template-preview">
            <i class="eicon-zoom-in-bold" aria-hidden="true"></i>
        </div>
    </div>
    <div class="elementor-template-library-template-footer">
        {{{ elementor.templates.layout.getTemplateActionButton( obj ) }}}
        <div class="elementor-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
        <div class="elementor-template-library-favorite">
            <input id="elementor-template-library-template-{{ template_id }}-favorite-input" class="elementor-template-library-template-favorite-input" type="checkbox"{{ favorite ? " checked" : "" }}>
            <label for="elementor-template-library-template-{{ template_id }}-favorite-input" class="elementor-template-library-template-favorite-label">
                <i class="eicon-heart-o" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?php _e('Favorite'); ?></span>
            </label>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-local">
    <div class="elementor-template-library-template-name elementor-template-library-local-column-1">{{{ title }}}</div>
    <div class="elementor-template-library-template-meta elementor-template-library-template-type elementor-template-library-local-column-2">{{{ elementor.translate( type ) }}}</div>
    <div class="elementor-template-library-template-meta elementor-template-library-template-author elementor-template-library-local-column-3">{{{ author }}}</div>
    <div class="elementor-template-library-template-meta elementor-template-library-template-date elementor-template-library-local-column-4">{{{ human_date }}}</div>
    <div class="elementor-template-library-template-controls elementor-template-library-local-column-5">
        <div class="elementor-template-library-template-preview">
            <i class="eicon-preview-medium" aria-hidden="true"></i>
            <span class="elementor-template-library-template-control-title"><?php _e('Preview'); ?></span>
        </div>
        <button class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button elementor-button-success">
            <i class="eicon-file-download" aria-hidden="true"></i>
            <span class="elementor-button-title"><?php _e('Insert'); ?></span>
        </button>
        <div class="elementor-template-library-template-more-toggle">
            <i class="eicon-ellipsis-h" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e('More actions'); ?></span>
        </div>
        <div class="elementor-template-library-template-more">
            <div class="elementor-template-library-template-export">
                <a href="{{ export_link }}">
                    <i class="eicon-sign-out" aria-hidden="true"></i>
                    <span class="elementor-template-library-template-control-title"><?php _e('Export'); ?></span>
                </a>
            </div>
            <div class="elementor-template-library-template-delete">
                <i class="eicon-trash-o" aria-hidden="true"></i>
                <span class="elementor-template-library-template-control-title"><?php _e('Delete'); ?></span>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-insert-button">
    <a class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button">
        <i class="eicon-file-download" aria-hidden="true"></i>
        <span class="elementor-button-title"><?php _e('Insert'); ?></span>
    </a>
</script>

<script type="text/template" id="tmpl-elementor-template-library-get-pro-button">
    <a class="elementor-template-library-template-action elementor-button elementor-button-go-pro" href="<?php echo esc_attr(Helper::getSettingsLink()); ?>#license">
        <i class="eicon-external-link-square" aria-hidden="true"></i>
        <span class="elementor-button-title"><?php _e('Activate License'); ?></span>
    </a>
</script>

<script type="text/template" id="tmpl-elementor-template-library-save-template">
    <div class="elementor-template-library-blank-icon">
        <i class="eicon-library-save" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?php _e('Save'); ?></span>
    </div>
    <div class="elementor-template-library-blank-title">{{{ title }}}</div>
    <div class="elementor-template-library-blank-message">{{{ description }}}</div>
    <form id="elementor-template-library-save-template-form">
        <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
        <input id="elementor-template-library-save-template-name" name="title" placeholder="<?php esc_attr_e('Enter Template Name'); ?>" required>
        <button id="elementor-template-library-save-template-submit" class="elementor-button elementor-button-success">
            <span class="elementor-state-icon">
                <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
            </span>
            <?php _e('Save'); ?>
        </button>
    </form>
    <!--div class="elementor-template-library-blank-footer">
        <?php _e('Want to learn more about the Elementor library?'); ?>
        <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php _e('Click here'); ?></a>
    </div-->
</script>

<script type="text/template" id="tmpl-elementor-template-library-import">
    <form id="elementor-template-library-import-form">
        <div class="elementor-template-library-blank-icon">
            <i class="eicon-library-upload" aria-hidden="true"></i>
        </div>
        <div class="elementor-template-library-blank-title"><?php _e('Import Template to Your Library'); ?></div>
        <div class="elementor-template-library-blank-message"><?php _e('Drag & drop your .JSON or .zip template file'); ?></div>
        <div id="elementor-template-library-import-form-or"><?php _e('or'); ?></div>
        <label for="elementor-template-library-import-form-input" id="elementor-template-library-import-form-label" class="elementor-button elementor-button-success"><?php _e('Select File'); ?></label>
        <input id="elementor-template-library-import-form-input" type="file" name="file" accept=".json,.zip" required/>
        <!--div class="elementor-template-library-blank-footer">
            <?php _e('Want to learn more about the Elementor library?'); ?>
            <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php _e('Click here'); ?></a>
        </div-->
    </form>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates-empty">
    <div class="elementor-template-library-blank-icon">
        <img src="<?php echo _CE_ASSETS_URL_ . 'img/no-search-results.svg'; ?>" class="elementor-template-library-no-results">
    </div>
    <div class="elementor-template-library-blank-title"></div>
    <div class="elementor-template-library-blank-message"></div>
    <!--div class="elementor-template-library-blank-footer">
        <?php _e('Want to learn more about the Elementor library?'); ?>
        <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php _e('Click here'); ?></a>
    </div-->
</script>

<script type="text/template" id="tmpl-elementor-template-library-preview">
    <?php echo "<\x69frame></\x69frame>"; ?>
</script>

<script type="text/template" id="tmpl-elementor-template-library-connect">
    <div id="elementor-template-library-connect-logo" class="elementor-gradient-logo">
        <i class="eicon-elementor" aria-hidden="true"></i>
    </div>
    <div class="elementor-template-library-blank-title">
        {{{ title }}}
    </div>
    <div class="elementor-template-library-blank-message">
        {{{ message }}}
    </div>
    <a id="elementor-template-library-connect__button" class="elementor-button elementor-button-success" href="javascript:void('todo')">
        {{{ button }}}
    </a>
    <?php $base_images_url = _CE_ASSETS_URL_ . 'img/library-connect/'; ?>
    <?php foreach (['left-1', 'left-2', 'right-1', 'right-2'] as $image) { ?>
        <img id="elementor-template-library-connect__background-image-<?php echo $image; ?>" class="elementor-template-library-connect__background-image" src="<?php echo $base_images_url . $image; ?>.png" draggable="false">
    <?php } ?>
</script>
