<?php
/**
 * Plugin Name:     Honeypot for WP comment
 * Plugin URI:      https://github.com/prasidhda/honeypot-wp-comment
 * Description:     Simple plugin to trap the spam comments using honey pot technique.
 * Author:          Prasidhda Malla
 * Author URI:      https://profiles.wordpress.org/prasidhda
 * Text Domain:     honeypot-wp-comment
 * Domain Path:     /languages
 * Version:         1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Function to render the Trap HTML elements in comment form
 * To change the input name, copy this whole function to your theme and override there and make sure to update the name
 * in hpwpc_check_honeypot() below as well
 * However It is recommended to use simple and common names as "email, phone, name, etc" and as well as the ID
 */
if (!function_exists('hpwpc_render_honey_pot')) {
    function hpwpc_render_honey_pot() {
        ob_start(); ?>
        <style>
            .hpwc {
                opacity: 0;
                position: absolute;
                top: 0;
                left: 0;
                height: 0;
                width: 0;
                z-index: -1;
            }
        </style>
        <label class="hpwc" for="phone"></label>
        <input class="hpwc" autocomplete="off" type="text" id="phone" name="phone"
               placeholder="<?php _e('Enter your Phone', 'honeypot-wp-comment'); ?>">
        <label class="hpwc" for="confirm-email"></label>
        <input class="hpwc" autocomplete="off" type="email" id="confirm-email" name="confirm-email"
               placeholder="<?php _e('Confirm your Email', 'honeypot-wp-comment'); ?>">

        <?php
        echo ob_get_clean();
    }
}
add_action('comment_form', 'hpwpc_render_honey_pot');

/**
 * Function to check the honey pot value
 * If any of the "honeypot" fields came filled. If yes, congrats, you trapped a spam.
 */
if (!function_exists('hpwpc_check_honeypot')) {
    function hpwpc_check_honeypot($approved) {

        return empty($_POST['phone']) || empty($_POST['confirm-email']) ? $approved : 'spam';

    }
}

add_filter('pre_comment_approved', 'hpwpc_check_honeypot', 9999, 1);

