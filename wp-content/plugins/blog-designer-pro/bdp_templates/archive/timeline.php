<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/timeline.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_timeline_template', 10, 2 );
if ( ! function_exists( 'bdp_archive_timeline_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter alter.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_timeline_template( $bdp_settings, $alter ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$format            = get_post_format();
		$bdp_all_post_type = array( 'product', 'download' );
		$post_format       = '';
		if ( 'status' === $format ) {
			$post_format = 'fas fa-comment';
		} elseif ( 'aside' === $format ) {
			$post_format = 'fas fa-file-alt';
		} elseif ( 'image' === $format ) {
			$post_format = 'far fa-file-image';
		} elseif ( 'gallery' === $format ) {
			$post_format = 'far fa-file-image';

		} elseif ( 'link' === $format ) {
			$post_format = 'fas fa-link';
		} elseif ( 'quote' === $format ) {
			$post_format = 'fas fa-quote-left';
		} elseif ( 'audio' === $format ) {
			$post_format = 'fas fa-music';
		} elseif ( 'video' === $format ) {
			$post_format = 'fas fa-video';
		} elseif ( 'chat' === $format ) {
			$post_format = 'fab fa-weixin';
		} else {
			$post_format = 'fas fa-thumbtack';
		}
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
				$style                 = 'style = "background-color: transparent; background-attachment: ' . $background_attachment . '; background-size: cover; background-image: url(' . $url . '); "';
			}
		}
		?>
		<div class="blog_template bdp_blog_template timeline blog-wrap <?php echo esc_attr( $alter ); ?>">
			<?php do_action( 'bdp_before_post_content' ); ?>
			<div class="post_hentry  <?php echo esc_attr( $post_format ); ?>">
				<div class="post_content_wrap animateblock" <?php echo ( isset( $style ) && '' != $style ) ? $style : ''; //phpcs:ignore ?>>
					<div class="post_wrapper box-blog">
						<?php
						$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
						if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
							?>
							<div class="label_featured_post"><span><?php echo esc_html( $label_featured_post ); ?></span></div> 
							<?php
						}
						?>
						<?php
						$show_fearue_image = 1;
						if ( isset( $bdp_settings['blog_background_image'] ) && 1 == $bdp_settings['blog_background_image'] ) { //phpcs:ignore
							$show_fearue_image = 0;
						}
						if ( 1 == $show_fearue_image ) { //phpcs:ignore
							if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
								?>
								<div class="bdp-post-image post-video bdp-video">
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
									?>
									<div class="photo bdp-post-image">
										<?php
										echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
										echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
										echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
										echo ( $bdp_post_image_link ) ? '</a>' : '';
										if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
											echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
										}
										if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
											$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
											echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
											do_action( 'bdp_woocommerce_sale_tag' );
											echo '</div>';
										}
										echo '</figure>';
										?>
									</div>
									<?php
								} else {
									$display_date = $bdp_settings['display_date'];
									if ( 1 == $display_date ) { //phpcs:ignore
										?>
										<div class="no_post_media">
										</div>
										<?php
									}
								}
							}
						} else {
							$display_date = $bdp_settings['display_date'];
							if ( 1 == $display_date ) { //phpcs:ignore
								?>
								<div class="no_post_media">
								</div>
								<?php
							}
						}
						?>
						<div class="desc">
							<h3 class="entry-title">
								<?php
								$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
								if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
									?>
									<a href="<?php the_permalink(); ?>">
									<?php } ?>
									<?php
									the_title();
									if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
										?>
									</a>
								<?php } ?>
							</h3>
							<?php
							if ( 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] || 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
								?>
								<div class="date_wrap">
									<?php
									$display_author = $bdp_settings['display_author'];
									if ( 1 == $display_author ) { //phpcs:ignore
										$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
										?>
										<span class="posted_by <?php echo ( $author_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
											<i class="fas fa-user"></i>
											<span> <?php echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore ?> </span>
										</span>
										<?php
									}
									$display_date = $bdp_settings['display_date'];
									$ar_year      = get_the_time( 'Y' );
									$ar_month     = get_the_time( 'm' );
									$ar_day       = get_the_time( 'd' );
									if ( 1 == $display_date ) { //phpcs:ignore
										$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
										?>
										<div class="datetime">
											<?php echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : ''; ?>
											<span class="month"><?php the_time( 'M' ); ?></span>
											<span class="date"><?php the_time( 'd' ); ?></span>
											<?php echo '</a>'; ?>
										</div>
										<?php
									}
									if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
										?>
										<span class="post-comment">
											<i class="fas fa-comment"></i>
											<?php
											if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
												comments_number( __( 'No Comments', 'blog-designer-pro' ), '1 ' . __( 'comment', 'blog-designer-pro' ), '% ' . __( 'comments', 'blog-designer-pro' ) );
											} else {
												comments_popup_link( __( 'Leave a Comment', 'blog-designer-pro' ), __( '1 comment', 'blog-designer-pro' ), '% ' . __( 'comments', 'blog-designer-pro' ), 'comments-link', __( 'Comments are off', 'blog-designer-pro' ) );
											}
											?>
										</span>
										<?php
									}

									if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
										echo do_shortcode( '[likebtn_shortcode]' );
									}
									?>
								</div>
								<?php
							}
							if ( 'product' === $post_type ) {
								do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
							}
							if ( 'download' === $post_type ) {
								do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
							}
							?>
							<?php
							if ( Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ) ) {
								?>
								<div class="post_content">
									<?php
									echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore
									?>
									<?php
									$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
									$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
									if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) : //phpcs:ignore
										$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : __( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
										$post_link   = get_permalink( $post->ID );
										if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
											$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
										}
										if ( 1 == $read_more_on ) { //phpcs:ignore
											echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
										}
										?>
									<?php endif; ?>
								</div>
									<?php if ( 2 == $read_more_on && 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) { //phpcs:ignore ?>
									<div class="read_more">
										<?php
										echo '<a class="more-tag" href="' . esc_url( $post_link ) . '"><i class="fas fa-plus"></i> ' . esc_html( $readmoretxt ) . ' </a>';
										?>
									</div>
										<?php
									}
							}
							?>
						</div>
					</div>
					<?php
					$social_share = ( isset( $bdp_settings['social_share'] ) && 0 == $bdp_settings['social_share'] ) ? false : true; //phpcs:ignore
					?>
						<footer class="blog_footer text-capitalize">
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
									if ( isset( $taxonomy ) ) {
										if ( isset( $term_list ) && ! empty( $term_list ) ) {
											?>
											<span class="categories category-link <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
												<span class="link-lable">  
												<?php
												if ( in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
													?>
													<i class="fas fa-folder"></i> 
													<?php
												} else {
													?>
													<i class="fas fa-bookmark"></i> <?php } ?>&nbsp;<?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
												<?php
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
											</span>
											<?php
										}
									}
								}
							}
						} else {
							if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
								$categories_list = get_the_category_list( ', ' );
								$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
								?>
								<span class="categories <?php echo ( $categories_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
									<span class="link-lable"> <i class="fas fa-folder"></i> <?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?>:&nbsp; </span>
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
							}
							if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
								$tags_list = get_the_tag_list( '', ', ' );
								$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
								if ( $tag_link ) {
									$tags_list = strip_tags( $tags_list ); //phpcs:ignore
								}
								if ( $tags_list ) :
									?>
									<span class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">
										<span class="link-lable"> <i class="fas fa-bookmark"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?>:&nbsp; </span>
										<?php
										echo $tags_list; //phpcs:ignore
										$show_sep = true;
										?>
									</span>
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
						if ( $social_share && ( ( 1 == $bdp_settings['facebook_link'] ) || ( 1 == $bdp_settings['twitter_link'] ) || ( 1 == $bdp_settings['linkedin_link'] ) || ( isset( $bdp_settings['email_link'] ) && 1 == $bdp_settings['email_link'] ) || ( 1 == $bdp_settings['pinterest_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['pocket_link'] ) && 1 == $bdp_settings['pocket_link'] ) || ( isset( $bdp_settings['skype_link'] ) && 1 == $bdp_settings['skype_link'] ) || ( isset( $bdp_settings['telegram_link'] ) && 1 == $bdp_settings['telegram_link'] ) || ( isset( $bdp_settings['reddit_link'] ) && 1 == $bdp_settings['reddit_link'] ) || ( isset( $bdp_settings['digg_link'] ) && 1 == $bdp_settings['digg_link'] ) || ( isset( $bdp_settings['tumblr_link'] ) && 1 == $bdp_settings['tumblr_link'] ) || ( isset( $bdp_settings['wordpress_link'] ) && 1 == $bdp_settings['wordpress_link'] ) || ( 1 == $bdp_settings['whatsapp_link'] ) ) ) { //phpcs:ignore
								Bdp_Utility::get_social_icons( $bdp_settings );
						}
						?>
						</footer>
				   
				</div>
			</div>
			<?php do_action( 'bdp_after_post_content' ); ?>
		</div>
		<?php
		do_action( 'bdp_separator_after_post' );
	}
}
