<?php
if (!class_exists('WP_Stats_Comments')) {
    class WP_Stats_Comments {

        public static function get_comment_stats() {
            $total_comments = wp_count_comments();
            $comment_stats = array(
                'totalComments' => $total_comments->total_comments,
                'approved' => $total_comments->approved,
                'pending' => $total_comments->moderated,
                'spam' => $total_comments->spam,
                'trash' => $total_comments->trash,
                'post-trashed' => $total_comments->{'post-trashed'},
            );
            return $comment_stats;
        }
    }
}
?>
