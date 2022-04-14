<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/explore.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_explore_template', 10, 2 );
if ( ! function_exists( 'bdp_archive_explore_template' ) ) {
	/**
	 * Add html for boxy template
	 *
	 * @param array  $bdp_settings settings.
	 * @param string $alter_class class.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_explore_template( $bdp_settings, $alter_class ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		$total_col         = $bdp_settings['template_columns'];
		$total_height      = $bdp_settings['template_grid_height'];
		$grid_height       = ( isset( $bdp_settings['blog_grid_height'] ) && 1 != $bdp_settings['blog_grid_height'] ) ? false : true; //phpcs:ignore
		$grid_skin         = $bdp_settings['template_grid_skin'];
		$full_height       = ( $grid_height ) ? 'height: ' . $total_height . 'px;' : '';
		$alter_class;
		$col_class = '';

		if ( 'repeat' === $grid_skin ) {
			if ( 1 == $alter_class % 5 ) { //phpcs:ignore
				$col_class = 'two_column large-col';
			} else {
				$col_class = 'two_column full-col small-col';
			}
		} elseif ( 'default' === $grid_skin ) {
			if ( 1 == $alter_class ) { //phpcs:ignore
				$col_class = 'two_column large-col full-col';
			} else {
				$col_class = 'two_column small-col full-col';
			}
		} elseif ( 'reverse' === $grid_skin ) {
			if ( ( 1 == $alter_class % 10 ) || 7 == $alter_class % 10 ) { //phpcs:ignore
				$col_class = 'two_column large-col full-col';
			} else {
				$col_class = 'two_column small-col full-col';
			}
		}

		$div_height = ( '' != $full_height ) ? 'style="' . esc_attr( $full_height ) . '"' : ''; //phpcs:ignore
		if ( has_post_thumbnail() ) {
			$post_thumbnail = 'full';
			$resized_image  = apply_filters( 'bdp_post_thumbnail_filter', get_the_post_thumbnail( $post->ID, $post_thumbnail ), $post->ID );
		}

		$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
		$class_name          = 'blog_template bdp_blog_template explore';
		if ( '' != $col_class ) { //phpcs:ignore
			$class_name .= ' ' . $col_class;
		}
		?>
		<div class="<?php echo esc_attr( $class_name ); ?>" <?php echo $div_height; ?>>
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="post_hentry">
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
					?>
					<div class="bdp-post-image">
					<?php
					if ( has_post_thumbnail() ) {
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						echo $resized_image; //phpcs:ignore
						echo ( $bdp_post_image_link ) ? '</a>' : '';

						if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
							?>
								<div class="bdp-pinterest-share-image">
									<?php
									$img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
									?>
									<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
								</div>
								<?php
						}
					} elseif ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) { //phpcs:ignore
						$thumbnail = wp_get_attachment_image( $bdp_settings['bdp_default_image_id'], 'full' );
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
						echo ( $bdp_post_image_link ) ? '</a>' : '';
					} else {
						$thumbnail = Bdp_Posts::get_sample_image( 'boxy_clean', $post->ID );
						echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
						echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
						echo ( $bdp_post_image_link ) ? '</a>' : '';
					}
					if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
						$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
						echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
						do_action( 'bdp_woocommerce_sale_tag' );
						echo '</div>';
					}
					?>
					</div>
				<?php } ?>
				<div class="grid-overlay">
					<div class="blog_header">
						<?php
						if ( has_post_thumbnail() ) {
							if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
								echo '<div class="bdp-post">';
								echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
								echo '</div>';
							}
						}
						?>
						<div class="post-title">
							<?php
							$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
							if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
								?>
								<a href="<?php esc_url( the_permalink() ); ?>">
								<?php } ?>
								<?php
								echo esc_html( get_the_title() );
								if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
									?>
								</a>
							<?php } ?>
						</div>
						<?php
						if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] || 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
							?>
							<div class="metadatabox">
								<?php
								if ( 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
									echo '<div class="metabox-top">';
									if ( 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
										?>
										<div class="mauthor">
											<i class="fas fa-user"></i>
											<?php echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore ?>
										</div>
										<?php
									}
									if ( 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
										$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
										?>
										<div class="post-date">
											<i class="far fa-calendar-alt"></i>
											<?php
											$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
											$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
											$ar_year     = get_the_time( 'Y' );
											$ar_month    = get_the_time( 'm' );
											$ar_day      = get_the_time( 'd' );
											echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
											echo esc_html( $bdp_date );
											echo ( $date_link ) ? '</a>' : '';
											?>
										</div>
										<?php
									}
									echo '</div>';
								}
								if ( 1 == $bdp_settings['display_comment_count'] || 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
									echo '<div class="metabox-bottom">';
									if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
										?>
										<div class="post-comment">
											<i class="fas fa-comment"></i>
											<?php
											if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
												comments_number( esc_html__( 'No Comments', 'blog-designer-pro' ), '1 ' . esc_html__( 'comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
											} else {
												comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
											}
											?>
										</div>
										<?php
									}

									if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
										echo '<div class="postlike_btn">';
										echo do_shortcode( '[likebtn_shortcode]' );
										echo '</div>';
									}
									echo '</div>';
								}
								?>
							</div>
							<?php
						}
						if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
							if ( 'product' === $post_type ) {
								do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
							}
							if ( 'download' === $post_type ) {
								do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
							}
							$taxonomy_names = get_object_taxonomies( $post_type, 'objects' );
							$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );

							foreach ( $taxonomy_names as $taxonomy_single ) {
								$taxonomy = $taxonomy_single->name;
								$sep      = 1;
								if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
									$term_list                  = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
									$taxonomy_link              = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
									$bdp_exclude_taxonomy       = array( 'product_cat', 'download_category' );
									$bdp_tag_icon_taxonomy      = array( 'product_tag', 'download_tag' );
									$bdp_exclude_label_taxonomy = array( 'product_tag', 'download_tag', 'product_cat', 'download_category' );
									if ( isset( $taxonomy ) ) {
										if ( isset( $term_list ) && ! empty( $term_list ) ) {
											?>
											<div class="category-link">
											<?php
											if ( in_array( $taxonomy, $bdp_tag_icon_taxonomy ) ) { //phpcs:ignore
												?>
												<i class="fas fa-bookmark"></i> 
												<?php
											} else {
												?>
												<i class="fas fa-folder"></i> <?php } ?>
												<?php
												if ( ! in_array( $taxonomy, $bdp_exclude_label_taxonomy ) ) { //phpcs:ignore
													?>
													<strong><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:</strong>
												<?php } ?>
												&nbsp;
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
											</div>
											<?php
										}
									}
								}
							}
						} else {
							if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
								?>
								<div class="category-link">
									<i class="fas fa-folder"></i>
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
								</div>
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
									<div class="tags">
										<i class="fas fa-bookmark"></i>&nbsp;
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
						<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
					</div>
				</div>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</div>
		<?php
		do_action( 'bdp_archive_separator_after_post' );
	}
}
