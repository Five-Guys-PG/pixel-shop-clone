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

use CE\CoreXBaseXModule as BaseModule;

class ModulesXPremiumXModule extends BaseModule
{
    public function getName()
    {
        return 'premium';
    }

    public function registerDocuments($documents)
    {
        $documents->registerDocumentType('content', 'CE\ModulesXPremiumXDocumentsXContent');
    }

    public function registerWidgets($widgets_manager)
    {
        foreach ([
            'AnimatedHeadline',
            'LayerSlider',
            'CallToAction',
            'FlipBox',
            'ImageHotspot',
            'ContactForm',
            'EmailSubscription',
            'Countdown',
            'TestimonialCarousel',
            'TrustedshopsReviews',
            'FacebookPage',
            'FacebookButton',
            'Template',
            'ImageSlider',
            'Module',
        ] as $class_suffix) {
            $widget_class = 'CE\ModulesXPremiumXWidgetsX' . $class_suffix;

            $widgets_manager->registerWidgetType(new $widget_class());
        }
    }

    public function __construct()
    {
        add_action('elementor/documents/register', [$this, 'registerDocuments']);
        add_action('elementor/widgets/widgets_registered', [$this, 'registerWidgets']);
    }
}
