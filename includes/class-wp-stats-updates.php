<?php
if (!class_exists('WP_Stats_Updates')) {
    class WP_Stats_Updates {

        public static function get_update_stats() {

            require_once ABSPATH . 'wp-admin/includes/update.php';

            $core_updates = get_core_updates(); 
            $theme_updates = get_theme_updates();
            $plugin_updates = get_plugin_updates();

            $core_count = 0;
            $current_version = get_bloginfo('version');
            $new_version = get_bloginfo('version');
            if (!empty($core_updates)) {
                foreach ($core_updates as $update) {
                    if ($update->response !== 'latest') {
                        $core_count = 1;
                        $current_version = $update->current;
                        $new_version = $update->version;
                    }
                }
            }
            $total_theme_updates = count($theme_updates);
            $total_plugin_updates = count($plugin_updates);

            $total_updates = $core_count + $total_theme_updates + $total_plugin_updates;

            return [
                'totalUpdates' => $total_updates,
                'core' => $core_count,
                'current_version' => $current_version,
                'new_version' => $new_version,
                'themes' => $total_theme_updates,
                'plugins' => $total_plugin_updates
            ];
        }
    }
}
?>
