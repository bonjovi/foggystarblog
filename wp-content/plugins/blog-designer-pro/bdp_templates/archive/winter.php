<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/winter.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_winter_template', 10, 2 );
if ( ! function_exists( 'bdp_archive_winter_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array $bdp_settings settings.
	 * @param type  $alterclass class.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_winter_template( $bdp_settings, $alterclass ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		$class_name        = 'blog_template bdp_blog_template winter';
		if ( '' != $alterclass ) { //phpcs:ignore
			$class_name .= ' ' . $alterclass;
		}
		$image_hover_effect = '';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) { //phpcs:ignore
			$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : ''; //phpcs:ignore
		}
		?>
		<div class="<?php echo esc_attr( $class_name ); ?>">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
			<div class="blog_header">
				<?php
				$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
				if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
					?>
					<div class="label_featured_post"><?php echo esc_html( $label_featured_post ); ?></div> 
					<?php
				}
				?>
				<?php
				$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
				if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
					?>
					<div class="label_featured_post"><?php echo esc_html( $label_featured_post ); ?></div>
					<?php
				}
				?>
				<div class="metadatabox">
				<?php
					$display_date   = $bdp_settings['display_date'];
					$display_author = $bdp_settings['display_author'];
					$ar_year        = get_the_time( 'Y' );
					$ar_month       = get_the_time( 'm' );
					$ar_day         = get_the_time( 'd' );
				if ( 1 == $display_date ) { //phpcs:ignore
					$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
					$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
					$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
					?>
						<time datetime="" class="datetime">
						<?php
						echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
						echo esc_html( $bdp_date );
						echo '</a>';
						?>
						</time>
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
							if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
								if ( isset( $term_list ) && ! empty( $term_list ) ) {
									?>
										<div class="tags <?php echo ( $taxonomy_link ) ? 'bdp_has_link' : 'bdp_no_links'; ?>">
											<span class="link-lable"> <?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
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
					if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
						$tags_list = get_the_tag_list( '', ', ' );
						$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
						if ( $tag_link ) {
							$tags_list = strip_tags( $tags_list ); //phpcs:ignore
						}
						if ( $tags_list ) :
							?>
							<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_links'; ?>">&nbsp;
								<?php
								echo $tags_list; //phpcs:ignore
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
					<h2 class="post-title">
						<?php
						$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
						if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
							?>
							<a href="<?php the_permalink(); ?>">
							<?php } ?>
							<?php
							echo esc_html( get_the_title() );
							if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
								?>
							</a>
						<?php } ?>
					</h2>
					<?php
					if ( 1 == $display_author ) { //phpcs:ignore
						$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
						?>
						<div class="posted_by">
							<span><?php esc_html_e( 'By', 'blog-designer-pro' ); ?> </span>
							<?php
							echo ( ! $author_link ) ? '<span class="auther-inner">' : '';
							echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore
							echo ( ! $author_link ) ? '</span>' : '';
							?>
						</div>
						<?php
					}
					if ( 1 == $bdp_settings['display_comment_count'] || ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) ) { //phpcs:ignore
						?>
						<div class="metacomments">
							<?php
							if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
								if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
									comments_number( __( 'No Comments', 'blog-designer-pro' ), '1 ' . __( 'comment', 'blog-designer-pro' ), '% ' . __( 'comments', 'blog-designer-pro' ) );
								} else {
									comments_popup_link( __( 'Leave a Comment', 'blog-designer-pro' ), __( '1 comment', 'blog-designer-pro' ), '% ' . __( 'comments', 'blog-designer-pro' ), 'comments-link', __( 'Comments are off', 'blog-designer-pro' ) );
								}
							}
							if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
								echo do_shortcode( '[likebtn_shortcode]' );
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<div class="bdp-post-image">
			<?php
				$no_image            = 'winter-no-image-post';
				$post_thumbnail      = 'full';
				$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
				$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
			if ( ! empty( $thumbnail ) ) {
				$no_image = '';
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
						<div class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?> <?php echo esc_attr( $no_image ); ?>">
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
						</div>
						<?php
				}
			} else {
				if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
					$categories_list = get_the_category_list( ', ' );
					$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
					if ( $categories_link ) {
						$categories_list = strip_tags( $categories_list ); //phpcs:ignore
					}
					if ( $categories_list ) :
						echo '<div class="category-link ' . esc_attr( $no_image ) . ' ">';
						echo ' ' . $categories_list; //phpcs:ignore
						$show_sep = true;
						echo '</div>';
						endif;
				}
			}
			if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
				?>
					<div class="post-video">
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
				if ( ! empty( $thumbnail ) ) {
					echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
					echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
					echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
					if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
						echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
					}
					if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
						$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
						echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
						do_action( 'bdp_woocommerce_sale_tag' );
						echo '</div>';
					}
					echo ( $bdp_post_image_link ) ? '</a>' : '';
					echo '</figure>';
				}
			}
			?>
			</div>
			<?php
			if ( 'product' === $post_type ) {
				do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
			}
			if ( 'download' === $post_type ) {
				do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
			}
			?>
			<div class="post_content_wrap">
				<div class="post_content">
				<p><?php echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore ?>
				<?php
				$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
				$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
				if ( 1 == $read_more_link && 0 != $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
					$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : __( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
					$post_link   = get_permalink( $post->ID );
					if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
						$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
					}
					if ( 1 == $read_more_on ) { //phpcs:ignore
						echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
					}
				}
				?>
				</p>
				<?php
				if ( 1 == $read_more_link && 0 != $bdp_settings['rss_use_excerpt'] && 2 == $read_more_on ) { //phpcs:ignore
					echo '<div class="read-more-div"><a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a></div>';
				}
				?>
				</div>
				<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
			</div>
			<?php do_action( 'bdp_after_archive_post_content' ); ?>
		</div>
		<?php
		do_action( 'bdp_archive_separator_after_post' );
	}
}
