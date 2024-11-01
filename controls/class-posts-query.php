<?php

namespace WadiAddons\Controls;

use Elementor\Base_Data_Control;
use UltimateElementor\Modules\QueryPost\Module;
if (! defined('ABSPATH')) {
    exit;
}
class Posts_Query extends Base_Data_Control
{
    const CONTROL_ID = 'wadi-posts-items';

    public function get_type()
    {
        return self::CONTROL_ID;
    }

    protected function get_default_settings()
    {
        return array(
            'label_block' => true,
            'multiple'    => false,
            'options'     => array(),
            'post_type'   => 'all',
        );
    }

    public function get_default_value()
    {
    }

    public function content_template()
    {
        $control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
						var value = data.controlValue;
						if ( typeof value == 'string' ) {
							var selected = ( option_value === value ) ? 'selected' : '';
						} else if ( null !== value ) {
							var value = _.values( value );
							var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
						}
						#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
    }

    public function enqueue()
    {
        wp_register_script('wadi_query_posts_control', WADI_ADDONS_URL . 'assets/dist/posts-editor.js', array( 'jquery' ), '1.0.0', false);
        wp_enqueue_script('wadi_query_posts_control');

        wp_localize_script(
            'wadi_query_posts_control',
            'wadi_addons_query_script',
            array(
                'get_post_nonce'    => wp_create_nonce('wadi-addons-post-nonce'),
                'get_post_by_query' => wp_create_nonce('wadi-addons-post-query'),
            )
        );
    }
}
