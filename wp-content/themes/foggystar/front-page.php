<?php
// template name: Home
get_header();
?>

<link rel="stylesheet" href="<?=get_template_directory_uri();?>/assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?=get_template_directory_uri();?>/assets/css/owl.theme.default.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=get_template_directory_uri();?>/assets/js/owl.carousel.min.js"></script>

<div class="container frontpage">
    <div class="frontpage__title">
        CryptoPortal FoggyStar
    </div>

    <div class="frontpage__slider owl-carousel">
        <?php if( have_rows('slider') ): ?>
            <?php while( have_rows('slider') ): the_row(); ?>
                <a class="frontpage__slide" href="<?php the_sub_field('slider_link'); ?>">
                    <div class="frontpage__slide-pic">
                        <img src="<?php the_sub_field('slider_picture'); ?>" alt="">
                    </div>  
                    <div class="frontpage__slide-text">
                        <?php the_sub_field('slider_category'); ?>
                    </div>  
                    <div class="frontpage__slide-title">
                        <?php the_sub_field('slider_title'); ?>
                    </div>  
                </a> 
            <?php endwhile; ?>
        <?php endif; ?>
        
    </div>

    <?php
        $categories = get_categories(array(
            'hide_empty' => 1,
            'include' => [3,4,5,7,8]
        ));


        $current = get_queried_object();
        $current_id = $current->term_id;

        if($current_id >= 0) {
            $args = array(
                'posts_per_page' => 10,
                'post__in' => get_option('sticky_posts'),
                //'cat' => $current_id,
                'cat' => [3,4,5,7,8],
                'orderby' => 'modified',
                'order' => 'DESC'
            );
        } else {
            $args = array(
                'posts_per_page' => 10,
                'post__in' => get_option('sticky_posts'),
                'orderby' => 'modified',
                'order' => 'DESC'
            );
        }

        $blog_posts = new WP_Query($args);

        //echo "<pre>"; var_dump($blog_posts); die;

        global $post;
        $featured_id = get_the_ID();
    ?>

    <div class="rubrics"> 

        <div class="rubrics__maintitle">    
            FoggyStar last news
        </div>

        <ul class="tabs"> 
            <li class="tabs__item active">
                <a href="#" class="tabs__link" data-tab="all-items">All</a>
            </li>
            <?php $i = 1; ?>
            <?php foreach ($categories as $category) : ?>
                <li class="tabs__item">
                    <a href="/blog/?category=<?= $category->slug; ?>" class="tabs__link" data-tab="<?= $category->slug; ?>"><?= $category->name; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="rubrics__items">
            <?php while ( $blog_posts->have_posts() ) : $blog_posts->the_post(); ?>
                <?php $category =  get_the_category(); ?>
                <div class="rubrics__item" data-tab="<?= $category[0]->slug; ?>">
                    <?php
                        $shadowlink = str_replace('https://zealous.foggystar.com', 'https://foggystar.com', get_permalink());
                        if(get_the_ID() == 86) {
                            $shadowlink = 'https://foggystar.com/blog/?p=52';
                        }
                    ?>
                    <a class="rubrics__post" href="<?php echo $shadowlink; ?>">
                        <div class="rubrics__post-pic">
                            <?php $thumb_url = get_the_post_thumbnail_url(); ?>
                            <img src="<?=$thumb_url?>" alt="">
                        </div>  
                        <div class="rubrics__post-category">
                            <?php foreach($category as $category_item): ?>
                                <div>
                                    <?php echo $category_item->name; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>  
                        
                        <div class="rubrics__post-title">
                            <?php the_title();?>
                        </div> 
                        <div class="rubrics__post-text">
                            <?php echo get_the_excerpt();?>
                        </div> 

                        <?php
                            // function reading_time() {
                            //     $content = get_the_excerpt();
                            //     $word_count = str_word_count( strip_tags( $content ) );
                            //     $readingtime = ceil($word_count / 200);
                            //     if ($readingtime == 1) {
                            //         $timer = " minute";
                            //     } else {
                            //         $timer = " minutes";
                            //     }
                            //     $totalreadingtime = $readingtime . $timer;
                            //     return $totalreadingtime;
                            // }
                        ?>
                        <div class="single__date single__date--mini">
                            <img src="<?=get_template_directory_uri();?>/assets/img/time-icon-mini.svg" alt="">
                            <?php the_field('reading_time'); ?>
                        </div>
                    </a> 
                
                </div>
            <?php endwhile; ?>
        </div>
        

    </div>

</div>



<?php
get_footer();

