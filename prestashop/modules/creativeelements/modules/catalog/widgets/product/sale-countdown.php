<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

use CE\ModulesXPremiumXWidgetsXCountdown as Countdown;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ModulesXCatalogXWidgetsXProductXSaleCountdown extends Countdown
{
    const REMOTE_RENDER = true;

    public function getName()
    {
        return 'product-sale-countdown';
    }

    public function getTitle()
    {
        return __('Sale Countdown');
    }

    public function getIcon()
    {
        return 'eicon-countdown';
    }

    public function getCategories()
    {
        return ['product-elements'];
    }

    public function getKeywords()
    {
        return ['shop', 'store', 'countdown', 'timer', 'date', 'sale', 'discount', 'product'];
    }

    protected function isDynamicContent()
    {
        return true;
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl(
            'due_date',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => '',
            ]
        );

        $this->updateControl('box_background_color', ['scheme' => '']);
        $this->updateControl('text_color', ['scheme' => '']);

        $this->updateControl('digits_typography_font_family', ['scheme' => '']);
        $this->updateControl('digits_typography_font_weight', ['scheme' => '']);

        $this->updateControl('label_typography_font_family', ['scheme' => '']);
        $this->updateControl('label_typography_font_weight', ['scheme' => '']);

        $this->updateControl('typography_font_family', ['scheme' => '']);
        $this->updateControl('typography_font_weight', ['scheme' => '']);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-countdown';
    }

    protected function render()
    {
        $product = $GLOBALS['smarty']->tpl_vars['product']->value;

        if (!empty($product['specific_prices']['to']) && '0000-00-00 00:00:00' !== $product['specific_prices']['to']) {
            $this->setSettings('due_date', $product['specific_prices']['to']);

            parent::render();
        }
    }

    protected function renderSmarty()
    {
        $settings = $this->getSettingsForDisplay(); ?>
        {if !empty($product.specific_prices.to) && '0000-00-00 00:00:00' !== $product.specific_prices.to}
            <div class="elementor-countdown-wrapper"
                data-date="{strtotime($product.specific_prices.to)}"
                data-expire-actions='<?php echo str_replace('{"', '{ "', json_encode($this->getActions($settings))); ?>'>
                <?php echo $this->getStrftime($settings); ?>
            </div>
        <?php if ($settings['expire_actions'] && in_array('message', $settings['expire_actions'])) { ?>
            <div class="elementor-countdown-expire--message"><?php echo $settings['message_after_expire']; ?></div>
        <?php } ?>
        {/if}
        <?php
    }
}
