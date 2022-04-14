<div class="swiper main__banner">
    <div class="swiper-wrapper">
        <?php
            $home_big_banner = get_field('home_big_banner', '2');
            if($home_big_banner) {

                foreach ($home_big_banner as $banner) {
                    echo '
        <div class="swiper-slide">
            <img src="'. $banner['home_big_banner_img'] . '" alt="">

            <div class="caption container">
                <span class="banner__big-title">'. $banner['home_banner_main_text'] . '</span>
                 '. $banner['home_banner_main_descr'] . '
                <a href="'. get_field('site_ref_link', 'option') . '" class="btn btn-playnow">'. get_field('site_play_btn', 'option') . '</a>
            </div>
        </div>';
                }
            }

        ?>


    </div>

    <div class="swiper-navs-row container">
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