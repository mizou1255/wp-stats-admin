<?php
if (!class_exists('WP_Stats_Users')) {
    class WP_Stats_Users {

        public static function get_user_stats() {
            $user_stats = count_users();

            $total_users = $user_stats['total_users'];
            $roles = $user_stats['avail_roles'];

            $user_stats_data = array(
                'totalUsers' => $total_users,
            );
            foreach ($roles as $role => $count) {
                if ($count > 0) {
                    $user_stats_data[ucfirst($role)] = $count;
                }
            }
            return rest_ensure_response($user_stats_data);
        }
    }
}
?>
