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

class ModulesXPremiumXWidgetsXContactForm extends WidgetBase
{
    protected static $col_width = [
        '100' => '100%',
        '80' => '80%',
        '75' => '75%',
        '66' => '66%',
        '60' => '60%',
        '50' => '50%',
        '40' => '40%',
        '33' => '33%',
        '25' => '25%',
        '20' => '20%',
    ];

    protected $context;

    protected $translator;

    protected $upload;

    protected $gdpr;

    protected $gdpr_msg;

    protected $gdpr_cfg;

    public function getName()
    {
        return 'contact-form';
    }

    public function getTitle()
    {
        return __('Contact Form');
    }

    public function getIcon()
    {
        return 'eicon-form-horizontal';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['submit', 'send', 'message'];
    }

    protected function getContactOptions()
    {
        $contacts = \Contact::getContacts($this->context->language->id);
        $opts = [
            '0' => __('Select'),
        ];
        foreach ($contacts as $contact) {
            $opts[$contact['id_contact']] = $contact['name'];
        }

        return $opts;
    }

    protected function getToken()
    {
        if (empty($this->context->cookie->contactFormTokenTTL) || $this->context->cookie->contactFormTokenTTL < time()) {
            $this->context->cookie->contactFormToken = call_user_func('md5', uniqid());
            $this->context->cookie->contactFormTokenTTL = time() + 600;
        }

        return $this->context->cookie->contactFormToken;
    }

    protected function trans($id, array $params = [], $domain = 'Modules.Contactform.Shop', $locale = null)
    {
        try {
            return $this->translator->trans($id, $params, $domain, $locale);
        } catch (\Exception $ex) {
            return $id;
        }
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_form_content',
            [
                'label' => __('Form Fields'),
            ]
        );
        $is_admin = is_admin();

        $this->addControl(
            'subject_id',
            [
                'label' => $this->trans('Subject Heading', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
                'type' => ControlsManager::SELECT,
                'options' => $is_admin ? $this->getContactOptions() : [],
                'default' => '0',
            ]
        );

        $this->addControl(
            'show_upload',
            [
                'label' => $this->trans('Attach File', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
                'type' => $this->upload ? ControlsManager::SWITCHER : ControlsManager::HIDDEN,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_reference',
            [
                'label' => $this->trans('Order Reference', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
            ]
        );

        if ($is_admin && !\Tools::file_exists_cache(_PS_MODULE_DIR_ . 'invrecaptcha/invrecaptcha.php')) {
            $this->addControl(
                'captcha',
                [
                    'type' => ControlsManager::RAW_HTML,
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                    'raw' => '
                        <i class="eicon-close" style="position:absolute; top:8px; right:8px; cursor:pointer" onclick="$(`.elementor-control-show_captcha input`).prop(`checked`, false).change()"></i>
                        Protect your site against spam and abuse, while letting your real customers pass through with ease.
                        <a href="https://addons.prestashop.com/website-security-access/32222-spam-protection-invisible-recaptcha.html" target="_blank">
                            <i class="eicon-link"></i>Invisible reCAPTCHA
                        </a> does this all in the background without any user interaction.
                        <style>.elementor-control-captcha:not(.elementor-hidden-control) ~ .elementor-control-show_captcha { display: none }</style>
                    ',
                    'condition' => [
                        'show_captcha!' => '',
                    ],
                ]
            );

            $this->addControl(
                'show_captcha',
                [
                    'label' => 'Spam Protection - Invisible reCaptcha',
                    'type' => ControlsManager::CHOOSE,
                    'options' => [
                        'yes' => [
                            'title' => __('Learn More'),
                            'icon' => 'eicon-info-circle',
                        ],
                    ],
                    'default' => 'yes',
                ]
            );
        }

        $this->addControl(
            'input_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
                'default' => 'sm',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'show_labels',
            [
                'label' => __('Label'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
            ]
        );

        $this->addControl(
            'mark_required',
            [
                'label' => __('Required Mark'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_subject_content',
            [
                'label' => $this->trans('Subject Heading', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
                'condition' => [
                    'subject_id' => '0',
                ],
            ]
        );

        $this->addControl(
            'subject_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Subject Heading'),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'subject_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_email_content',
            [
                'label' => $this->trans('Email address', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
            ]
        );

        $this->addControl(
            'email_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Email address'),
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'email_placeholder',
            [
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('your@email.com'),
            ]
        );

        $this->addResponsiveControl(
            'email_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_reference_content',
            [
                'label' => $this->trans('Order Reference', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
                'condition' => [
                    'show_reference!' => '',
                ],
            ]
        );

        $this->addControl(
            'reference_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Order Reference'),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'reference_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_upload_content',
            [
                'label' => $this->trans('Attach File', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
                'condition' => [
                    'show_upload' => $this->upload ? 'yes' : 'hide',
                ],
            ]
        );

        $this->addControl(
            'upload_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Attach File'),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'upload_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_message_content',
            [
                'label' => $this->trans('Message', [], 'Modules.Contactform.Shop', _CE_LOCALE_),
            ]
        );

        $this->addControl(
            'message_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Message'),
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'message_placeholder',
            [
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('How can we help?'),
            ]
        );

        $this->addResponsiveControl(
            'message_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->addControl(
            'message_rows',
            [
                'label' => __('Rows'),
                'type' => ControlsManager::NUMBER,
                'default' => '4',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_content',
            [
                'label' => __('Button'),
            ]
        );

        $this->addControl(
            'button_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'primary' => __('Primary'),
                    'secondary' => __('Secondary'),
                ],
                'prefix_class' => 'elementor-button-',
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'button_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Send'),
            ]
        );

        $this->addControl(
            'button_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::CHOOSE,
                'toggle' => false,
                'options' => WidgetButton::getButtonSizes(),
                'default' => 'sm',
                'style_transfer' => true,
            ]
        );

        $this->addResponsiveControl(
            'button_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->addResponsiveControl(
            'button_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'end' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'stretch' => [
                        'title' => __('Justified'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'stretch',
                'prefix_class' => 'elementor%s-button-align-',
            ]
        );

        $this->addControl(
            'selected_icon',
            [
                'label' => __('Icon'),
                'label_block' => false,
                'type' => ControlsManager::ICONS,
                'skin' => 'inline',
                'fa4compatibility' => 'icon',
                'recommended' => [
                    'ce-icons' => [
                        'caret-right',
                        'angle-right',
                        'chevron-right',
                        'arrow-right',
                        'long-arrow-right',
                    ],
                    'fa-solid' => [
                        'right-to-bracket',
                        'arrow-right-to-bracket',
                        'right-long',
                        'arrow-right-long',
                        'arrow-right',
                        'chevron-right',
                        'caret-right',
                        'angle-right',
                        'angles-right',
                        'square-caret-right',
                        'circle-chevron-right',
                        'circle-arrow-right',
                        'circle-right',
                        'paper-plane',
                    ],
                    'fa-regular' => [
                        'square-caret-right',
                        'circle-right',
                        'paper-plane',
                    ],
                ],
            ]
        );

        $this->addControl(
            'icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-button-text' => 'flex-grow: min(0, {{SIZE}})',
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_additional_options',
            [
                'label' => __('Additional Options'),
            ]
        );

        $this->addControl(
            'custom_messages',
            [
                'label' => __('Messages'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'yes' => __('Custom'),
                ],
            ]
        );

        $this->addControl(
            'success_message',
            [
                'label' => __('Success'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Your message has been successfully sent to our team.'),
                'condition' => [
                    'custom_messages!' => '',
                ],
            ]
        );

        $this->addControl(
            'error_message',
            [
                'label' => __('Error'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('An error occurred while sending the message.'),
                'condition' => [
                    'custom_messages!' => '',
                ],
            ]
        );

        $is_admin && $this->addControl(
            'configure_module',
            [
                'label' => __('Contact Form'),
                'type' => ControlsManager::BUTTON,
                'text' => '<i class="eicon-external-link-square"></i>' . __('Configure'),
                'link' => [
                    'url' => $this->context->link->getAdminLink('AdminModules', true, [], ['configure' => 'contactform']),
                    'is_external' => true,
                ],
                'separator' => 'before',
            ]
        );

        $this->gdpr && $this->addControl(
            'configure_gdpr',
            [
                'label' => __('GDPR'),
                'type' => ControlsManager::BUTTON,
                'text' => '<i class="eicon-external-link-square"></i>' . __('Configure'),
                'link' => [
                    'url' => $this->gdpr_cfg,
                    'is_external' => true,
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_form',
            [
                'label' => __('Form'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'column_gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->addControl(
            'row_gap',
            [
                'label' => __('Rows Gap'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'heading_style_label',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Label'),
                'separator' => 'before',
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'label_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 8,
                ],
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group label' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'mark_color',
            [
                'label' => __('Mark Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-mark-required .elementor-field-label:after' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_labels!' => '',
                    'mark_required!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-field-group label',
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->gdpr && $this->addControl(
            'heading_style_checkbox',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Checkbox'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'checkbox_spacing',
            [
                'label' => __('Spacing'),
                'type' => $this->gdpr ? ControlsManager::SLIDER : ControlsManager::HIDDEN,
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'selectors' => !$this->gdpr ? [] : [
                    '{{WRAPPER}} input[type=checkbox]' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_field_style',
            [
                'label' => __('Field'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addControl(
            'field_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
            ]
        );

        $this->addControl(
            'field_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field-textual' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'button_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->startControlsTabs('tabs_button_style');

        $this->startControlsTab(
            'tab_button_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'button_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_button_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'button_hover_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_hover_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_animation',
            [
                'label' => __('Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'button_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'button_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->addResponsiveControl(
            'button_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_messages_style',
            [
                'label' => __('Messages'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'messages_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-message',
            ]
        );

        $this->addControl(
            'heading_style_success',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Success'),
            ]
        );

        $this->addControl(
            'success_message_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
                ],
            ]
        );

        $this->addControl(
            'heading_style_error',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Error'),
            ]
        );

        $this->addControl(
            'error_message_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $id = $this->getId();
        $id_contact = 0;
        $id_order = 0;
        $orders = $settings['show_reference'] && !empty($this->context->customer->id) ? \Order::getCustomerOrders($this->context->customer->id) : [];
        $show_labels = (bool) $settings['show_labels'];
        $mark_class = !empty($settings['mark_required']) ? ' elementor-mark-required' : '';
        $input_classes = 'elementor-field elementor-field-textual elementor-size-' . esc_attr($settings['input_size']);

        $this->addRenderAttribute('form', [
            'action' => $this->context->link->getModuleLink('creativeelements', 'ajax', [], null, null, null, true),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'data-success' => $settings['custom_messages'] ? $settings['success_message'] : '',
            'data-error' => $settings['custom_messages'] ? $settings['error_message'] : '',
        ]);
        $this->addRenderAttribute('email', [
            'id' => 'from-' . $id,
            'value' => isset($this->context->customer->email) ? $this->context->customer->email : '',
            'placeholder' => $settings['email_placeholder'] ?: $this->trans('your@email.com', [], 'Shop.Forms.Help'),
            'inputmode' => 'email',
        ]);
        $this->addRenderAttribute('message', [
            'id' => 'message-' . $id,
            'placeholder' => $settings['message_placeholder'] ?: $this->trans('How can we help?', [], 'Shop.Forms.Help'),
            'rows' => (int) $settings['message_rows'],
        ]);
        $this->addRenderAttribute('button', 'class', 'elementor-button elementor-size-' . $settings['button_size']);

        if ($settings['button_hover_animation']) {
            $this->addRenderAttribute('button', 'class', 'elementor-animation-' . $settings['button_hover_animation']);
        }

        if (\Tools::getIsset('token') && $id_customer_thread = (int) \Tools::getValue('id_customer_thread')) {
            $ct = new \CustomerThread($id_customer_thread);

            if (\Tools::getValue('token') === $ct->token) {
                $id_contact = $ct->id_contact;

                if ($settings['show_reference'] && $id_order = $ct->id_order) {
                    $order = new \Order($id_order);
                    $orders = [
                        [
                            'id_order' => $order->id,
                            'reference' => $order->reference,
                        ],
                    ];
                }
                $this->setRenderAttribute('email', 'value', $ct->email);
            }
        } ?>
        <form class="elementor-contact-form" <?php $this->printRenderAttributeString('form'); ?>>
            <div class="elementor-form-fields-wrapper">
                <input type="hidden" name="url">
            <?php if ($token = $this->getToken()) { ?>
                <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>">
            <?php } ?>
            <?php if ($settings['subject_id']) { ?>
                <input type="hidden" name="id_contact" value="<?php echo (int) ($id_contact ?: $settings['subject_id']); ?>">
            <?php } else { ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['subject_width']; ?> elementor-field-type-select">
                <?php if ($show_labels) { ?>
                    <label class="elementor-field-label" for="id-contact-<?php echo $id; ?>">
                        <?php echo $settings['subject_label'] ?: $this->trans('Subject Heading'); ?>
                    </label>
                <?php } ?>
                    <div class="elementor-select-wrapper">
                        <select name="id_contact" id="id-contact-<?php echo $id; ?>" class="<?php echo $input_classes; ?>">
                    <?php foreach (\Contact::getContacts($this->context->language->id) as $contact) { ?>
                        <?php if (!$id_contact || $id_contact == $contact['id_contact']) { ?>
                            <option value="<?php echo (int) $contact['id_contact']; ?>"><?php echo $contact['name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo ((int) $settings['email_width']) . $mark_class; ?> elementor-field-type-email">
                <?php if ($show_labels) { ?>
                    <label class="elementor-field-label" for="from-<?php echo $id; ?>">
                        <?php echo $settings['email_label'] ?: $this->trans('Email address'); ?>
                    </label>
                <?php } ?>
                    <input type="email" name="from" <?php $this->printRenderAttributeString('email'); ?> class="<?php echo $input_classes; ?>" required>
                </div>
            <?php if ($settings['show_reference'] && (Plugin::$instance->editor->isEditMode() || $orders)) { ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['reference_width']; ?> elementor-field-type-select">
                <?php if ($show_labels) { ?>
                    <label class="elementor-field-label" for="id-order-<?php echo $id; ?>">
                        <?php echo $settings['reference_label'] ?: $this->trans('Order Reference'); ?>
                    </label>
                <?php } ?>
                    <div class="elementor-select-wrapper">
                        <select name="id_order" id="id-order-<?php echo $id; ?>" class="<?php echo $input_classes; ?>">
                        <?php if (!$id_order) { ?>
                            <option value=""><?php echo $this->trans('Select reference'); ?></option>
                        <?php } ?>
                        <?php foreach ($orders as &$order) { ?>
                            <option value="<?php echo (int) $order['id_order']; ?>"><?php echo $order['reference']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->upload && $settings['show_upload']) { ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['upload_width']; ?> elementor-field-type-file">
                <?php if ($show_labels) { ?>
                    <label class="elementor-field-label" for="file-upload-<?php echo $id; ?>">
                        <?php echo $settings['upload_label'] ?: $this->trans('Attach File'); ?>
                    </label>
                <?php } ?>
                    <label class="elementor-row <?php echo $input_classes; ?>">
                        <input type="file" name="fileUpload" id="file-upload-<?php echo $id; ?>" accept=".txt, .rtf, .doc, .docx, .pdf, .zip, .png, .jpeg, .gif, .jpg">
                    </label>
                </div>
            <?php } ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo ((int) $settings['message_width']) . $mark_class; ?> elementor-field-type-textarea">
                <?php if ($show_labels) { ?>
                    <label class="elementor-field-label" for="message-<?php echo $id; ?>">
                        <?php echo $settings['message_label'] ?: $this->trans('Message'); ?>
                    </label>
                <?php } ?>
                    <textarea name="message" <?php $this->printRenderAttributeString('message'); ?> class="<?php echo $input_classes; ?>" required></textarea>
                </div>
            <?php if ($this->gdpr) { ?>
                <div class="elementor-field-group elementor-column elementor-col-100<?php echo $mark_class; ?> elementor-field-type-gdpr">
                    <label class="elementor-field-label">
                        <input type="checkbox" name="<?php echo $this->gdpr; ?>" value="1" required><span class="elementor-checkbox-label"><?php echo $this->gdpr_msg; ?></span>
                    </label>
                </div>
            <?php } ?>
                <div class="elementor-field-group elementor-column elementor-col-<?php echo (int) $settings['button_width']; ?> elementor-field-type-submit">
                    <button type="submit" name="submitMessage" value="Send" <?php $this->printRenderAttributeString('button'); ?>>
                        <span class="elementor-button-content-wrapper">
                        <?php if ($icon = IconsManager::getBcIcon($settings, 'icon', ['aria-hidden' => 'true'])) { ?>
                            <span class="elementor-align-icon-<?php echo esc_attr($this->getSettings('icon_align')); ?>"><?php echo $icon; ?></span>
                        <?php } ?>
                            <span class="elementor-button-text"><?php echo $settings['button_text'] ?: $this->trans('Send'); ?></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
    }

    protected function contentTemplate()
    {
        ?>
        <#
        var contacts = <?php echo json_encode(\Contact::getContacts($this->context->language->id)); ?>,
            email_placeholder = settings.email_placeholder || <?php echo json_encode($this->trans('your@email.com', [], 'Shop.Forms.Help')); ?>,
            message_placeholder = settings.message_placeholder || <?php echo json_encode($this->trans('How can we help?', [], 'Shop.Forms.Help')); ?>,
            upload = <?php echo (int) $this->upload; ?>;
            mark_class = settings.mark_required ? ' elementor-mark-required' : '';
        #>
        <form class="elementor-contact-form">
            <div class="elementor-form-fields-wrapper">
            <# if (settings.subject_id <= 0) { #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.subject_width }} elementor-field-type-select">
                <# if (settings.show_labels) { #>
                    <label class="elementor-field-label">{{ settings.subject_label || <?php echo json_encode($this->trans('Subject Heading')); ?> }}</label>
                <# } #>
                    <div class="elementor-select-wrapper">
                        <select name="id_contact" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                        <# _.each(contacts, function(contact) { #>
                            <option>{{ contact.name }}</option>
                        <# }) #>
                        </select>
                    </div>
                </div>
            <# } #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.email_width }}{{ mark_class}} elementor-field-type-email">
                <# if (settings.show_labels) { #>
                    <label class="elementor-field-label">{{ settings.email_label || <?php echo json_encode($this->trans('Email address')); ?> }}</label>
                <# } #>
                    <input type="email" name="from" placeholder="{{ email_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                </div>
            <# if (settings.show_reference) { #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.reference_width }} elementor-field-type-select">
                <# if (settings.show_labels) { #>
                    <label class="elementor-field-label">{{ settings.reference_label || <?php echo json_encode($this->trans('Order Reference')); ?> }}</label>
                <# } #>
                    <div class="elementor-select-wrapper">
                        <select name="id_order" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                            <option>{{ <?php echo json_encode($this->trans('Select reference')); ?> }}</option>
                        </select>
                    </div>
                </div>
            <# } #>
            <# if (upload && settings.show_upload) { #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.upload_width }} elementor-field-type-file">
                <# if (settings.show_labels) { #>
                    <label class="elementor-field-label">{{ settings.upload_label || <?php echo json_encode($this->trans('Attach File')); ?> }}</label>
                <# } #>
                    <label class="elementor-row elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                        <input type="file" name="fileUpload">
                    </label>
                </div>
            <# } #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.message_width }}{{ mark_class }} elementor-field-type-textarea">
                <# if (settings.show_labels) { #>
                    <label class="elementor-field-label">{{ settings.message_label || <?php echo json_encode($this->trans('Message')); ?> }}</label>
                <# } #>
                    <textarea name="message" placeholder="{{ message_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" rows="{{ settings.message_rows }}"></textarea>
                </div>
            <?php if ($this->gdpr) { ?>
                <div class="elementor-field-group elementor-column elementor-col-100{{ mark_class }} elementor-field-type-gdpr">
                    <label class="elementor-field-label">
                        <input type="checkbox"><span class="elementor-checkbox-label"><?php echo $this->gdpr_msg; ?></span>
                    </label>
                </div>
            <?php } ?>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.button_width }} elementor-field-type-submit">
                    <button type="submit" name="submitMessage" value="Send" class="elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_hover_animation }}">
                        <span class="elementor-button-content-wrapper">
                        <# if (settings.icon || settings.selected_icon.value) { #>
                            <span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
                                {{{ elementor.helpers.getBcIcon(view, settings, 'icon', {'aria-hidden': true}) }}}
                            </span>
                        <# } #>
                            <span class="elementor-button-text">{{ settings.button_text || <?php echo json_encode($this->trans('Send')); ?> }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->translator = $this->context->getTranslator();
        $this->upload = \Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD');
        $this->initGDPR();

        parent::__construct($data, $args);
    }

    protected function initGDPR()
    {
        $id_lang = $this->context->language->id;

        if (\Module::isEnabled('psgdpr') && \Module::getInstanceByName('psgdpr')
            && \GDPRConsent::getConsentActive($id_module = \Module::getModuleIdByName('contactform'))
        ) {
            $this->gdpr = 'psgdpr_consent_checkbox';
            $this->gdpr_msg = \GDPRConsent::getConsentMessage($id_module, $id_lang);
            is_admin() && $this->gdpr_cfg = $this->context->link->getAdminLink('AdminModules', true, [], [
                'configure' => 'psgdpr',
                'page' => 'dataConsent',
            ]);
        } elseif (\Module::isEnabled('gdprpro') && \Configuration::get('gdpr-pro_consent_contact_enable')) {
            $this->gdpr = 'gdpr_consent_chkbox';
            $this->gdpr_msg = \Configuration::get('gdpr-pro_consent_contact_text', $id_lang);
            is_admin() && $this->gdpr_cfg = $this->context->link->getAdminLink('AdminGdprConfig');
        }
        // Strip <p> tags from GDPR message
        $this->gdpr_msg && $this->gdpr_msg = preg_replace('`</?p\b.*?>`i', '', $this->gdpr_msg);
    }
}
