<?php
/**
 * Administration API: Core Ajax handlers
 *
 * @link       https://www.solwininfotech.com/
 * @since      1.0.0
 *
 * @package    Blog_Designer_PRO
 * @subpackage Blog_Designer_PRO/admin
 * @author     Solwin Infotech <info@solwininfotech.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Blog Designer PRO Backend Functions Class.
 *
 * @class   Bdp_Ajax_Actions
 * @version 1.0.0
 */
class Bdp_Ajax_Actions {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_custom_post_taxonomy', array( $this, 'bdp_custom_post_taxonomy' ) );
		add_action( 'wp_ajax_get_custom_taxonomy_terms', array( $this, 'bdp_get_custom_taxonomy_terms' ) );
		add_action( 'wp_ajax_custom_post_taxonomy_display_settings', array( $this, 'bdp_custom_post_taxonomy_display_settings' ) );
		add_action( 'wp_ajax_bdp_get_posts_single_template', array( $this, 'bdp_get_posts_single_template' ) );
		add_action( 'wp_ajax_bdp_preview_request', array( $this, 'bdp_preview_request' ) );
		add_action( 'wp_ajax_bdp_archive_preview_request', array( $this, 'bdp_archive_preview_request' ) );
		add_action( 'wp_ajax_bdp_closed_bdpboxes', array( $this, 'bdp_closed_bdpboxes' ) );
		add_action( 'wp_ajax_bdp_admin_notice_pro_layouts_dismiss', array( $this, 'bdp_admin_notice_pro_layouts_dismiss' ) );
		add_action( 'wp_ajax_bdp_create_layout_from_blog_designer_dismiss', array( $this, 'bdp_create_layout_from_blog_designer_dismiss' ) );
		add_action( 'wp_ajax_nopriv_bdp_blog_template_search_result', array( $this, 'bdp_blog_template_search_result' ) );
		add_action( 'wp_ajax_bdp_blog_template_search_result', array( $this, 'bdp_blog_template_search_result' ) );
		add_action( 'wp_ajax_nopriv_bdp_single_blog_template_search_result', array( $this, 'bdp_single_blog_template_search_result' ) );
		add_action( 'wp_ajax_bdp_single_blog_template_search_result', array( $this, 'bdp_single_blog_template_search_result' ) );
		add_action( 'wp_ajax_bdp_notice_template_outdated_dismiss', array( $this, 'bdp_notice_template_outdated_dismiss' ) );
	}
	/**
	 * Ajax handler to get custom post taxonomy
	 *
	 * @return void
	 */
	public function bdp_custom_post_taxonomy() {
		ob_start();
		?>
		<table>
			<tbody>
				<?php
				if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) { //phpcs:ignore
					$custom_posttype = esc_attr( $_POST['posttype'] ); //phpcs:ignore
				}
				$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
				$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
				if ( ! empty( $taxonomy_names ) ) {
					foreach ( $taxonomy_names as $taxonomy_name ) {
						if ( ! empty( $taxonomy_name ) ) {
							$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
							if ( ! empty( $terms ) ) {
								?>
								<tr class="custom-taxonomy">
									<td>
										<?php
										esc_html_e( 'Select', 'blog-designer-pro' );
										echo ' ' . esc_html( $taxonomy_name->label );
										?>
									</td>
									<td>
										<select data-placeholder="Choose <?php echo esc_attr( $taxonomy_name->label ); ?>" multiple style="width:220px" class="chosen-select custom_post_term" name="<?php echo esc_attr( $taxonomy_name->name ); ?>_terms[]" id="terms_<?php echo esc_attr( $taxonomy_name->name ); ?>">
											<?php
											foreach ( $terms as $term ) {
												?>
												<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
											<?php } ?>
										</select>
										<div class="exclude_tag_list_div"><label><input id="exclude_<?php echo esc_html( $taxonomy_name->name ); ?>_list" name="exclude_<?php echo esc_html( $taxonomy_name->name ); ?>_list" type="checkbox" value="1" /> <?php echo esc_html__( 'Exclude Selected ', 'blog-designer-pro' ) . esc_attr( $taxonomy_name->label ); ?></label></div>
									</td>
								</tr>
								<?php
							}
						}
					}
				}
				?>
			</tbody>
		</table>
		<?php
		$data = ob_get_clean();
		echo $data; //phpcs:ignore
		die();
	}
	/**
	 * Administration API: Core Ajax handlers
	 * Ajax handler to get custom post taxonomy terms
	 *
	 * @since 2.1
	 * @return void
	 */
	public function bdp_get_custom_taxonomy_terms() {
		ob_start();
		if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) { //phpcs:ignore
			$custom_posttype = esc_attr( $_POST['posttype'] ); //phpcs:ignore
		}
		$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
		$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
		if ( ! empty( $taxonomy_names ) ) {
			foreach ( $taxonomy_names as $taxonomy_name ) {
				$terms = get_terms( $taxonomy_name->name, array( 'hide_empty' => false ) );
				if ( ! empty( $terms ) ) {
					?>
					<li class="bdp-post-terms">
						<div class="bdp-left"><span class="bdp-key-title"><?php echo esc_html__( 'Select', 'blog-designer-pro' ) . ' ' . esc_html( $taxonomy_name->label ); ?></span></div>
						<div class="bdp-right">
							<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-select"><span class="bdp-tooltips"><?php echo esc_html__( 'Filter post via', 'blog-designer-pro' ) . ' ' . esc_html( $taxonomy_name->label ); ?></span></span>
							<select data-placeholder="Choose <?php echo esc_attr( $taxonomy_name->label ); ?>" multiple style="width:220px" class="chosen-select custom_post_term" name="<?php echo esc_attr( $taxonomy_name->name ); ?>_terms[]" id="terms_<?php echo esc_attr( $taxonomy_name->name ); ?>">
								<?php foreach ( $terms as $term ) { ?>
									<option value="<?php echo esc_attr( $term->name ); ?>"><?php echo esc_html( $term->name ); ?></option>
								<?php } ?>
							</select>
							<div class="exclude_tag_list_div"><label><input id="exclude_<?php echo esc_attr( $taxonomy_name->name ); ?>_list" name="exclude_<?php echo esc_attr( $taxonomy_name->name ); ?>_list" type="checkbox" value="1" /> <?php echo esc_html__( 'Exclude Selected ', 'blog-designer-pro' ) . esc_html( $taxonomy_name->label ); ?></label></div>
						</div>
					</li>
					<?php
				}
			}
		}
		$data = ob_get_clean();
		echo $data; //phpcs:ignore
		die();
	}
	/**
	 * Ajax handler to get custom post taxonomy display settings
	 * Administration API: Core Ajax handlers
	 *
	 * @since 2.1
	 */
	public function bdp_custom_post_taxonomy_display_settings() {
		ob_start();
		if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) { //phpcs:ignore
			$custom_posttype = esc_attr( $_POST['posttype'] ); //phpcs:ignore
		}
		$taxonomy_names = get_object_taxonomies( $custom_posttype, 'objects' );
		$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
		if ( 'post' === $custom_posttype ) {
			?>
			<div class="bdp-typography-cover display-custom-taxonomy">
				<div class="bdp-typography-label">
					<span class="bd-key-title"><?php esc_html_e( 'Post Category', 'blog-designer-pro' ); ?></span>
					<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show post category on blog layout', 'blog-designer-pro' ); ?></span></span>
				</div>
				<div class="bdp-typography-content">
					<fieldset class="bdp-social-options bdp-display_author buttonset">
						<input id="display_category_1" name="display_category" type="radio" value="1" checked="checked" />
						<label for="display_category_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
						<input id="display_category_0" name="display_category" type="radio" value="0" />
						<label for="display_category_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
					</fieldset>
					<label class="disable_link"><input id="disable_link_category" name="disable_link_category" type="checkbox" value="1" /> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?></label>
					<label class="filter_data"><input id="filter_cat" name="filter_category" type="checkbox" value="1" /> <?php esc_html_e( 'Display Filter for Categories', 'blog-designer-pro' ); ?></label>
				</div>
			</div>
			<div class="bdp-typography-cover display-custom-taxonomy">
				<div class="bdp-typography-label">
					<span class="bd-key-title"><?php esc_html_e( 'Post Tag', 'blog-designer-pro' ); ?></span>
					<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show post tag on blog layout', 'blog-designer-pro' ); ?></span></span>
				</div>
				<div class="bdp-typography-content">
					<fieldset class="bdp-social-options bdp-display_author buttonset">
						<input id="display_tag_1" name="display_tag" type="radio" value="1" checked="checked" />
						<label for="display_tag_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
						<input id="display_tag_0" name="display_tag" type="radio" value="0" />
						<label for="display_tag_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
					</fieldset>
					<label class="disable_link"><input id="disable_link_tag" name="disable_link_tag" type="checkbox" value="1" /> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?></label>
					<label class="filter_data"><input id="filter_tag" name="filter_tags" type="checkbox" value="1" /> <?php esc_html_e( 'Display Filter for Tags', 'blog-designer-pro' ); ?></label>
				</div>
			</div>
			<?php
		} elseif ( ! empty( $taxonomy_names ) ) {
			foreach ( $taxonomy_names as $taxonomy_name ) {
				if ( ! empty( $taxonomy_name ) ) {
					?>
					<div class="bdp-typography-cover display-custom-taxonomy">
						<div class="bdp-typography-label">
							<span class="bd-key-title"><?php echo esc_attr( $taxonomy_name->label ); ?></span>
							<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php echo esc_html__( 'Enable/Disable', 'blog-designer-pro' ) . ' ' . esc_attr( $taxonomy_name->label ) . ' ' . esc_html__( 'in blog layout', 'blog-designer-pro' ); ?></span></span>
						</div>
						<div class="bdp-typography-content">
							<fieldset class="bdp-display_tax buttonset">
								<input id="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_1" name="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="radio" value="1" />
								<label for="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
								<input id="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_0" name="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="radio" value="0" checked="checked"/>
								<label for="display_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
							</fieldset>
							<label class="disable_link">
								<input id="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" name="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="checkbox" value="1" 
								<?php
								if ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] ) ) {
									checked( 1, $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] );
								}
								?>
								/>
								<?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
							</label>
						</div>
					</div>
					<?php
				}
			}
		}
		$data = ob_get_clean();
		echo $data; //phpcs:ignore
		die();
	}
	/**
	 * Get post listing
	 *
	 * @return void
	 */
	public function bdp_get_posts_single_template() {
		ob_start();
		$tax_ids = isset( $_POST['tax_ids'] ) ? $_POST['tax_ids'] : array(); //phpcs:ignore
		$tax     = isset( $_POST['tax'] ) ? esc_attr( $_POST['tax'] ) : ''; //phpcs:ignore
		global $wpdb;
		$db_posts      = $wpdb->get_results( 'SELECT single_post_id FROM ' . $wpdb->prefix . 'bdp_single_layouts' ); //phpcs:ignore
		$db_posts_list = array();
		if ( $db_posts ) {
			foreach ( $db_posts as $db_post ) {
				$sub_list = $db_post->single_post_id;
				if ( $sub_list ) {
					$db_post_ids = explode( ',', $sub_list );
					foreach ( $db_post_ids as $db_post_id ) {
						$db_posts_list[] = $db_post_id;
					}
				}
			}
		}
		$final_posts = $db_posts_list;
		if ( 'tag' === $tax && ! empty( $tax_ids ) ) {
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'desc',
				'tag__in'        => $tax_ids,
			);
		} elseif ( 'category' === $tax && ! empty( $tax_ids ) ) {
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'desc',
				'category__in'   => $tax_ids,
			);
		} else {
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => 'post',
				'orderby'        => 'date',
				'order'          => 'desc',
			);
		}
		$allposts = get_posts( $args );
		?>
		<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Posts', 'blog-designer-pro' ); ?></span></div>
		<div class="bdp-right">
			<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-select"><span class="bdp-tooltips"><?php esc_html_e( 'Select post from available posts for single post layout', 'blog-designer-pro' ); ?></span></span>
			<?php
			if ( $allposts ) {
				?>
				<select data-placeholder="<?php esc_attr_e( 'Choose Posts', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_posts[]" id="template_posts">
					<?php
					foreach ( $allposts as $single_post ) :
						setup_postdata( $single_post );
						?>
						<option 
						<?php
						if ( in_array( $single_post->ID, $final_posts ) ) { //phpcs:ignore
							echo 'disabled="disabled" ';
						}
						?>
						value="<?php echo esc_attr( $single_post->ID ); ?>"><?php echo esc_html( $single_post->post_title ); ?></option>
							<?php
						endforeach;
						wp_reset_postdata();
					?>
				</select>
				<div class="bdp-setting-description bdp-note">
					<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
				<?php esc_html_e( 'Default All Posts Selected', 'blog-designer-pro' ); ?>
				</div>
				<?php
			} else {
				esc_html_e( 'No posts found', 'blog-designer-pro' );
			}
			?>
		</div>
		<?php
		$data = ob_get_clean();
		echo $data; //phpcs:ignore
		die();
	}
	/**
	 * Function for getting post list (function not in use)
	 */
	public function bdp_get_taxonomy_list() {
		ob_start();
		if ( isset( $_POST['posttype'] ) && ! empty( $_POST['posttype'] ) ) { //phpcs:ignore
			$custom_posttype = esc_attr( $_POST['posttype'] ); //phpcs:ignore
		}
		$taxonomy_names = get_object_taxonomies( $custom_posttype );
		$sep            = 1;
		if ( ! empty( $taxonomy_names ) ) {
			foreach ( $taxonomy_names as $taxonomy_name ) {
				if ( ! empty( $taxonomy_name ) ) {
					$terms = get_terms( $taxonomy_name, array( 'hide_empty' => false ) );
					if ( ! empty( $terms ) ) {
						if ( 1 != $sep ) { //phpcs:ignore
							echo ',';
						}
                        echo $taxonomy_name; //phpcs:ignore
						$sep++;
					}
				}
			}
		}
		$data = ob_get_clean();
		echo $data; //phpcs:ignore
		die();
	}
	/**
	 * Ajax handler for preview
	 *
	 * @return void
	 */
	public function bdp_preview_request() {
		if ( isset( $_POST['settings'] ) ) { //phpcs:ignore
			$bdp_settings = array();
			parse_str( $_POST['settings'], $bdp_settings ); //phpcs:ignore
			echo Bdp_Template::layout_view_portion( '', $bdp_settings ); //phpcs:ignore
			exit();
		}
	}
	/**
	 * Ajax handler for archive preview
	 *
	 * @return void
	 */
	public function bdp_archive_preview_request() {
		if ( isset( $_POST['settings'] ) ) { //phpcs:ignore
			$bdp_settings = array();
			parse_str( $_POST['settings'], $bdp_settings ); //phpcs:ignore
			$alter_class = '';
			$alter       = 1;
			$bdp_theme   = $bdp_settings['template_name'];
			if ( isset( $bdp_settings['bdp_blog_order_by'] ) ) {
				$orderby = $bdp_settings['bdp_blog_order_by'];
			}
			if ( isset( $bdp_settings['firstpost_unique_design'] ) && '' != $bdp_settings['firstpost_unique_design'] ) { //phpcs:ignore
				$firstpost_unique_design = $bdp_settings['firstpost_unique_design'];
			} else {
				$firstpost_unique_design = 0;
			}
			?>
			<div class="blog_template bdp_wrapper <?php echo esc_attr( $bdp_theme ); ?>_cover bdp_archive <?php echo esc_attr( $bdp_theme ); ?>">
				<?php
				if ( 'accordion' === $bdp_theme ) {
					$template_icon_alignment = ( isset( $bdp_settings['template_icon_alignment'] ) && '' != $bdp_settings['template_icon_alignment'] ) ? $bdp_settings['template_icon_alignment'] : 'icon-left'; 
		
					$bdp_accordion_layout_class = ( isset( $bdp_settings['accordion_template'] ) && '' != $bdp_settings['accordion_template'] ) ? $bdp_settings['accordion_template'] : 'accordion-template-1'; 
					echo '<div class="blog_template accordion accordion_wrapper '. $bdp_accordion_layout_class .' ' . $template_icon_alignment . '">'; 
				}
				if ( 'offer_blog' === $bdp_theme ) {
					echo '<div class="bdp_single_offer_blog">';
				}
				if ( 'winter' === $bdp_theme ) {
					echo '<div class="bdp_single_winter">';
				}
				if ( 'author_template' === $bdp_settings['custom_archive_type'] ) {
					$display_author           = isset( $bdp_settings['display_author_data'] ) ? $bdp_settings['display_author_data'] : 0;
					$txt_author_title         = isset( $bdp_settings['txtAuthorTitle'] ) ? $bdp_settings['txtAuthorTitle'] : '[author]';
					$display_author_biography = $bdp_settings['display_author_biography'];
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
				}
				if ( 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
					echo '<div class="bdp-row ' . esc_attr( $bdp_theme ) . '">';
				}
				if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
					$column_setting        = ( isset( $bdp_settings['column_setting'] ) && '' != $bdp_settings['column_setting'] ) ? 'column_layout_' . $bdp_settings['column_setting'] : 'column_layout_2'; //phpcs:ignore
					$column_setting_ipad   = ( isset( $bdp_settings['column_setting_ipad'] ) && '' != $bdp_settings['column_setting_ipad'] ) ? 'column_layout_ipad_' . $bdp_settings['column_setting_ipad'] : 'column_layout_ipad_2'; //phpcs:ignore
					$column_setting_tablet = ( isset( $bdp_settings['column_setting_tablet'] ) && '' != $bdp_settings['column_setting_tablet'] ) ? 'column_layout_tablet_' . $bdp_settings['column_setting_tablet'] : 'column_layout_tablet_1'; //phpcs:ignore
					$column_setting_mobile = ( isset( $bdp_settings['column_setting_mobile'] ) && '' != $bdp_settings['column_setting_mobile'] ) ? 'column_layout_mobile_' . $bdp_settings['column_setting_mobile'] : 'column_layout_mobile_1'; //phpcs:ignore
					$column_class          = $column_setting . ' ' . $column_setting_ipad . ' ' . $column_setting_tablet . ' ' . $column_setting_mobile;
					echo '<div class="bdp-row ' . esc_attr( $column_class ) . ' ' . esc_attr( $bdp_theme ) . '">';
				}
				if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme ) {
					echo '<div class="bdp-js-masonry masonry bdp_' . esc_attr( $bdp_theme ) . '">';
				}
				if ( 'boxy-clean' === $bdp_theme ) {
					echo '<div class="blog_template boxy-clean"><ul>';
				}
				$slider_navigation = isset( $bdp_settings['navigation_style_hidden'] ) ? $bdp_settings['navigation_style_hidden'] : 'navigation3';
				if ( 'crayon_slider' === $bdp_theme ) {
					$unique_id = mt_rand(); //phpcs:ignore
					echo '<div class="blog_template slider_template crayon_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
				}
				if ( 'sallet_slider' === $bdp_theme ) {
					$unique_id = mt_rand(); //phpcs:ignore
					echo '<div class="blog_template slider_template sallet_slider ' . esc_attr( $slider_navigation ) . ' slider_' . esc_attr( $unique_id ) . '"><ul class="slides">';
				}
				if ( 'sunshiny_slider' === $bdp_theme ) {
					$unique_id = mt_rand(); //phpcs:ignore
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
					echo '<div class="blog_template wise_block_wrapper">';
				}
				global $wp_query;
				$posts_per_page = $bdp_settings['posts_per_page'];
				$orderby        = 'date';
				$order          = 'DESC';
				if ( isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) { //phpcs:ignore
					$orderby = $bdp_settings['bdp_blog_order_by'];
				}
				if ( isset( $bdp_settings['bdp_blog_order'] ) && isset( $bdp_settings['bdp_blog_order_by'] ) && '' != $bdp_settings['bdp_blog_order_by'] ) { //phpcs:ignore
					$order = $bdp_settings['bdp_blog_order'];
				}
				$paged       = Bdp_Utility::paged();
				$post_status = isset( $bdp_settings['bdp_post_status'] ) ? $bdp_settings['bdp_post_status'] : array( 'publish' );

				if ( 'category_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $bdp_settings['template_category'][0] ) ) {
						$cat = $bdp_settings['template_category'][0];
					} else {
						$cat = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'post_status'    => $post_status,
						'cat'            => $cat,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array( //phpcs:ignore
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} elseif ( 'tag_template' === $bdp_settings['custom_archive_type'] ) {
					if ( isset( $bdp_settings['template_tags'][0] ) ) {
						$tag = $bdp_settings['template_tags'][0];
					} else {
						$tag = '';
					}
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'post_status'    => $post_status,
						'tag_id'         => $tag,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array( //phpcs:ignore
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} elseif ( 'date_template' === $bdp_settings['custom_archive_type'] ) {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'post_status'    => $post_status,
						'year'           => get_query_var( 'year' ),
						'monthnum'       => get_query_var( 'monthnum' ),
						'day'            => get_query_var( 'day' ),
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array( //phpcs:ignore
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				} else {
					if ( 'meta_value_num' === $orderby ) {
						$orderby_str = $orderby . ' date';
					} else {
						$orderby_str = $orderby;
					}
					$arg_posts = array(
						'post_type'      => 'post',
						'posts_per_page' => $posts_per_page,
						'orderby'        => $orderby_str,
						'order'          => $order,
						'paged'          => $paged,
						'post_status'    => $post_status,
					);
					if ( 'meta_value_num' === $orderby ) {
						$arg_posts['meta_query'] = array( //phpcs:ignore
							'relation' => 'OR',
							array(
								'key'     => '_post_like_count',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => '_post_like_count',
								'compare' => 'EXISTS',
							),
						);
					}
				}
				if ( ( 'date' === $orderby || 'modified' === $orderby ) && isset( $bdp_settings['template_name'] ) && ( 'story' === $bdp_settings['template_name'] || 'timeline' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				if ( isset( $bdp_settings['template_name'] ) && ( 'explore' === $bdp_settings['template_name'] || 'hoverbic' === $bdp_settings['template_name'] ) ) {
					$arg_posts['ignore_sticky_posts'] = 1;
				}
				$loop       = new WP_Query( $arg_posts );
				$temp_query = $wp_query;
				$wp_query   = null; //phpcs:ignore
				$wp_query   = $loop; //phpcs:ignore
				$prev_year1 = null;
				$prev_year  = null;
				$alter_val  = null;
				$prev_month = null;
				if ( $loop->have_posts() ) {
					// Start the loop.
					while ( have_posts() ) :
						the_post();
						if ( isset( $bdp_settings['template_alternativebackground'] ) && 1 == $bdp_settings['template_alternativebackground'] ) { //phpcs:ignore
							if ( $alter % 2 == 0 ) { //phpcs:ignore
								$alter_class = ' alternative-back';
							} else {
								$alter_class = '';
							}
						}
						if ( 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'story' === $bdp_theme || 'fairy' === $bdp_theme || 'clicky' === $bdp_theme ) {
							if ( $alter % 2 == 0 ) { //phpcs:ignore
								$alter_class = 'even_class';
							} else {
								$alter_class = '';
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
											echo '<p class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $prev_year ) . '</span></span></p>';
										}
									} elseif ( isset( $bdp_settings['timeline_display_option'] ) && 'display_month' === $bdp_settings['timeline_display_option'] ) {
										$this_year  = get_the_date( 'Y' );
										$this_month = get_the_time( 'M' );
										$prev_year  = $this_year;
										if ( $prev_month != $this_month ) { //phpcs:ignore
											$prev_month = $this_month;
											echo '<p class="timeline_year"><span class="year_wrap"><span class="year">' . esc_html( $this_year ) . '</span><span class="month">' . esc_html( $prev_month ) . '</span></span></p>';
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
							if ( 'invert-grid' === $bdp_theme || 'boxy-clean' === $bdp_theme || 'news' === $bdp_theme || 'deport' === $bdp_theme || 'navia' === $bdp_theme || 'clicky' === $bdp_theme ) {
								if ( 1 == $firstpost_unique_design ) { //phpcs:ignore
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
						// Include the single post content template.
						Bdp_Template::get_template( 'archive/' . $bdp_theme . '.php' );
						do_action( 'bd_archive_design_format_function', $bdp_settings, $alter_class, $prev_year, $alter_val, $paged );
						$alter ++;
						// End of the loop.
					endwhile;
					if ( 'boxy-clean' === $bdp_theme || 'crayon_slider' === $bdp_theme || 'sallet_slider' === $bdp_theme || 'sunshiny_slider' === $bdp_theme ) {
						echo '</ul></div>';
					}
					if ( 'foodbox' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'neaty_block' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'wise_block' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'glossary' === $bdp_theme || 'boxy' === $bdp_theme || 'boxy' === $bdp_theme || 'brit_co' === $bdp_theme || 'glossary' === $bdp_theme || 'invert-grid' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'media-grid' === $bdp_theme || 'chapter' === $bdp_theme || 'roctangle' === $bdp_theme || 'glamour' === $bdp_theme || 'famous' === $bdp_theme || 'minimal' === $bdp_theme ) {
						echo '</div>';
					}
					if ( 'timeline' === $bdp_theme ) {
						echo '</div></div>';
					}
					if ( 'easy_timeline' === $bdp_theme || 'steps' === $bdp_theme ) {
						echo '</div></ul>';
					}
					if ( 'accordion' === $bdp_theme || 'offer_blog' === $bdp_theme || 'winter' === $bdp_theme || 'my_diary' === $bdp_theme || 'story' === $bdp_theme || 'brite' === $bdp_theme || 'cool_horizontal' === $bdp_theme || 'overlay_horizontal' === $bdp_theme ) {
						echo '</div>';
					}
					$slider_array = array( 'crayon_slider', 'sunshiny_slider', 'sallet_slider' );
					if ( in_array( $bdp_theme, $slider_array ) ) { //phpcs:ignore
						if ( ! wp_script_is( 'bdp-galleryimage-script', $list = 'enqueued' ) ) { //phpcs:ignore
							wp_enqueue_script( 'bdp-galleryimage-script' );
						}
						$template_slider_scroll    = isset( $bdp_settings['template_slider_scroll'] ) ? $bdp_settings['template_slider_scroll'] : 1;
						$display_slider_navigation = isset( $bdp_settings['display_slider_navigation'] ) ? $bdp_settings['display_slider_navigation'] : 1;
						$display_slider_controls   = isset( $bdp_settings['display_slider_controls'] ) ? $bdp_settings['display_slider_controls'] : 1;
						$slider_autoplay           = isset( $bdp_settings['slider_autoplay'] ) ? $bdp_settings['slider_autoplay'] : 1;
						$slider_autoplay_intervals = isset( $bdp_settings['slider_autoplay_intervals'] ) ? $bdp_settings['slider_autoplay_intervals'] : 7000;
						$slider_speed              = isset( $bdp_settings['slider_speed'] ) ? $bdp_settings['slider_speed'] : 600;
						$template_slider_effect    = isset( $bdp_settings['template_slider_effect'] ) ? $bdp_settings['template_slider_effect'] : 'slide';
						$slider_column             = 1;
						if ( 'slide' === $bdp_settings['template_slider_effect'] ) {
							$slider_column        = isset( $bdp_settings['template_slider_columns'] ) ? $bdp_settings['template_slider_columns'] : 1;
							$slider_column_ipad   = isset( $bdp_settings['template_slider_columns_ipad'] ) ? $bdp_settings['template_slider_columns_ipad'] : 1;
							$slider_column_tablet = isset( $bdp_settings['template_slider_columns_tablet'] ) ? $bdp_settings['template_slider_columns_tablet'] : 1;
							$slider_column_mobile = isset( $bdp_settings['template_slider_columns_mobile'] ) ? $bdp_settings['template_slider_columns_mobile'] : 1;
						} else {
							$slider_column        = 1;
							$slider_column_ipad   = 1;
							$slider_column_tablet = 1;
							$slider_column_mobile = 1;
						}
						$slider_arrow = isset( $bdp_settings['arrow_style_hidden'] ) ? $bdp_settings['arrow_style_hidden'] : 'arrow1';
						if ( '' == $slider_arrow ) { //phpcs:ignore
							$prev = "<i class='fas fa-chevron-left'></i>";
							$next = "<i class='fas fa-chevron-right'></i>";
						} else {
							$prev = "<div class='" . esc_attr( $slider_arrow ) . "'></div>";
							$next = "<div class='" . esc_attr( $slider_arrow ) . "'></div>";
						}
						?>
						<script type="text/javascript" class="dynamic_script">
							jQuery(document).ready(function () {
								var $maxItems = 1;
								if (jQuery(window).width() > 980) {
									$maxItems = <?php echo esc_attr( $slider_column ); ?>;
								} elseif(jQuery(window).width() <= 980 && jQuery(window).width() > 720) {
									$maxItems = <?php echo esc_attr( $slider_column_ipad ); ?>;
								} elseif(jQuery(window).width() <= 720 && jQuery(window).width() > 480) {
									$maxItems = <?php echo esc_attr( $slider_column_tablet ); ?>;
								} elseif(jQuery(window).width() <= 480) {
									$maxItems = <?php echo esc_attr( $slider_column_mobile ); ?>;
								}
								jQuery('.slider_' + <?php echo esc_attr( $unique_id ); ?>).flexslider({
								move: <?php echo esc_attr( $template_slider_scroll ); ?>,
										animation: '<?php echo esc_attr( $template_slider_effect ); ?>',itemWidth:10,itemMargin:15,minItems:1,maxItems:$maxItems,
										rtl: 
										<?php
										if ( is_rtl() ) {
											echo 1;
										} else {
											echo 0; }
										?>
										,
										<?php if ( $display_slider_navigation ) { ?>
												directionNav: true,
										<?php } else { ?>
												directionNav: false,
										<?php } ?>
										<?php if ( $display_slider_controls ) { ?>
												controlNav: true,
										<?php } else { ?>
												controlNav: false,
										<?php } ?>
										<?php if ( $slider_autoplay ) { ?>
												slideshow: true,
										<?php } else { ?>
												slideshow: false,
										<?php } ?>
										<?php if ( $slider_autoplay ) { ?>
												slideshowSpeed: <?php echo esc_attr( $slider_autoplay_intervals ); ?>,
										<?php } ?>
										<?php if ( $slider_speed ) { ?>
												animationSpeed: <?php echo esc_attr( $slider_speed ); ?>,
										<?php } ?>
										prevText: "<?php echo esc_attr( $prev ); ?>",
										nextText: "<?php echo esc_attr( $next ); ?>"
							});
							});
						</script>
						<?php
					}
					if ( ! in_array( $bdp_theme, $slider_array ) && isset( $bdp_settings['pagination_type'] ) && 'no_pagination' != $bdp_settings['pagination_type'] ) { //phpcs:ignore
						$pagination_template = isset( $bdp_settings['pagination_template'] ) ? $bdp_settings['pagination_template'] : 'template-1';
						echo '<div class="wl_pagination_box ' . esc_attr( $pagination_template ) . '">';
						echo Bdp_Posts::standard_paging_nav(); //phpcs:ignore
						echo '</div>';
					}
				} else {
					esc_html_e( 'No posts found', 'blog-designer-pro' );
				}
				wp_reset_query(); //phpcs:ignore
				$wp_query = null; //phpcs:ignore
				$wp_query = $temp_query; //phpcs:ignore
				?>
			</div>
			<?php
		}
		exit();
	}
	/**
	 * Ajax handler for Store closed box id
	 *
	 * @return void
	 */
	public function bdp_closed_bdpboxes() {
		$closed = isset( $_POST['closed'] ) ? explode( ',', $_POST['closed'] ) : array(); //phpcs:ignore
		$closed = array_filter( $closed );
		$page   = isset( $_POST['page'] ) ? $_POST['page'] : ''; //phpcs:ignore
		if ( $page != sanitize_key( $page ) ) { //phpcs:ignore
			wp_die( 0 );
		}
		if ( ! $user = wp_get_current_user() ) { //phpcs:ignore
			wp_die( -1 );
		}
		if ( is_array( $closed ) ) {
			update_user_option( $user->ID, "bdpclosedbdpboxes_$page", $closed, true );
		}
		wp_die( 1 );
	}
	/**
	 * Admin notice layouts notice dismiss
	 *
	 * @since 1.6
	 */
	public static function bdp_admin_notice_pro_layouts_dismiss() {
		update_option( 'bdp_admin_notice_pro_layouts_dismiss', true );
	}
	/**
	 * Admin notice layouts transfer notice dismiss
	 *
	 * @since 1.6
	 */
	public static function bdp_create_layout_from_blog_designer_dismiss() {
		update_option( 'bdp_admin_notice_create_layout_from_blog_designer_dismiss', true );
	}
	/**
	 * Blog Template Search Result
	 *
	 * @since 1.6
	 */
	public function bdp_blog_template_search_result() {
		$template_name = strtolower( sanitize_text_field( $_POST['temlate_name'] ) ); //phpcs:ignore
		$tempate_list  = Bdp_Template::blog_template_list();
		foreach ( $tempate_list as $key => $value ) {
			if ( '' == $template_name ) { //phpcs:ignore
				?>
				<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" <?php echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : ''; //phpcs:ignore ?>>
					<div class="template-thumbnail-inner">
						<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
						<div class="hover_overlay">
							<div class="popup-template-name">
								<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
								<div class="popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
							</div>
						</div>
					</div>
					<span class="bdp-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
				</div>
				<?php
			} elseif ( preg_match( '/' . trim( $template_name ) . '/', $key ) ) {
				?>
				<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" <?php echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : ''; //phpcs:ignore ?>>
					<div class="template-thumbnail-inner">
						<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
						<div class="hover_overlay">
							<div class="popup-template-name">
								<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
								<div class="popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
							</div>
						</div>
					</div>
					<span class="bdp-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
				</div>
				<?php
			}
		}
		exit();
	}

	/**
	 * Single Blog Template Search Result
	 *
	 * @since 1.6
	 */
	public function bdp_single_blog_template_search_result() {
		$template_name = sanitize_text_field( $_POST['temlate_name'] ); //phpcs:ignore
		$tempate_list  = Bdp_Template::single_blog_template_list();
		foreach ( $tempate_list as $key => $value ) {
			if ( '' == $template_name ) { //phpcs:ignore
				?>
				<div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" <?php echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : ''; //phpcs:ignore ?>>
					<div class="template-thumbnail-inner">
						<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
						<div class="hover_overlay">
							<div class="popup-template-name">
								<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
								<div class="popup-view"><a href="<?php echo esc_url( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
							</div>
						</div>
					</div>
					<span class="bdp-span-template-name"><?php echo esc_html( $value['template_name'] ); ?></span>
				</div>
				<?php
			} elseif ( preg_match( '/' . trim( $template_name ) . '/', $key ) ) {
				?>
				<div class="template-thumbnail <?php echo esc_attr( esc_attr( $value['class'] ) ); ?>" <?php echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : ''; //phpcs:ignore ?>>
					<div class="template-thumbnail-inner">
						<img src="<?php echo esc_url( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
						<div class="hover_overlay">
							<div class="popup-template-name">
								<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
								<div class="popup-view"><a href="<?php echo esc_html( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
							</div>
						</div>
					</div>
					<span class="bdp-span-template-name"><?php echo esc_html( $value['template_name'] ); ?></span>
				</div>
				<?php
			}
		}
		exit();
	}
	/**
	 * Admin notice template outdated notice dismiss
	 *
	 * @since 2.0
	 */
	public function bdp_notice_template_outdated_dismiss() {
		update_option( 'bdp_template_outdated', 1 );
	}
}
new Bdp_Ajax_Actions();
