<?php
/*if( function_exists('acf_add_options_page') ) {

    $page = acf_add_options_page(array(
        'page_title' 	=> __('Настройки темы', 'productify'),
        'menu_title' 	=> __('Настройки темы', 'productify'),
        'menu_slug' 	=> 'my-theme-options',
        'capability' 	=> 'edit_posts',
        'redirect' 	    => false
    ));

}
add_action('wp_enqueue_scripts', function() {

    wp_enqueue_style('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_style('main', get_stylesheet_uri());

    wp_enqueue_script('swiperJs',  'https://unpkg.com/swiper/swiper-bundle.min.js', ['jquery'], null, true);
    wp_enqueue_script('mainJs', get_template_directory_uri() . '/main.js', ['jquery', 'swiperJs'], null, true);

});

add_theme_support('title-tag');
add_theme_support('post-thumbnails');

add_action( 'after_setup_theme', function(){
    register_nav_menus( [
        'main_menu' => 'Main menu',
        'alt_menu' => 'Alt menu'
    ] );
} );

//ДОБАВЛЯЕМ РЕЙТИНГ К КОММЕНТАРИЯМ
add_action( 'comment_form_logged_in_after', 'comm_rating_rating_field' );
add_action( 'comment_form_after_fields', 'comm_rating_rating_field' );
function comm_rating_rating_field () { ?>
    <div class="com_block_star">
        <label for="rating"><?php the_field('comments_star_rating', 'option') ?><span class="required">*</span></label>
        <fieldset class="comments-rating">
<span class="rating-container">
            <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                <input type="radio" id="rating-<?php echo esc_attr( $i ); ?>" name="rating" value="<?php echo esc_attr( $i ); ?>" /><label for="rating-<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></label>
            <?php endfor; ?>
<input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
</span>
        </fieldset>
    </div>
    <?php
}
//СОХРАНЯЕМ РЕЗУЛЬТАТ
add_action( 'comment_post', 'comm_rating_save_comment_rating' );
function comm_rating_save_comment_rating( $comment_id ) {
    if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) )
        $rating = intval( $_POST['rating'] );
    add_comment_meta( $comment_id, 'rating', $rating );
}

//ОБЯЗАТЕЛЬНОСТЬ РЕЙТИНГА
add_filter( 'preprocess_comment', 'comm_rating_require_rating' );
function comm_rating_require_rating( $commentdata ) {
    if ( ! isset( $_POST['rating'] ) || 0 === intval( $_POST['rating'] ) )
        wp_die('Error!');
    return $commentdata;
}

//ВЫВОДИМ РЕЙТИНГ В ОПУБЛИКОВАННОМ КОММЕНТАРИИ
add_filter( 'comment_text', 'comm_rating_display_rating');
function comm_rating_display_rating( $comment_text ){
    if ( $rating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
        $stars = '<div class="com_star">';
        for ( $i = 1; $i <= $rating; $i++ ) {$stars .= '<span class="dashicons dashicons-star-filled"></span>';}
        $stars .= '</div>';
        $comment_text = $comment_text . $stars;
        return $comment_text;
    } else {return $comment_text;}
}

//ПОДКЛЮЧАЕМ СТИЛИ DASHICONS
add_action( 'wp_enqueue_scripts', 'comm_rating_styles' );
function comm_rating_styles() {
    wp_enqueue_style( 'dashicons');
}



function get_custom_title($title) {
    if(empty($title )) {
        $title = get_the_title();
    }
	if(get_field('single_h1_title')) {
		 return '<h1 class="frame-title">' .  get_field('single_h1_title')  . '</h1>';
	}
    $titleTemplate = get_field('h1_template', 'option');
    $clearedTitle = str_replace(['{h1}'], [$title], $titleTemplate);
	
    return '<h1 class="frame-title">' . $clearedTitle . '</h1>';

}

function comm_rating_display_average_rating() {
    global $post;
    $stars   = '';
    $average = comm_rating_get_average_ratings( $post->ID );
    for ( $i = 1; $i <= $average + 1; $i++ ) { $width = intval( $i - $average > 0 ? 20 - ( ( $i - $average ) * 20 ) : 20 );
        if ( 0 === $width ) {continue;}
        $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';
        if ( $i - $average > 0 ) {
            $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;" class="dashicons dashicons-star-empty"></span>';
        }
    }
    $custom_content  = '<div class="all_com_pr">' . get_field('comments_average_rating', 'option') . ': ' . $average .' ' . $stars .'</div>';
    echo $custom_content;
}

function comment_form_hide_cookies_consent( $fields ) {
    unset( $fields['cookies'] );
    return $fields;
}
add_filter( 'comment_form_default_fields', 'comment_form_hide_cookies_consent' );

function remove_comment_fields($fields) {

    $fields['author'] = '<p class="comment-form-author">' . '<label for="author"> ' . get_field('comments_name', 'option') .'  <span class="required">*</span>: </label>
    <input id="author" name="author" type="text" value=" " size="30" maxlength="245" /></p>';
    unset($fields['url']);

    return $fields;
}
add_filter('comment_form_default_fields', 'remove_comment_fields');

add_shortcode('contact_form', 'form_shortcode');

function form_shortcode() {
 return '<form class="contact-form">
<label for="">
<span>' . get_field('label_name', 'option') . '</span>
<input required type="text" name = "yourName" placeholder="' . get_field('form_name', 'option') . '">
</label>
<label for="">
<span>' . get_field('label_email', 'option') . '</span>
 
<input required type="mail" name = "yourMail" placeholder="' . get_field('form_email', 'option') . '" >
</label>
<label for="">
<span>' . get_field('label_subject', 'option') . '</span>
 
<input required type="text" name = "yourSubject" placeholder="' . get_field('form_subject', 'option') . '">
</label>
<label>
<span>' . get_field('label_message', 'option') . '</span>
 
<textarea required   name = "yourName" placeholder="' . get_field('form_message', 'option') . '">
</textarea>
</label>
<button class = "btn btn-playnow">' . get_field('form_submit_btn', 'option') . '</button>
</form>';
}
*/



add_theme_support( 'post-thumbnails' );

 