<?php
get_header();

get_template_part('temps/home/home', 'banner');


$games_cats = get_categories([
    'taxonomy' => 'category',
    'type' => 'post',
    'include' => get_queried_object_id()
]);

if ($games_cats) {

    foreach ($games_cats as $game_cat) {
        echo '<section class="games-row">
    <div class="container">
 
        <div class="entry-content">';
        the_archive_description();
        echo '</div>
        <div class="games">
            <div class="row">';
            $query = new WP_Query([
                'posts_per_page' => -1,
                'post_type' => 'post',
                'tax_query' => [
                    [
                        'taxonomy' => 'category',
                        'field' => 'id',
                        'terms' => [$game_cat->term_id],
                    ],
                ],
            ]);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    get_template_part('temps/loop/loop', 'game');
                }
                wp_reset_postdata();
            }
        echo '</div>
        </div>';
            if(get_field('cat_under_text', 'category_' . get_queried_object_id())) {
                echo '<div class = "entry-content">' . get_field('cat_under_text', 'category_' . get_queried_object_id()) . '</div>';
            }
            echo '       
    </div>
</section>';
    }

}


get_footer();