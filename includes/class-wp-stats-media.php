<?php
if (!class_exists('WP_Stats_Media')) {
    class WP_Stats_Media {

        public static function get_media_stats() {
            $attachments = get_posts(array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'fields' => 'ids'
            ));
            $total_media_count = count($attachments);
            $unused_images_count = self::count_unused_images();
            
            $all_media_counts = wp_count_attachments();
            $total_media_size = self::get_total_media_size();
            $total_unused_media_size = self::get_unused_images_size();
            $broken_links = self::get_broken_links($attachments);

            $media_stats = array(
                'totalMediaCount' => $total_media_count,
                'totalMediaSize' => $total_media_size, 
                'totalUnusedMediaSize' => $total_unused_media_size, 
                'unusedImagesCount' => $unused_images_count,
                'brokenLinksCount' => count($broken_links),
                'brokenLinks' => $broken_links
            );

            foreach ($all_media_counts as $type => $count) {
                if ($count > 0) {
                    $media_stats[ucfirst($type)] = $count;
                }
            }

            return rest_ensure_response($media_stats);
        }

        private static function get_broken_links($attachments) {
            $broken_links = [];
        
            foreach ($attachments as $attachment_id) {
                $file_path = get_attached_file($attachment_id);
                if (!file_exists($file_path)) {
                    $broken_links[] = [
                        'id' => $attachment_id,
                        'url' => wp_get_attachment_url($attachment_id)
                    ];
                }
            }
        
            return $broken_links;
        }
        

        public static function count_unused_images() {
            global $wpdb;
            $unused_images_query = $wpdb->get_col("
                SELECT ID FROM {$wpdb->posts} 
                WHERE post_type = 'attachment' 
                AND (post_parent IS NULL OR post_parent = 0)
            ");
            $unused_images_count = count($unused_images_query);
            return $unused_images_count;
        }

        private static function get_total_media_size() {
            $media_size = 0;
            $media_query = new WP_Query(array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_status' => array('inherit')
            ));
            if ($media_query->have_posts()) {
                while ($media_query->have_posts()) {
                    $media_query->the_post();
                    $file_path = get_attached_file(get_the_ID());
                    if (file_exists($file_path)) {
                        $media_size += filesize($file_path);
                    }
                }
                wp_reset_postdata();
            }
            $formatted_size = size_format($media_size);
            return $formatted_size;
        }

        private static function get_unused_images_size() {
            global $wpdb;
            $unused_images_query = $wpdb->get_col("
                SELECT ID FROM {$wpdb->posts} 
                WHERE post_type = 'attachment' 
                AND (post_parent IS NULL OR post_parent = 0)
            ");

            $unused_images_size = 0;

            foreach ($unused_images_query as $attachment_id) {
                $file_path = get_attached_file($attachment_id);
                if ($file_path && file_exists($file_path)) {
                    $unused_images_size += filesize($file_path);
                }
            }
            $formatted_size = size_format($unused_images_size);

            return $formatted_size;
        }
    }
}
?>
