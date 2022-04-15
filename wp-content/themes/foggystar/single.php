<?php
get_header();

switch_to_locale('en_US');
?>

<link rel="stylesheet" href="<?=get_template_directory_uri();?>/assets/css/owl.carousel.min.css">
<link rel="stylesheet" href="<?=get_template_directory_uri();?>/assets/css/owl.theme.default.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=get_template_directory_uri();?>/assets/js/owl.carousel.min.js"></script>

<div class="backarrow__wrapper">
    <div class="backarrow__container">
        <a href="/blog/" class="backarrow">
            <div class="backarrow__pic">
                <img src="<?=get_template_directory_uri();?>/assets/img/arrow.svg" alt="" width="31">
            </div>
            <div class="backarrow__text">
                Back to portal
            </div>
        </a>
    </div>
</div>

<script>
    $(function() {
        $('.wp-block-embed__wrapper').on('click', function() {
            $(this).addClass('active');

            var videoURL = $('.wp-block-embed__wrapper iframe').prop('src');
            videoURL += "&autoplay=1";
            $('.wp-block-embed__wrapper iframe').prop('src',videoURL);
        });
    });
</script>

<div class="single">
    <div class="single__category">
        <?php $category = get_the_category($post->ID); ?>
        <?php echo $category[0]->name; ?>
    </div>
    <div class="single__title">
        <?php if($post->ID == 52): ?>
            How to Deposit Funds<br> on PC
        <?php elseif($post->ID == 86): ?>
            How to Deposit Funds<br> on Mob
        <?php elseif($post->ID == 95): ?>
            How to Deposit Funds<br> Watch video
        <?php else: ?>  
            <?php the_title(); ?>
        <?php endif; ?>       
    </div>
    <div class="single__date">
        <img src="<?=get_template_directory_uri();?>/assets/img/time-icon.svg" alt="">
        <?php //echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?>
        1 week ago
    </div>
    <div class="single__content <?php if(get_the_ID() == 123) {echo 'grey';} ?>">     
        <div class="single__excerpt">
            <?php echo get_the_excerpt();?>
        </div>

        <?php if(get_the_ID() == 52 || get_the_ID() == 86 || get_the_ID() == 95): ?>
            <div class="steps__switchers">
                <a href="/blog/?p=52" class="steps__switcher <?php if(get_the_ID() == 52) {echo 'active';} ?>">
                    Desktop
                </a>
                <a href="/blog/?p=86" class="steps__switcher <?php if(get_the_ID() == 86) {echo 'active';} ?>">
                    Mobile
                </a>
                <a href="/blog/?p=95" class="steps__switcher <?php if(get_the_ID() == 95) {echo 'active';} ?>">
                    Watch video
                </a>
            </div>
        <?php endif; ?>

        <?php if(get_the_ID() != 52 && get_the_ID() != 123  && get_the_ID() != 164  && get_the_ID() != 172): ?>
            <div class="single__thumb">
                <?php $thumb_url = get_the_post_thumbnail_url(); ?>
                <img src="<?=$thumb_url?>" alt="" width="100%"> 
            </div>      
        <?php endif; ?>  

        <?php if(get_the_ID() == 164): ?>
            <?php include('includes/happy-easter.php'); ?>
        <?php endif; ?>  

        <?php if(get_the_ID() == 172): ?>
            <?php include('includes/progmatic.php'); ?>
        <?php endif; ?>  

        <?php the_content(); ?>

        <?php if(get_the_ID() == 52 || get_the_ID() == 86 || get_the_ID() == 95): ?>
            <div class="steps">
                <?php if( have_rows('steps') ): ?>
                    <div class="steps__items">
                        <?php while( have_rows('steps') ): the_row(); ?>
                            <div class="steps__item">
                                <div class="steps__title">
                                    <?php the_sub_field('steps_title'); ?>
                                </div>
                                <div class="steps__text">
                                    <?php the_sub_field('steps_text'); ?>
                                </div>
                                <div class="steps__picture">
                                    <img src="<?php the_sub_field('steps_picture'); ?>" alt="" width="<?php if(get_sub_field('steps_picture_width') != '') {the_sub_field('steps_picture_width');} ?>">
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>















<?php
    $categories = get_categories(array(
        'hide_empty' => 1,
        'include' => [3,4,5]
    ));


    $current = get_queried_object();
    $current_id = $current->term_id;

    if($current_id >= 0) {
        $args = array(
            'posts_per_page' => 10,
            'post__in' => get_option('sticky_posts'),
            //'cat' => $current_id,
            'cat' => [3,4,5],
        );
    } else {
        $args = array(
            'posts_per_page' => 10,
            'post__in' => get_option('sticky_posts'),
        );
    }

    $blog_posts = new WP_Query($args);

    //echo "<pre>"; var_dump($blog_posts); die;

    global $post;
    $featured_id = get_the_ID();
?>

<div class="container">  
    <div class="rubrics rubrics--single"> 
        <hr class="hr">
        <br><br>

        <div class="rubrics__maintitle">    
            Our last news
        </div>

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
                    </a> 
                
                </div>
            <?php endwhile; ?>
        </div>

        <br>
        <a href="/blog/" class="steps__switcher steps__back">
            Back to portal
        </a>
        <br><br><br>
        <hr class="hr">
        
        <br><br><br><br>
    </div>    
</div>


<?php
get_footer();
?>