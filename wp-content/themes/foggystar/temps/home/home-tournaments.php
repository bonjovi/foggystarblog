<section class="home_tournaments">
    <div class="container">
        <div class="row">

            <div class="item recent_wins">
                    <span class="label">
                        <?php the_field('site_recent_wins', 'option');?>
                    </span>

                <div class="caption">
                    <?php
                    $promotions_list = get_field('promotions_list', '75');
                    if($promotions_list) {
                        foreach($promotions_list as $item) {
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
                            break;
                        }

                    }

                    ?>
                </div>

            </div>

            <div class="item swiper tournaments">
                <div class="swiper-wrapper">
                    <?php
                    $tournaments = get_field('tournaments', 76 );
                    if($tournaments) {
                        foreach ($tournaments as $tournament) {
                            echo ' <div class="swiper-slide">
                        <img src="'. $tournament['tournament_image'] . '" alt="">
                        <span class="label">
                                ' . get_the_title(76) . '
                            </span>
                        <div class="caption">
                            <span class="title">' . $tournament['tournament_big_title'] . '</span>

                            <div class="scores">
                                <div class="score">
                                    <svg width="18" height="19" viewBox="0 0 18 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.000991821 9.69385C0.000991821 4.7236 4.03038 0.694092 9.00081 0.694092C13.9712 0.694092 18.0008 4.72342 18.0008 9.69385C18.0008 14.6643 13.9711 18.6941 9.00075 18.6941C4.03038 18.6941 0.000991821 14.6641 0.000991821 9.69385ZM3.00102 9.6939C3.00102 6.38043 5.68727 3.69409 9.00087 3.69409C12.3145 3.69409 15.0009 6.38034 15.0009 9.6939C15.0009 13.0075 12.3144 15.6941 9.00083 15.6941C5.68735 15.6941 3.00102 13.0075 3.00102 9.6939ZM9.00087 2.69409C5.13498 2.69409 2.00102 5.82815 2.00102 9.6939C2.00102 13.5597 5.13498 16.6941 9.00083 16.6941C12.8667 16.6941 16.0009 13.5598 16.0009 9.6939C16.0009 5.82801 12.8667 2.69409 9.00087 2.69409Z"></path>
                                    </svg>
                                    <span>' . $tournament['prize_ppol'] . '</span>

                                </div>
                                <div class="score">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                    </svg>
                                    <span><strong>' . $tournament['deadline'] . '</strong>' . get_field('site_time_remaining', 'option') . '</span>
                                </div>
                            </div>

                            <p>'. $tournament['tournament_description'] . '</p>



                        </div>
                        <div class="buttons">
                            <a href="'. get_field('site_ref_link', 'option') . '" class="btn btn-playnow"> '. $tournament['tournament_join_now'] . '</a>
                            <a href="'. get_field('site_ref_link', 'option') . '" class="btn"> '. $tournament['tournament_read_more'] . '</a>
                        </div>
                    </div>';
                        }
                    }

                    ?>


                </div>
                <div class="swiper-navs-row">
                    <div class="swiper-button-prevs swiper-navs"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg></div>
                    <div class="swiper-button-nexts swiper-navs">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>