<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\ModulesXCatalogXWidgetsXProductXRating as ProductRating;

class ModulesXCatalogXWidgetsXProductXMiniatureXRating extends ProductRating
{
    public function getName()
    {
        return 'product-miniature-rating';
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl('selected_comments_number_icon', [
            'exclude_inline_options' => ['svg'],
            'default' => [
                'value' => '',
                'library' => '',
            ],
        ]);

        $this->updateControl('comments_number_before', [
            'placeholder' => '',
            'default' => '(',
        ]);

        $this->updateControl('comments_number_after', [
            'default' => ')',
        ]);
    }

    protected function renderStars($icon)
    {
        if (!is_admin()) {
            return parent::renderStars($icon);
        }
        ob_start(); ?>
        {$floored_rating = $average_grade|intval}
        {for $stars=1 to 5}
            {if $stars <= $floored_rating}
                <i class="elementor-star-full"><?php echo $icon; ?></i>
            {elseif $floored_rating + 1 === $stars}
                <i class="elementor-star-{10*($average_grade - $floored_rating)}"><?php echo $icon; ?></i>
            {else}
                <i class="elementor-star-empty"><?php echo $icon; ?></i>
            {/if}
        {/for}
        <?php
        return ob_get_clean();
    }

    protected function renderSmarty()
    {
        $settings = $this->getSettingsForDisplay();
        $comments_number_icon = isset($settings['comments_number_icon']) && !isset($settings['__fa4_migrated']['selected_comments_number_icon'])
            ? $settings['comments_number_icon']
            : $settings['selected_comments_number_icon']['value']; ?>
        {$cb = Context::getContext()->controller->getContainer()}
        {if $cb->has('product_comment_repository')}
            {$pcr = $cb->get('product_comment_repository')}
            {$pcm = Configuration::get('PRODUCT_COMMENTS_MODERATE')}
            {$nb_comments = _q_c_($pcr, $pcr->getCommentsNumber($product.id, $pcm)|intval, 0)}
            <?php echo $settings['hide_empty']
                ? '{if $nb_comments}'
                : '{if $pcr}'; ?>
            {$average_grade = Tools::ps_round($pcr->getAverageGrade($product.id, $pcm), 1)}
            <div class="ce-product-rating">
                <?php WidgetStarRating::render(); ?>
            {if $nb_comments}
            <?php if ($settings['show_average_grade']) { ?>
                <span class="ce-product-rating__average-grade">{$average_grade}</span>
            <?php } ?>

            <?php if ($settings['show_comments_number']) { ?>
                <span class="elementor-icon-list-item">
                <?php if ($comments_number_icon) { ?>
                    <span class="elementor-icon-list-icon">
                        <i class="<?php echo esc_attr($comments_number_icon); ?>" aria-hidden="true"></i>
                    </span>
                <?php } ?>
                    <span class="elementor-icon-list-text">
                        <?php echo "$settings[comments_number_before]{\$nb_comments}$settings[comments_number_after]"; ?>
                    </span>
                </span>
            <?php } ?>
            {/if}
            </div>
            <?php echo '{/if}'; ?>
        {/if}
        <?php
    }
}
