<?php
get_header();
?>
<section class="page-single">
    <div class="container">


    <div class="row">
        <?php if(get_field('showing_sidebar'))  {
            ?>
            <div class="microsidebar">
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
            </div>
    <?php
        }?>

        <main class="entry-content <?php echo !get_field('showing_sidebar') ? ' full': '' ?>">
             <?php the_post(); the_content();?>

             <?php
             // If comments are open or we have at least one comment, load up the comment template.
             if ( comments_open() || get_comments_number() ) :
                 comments_template();
             endif;

             ?>
        </main>
    </div>

    </div>
</section>
<?php
get_footer();