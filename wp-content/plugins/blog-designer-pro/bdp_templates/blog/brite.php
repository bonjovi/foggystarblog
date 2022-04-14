<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/brite.php.
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
$bdp_post_id       = $post->ID;
$display_filter_by = ( isset( $bdp_settings['display_filter_by'] ) && ! empty( $bdp_settings['display_filter_by'] ) ) ? $bdp_settings['display_filter_by'] : '';
$category          = '';
if ( ! empty( $display_filter_by ) ) {
	$category_detail = wp_get_post_terms( $post->ID, $display_filter_by );
	if ( ! empty( $category_detail ) ) {
		foreach ( $category_detail as $cd ) {
			$category .= $cd->slug . ' ';
		}
	}
}
$bdp_all_post_type = array( 'product', 'download' );
?>
<div class="bdp_blog_template brite-post-wrapper bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
	<div class="brite-post-inner-wrapp">
		<?php do_action( 'bdp_before_post_content' ); ?>
		<div class="post-content-area">
			<div class="post-content-body">
				<div class="post-title">
					<?php
					$bdp_post_title_link = isset( $bdp_settings['bdp_post_title_link'] ) ? $bdp_settings['bdp_post_title_link'] : 1;
					if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
						echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_url( get_the_title() ) . '">';
					}
					?>
					<h2><?php echo esc_html( get_the_title() ); ?></h2>
					<?php
					if ( 1 == $bdp_post_title_link ) { //phpcs:ignore
						echo '</a>';
					}
					?>
				</div>
			   
				<div class="post-meta">
					<?php
					if ( 'post' === $bdp_settings['custom_post_type'] && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
						?>
						<div class="post-categories">
							<i class="fas fa-folder-open"></i>
							<?php
							$categories_list = get_the_category_list( ', ' );
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
					} elseif ( isset( $bdp_settings['custom_post_type'] ) && in_array( $bdp_settings['custom_post_type'], $bdp_all_post_type ) ) { //phpcs:ignore
						$bdp_tax_cat = '';
						if ( 'product' === $bdp_settings['custom_post_type'] ) {
							$bdp_tax_cat = 'product_cat';
						} elseif ( 'download' === $bdp_settings['custom_post_type'] ) {
							$bdp_tax_cat = 'download_category';
						}
						if ( '' != $bdp_tax_cat && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_cat ] ) { //phpcs:ignore
							$categories_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_cat ] ) ? false : true; //phpcs:ignore
							$product_categories = wp_get_post_terms( $post->ID, $bdp_tax_cat, array( 'hide_empty' => 'false' ) );
							$sep                = 1;
							?>
								<div class="post-category">
									<i class="fas fa-folder-open"></i>
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
					}

					if ( 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
						$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
						?>
						<div class="date-meta">
							<?php
							$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
							$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );

							echo '<i class="far fa-calendar-alt"></i>&nbsp;';
							echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							?>
						</div>
						<?php
					}


					if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
						?>
						<div class="post-comment">
							<i class="fas fa-comment"></i>
							<?php
							if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
								comments_number( '0', '1', '%' );
							} else {
								comments_popup_link( '0', '1', '%', 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
							}
							?>
						</div>
						<?php
					}

					if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
						echo do_shortcode( '[likebtn_shortcode]' );
					}
					?>
				</div>
				<?php
				if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
					do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
				}
				if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
					do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
				}
				?>
				<div class="post-content">
					<?php
					echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore
					$read_more_on = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
					if ( '' != get_the_content() && 1 == $read_more_on ) { //phpcs:ignore
						$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
						if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
							$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
							$post_link   = get_permalink( $post->ID );
							if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
								$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
							}
							echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
						}
					}
					?>
				</div>

				<?php
				if ( 'post' === $bdp_settings['custom_post_type'] && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
					$tags_lists = get_the_tags();

					$tag_link = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? false : true; //phpcs:ignore

					if ( ! empty( $tags_lists ) ) {
						echo '<div class="post-tags">';
						esc_html_e( 'Tags', 'blog-designer-pro' );
						echo ': ';
						echo '<div class="post-tags-wrapp">';
						foreach ( $tags_lists as $tags_list ) {
							echo '<span class="tag">';
							if ( $tag_link ) {
								echo '<a rel="tag" href="' . esc_url( get_tag_link( $tags_list->term_id ) ) . '">';
							}
							echo esc_html( $tags_list->name );
							if ( $tag_link ) {
								echo '</a>';
							}
							echo '</span>';
						}
						echo '</div>';
						echo '</div>';
					}
				}

				if ( 'post' != $bdp_settings['custom_post_type'] ) { //phpcs:ignore
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
									<div class="post-tags">
										<?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp;
										<div class="post-tags-wrapp">
										<?php
										foreach ( $term_list as $term_nm ) {
											$term_link = get_term_link( $term_nm );
											echo '<span class="tag">';
											echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
											echo esc_html( $term_nm->name );
											echo ( $taxonomy_link ) ? '</a>' : '';
											echo '</span>';
										}
										?>
										</div>
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
				?>
			</div>

			<div class="post-footer">
				<?php
				$read_more_on = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
				if ( '' != get_the_content() && 2 == $read_more_on ) { //phpcs:ignore
					$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
					if ( 1 == $read_more_link && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
						$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
						$post_link   = get_permalink( $post->ID );
						if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
							$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
						}
						echo '<div class="read-more-div"><a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a></div>';
					}
				}

				Bdp_Utility::get_social_icons( $bdp_settings );
				?>
			</div>
		</div>
		<div class="post-media">
			<?php
				$label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
				if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
				?>
					<div class="label_featured_post"><?php echo esc_attr( $label_featured_post ); ?></div> 
				<?php
			}
			?>
			<?php
			if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
				?>
				<div class="bdp-post-image post-video">
					<?php echo Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ); //phpcs:ignore ?>
				</div>
				<?php
			} else {
				$post_thumbnail      = 'full';
				$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id( $bdp_post_id ), $bdp_post_id );
				$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
				if ( ! empty( $thumbnail ) ) {
					echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
					echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
					echo ( $bdp_post_image_link ) ? '</a>' : '';
				}
				if ( class_exists( 'woocommerce' ) && 'product' === $bdp_settings['custom_post_type'] ) {
					if ( isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
						$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
						echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
						do_action( 'bdp_woocommerce_sale_tag' );
						echo '</div>';
					}
				}
				if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
					?>
					<div class="bdp-pinterest-share-image">
						<?php $img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
						<a target="_blank" href="<?php echo 'https://pinterest.com/pin/create/button/?url=' . esc_attr( get_permalink( $post->ID ) ) . '&media=' . esc_attr( $img_url ); ?>"></a>
					</div>
					<?php
				}
			}

			if ( 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
				echo '<div class="post-author">';
				$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
				echo '<div class="author-avatar">';
				echo get_avatar( get_the_author_meta( 'ID' ), 60 );
				echo '</div>';
				echo '<div class="author-name">';
				echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore
				echo '</div>';
				echo '</div>';
			}
			?>
		</div>

		<?php do_action( 'bdp_after_post_content' ); ?>
	</div>
</div>

<?php
do_action( 'bdp_separator_after_post' );
