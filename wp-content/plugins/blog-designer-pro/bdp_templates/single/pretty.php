<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/single/pretty.php.
 *
 * @link       https://www.solwininfotech.com/
 * @since      2.3
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action( 'bd_single_design_format_function', 'bdp_single_pretty_template', 10, 1 );
if ( ! function_exists( 'bdp_single_pretty_template' ) ) {
	/**
	 * Add html for region template
	 *
	 * @global object $post
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_single_pretty_template( $bdp_settings ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		$left_after        = '';
		?>
		<div class="blog_template bdp_blog_template pretty">
			<?php
			do_action( 'bdp_before_single_post_content' );
			?>
			<div class="entry-container">
				<?php
				if ( has_post_thumbnail() && isset( $bdp_settings['display_thumbnail'] ) && 1 == $bdp_settings['display_thumbnail'] ) { //phpcs:ignore
					$left_after = 'post-has-image';
					?>
					<div class="bdp-post-image post-has-image">
						<?php
						if ( 'product' === $post_type ) {
							if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
								echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
							}
							do_action( 'bdp_woocommerce_show_product_images', $bdp_settings, $post->ID );
						} else {
							$single_post_image = Bdp_Posts::get_the_single_post_thumbnail( $bdp_settings, get_post_thumbnail_id(), get_the_ID() );
							echo apply_filters( 'bdp_single_post_thumbnail_filter', $single_post_image, get_the_ID() ); //phpcs:ignore
							if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
								echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
							}
						}
						?>
					</div>
					<?php
				}
				?>
				<div class="blog_header">
					<div class="left-content-wrapper <?php echo esc_attr( $left_after ); ?>"></div>
					<div class="right-content-wrapper">
						<div class="blog_header_meta_wrapper">
							<?php
							$display_title = ( isset( $bdp_settings['display_title'] ) && '' != $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1; //phpcs:ignore
							if ( 1 == $display_title ) { //phpcs:ignore
								?>
								<h1><?php echo esc_html( get_the_title() ); ?></h1>
							<?php } ?>
							<?php
							$display_author        = $bdp_settings['display_author'];
							$display_comment_count = ( isset( $bdp_settings['display_comment'] ) && '' != $bdp_settings['display_comment'] ) ? $bdp_settings['display_comment'] : 0; //phpcs:ignore
							$ar_year               = get_the_time( 'Y' );
							$ar_month              = get_the_time( 'm' );
							$ar_day                = get_the_time( 'd' );
							if ( 1 == $display_author || 1 == $bdp_settings['display_postlike'] || 1 == $display_comment_count ) { //phpcs:ignore
								?>
								<div class="metadatabox">
									<?php
									if ( 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
										$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
										?>
										<span class="author <?php echo ( $author_link ) ? 'bdp-has-links' : 'bdp-no-links'; ?>">
											<span class="link-lable"><i class="fas fa-user"></i></span>
											<?php echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore ?>
										</span>
										<?php
									}
									if ( isset( $bdp_settings['display_comment'] ) && 1 == $bdp_settings['display_comment'] ) { //phpcs:ignore
										if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
											?>
											<span class="comment">
												<span class="icon_cnt">
													<i class="fas fa-comment"></i>
													<?php
													$comment_link = ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) ? false : true; //phpcs:ignore
													Bdp_Posts::comment_count( $comment_link );
													?>
												</span>
											</span>
											<?php
										endif;
									}
									if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
										echo do_shortcode( '[likebtn_shortcode]' );
									}
									?>
								</div>
							<?php } ?>
						</div>
						<div class="blog-header-avatar">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
						</div>
						<?php
						if ( 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
							$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
							$ar_year   = get_the_time( 'Y' );
							$ar_month  = get_the_time( 'm' );
							$ar_day    = get_the_time( 'd' );
							?>
							<div class="post_date">
								<?php echo ( 'product' !== $post_type && $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '" class="date">' : '<span class="date">'; ?>
								<span class="date"><?php echo ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'd' ) ) : esc_html( get_the_time( 'd' ) ); //phpcs:ignore ?></span>
								<span class="month"><?php echo ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'F' ) ) : esc_html( get_the_time( 'F' ) ); //phpcs:ignore ?></span>
								<span class="year"><?php echo ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'Y' ) ) : esc_html( get_the_time( 'Y' ) ); //phpcs:ignore ?></span>
								<?php echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '</span>'; ?>
							</div>
							<?php
						}
						if ( 'product' === $post_type ) {
							do_action( 'bdp_woocommerce_meta_data', $bdp_settings, $post->ID );
						}
						if ( 'download' === $post_type && isset( $bdp_settings['display_download_price'] ) && 1 == $bdp_settings['display_download_price'] ) { //phpcs:ignore
							do_action( 'bdp_edd_single_download_price', $post->ID );
						}
						?>
						<div class="post_content">
							<?php
							do_action( 'bdp_single_post_content_data', $bdp_settings, $post->ID );
							if ( 'download' === $post_type && isset( $bdp_settings['display_edd_addtocart_button'] ) && 1 == $bdp_settings['display_edd_addtocart_button'] ) { //phpcs:ignore
								do_action( 'bdp_edd_single_download_cart_button', $post->ID );
							}
							if ( Bdp_Template_Acf::is_acf_plugin() ) {
								if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
									echo '<div class="bdp_acf_field">';
									do_action( 'bdp_after_single_post_content_data', $bdp_settings, $post->ID );
									echo '</div>';
								}
							}
							if ( isset( $bdp_settings['display_post_views'] ) && 0 != $bdp_settings['display_post_views'] ) { //phpcs:ignore
								if ( '' != Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ) ) { //phpcs:ignore
									echo '<div class="display_post_views">';
									echo Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ); //phpcs:ignore
									echo '</div>';
								}
							}
							?>
						</div>
						<?php
						if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
							$taxonomy_names       = get_object_taxonomies( $post_type, 'objects' );
							$taxonomy_names       = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
							$bdp_include_taxonomy = array( 'product_tag', 'download_tag' );
							foreach ( $taxonomy_names as $taxonomy_single ) {
								$taxonomy = $taxonomy_single->name;
								$sep      = 1;
								if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
									$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
									$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
									if ( isset( $taxonomy ) ) {
										if ( isset( $term_list ) && ! empty( $term_list ) ) {
											echo '<div class="post-meta-cats-tags">';
											?>
											<div class="
											<?php
											if ( in_array( $taxonomy, $bdp_include_taxonomy ) ) { //phpcs:ignore
												echo 'tags';
											} else {
												echo 'category-link';
											}
											echo ( $taxonomy_link ) ? 'bdp-no-links' : 'bdp-has-links';
											?>
											">
											<span class="link-lable"><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
											<?php
											foreach ( $term_list as $term_nm ) {
												if ( 1 != $sep && ! in_array( $taxonomy, $bdp_include_taxonomy ) ) { //phpcs:ignore
													?>
														<span class="seperater"><?php echo ', '; ?></span>
														<?php
												}
												$term_link = get_term_link( $term_nm );
												echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
												echo esc_html( $term_nm->name );
												echo ( $taxonomy_link ) ? '</a>' : '';
												$sep++;
											}
											?>
											</div>
											<?php
											echo '</div>';
										}
									}
								}
							}
						} else {
							if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) { //phpcs:ignore
								echo '<div class="post-meta-cats-tags">';
							}
							if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
								$categories_list = get_the_category_list( ' , ' );
								$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
								?>
								<div class="category-link <?php echo ( $categories_link ) ? 'bdp-no-links' : 'bdp-has-links'; ?>">
									<?php
									if ( $categories_link ) {
										$categories_list = strip_tags( $categories_list ); //phpcs:ignore
									}
									if ( $categories_list ) :
										echo '<span class="link-lable">' . esc_html__( 'Category: ', 'blog-designer-pro' ) . '&nbsp;&nbsp; </span>';
										echo $categories_list; //phpcs:ignore
									endif;
									?>
								</div>
								<?php
							}
							if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
								$tags_list = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? get_the_tag_list( '', ', ' ) : get_the_tag_list( '', ' ' ); //phpcs:ignore
								$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
								if ( $tag_link ) {
									$tags_list = strip_tags( $tags_list ); //phpcs:ignore
								}
								if ( $tags_list ) :
									?>
									<div class="tags <?php echo ( $tag_link ) ? 'bdp-no-links' : 'bdp-has-links'; ?>">
										<?php
										echo '<span class="link-lable">' . esc_html__( 'Tags: ', 'blog-designer-pro' ) . '&nbsp;&nbsp; </span>';
										echo $tags_list; //phpcs:ignore
										?>
									</div>
									<?php
								endif;
							}
							if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) { //phpcs:ignore
								echo '</div>';
							}
						}

						?>
						<div class="entry-meta clearfix">
							<?php $social_share_position = ( isset( $bdp_settings['social_share_position'] ) && '' != $bdp_settings['social_share_position'] ) ? $bdp_settings['social_share_position'] : ''; //phpcs:ignore ?>
							<div class="pull-left social-component-count-<?php echo esc_attr( $bdp_settings['social_count_position'] ); ?> <?php echo esc_attr( $social_share_position ) . '_position'; ?>">
								<?php
								if ( is_single() ) {
									do_action( 'bdp_social_share_text', $bdp_settings );
								}
								Bdp_Utility::get_social_icons( $bdp_settings );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php do_action( 'bdp_after_single_post_content' ); ?>
		</div>
		<?php
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_image' ), 5, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_content_cover_start' ), 10, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_name' ), 15, 4 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_biography' ), 20, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_social_links' ), 25, 4 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_content_cover_end' ), 30, 2 );
		add_action( 'bdp_related_post_detail', array( 'Bdp_Posts', 'related_post_title' ), 5, 4 );
		add_action( 'bdp_related_post_detail', array( 'Bdp_Posts', 'related_post_item' ), 10, 9 );
	}
}
?>
