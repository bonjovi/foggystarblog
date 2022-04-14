<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <?php wp_head();?>
</head>
<body>
<div class="sidebar-overlay"></div>
<aside class="sidebar-menu">

    <div class="sidebar-logo__section">
        <span class = "label"><?php the_field('site_menu', 'option');?></span>
        <span class="close"><svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="1.5" y1="-1.5" x2="18.4722" y2="-1.5" transform="matrix(0.713188 0.700973 -0.713188 0.700973 0 2)" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></line>
                <line x1="1.5" y1="-1.5" x2="18.4722" y2="-1.5" transform="matrix(-0.713188 0.700973 0.713188 0.700973 16.2439 2)" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></line>
                </svg></span>
        <a href="<?php echo site_url();?>" class="logo"><img src="<?php bloginfo('template_directory');?>/img/logo.svg" width="100" height="55" alt="" ></a>
    </div>

    <div class="sidebar-buttons__section">
        <a href="<?php the_field('site_ref_link', 'option');?>" class="btn btn-login"><?php the_field('site_login_btn', 'option');?></a>
        <a href="<?php the_field('site_ref_link', 'option');?>" class="btn btn-playnow"><?php the_field('site_signup_btn', 'option');?></a>
    </div>
    <?php

    wp_nav_menu(
            [
                    'theme_location' => 'main_menu',
                    'menu' => 'main_menu',
                    'container' => 'nav',
                    'container_class' => 'sidebar-menu__section',
            ]
    );
    wp_nav_menu(
        [
            'theme_location' => 'alt_menu',
            'menu' => 'alt_menu',
            'container' => 'nav',
            'container_class' => 'sidebar-menu__section  simple',
        ]
    );


    ?>


    <div class="sidebar-langs__section lang-dropdown">
            <?php $lang_list = get_field('languages_list', 'option');
            if($lang_list) {
                foreach ($lang_list as $item) {
                    echo '<span class="current-lang lang-dropdown-btn">
                
                <span class="icon-lang"><img src="'. $item['lang_flag'] . '" width = "21"  height = "21" alt="">'. $item['lang_name'] .'</span> 
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                      </svg>
                </span>';
                    break;
                }

                echo '<div class="lang-dropdown-content">';
                $i = 0;
                foreach ($lang_list as $item) {
                    $i++;
                    if($i == 1) {
                        continue;
                    } else {
                        echo '<a href = "'. get_field('site_ref_link', 'option') . '" class="icon-lang">
                    <img src="' . $item['lang_flag'] . '" width = "21"  height = "21" alt="">
                    '. $item['lang_name'] .'</a>';
                    }

                }
                echo '</div>';

            }
            ?>

        
    </div>
</aside>

<header class="header">
    <div class="row">
        <div class="main__left_side">
                <span class="burger">
                    <svg width="30" height="26" viewBox="0 0 30 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="30" height="5.2" rx="2.6" fill="white"></rect>
                        <rect y="10.3999" width="30" height="5.2" rx="2.6" fill="white"></rect>
                        <rect y="20.8" width="30" height="5.2" rx="2.6" fill="white"></rect>
                        </svg>
                </span>
            <a href="<?php echo site_url();?>" class="logo"><img src="<?php bloginfo('template_directory');?>/img/logo.svg" width="100" height="55" alt="" ></a>
            <?php

            wp_nav_menu(
                [
                    'theme_location' => 'main_menu',
                    'menu' => 'main_menu',
                    'container' => 'nav',
                    'container_class' => 'header-menu',
                ]
            );

            ?>


        </div>

        <div class="buttons">
            <a href="<?php the_field('site_ref_link', 'option');?>" class="btn btn-login"><?php the_field('site_login_btn', 'option');?></a>
            <a href="<?php the_field('site_ref_link', 'option');?>" class="btn btn-signup"><?php the_field('site_signup_btn', 'option');?></a>
            <a href="<?php the_field('site_ref_link', 'option');?>" class="search">
                <svg width="19" height="19" viewBox="0 0 19 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 8.56639C0 3.83531 3.83531 0 8.56639 0C13.2975 0 17.1328 3.83531 17.1328 8.56639C17.1328 10.3124 16.6104 11.9363 15.7135 13.2906L18.3443 15.9213C19.0133 16.5904 19.0133 17.6752 18.3443 18.3443C17.6752 19.0133 16.5904 19.0133 15.9213 18.3443L13.2906 15.7135C11.9363 16.6104 10.3124 17.1328 8.56639 17.1328C3.83531 17.1328 0 13.2975 0 8.56639ZM12.3033 12.0954C12.2661 12.1268 12.2299 12.1599 12.1949 12.1949C12.1599 12.2299 12.1268 12.2661 12.0954 12.3033C11.1748 13.173 9.93286 13.7062 8.56639 13.7062C5.72774 13.7062 3.42656 11.405 3.42656 8.56639C3.42656 5.72774 5.72774 3.42656 8.56639 3.42656C11.405 3.42656 13.7062 5.72774 13.7062 8.56639C13.7062 9.93286 13.173 11.1748 12.3033 12.0954Z"></path>
                </svg></a>
        </div>
    </div>
</header>
