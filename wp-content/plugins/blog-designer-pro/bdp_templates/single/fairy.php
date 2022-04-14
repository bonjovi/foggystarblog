<?php
/**
 * The template for displaying all single posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/single/fairy.php.
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

add_action( 'bd_single_design_format_function', 'bdp_single_fairy_template', 10, 1 );

if ( ! function_exists( 'bdp_single_fairy_template' ) ) {
	/**
	 * Add html for fairy template
	 *
	 * @global object $post
	 * @param array $bdp_settings settings.
	 * @return void
	 */
	function bdp_single_fairy_template( $bdp_settings ) {
		global $post;
		$post_type         = get_post_type( $post->ID );
		$bdp_all_post_type = array( 'product', 'download' );
		$has_thumbnail     = true;
		?>
		<div class="blog_template bdp_blog_template fairy">
			<?php
			do_action( 'bdp_before_single_post_content' );

			$display_author     = $bdp_settings['display_author'];
			$display_date       = $bdp_settings['display_date'];
			$display_comment    = ( isset( $bdp_settings['display_comment'] ) && '' != $bdp_settings['display_comment'] ) ? $bdp_settings['display_comment'] : 0; //phpcs:ignore
			$display_postlike   = $bdp_settings['display_postlike'];
			$display_post_views = $bdp_settings['display_post_views'];
			$display_title      = ( isset( $bdp_settings['display_title'] ) && '' != $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1; //phpcs:ignore
			if ( 1 == $display_title ) { //phpcs:ignore
				?>
				<h1 class="post-title"><?php echo esc_html( get_the_title() ); ?></h1>
				<?php
			}
            if ( has_post_thumbnail() && isset( $bdp_settings['display_thumbnail'] ) && 1 == $bdp_settings['display_thumbnail'] ) { //phpcs:ignore
				?>
				<div class="bdp-post-image">
					<div class="post-thumbnail-cover">
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
						<div class="post-img-overlay"></div>
					</div>
					<?php
					if ( 1 == $display_author || 1 == $display_date ) { //phpcs:ignore
						echo '<div class="post-meta-cover">';
						$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
						if ( 1 == $display_author ) { //phpcs:ignore
							echo '<div class="author-avatar">';
							echo ( $author_link ) ? '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' : '';
							echo get_avatar( get_the_author_meta( 'ID' ), 60 );
							echo ( $author_link ) ? '</a>' : '';
							echo '</div>';
						}
						echo '<div class="post-meta-wrapp">';
						if ( 1 == $display_author ) { //phpcs:ignore
							echo '<div class="author-name">';
							echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore
							echo '</div>';
						}
						if ( 1 == $bdp_settings['display_date'] ) { //phpcs:ignore
							$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
							$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
							$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );
							?>
							<div class="mdate">
								<?php
								echo ( 'product' !== $post_type && $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
								echo esc_html( $bdp_date );
								echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '';
								?>
							</div>
							<?php
						}
						echo '</div>';
						echo '</div>';
					}
					?>
				</div>
				<?php
			} else {
				$has_thumbnail = false;
				if ( 1 == $display_author || 1 == $display_date ) { //phpcs:ignore
					?>
					<div class="post-meta">
						<?php
						if ( 1 == $display_author ) { //phpcs:ignore
							$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
							?>
							<span class="author">
								<?php
								echo ( $author_link ) ? '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' : '';
								echo esc_html__( 'by', 'blog-designer-pro' ) . ' ';
								echo get_the_author();
								echo ( $author_link ) ? '</a>' : '';
								?>
							</span>
							<?php
						}
                        if ( 1 == $display_date ) { //phpcs:ignore
							$ar_year   = get_the_time( 'Y' );
							$ar_month  = get_the_time( 'm' );
							$ar_day    = get_the_time( 'd' );
							$date_link = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
							?>
							<span class="date-meta">
								<?php
								$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
								$ar_year     = get_the_time( 'Y' );
								$ar_month    = get_the_time( 'm' );
								$ar_day      = get_the_time( 'd' );

								echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
								echo apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID ); //phpcs:ignore
								echo ( 'product' !== $post_type && $date_link ) ? '</a>' : '';
								?>
							</span>
							<?php
						}
						if ( 1 == $display_comment && 1 != $display_postlike && 0 == $display_post_views ) { //phpcs:ignore
							echo '</div>';
						}
				}
			}

			if ( 1 == $display_comment || 1 == $display_postlike || 0 != $display_post_views ) { //phpcs:ignore
				if ( $has_thumbnail ) {
					echo '<div class="post-meta">';
				}
				if ( 1 == $display_comment ) { //phpcs:ignore
					?>
						<span class="post-comment">
						<?php
						if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
							comments_number( esc_html__( 'No Comments', 'blog-designer-pro' ), '1 ' . esc_html__( 'comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
						} else {
							comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
						}
						?>
						</span>
						<?php
				}

				if ( 1 == $display_postlike ) { //phpcs:ignore
					echo do_shortcode( '[likebtn_shortcode]' );
				}

				if ( 0 != $display_post_views ) { //phpcs:ignore
					if ( '' != Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ) ) { //phpcs:ignore
						echo '<div class="display_post_views">';
						echo Bdp_Posts::get_post_views( get_the_ID(), $bdp_settings ); //phpcs:ignore //phpcs:ignore
						echo '</div>';
					}
				}
				?>
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
			if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
				$taxonomy_names       = get_object_taxonomies( $post_type, 'objects' );
				$taxonomy_names       = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
				$bdp_include_taxonomy = array( 'product_tag', 'download_tag' );
				foreach ( $taxonomy_names as $taxonomy_single ) {
					$taxonomy = $taxonomy_single->name;
					$sep      = 1;
					if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
						echo '<div class="post-meta-cats-tags">';
						$term_list     = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
						$taxonomy_link = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
						if ( isset( $taxonomy ) ) {
							if ( isset( $term_list ) && ! empty( $term_list ) ) {
								?>
								<div class="category-link">
									<span class="link-lable <?php echo ( $taxonomy_link ) ? '' : ' categories_link'; ?>">
									<?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp; </span>
									<?php
									foreach ( $term_list as $term_nm ) {
										$term_link = get_term_link( $term_nm );
                                        if ( 1 != $sep && ! in_array( $taxonomy, $bdp_include_taxonomy ) ) { //phpcs:ignore
											?>
											<span class="seperater"><?php echo ', '; ?></span>
											<?php
										}
										if ( in_array( $taxonomy, $bdp_include_taxonomy ) ) { //phpcs:ignore
											echo '<span class="tag">';
										}
										echo ( $taxonomy_link ) ? '<a href="' . esc_url( $term_link ) . '">' : '';
										echo esc_html( $term_nm->name );
										echo ( $taxonomy_link ) ? '</a>' : '';
										if ( in_array( $taxonomy, $bdp_include_taxonomy ) ) { //phpcs:ignore
											echo '</span>';
										}
										$sep++;
									}
									?>
								</div>
								<?php
							}
						}
						echo '</div>';
					}
				}
			} else {
				if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) { //phpcs:ignore
					echo '<div class="post-meta-cats-tags">';
				}

				if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
					?>
					<div class="category-link">
						<span class="link-lable"> <?php esc_html_e( 'Category', 'blog-designer-pro' ); ?> &nbsp;:&nbsp; </span>
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
					$tags_lists = get_the_tags();

					$tag_link = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? false : true; //phpcs:ignore

					if ( ! empty( $tags_lists ) ) {
						echo '<div class="post-tags">';
						echo '<span class="link-lable">' . esc_html__( 'Tags: ', 'blog-designer-pro' ) . '</span>';
						echo '<div class="post-tags-wrapp">';
						foreach ( $tags_lists as $tags_list ) {
							echo '<span class="tag">';
							if ( $tag_link ) {
								echo '<a rel="tag" href="' . esc_url( get_tag_link( $tags_list->term_id ) ) . '">';
							}
							echo $tags_list->name; //phpcs:ignore
							if ( $tag_link ) {
								echo '</a>';
							}
							echo '</span>';
						}
						echo '</div>';
						echo '</div>';
					}
				}
                if ( ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) || ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) ) { //phpcs:ignore
					echo '</div>';
				}
			}
			?>
			<div class="post-share-div">
				<?php
				if ( is_single() ) {
					do_action( 'bdp_social_share_text', $bdp_settings );
				}
				Bdp_Utility::get_social_icons( $bdp_settings );
				?>
			</div>
			<?php

			do_action( 'bdp_after_single_post_content' );
			?>
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
