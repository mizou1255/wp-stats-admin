<?php
/**
 * Plugin Name: WP Stats Admin
 * Description: A WordPress plugin to display an admin panel with all stats using Vue.js 3.
 * Version: 1.0
 * Author: Moez
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
define( 'WPSA_VERSION', '1.0.0' );
define( 'WPSA_PLUGIN', __FILE__ );
define( 'WPSA_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'WPSA_PLUGIN_PATH', wp_normalize_path( plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR ) );

require_once WPSA_PLUGIN_PATH . 'includes/class-wp-stats-admin.php';
require_once WPSA_PLUGIN_PATH . 'includes/class-wp-stats-cpt.php';
require_once WPSA_PLUGIN_PATH . 'includes/class-wp-stats-media.php';
require_once WPSA_PLUGIN_PATH . 'includes/class-wp-stats-users.php';
require_once WPSA_PLUGIN_PATH . 'includes/class-wp-stats-comments.php';
require_once WPSA_PLUGIN_PATH . 'includes/class-wp-stats-updates.php';


add_action('plugins_loaded', function() {
    new WP_Stats_Admin();
});
