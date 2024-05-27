<?php
if (!class_exists('WP_Stats_CPT')) {
    class WP_Stats_CPT {

        public static function get_data_post_types() {
            $post_types = get_post_types(array(
                'public' => true,
            ), 'objects');

            $results = array();

            foreach ($post_types as $post_type) {
                $args = array(
                    'post_type' => $post_type->name,
                    'posts_per_page' => -1,
                    'post_status' => array('draft', 'pending', 'publish', 'trash')
                );

                $query = new WP_Query($args);

                $total_posts = $query->found_posts;
                $draft_posts = 0;
                $pending_posts = 0;
                $publish_posts = 0;
                $trash_posts = 0;

                foreach ($query->posts as $post) {
                    switch ($post->post_status) {
                        case 'draft':
                            $draft_posts++;
                            break;
                        case 'pending':
                            $pending_posts++;
                            break;
                        case 'publish':
                            $publish_posts++;
                            break;
                        case 'trash':
                            $trash_posts++;
                            break;
                    }
                }

                $draft_percentage = ($total_posts > 0) ? round(($draft_posts / $total_posts) * 100, 2) : 0;
                $pending_percentage = ($total_posts > 0) ? round(($pending_posts / $total_posts) * 100, 2) : 0;
                $publish_percentage = ($total_posts > 0) ? round(($publish_posts / $total_posts) * 100, 2) : 0;
                $trash_percentage = ($total_posts > 0) ? round(($trash_posts / $total_posts) * 100, 2) : 0;

                if ($draft_percentage == 0 && $pending_percentage == 0 && $publish_percentage == 0) {
                    continue;
                }

                $results[] = array(
                    'name' => $post_type->name,
                    'label' => $post_type->label,
                    'draft_count' => $draft_posts,
                    'pending_count' => $pending_posts,
                    'publish_count' => $publish_posts,
                    'trash_count' => $trash_posts,
                    'draft_percentage' => $draft_percentage,
                    'pending_percentage' => $pending_percentage,
                    'publish_percentage' => $publish_percentage,
                    'trash_percentage' => $trash_percentage,
                    'selected' => true
                );
            }

            return rest_ensure_response($results);
        }
    }
}
?>