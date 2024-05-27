<?php
if (!class_exists('WP_Stats_Admin')) {
    class WP_Stats_Admin {

        public function __construct() {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('rest_api_init', array($this, 'register_api_endpoints'));
        }

        public function add_admin_menu() {
            add_menu_page(
                'WP Stats Admin',
                'WP Stats Admin',
                'manage_options',
                'wp-stats-admin',
                array($this, 'admin_page'),
                WPSA_PLUGIN_URI . 'assets/img/icon.png'
            );
        }

        public function enqueue_scripts($hook) {
            if ($hook !== 'toplevel_page_wp-stats-admin') {
                return;
            }

            
                wp_enqueue_style('wp-stats-admin-app-css', WPSA_PLUGIN_URI . 'assets/dist/app.min.css');
                wp_enqueue_style('wp-stats-admin-style', WPSA_PLUGIN_URI . 'assets/dist/style.min.css');
            

            wp_enqueue_script('vuejs',  WPSA_PLUGIN_URI . 'assets/vue.js', array(), WPSA_VERSION, true);
            wp_enqueue_script('wp-stats-admin', WPSA_PLUGIN_URI . 'assets/dist/app.min.js', array(), WPSA_VERSION, true);
            

            wp_localize_script('wp-stats-admin', 'vuejsAdminPanel', array(
                'nonce' => wp_create_nonce('wp_rest')
            ));
        }

        public function admin_page() {
            ?>
            <div id="wp-stats-admin-app"></div>
            <?php
        }

        public function register_api_endpoints() {
            register_rest_route('wp-stats-admin/v1', '/data', array(
                'methods' => 'GET',
                'callback' => array('WP_Stats_CPT', 'get_data_post_types'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            ));
            register_rest_route('wp-stats-admin/v1', '/media-stats', array(
                'methods' => 'GET',
                'callback' => array('WP_Stats_Media', 'get_media_stats'),
                'permission_callback' => function () {
                    return true;
                }
            ));
            register_rest_route('wp-stats-admin/v1', '/user-stats', [
                'methods' => 'GET',
                'callback' => array('WP_Stats_Users', 'get_user_stats'),
                'permission_callback' => function () {
                    return true;
                }
            ]);
            register_rest_route('wp-stats-admin/v1', '/comment-stats', [
                'methods' => 'GET',
                'callback' => array('WP_Stats_Comments', 'get_comment_stats'),
                'permission_callback' => function () {
                    return true;
                }
            ]);
            register_rest_route('wp-stats-admin/v1', '/update-stats', [
                'methods' => 'GET',
                'callback' => array('WP_Stats_Updates', 'get_update_stats'),
                'permission_callback' => function () {
                    return true;
                }
            ]);
        }
    }
}
?>
