<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/archive/schedule.php.
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
add_action( 'bd_archive_design_format_function', 'bdp_archive_schedule_template', 10, 1 );

if ( ! function_exists( 'bdp_archive_schedule_template' ) ) {

	/**
	 * Add html for boxy template
	 *
	 * @param array $bdp_settings settings.
	 * @global object $post
	 * @return void
	 */
	function bdp_archive_schedule_template( $bdp_settings ) {
		global $post;
		$post_type          = get_post_type( $post->ID );
		$bdp_all_post_type  = array( 'product', 'download' );
		$image_hover_effect = 'bdp-post-image ';
		if ( isset( $bdp_settings['bdp_image_hover_effect'] ) && 1 == $bdp_settings['bdp_image_hover_effect'] ) { //phpcs:ignore
			$image_hover_effect .= ( isset( $bdp_settings['bdp_image_hover_effect_type'] ) && '' != $bdp_settings['bdp_image_hover_effect_type'] ) ? $bdp_settings['bdp_image_hover_effect_type'] : ''; //phpcs:ignore
		}
		?>
		<div class="bdp_blog_template blog_template schedule bdp_blog_single_post_wrapp">
			<?php do_action( 'bdp_before_archive_post_content' ); ?>
				<div class="schedule-wrap">
					<div class="bdp-post-meta">
						<div class="bdp-author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 75 ); ?>
							<?php
							if ( 1 == $bdp_settings['display_author'] ) { //phpcs:ignore
								$author_link = ( isset( $bdp_settings['disable_link_author'] ) && 1 == $bdp_settings['disable_link_author'] ) ? false : true; //phpcs:ignore
								?>
									<span class="author">
									<?php
									$author_data  = '';
									$author_data .= '<span class="author-name">';
									$author_data .= ( $author_link ) ? '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" >' : '';
									$author_data .= Bdp_Author::get_post_auhtors( $post->ID, $bdp_settings );

									$author_data .= ( $author_link ) ? '</a>' : '';
									$author_data .= '</span>';
									echo apply_filters( 'bdp_existing_authors', $author_data, get_the_author_meta( 'ID' ) ); //phpcs:ignore
									do_action( 'bdp_extra_authors', $author_link );
									?>
									</span>
									<?php
							}
							?>
						</div>
						<?php
						$display_date = $bdp_settings['display_date'];
						if ( 1 == $display_date ) { //phpcs:ignore
							$date_link   = ( isset( $bdp_settings['disable_link_date'] ) && 1 == $bdp_settings['disable_link_date'] ) ? false : true; //phpcs:ignore
							$date_format = ( isset( $bdp_settings['post_date_format'] ) && 'default' !== $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : get_option( 'date_format' );
							$bdp_date    = ( isset( $bdp_settings['dsiplay_date_from'] ) && 'modify' === $bdp_settings['dsiplay_date_from'] ) ? apply_filters( 'bdp_date_format', get_post_modified_time( $date_format, $post->ID ), $post->ID ) : apply_filters( 'bdp_date_format', get_the_time( $date_format, $post->ID ), $post->ID );
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );
							echo '<div class="meta-archive">';
							echo ( $date_link ) ? '<a class="mdate" href="' . esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ) . '">' : '';
							echo esc_html( $bdp_date );
							echo ( $date_link ) ? '</a>' : '';
							echo '</div>';
						}
						if ( 'post' === $post_type ) {
							if ( isset( $bdp_settings['display_tag'] ) && 1 == $bdp_settings['display_tag'] ) { //phpcs:ignore
								$tags_list = get_the_tag_list( '', ' ' );
								$tag_link  = ( isset( $bdp_settings['disable_link_tag'] ) && 1 == $bdp_settings['disable_link_tag'] ) ? true : false; //phpcs:ignore
								if ( $tag_link ) {
									$tags_list = strip_tags( $tags_list ); //phpcs:ignore
								}
								if ( $tags_list ) :
									?>
									<div class="tags <?php echo ( $tag_link ) ? 'bdp_no_links' : 'bdp_has_link'; ?>">
									<?php
										echo $tags_list; //phpcs:ignore
										$show_sep = true;
									?>
									</div>
									<?php
								endif;
							}
						} if ( in_array( $post_type, $bdp_all_post_type ) ) { //phpcs:ignore
							$bdp_tax_tag = '';
							if ( 'product' === $post_type ) {
								$bdp_tax_tag = 'product_tag';
							} elseif ( 'download' === $post_type ) {
								$bdp_tax_tag = 'download_tag';
							}
							if ( '' != $bdp_tax_tag && isset( $bdp_settings[ 'display_taxonomy_' . $bdp_tax_tag ] ) && 1 == $bdp_settings[ 'display_taxonomy_' . $bdp_tax_tag ] ) { //phpcs:ignore
								$tags_link    = ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_tag ] ) && 1 == $bdp_settings[ 'disable_link_taxonomy_' . $bdp_tax_tag ] ) ? false : true; //phpcs:ignore
								$product_tags = wp_get_post_terms( $post->ID, $bdp_tax_tag, array( 'hide_empty' => 'false' ) );
								$sep          = 1;
								?>
									<div class="tags <?php echo ( $tags_link ) ? 'bdp_no_links' : 'bdp_has_link'; ?>">
										<?php
										foreach ( $product_tags as $tag ) {
											echo '<span class="bdp-custom-tag">';
											echo ( $tags_link ) ? '<a href="' . esc_url( get_term_link( $tag->term_id ) ) . '">' : '';
											echo esc_html( $tag->name );
											echo ( $tags_link ) ? '</a>' : '';
											echo '</span>';
											$sep++;
										}
										?>
									</div>
								<?php
							}
						}
						?>
					<div class="meta-comment">
						<?php
						if ( 1 == $bdp_settings['display_comment_count'] ) { //phpcs:ignore
							?>
							<p>
							<span class="metacomments" 
							<?php
							if ( ! has_post_thumbnail() && '' === $bdp_settings['bdp_default_image_id'] ) {
								echo 'style="margin-right:0"';}
							?>
							>
								<i class="fas fa-comment"></i>
								<?php
								if ( isset( $bdp_settings['disable_link_comment'] ) && 1 == $bdp_settings['disable_link_comment'] ) { //phpcs:ignore
									comments_number( esc_html__( 'No Comments', 'blog-designer-pro' ), '1 ' . esc_html__( 'comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ) );
								} else {
									comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer-pro' ), esc_html__( '1 comment', 'blog-designer-pro' ), '% ' . esc_html__( 'comments', 'blog-designer-pro' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer-pro' ) );
								}
								?>
							</span>
							</p>
						<?php } ?>
						<?php
						if ( isset( $bdp_settings['display_postlike'] ) && 1 == $bdp_settings['display_postlike'] ) { //phpcs:ignore
							?>
							<p> 
							<?php
							echo do_shortcode( '[likebtn_shortcode]' );
							?>
							</p> 
							<?php
						}
						?>
					</div>
					</div>
					<div class="schedule-content-wrap">
						<div class="schedule-circle"></div>
						<div class="schedule-time">
							<a href="#" class="schedule-button"><?php echo get_the_time(); //phpcs:ignore ?></a>
						</div>
						<div class="schedule-inner inner-first">
							<?php
							$post_thumbnail = 'full';
							$thumbnail      = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
							if ( isset( $thumbnail ) && ! empty( $thumbnail ) ) {
								?>
								<div class="schedule-image-wrapper">
									<?php
									if ( Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ) && 1 == $bdp_settings['rss_use_excerpt'] ) { //phpcs:ignore
										?>
											<div class="bdp-post-image post-video bdp-video">
											<?php echo Bdp_Utility::get_first_embed_media( $post->ID, $bdp_settings ); //phpcs:ignore ?>
											</div>
											<?php
									} else {
										$post_thumbnail      = 'full';
										$thumbnail           = Bdp_Posts::get_the_thumbnail( $bdp_settings, $post_thumbnail, get_post_thumbnail_id(), $post->ID );
										$bdp_post_image_link = ( isset( $bdp_settings['bdp_post_image_link'] ) && 0 == $bdp_settings['bdp_post_image_link'] ) ? false : true; //phpcs:ignore

										echo '<figure class="' . esc_attr( $image_hover_effect ) . '">';
										echo ( $bdp_post_image_link ) ? '<a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="deport-img-link">' : '';
										echo apply_filters( 'bdp_post_thumbnail_filter', $thumbnail, $post->ID ); //phpcs:ignore
										echo ( $bdp_post_image_link ) ? '</a>' : '';

										if ( isset( $bdp_settings['social_share'] ) && 1 == $bdp_settings['social_share'] && isset( $bdp_settings['pinterest_image_share'] ) && 1 == $bdp_settings['pinterest_image_share'] ) { //phpcs:ignore
											echo Bdp_Utility::pinterest( $post->ID ); //phpcs:ignore
										}
										if ( 'product' === $post_type && isset( $bdp_settings['display_sale_tag'] ) && 1 == $bdp_settings['display_sale_tag'] ) { //phpcs:ignore
											$bdp_sale_tagtext_alignment = ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) && '' != $bdp_settings['bdp_sale_tagtext_alignment'] ) ? $bdp_settings['bdp_sale_tagtext_alignment'] : 'left-top'; //phpcs:ignore
											echo '<div class="bdp_woocommerce_sale_wrap ' . esc_attr( $bdp_sale_tagtext_alignment ) . '">';
											do_action( 'bdp_woocommerce_sale_tag' );
											echo '</div>';
										}
										echo '</figure>';
									}
									?>
								</div>
							<?php } ?>
							<div class="post-content">
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
								</h2>
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
											$bdp_exclude_taxonomy = array( 'product_tag', 'download_tag' );
											if ( isset( $taxonomy ) && ! in_array( $taxonomy, $bdp_exclude_taxonomy ) ) { //phpcs:ignore
												if ( isset( $term_list ) && ! empty( $term_list ) ) {
													echo '<div class="post-category">';
													?>
													<span class="post-category taxonomies<?php echo ( $taxonomy_link ) ? ' bdp_no_links' : ' bdp_has_link'; ?>">
															<span class="link-lable"><?php echo esc_html( $taxonomy_single->label ); ?>&nbsp;:&nbsp;</span>
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
													echo '</div>';
												}
											}
										}
									}
								} else {
									if ( isset( $bdp_settings['display_category'] ) && 1 == $bdp_settings['display_category'] ) { //phpcs:ignore
										echo '<div class="post-category">';
										$categories_list  = get_the_category_list( ', ' );
										$categories_link  = ( isset( $bdp_settings['disable_link_category'] ) && 1 == $bdp_settings['disable_link_category'] ) ? true : false; //phpcs:ignore
										$categories_class = ( $categories_link ) ? 'bdp_no_links' : 'bdp_has_links';
										echo '<span class="post-category ' . esc_attr( $categories_class ) . '"><i class="fas fa-folder"></i>';
										if ( $categories_link ) {
											$categories_list = strip_tags( $categories_list ); //phpcs:ignore
										}
										if ( $categories_list ) :
											echo $categories_list; //phpcs:ignore
											$show_sep = true;
										endif;
										echo '</span>';
										echo '</div>';
									}
								}
								if ( Bdp_Template_Acf::is_acf_plugin() ) {
									if ( isset( $bdp_settings['display_acf_field'] ) && 1 == $bdp_settings['display_acf_field'] ) { //phpcs:ignore
										echo '<div class="bdp_acf_field">';
										do_action( 'bdp_after_blog_post_content_data', $bdp_settings, $post->ID );
										echo '</div>';
									}
								}
								if ( 'product' === $post_type ) {
									do_action( 'bdp_woocommerce_product_details_function', $bdp_settings, $post->ID );
								}
								if ( 'download' === $post_type ) {
									do_action( 'bdp_easy_digital_download_product_details_function', $bdp_settings, $post->ID );
								}
								?>
								<div class="postcontent">
									<?php
									echo Bdp_Posts::get_content( $post->ID, $bdp_settings['rss_use_excerpt'], $bdp_settings['txtExcerptlength'], $bdp_settings ); //phpcs:ignore
									$read_more_on   = isset( $bdp_settings['read_more_on'] ) ? $bdp_settings['read_more_on'] : 1;
									$read_more_link = isset( $bdp_settings['read_more_link'] ) ? $bdp_settings['read_more_link'] : 1;
									if ( 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) : //phpcs:ignore
										$readmoretxt = '' != $bdp_settings['txtReadmoretext'] ? $bdp_settings['txtReadmoretext'] : esc_html__( 'Read More', 'blog-designer-pro' ); //phpcs:ignore
										$post_link   = get_permalink( $post->ID );
										if ( isset( $bdp_settings['post_link_type'] ) && 1 == $bdp_settings['post_link_type'] ) { //phpcs:ignore
											$post_link = ( isset( $bdp_settings['custom_link_url'] ) && '' != $bdp_settings['custom_link_url'] ) ? $bdp_settings['custom_link_url'] : get_permalink( $post->ID ); //phpcs:ignore
										}
										if ( 1 == $read_more_on ) { //phpcs:ignore
											echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
										}
									endif;
									?>
								</div>
								<div class="post-footer">
								<?php
								if ( 2 == $read_more_on && 1 == $bdp_settings['rss_use_excerpt'] && 1 == $read_more_link ) { //phpcs:ignore
									echo '<div class="read-more-div">';
									echo '<a class="more-tag" href="' . esc_url( $post_link ) . '">' . esc_html( $readmoretxt ) . ' </a>';
									echo '</div>';
								}
								?>
								<?php Bdp_Utility::get_social_icons( $bdp_settings ); ?>
								</div>
							</div>
						</div>
						<?php do_action( 'bdp_after_archive_post_content' ); ?>
					</div>
			</div>
		</div>
		<?php
	}
}
