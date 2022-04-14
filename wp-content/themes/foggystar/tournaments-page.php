<?php
// template name: Tournaments
get_header();
?>
    <section class="tournaments">
        <div class="container">
            <div class="entry-content">
                <?php the_content();?>
            </div>
            <div class="row">
                <?php
                $promotions_list = get_field('tournaments');

                if($promotions_list) {
                    foreach ($promotions_list as $item) {
                        echo '
                    <div class = "promo-item">
                     <div class = "promo-item__left">
                        ' .
                            ( $item['tournament_label'] ? '<label>' . $item['tournament_label'] . ' </labeL>' : '') . '
                        <span class="big-title">' . $item['tournament_big_title'] . '</span>
                        <span class="description">' . $item['tournament_description'] . '</span>
                        <a href="'. get_field('site_ref_link', 'option') . '" class = "btn btn-playnow">' . $item['tournament_join_now'] . '</a>
                        <a href="' . get_field('site_ref_link', 'option') . '" class="btn">' . $item['tournament_read_more'] . '</a>
                    </div>
                    <div class="promo-item__right">
                         <img class="background" src="'. $item['tournament_image_page'] . '" alt="' . $item['tournament_big_title'] . '"> 
                         <div class="caption">
                         <span class="prize">'. $item['prize_ppol'] . '</span>
                             <div class="score">
                                 
                                    <span class="rows">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                    </svg><strong>' . $item['deadline'] . ' <span class="rem">' . get_field('site_time_remaining', 'option') . ' </span></strong>  
                                      </span>
                                
                             </div>
                             <table class="table"> 
                             <thead><tr><th>' . get_field('site_place_txt', 'option') . ' </th><th>' . get_field('site_prize_txt', 'option') . ' </th></tr></thead>
                             <tbody> ';
                             $i = 0;
                             foreach($item['places'] as $t) {
                                 $i++;
                                 echo '<tr><td class = "with-cup">' . $i . '<img width = "16" height = "16" src = "'. get_template_directory_uri() . '/img/cup.png"></td> <td>' . $t['prize'] .
                                 '</td></tr>';
                             }
                             echo ' </tbody>
                             </table>
                         </div>
                         
                             
                     
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