<div class="games-item">
    <picture>
        <img src="<?php echo get_the_post_thumbnail_url();?>" alt="<?php echo get_the_title(); ?>">
    </picture>
    <div class="game-caption">
     <span class="game-title"><?php echo get_the_title();?></span>
        <div class="row">
            <?php
            /*
             * Check for Live casino Cat
             */
            $current_terms = get_the_terms(get_the_ID(), 'category');

            foreach ($current_terms as $term) {
                if($term->term_id == 4) {
                    $is_live = true;
                }
            }
            if($is_live) {
                echo '<a href="' . get_field('site_ref_link', 'option') . '" class="btn btn-playnow">' . get_field('site_play_btn', 'option') . '</a>';
            } else {
                echo '<a href="' . get_field('site_ref_link', 'option') . '" class="btn btn-playnow">' . get_field('site_play_btn', 'option') . '</a>
                      <a href="' . get_the_permalink() . '" class="btn">' . get_field('site_demo_btn', 'option') . '</a>';
            }
            ?>


        </div>

    </div>
</div>