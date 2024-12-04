<?php
/**
 * Creative Elements - live Theme & Page Builder
 *
 * @author    WebshopWorks
 * @copyright 2019-2024 WebshopWorks.com
 * @license   One domain support license
 */
namespace CE;

if (!defined('_PS_VERSION_')) {
    exit;
}

use CE\ModulesXThemeXWidgetsXTraitsXNav as NavTrait;

class ModulesXThemeXWidgetsXCurrencySelector extends WidgetBase
{
    use NavTrait;

    protected $context;

    protected $currency_symbol;

    protected $currency_code;

    protected $currency_name;

    public function getName()
    {
        return 'currency-selector';
    }

    public function getTitle()
    {
        return __('Currency Selector');
    }

    public function getIcon()
    {
        return 'eicon-currencies';
    }

    public function getCategories()
    {
        return ['theme-elements'];
    }

    public function getKeywords()
    {
        return ['currency', 'selector', 'chooser'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_layout',
            [
                'label' => __('Currency Selector'),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'default' => 'dropdown',
                'options' => [
                    'classic' => __('Classic'),
                    'dropdown' => __('Dropdown'),
                ],
                'separator' => 'after',
            ]
        );

        $this->addControl(
            'content',
            [
                'label' => __('Content'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'default' => ['symbol', 'code'],
                'options' => [
                    'symbol' => __('Symbol'),
                    'code' => __('ISO Code'),
                    'name' => __('Currency'),
                ],
                'multiple' => true,
            ]
        );

        $this->addControl(
            'show_current',
            [
                'label' => __('Current Currency'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'prefix_class' => 'elementor-nav--',
                'return_value' => 'active',
            ]
        );

        $this->registerNavContentControls([
            'layout_options' => [
                'horizontal' => __('Horizontal'),
                'vertical' => __('Vertical'),
            ],
            'submenu_condition' => [
                'skin' => 'dropdown',
            ],
        ]);

        $this->endControlsSection();

        $this->registerNavStyleSection([
            'active_condition' => [
                'show_current!' => '',
            ],
            'space_between_condition' => [
                'skin' => 'classic',
            ],
        ]);

        $this->registerDropdownStyleSection([
            'condition' => [
                'skin' => 'dropdown',
            ],
            'active_condition' => [
                'show_current!' => '',
            ],
        ]);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-nav-menu';
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $currencies = \Currency::getCurrencies(false, true, true);

        if (\Configuration::isCatalogMode() || count($currencies) <= 1 || !$settings['content']) {
            return;
        }
        $this->currency_symbol = in_array('symbol', $settings['content']);
        $this->currency_code = in_array('code', $settings['content']);
        $this->currency_name = in_array('name', $settings['content']);
        $url = preg_replace('/[&\?](SubmitCurrency|id_currency)=[^&]*/', '', $_SERVER['REQUEST_URI']);
        $id_currency = $this->context->currency->id;
        $menu = [
            '0' => [
                'id_currency' => $id_currency,
                'symbol' => !empty($this->context->currency->symbol)
                    ? $this->context->currency->symbol
                    : $this->context->currency->sign,
                'iso_code' => $this->context->currency->iso_code,
                'name' => $this->context->currency->name,
                'url' => 'javascript:;',
                'current' => false,
                'children' => [],
            ],
        ];
        foreach ($currencies as &$currency) {
            $currency['current'] = $id_currency == $currency['id'];
            $currency['url'] = \Tools::url($url, 'SubmitCurrency=1&id_currency=' . (int) $currency['id_currency']);

            $menu[0]['children'][] = $currency;
        }
        if ('classic' === $settings['skin']) {
            $menu = &$menu[0]['children'];
        } else {
            $this->indicator = isset($settings['indicator']) && !isset($settings['__fa4_migrated']['submenu_icon'])
                ? $settings['indicator']
                : $settings['submenu_icon']['value']
            ;
        }
        $ul_class = 'elementor-nav';

        if ('vertical' === $settings['layout']) {
            $ul_class .= ' sm-vertical';
        }

        // General Menu.
        ob_start();
        $this->selectorList($menu, 0, $ul_class);
        $menu_html = ob_get_clean();

        $this->addRenderAttribute('main-menu', 'class', [
            'elementor-currencies',
            'elementor-nav--main',
            'elementor-nav__container',
            'elementor-nav--layout-' . $settings['layout'],
        ]);

        if ('none' !== $settings['pointer']) {
            $animation_type = self::getPointerAnimationType($settings['pointer']);

            $this->addRenderAttribute('main-menu', 'class', [
                'e--pointer-' . $settings['pointer'],
                'e--animation-' . $settings[$animation_type],
            ]);
        } ?>
        <nav <?php $this->printRenderAttributeString('main-menu'); ?>><?php echo $menu_html; ?></nav>
        <?php
    }

    protected function selectorList(array &$nodes, $depth = 0, $ul_class = '')
    {
        ?>
        <ul <?php echo $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="selector-' . $this->getId() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) { ?>
            <li class="<?php printf(self::$li_class, 'lang', "currency-{$node['id_currency']}", $node['current'] ? ' current-menu-item' : '', !empty($node['children']) ? ' menu-item-has-children' : ''); ?>">
                <a class="<?php echo ($depth ? 'elementor-sub-item' : 'elementor-item') . ($node['current'] ? ' elementor-item-active' : ''); ?>" href="<?php echo esc_attr($node['url']); ?>">
                <?php if ($this->currency_symbol) { ?>
                    <span class="elementor-currencies__symbol"><?php echo $node[!empty($node['symbol']) ? 'symbol' : 'sign']; ?></span>
                <?php } ?>
                <?php if ($this->currency_code) { ?>
                    <span class="elementor-currencies__code"><?php echo $node['iso_code']; ?></span>
                <?php } ?>
                <?php if ($this->currency_name) { ?>
                    <span class="elementor-currencies__name"><?php echo $node['name']; ?></span>
                <?php } ?>
                <?php if ($this->indicator && !empty($node['children'])) { ?>
                    <span class="sub-arrow <?php echo esc_attr($this->indicator); ?>"></span>
                <?php } ?>
                </a>
                <?php empty($node['children']) || $this->selectorList($node['children'], $depth + 1); ?>
            </li>
        <?php } ?>
        </ul>
        <?php
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
