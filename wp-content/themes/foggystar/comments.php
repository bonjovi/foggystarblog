<?php

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :

        the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ul',
					'short_ping' => true,
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'play' ); ?></p>
		<?php
		endif;

	endif; // Check for have_comments().
	$comments_args = array(
		// изменим название кнопки
		'label_submit' => get_field('site_send_review', 'option'),
		// заголовок секции ответа
		'title_reply'=> get_field('site_write_review_text', 'option'),
		// удалим текст, который будет показано после того как коммент отправлен
		'comment_notes_after' => '',
		// переопределим textarea (тело формы)
		'comment_field' => '<p class="comment-form-comment">
        <label for="comment">' . get_field('site_review_text', 'option') . '*: </label>
        <textarea id="comment" name="comment" aria-required="true"></textarea></p>',
	);

	comment_form($comments_args );

	?>

</div><!-- #comments -->
