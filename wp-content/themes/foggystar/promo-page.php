<?php
// template name: Promotions
get_header();
?>
<section class="promotions">
    <div class="container">
        <div class="entry-content">
            <?php the_content();?>
        </div>
        <div class="row">
            <?php
            $promotions_list = get_field('promotions_list');

            if($promotions_list) {
                foreach ($promotions_list as $item) {
                    echo '
                    <div class = "promo-item">
                     <div class = "promo-item__left">
                        ' .
                            ( $item['promo_top_label'] ? '<label>' . $item['promo_top_label'] . ' </labeL>' : '') . '
                        <span class="big-title">' . $item['promo_big_title'] . '</span>
                        <span class="description">' . $item['promo_description'] . '</span>
                        <a href="'. get_field('site_ref_link', 'option') . '" class = "btn btn-playnow">' . $item['promo_btn_text'] . '</a>
                    </div>
                    <div class="promo-item__right">
                         <picture><img src="'. $item['promo_image'] . '" alt="' . $item['promo_big_title'] . '"></picture>
                         <picture class="degree">
                         <img src="' . get_template_directory_uri() . '/img/relative.svg" alt="">
                         <span class="count">' . $item['promo_percentage']  . '</span>
                        </picture>                                             
                     
                    </div>
                    </div>
                    ';
                }
            }

            ?>
        </div>
        <div class="entry-content">
            <?php the_field('promotion_under_text');?>
        </div>
    </div>
</section>


<?php

get_footer();