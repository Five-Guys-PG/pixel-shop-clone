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

use CE\CoreXDynamicTagsXTag as Tag;
use CE\ModulesXDynamicTagsXModule as Module;

class ModulesXDynamicTagsXTagsXCurrentDateTime extends Tag
{
    public function getName()
    {
        return 'current-date-time';
    }

    public function getTitle()
    {
        return __('Current Date Time');
    }

    public function getGroup()
    {
        return Module::SITE_GROUP;
    }

    public function getCategories()
    {
        return [Module::TEXT_CATEGORY];
    }

    protected function _registerControls()
    {
        $this->addControl(
            'date_format',
            [
                'label' => __('Date Format'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    '' => __('None'),
                    'F j, Y' => date('F j, Y'),
                    'Y-m-d' => date('Y-m-d'),
                    'm/d/Y' => date('m/d/Y'),
                    'd/m/Y' => date('d/m/Y'),
                    'custom' => __('Custom'),
                ],
                'default' => 'default',
            ]
        );

        $this->addControl(
            'time_format',
            [
                'label' => __('Time Format'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    '' => __('None'),
                    'g:i a' => date('g:i a'),
                    'g:i A' => date('g:i A'),
                    'H:i' => date('H:i'),
                ],
                'default' => 'default',
                'condition' => [
                    'date_format!' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'custom_format',
            [
                'label' => __('Custom Format'),
                'default' => \Context::getContext()->language->date_format_full,
                'description' => sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    'https://www.php.net/manual/datetime.format.php#refsect1-datetime.format-parameters',
                    __('Documentation on date and time formatting')
                ),
                'condition' => [
                    'date_format' => 'custom',
                ],
            ]
        );
    }

    public function render()
    {
        $settings = $this->getSettings();

        if ('custom' === $settings['date_format']) {
            $format = $settings['custom_format'];
        } else {
            $language = \Context::getContext()->language;
            $date_format = $settings['date_format'];
            $time_format = $settings['time_format'];
            $format = '';

            if ('default' === $date_format) {
                $date_format = $language->date_format_lite;
            }
            if ('default' === $time_format) {
                $time_format = substr($language->date_format_full, strlen($language->date_format_lite));
            }

            if ($date_format) {
                $format = $date_format;
                $has_date = true;
            } else {
                $has_date = false;
            }

            if ($time_format) {
                if ($has_date) {
                    $format .= ' ';
                }
                $format .= $time_format;
            }
        }
        echo date($format);
    }
}
