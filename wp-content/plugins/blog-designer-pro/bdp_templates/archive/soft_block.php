<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/soft_block.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_soft_block_template', 10, 1 );

if ( ! function_exists( 'bdp_archive_soft_block_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array $bdp_settings settings.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_soft_block_template( $bdp_settings ) {
		global $post;
		$post_type          = get_post_type( $post->ID );
		$bdp_all_post_type  = array( 'product', 'download' );
		$image_hover_effect = 'bdp-post-image ';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) { //phpcs:ignore
			$image_hover_effect .= ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : ''; //phpcs:ignore
		}
		$post_thumbnail = 'full';
		$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id( $post->ID ), $post->ID );
		$class          = '';
		if ( empty( $thumbnail ) ) {
			$post_video_thumbnail = Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings );
			if ( '' === $post_video_thumbnail ) {
				$class = 'content-full-width';
			}
		}
		?>
		<div class="bdp_blog_template soft-block-post-wrapper bdp_blog_single_post_wrapp">
		<div class="bdp_wrapper soft_block_wrapper">
		<div class="soft-block-flex">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="post-content-area <?php echo esc_attr( $class ); ?>">
				<?php
				$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
				if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
					echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_url( get_the_title() ) . '">';
				}
				?>
				<h2 class="post-title"><?php echo esc_attr( get_the_title() ); ?></h2>
				<?php
				if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
					echo '</a>';
				}
				?>
				<div class="post-meta">
					<?php
					if ( 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
						$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
						echo '<span class="author-name">';
						echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore
						echo '</span>';
					}
					if ( 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
						$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
						?>
						<span class="date-meta">
							<?php
							$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
							$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );

							echo '<i class="far fa-calendar-alt"></i>';
							echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							?>
						</span>
						<?php
					}
					if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
						?>
						<span class="post-comment">
							<i class="fas fa-comment"></i>
							<?php
							if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
								comments_number( '0', '1', '%' );
							} else {
								comments_popup_link( '0', '1', '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
							}
							?>
						</span>
						<?php
					}
					if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
						echo do_shortcode( '[likebtn_shortcode]' );
					}
					?>
					<div class="meeting-day">
					<?php $ar_day = get_the_time( 'd' ); ?>
					<p class="day-inner"><?php echo esc_html( $ar_day ); ?></p>
				</div>
				</div>
				<div class="blog_footer">
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
										echo '<div class="bdp-custom-category-data">';
										if ( isset( $term_list ) && ! empty( $term_list ) ) {
											?>
											<span class="category-link<?php echo ( $taxonomy_link ) ? '' : ' categories_link'; ?>">
											<?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
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
										echo '</div>';
									}
								}
							}
						} else {
							if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
								$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
								?>
								<span class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
									<span class="link-lable"> <i class="fas fa-folder"></i> <?php esc_html_e( 'Category', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
									<?php
									$categories_list = get_the_category_list( ', ' );
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
							if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
								$tags_list = get_the_tag_list( '', ', ' );
								$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
								if ( $tag_link ) {
									$tags_list = strip_tags( $tags_list ); //phpcs:ignore
								}
								if ( $tags_list ) :
									?>
									<div class="tags<?php echo ( $tag_link ) ? ' tag_link' : ''; ?>">
										<span class="link-lable"> <i class="fas fa-bookmark"></i> <?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
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
					</div>
				</div>
				<?php
				if ( 'product' === $post_type ) {
					do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
				}
				if ( 'download' === $post_type ) {
					do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
				}
				?>
				<div class="post_content">
					<?php
					echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore
					$read_more_on = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 1;
					$readmoretxt  = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
					$post_link    = get_permalink( $post->ID );
					if ( '' != get_the_content() && 1 == $read_more_on ) { //phpcs:ignore
						$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
						if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore

							if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
								$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
							}
							echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
						}
					}
					?>
				</div>
				<?php
				if ( '' != $bdp_settings['txtReadmoretext'] && 1 == $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) { //phpcs:ignore
					?>
					<div class="read-more">
						<?php echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>'; ?>
					</div>
					<?php
				}
				Bdp_Utility::get_social_icons( $bdp_settings );
				?>
			</div>

			<div class="post-media">
				<?php
				if ( empty( $thumbnail ) ) {
					$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
					if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
						?>
						<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
						<?php
					}
				}
				?>
				<?php
				if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && $bdp_settings['rss_use_excerpt'] == 1 ) { //phpcs:ignore
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
					$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
					if ( ! empty( $thumbnail ) ) {
						$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
						if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
							?>
							<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
							<?php
						}
						echo '<figure class="bdp-mb-15 ' . esc_attr( $image_hover_effect ) . '">';
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
						echo ( $bdp_post_image_link ) ? '</a>' : '';
						if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
							$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
							echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
							do_action( 'bdp_woocommerce_sale_tag' );
							echo '</div>';
						}
						if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
							?>
							<div class="bdp-pinterest-share-image">
								<?php $img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
								<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
							</div>
							<?php
						}

						echo '</figure>';
					}
				}
				?>
				</div>
				<?php do_action( 'bdp_after_archive_post_content' ); ?>
			</div>
		</div>
	</div>
		<?php
	}
}
