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

class ModulesXThemeXWidgetsXSignIn extends WidgetBase
{
    use NavTrait;

    const REMOTE_RENDER = true;

    protected $context;

    public function getName()
    {
        return 'sign-in';
    }

    public function getTitle()
    {
        return __('Sign in');
    }

    public function getIcon()
    {
        return 'eicon-lock-user';
    }

    public function getCategories()
    {
        return ['theme-elements'];
    }

    public function getKeywords()
    {
        return ['login', 'user', 'account', 'logout'];
    }

    public function getLinkToOptions()
    {
        $t = $this->context->getTranslator();

        return [
            'my-account' => $t->trans('My account', [], 'Shop.Navigation'),
            'identity' => $t->trans('Personal Information', [], 'Shop.Theme.Checkout'),
            'address' => $t->trans('New address', [], 'Shop.Theme.Customeraccount'),
            'addresses' => $t->trans('Addresses', [], 'Shop.Navigation'),
            'history' => $t->trans('Order history', [], 'Shop.Navigation'),
            'order-slip' => $t->trans('Credit slip', [], 'Shop.Navigation'),
            'discount' => $t->trans('Vouchers', [], 'Shop.Theme.Customeraccount'),
            'logout' => $t->trans('Sign out', [], 'Shop.Theme.Actions'),
            'custom' => __('Custom URL'),
        ];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_selector',
            [
                'label' => $this->getTitle(),
            ]
        );

        $this->startControlsTabs('tabs_label_content');

        $this->startControlsTab(
            'tab_label_sign_in',
            [
                'label' => __('Sign in'),
            ]
        );

        $this->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'ce-icons' => [
                        'user',
                        'user-o',
                        'user-circle',
                        'user-circle-o',
                        'user-simple',
                        'user-minimal',
                    ],
                    'fa-regular' => [
                        'user',
                        'circle-user',
                    ],
                    'fa-solid' => [
                        'user',
                        'circle-user',
                        'user-large',
                    ],
                ],
            ]
        );

        $this->addControl(
            'label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'default' => __('Sign in'),
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_label_signed_in',
            [
                'label' => __('Signed in'),
            ]
        );

        $this->addControl(
            'account',
            [
                'label' => __('Label'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'default' => ['icon', 'firstname'],
                'multiple' => true,
                'options' => [
                    'icon' => __('Icon'),
                    'before' => __('Before'),
                    'firstname' => __('First Name'),
                    'lastname' => __('Last Name'),
                    'after' => __('After'),
                ],
            ]
        );

        $this->addControl(
            'before',
            [
                'label' => __('Before'),
                'type' => ControlsManager::TEXT,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'account',
                            'operator' => 'contains',
                            'value' => 'before',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'after',
            [
                'label' => __('After'),
                'type' => ControlsManager::TEXT,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'account',
                            'operator' => 'contains',
                            'value' => 'after',
                        ],
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->registerNavContentControls();

        $this->addControl(
            'heading_usermenu',
            [
                'label' => __('Usermenu'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'link_to',
            [
                'label' => __('Link'),
                'type' => ControlsManager::SELECT,
                'default' => 'identity',
                'options' => $this->getLinkToOptions(),
            ]
        );

        $repeater->addControl(
            'link',
            [
                'label_block' => true,
                'type' => ControlsManager::URL,
                'placeholder' => __('http://your-link.com'),
                'options' => false,
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $repeater->addControl(
            'text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
            ]
        );

        $repeater->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'exclude_inline_options' => ['svg'],
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->addControl(
            'usermenu',
            [
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'link_to' => 'my-account',
                        'selected_icon' => [
                            'value' => 'far fa-user',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'link_to' => 'addresses',
                        'selected_icon' => [
                            'value' => 'far fa-address-book',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'link_to' => 'history',
                        'selected_icon' => [
                            'value' => 'fas fa-list',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'link_to' => 'logout',
                        'selected_icon' => [
                            'value' => 'fas fa-right-from-bracket',
                            'library' => 'fa-solid',
                        ],
                    ],
                ],
                'title_field' => '<#
                    var controls = elementor.panel.currentView.currentPageView.model.get("settings").controls,
                        migrated = "undefined" !== typeof __fa4_migrated,
                        icon = "undefined" !== typeof icon ? icon : false; #>
                    <i class="{{ icon && !migrated ? icon : selected_icon.value }}"></i>
                    {{{ text || controls.usermenu.fields.link_to.options[link_to] }}}',
            ]
        );

        $this->endControlsSection();

        $this->registerNavStyleSection([
            'show_icon' => true,
            'active_condition' => [
                'hide!' => '',
            ],
            'space_between_condition' => [
                'hide!' => '',
            ],
        ]);

        $this->registerDropdownStyleSection([
            'active_condition' => [
                'hide!' => '',
            ],
        ]);
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-nav-menu';
    }

    public function getUrl(&$item)
    {
        if ('custom' === $item['link_to']) {
            return $item['link']['url'];
        }
        if ('logout' === $item['link_to']) {
            return $this->context->link->getPageLink('index', true, null, 'mylogout');
        }

        return $this->context->link->getPageLink($item['link_to'], true);
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $customer = $this->context->customer;
        $icon = isset($settings['icon']) && !isset($settings['__fa4_migrated']['selected_icon'])
            ? $settings['icon']
            : $settings['selected_icon']['value'];
        $this->indicator = isset($settings['indicator']) && !isset($settings['__fa4_migrated']['submenu_icon'])
            ? $settings['indicator']
            : $settings['submenu_icon']['value'];

        if ($customer->isLogged()) {
            $options = $this->getLinkToOptions();
            $account = &$settings['account'];
            $menu = [
                [
                    'id' => 0,
                    'icon' => in_array('icon', $account) ? $icon : '',
                    'label' => call_user_func(function () use ($settings, $account, $customer) {
                        $label = '';

                        in_array('before', $account) && $label .= $settings['before'];
                        in_array('firstname', $account) && $label .= " {$customer->firstname}";
                        in_array('lastname', $account) && $label .= " {$customer->lastname}";
                        in_array('after', $account) && $label .= $settings['after'];

                        return trim($label);
                    }),
                    'url' => $this->context->link->getPageLink('my-account', true),
                    'children' => [],
                ],
            ];
            foreach ($settings['usermenu'] as $i => &$item) {
                $menu[0]['children'][] = [
                    'id' => $i + 1,
                    'icon' => !empty($item['icon']) && !isset($item['__fa4_migrated']['selected_icon'])
                        ? $item['icon']
                        : $item['selected_icon']['value'],
                    'label' => $item['text'] ?: $options[$item['link_to']],
                    'url' => $this->getUrl($item),
                ];
            }
        } else {
            $menu = [
                [
                    'id' => 0,
                    'icon' => $icon,
                    'label' => $settings['label'],
                    'url' => $this->context->link->getPageLink('my-account', true),
                    'children' => [],
                ],
            ];
        }
        $ul_class = 'elementor-nav';

        // General Menu.
        ob_start();
        $this->accountList($menu, 0, $ul_class);
        $menu_html = ob_get_clean();

        $this->addRenderAttribute('main-menu', 'class', [
            'elementor-sign-in',
            'elementor-nav--main',
            'elementor-nav__container',
            'elementor-nav--layout-horizontal',
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

    protected function accountList(array &$nodes, $depth = 0, $ul_class = '')
    {
        ?>
        <ul <?php echo $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="usermenu-' . $this->getId() . '" class="' . $ul_class . '"'; ?>>
        <?php foreach ($nodes as &$node) { ?>
            <li class="<?php printf(self::$li_class, 'account', "account-{$node['id']}", '', !empty($node['children']) ? ' menu-item-has-children' : ''); ?>">
                <a class="<?php echo $depth ? 'elementor-sub-item' : 'elementor-item'; ?>" href="<?php echo esc_attr($node['url']); ?>"<?php $depth || print ' aria-label="' . __('My account') . '"'; ?>>
                <?php if ($node['icon']) { ?>
                    <i class="<?php echo $node['icon']; ?>"></i>
                <?php } ?>
                <?php if ($node['label']) { ?>
                    <span><?php echo $node['label']; ?></span>
                <?php } ?>
                <?php if ($this->indicator && !empty($node['children'])) { ?>
                    <span class="sub-arrow <?php echo esc_attr($this->indicator); ?>"></span>
                <?php } ?>
                </a>
                <?php empty($node['children']) || $this->accountList($node['children'], $depth + 1); ?>
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
