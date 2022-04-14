<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/glamour.php.
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

add_action( 'bd_archive_design_format_function', 'bdp_archive_glamour_template', 10, 1 );

if ( ! function_exists( 'bdp_archive_glamour_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array $bdp_settings settings.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_glamour_template( $bdp_settings ) {
		global $post;
		$post_type          = get_post_type( $post->ID );
		$bdp_all_post_type  = array( 'product', 'download' );
		$image_hover_effect = '';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) { //phpcs:ignore
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : ''; //phpcs:ignore
		}
		$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true; //phpcs:ignore
		$apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0'; 
		$template_name     = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';
		?>
		<div class="bdp_blog_template glamour-post-wrapper">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="glamour-blog">
				<?php
				$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
				if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
					?>
					<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
					<?php
				}
				?>
				<div class="glamour-wrapper">
					<?php
					if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
						?>
						<div class="bdp-post-image post-video">
							<?php
							if ( 'quote' === get_post_format() ) {
								if ( has_post_thumbnail() ) {
									$post_thumbnail = 'full';
									$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
									echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
									echo '<div class="upper_image_wrapper">';
									echo Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ); //phpcs:ignore
									echo '</div>';
								}
							} elseif ( 'link' === get_post_format() ) {
								if ( has_post_thumbnail() ) {
									$post_thumbnail = 'full';
									$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
									echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
									echo '<div class="upper_image_wrapper bdp_link_post_format">';
									echo Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ); //phpcs:ignore
									echo '</div>';
								}
							} else {
								echo Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ); //phpcs:ignore
							}
							?>
						</div>
						<?php
					} else {
						$post_thumbnail      = 'full';
						$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
						$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
						if ( ! empty( $thumbnail ) ) {
							echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
							echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="chapter-img-link">' : '';
							echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
							echo ( $bdp_post_image_link ) ? '</a>' : '';
							if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
								?>
								<div class="bdp-pinterest-share-image">
									<?php $img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
									<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
								</div>
								<?php
							}
							if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
								$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
								echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
								do_action( 'bdp_woocommerce_sale_tag' );
								echo '</div>';
							}
							echo '</figure>';
						}
					}
					?>
				</div>
				<div class="glamour-opacity"> </div>
				<div class="glamour-inner">
					<?php
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
								<div class="post-categories">
									<?php
									foreach ( $product_categories as $category ) {
										if ( 1 != $sep ) { //phpcs:ignore
											?>
											<span class="seperater">&nbsp;&nbsp;&nbsp;</span>
											<?php
										}
										echo ( $categories_link ) ? '<a href="' . esc_url( get_term_link( $category->term_id ) ) . '">' : '';
										echo esc_html( $category->name );
										echo ( $categories_link ) ? '</a>' : '';
										$sep++;
									}
									?>
								</div>
							<?php
						}
					} else {
						if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
							?>
							<div class="post-categories">
								<?php
								$categories_list = get_the_category_list( '&nbsp;&nbsp;&nbsp;' );
								$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
								if ( $categories_link ) {
									$categories_list = strip_tags( $categories_list ); //phpcs:ignore
								}
								if ( $categories_list ) {
									echo $categories_list; //phpcs:ignore
								}
								?>
							</div>
							<?php
						}
					}
					?>
					<div class="post-title">
						<h2>
						<?php
						$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
						if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
							echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_url( get_the_title() ) . '">';
						}
						echo esc_html( get_the_title() );
						if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
							echo '</a>';
						}
						?>
						</h2>
					</div>
					<?php
					if ( 'product' === $post_type ) {
						do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
					}
					if ( 'download' === $post_type ) {
						do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
					}
					$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
					$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
					if ( 1 == $read_more_link ) { //phpcs:ignore
						$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
						$post_link   = get_permalink( $post->ID );
						if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
							$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
						}
					}
					if ( $bdp_settings['txtExcerptlength'] > 0 ) {
						?>
						<div class="post-content">
							<?php
							echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore
							if ( 1 == $read_more_on && 1 == $read_more_link ) { //phpcs:ignore
								echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . '</a>';
							}
							?>
						</div>
						<?php
					}
					if ( 1 == $read_more_link && 2 == $read_more_on && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
						?>
						<div class="read-more-div">
							<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>">
								<?php echo esc_html( $readmoretxt ); ?>
							</a>
						</div>
						<?php
					}
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
								$bdp_tag_taxonomy     = array( 'product_tag', 'download_tag' );
								if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
									if ( isset( $term_list ) && ! empty( $term_list ) ) {
										?>
										<div class="tags">
											<?php
											if ( in_array( $taxonomy, $bdp_tag_taxonomy ) ) { //phpcs:ignore
												echo '<i class="fas fa-tags"></i>';
											}
											if ( ! in_array( $taxonomy, $bdp_tag_taxonomy ) ) { //phpcs:ignore
												?>
												<span class="link-lable"><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span> 
												<?php
											}
											foreach ( $term_list as $term_nm ) {
												$term_link = get_term_link( $term_nm );
												if ( 1 != $sep ) { //phpcs:ignore
													?>
													<span class="seperater"><?php echo ', '; ?></span>
													<?php
												}
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
								<div class="tags">
									<i class="fas fa-tags"></i>
									<?php
									echo $tags_list; //phpcs:ignore
									$show_sep = true;
									?>
								</div>
								<?php
							endif;
						}
					}
					if ( Bdp_Template_Acf::is_acf_plugin() ) {
						if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
							echo '<div class="bdp_acf_field">';
							do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
							echo '</div>';
						}
					}
					?>

					<div class="footer-entry glamour-blog-footer">
						<div class="post-meta glamour-meta">
							<?php
							$display_author  = $bdp_settings['display_author'];
							$display_date    = $bdp_settings['display_date'];
							$display_comment = $bdp_settings['display_comment_count'];
							$separator       = '<span class="bdp-separator"> | </span>';

							if ( 1 == $display_author ) { //phpcs:ignore
								echo '<div class="post-author">';
								$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
								echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore
								echo '</div>';
							}
							?>

							<?php
							if ( 1 == $display_date ) { //phpcs:ignore
								$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
								?>
								<div class="date-meta">
									<?php
									if ( 1 == $display_author ) { //phpcs:ignore
										echo $separator; //phpcs:ignore
									}
									$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
									$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
									$ar_year     = get_the_time( 'Y' );
									$ar_month    = get_the_time( 'm' );
									$ar_day      = get_the_time( 'd' );

									echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
									echo esc_html( $bdp_date );
									echo ( $date_link ) ? '</a>' : '';
									?>
								</div>
								<?php
							}
							?>

							<?php
							if ( 1 == $display_comment ) { //phpcs:ignore
								?>
								<div class="post-comment">
									<?php
									if ( 1 == $display_author || 1 == $display_date ) { //phpcs:ignore
										echo $separator; //phpcs:ignore
									}
									echo '<i class="fas fa-comment"></i> ';
									if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
										comments_number( '0', '1', '%' );
									} else {
										comments_popup_link( '0', '1', '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
									}
									?>
								</div>
								<?php
							}
							?>

						</div>
						<div class="glamour-footer-icon">
							<?php
							if ( $social_share ) {
								if ( ( 1 == $bdp_settings['facebook_link'] ) || ( 1 == $bdp_settings['twitter_link'] ) || ( 1 == $bdp_settings['linkedin_link'] ) || ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) || ( 1 == $bdp_settings['pinterest_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) || ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) || ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) || ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) || ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) || ( 1 == $bdp_settings['whatsapp_link'] ) ) { //phpcs:ignore
									echo '<span class="glamour-social-div"><a href="javascript:void(0)" class="glamour-social-links"> <i class="fas fa-share-alt"></i></a></span>';
								}
							}
							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
								echo do_shortcode( '[likebtn_shortcode]' );
							}
							?>
						</div>
					</div>
				</div>

				<?php
				if ( $social_share ) {
					if ( ( 1 == $bdp_settings['facebook_link'] ) || ( 1 == $bdp_settings['twitter_link'] ) || ( 1 == $bdp_settings['linkedin_link'] ) || ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) || ( 1 == $bdp_settings['pinterest_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) || ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) || ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) || ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) || ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) || ( 1 == $bdp_settings['whatsapp_link'] ) ) { //phpcs:ignore
								echo '<div class="glamour-social-cover">';
								Bdp_Utility::get_social_icons( $bdp_settings );
								echo '<span class="glamour-social-div-closed"><a href="javascript:void(0)" class="glamour-social-links-closed"> <i class="fas fa-times"></i></a></span>';
								echo '</div>';
					}
				}
				?>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</div>
		<?php
	}
}

