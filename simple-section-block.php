<?php
/**
 * Plugin Name:       Simple Section Gutenberg Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       A Simple Section Gutenberg Plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-section-block
 * Domain Path:       /languages
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Load the library
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'plugins_loaded', 'crb_load' );

// Load plugin files
add_action( 'enqueue_block_assets', function( $hook ){
    wp_enqueue_style(
        'simple-section-block-css',
        plugins_url( 'assets/css/style.css', __FILE__ )
    );
} );

// Block contents
function crb_attach_plugin_options() {
    Block::make( __( 'Simple Section Block Options', 'simple-section-block' ) )
        // ->set_description( __( 'A Simple Section Block Options', 'simple-section-block' ) )
        // ->set_category( 'simple_section', _( 'Simple Section Block', 'admin-media' ) )
        // ->set_keywords( [ __( 'simple section', 'gutenberg block' ) ] )
        ->add_fields( 
            [
                // Left Contents
                Field::make( 'separator', 'simple_block_left_section', __( 'Left Contents', 'simple-section-block' ) ),
                Field::make( 'image', 'simple_block_top_icon', __( 'Upload The Icon Image', 'simple-section-block' ) ),
                Field::make( 'text', 'simple_block_sec_title', __( 'Section Title', 'simple-section-block' ) ),
                Field::make( 'textarea', 'simple_block_short_text', __( 'Short Brief', 'simple-section-block' ) ),
                Field::make( 'text', 'simple_block_btn_text', __( 'Button Text', 'simple-section-block' ) ),
                Field::make( 'text', 'simple_block_btn_url', __( 'Button URL', 'simple-section-block' ) ),
                Field::make( 'checkbox', 'simple_block_btn_show', __( 'Show/Hide Button Icon', 'simple-section-block' ) )->set_option_value( 'yes' ),

                // Right Contents
                Field::make( 'separator', 'simple_block_right_section', __( 'Right Contents', 'simple-section-block' ) ),
                Field::make( 'image', 'simple_block_right_image', __( 'Upload The Right Image', 'simple-section-block' ) ),
    
            ]
        )->set_render_callback( function( $fields, $attributes, $inner_blocks ) {
        ?>
            <div class="simple-section-block">
                <!-- Left contents -->
                <div class="left-content">
                    <div class="block-image">
                        <?php
                            if ( !empty($fields['simple_block_top_icon']) ) {
                                echo wp_get_attachment_image( $fields['simple_block_top_icon'], 'full' );
                            }
                        ?>
                    </div>
                    <div class="block-header">
                        <?php
                            if ( !empty($fields['simple_block_sec_title']) ) {
                                echo '<h1>'.esc_html( $fields['simple_block_sec_title'] ).'</h1>';
                            }
                        ?>
                    </div>
                    <div class="block-description">
                        <?php
                            if ( !empty($fields['simple_block_short_text']) ) {
                                echo '<p>'.wp_kses_post( nl2br($fields['simple_block_short_text']) ).'</p>';
                            }
                        ?>
                    </div>
                    <div class="block-button">
                        <?php
                            if ( !empty($fields['simple_block_btn_text']) ) {
                                $btn_url = $fields['simple_block_btn_url'] ? $fields['simple_block_btn_url'] : '#';
                                $btn_class = $fields['simple_block_btn_show'] == 'yes' ? 'show-icon' : 'no-icon';
                                echo '<a href="'.esc_url( $btn_url ).'" class="'.esc_attr($btn_class).'">'.esc_html( $fields['simple_block_btn_text'] ).'</a>';
                            }
                        ?>
                    </div>
                </div>

                <!-- Right contents -->
                <div class="right-content">
                    <div class="block-image">
                        <?php
                            if ( !empty($fields['simple_block_right_image']) ) {
                                echo wp_get_attachment_image( $fields['simple_block_right_image'], 'full' );
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php
        });
}
add_action( 'carbon_fields_register_fields', 'crb_attach_plugin_options' );
        
