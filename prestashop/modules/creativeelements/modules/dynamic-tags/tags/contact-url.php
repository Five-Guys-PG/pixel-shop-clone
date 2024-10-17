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

use CE\CoreXDynamicTagsXDataTag as DataTag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXContactURL extends DataTag
{
    public function getName()
    {
        return 'contact-url';
    }

    public function getTitle()
    {
        return __('Contact URL');
    }

    public function getGroup()
    {
        return Module::ACTION_GROUP;
    }

    public function getCategories()
    {
        return [Module::URL_CATEGORY];
    }

    public function getPanelTemplateSettingKey()
    {
        return 'link_type';
    }

    protected function _registerControls()
    {
        $this->addControl(
            'link_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Select...'),
                    'email' => __('Email'),
                    'tel' => __('Tel'),
                    'sms' => __('SMS'),
                    'whatsapp' => __('WhatsApp'),
                    'skype' => __('Skype'),
                    'messenger' => __('Messenger'),
                    'viber' => __('Viber'),
                    'waze' => __('Waze'),
                    'google_calendar' => __('Google Calendar'),
                    'outlook_calendar' => __('Outlook Calendar'),
                    'yahoo_calendar' => __('Yahoo Calendar'),
                ],
            ]
        );

        $this->addControl(
            'mail_to',
            [
                'label' => __('Email'),
                'type' => ControlsManager::TEXT,
                'condition' => [
                    'link_type' => 'email',
                ],
            ]
        );

        $this->addControl(
            'mail_subject',
            [
                'label' => __('Subject'),
                'type' => ControlsManager::TEXT,
                'label_block' => 'true',
                'condition' => [
                    'link_type' => 'email',
                ],
            ]
        );

        $this->addControl(
            'mail_body',
            [
                'label' => __('Message'),
                'type' => ControlsManager::TEXTAREA,
                'label_block' => 'true',
                'condition' => [
                    'link_type' => 'email',
                ],
            ]
        );

        $this->addControl(
            'tel_number',
            [
                'label' => __('Number'),
                'type' => ControlsManager::TEXT,
                'condition' => [
                    'link_type' => [
                        'tel',
                        'sms',
                        'whatsapp',
                        'viber',
                    ],
                ],
            ]
        );

        $this->addControl(
            'username',
            [
                'label' => __('Username'),
                'type' => ControlsManager::TEXT,
                'condition' => [
                    'link_type' => ['skype', 'messenger'],
                ],
            ]
        );

        $this->addControl(
            'viber_action',
            [
                'label' => __('Action'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'contact' => __('Contact'),
                    'add' => __('Add'),
                ],
                'default' => 'contact',
                'condition' => [
                    'link_type' => 'viber',
                ],
            ]
        );

        $this->addControl(
            'skype_action',
            [
                'label' => __('Action'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'call' => __('Call'),
                    'chat' => __('Chat'),
                    'userinfo' => __('Show Profile'),
                    'add' => __('Add to Contacts'),
                    'voicemail' => __('Send Voice Mail'),
                ],
                'default' => 'call',
                'condition' => [
                    'link_type' => 'skype',
                ],
            ]
        );

        $this->addControl(
            'waze_address',
            [
                'label' => __('Location'),
                'type' => ControlsManager::TEXT,
                'label_block' => 'true',
                'condition' => [
                    'link_type' => 'waze',
                ],
            ]
        );

        $this->addControl(
            'event_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'label_block' => 'true',
                'condition' => [
                    'link_type' => [
                        'google_calendar',
                        'outlook_calendar',
                        'yahoo_calendar',
                    ],
                ],
            ]
        );

        $this->addControl(
            'event_description',
            [
                'label' => __('Description'),
                'type' => ControlsManager::TEXTAREA,
                'condition' => [
                    'link_type' => [
                        'google_calendar',
                        'outlook_calendar',
                        'yahoo_calendar',
                    ],
                ],
            ]
        );

        $this->addControl(
            'event_location',
            [
                'label' => __('Location'),
                'type' => ControlsManager::TEXT,
                'label_block' => 'true',
                'condition' => [
                    'link_type' => [
                        'google_calendar',
                        'outlook_calendar',
                        'yahoo_calendar',
                    ],
                ],
            ]
        );

        $this->addControl(
            'event_start_date',
            [
                'label' => __('Start'),
                'type' => ControlsManager::DATE_TIME,
                'condition' => [
                    'link_type' => [
                        'google_calendar',
                        'outlook_calendar',
                        'yahoo_calendar',
                    ],
                ],
            ]
        );

        $this->addControl(
            'event_end_date',
            [
                'label' => __('End'),
                'type' => ControlsManager::DATE_TIME,
                'condition' => [
                    'link_type' => [
                        'google_calendar',
                        'outlook_calendar',
                        'yahoo_calendar',
                    ],
                ],
            ]
        );
    }

    private function buildMailToLink($settings)
    {
        if (empty($settings['mail_to'])) {
            return '';
        }

        $link = 'mailto:' . $settings['mail_to'] . '?';

        $build_parts = [];

        if (!empty($settings['mail_subject'])) {
            $build_parts['subject'] = $this->escapeSpaceInUrl($settings['mail_subject']);
        }

        if (!empty($settings['mail_body'])) {
            $build_parts['body'] = $this->escapeSpaceInUrl($settings['mail_body']);
        }

        return add_query_arg($build_parts, $link);
    }

    private function buildSmsLink($settings)
    {
        if (empty($settings['tel_number'])) {
            return '';
        }

        $value = 'sms:' . $settings['tel_number'];

        return $value;
    }

    private function buildWhatsappLink($settings)
    {
        if (empty($settings['tel_number'])) {
            return '';
        }

        return 'https://api.whatsapp.com/send?phone=' . $settings['tel_number'];
    }

    private function buildSkypeLink($settings)
    {
        if (empty($settings['username'])) {
            return '';
        }

        $action = 'call';
        if (!empty($settings['skype_action'])) {
            $action = $settings['skype_action'];
        }
        $link = 'skype:' . $settings['username'] . '?' . $action;

        return $link;
    }

    private function buildWazeLink($settings)
    {
        $link = 'https://waze.com/ul?';

        $build_parts = [
            'q' => $settings['waze_address'],
            'z' => 10,
            'navigate' => 'yes',
        ];

        return add_query_arg($build_parts, $link);
    }

    private function dateToIso($date, $all_day = false)
    {
        $time = strtotime($date);
        if ($all_day) {
            return date('Ymd\/Ymd', $time);
        }

        return date('Ymd\THis', $time);
    }

    private function dateToIcs($date)
    {
        $time = strtotime($date);

        return date('Y-m-d\Th:i:s', $time);
    }

    private function escapeSpaceInUrl($url)
    {
        return str_replace(' ', '%20', $url);
    }

    private function buildGoogleCalendarLink($settings)
    {
        $dates = '';
        if (!empty($settings['event_start_date'])) {
            if (empty($settings['event_end_date'])) {
                $dates = $this->dateToIso($settings['event_start_date'], true);
            } else {
                $dates = $this->dateToIso($settings['event_start_date']) . '/' . $this->dateToIso($settings['event_end_date']);
            }
        }
        $link = 'https://www.google.com/calendar/render?action=TEMPLATE&';
        $build_parts = [
            'text' => empty($settings['event_title']) ? '' : $this->escapeSpaceInUrl($settings['event_title']),
            'details' => empty($settings['event_description']) ? '' : $this->escapeSpaceInUrl($settings['event_description']),
            'dates' => $dates,
            'location' => empty($settings['event_location']) ? '' : $this->escapeSpaceInUrl($settings['event_location']),
        ];

        return add_query_arg($build_parts, $link);
    }

    private function buildOutlookCalendarLink($settings)
    {
        $link = 'https://outlook.office.com/owa/?path=/calendar/action/compose&';
        $build_parts = [
            'subject' => empty($settings['event_title']) ? '' : urlencode($settings['event_title']),
            'body' => empty($settings['event_description']) ? '' : urlencode($settings['event_description']),
            'location' => empty($settings['event_location']) ? '' : urlencode($settings['event_location']),
        ];

        if (!empty($settings['event_start_date'])) {
            $build_parts['startdt'] = urlencode($this->dateToIcs($settings['event_start_date']));
        }

        if (!empty($settings['event_end_date'])) {
            $build_parts['enddt'] = urlencode($this->dateToIcs($settings['event_end_date']));
        }

        return add_query_arg($build_parts, $link);
    }

    private function buildMessengerLink($settings)
    {
        if (empty($settings['username'])) {
            return '';
        }

        return 'https://m.me/' . $settings['username'];
    }

    private function buildYahooCalendarLink($settings)
    {
        $link = 'https://calendar.yahoo.com/?v=60&view=d&type=20';
        $build_parts = [
            'title' => empty($settings['event_title']) ? '' : urlencode($settings['event_title']),
            'desc' => empty($settings['event_description']) ? '' : urlencode($settings['event_description']),
            'in_loc' => empty($settings['event_location']) ? '' : urlencode($settings['event_location']),
        ];

        if (!empty($settings['event_start_date'])) {
            $build_parts['st'] = urlencode(date('Ymd\This', strtotime($settings['event_start_date'])));
        }

        if (!empty($settings['event_end_date'])) {
            $build_parts['et'] = urlencode(date('Ymd\This', strtotime($settings['event_end_date'])));
        }

        return add_query_arg($build_parts, $link);
    }

    public function buildViberLink($settings)
    {
        if (empty($settings['tel_number'])) {
            return '';
        }
        $action = 'contact';
        if (!empty($settings['viber_action'])) {
            $action = $settings['viber_action'];
        }

        return add_query_arg([
            'number' => urlencode($settings['tel_number']),
        ], 'viber://' . $action);
    }

    public function getValue(array $options = [])
    {
        $settings = $this->getSettings();

        if (empty($settings['link_type'])) {
            return;
        }

        $value = '';
        switch ($settings['link_type']) {
            case 'email':
                $value = $this->buildMailToLink($settings);
                break;
            case 'tel':
                $value = empty($settings['tel_number']) ? '' : 'tel:' . $settings['tel_number'];
                break;
            case 'sms':
                $value = $this->buildSmsLink($settings);
                break;
            case 'messenger':
                $value = $this->buildMessengerLink($settings);
                break;
            case 'whatsapp':
                $value = $this->buildWhatsappLink($settings);
                break;
            case 'skype':
                $value = $this->buildSkypeLink($settings);
                break;
            case 'waze':
                $value = $this->buildWazeLink($settings);
                break;
            case 'google_calendar':
                $value = $this->buildGoogleCalendarLink($settings);
                break;
            case 'outlook_calendar':
                $value = $this->buildOutlookCalendarLink($settings);
                break;
            case 'yahoo_calendar':
                $value = $this->buildYahooCalendarLink($settings);
                break;
            case 'viber':
                $value = $this->buildViberLink($settings);
                break;
        }

        return $value;
    }
}
