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

/**
 * Elementor animation control.
 *
 * A base control for creating entrance animation control. Displays a select box
 * with the available entrance animation effects @see ControlAnimation::getAnimations() .
 *
 * @since 1.0.0
 */
class ControlAnimation extends BaseDataControl
{
    /**
     * Get control type.
     *
     * Retrieve the animation control type.
     *
     * @since 1.0.0
     *
     * @return string Control type
     */
    public function getType()
    {
        return 'animation';
    }

    /**
     * Retrieve default control settings.
     *
     * Get the default settings of the control. Used to return the default
     * settings while initializing the control.
     *
     * @since 2.5.0
     *
     * @return array Control default settings
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
            'render_type' => 'none',
            'exclude' => [],
        ];
    }

    /**
     * Get animations list.
     *
     * Retrieve the list of all the available animations.
     *
     * @since 1.0.0
     * @static
     *
     * @return array Control type
     */
    public static function getAnimations()
    {
        $animations = [
            'Fading' => [
                'fadeIn' => 'Fade In',
                'fadeInDown' => 'Fade In Down',
                'fadeInLeft' => 'Fade In Left',
                'fadeInRight' => 'Fade In Right',
                'fadeInUp' => 'Fade In Up',
            ],
            'Revealing' => [
                'reveal' => 'Reveal',
                'revealFromDown' => 'Reveal from Down',
                'revealFromLeft' => 'Reveal from Left',
                'revealFromRight' => 'Reveal from Right',
                'revealFromUp' => 'Reveal from Up',
            ],
            'Sliding & Revealing' => [
                'slideRevealFromDown' => 'Slide & Reveal from Down',
                'slideRevealFromLeft' => 'Slide & Reveal from Left',
                'slideRevealFromRight' => 'Slide & Reveal from Right',
                'slideRevealFromUp' => 'Slide & Reveal from Up',
            ],
            'Scaling & Revealing' => [
                'scaleReveal' => 'Scale & Reveal',
                'scaleRevealFromDown' => 'Scale & Reveal from Down',
                'scaleRevealFromLeft' => 'Scale & Reveal from Left',
                'scaleRevealFromRight' => 'Scale & Reveal from Right',
                'scaleRevealFromUp' => 'Scale & Reveal from Up',
            ],
            'Zooming' => [
                'zoomIn' => 'Zoom In',
                'zoomInDown' => 'Zoom In Down',
                'zoomInLeft' => 'Zoom In Left',
                'zoomInRight' => 'Zoom In Right',
                'zoomInUp' => 'Zoom In Up',
            ],
            'Bouncing' => [
                'bounceIn' => 'Bounce In',
                'bounceInDown' => 'Bounce In Down',
                'bounceInLeft' => 'Bounce In Left',
                'bounceInRight' => 'Bounce In Right',
                'bounceInUp' => 'Bounce In Up',
            ],
            'Sliding' => [
                'slideInDown' => 'Slide In Down',
                'slideInLeft' => 'Slide In Left',
                'slideInRight' => 'Slide In Right',
                'slideInUp' => 'Slide In Up',
            ],
            'Rotating' => [
                'rotateIn' => 'Rotate In',
                'rotateInDownLeft' => 'Rotate In Down Left',
                'rotateInDownRight' => 'Rotate In Down Right',
                'rotateInUpLeft' => 'Rotate In Up Left',
                'rotateInUpRight' => 'Rotate In Up Right',
            ],
            'Attention Seekers' => [
                'bounce' => 'Bounce',
                'flash' => 'Flash',
                'pulse' => 'Pulse',
                'rubberBand' => 'Rubber Band',
                'shake' => 'Shake',
                'headShake' => 'Head Shake',
                'swing' => 'Swing',
                'tada' => 'Tada',
                'wobble' => 'Wobble',
                'jello' => 'Jello',
            ],
            'Specials' => [
                'rollIn' => 'Roll In',
            ],
        ];

        /*
         * Element appearance animations list.
         *
         * @since 2.4.0
         *
         * @param array $additional_animations Additional Animations array
         */
        $additional_animations = apply_filters('elementor/controls/animations/additional_animations', []);

        return array_merge($animations, $additional_animations);
    }

    /**
     * Render animations control template.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     */
    public function contentTemplate()
    {
        $control_uid = $this->getControlUid(); ?>
        <# var animations = <?php echo json_encode(static::getAnimations()); ?>; #>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo $control_uid; ?>" data-setting="{{ data.name }}">
                    <option value=""><?php _e('Default'); ?></option>
                    <option value="none"><?php _e('None'); ?></option>
                <# for (var group_name in animations) { #>
                    <# if (~data.exclude.indexOf(group_name)) continue; #>
                    <optgroup label="{{ group_name }}">
                    <# for (var animation_name in animations[group_name]) { #>
                        <option value="{{ animation_name }}">{{{ animations[group_name][animation_name] }}}</option>
                    <# } #>
                    </optgroup>
                <# } #>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
