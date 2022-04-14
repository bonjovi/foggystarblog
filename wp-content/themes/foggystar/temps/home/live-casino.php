<?php
$games_cats = get_categories([
    'taxonomy' => 'category',
    'type' => 'post',
    'hide_empty' => 0,
    'include' => [4]
]);

if($games_cats) {

    foreach ($games_cats as $game_cat) {
        echo '<section class="games-row">
    <div class="container">
        <div class="row-header">
            <div class="row">
                <h2 class="games-cat__title">' . $game_cat->name . '</h2>
                <a href="#" class="show__all">'. get_field('site_show_all', 'option') . ' <span class="arrow">></span></a>
            </div>
        </div>
        <div class="games">
            <div class="row">';
        $query = new WP_Query([
            'posts_per_page' => 18,
            'post_type' => 'post',
            'tax_query' => [
                [
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => [$game_cat->term_id],
                ],
            ],
        ]);
        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                get_template_part('temps/loop/loop', 'game');
            }
            wp_reset_postdata();
        }
        echo '</div>
        </div>
    </div>
</section>';
    }

}

?>