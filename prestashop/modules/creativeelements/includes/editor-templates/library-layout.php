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
<script type="text/template" id="tmpl-elementor-templates-modal__header">
    <# var modalHeader = 'elementor-templates-modal__header'; #>
    <div class="{{{ modalHeader }}}__logo-area"></div>
    <div class="{{{ modalHeader }}}__menu-area"></div>
    <div class="{{{ modalHeader }}}__items-area">
    <# if ( closeType ) { #>
        <div class="{{{ modalHeader }}}__close {{{ modalHeader }}}__close--{{{ closeType }}} {{{ modalHeader }}}__item">
            <# if ( 'skip' === closeType ) { #>
            <span><?php _e('Skip'); ?></span>
            <# } #>
            <i class="eicon-close" aria-hidden="true" title="<?php _e('Close'); ?>"></i>
            <span class="elementor-screen-only"><?php _e('Close'); ?></span>
        </div>
    <# } #>
        <div id="elementor-template-library-header-tools"></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-templates-modal__header__logo">
    <img src="<?php echo esc_attr(_CE_URL_ . 'logo.png'); ?>" alt="CE" width="26" height="26" style="margin-right: 10px;">
    <span class="elementor-templates-modal__header__logo__title">{{{ title }}}</span>
</script>
