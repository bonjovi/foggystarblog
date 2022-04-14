<?php
/**
 * The template for displaying all blog posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/blog/lightbreeze.php.
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
$class_name = 'blog_template bdp_blog_template box-template active lightbreeze ';
if ( '' != $alter_class ) { //phpcs:ignore
	$class_name .= ' ' . $alter_class;
}
$image_hover_effect = '';
if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) { //phpcs:ignore
	$image_hover_effect = ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : ''; //phpcs:ignore
}
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
<div class="
<?php
echo esc_attr( $class_name );
if ( 'post' === $bdp_settings['custom_post_type'] && 0 == $bdp_settings['display_category'] ) { //phpcs:ignore
	echo ' no-category-display';
}
?>
bdp_blog_single_post_wrapp <?php echo esc_attr( $category ); ?>">
	<?php do_action( 'bdp_before_post_content' ); ?>
    <?php if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore ?>
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
		$post_thumbnail = 'full';
		$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
		if ( ! empty( $thumbnail ) ) {
            $bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore
			?>
			<div class="bdp-post-image">
				<?php
				echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
				echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '">' : '';
				echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
				echo ( $bdp_post_image_link ) ? '</a>' : '';
				if ( isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] && isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] ) { //phpcs:ignore
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
				echo '</figure>';
				?>
			</div>
			<?php
		}
	}
    $label_featured_post = ( isset( $bdp_settings['label_featured_post'] ) && '' != $bdp_settings['label_featured_post'] ) ? $bdp_settings['label_featured_post'] : ''; //phpcs:ignore
	if ( '' != $label_featured_post && is_sticky() ) { //phpcs:ignore
		?>
		<div class="label_featured_post"><span><?php echo esc_attr( $label_featured_post ); ?></span></div>
		<?php
	}
	?>
	<div class="blog_header 
	<?php
	if ( has_post_thumbnail() || ( isset( $bdp_settings['bdp_default_image_id'] ) && '' != $bdp_settings['bdp_default_image_id'] ) ) { //phpcs:ignore
		echo 'blog_header_img';
	}
	?>
	">
		<h2 class="post-title">
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
			</a>
		</h2>
		<?php if ( 1 == $bdp_settings['display_date'] || 1 == $bdp_settings['display_postlike'] || 1 == $bdp_settings['display_author'] || 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore ?>
			<div class="meta_data_box metadatabox">
				<?php
				$display_date   = $bdp_settings['display_date'];
				$display_author = $bdp_settings['display_author'];
				if ( 1 == $display_author ) { //phpcs:ignore
					$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
					?>
					<div class="metauser <?php echo ( ! $author_link ) ? 'bdp_no_links' : ''; ?>">
						<span class="link-lable"> <i class="fas fa-user"></i> <?php esc_html_e( 'Posted by', 'blog-designer-pro' ); ?>&nbsp;</span>
						<?php echo Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings ); //phpcs:ignore ?>
						<br />
					</div>
					<?php
				}
				if ( 1 == $display_date ) { //phpcs:ignore
					$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
					$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
					$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
					$ar_year     = get_the_time( 'Y' );
					$ar_month    = get_the_time( 'm' );
					$ar_day      = get_the_time( 'd' );
					?>
					<div class="metadate">
						<span class="mdate">
							<i class="far fa-calendar-alt"></i>
							<?php
							echo ( $date_link ) ? '<a href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							?>
						</span>
					</div>
					<?php
				}
				if ( 'post' === $bdp_settings['custom_post_type'] ) {
					if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
						$categories_list = get_the_category_list( ', ' );
						$categories_link = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
						?>
						<div class="category-link <?php echo ( $categories_link ) ? 'bdp_no_links' : ''; ?>">
							<span class="link-lable"> <i class="fas fa-folder-open"></i></span>
							<?php
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
							<div class="category-link<?php echo ( $categories_link ) ? ' categories_link' : ''; ?>">
								<span class="link-lable"> <i class="fas fa-folder-open"></i></span>
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

				if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
					?>
					<div class="metacomments">
						<!--<div class="icon-comment"></div>-->
						<i class="fas fa-comment"></i>
						<?php
						if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
							comments_number( esc_html__( 'No Comments', 'blog-designer-pro' ), '1 ' . esc_html__( 'comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
						} else {
							comments_popup_link( esc_html__( 'No Comments', 'blog-designer-pro' ), esc_html__( '1 Comment', 'blog-designer-pro' ), '% ' . esc_html__( 'Comments', 'blog-designer-pro' ) );
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
		}
		if ( isset( $bdp_settings['custom_post_type'] ) && 'product' === $bdp_settings['custom_post_type'] ) {
			do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
		}
		if ( isset( $bdp_settings['custom_post_type'] ) && 'download' === $bdp_settings['custom_post_type'] ) {
			do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
		}
		?>
	</div>
	<div class="post_content">
		<?php echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore ?>
		<?php
		$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 2;
		$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
		if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) : //phpcs:ignore
			$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
			$post_link   = get_permalink( $post->ID );
			if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
				$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
			}
			if ( 1 == $read_more_on ) { //phpcs:ignore
				?>
				<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>">
					<?php echo esc_html( $readmoretxt ); ?>
				</a>
			<?php } ?>
		<?php endif; ?>
	</div>
	<?php
	if ( 'post' === $bdp_settings['custom_post_type'] ) {
		if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
			$tags_list = get_the_tag_list( '', ', ' );
			$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
			if ( $tag_link ) {
				$tags_list = strip_tags( $tags_list ); //phpcs:ignore
			}
			if ( $tags_list ) :
				?>
				<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : ''; ?>">
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
	if ( 'post' !== $bdp_settings['custom_post_type'] ) {
		$taxonomy_names = get_object_taxonomies( $bdp_settings['custom_post_type'], 'objects' );
		$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
		foreach ( $taxonomy_names as $taxonomy_single ) {
			$taxonomy = $taxonomy_single->name; //phpcs:ignore
			if ( isset( $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $taxonomy ] ) { //phpcs:ignore
				$term_list            = wp_get_post_terms( get_the_ID(), $taxonomy, array( 'fields' => 'all' ) );
				$taxonomy_link        = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy ] ) ? false : true; //phpcs:ignore
				$bdp_exclude_taxonomy = array( 'product_cat', 'download_category' );
				if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
					if ( isset( $term_list ) && ! empty( $term_list ) ) {
						$sep = 1;
						?>
						<div class="category-link taxonomies <?php echo esc_attr( $taxonomy ); ?> <?php echo ( $taxonomy_link ) ? 'bdp_has_links' : 'bdp_no_links'; ?>">
							<span class="link-lable"> <i class="fas fa-tags"></i> <?php echo esc_attr( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
	}
	if ( Bdp_Template_Acf::is_acf_plugin() ) {
		if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
			echo '<div class="bdp_acf_field">';
			do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
			echo '</div>';
		}
	}
	if ( 2 == $read_more_on && 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) { //phpcs:ignore
		?>
		<div class="read-more">
			<a class="more-tag" href="<?php echo esc_url( $post_link ); ?>">
				<?php echo esc_html( $readmoretxt ); ?>
			</a>
		</div>
		<?php
	}
	Bdp_Utility::get_social_icons( $bdp_settings );
	do_action( 'bdp_after_post_content' );
	?>
</div>
<?php
do_action( 'bdp_separator_after_post' );
