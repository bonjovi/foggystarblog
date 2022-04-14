<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/timeline.php.
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
global $post;

$image_hover_effect = '';
if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) { //phpcs:ignore
	$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : ''; //phpcs:ignore
}
if ( isset( $bdp_settings['blog_background_image'] ) && 1 == $bdp_settings['blog_background_image'] ) { //phpcs:ignore
	if ( has_post_thumbnail() ) {
		$url = wp_get_attachment_url( get_post_thumbnail_id() );
	} elseif ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) { //phpcs:ignore
		$url = wp_get_attachment_url( $bdp_settings['bdp_default_image_id'] );
	} else {
		$url = '';
	}
	if ( '' != $url ) { //phpcs:ignore
		$background_attachment = ( isset( $bdp_settings['blog_background_image_style'] ) && 1 == $bdp_settings['blog_background_image_style'] ) ? 'fixed' : 'scroll'; //phpcs:ignore
		$style                 = 'style = "background-color: transparent; background-attachment: ' . $background_attachment . '; background-size: cover; background-image: url(' . esc_attr( $url ) . '); "';
	}
}
$bdp_all_post_type = array( 'product', 'download' );
?>

<div class="blog_template bdp_blog_template bdp_post_content_tabbed blog-wrap bdp_blog_single_post_wrapp">
	<?php do_action( 'bdp_before_post_content' ); ?>
		<div class="bdp-post-inner-content">
			<div class="bdp-img">
				<?php
				$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
				if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
					?>
					<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
					<?php
				}
				if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
					?>
					<div class="bdp-post-image post-video">
						<?php
						if ( 'quote' === get_post_format() ) {
							if ( has_post_thumbnail() ) {
								$post_thumbnail = 'full';
								if ( 1 == $tabbed_post_style ) { //phpcs:ignore
									$post_thumbnail = 'tabbed_thumb';
								}
								$thumbnail = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
								if ( 1 == $tabbed_post_style ) { //phpcs:ignore
									$post_thumbnail = 'tabbed_thumb';
									$thumbnail      = get_the_post_thumbnail( $post->ID, $post_thumbnail );
								}
								echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
								echo '<div class="upper_image_wrapper">';
								echo Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ); //phpcs:ignore
								echo '</div>';
							}
						} elseif ( 'link' === get_post_format() ) {
							if ( has_post_thumbnail() ) {
								$post_thumbnail = 'full';
								if ( 1 == $tabbed_post_style ) { //phpcs:ignore
									$post_thumbnail = 'tabbed_thumb';
								}
								$thumbnail = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
								if ( 1 == $tabbed_post_style ) { //phpcs:ignore
									$post_thumbnail = 'tabbed_thumb';
									$thumbnail      = get_the_post_thumbnail( $post->ID, $post_thumbnail );
								}
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
					?>
					<div class="bdp-post-image">
					<?php
						$post_thumbnail = 'brit_co_img';
						$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
					if ( 1 == $tabbed_post_style ) { //phpcs:ignore
						$post_thumbnail = 'tabbed_thumb';
						$thumbnail      = get_the_post_thumbnail( $post->ID, $post_thumbnail );
					}
						$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
					if ( ! empty( $thumbnail ) ) {
						echo '<figure class="bdp-mb-15 ' . esc_attr( $image_hover_effect ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
						echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
						echo ( $bdp_post_image_link ) ? '</a>' : '';

						if ( 0 == $tabbed_post_style ) { //phpcs:ignore
							if ( 0 == $tabbed_post_style && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] && isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] ) { //phpcs:ignore
								echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
							}
							if ( class_exists( 'woocommerce' ) && 'product' === $bdp_settings['custom_post_type'] ) {
								if ( isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
									$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
									echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
									do_action( 'bdp_woocommerce_sale_tag' );
									echo '</div>';
								}
							}
						}

						echo '</figure>';
					}
					?>
					</div>
				<?php } ?>
			</div>
			<?php
			if ( 1 == $tabbed_post_style ) { //phpcs:ignore
				echo '<div class="post-inner-right">';
			}
			if ( 0 == $tabbed_post_style ) { //phpcs:ignore
				echo '<h2 class="post-title bdp-mb-15">';
			} else {
				echo '<h3 class="post-title bdp-mb-15">';
			}
					$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
			if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
				?>
				<a href="<?php esc_url( the_permalink() ); ?>">
				<?php
			}
			the_title();
			if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
				?>
				</a>
				<?php
			}
			if ( 0 == $tabbed_post_style ) { //phpcs:ignore
				echo '</h2>';
			} else {
				echo '</h3>';
			}
			?>
				<div class="bdp-tabbed-meta-content">
					<?php
					$display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1;
					$display_date   = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1;
					if ( 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
						echo '<span class="author">' . esc_html__( 'By', 'blog-designer-pro' ) . '&nbsp;' . Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ) . '</span>'; //phpcs:ignore
					}
					if ( 1 == $bdp_settings['display_author'] && 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
						echo '<span class="bdp-separator"> | </span>';
					}
					if ( 1 == $display_date ) { //phpcs:ignore
						$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
						$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
						$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
						$ar_year     = get_the_time( 'Y' );
						$ar_month    = get_the_time( 'm' );
						$ar_day      = get_the_time( 'd' );
						$date_class  = ( $date_link ) ? 'bdp_has_links' : 'bdp_no_links';
						echo '<span class="bdp-post-date post-date ' . esc_attr( $date_class ) . '">';
						echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
						echo esc_attr( $bdp_date );
						echo ( $date_link ) ? '</a>' : '';
						echo '</span>';
					}

					if ( 'post' === $bdp_settings['custom_post_type'] ) {
						if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
							if ( 1 == $bdp_settings['display_date'] && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
								echo '<span class="bdp-separator"> | </span>';
							}
							$categories_list = get_the_category_list( ', ' );
							$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
							?>
							<span class="post-category bdp-mb-15 <?php echo ( $categories_link ) ? 'bdp-no-link' : 'bdp-has-links'; ?>">
								<?php
								if ( $categories_link ) {
									$categories_list = strip_tags( $categories_list ); //phpcs:ignore
								}
								if ( $categories_list ) :
									echo $categories_list; //phpcs:ignore
									$show_sep = true;
								endif;
								?>
							</span>
							<?php
							if ( 1 == $bdp_settings['display_category'] && 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
								echo '<span class="bdp-separator"> | </span>';
							}
						}
					} elseif ( isset( $bdp_settings['custom_post_type'] ) && in_array( $bdp_settings['custom_post_type'], $bdp_all_post_type ) ) { //phpcs:ignore
						$bdp_tax_cat = '';
						if ( 'product' === $bdp_settings['custom_post_type'] ) {
							$bdp_tax_cat = 'product_cat';
						} elseif ( 'download' === $bdp_settings['custom_post_type'] ) {
							$bdp_tax_cat = 'download_category';
						}
						if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) { //phpcs:ignore
							if ( 1 == $bdp_settings['display_date'] && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) { //phpcs:ignore
								echo '<span class="bdp-separator"> | </span>';
							}
							$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true; //phpcs:ignore
							$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
							$sep                = 1;
							?>
								<span class="post-category bdp-mb-15 <?php echo ( $categories_link ) ? ' bdp-no-link' : 'bdp-has-links'; ?>">
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
							if ( 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] && 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
								echo '<span class="bdp-separator"> | </span>';
							}
						}
					}

					if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comment">
								<?php
								if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
									comments_number( '0', '1', '%' );
								} else {
									comments_popup_link( '0', '1', '%' );
								}
								?>
								<i class="fas fa-comments"></i>
							</span>
							<?php
						endif;
						if ( 1 == $bdp_settings['display_comment_count'] && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
							echo '<span class="bdp-separator"> | </span>';
						}
					}
					if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
						echo do_shortcode( '[likebtn_shortcode]' );
					}
					?>
				</div>
				<?php
				if ( 0 == $tabbed_post_style ) { //phpcs:ignore
					if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
						do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
					}
					if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
						do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
					}
					?>
					<div class="bdp-post-content">
						<?php
						echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore
						$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
						$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 1;
						$readmoretxt    = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
						if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
							$post_link = get_permalink( $post->ID );
							if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
								$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
							}
							if ( 1 == $read_more_on ) { //phpcs:ignore
								echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
							}
						}
						if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) { //phpcs:ignore
							$post_link = get_permalink( $post->ID );
							$post_link = get_permalink( $post->ID );
							if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
								$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
							}
							?>
							<div class="read-more bdp-mb-15">
								<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>'; ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
					if ( 'post' === $bdp_settings['custom_post_type'] ) {
						if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
							$tags_list = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? get_the_tag_list( '', ', ' ) : get_the_tag_list( '', ', ' ); //phpcs:ignore
							$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
							if ( $tag_link ) {
								$tags_list = strip_tags( $tags_list ); //phpcs:ignore
							}
							if ( $tags_list ) :
								?>
								<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
									<?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>&nbsp;:&nbsp;
									<?php
									echo $tags_list; //phpcs:ignore
									$show_sep = true;
									?>
								</div>
								<?php
							endif;
						}
					} else {
						$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
						$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
						foreach ( $taxonomy_names as $taxonomy_single ) {
							$taxonomy = $taxonomy_single->name; //phpcs:ignore
							$sep      = 1;
							if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
								$term_list            = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
								$taxonomy_link        = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
								$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
								if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
									if ( isset( $term_list ) && ! empty( $term_list ) ) {
										?>
										<div class="tags taxonomies <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
											<span class="link-lable"><?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
											<?php
											foreach ( $term_list as $term_nm ) {
												$term_link = get_term_link( $term_nm );

												if ( 1 != $sep ) { //phpcs:ignore
													?>
													<span class="seperater"><?php echo ', '; ?></span>
													<?php
												}
												echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
												echo esc_attr( $term_nm->name );
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
					}
					if ( Bdp_Template_Acf::is_acf_plugin() ) {
						if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
							echo '<div class="bdp_acf_field">';
							do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
							echo '</div>';
						}
					}
					if ( isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
						?>
					<div class="social-share">
						<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
					</div>
						<?php
					}
					?>
				<?php } ?>
			<?php
			if ( 1 == $tabbed_post_style ) { //phpcs:ignore
				echo '</div>';
			}
			?>
		</div>
	<?php do_action( 'bdp_after_post_content' ); ?>
</div>
<?php
do_action( 'bdp_separator_after_post' );
