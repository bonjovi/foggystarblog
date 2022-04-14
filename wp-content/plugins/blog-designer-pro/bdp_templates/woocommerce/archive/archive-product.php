<?php
/**
 * The template for displaying all archive posts
 * This template can be overridden by copying it to yourtheme/bdp_templates/woocommerce/archive/archive-product.php.
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.2
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

defined( 'ABSPATH' ) || exit;
get_header();
if ( have_posts() ) :
	?>
	<header class="entry-header header-title-plans-pricing">
		<?php
		the_archive_title( '<h1 class="entry-title">', '</h1>' );
		?>
	</header><!-- .page-header -->
	<?php
endif;
?>
<div class="container">
	<div class="row">		
		<div class="col-sm-8 col-xs-12 ">
			<?php
			do_action( 'bdp_before_product_archive_page' );
			$archive_id   = '';
			$bdp_theme    = '';
			$bdp_settings = array();
			$archive_list = array();
			$archive_list = Bdp_Woocommerce::get_product_archive_list();
			$paged        = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; //phpcs:ignore
			if ( is_product_category() && in_array( 'category_template', $archive_list ) ) { //phpcs:ignore
				$categories        = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$category_id       = $categories->term_id;
				$bdp_category_data = Bdp_Template::get_product_category_template_settings( $category_id, $archive_list );
				$archive_id        = $bdp_category_data['id'];
				$bdp_settings      = $bdp_category_data['bdp_settings'];
				if ( $bdp_settings ) {
					$bdp_theme                 = $bdp_settings['template_name'];
					$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
					if ( 1 == $bdp_template_name_changed ) { //phpcs:ignore
						if ( 'classical' === $bdp_theme ) {
							$bdp_theme = 'nicy';
						} elseif ( 'lightbreeze' === $bdp_theme ) {
							$bdp_theme = 'sharpen';
						} elseif ( 'spektrum' === $bdp_theme ) {
							$bdp_theme = 'hub';
						}
					} else {
						update_option( 'bdp_template_name_changed', 0 );
					}
				}
			} elseif ( is_product_tag() && in_array( 'tag_template', $archive_list ) ) { //phpcs:ignore
				$tags         = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$tag_id       = $tags->term_id;
				$bdp_tag_data = Bdp_Template::get_product_tag_template_settings( $tag_id, $archive_list );
				$archive_id   = $bdp_tag_data['id'];
				$bdp_settings = $bdp_tag_data['bdp_settings'];
				if ( $bdp_settings ) {
					$bdp_theme                 = $bdp_settings['template_name'];
					$bdp_template_name_changed = get_option( 'bdp_template_name_changed', 1 );
					if ( 1 == $bdp_template_name_changed ) { //phpcs:ignore
						if ( 'classical' === $bdp_theme ) {
							$bdp_theme = 'nicy';
						} elseif ( 'lightbreeze' === $bdp_theme ) {
							$bdp_theme = 'sharpen';
						} elseif ( 'spektrum' === $bdp_theme ) {
							$bdp_theme = 'hub';
						}
					} else {
						update_option( 'bdp_template_name_changed', 0 );
					}
				}
			}
			if ( isset( $bdp_settings['bdp_blog_order_by'] ) ) {
				$orderby = $bdp_settings['bdp_blog_order_by']; //phpcs:ignore
			}
			if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) { //phpcs:ignore
				$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
			} else {
				$firstpost_unique_design = 0;
			}
			$alter_class = '';
			$prev_year   = '';
			$alter       = 1;
			$alter_val   = null;
			global $wp_product_query;
			$arg_posts            = Bdp_Woocommerce::get_product_archive_wp_query( $bdp_settings );
			$loop                 = new WP_Query( $arg_posts );
			$temp_query           = $wp_product_query;
			$bdp_is_author        = is_author();
			$wp_product_query     = null;
			$wp_product_query     = $loop;
			$max_num_pages        = $wp_product_query->max_num_pages;
			$sticky_posts         = get_option( 'sticky_posts' );
			$prev_year1           = null;
			$prev_year            = null;
			$alter_val            = null;
			$prev_month           = null;
			$ajax_preious_year    = '';
			$ajax_preious_month   = '';
			$main_container_class = ( isset( $bdp_settings['main_container_class'] ) && '' != $bdp_settings['main_container_class'] ) ? $bdp_settings['main_container_class'] : ''; //phpcs:ignore

			if ( 'crayon_slider' === $bdp_theme || 'sunshiny_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme ) {
				$unique_id = mt_rand(); //phpcs:ignore
			}
			?>
			<?php 
			$same_height_class = '';
			$apply_same_height = isset( $bdp_settings['apply_same_height'] ) ? $bdp_settings['apply_same_height'] : '0'; 
				if ( 1 == $apply_same_height ) { //phpcs:ignore
					$same_height_class = 'same_height_all';
				}
			?>
			<div class="blog_template bdp_archive_product_template bdp_wrapper <?php echo esc_attr( $bdp_theme ); ?>_cover bdp_archive <?php echo esc_attr( $bdp_theme ) . ' layout_id_' . esc_attr( $archive_id ); ?>">
				<?php
				if ( '' != $main_container_class ) { //phpcs:ignore
					echo '<div class="' . esc_attr( $main_container_class ) . '">';
				}
				if ( 'offer_blog' === $bdp_theme ) {
					echo '<div class="bdp_single_offer_blog">';
				}
				if ( 'accordion' === $bdp_theme ) {
					$template_icon_alignment = ( isset( $bdp_settings['template_icon_alignment'] ) && '' != $bdp_settings['template_icon_alignment'] ) ? $bdp_settings['template_icon_alignment'] : 'icon-left'; 
	
					$bdp_accordion_layout_class = ( isset( $bdp_settings['accordion_template'] ) && '' != $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1'; 
					echo '<div class="blog_template accordion accordion_wrapper '. $bdp_accordion_layout_class .' ' . $template_icon_alignment . '">'; 
	
				}
				if ( 'winter' === $bdp_theme ) {
					echo '<div class="bdp_single_winter">';
				}


				if ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_more_btn' === $bdp_settings['pagination_type'] ) ) {
					echo "<div class='bdp-load-more-pre'>";
				}
				if ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) ) {
					echo "<div class='bdp-load-onscroll-pre' id='bdp-load-onscroll-pre'>";
				}

				if ( 'timeline' === $bdp_theme ) {
					if ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'left_side' === $bdp_settings['bdp_timeline_layout'] ) {
						if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) { //phpcs:ignore
							echo '<div class="timeline_bg_wrap left_side with_year"><div class="timeline_back clearfix">';
						} else {
							echo '<div class="timeline_bg_wrap left_side"><div class="timeline_back clearfix">';
						}
					} elseif ( isset( $bdp_settings['bdp_timeline_layout'] ) && 'right_side' === $bdp_settings['bdp_timeline_layout'] ) {
						if ( isset( $bdp_settings['timeline_display_option'] ) && '' != $bdp_settings['timeline_display_option'] ) { //phpcs:ignore
							echo '<div class="timeline_bg_wrap right_side with_year"><div class="timeline_back clearfix">';
						} else {
							echo '<div class="timeline_bg_wrap right_side"><div class="timeline_back clearfix">';
						}
					} else {
						if ( 'date' === $orderby || 'modified' === $orderby ) {
							echo '<div class="timeline_bg_wrap date_order"><div class="timeline_back clearfix">';
						} else {
							echo '<div class="timeline_bg_wrap"><div class="timeline_back clearfix">';
						}
					}
					$ajax_preious_year  = get_the_date( 'Y' );
					$ajax_preious_month = get_the_time( 'M' );
				}

				if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
					echo '<div class="bdp-row ' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . '">';
				}
				if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'advice' === $bdp_theme || 'minimal' === $bdp_theme ) {
					$column_setting        = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? 'column_layout_' . esc_attr( $bdp_settings['column_setting'] ) : 'column_layout_2'; //phpcs:ignore
					$column_setting_ipad   = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? 'column_layout_ipad_' . esc_attr( $bdp_settings['column_setting_ipad'] ) : 'column_layout_ipad_2'; //phpcs:ignore
					$column_setting_tablet = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? 'column_layout_tablet_' . esc_attr( $bdp_settings['column_setting_tablet'] ) : 'column_layout_tablet_1'; //phpcs:ignore
					$column_setting_mobile = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? 'column_layout_mobile_' . esc_attr( $bdp_settings['column_setting_mobile'] ) : 'column_layout_mobile_1'; //phpcs:ignore
					$column_class          = $column_setting . ' ' . esc_attr( $column_setting_ipad ) . ' ' . esc_attr( $column_setting_tablet ) . ' ' . esc_attr( $column_setting_mobile );
					if ( 'roctangle' === $bdp_theme ) {
						echo '<div class="bdp-row masonry ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
					} else {
						echo '<div class="bdp-row ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . '">';
					}
				}
				if ( 'glossary' === $bdp_theme || 'quci' === $bdp_theme || 'boxy' === $bdp_theme ) {
					echo '<div class="bdp-js-masonry masonry bdp_' . esc_attr( $bdp_theme ) . ' ' . esc_attr( $same_height_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
				}
				if ( 'boxy-clean' === $bdp_theme ) {
					echo '<div class="blog_template boxy-clean"><ul>';
				}
				$slider_navigation = isset( $bdp_settings['navigation_style_hidden'] ) ? $bdp_settings['navigation_style_hidden'] : 'navigation1';
				if ( 'crayon_slider' === $bdp_theme ) {
					echo '<div class="blog_template slider_template crayon_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
				}
				if ( 'sallet_slider' === $bdp_theme ) {
					echo '<div class="blog_template slider_template sallet_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
				}
				if ( 'sunshiny_slider' === $bdp_theme ) {
					echo '<div class="blog_template slider_template sunshiny_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
				}
				if ( 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
					echo '<div class="logbook flatLine flatNav flatButton">';
				}
				if ( 'easy_timeline' === $bdp_theme ) {
					echo '<div class="blog_template bdp_blog_template easy-timeline-wrapper"><ul class="easy-timeline" data-effect="' . esc_attr( $bdp_settings['easy_timeline_effect'] ) . '">';
				}
				if ( 'steps' === $bdp_theme ) {
					echo '<div class="blog_template bdp_blog_template steps-wrapper"><ul class="steps" data-effect="' . esc_attr( $bdp_settings['easy_timeline_effect'] ) . '">';
				}
				if ( 'my_diary' === $bdp_theme ) {
					echo '<div class="my_diary_wrapper">';
				}
				if ( 'story' === $bdp_theme ) {
					echo '<div class="story_wrapper">';
				}
				if ( 'brit_co' === $bdp_theme ) {
					echo '<div class="brit_co bdp_brit_co">';
				}
				if ( 'brite' === $bdp_theme ) {
					echo '<div class="brite-wrapp">';
				}
				if ( 'foodbox' === $bdp_theme ) {
					echo '<div class="foodbox-blog-wrapp">';
				}
				if ( 'neaty_block' === $bdp_theme ) {
					echo '<div class="neaty_block_blog_wrapp">';
				}
				if ( 'wise_block' === $bdp_theme ) {
					echo '<div class="blog_template wise_block_wrapper ' . esc_attr( $same_height_class )  . ' ' .  esc_attr( $bdp_theme ) . '">';
				}
				if ( 'soft_block' === $bdp_theme ) {
					echo '<div class="blog_template soft_block_wrapper">';
				}
				if ( 'schedule' === $bdp_theme ) {
					echo '<div class="blog_template schedule_wrapper">';
				}
				if ( have_posts() ) {
					while ( $loop->have_posts() ) :
						$loop->the_post();
						if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) { //phpcs:ignore
							if ( 0 == $alter % 2 ) { //phpcs:ignore
								$alter_class = ' alternative-back';
							} else {
								$alter_class = '';
							}
						}
						if ( 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'story' === $bdp_theme || 'fairy' === $bdp_theme || 'clicky' === $bdp_theme ) {
							if ( 0 == $alter % 2 ) { //phpcs:ignore
								$alter_class = 'even_class';
							} else {
								$alter_class = '';
							}
						}
						if ( 'timeline' === $bdp_theme ) {
							if ( 0 == $alter % 2 ) { //phpcs:ignore
								$alter_class = 'even_class';
							} else {
								$alter_class = 'odd_class';
							}
						}
						if ( 'invert-grid' === $bdp_theme || 'media-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'story' === $bdp_theme || 'explore' === $bdp_theme || 'hoverbic' === $bdp_theme ) {
							$alter_class = $alter;
						}
						if ( $bdp_theme ) {
							if ( 'timeline' === $bdp_theme ) {
								if ( 'date' === $orderby || 'modified' === $orderby ) {
									if ( isset( $bdp_settings['timeline_display_option'] ) && 'display_year' === $bdp_settings['timeline_display_option'] ) {
										$this_year = get_the_date( 'Y' );
										if ( $prev_year != $this_year ) { //phpcs:ignore
											$prev_year = $this_year;
											echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_attr( $prev_year ) . '</span></span></p>';
										}
									} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
										$prev_month = '';
										$this_year  = get_the_date( 'Y' );
										$this_month = get_the_time( 'M' );
										$prev_year  = $this_year;
										if ( $prev_month != $this_month ) { //phpcs:ignore
											$prev_month = $this_month;
											echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_attr( $this_year ) . '</span><span class="month">' . esc_attr( $prev_month ) . '</span></span></p>';
										}
									}
								}
							}
							if ( 'story' === $bdp_theme ) {
								if ( 'date' === $orderby || 'modified' === $orderby ) {
									$this_year = get_the_date( 'Y' );
									if ( $prev_year1 != $this_year ) { //phpcs:ignore
										$prev_year1 = $this_year;
										$prev_year  = 0;
									} elseif ( $prev_year1 == $this_year ) { //phpcs:ignore
										$prev_year = 1;
									}
								} else {
									$prev_year = get_the_date( 'Y' );
								}
							}
							if ( 'media-grid' === $bdp_theme ) {
								$alter_val = $alter;
							}
							if ( 1 == $firstpost_unique_design ) { //phpcs:ignore
								if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme ) {
									$alter_val = $alter;
									if ( 1 == $paged ) { //phpcs:ignore
										if ( 1 == $alter ) { //phpcs:ignore
											$prev_year = 0;
										} else {
											$prev_year = 1;
										}
									} else {
										$prev_year = 1;
									}
								}
								if ( 'media-grid' === $bdp_theme ) {
									$column_setting = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? $bdp_settings['column_setting'] : 2; //phpcs:ignore
									$alter_val      = $alter;
									if ( 1 == $paged ) { //phpcs:ignore
										if ( $column_setting >= 2 && $alter <= 2 ) {
											$prev_year = 0;
										} else {
											if ( 1 == $alter ) { //phpcs:ignore
												$prev_year = 0;
											} else {
												$prev_year = 1;
											}
										}
									} else {
										$prev_year = 1;
									}
								}
							}
						}
						Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
						do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
						$alter ++;
					endwhile;
				} else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action( 'woocommerce_no_products_found' );
				}
				if ( 'foodbox' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'neaty_block' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'wise_block' === $bdp_theme || 'soft_block' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'schedule' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'boxy-clean' === $bdp_theme || 'crayon_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'sunshiny_slider' === $bdp_theme ) {
					echo '</ul></div>';
				}
				if ( 'brit_co' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme || 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'quci' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
					echo '</div>';
				}

				if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'advice' === $bdp_theme || 'minimal' === $bdp_theme ) {
					echo '</div>';
				}
				if ( 'timeline' === $bdp_theme ) {
					echo '</div>
                        </div>';
				}
				if ( 'easy_timeline' === $bdp_theme || 'steps' === $bdp_theme ) {
					echo '</div></ul>';
				}
				if ( 'offer_blog' === $bdp_theme || 'winter' === $bdp_theme || 'my_diary' === $bdp_theme || 'story' === $bdp_theme || 'brite' === $bdp_theme || 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme || 'accordion' === $bdp_theme ) {
					echo '</div>';
				}
				$slider_array = array( 'cool_horizontal', 'overlay_horizontal', 'crayon_slider', 'sunshiny_slider', 'sallet_slider' );
				if ( ! in_array( $bdp_theme, $slider_array ) && ( isset( $bdp_settings['pagination_type'] ) && 'no_pagination' !== $bdp_settings['pagination_type'] ) ) { //phpcs:ignore
					if ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_more_btn' === $bdp_settings['pagination_type'] ) ) {
						echo '</div>';
						$is_loadmore_btn = '';
						if ( $max_num_pages > 1 ) {
							$is_loadmore_btn = '';
						} else {
							$is_loadmore_btn = '1';
						}
						if ( is_front_page() ) {
							$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
						} else {
							$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						}
						$bdp_search_text = '';
						if ( isset( $_GET['s'] ) ) { //phpcs:ignore
							$bdp_search_text = esc_attr( $_GET['s'] ); //phpcs:ignore
						}
						echo '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';
						echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
						echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
						echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
						echo '<input type="hidden" name="term_id" id="term_id" value="' . esc_attr( $category_id ) . '" />';
						echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
						echo '<input type="hidden" name="blog_layout" id="blog_layout" value="product_archive_layout" />';
						echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $archive_id ) . '" />';
						if ( 'timeline' === $bdp_theme ) {
							echo '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . esc_attr( $ajax_preious_year ) . '" />';
							echo '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . esc_attr( $ajax_preious_month ) . '" />';
						}
						echo Bdp_Utility::get_loader( $bdp_settings ); //phpcs:ignore
						echo '</form>';
						if ( '' === $is_loadmore_btn ) {
							$class = isset( $bdp_settings['load_more_button_template'] ) ? $bdp_settings['load_more_button_template'] : 'template-1';
							echo '<div class="bdp-load-more text-center" style="float:left;width:100%">';
							echo '<a href="javascript:void(0);" class="button bdp-load-more-btn ' . esc_attr( $class ) . '">';
							echo ( isset( $bdp_settings['loadmore_button_text'] ) && '' != $bdp_settings['loadmore_button_text'] ) ? esc_html( $bdp_settings['loadmore_button_text'] ) : esc_html__( 'Load More', 'blog-designer-pro' ); //phpcs:ignore
							echo '</a>';
							echo '</div>';
						}
					} elseif ( $max_num_pages > 1 && ( isset( $bdp_settings['pagination_type'] ) && 'load_onscroll_btn' === $bdp_settings['pagination_type'] ) ) {
						echo '</div>';
						$is_load_onscroll_btn = '';
						if ( $max_num_pages > 1 ) {
							$is_load_onscroll_btn = '';
						} else {
							$is_load_onscroll_btn = '1';
						}
						$bdp_search_text = '';
						if ( isset( $_GET['s'] ) ) { //phpcs:ignore
							$bdp_search_text = esc_attr( $_GET['s'] ); //phpcs:ignore
						}
						if ( is_front_page() ) {
							$bdppaged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
						} else {
							$bdppaged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						}
						echo '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';

						echo '<input type="hidden" name="paged" id="paged" value="' . esc_attr( $bdppaged ) . '" />';
						if ( 'story' === $bdp_theme ) {
							echo '<input type="hidden" name="this_year" id="this_year" value="' . esc_attr( $this_year ) . '" />';
						}
						echo '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . esc_attr( $posts_per_page ) . '" />';
						echo '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . esc_attr( $max_num_pages ) . '" />';
						echo '<input type="hidden" name="blog_template" id="blog_template" value="' . esc_attr( $bdp_theme ) . '" />';
						echo '<input type="hidden" name="blog_layout" id="blog_layout" value="product_archive_layout" />';
						echo '<input type="hidden" name="blog_shortcode_id" id="blog_shortcode_id" value="' . esc_attr( $archive_id ) . '" />';
						echo '<input type="hidden" name="term_id" id="term_id" value="' . esc_attr( $category_id ) . '" />';
						if ( 'timeline' === $bdp_theme ) {
							echo '<input type="hidden" name="timeline_previous_year" id="timeline_previous_year" value="' . esc_attr( $ajax_preious_year ) . '" />';
							echo '<input type="hidden" name="timeline_previous_month" id="timeline_previous_month" value="' . esc_attr( $ajax_preious_month ) . '" />';
						}
						echo Bdp_Utility::get_loader( $bdp_settings ); //phpcs:ignore
						echo '</form>';
						if ( '' === $is_load_onscroll_btn ) {
							$class = '';
							echo '<div class="bdp-load-onscroll text-center">';
							echo '<a href="javascript:void(0);" class="button bdp-load-onscroll-btn ' . esc_attr( $class ) . '">';
							echo esc_html__( 'Loading Posts', 'blog-designer-pro' ) . '</a>';
							echo '</div>';
						}
					}
					if ( isset( $bdp_settings['pagination_type'] ) && 'paged' === $bdp_settings['pagination_type'] ) {
						$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
						echo '<div class="wl_pagination_box ' . esc_attr( $pagination_template ) . '">';
						echo Bdp_Woocommerce::standard_product_paging_nav(); //phpcs:ignore
						echo '</div>';
					}
				}
				if ( '' != $main_container_class ) { //phpcs:ignore
					echo '</div">';
				}
				wp_reset_query(); //phpcs:ignore
				$wp_product_query = null;
				$wp_product_query = $temp_query;
				wp_reset_query(); //phpcs:ignore
				?>
		</div>
		<?php
			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'bdp_after_product_archive_page' );

			/**
			 * Hook: woocommerce_sidebar.
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
		?>
		</div>
			<div class="col-sm-4 col-xs-12 blog-sidebar">
				<?php do_action( 'woocommerce_sidebar' ); ?>
			</div>
		</div>
	</div>
</div>
<?php
get_footer();
