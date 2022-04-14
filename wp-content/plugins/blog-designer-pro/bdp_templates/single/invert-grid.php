<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/single/invert-grid.php.
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
add_action( 'bd_single_design_format_function', 'bdp_single_invert_grid_template', 10, 1 );
if ( ! function_exists( 'bdp_single_invert_grid_template' ) ) {
	/**
	 * Add html for invert-grid template
	 *
	 * @global object $post
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_single_invert_grid_template( $bdp_settings ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		?>
		<div class="blog_template bdp_blog_template invert-grid">
			<?php do_action( 'bdp_before_single_post_content' ); ?>
			<div class="post-body-div">
				<?php
				$class = '';
				if ( isset( $bdp_settings['display_thumbnail'] ) && 1 == $bdp_settings['display_thumbnail'] && has_post_thumbnail() ) { //phpcs:ignore
					?>
					<div class="bdp-post-image">
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
				} else {
					$class = 'no_thumb';
				}
				if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
					$bdp_tax_cat = '';
					if ( 'product' === $post_type ) {
						$bdp_tax_cat = 'product_cat';
					} elseif ( 'download' === $post_type ) {
						$bdp_tax_cat = 'download_category';
					}
					if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) { //phpcs:ignore
						$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true; //phpcs:ignore
						$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
						$sep                = 1;
						?>
							<span class="category-link <?php echo esc_html( $class ); ?>">
								<?php
								foreach ( $product_categories as $category ) {
									if ( 1 != $sep ) { //phpcs:ignore
										?>
										<span class="seperater"><?php echo ', '; ?></span>
										<?php
									}
									echo ( $categories_link ) ? '<a href="' . esc_url( get_term_link( $category->term_id ) ) . '">' : '';
									echo esc_html( $category->name );
									echo ( $categories_link ) ? '</a>' : '';
									$sep++;
								}
								?>
							</span>
							<?php
					}
				} else {
					if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
						?>
						<span class="category-link <?php echo esc_html( $class ); ?>">
							<?php
							$categories_list = get_the_category_list( ', ' );
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
							if ( $categories_link ) {
								$categories_list = strip_tags( $categories_list ); //phpcs:ignore
							}
							if ( $categories_list ) :
								echo ' ' . $categories_list; //phpcs:ignore
								$show_sep = true;
							endif;
							?>
						</span>
						<?php
					}
				}

				?>

				<div class="header-meta">
					<div class="metadatabox">
						<?php
						if ( isset( $bdp_settings['display_date'] ) && 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
							$ar_year   = get_the_time( 'Y' );
							$ar_month  = get_the_time( 'm' );
							$ar_day    = get_the_time( 'd' );
							$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
							?>
							<div class="mdate">
								<?php echo ( 'product' !== $post_type && $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : ''; ?>
								<span class="mdate-month"> <?php echo ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'M' ) ) : esc_html( get_the_time( 'M' ) ); //phpcs:ignore ?> </span>
								<span class="mdate-day"> <?php echo ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? esc_html( get_the_modified_time( 'd' ) ) : esc_html( get_the_time( 'd' ) ); //phpcs:ignore ?> </span>
								<?php echo ( 'product' !== $post_type && $date_link ) ? '</a>' : ''; ?>
							</div>
							<?php
						}
						echo '<div class="header-metadatabox">';
						$display_title = ( isset( $bdp_settings['display_title'] ) && '' != $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1; //phpcs:ignore
						if ( 1 == $display_title ) { //phpcs:ignore
							?>
							<h1 class="post-title <?php echo esc_html( $class ); ?>"><?php echo esc_html( get_the_title() ); ?></h1>
							<?php
						}
						if ( $bdp_settings['display_author'] && 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
							echo '<div class="post-author-box">';
							esc_html_e( 'Posted By', 'blog-designer-pro' );
							echo ': ';
							$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
							?>
							<span class="post-author <?php echo ( $author_link ) ? 'bdp-has-links' : 'bdp-no-links'; ?>">
								<?php echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore ?>
							</span>
							<?php
							echo '</div>';
						}
						if ( isset( $bdp_settings['display_comment'] ) && 1 == $bdp_settings['display_comment'] ) { //phpcs:ignore
							?>
							<div class="metacomments-box">
								<span class="metacomments">
									<?php
									if ( 0 != get_comments_number() ) { //phpcs:ignore
										echo esc_html__( 'Comments', 'blog-designer-pro' ) . ': ';
									}
									if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
										$id     = get_the_ID();
										$number = get_comments_number( $id );
										if ( 0 == $number && ! comments_open() && ! pings_open() ) { //phpcs:ignore
											echo esc_html__( 'Comments are off', 'blog-designer-pro' );
										} else {
											comments_number( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), 1, '%' );
										}
									} else {
											comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), 1, '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
									}
									?>
								</span>
							</div>
							<?php
						}
						if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
							echo do_shortcode( '[likebtn_shortcode]' );
						}
						if ( isset( $bdp_settings['display_post_views'] ) && 0 != $bdp_settings['display_post_views'] ) { //phpcs:ignore
							if ( '' != Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ) ) { //phpcs:ignore
								echo '<div class="display_post_views">';
								echo Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ); //phpcs:ignore
								echo '</div>';
							}
						}
						echo '</div>';
						?>
					</div>
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
				</div>
				<?php
				if ( Bdp_Template_Acf::is_acf_plugin() ) {
					if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
						echo '<div class="bdp_acf_field">';
						do_action( 'bdp_after_single_post_content_data', $bdp_settings, $post->ID );
						echo '</div>';
					}
				}
				?>
				<?php
				$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true; //phpcs:ignore
				?>
					<div class="footer_metabox">
						<?php
						if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
							$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
							$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
							foreach ( $taxonomy_names as $taxonomy_single ) {
								$taxonomy = $taxonomy_single->name;
								$sep      = 1;
								if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
									$term_list            = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
									$taxonomy_link        = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
									$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
									if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
										if ( isset( $term_list ) && ! empty( $term_list ) ) {
											?>
											<div class="tags">
												<span class="link-lable"><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
											<?php
										}
									}
								}
							}
						} else {
							if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
								$tags_list = get_the_tag_list( '', ', ' );
								$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
								if ( $tag_link ) {
									$tags_list = strip_tags( $tags_list ); //phpcs:ignore
								}
								if ( $tags_list ) :
									?>
									<div class="tags <?php echo ( $tag_link ) ? 'bdp-no-links' : 'bdp-has-links'; ?>">
										<?php
										echo '<span class="link-lable">' . esc_html__( 'Tags: ', 'blog-designer-pro' ) . '</span>';
										echo $tags_list; //phpcs:ignore
										?>
									</div>
									<?php
								endif;
							}
						}
						if ( $social_share && ( ( 1 == $bdp_settings['facebook_link'] ) || ( 1 == $bdp_settings['twitter_link'] ) || ( 1 == $bdp_settings['linkedin_link'] ) || ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) || ( 1 == $bdp_settings['pinterest_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) || ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) || ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) || ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) || ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) || ( 1 == $bdp_settings['whatsapp_link'] ) ) ) { //phpcs:ignore
                          $social_share_position = ( isset( $bdp_settings['social_share_position'] ) && '' != $bdp_settings['social_share_position'] ) ? $bdp_settings['social_share_position'] : ''; //phpcs:ignore
							echo '<div class="bdp_single_social_share_position ' . esc_attr( $social_share_position ) . '_position ">';
							if ( is_single() ) {
								do_action( 'bdp_social_share_text', $bdp_settings );
							}
							Bdp_Utility::get_social_icons( $bdp_settings );
							echo '</div>';

						}
						?>
					</div>
			   
				<?php do_action( 'bdp_after_single_post_content' ); ?>
			</div>
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
