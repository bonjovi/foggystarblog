<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/single/news.php.
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
add_action( 'bd_single_design_format_function', 'bdp_single_news_template', 10, 1 );
if ( ! function_exists( 'bdp_single_news_template' ) ) {
	/**
	 * Add html for news template
	 *
	 * @global object $post
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_single_news_template( $bdp_settings ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		?>
		<div class="bdp_blog_template news">
			<?php do_action( 'bdp_before_single_post_content' ); ?>
			<div class="post-thumbnail-div bdp-post-image">
				<?php
				if ( has_post_thumbnail() && $bdp_settings['display_thumbnail'] && 1 == $bdp_settings['display_thumbnail'] ) { //phpcs:ignore
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
				}
				?>
			</div>
			<div>
				<?php
				$display_title = ( isset( $bdp_settings['display_title'] ) && '' != $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1; //phpcs:ignore
				if ( 1 == $display_title ) { //phpcs:ignore
					?>
					<h1 class="post-title"><?php echo esc_html( get_the_title() ); ?></h1>
					<?php
				}
				?>
				<div class="post_meta">
					<?php
					if ( ( isset( $bdp_settings['display_date'] ) && 1 == $bdp_settings['display_date'] ) || ( isset( $bdp_settings['display_author'] ) && 1 == $bdp_settings['display_author'] ) ) { //phpcs:ignore
						?>
						<div class="post_meta_data">
						<?php
						if ( ( isset( $bdp_settings['display_date'] ) && 1 == $bdp_settings['display_date'] ) || ( isset( $bdp_settings['display_author'] ) && 1 == $bdp_settings['display_author'] ) ) { //phpcs:ignore
							esc_html_e( 'Last Updated', 'blog-designer-pro' );
						}
						$display_date = $bdp_settings['display_date'];
						if ( isset( $bdp_settings['display_date'] ) && 1 == $display_date ) { //phpcs:ignore
							echo '&nbsp;';
							esc_html_e( 'On', 'blog-designer-pro' );
							echo '&nbsp;';
							$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
							$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );
							$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
							echo ( 'product' !== $post_type && $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '';
						}
						if ( isset( $bdp_settings['display_author'] ) && 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
							$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
							echo '&nbsp;' . esc_html__( 'By', 'blog-designer-pro' );
							echo ' ';
							?>
							<span class="post-author <?php echo ( $author_link ) ? 'bdp-has-links' : 'bdp-no-links'; ?>">
							<?php echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore ?>
							</span>
							<?php
						}
						?>
							</div>
							<?php
					}
					if ( isset( $bdp_settings['display_comment'] ) && 1 == $bdp_settings['display_comment'] ) { //phpcs:ignore
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
							?>
							<span class="metacomments">
								<i class="fas fa-comment"></i>
								<?php
								if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
									$id     = get_the_ID();
									$number = get_comments_number( $id );

									if ( 0 == $number && ! comments_open() && ! pings_open() ) { //phpcs:ignore
										echo esc_html__( 'Comments are off', 'blog-designer-pro' );
									} else {
										comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
									}
								} else {
									comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
								}
								?>
							</span>
							<?php
						}
					}
					if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
						echo do_shortcode( '[likebtn_shortcode]' );
					}
					?>
				</div>
				<?php
				if ( 'product' === $post_type ) {
					do_action( 'bdp_woocommerce_meta_data', $bdp_settings, $post->ID );
				}
				if ( 'download' === $post_type && isset( $bdp_settings['display_download_price'] ) && 1 == $bdp_settings['display_download_price'] ) { //phpcs:ignore
					do_action( 'bdp_edd_single_download_price', $post->ID );
				}
				?>
				<div class="post_content entry-content">
					<?php
						do_action( 'bdp_single_post_content_data', $bdp_settings, $post->ID );
					if ( 'download' === $post_type && isset( $bdp_settings['display_edd_addtocart_button'] ) && 1 == $bdp_settings['display_edd_addtocart_button'] ) { //phpcs:ignore
						do_action( 'bdp_edd_single_download_cart_button', $post->ID );
					}
					?>
					<?php
					if ( isset( $bdp_settings['display_post_views'] ) && 0 != $bdp_settings['display_post_views'] ) { //phpcs:ignore
						if ( '' != Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ) ) { //phpcs:ignore
							echo '<div class="display_post_views">';
							echo Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ); //phpcs:ignore
							echo '</div>';
						}
					}
					if ( Bdp_Template_Acf::is_acf_plugin() ) {
						if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
							echo '<div class="bdp_acf_field">';
							do_action( 'bdp_after_single_post_content_data', $bdp_settings, $post->ID );
							echo '</div>';
						}
					}
					?>
					<div class="footer_meta">
					<?php
					if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
						$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
						$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
						foreach ( $taxonomy_names as $taxonomy_single ) {
							$taxonomy = $taxonomy_single->name;
							$sep      = 1;
							if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
								$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
								$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
								if ( isset( $taxonomy ) ) {
									if ( isset( $term_list ) && ! empty( $term_list ) ) {
										$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
										?>
										<div class="tag_cat">
											<div class="tags">
												<span class="link-lable"> 
												<?php
												if ( in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
													?>
													<i class="fas fa-bookmark"></i>
													<?php
												} else {
													?>
													<i class="fas fa-tags"></i><?php } ?> <?php echo esc_html( $taxonomy_single->label ); ?> &nbsp;:&nbsp; </span>
												<?php
												foreach ( $term_list as $term_nm ) {
													if ( 1 != $sep ) { //phpcs:ignore
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
										</div>
										<?php
									}
								}
							}
						}
					} else {
						if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) { //phpcs:ignore
							?>
							<div class="tag_cat">
								<?php
								if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
									$categories_list = get_the_category_list( ', ' );
									$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
									$class           = ( $categories_link ) ? 'bdp-no-links' : 'bdp-has-links';
									if ( $categories_link ) {
										$categories_list = strip_tags( $categories_list ); //phpcs:ignore
									}
									if ( $categories_list ) :
										echo '<span class="post-category ' . esc_attr( $class ) . '"><i class="fas fa-bookmark"></i>';
										echo ' ' . $categories_list; //phpcs:ignore
										$show_sep = true;
										echo '</span>';
									endif;
								}
								if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
									$tags_list = get_the_tag_list( '', ', ' );
									$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
									if ( $tag_link ) {
										$tags_list = strip_tags( $tags_list ); //phpcs:ignore
									}
									if ( $tags_list ) :
										?>
										<div class="tags <?php echo ( $tag_link ) ? 'bdp-no-links' : 'bdp-has-links'; ?>">
											<i class="fas fa-tags"></i>
											<?php
											echo $tags_list; //phpcs:ignore
											$show_sep = true;
											?>
										</div>
										<?php
									endif;
								}
								?>
							</div>
							<?php
						}
					}
					$social_share_position = ( isset( $bdp_settings['social_share_position'] ) && '' != $bdp_settings['social_share_position'] ) ? $bdp_settings['social_share_position'] : ''; //phpcs:ignore
					echo '<div class="bdp_single_social_share_position ' . esc_attr( $social_share_position ) . '_position ">';
					if ( is_single() ) {
						do_action( 'bdp_social_share_text', $bdp_settings );
					}
					Bdp_Utility::get_social_icons( $bdp_settings );
					echo '</div>';
					?>
					</div>
				</div>
			</div>
			<?php do_action( 'bdp_after_single_post_content' ); ?>
		</div>
		<?php
		$bdp_theme                = $bdp_settings['template_name'];
		$display_author           = $bdp_settings['display_author_data'];
		$display_author_biography = $bdp_settings['display_author_biography'];
		$txt_author_title         = isset( $bdp_settings['txtAuthorTitle'] ) ? $bdp_settings['txtAuthorTitle'] : '';
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_image' ), 5, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_content_cover_start' ), 10, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_name' ), 15, 4 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_biography' ), 20, 2 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_social_links' ), 25, 4 );
		add_action( 'bdp_author_detail', array( 'Bdp_Author', 'display_author_content_cover_end' ), 30, 2 );
		if ( isset( $display_author ) && 1 == $display_author ) { //phpcs:ignore
			?>
			<div class="author_div bdp_blog_template">
				<ul class="nav nav-tabs">
					<li class="active">
						<i class="fas fa-user"></i>
						<span class="ts-fab-tab-text"><?php esc_html_e( 'About Author', 'blog-designer-pro' ); ?></span>
					</li>
				</ul>
				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">
						<?php
						do_action( 'author_bio', $bdp_theme, $display_author_biography, $txt_author_title, $bdp_settings );
						?>
					</div>
				</div>
			</div>
			<?php
		}
		add_action( 'bdp_related_post_detail', array( 'Bdp_Posts', 'related_post_title' ), 5, 4 );
		add_action( 'bdp_related_post_detail', array( 'Bdp_Posts', 'related_post_item' ), 10, 9 );
	}
}
