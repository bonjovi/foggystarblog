<section class="bottom-menu">
    <div class="container">
        <?php

        wp_nav_menu(
            [
                'theme_location' => 'alt_menu',
                'menu' => 'alt_menu',
                'container' => 'nav',
                'container_class' => '',
            ]
        );

        ?>

        <span class="lang-dropdown">
            <?php
            $languages_list = get_field('languages_list', 'option');
            if($languages_list) {
                foreach ($languages_list as $item) {
                    echo '<span class="lang-dropdown-btn">
                            <img src="' . $item['lang_flag'] . '" alt="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                              </svg>
                         </span>';
                    break;
                }
                echo ' <span class="lang-dropdown-content">';
                foreach ($languages_list as $item) {
                echo '<a href="'. get_field('site_ref_link', 'option') . '"> 
                       <img  width="16" height="16" src="'. $item['lang_flag'] .'" alt=""></a>';
                }
                echo '</span>';

            }

            ?>



            </span>
        </span>
    </div>
</section>