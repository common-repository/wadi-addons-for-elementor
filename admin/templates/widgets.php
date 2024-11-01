<?php

if (! defined('ABSPATH')) {
    exit;
}

use WadiAddons\Admin\Admin_Helper;
use WadiAddons\Includes\WadiHelpers;

$elements = Admin_Helper::get_wadi_elements_list();

$used_widgets = Admin_Helper::wadi_used_widgets();

// Get elements settings
$enabled_elements = Admin_Helper::get_wadi_enabled_elements();

$global_btn  = get_option( 'wadi_elements_btn_value', 'true' );
$enable_btn  = 'true' === $global_btn ? 'active' : '';
$disable_btn = 'true' === $global_btn ? '' : 'active';


?>
<div class='wadi_addons_elements_control_container'>
    <div class="wadi_elements_wrapper">
        
        <form action="" id="wadi_elements_settings" method="post" name="wadi-elements-settings" class="wadi-elements">
            <div class="wadi_elements_info">
                <div class="wadi_section_info">
                    <h4><?php echo esc_attr(__('Wadi Elements Status', 'wadi-addons')); ?></h4>
                    <p><?php echo esc_attr(__('Use this to Enable or Disable All Elements on single click.', 'wadi-addons')); ?></p>
                </div>

                <div class="wadi_elements_status">
                    <button type="button" class="wadi_button wadi_button-enable <?php echo esc_attr($enable_btn); ?>"><?php echo esc_attr(__('Enable Elements', 'wadi-addons')); ?></button>
                    <button type="button" class="wadi_button wadi_button-disable <?php echo esc_attr($disable_btn); ?>"><?php echo esc_attr(__('Disable Elements', 'wadi-addons')); ?></button>
                    <?php if ($used_widgets) { ?>
                        <button type="button" class="wadi_button-unused"><?php echo esc_attr(__('Disable Unused Widgets', 'wadi-addons')); ?></button>
                    <?php } ?>	
                </div>
            </div>
            <div class="wadi_elements_items_settings">


                <div class="wadi_element_single">            
                    <?php
                            foreach ($elements as $key => $cat) :
        
                                ?>
                                
                                <div id="wadi-elements-tab-<?php echo wp_kses_post($key); ?>" class="wadi-switchers-container">
                                <h3 class="wadi-elements-tab-title"><?php echo wp_kses_post(__($cat['title'])); ?></h3>
                                <div class="wadi-switchers">
                                <?php
                                    foreach ($cat['elements'] as $index => $elem) {
                                        
                                        if(Admin_Helper::check_wadi_addons_is_free()) {
                                           $status = (isset( $elem['is_pro']) && true === $elem['is_pro']) ? 'disabled' : checked( 1, $enabled_elements[ $elem['key'] ], false );
                                        } else {
                                            $status =  (isset( $elem['is_pro']) && false === $elem['is_pro'] && !$elem['is_checked']) ? 'disabled' : checked( 1, $enabled_elements[ $elem['key'] ], false );
                                        }
								        $class = (isset( $elem['is_pro'] ) && false === $elem['is_pro']) ? 'pro_' : '';
                                        $switcher_class = $class . 'wadi_addon_switcher round';


								    ?>
                                        <div class="wadi_element_item">
                                            <?php if(isset($elem['widget_icon'])) { ?>
                                                <div class="wadi_widget_icon">
                                                    <i class="wadi_icon_widget wadi-<?php echo wp_kses_post($elem['widget_icon']); ?>"></i>
                                                </div>
                                            <?php } ?>
                                            <div class="wadi_element_info">
                                                <div class="wadi_element_meta_wrap">
                                                    <div class="wadi_element_name">
                                                        <?php echo wp_kses_post($elem['title']); ?>
                                                    <span class="wadi_total_use" title="Total Use">
                                                    <?php
                                                    if ( ! isset( $elem['is_global'] ) && is_array( $used_widgets ) ) {
                                                        echo esc_html__( in_array( $elem['name'], array_keys( $used_widgets ) ) ? '(' . $used_widgets[ $elem['name'] ] . ')' : '(0)' );}
                                                    ?>
                                                    </span>
                                                        <?php if ( isset( $elem['is_pro'] ) ) : ?>
                                                            <span class="wadi_addon_pro_element"><?php echo __( 'pro', 'wadi-addons' ); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                        <div class="wadi_meta_data">
                                                            <?php if ( isset( $elem['demo'] ) ) : ?>
                                                                <a class="wadi_element_link" href="<?php echo esc_url( $elem['demo'] ); ?>" target="_blank">
                                                                    <?php echo esc_attr(__( 'Live Demo', 'wadi-addons' )); ?>
                                                                    <span class="wadi_element_link_separator"></span>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if ( isset( $elem['doc'] ) ) : ?>
                                                                <a class="wadi_element_link" href="<?php echo esc_url( $elem['doc'] ); ?>" target="_blank">
                                                                    <?php echo esc_attr(__( 'Docs', 'wadi-addons' )); ?>
                                                                    <?php if ( isset( $elem['tutorial'] ) ) : ?>
                                                                        <span class="wadi_element_link_separator"></span>
                                                                    <?php endif; ?>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if ( isset( $elem['tutorial'] ) ) : ?>
                                                                <a class="wadi_element_link" href="<?php echo esc_url( $elem['tutorial'] ); ?>" target="_blank">
                                                                    <?php echo esc_attr(__( 'Video Tutorial', 'wadi-addons' )); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                </div>
                                            </div>
                                            <label class="switch wadi-switcher">
                                                <input type="checkbox" id="<?php echo esc_attr( $elem['key'] ); ?>" name="<?php echo esc_attr( $elem['key'] ); ?>" <?php echo wp_kses_post($status); ?>>
                                                <span class="<?php echo esc_attr( $switcher_class ); ?>"></span>
                                            </label>
                                        </div>

                                    <?php
                                    }
                                    
                                ?>
                        </div>
                                </div>
                                
                                <?php
                            endforeach;
                            ?>
                    </div>
                </div>
        </form>

    </div>
</div>