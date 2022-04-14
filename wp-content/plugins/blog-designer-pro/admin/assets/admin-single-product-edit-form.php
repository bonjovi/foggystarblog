<?php
/**
 * Add/Edit Single Layout setting page
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

global $wp_version, $wpdb, $bdp_errors, $bdp_success, $bdp_settings;
if ( isset( $_GET['page'] ) && 'single_product' === $_GET['page'] ) { //phpcs:ignore
	$page                 = esc_attr( $_GET['page'] ); //phpcs:ignore
	$bdp_settings         = array();
	$custom_single_type   = '';
	$single_template_name = '';
	if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) && is_numeric( $_GET['id'] ) ) { //phpcs:ignore
		$single_id       = intval( $_GET['id'] ); //phpcs:ignore
		$table_name      = $wpdb->prefix . 'bdp_single_product';
		$get_qry         = "SELECT * FROM $table_name WHERE ID = $single_id";
		$get_allsettings = $wpdb->get_results( $get_qry, ARRAY_A ); //phpcs:ignore
		if ( ! isset( $get_allsettings[0]['settings'] ) ) {
			echo '<div class="updated notice">';
			wp_die( esc_html__( 'You attempted to edit an item that doesn’t exist. Perhaps it was deleted?', 'blog-designer-pro' ) );
			echo '</div>';
		}
		$allsettings = $get_allsettings[0]['settings'];
		if ( is_serialized( $allsettings ) ) {
			$bdp_settings         = unserialize( $allsettings ); //phpcs:ignore
			$custom_single_type   = $get_allsettings[0]['single_product_template'];
			$single_template_name = $get_allsettings[0]['single_product_name'];
		}
	}
}
$font_family   = Bdp_Utility::default_recognized_font_faces();
$template_name = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';

$tempate_list = Bdp_Template::single_blog_template_list();

if ( 'brite' === $template_name || 'minimal' === $template_name || 'clicky' === $template_name ) {
	$winter_category_txt = esc_html__( 'Choose Tags Background Color', 'blog-designer-pro' );
} else {
	$winter_category_txt = esc_html__( 'Choose Category Background Color', 'blog-designer-pro' );
}
?>
<div class="wrap">
	<?php
	if ( isset( $bdp_errors ) ) {
		if ( is_wp_error( $bdp_errors ) ) {
			?>
			<div class="error notice">
				<p><?php echo esc_html( $bdp_errors->get_error_message() ); ?></p>
			</div>
			<?php
		}
	}
	if ( isset( $bdp_success ) ) {
		?>
		<div class="updated notice">
			<p><?php echo $bdp_success; //phpcs:ignore ?></p>
		</div>
		<?php
	}
	if ( isset( $_GET['message'] ) && 'single_added_msg' === $_GET['message'] ) { //phpcs:ignore
		?>
		<div class="updated notice">
			<p><?php esc_html_e( 'Single layout added successfully.', 'blog-designer-pro' ); ?></p>
		</div>
		<?php
	}
	?>
	<h1 class="bdp-shortcode-div">
		<div class= "pull-left bdp_edit_layout">
		<?php
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) { //phpcs:ignore
			esc_html_e( 'Edit Single Product Design', 'blog-designer-pro' );
		} else {
			esc_html_e( 'Add Single Product Layout', 'blog-designer-pro' );
		}
		?>
		</div>
		<?php
        if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) { //phpcs:ignore
			?>
			<div class="pull-right">
			<a class="page-title-action bdp_create_new_layout" href="?page=single_product">
				<?php esc_html_e( 'Create New Single Layout', 'blog-designer-pro' ); ?>
			</a>
			</div>
			<?php
		}
		?>
	</h1>
	<?php
	if ( isset( $_GET['message'] ) && 'shortcode_duplicate_msg' === $_GET['message'] ) { //phpcs:ignore
		?>
		<div class="updated notice">
			<p><?php esc_html_e( 'Layout duplicated successfully. Please Select Product Categories or Tags and Select Products for this layout.', 'blog-designer-pro' ); ?></p>
		</div>
		<?php
	}
	?>
	<form method="post" id="single-layout-form" action="" class="bd-form-class single-layout-product">
		<?php
		wp_nonce_field( 'bdp-product-shortcode-form-submit', 'bdp-single_product-submit-nonce' );
		$page = ''; //phpcs:ignore
		if ( isset( $_GET['page'] ) && '' != $_GET['page'] ) { //phpcs:ignore
			$page = esc_attr( $_GET['page'] ); //phpcs:ignore
			?>
			<input type="hidden" name="originalpage" class="bdporiginalpage" value="<?php echo esc_attr( $page ); ?>">
		<?php } ?>
		<div id="poststuff">
			<div class="postbox-container bd-settings-wrappers bd_poststuff">
				<div class="bd-header-wrapper">
					<div class="bd-logo-wrapper pull-left">
						<h3><?php esc_html_e( 'Blog designer settings', 'blog-designer-pro' ); ?></h3>
					</div>
					<div class="pull-right">
						<a id="bdp-btn-single" title="<?php esc_html_e( 'Save Changes', 'blog-designer-pro' ); ?>" class="show_single_save button submit_fixed button-primary">
							<span><i class="fas fa-check"></i>&nbsp;&nbsp;<?php esc_html_e( 'Save Changes', 'blog-designer-pro' ); ?></span>
						</a>
						<input type="submit" style="display:none;" class="button-primary bdp_single_save_btn" name="savedata" value="<?php esc_attr_e( 'Save Changes', 'blog-designer-pro' ); ?>" />
					</div>
				</div>
				<div class="bd-menu-setting">
					<?php
					$bdpgeneral_class                   = '';
					$bdpmedia_class                     = '';
					$bdpstandard_class                  = '';
					$bdptitle_class                     = '';
					$bdpauthor_class                    = '';
					$bdpcontent_class                   = '';
					$bdprelatd_class                    = '';
					$bdpsinglepostnavigation_class      = '';
					$bdpsocial_class                    = '';
					$bdpproductsetting_class            = '';
					$bdpacffieldssetting_class          = '';
					$bdpgeneral_class_show              = '';
					$bdpmedia_class_show                = '';
					$bdpstandard_class_show             = '';
					$bdptitle_class_show                = '';
					$bdpauthor_class_show               = '';
					$bdpcontent_class_show              = '';
					$bdprelated_class_show              = '';
					$bdpsinglepostnavigation_class_show = '';
					$bdpsocial_class_show               = '';
					$bdpproductsetting_class_show       = '';
					$bdpacffieldssetting_class_show     = '';
					if ( Bdp_Posts::postbox_classes( 'bdpsinglegeneral', $page ) ) {
						$bdpgeneral_class      = 'class="bd-active-tab"';
						$bdpgeneral_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglestandard', $page ) ) {
						$bdpstandard_class      = 'class="bd-active-tab"';
						$bdpstandard_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsingletitle', $page ) ) {
						$bdptitle_class      = 'class="bd-active-tab"';
						$bdptitle_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglepostauthor', $page ) ) {
						$bdpauthor_class      = 'class="bd-active-tab"';
						$bdpauthor_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsingleconent', $page ) ) {
						$bdpcontent_class      = 'class="bd-active-tab"';
						$bdpcontent_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglemedia', $page ) ) {
						$bdpmedia_class      = 'class="bd-active-tab"';
						$bdpmedia_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsingleproductsetting', $page ) ) {
						$bdpproductsetting_class      = 'class="bd-active-tab"';
						$bdpproductsetting_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglerelated', $page ) ) {
						$bdprelatd_class       = 'class="bd-active-tab"';
						$bdprelated_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglepostnavigation', $page ) ) {
						$bdpsinglepostnavigation_class      = 'class="bd-active-tab"';
						$bdpsinglepostnavigation_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpacffield', $page ) ) {
						$bdpsocial_class      = 'class="bd-active-tab"';
						$bdpsocial_class_show = 'style="display: block;"';
					} elseif ( Bdp_Posts::postbox_classes( 'bdpsinglesocial', $page ) ) {
						$bdpsocial_class      = 'class="bd-active-tab"';
						$bdpsocial_class_show = 'style="display: block;"';
					} else {
						$bdpgeneral_class      = 'class="bd-active-tab"';
						$bdpgeneral_class_show = 'style="display: block;"';
					}
					?>
					<ul class="bd-setting-handle">
						<li data-show="bdpsinglegeneral" <?php echo $bdpgeneral_class; //phpcs:ignore ?>>
							<i class="fas fa-cog"></i>
							<span><?php esc_html_e( 'General Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglestandard" <?php echo $bdpstandard_class; //phpcs:ignore ?>>
							<i class="fas fa-gavel"></i>
							<span><?php esc_html_e( 'Standard Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsingletitle" <?php echo $bdptitle_class; //phpcs:ignore ?>>
							<i class="fas fa-text-width"></i>
							<span><?php esc_html_e( 'Title Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsingleconent" <?php echo $bdpcontent_class; //phpcs:ignore ?>>
							<i class="far fa-file-alt"></i>
							<span><?php esc_html_e( 'Content Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglemedia" <?php echo $bdpmedia_class; //phpcs:ignore ?>>
							<i class="far fa-image"></i>
							<span><?php esc_html_e( 'Media Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsingleproductsetting" <?php echo $bdpproductsetting_class; //phpcs:ignore ?>>
							<i class="fas fa-shopping-cart"></i>
							<span><?php esc_html_e( 'Product Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglepostnavigation" <?php echo $bdpsinglepostnavigation_class; //phpcs:ignore ?>>
							<i class="fas fa-exchange-alt"></i>
							<span><?php esc_html_e( 'Product Navigation (Next/Previous) Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglepostauthor" <?php echo $bdpauthor_class; //phpcs:ignore ?>>
							<i class="fas fa-user"></i>
							<span><?php esc_html_e( 'Author Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<li data-show="bdpsinglerelated" <?php echo $bdprelatd_class; //phpcs:ignore ?>>
							<i class="fas fa-th-large"></i>
							<span><?php esc_html_e( 'Related Product Settings', 'blog-designer-pro' ); ?></span>
						</li>
						<?php
						if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
							$groups = acf_get_field_groups();
							if ( ! empty( $groups ) ) {
								?>
								<li data-show="bdpacffieldssetting" <?php echo $bdpacffieldssetting_class; //phpcs:ignore ?>>
									<i class="fas fa-plus-square"></i>
									<span><?php esc_html_e( 'Acf Field Settings', 'blog-designer-pro' ); ?></span>
								</li>
								<?php
							}
						}
						?>
						<li data-show="bdpsinglesocial" <?php echo $bdpsocial_class; //phpcs:ignore ?>>
							<i class="fas fa-share-alt"></i>
							<span><?php esc_html_e( 'Social Share Settings', 'blog-designer-pro' ); ?></span>
						</li>
					</ul>
				</div>
				<div id="bdpsinglegeneral" class="postbox postbox-with-fw-options"<?php echo $bdpgeneral_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<h3 class="bdp-table-title"><?php esc_html_e( 'Select Single Product Layout', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-left">
									<p class="bdp-margin-bottom-50"><?php esc_html_e( 'Select your favorite layout from 45+ powerful single product layouts.', 'blog-designer-pro' ); ?> </p>
									<p class="bdp-margin-bottom-30"><b><?php esc_html_e( 'Current Template:', 'blog-designer-pro' ); ?></b> &nbsp;&nbsp;
										<span class="bdp-template-name">
										<?php
										if ( isset( $tempate_list[ $template_name ]['template_name'] ) ) {
											echo esc_html( $tempate_list[ $template_name ]['template_name'] );
										} else {
											echo esc_html__( 'Classical Template', 'blog-designer-pro' );
										}
										?>
										</span>
									</p>
									<div class="bdp_select_template_button_div">
										<input type="button" class="bdp_select_template" value="<?php esc_attr_e( 'Select Other Template', 'blog-designer-pro' ); ?>">
										<?php
										if ( isset( $_GET['page'] ) && 'add_shortcode' === $_GET['page'] && ! isset( $_GET['action'] ) ) { //phpcs:ignore
											$bdpcrtscode = 'bdp_template_name bdp-create-shortcode';
										} else{
											$bdpcrtscode = 'bdp_template_name';
										}
										if ( $template_name ) {
											$temp_nameval = $template_name;
										} else{
											$temp_nameval = '';
										}
										?>
										<input type="hidden" class="<?php echo esc_attr( $bdpcrtscode ); ?>" value="<?php echo esc_attr( $temp_nameval ); ?>" name="template_name">
									</div>
									<?php if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] && isset( $_GET['id'] ) ) { //phpcs:ignore ?>
										<input type="submit" class="bdp-reset-data" name="resetdata" value="<?php esc_attr_e( 'Reset Layout Settings', 'blog-designer-pro' ); ?>" />
										<?php
									}
									?>
								</div>
								<div class="bdp-right">
									<div class="select_button_upper_div">
										<div class="bdp_selected_template_image">
											<div 
											<?php
											$template_name = isset( $bdp_settings['template_name'] ) ? $bdp_settings['template_name'] : 'classical';
											if ( '' === $template_name ) {
												echo ' class="bdp_no_template_found"';
											}
											?>
											>
												<?php
												if ( '' != $template_name ) { //phpcs:ignore
													$image_name = $template_name . '.jpg';
													?>
													<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $image_name ); ?>" alt=" 
													<?php
													if ( isset( $bdp_settings['template_name'] ) ) {
														echo esc_attr( $tempate_list[ $bdp_settings['template_name'] ]['template_name'] );
													}
													?>
													" title=" 
													<?php
													if ( isset( $bdp_settings['template_name'] ) ) {
														echo esc_attr( $tempate_list[ $bdp_settings['template_name'] ]['template_name'] );
													}
													?>
													" />
													<label id="template_select_name">
														<?php
														if ( isset( $tempate_list[ $template_name ]['template_name'] ) ) {
															echo esc_html( $tempate_list[ $template_name ]['template_name'] );
														} else {
															echo esc_html__( 'Classical Template', 'blog-designer-pro' );
														}
														?>
													</label>
													<?php
												} else {
													esc_html_e( 'No template exist for selection', 'blog-designer-pro' );
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</li>

							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Template Color Preset', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right bdp-preset-position">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select color preset', 'blog-designer-pro' ); ?></span></span>
									<?php
									$bdp_color_preset      = isset( $bdp_settings['bdp_color_preset'] ) ? $bdp_settings['bdp_color_preset'] : $template_name . '_default';
									$template_color_preset = array(
										'boxy'             => array(
											'boxy_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E84159,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#999999',
											),
											'boxy_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A2855B,template_ftcolor:#555555,template_fthovercolor:#A2855B,template_titlecolor:#8B6632,related_title_color:#8B6632,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8B6632,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A2855B,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8B6632,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8B6632,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A2855B',
												'display_value' => '#8B6632,#A2855B,#555555,#999999',
											),
											'boxy_black_pearl' => array(
												'preset_name' => esc_html__( 'Black Pearl', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#44545D,template_ftcolor:#555555,template_fthovercolor:#44545D,template_titlecolor:#152934,related_title_color:#152934,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#152934,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#44545D,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#152934,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#152934,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#44545D',
												'display_value' => '#152934,#44545D,#555555,#999999',
											),
											'boxy_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#555555,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E663C,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E8563,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E663C,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E663C,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#555555,#999999',
											),
											'boxy_peru_tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#916748,template_ftcolor:#555555,template_fthovercolor:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#75411A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#916748,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#75411A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75411A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#555555,#999999',
											),
											'boxy_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6D4657,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#49182D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D4657,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#49182D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#555555,#999999',
											),
										),
										'boxy-clean'       => array(
											'boxy-clean_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'boxy-clean_mandalay' => array(
												'preset_name' => esc_html__( 'Mandalay', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#C18F55,template_ftcolor:#555555,template_fthovercolor:#C18F55,template_titlecolor:#B1732A,related_title_color:#B1732A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#B1732A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#C18F55,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#B1732A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#C18F55',
												'display_value' => '#B1732A,#C18F55,#555555,#888888',
											),
											'boxy-clean_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#ED4961,template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E81B3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#ED4961,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E81B3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#555555,#888888',
											),
											'boxy-clean_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#A2855B,template_ftcolor:#555555,template_fthovercolor:#A2855B,template_titlecolor:#8B6632,related_title_color:#8B6632,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8B6632,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A2855B,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8B6632,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A2855B',
												'display_value' => '#8B6632,#A2855B,#555555,#888888',
											),
											'boxy-clean_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#6D4657,template_ftcolor:#555555,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8B6632,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D4657,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8B6632,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#555555,#888888',
											),
											'boxy-clean_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#435F7F,template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#14375F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#435F7F,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#14375F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#14375F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#888888',
											),
										),
										'brit_co'          => array(
											'brit_co_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagbgcolor:#239190,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#3E91AD,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#0E7699,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#3E91AD,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#0E7699',
												'display_value' => '#0E7699,#3E91AD,#555555,#666666',
											),
											'brit_co_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#7D194F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#974772,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#7D194F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7D194F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#555555,#666666',
											),
											'brit_co_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#666666',
											),
											'brit_co_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF6F1,template_ftcolor:#555555,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E4802A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E99955,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E4802A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E4802A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#666666',
											),
											'brit_co_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EEF1F4,template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#14375F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#435F7F,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#14375F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#14375F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#666666',
											),
											'brit_co_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EEF4F1,template_ftcolor:#555555,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E663C,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E8563,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E663C,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E663C,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#555555,#666666',
											),
										),
										'brite'            => array(
											'brite_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#3E91AD,winter_category_color:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#999999',
											),
											'brite_mandalay' => array(
												'preset_name' => esc_html__( 'Mandalay', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#C18F55,winter_category_color:#C18F55,template_titlecolor:#B1732A,related_title_color:#B1732A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#B1732A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#C18F55,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#B1732A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#B1732A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#C18F55',
												'display_value' => '#B1732A,#C18F55,#555555,#999999',
											),
											'brite_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,winter_category_color:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#999999',
											),
											'brite_red_violet' => array(
												'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#C44C91,winter_category_color:#C44C91,template_titlecolor:#B51F76,related_title_color:#B51F76,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#B51F76,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#C44C91,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#B51F76,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#B51F76,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#C44C91',
												'display_value' => '#B51F76,#C44C91,#555555,#999999',
											),
											'brite_peru_tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#916748,winter_category_color:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#75411A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#916748,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#75411A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75411A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#555555,#999999',
											),
											'brite_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#CEBF59,winter_category_color:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#CEBF59,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
											),
										),
										'chapter'          => array(
											'chapter_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'chapter_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#CEBF59,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#888888',
											),
											'chapter_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'chapter_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'chapter_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C62AA,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A381BB,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C62AA,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C62AA,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#555555,#888888',
											),
											'chapter_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'classical'        => array(
											'classical_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#777777',
											),
											'classical_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#BA7850,template_ftcolor:#BA7850,template_fthovercolor:#555555,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BA7850,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#555555,#777777',
											),
											'classical_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#67704E,template_ftcolor:#67704E,template_fthovercolor:#555555,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#777777',
											),
											'classical_terracotta' => array(
												'preset_name' => esc_html__( 'Terracotta', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#B06F6D,template_ftcolor:#B06F6D,template_fthovercolor:#555555,template_titlecolor:#9C4B48,related_title_color:#9C4B48,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#9C4B48,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#B06F6D,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#9C4B48,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#9C4B48,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#B06F6D',
												'display_value' => '#9C4B48,#B06F6D,#555555,#777777',
											),
											'classical_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF7F1,template_color:#E5A452,template_ftcolor:#E5A452,template_fthovercolor:#555555,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DF8D27,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E5A452,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DF8D27,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF8D27,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#555555,#777777',
											),
											'classical_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F6F7F1,template_color:#93A564,template_ftcolor:#93A564,template_fthovercolor:#555555,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93A564,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#777777',
											),
										),
										'cool_horizontal'  => array(
											'cool_horizontal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#e21130,template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#e21130,related_title_color:#e21130,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#e21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#e21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#e21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#e21130,#666666,#e21130,#444444',
											),
											'cool_horizontal_pink' => array(
												'preset_name' => esc_html__( 'Pink', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#D33683,template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#D33683,related_title_color:#D33683,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#D33683,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#D33683,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#D33683,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#D33683,#666666,#D33683,#444444',
											),
											'cool_horizontal_blue' => array(
												'preset_name' => esc_html__( 'Chetwode Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6C71C3,template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#6C71C3,related_title_color:#6C71C3,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6C71C3,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6C71C3,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6C71C3,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#6C71C3,#666666,#6C71C3,#444444',
											),
											'cool_horizontal_java' => array(
												'preset_name' => esc_html__( 'Java', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#29A198,template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#29A198,related_title_color:#29A198,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#29A198,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#29A198,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#29A198,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#29A198,#666666,#29A198,#444444',
											),
											'cool_horizontal_curious_blue' => array(
												'preset_name' => esc_html__( 'Curious Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#268BD3,template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#268BD3,related_title_color:#268BD3,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#268BD3,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#268BD3,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#268BD3,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#268BD3,#666666,#268BD3,#444444',
											),
											'cool_horizontal_olive' => array(
												'preset_name' => esc_html__( 'Olive', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#869901,template_ftcolor:#666666,template_fthovercolor:#333333,template_titlecolor:#869901,related_title_color:#869901,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#869901,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#666666,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#869901,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#869901,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#869901,#666666,#869901,#444444',
											),
										),
										'deport'           => array(
											'deport_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E91AD,template_ftcolor:#555555,template_fthovercolor:#3E91AD,deport_dashcolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'deport_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E99955,template_ftcolor:#555555,template_fthovercolor:#E99955,deport_dashcolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E4802A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E99955,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E4802A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E4802A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#888888',
											),
											'deport_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#CEBF59,template_ftcolor:#555555,template_fthovercolor:#CEBF59,deport_dashcolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#CEBF59,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#888888',
											),
											'deport_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#ED4961,template_ftcolor:#555555,template_fthovercolor:#ED4961,deport_dashcolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E81B3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#ED4961,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E81B3A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E81B3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#555555,#888888',
											),
											'deport_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A381BB,template_ftcolor:#555555,template_fthovercolor:#A381BB,deport_dashcolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C62AA,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A381BB,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C62AA,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C62AA,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#555555,#888888',
											),
											'deport_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#8BA3CE,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,deport_dashcolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'easy_timeline'    => array(
											'easy_timeline_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#E21130,template_fthovercolor:#444444,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#E21130,#999999,#444444,#666666',
											),
											'easy_timeline_dim_gray' => array(
												'preset_name' => esc_html__( 'Dim Gray', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#666666,template_fthovercolor:#f1f1f1,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#666666,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#666666,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#666666,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#666666,#999999,#444444,#444444',
											),
											'easy_timeline_flamenco' => array(
												'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#E18942,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E18942,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E18942,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E18942,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#E18942,#999999,#444444,#666666',
											),
											'easy_timeline_jagger' => array(
												'preset_name' => esc_html__( 'Jagger', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#3D3242,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#3D3242,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#3D3242,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#3D3242,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#3D3242,#999999,#444444,#666666',
											),
											'easy_timeline_camelot' => array(
												'preset_name' => esc_html__( 'Camelot', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#7A3E48,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#7A3E48,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#7A3E48,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7A3E48,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#7A3E48,#999999,#444444,#666666',
											),
											'easy_timeline_sundance' => array(
												'preset_name' => esc_html__( 'Sundance', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#bfbfbf,template_ftcolor:#C59F4A,template_fthovercolor:#999999,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C59F4A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C59F4A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C59F4A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#C59F4A,#999999,#444444,#666666',
											),
										),
										'elina'            => array(
											'elina_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E84159,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#333333,#555555',
											),
											'elina_madras' => array(
												'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#6D6145,template_titlecolor:#493917,related_title_color:#493917,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#493917,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D6145,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#493917,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#493917,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6D6145',
												'display_value' => '#493917,#6D6145,#333333,#555555',
											),
											'elina_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'elina_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#333333,#555555',
											),
											'elina_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF7F1,template_ftcolor:#333333,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DF8D27,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E5A452,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DF8D27,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF8D27,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'elina_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF7F1,template_ftcolor:#333333,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'evolution'        => array(
											'evolution_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'evolution_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4E9E1,template_ftcolor:#333333,template_fthovercolor:#BA7850,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BA7850,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'evolution_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCE1E4,template_ftcolor:#333333,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E81B3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#ED4961,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E81B3A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E81B3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
											'evolution_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FBEDE2,template_ftcolor:#333333,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E4802A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E99955,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E4802A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E4802A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#333333,#555555',
											),
											'evolution_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DFEAE5,template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E663C,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#0E663C,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E663C,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E663C,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'evolution_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ECF0F7,template_ftcolor:#333333,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'explore'          => array(
											'explore_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#44545D,template_titlecolor:#152934,related_title_color:#152934,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#152934,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#44545D,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#152934,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#152934,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#44545D',
												'display_value' => '#152934,#44545D,#333333,#555555',
											),
											'explore_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#A39D5A,template_titlecolor:#8C8431,related_title_color:#8C8431,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C8431,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A39D5A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C8431,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C8431,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A39D5A',
												'display_value' => '#8C8431,#A39D5A,#333333,#555555',
											),
											'explore_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#BA7850,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BA7850,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'explore_catalina_blue' => array(
												'preset_name' => esc_html__( 'Catalina Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F0F1F4,template_ftcolor:#333333,template_fthovercolor:#495F85,template_titlecolor:#1B3766,related_title_color:#1B3766,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#1B3766,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#495F85,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#1B3766,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#1B3766,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#495F85',
												'display_value' => '#1B3766,#495F85,#333333,#555555',
											),
											'explore_red_violet' => array(
												'preset_name' => esc_html__( 'Red Violet', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAF0F6,template_ftcolor:#333333,template_fthovercolor:#C44C91,template_titlecolor:#B51F76,related_title_color:#B51F76,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#B51F76,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#C44C91,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#B51F76,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#B51F76,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#C44C91',
												'display_value' => '#B51F76,#C44C91,#333333,#555555',
											),
											'explore_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F3F0F1,template_ftcolor:#333333,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#49182D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D4657,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#49182D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
										),
										'glossary'         => array(
											'glossary_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#ea4335,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#ea4335,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#ea4335,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#ea4335,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#ea4335,#222222,#555555,#888888',
											),
											'glossary_madras' => array(
												'preset_name' => esc_html__( 'Madras', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#6D6145,template_titlecolor:#493917,related_title_color:#493917,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#493917,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D6145,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#493917,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#493917,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6D6145',
												'display_value' => '#493917,#6D6145,#555555,#888888',
											),
											'glossary_catalina_blue' => array(
												'preset_name' => esc_html__( 'Catalina Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#495F85,template_titlecolor:#1B3766,related_title_color:#1B3766,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#1B3766,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#495F85,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#1B3766,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#1B3766,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#495F85',
												'display_value' => '#1B3766,#495F85,#555555,#888888',
											),
											'glossary_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#495F85,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#974772,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#495F85,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#495F85,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#495F85,#974772,#555555,#888888',
											),
											'glossary_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'glossary_peru-tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#75411A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#916748,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#75411A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75411A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#555555,#888888',
											),
										),
										'hub'              => array(
											'hub_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#555555,#888888',
											),
											'hub_crimson' => array(
												'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'hub_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'hub_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E4802A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E99955,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E4802A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E4802A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#888888',
											),
											'hub_wasabi'  => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93A564,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#888888',
											),
											'hub_yonder'  => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'invert-grid'      => array(
											'invert-grid_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#47c2dc,template_ftcolor:#d35400,template_fthovercolor:#555555,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#47c2dc,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#d35400,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#47c2dc,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#47c2dc,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#d35400',
												'display_value' => '#47c2dc,#d35400,#333333,#555555',
											),
											'invert-grid_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#8B6632,template_ftcolor:#A2855B,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8B6632,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A2855B,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8B6632,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8B6632,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A2855B',
												'display_value' => '#8B6632,#A2855B,#333333,#555555',
											),
											'invert-grid_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E663C,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E8563,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E663C,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E663C,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'invert-grid_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6D4657,template_ftcolor:#333333,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#49182D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6D4657,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#49182D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#49182D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6D4657',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
											'invert-grid_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E5A452,template_ftcolor:#333333,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DF8D27,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E5A452,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DF8D27,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF8D27,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'invert-grid_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#ED4961,template_ftcolor:#333333,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E81B3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#ED4961,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E81B3A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E81B3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
										),
										'lightbreeze'      => array(
											'lightbreeze_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#f5ab35,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#f5ab35,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4c4c4c,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#f5ab35,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#f5ab35,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#f5ab35,#ffffff,#4c4c4c,#808080',
											),
											'lightbreeze_pink' => array(
												'preset_name' => esc_html__( 'Pink', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FEF5F8,template_ftcolor:#e21130,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#e21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#e21130,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#e21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#e21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#e21130',
												'display_value' => '#e21130,#FEF5F8,#e21130,#808080',
											),
											'lightbreeze_solitude' => array(
												'preset_name' => esc_html__( 'Solitude', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FFF3E5,template_ftcolor:#FF8A00,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#FF8A00,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4c4c4c,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#FF8A00,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#FF8A00,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#FF8A00,#FFF3E5,#4c4c4c,#808080',
											),
										),
										'masonry_timeline' => array(
											'masonry_timeline_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#E21130,template_fthovercolor:#333333,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#E21130,#222222,#333333,#555555',
											),
											'masonry_timeline_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#8C8431,template_fthovercolor:#333333,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C8431,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C8431,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C8431,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#8C8431,#222222,#333333,#555555',
											),
											'masonry_timeline_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'masonry_timeline_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
											'masonry_timeline_peru_tan' => array(
												'preset_name' => esc_html__( 'Peru Tan', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#916748,template_titlecolor:#75411A,related_title_color:#75411A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#75411A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#916748,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#75411A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75411A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#916748',
												'display_value' => '#75411A,#916748,#333333,#555555',
											),
											'masonry_timeline_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E663C,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E8563,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E663C,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E663C,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
										),
										'media-grid'       => array(
											'media-grid_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'media-grid_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#BA7850,template_ftcolor:#333333,template_fthovercolor:#BA7850,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BA7850,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'media-grid_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#974772,template_ftcolor:#333333,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#7D194F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#974772,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#7D194F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7D194F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#333333,#555555',
											),
											'media-grid_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#67704E,template_ftcolor:#333333,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#333333,#555555',
											),
											'media-grid_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A381BB,template_ftcolor:#333333,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C62AA,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A381BB,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C62AA,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C62AA,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#333333,#555555',
											),
											'media-grid_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#8BA3CE,template_ftcolor:#333333,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'my_diary'         => array(
											'my_diary_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#333333,#555555',
											),
											'my_diary_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#A39D5A,template_titlecolor:#8C8431,related_title_color:#8C8431,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C8431,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A39D5A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C8431,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C8431,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A39D5A',
												'display_value' => '#8C8431,#A39D5A,#333333,#555555',
											),
											'my_diary_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#333333,#555555',
											),
											'my_diary_mandalay' => array(
												'preset_name' => esc_html__( 'Mandalay', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#BA7850,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BA7850,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#333333,#555555',
											),
											'my_diary_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EEF1F4,template_ftcolor:#333333,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#14375F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#435F7F,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#14375F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#14375F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#333333,#555555',
											),
											'my_diary_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F5F7FB,template_ftcolor:#333333,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'navia'            => array(
											'navia_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#555555,#999999',
											),
											'navia_toddy'  => array(
												'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,related_title_color:#AE742A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#AE742A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BE9055,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#AE742A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#AE742A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BE9055',
												'display_value' => '#AE742A,#BE9055,#555555,#999999',
											),
											'navia_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#999999',
											),
											'navia_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#14375F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#435F7F,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#14375F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#14375F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#999999',
											),
											'navia_claret' => array(
												'preset_name' => esc_html__( 'Claret', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#93425D,template_titlecolor:#781335,related_title_color:#781335,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#781335,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93425D,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#781335,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#781335,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93425D',
												'display_value' => '#781335,#93425D,#555555,#999999',
											),
											'navia_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#CEBF59,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
											),
										),
										'news'             => array(
											'news_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#A95624,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#A95624,#222222,#555555,#999999',
											),
											'news_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#999999',
											),
											'news_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#7D194F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#974772,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#7D194F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7D194F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#555555,#999999',
											),
											'news_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E81B3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#ED4961,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E81B3A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E81B3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#555555,#999999',
											),
											'news_wasabi'  => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93A564,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#999999',
											),
											'news_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#CEBF59,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#999999',
											),
										),
										'nicy'             => array(
											'nicy_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#3E91AD,template_ftcolor:#3E91AD,template_fthovercolor:#555555,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#777777',
											),
											'nicy_rich_gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#BA7850,template_ftcolor:#BA7850,template_fthovercolor:#555555,template_titlecolor:#A95624,related_title_color:#A95624,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A95624,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BA7850,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A95624,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BA7850',
												'display_value' => '#A95624,#BA7850,#555555,#777777',
											),
											'nicy_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#67704E,template_ftcolor:#67704E,template_fthovercolor:#555555,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E967704E1AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#555555,#777777',
											),
											'nicy_terracotta' => array(
												'preset_name' => esc_html__( 'Terracotta', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#B06F6D,template_ftcolor:#B06F6D,template_fthovercolor:#555555,template_titlecolor:#9C4B48,related_title_color:#9C4B48,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#9C4B48,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#B06F6D,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#9C4B48,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#9C4B48,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91B06F6DAD',
												'display_value' => '#9C4B48,#B06F6D,#555555,#777777',
											),
											'nicy_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF7F1,template_color:#E5A452,template_ftcolor:#E5A452,template_fthovercolor:#555555,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DF8D27,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E5A452,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DF8D27,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF8D27,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#555555,#777777',
											),
											'nicy_wasabi'  => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F6F7F1,template_color:#93A564,template_ftcolor:#93A564,template_fthovercolor:#555555,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#777777,firstletter_contentcolor:#777777,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93A564,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#777777',
											),
										),
										'offer_blog'       => array(
											'offer_blog_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#0E7699,template_fthovercolor:#333333,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#333333,#555555',
											),
											'offer_blog_earls_green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#CEBF59,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#CEBF59',
												'display_value' => '#C2AF2F,#CEBF59,#333333,#555555',
											),
											'offer_blog_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#974772,template_titlecolor:#7D194F,related_title_color:#7D194F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#7D194F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#974772,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#7D194F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7D194F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#974772',
												'display_value' => '#7D194F,#974772,#333333,#555555',
											),
											'offer_blog_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#333333,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#67704E',
												'display_value' => '#414C22,#67704E,#333333,#555555',
											),
											'offer_blog_west-side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF6F1,template_ftcolor:#333333,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E4802A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E99955,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E4802A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E4802A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#333333,#555555',
											),
											'offer_blog_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F5F7FB,template_ftcolor:#333333,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#333333,#555555',
											),
										),
										'overlay_horizontal' => array(
											'overlay_horizontal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#000000,template_ftcolor:#e2112f,template_fthovercolor:#ffffff,template_titlecolor:#ffffff,related_title_color:#ffffff,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#000000,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#e2112f,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#000000,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#000000,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#e2112f',
												'display_value' => '#000000,#e2112f,#ffffff,#333333',
											),
											'overlay_horizontal_persian_red' => array(
												'preset_name' => esc_html__( 'Persian Red', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#CC3333,template_ftcolor:#33cccc,template_fthovercolor:#ffffff,template_titlecolor:#3333cc,related_title_color:#ffffff,template_contentcolor:#ffffff,firstletter_contentcolor:#ffffff,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#33cccc,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3333cc,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#33cccc,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#33cccc,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3333cc',
												'display_value' => '#CC3333,#33cccc,#3333cc,#ffffff',
											),
											'overlay_horizontal_dark_goldenrod' => array(
												'preset_name' => esc_html__( 'Dark Goldenrod', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#b8860b,template_ftcolor:#0bb886,template_fthovercolor:#94b80b,template_titlecolor:#0b3db8,related_title_color:#0b3db8,template_contentcolor:#b8300b,firstletter_contentcolor:#b8300b,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#b8860b,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#b8300b,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#b8860b,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#b8860b,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#b8300b',
												'display_value' => '#b8860b,#0bb886,#0b3db8,#b8300b',
											),
											'overlay_horizontal_deep_cerise' => array(
												'preset_name' => esc_html__( 'Deep Cerise', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#da3287,template_ftcolor:#87da32,template_fthovercolor:#3287da,template_titlecolor:#3287da,related_title_color:#3287da,template_contentcolor:#32da85,firstletter_contentcolor:#32da85,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#da3287,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#32da85,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#da3287,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#da3287,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#32da85',
												'display_value' => '#da3287,#87da32,#3287da,#32da85',
											),
											'overlay_horizontal_rust' => array(
												'preset_name' => esc_html__( 'Rust', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#a55d35,template_ftcolor:#35a55d,template_fthovercolor:#5d35a5,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#ffffff,firstletter_contentcolor:#ffffff,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#a55d35,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#333333,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#a55d35,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#a55d35,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#333333',
												'display_value' => '#a55d35,#35a55d,#333333,#ffffff',
											),
											'overlay_horizontal_blue' => array(
												'preset_name' => esc_html__( 'Chetwode Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#666fb4,template_ftcolor:#6fb466,template_fthovercolor:#ffffff,template_titlecolor:#b4ab66,related_title_color:#b4ab66,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#666fb4,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6fb466,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#666fb4,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#666fb4,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6fb466',
												'display_value' => '#666fb4,#6fb466,#b4ab66,#333333',
											),
										),
										'pretty'           => array(
											'pretty_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#ff93a3,template_ftcolor:#999999,template_fthovercolor:#859f88,template_titlecolor:#859f88,related_title_color:#859f88,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#859f88,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#999999,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#859f88,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#859f88,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999',
												'display_value' => '#ff93a3,#859f88,#555555,#999999',
											),
											'pretty_sky_blue' => array(
												'preset_name' => esc_html__( 'Sky Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#DAEBFF,template_ftcolor:#888888,template_fthovercolor:#00809D,template_titlecolor:#00809D,related_title_color:#00809D,template_contentcolor:#484848,firstletter_contentcolor:#484848,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#00809D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#484848,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#00809D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#00809D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#484848',
												'display_value' => '#DAEBFF,#00809D,#484848,#888888',
											),
											'pretty_lite_green' => array(
												'preset_name' => esc_html__( 'Lite Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#C3F3DD,template_ftcolor:#888888,template_fthovercolor:#0ef58d,template_titlecolor:#0ef58d,related_title_color:#0ef58d,template_contentcolor:#484848,firstletter_contentcolor:#484848,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0ef58d,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#484848,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0ef58d,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0ef58d,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#484848',
												'display_value' => '#C3F3DD,#0ef58d,#484848,#888888',
											),
										),
										'region'           => array(
											'region_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#333333,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#333333,#555555',
											),
											'region_regal-blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#333333,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#14375F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#435F7F,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#14375F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#14375F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#333333,#555555',
											),
											'region_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#333333,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E81B3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#ED4961,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E81B3A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E81B3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#ED4961',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
											'region_lemon_ginger' => array(
												'preset_name' => esc_html__( 'Lemon Ginger', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#333333,template_fthovercolor:#A39D5A,template_titlecolor:#8C8431,related_title_color:#8C8431,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C8431,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A39D5A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C8431,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C8431,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A39D5A',
												'display_value' => '#8C8431,#A39D5A,#333333,#555555',
											),
											'region_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E663C,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E8563,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E663C,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E663C,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E8563',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'region_toddy' => array(
												'preset_name' => esc_html__( 'Toddy', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#333333,template_fthovercolor:#BE9055,template_titlecolor:#AE742A,related_title_color:#AE742A,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#AE742A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#BE9055,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#AE742A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#AE742A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#BE9055',
												'display_value' => '#AE742A,#BE9055,#333333,#555555',
											),
										),
										'roctangle'        => array(
											'roctangle_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#f18293,template_ftcolor:#666666,template_fthovercolor:#444444,template_titlecolor:#222222,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#f18293,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#f18293,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#f18293,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#f18293,#222222,#444444,#666666',
											),
											'roctangle_sky_blue' => array(
												'preset_name' => esc_html__( 'Sky Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#92E2FD,template_ftcolor:#666666,template_fthovercolor:#444444,template_titlecolor:#222222,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#92E2FD,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#92E2FD,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#92E2FD,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#92E2FD,#222222,#444444,#666666',
											),
											'roctangle_lite_green' => array(
												'preset_name' => esc_html__( 'Lite Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#0ef58d,template_ftcolor:#666666,template_fthovercolor:#444444,template_titlecolor:#222222,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0ef58d,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0ef58d,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0ef58d,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0ef58d,#222222,#444444,#666666',
											),
										),
										'sharpen'          => array(
											'sharpen_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#f5ab35,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4c4c4c,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#f5ab35,#ffffff,#4c4c4c,#808080',
											),
											'sharpen_pink' => array(
												'preset_name' => esc_html__( 'Pink', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FEF5F8,template_ftcolor:#e21130,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#e21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4c4c4c,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#e21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#e21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#e21130,#FEF5F8,#4c4c4c,#808080',
											),
											'sharpen_solitude' => array(
												'preset_name' => esc_html__( 'Solitude', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FFF3E5,template_ftcolor:#FF8A00,template_fthovercolor:#4c4c4c,template_titlecolor:#4c4c4c,related_title_color:#4c4c4c,template_contentcolor:#808080,firstletter_contentcolor:#808080,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#FF8A00,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4c4c4c,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#FF8A00,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#FF8A00,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4c4c4c',
												'display_value' => '#FF8A00,#FFF3E5,#4c4c4c,#808080',
											),
										),
										'spektrum'         => array(
											'spektrum_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#0E7699,template_fthovercolor:#555555,template_titlecolor:#222222,related_title_color:#222222,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222222,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222222',
												'display_value' => '#0E7699,#222222,#555555,#888888',
											),
											'spektrum_crimson' => array(
												'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'spektrum_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,related_title_color:#414C22,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#414C22,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#414C22,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#414C22,#67704E,#555555,#888888',
											),
											'spektrum_west_side' => array(
												'preset_name' => esc_html__( 'West Side', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#E99955,template_titlecolor:#E4802A,related_title_color:#E4802A,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E4802A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E99955,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E4802A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E4802A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E99955',
												'display_value' => '#E4802A,#E99955,#555555,#888888',
											),
											'spektrum_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93A564,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#888888',
											),
											'spektrum_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#888888',
											),
										),
										'story'            => array(
											'story_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#4458c9,template_color:#b00dd8,template_alternative_color:#b00dd8,story_startup_border_color:#ffffff,story_startup_background:#85e71c,story_startup_text_color:#333333,story_ending_background:#ade175,story_ending_text_color:#333333,template_ftcolor:#4458c9,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,related_title_color:#707070,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#4458c9,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#333333,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#4458c9,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#4458c9,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#333333',
												'display_value' => '#4458c9,#b00dd8,#85e71c,#333333',
											),
											'story_goldenrod' => array(
												'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#daa520,template_color:#2055da,template_alternative_color:#d23582,story_startup_border_color:#da4820,story_startup_background:#da4820,story_startup_text_color:#ffffff,story_ending_background:#da4820,story_ending_text_color:#ffffff,template_ftcolor:#da4820,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,related_title_color:#707070,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#daa520,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#da4820,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#daa520,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#daa520,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#da4820',
												'display_value' => '#daa520,#2055da,#da4820,#333333',
											),
											'story_radical_red' => array(
												'preset_name' => esc_html__( 'Radical Red', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ff355e,template_color:#355eff,template_alternative_color:#f18547,story_startup_border_color:#5d8a99,story_startup_background:#5d8a99,story_startup_text_color:#ffffff,story_ending_background:#5d8a99,story_ending_text_color:#ffffff,template_ftcolor:#5d8a99,template_fthovercolor:#2b2b2b,template_titlecolor:#707070,related_title_color:#707070,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#ff355e,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#5d8a99,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#ff355e,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#ff355e,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#5d8a99',
												'display_value' => '#ff355e,#355eff,#5d8a99,#333333',
											),
										),
										'tagly'            => array(
											'tagly_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#d4c6a8,template_ftcolor:#b79a5e,template_fthovercolor:#d4c6a8,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#b79a5e,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#333333,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#b79a5e,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#b79a5e,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#333333',
												'display_value' => '#b79a5e,#d4c6a8,#333333,#555555',
											),
											'tagly_crimson' => array(
												'preset_name' => esc_html__( 'Crimson', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#E84159,template_ftcolor:#555555,template_fthovercolor:#E84159,template_titlecolor:#E21130,related_title_color:#E21130,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E21130,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E84159,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E21130,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E21130,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E84159',
												'display_value' => '#E21130,#E84159,#555555,#888888',
											),
											'tagly_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E91AD,template_ftcolor:#555555,template_fthovercolor:#3E91AD,template_titlecolor:#0E7699,related_title_color:#0E7699,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E7699,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E7699,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#0E7699,#3E91AD,#555555,#888888',
											),
											'tagly_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#93A564,template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#93A564,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#93A564',
												'display_value' => '#788F3D,#93A564,#555555,#888888',
											),
											'tagly_ce_soir' => array(
												'preset_name' => esc_html__( 'Ce Soir', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A381BB,template_ftcolor:#555555,template_fthovercolor:#A381BB,template_titlecolor:#8C62AA,related_title_color:#8C62AA,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8C62AA,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#A381BB,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8C62AA,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8C62AA,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#A381BB',
												'display_value' => '#8C62AA,#A381BB,#555555,#888888',
											),
											'tagly_earls-green' => array(
												'preset_name' => esc_html__( 'Earls Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#CEBF59,template_ftcolor:#555555,template_fthovercolor:#CEBF59,template_titlecolor:#C2AF2F,related_title_color:#C2AF2F,template_contentcolor:#888888,firstletter_contentcolor:#888888,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C2AF2F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#888888,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C2AF2F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C2AF2F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#888888',
												'display_value' => '#C2AF2F,#CEBF59,#555555,#888888',
											),
										),
										'timeline'         => array(
											'timeline_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#0099CB,template_color:#0099CB,template_ftcolor:#0099CB,template_fthovercolor:#333333,template_titlecolor:#0099CB,related_title_color:#222222,template_titlebackcolor:#E6F5FA,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0099CB,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#005B79,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0099CB,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0099CB,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#005B79',
												'display_value' => '#0099CB,#005B79,#222222,#333333',
											),
											'timeline_venetian_red' => array(
												'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#414a54,template_color:#CC0001,template_ftcolor:#f15f74,template_fthovercolor:#444444,template_titlecolor:#f15f74,related_title_color:#f15f74,template_titlebackcolor:#ffffff,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#CC0001,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#CC0001,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#CC0001,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#CC0001,#444444,#f15f74,#333333',
											),
											'timeline_pink' => array(
												'preset_name' => esc_html__( 'Dark Sea Green', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#f15f74,template_color:#8FBC8F,template_ftcolor:#f15f74,template_fthovercolor:#444444,template_titlecolor:#475E47,related_title_color:#475E47,template_titlebackcolor:#F6F8F5,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8FBC8F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#475E47,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8FBC8F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8FBC8F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#475E47',
												'display_value' => '#8FBC8F,#475E47,#f15f74,#333333',
											),
											'timeline_dark_orchid' => array(
												'preset_name' => esc_html__( 'Dark Orchid', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#9A33CC,template_color:#9A33CC,template_ftcolor:#CC9932,template_fthovercolor:#444444,template_titlecolor:#5B1E7A,related_title_color:#5B1E7A,template_titlebackcolor:#F5EAFA,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#9A33CC,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#5B1E7A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#9A33CC,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#9A33CC,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#5B1E7A',
												'display_value' => '#9A33CC,#5B1E7A,#CC9932,#333333',
											),
											'timeline_dark_orange' => array(
												'preset_name' => esc_html__( 'Dark Orange', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#FF8A00,template_color:#FF8A00,template_ftcolor:#0073FF,template_fthovercolor:#444444,template_titlecolor:#7F4600,related_title_color:#7F4600,template_titlebackcolor:#FFF3E5,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#FF8A00,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#7F4600,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#FF8A00,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#FF8A00,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#7F4600',
												'display_value' => '#FF8A00,#7F4600,#0073FF,#333333',
											),
											'timeline_venetian_red' => array(
												'preset_name' => esc_html__( 'Venetian Red', 'blog-designer-pro' ),
												'preset_value' => 'displaydate_backcolor:#CC0001,template_color:#C80815,template_ftcolor:#08C8BB,template_fthovercolor:#444444,template_titlecolor:#78040C,related_title_color:#78040C,template_titlebackcolor:#FAE4E6,template_contentcolor:#333333,firstletter_contentcolor:#333333,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#C80815,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#78040C,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#C80815,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#C80815,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#78040C',
												'display_value' => '#C80815,#78040C,#08C8BB,#333333',
											),
										),
										'winter'           => array(
											'winter_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#e7492f,template_ftcolor:#37aece,template_fthovercolor:#444444,template_titlecolor:#444444,related_title_color:#444444,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#e7492f,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#37aece,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#e7492f,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#e7492f,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#37aece',
												'display_value' => '#e7492f,#37aece,#444444,#555555',
											),
											'winter_wasabi' => array(
												'preset_name' => esc_html__( 'Wasabi', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#93A564,template_ftcolor:#555555,template_fthovercolor:#93A564,template_titlecolor:#788F3D,related_title_color:#788F3D,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#788F3D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#788F3D,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#788F3D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#788F3D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#788F3D',
												'display_value' => '#788F3D,#788F3D,#555555,#999999',
											),
											'winter_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#8BA3CE,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,related_title_color:#6E8CC2,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6E8CC2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#8BA3CE,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6E8CC2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#8BA3CE',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#999999',
											),
											'winter_regal_blue' => array(
												'preset_name' => esc_html__( 'Regal Blue', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#435F7F,template_ftcolor:#555555,template_fthovercolor:#435F7F,template_titlecolor:#14375F,related_title_color:#14375F,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#14375F,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#435F7F,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#14375F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#14375F,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#435F7F',
												'display_value' => '#14375F,#435F7F,#555555,#999999',
											),
											'winter_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#E5A452,template_ftcolor:#555555,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DF8D27,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#E5A452,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DF8D27,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF8D27,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#E5A452',
												'display_value' => '#DF8D27,#E5A452,#555555,#999999',
											),
											'winter_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'winter_category_color:#EE4861,template_ftcolor:#555555,template_fthovercolor:#EE4861,template_titlecolor:#EA1A3A,related_title_color:#EA1A3A,template_contentcolor:#999999,firstletter_contentcolor:#999999,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#EA1A3A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#EE4861,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#EA1A3A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#EA1A3A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#EE4861',
												'display_value' => '#EA1A3A,#EE4861,#555555,#999999',
											),
										),
										'minimal'          => array(
											'minimal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#444444,template_fthovercolor:#e84c89,winter_category_color:#e84c89,template_titlecolor:#000000,related_title_color:#000000,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E7e84c89699,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#e84c89,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#e84c89,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#ffffff,#e84c89,#444444,#000000',
											),
											'minimal_caribbean' => array(
												'preset_name' => esc_html__( 'Caribbean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DFFBF0,template_ftcolor:#065B39,template_fthovercolor:#0EE08B,winter_category_color:#0EE08B,template_titlecolor:#043A25,related_title_color:#043A25,template_contentcolor:#065B39,firstletter_contentcolor:#065B39,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0EE08B,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#065B39,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0EE08B,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0EE08B,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#065B39',
												'display_value' => '#DFFBF0,#0EE08B,#065B39,#043A25',
											),
											'minimal_cerulean' => array(
												'preset_name' => esc_html__( 'Cerulean', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DFE8ED,template_ftcolor:#062230,template_fthovercolor:#0E5476,winter_category_color:#0E5476,template_titlecolor:#04161E,related_title_color:#04161E,template_contentcolor:#062230,firstletter_contentcolor:#062230,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0E5476,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#062230,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0E5476,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0E5476,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#062230',
												'display_value' => '#DFE8ED,#0E5476,#062230,#04161E',
											),
											'minimal_purple' => array(
												'preset_name' => esc_html__( 'Purple', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EEE9FA,template_ftcolor:#352859,template_fthovercolor:#8261DA,winter_category_color:#8261DA,template_titlecolor:#221A39,related_title_color:#221A39,template_contentcolor:#352859,firstletter_contentcolor:#352859,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#8261DA,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#352859,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8261DA,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#8261DA,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#352859',
												'display_value' => '#EEE9FA,#8261DA,#352859,#221A39',
											),
											'minimal_harvest' => array(
												'preset_name' => esc_html__( 'Harvest', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCF5EC,template_ftcolor:#5E4A2E,template_fthovercolor:#E6B571,winter_category_color:#E6B571,template_titlecolor:#3C2F1E,related_title_color:#3C2F1E,template_contentcolor:#5E4A2E,firstletter_contentcolor:#5E4A2E,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#E6B571,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#5E4A2E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#E6B571,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#E6B571,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#5E4A2E',
												'display_value' => '#FCF5EC,#E6B571,#5E4A2E,#3C2F1E',
											),
											'minimal_scarlet' => array(
												'preset_name' => esc_html__( 'Scarlet', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDE4E1,template_ftcolor:#62130A,template_fthovercolor:#F02F18,winter_category_color:#F02F18,template_titlecolor:#3E0C06,related_title_color:#3E0C06,template_contentcolor:#62130A,firstletter_contentcolor:#62130A,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#F02F18,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#62130A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#F02F18,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#F02F18,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#62130A',
												'display_value' => '#FDE4E1,#F02F18,#62130A,#3E0C06',
											),
										),
										'glamour'          => array(
											'glamour_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#444444,template_fthovercolor:#f5c034,template_titlecolor:#000000,related_title_color:#000000,template_contentcolor:#444444,firstletter_contentcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#f5c034,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#444444,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#f5c034,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#f5c034,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#ffffff,#f5c034,#444444,#000000',
											),
											'glamour_aqua' => array(
												'preset_name' => esc_html__( 'Aqua', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DDFFFC,template_ftcolor:#003531,template_fthovercolor:#00FFE9,template_titlecolor:#00221F,related_title_color:#00221F,template_contentcolor:#003531,firstletter_contentcolor:#003531,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#00FFE9,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#003531,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#00FFE9,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#00FFE9,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#003531',
												'display_value' => '#DDFFFC,#00FFE9,#003531,#00221F',
											),
											'glamour_jade' => array(
												'preset_name' => esc_html__( 'Jade', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DDF6ED,template_ftcolor:#00261A,template_fthovercolor:#00B97B,template_titlecolor:#001811,related_title_color:#001811,template_contentcolor:#00261A,firstletter_contentcolor:#00261A,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#00B97B,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#00261A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#00B97B,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#00B97B,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#00261A',
												'display_value' => '#DDF6ED,#00B97B,#00261A,#001811',
											),
											'glamour_malibu' => array(
												'preset_name' => esc_html__( 'Malibu', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#E9F6FC,template_ftcolor:#152831,template_fthovercolor:#60C1E8,template_titlecolor:#0E1A1F,related_title_color:#0E1A1F,template_contentcolor:#152831,firstletter_contentcolor:#152831,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#60C1E8,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#152831,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#60C1E8,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#60C1E8,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#152831',
												'display_value' => '#E9F6FC,#60C1E8,#152831,#0E1A1F',
											),
											'glamour_bourbon' => array(
												'preset_name' => esc_html__( 'Bourbon', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4ECE7,template_ftcolor:#2D1E13,template_fthovercolor:#AD6F49,template_titlecolor:#170F0A,related_title_color:#170F0A,template_contentcolor:#2D1E13,firstletter_contentcolor:#2D1E13,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#AD6F49,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#2D1E13,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#AD6F49,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#AD6F49,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#2D1E13',
												'display_value' => '#F4ECE7,#AD6F49,#2D1E13,#170F0A',
											),
											'glamour_raven' => array(
												'preset_name' => esc_html__( 'Raven', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EAECED,template_ftcolor:#222528,template_fthovercolor:#696F7A,template_titlecolor:#0E0F11,related_title_color:#0E0F11,template_contentcolor:#222528,firstletter_contentcolor:#222528,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#696F7A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#222528,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#696F7A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#696F7A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#222528',
												'display_value' => '#EAECED,#696F7A,#222528,#0E0F11',
											),
										),
										'famous'           => array(
											'famous_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#a5a5a5,template_fthovercolor:#f20075,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#f20075,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#f20075,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#f20075,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#f20075,#a5a5a5,#555555,#333333',
											),
											'famous_vivid_gamboge' => array(
												'preset_name' => esc_html__( 'Vivid Gamboge', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#a5a5a5,template_fthovercolor:#F99900,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#F99900,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#F99900,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#F99900,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#F99900,#a5a5a5,#555555,#333333',
											),
											'famous_timber_green' => array(
												'preset_name' => esc_html__( 'Timber Green', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#a5a5a5,template_fthovercolor:#3D3242,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#3D3242,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#3D3242,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#3D3242,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#3D3242,#a5a5a5,#555555,#333333',
											),
											'famous_jagger' => array(
												'preset_name' => esc_html__( 'Jagger', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#a5a5a5,template_fthovercolor:#374232,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#374232,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#374232,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#374232,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#374232,#a5a5a5,#555555,#333333',
											),
											'famous_barossa' => array(
												'preset_name' => esc_html__( 'Barossa', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#a5a5a5,template_fthovercolor:#423237,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#423237,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#423237,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#423237,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#423237,#a5a5a5,#555555,#333333',
											),
											'famous_blumine' => array(
												'preset_name' => esc_html__( 'Blumine', 'blog-designer-pro' ),
												'preset_value' => 'template_ftcolor:#a5a5a5,template_fthovercolor:#2A5B66,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#2A5B66,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#2A5B66,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#2A5B66,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#2A5B66,#a5a5a5,#555555,#333333',
											),
										),
										'fairy'            => array(
											'fairy_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#b6b6b6,template_fthovercolor:#0089bb,template_titlecolor:#000000,related_title_color:#000000,template_contentcolor:#5b5b5b,firstletter_contentcolor:#5b5b5b,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0089bb,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#5b5b5b,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0089bb,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0089bb,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#5b5b5b',
												'display_value' => '#ffffff ,#0089bb,#5b5b5b,#000000',
											),
											'fairy_gorse'  => array(
												'preset_name' => esc_html__( 'Gorse', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDFBE2,template_ftcolor:#7E7415,template_fthovercolor:#F7E229,template_titlecolor:#221E06,related_title_color:#221E06,template_contentcolor:#342F09,firstletter_contentcolor:#342F09,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#F7E229,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#342F09,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#F7E229,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#F7E229,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#342F09',
												'display_value' => '#FDFBE2 ,#F7E229,#342F09,#221E06',
											),
											'fairy_scampi' => array(
												'preset_name' => esc_html__( 'Scampi', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ECECF0,template_ftcolor:#393848,template_fthovercolor:#6F6E8D,template_titlecolor:#0F0E13,related_title_color:#0F0E13,template_contentcolor:#18171E,firstletter_contentcolor:#18171E,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6F6E8D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#18171E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6F6E8D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6F6E8D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#18171E',
												'display_value' => '#ECECF0 ,#6F6E8D,#18171E,#0F0E13',
											),
											'fairy_crusoe' => array(
												'preset_name' => esc_html__( 'Crusoe', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#DFEAE1,template_ftcolor:#083511,template_fthovercolor:#0F6620,template_titlecolor:#020E05,related_title_color:#020E05,template_contentcolor:#041B09,firstletter_contentcolor:#041B09,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#0F6620,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#041B09,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#0F6620,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#0F6620,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#041B09',
												'display_value' => '#DFEAE1 ,#0F6620,#041B09,#020E05',
											),
											'fairy_seagull' => array(
												'preset_name' => esc_html__( 'Seagull', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EDF4F8,template_ftcolor:#3E5866,template_fthovercolor:#7BADC8,template_titlecolor:#11171B,related_title_color:#11171B,template_contentcolor:#202D35,firstletter_contentcolor:#202D35,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#7BADC8,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#202D35,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#7BADC8,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#7BADC8,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#202D35',
												'display_value' => '#EDF4F8 ,#7BADC8,#202D35,#11171B',
											),
											'fairy_persimmon' => array(
												'preset_name' => esc_html__( 'Persimmon', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAE8DD,template_ftcolor:#722A05,template_fthovercolor:#722A05,template_titlecolor:#1E0B02,related_title_color:#1E0B02,template_contentcolor:#2E1202,firstletter_contentcolor:#2E1202,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DF5309,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#2E1202,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DF5309,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DF5309,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#2E1202',
												'display_value' => '#FAE8DD ,#DF5309,#2E1202,#1E0B02',
											),
										),
										'clicky'           => array(
											'clicky_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#a7a7a7,template_fthovercolor:#586f8c,winter_category_color:#586f8c,template_titlecolor:#586f8c,related_title_color:#586f8c,template_contentcolor:#686868,firstletter_contentcolor:#686868,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#586f8c,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#686868,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#686868,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#686868,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#586f8c',
												'display_value' => '#ffffff ,#a7a7a7,#686868,#586f8c',
											),
											'clicky_portage' => array(
												'preset_name' => esc_html__( 'Portage', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#C1C5ED,template_fthovercolor:#2A2B3C,winter_category_color:#2A2B3C,template_titlecolor:#2A2B3C,related_title_color:#2A2B3C,template_contentcolor:#9DA5E4,firstletter_contentcolor:#9DA5E4,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#9DA5E4,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#2A2B3C,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#9DA5E4,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#9DA5E4,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#2A2B3C',
												'display_value' => '#ffffff ,#C1C5ED,#9DA5E4,#2A2B3C',
											),
											'clicky_emerald' => array(
												'preset_name' => esc_html__( 'Emerald', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#E4FCEA,template_ftcolor:#229541,template_fthovercolor:#0B3116,winter_category_color:#0B3116,template_titlecolor:#0B3116,related_title_color:#0B3116,template_contentcolor:#165F2A,firstletter_contentcolor:#165F2A,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#165F2A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#0B3116,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#165F2A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#165F2A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#0B3116',
												'display_value' => '#E4FCEA ,#229541,#165F2A,#0B3116',
											),
										),
										'cover'            => array(
											'cover_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#b6b6b6,template_fthovercolor:#ff6063,template_titlecolor:#272727,related_title_color:#272727,template_contentcolor:#696969,firstletter_contentcolor:#696969,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#696969,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#272727,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#696969,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#696969,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#272727',
												'display_value' => '#ffffff ,#b6b6b6,#696969,#272727',
											),
											'cover_rust'  => array(
												'preset_name' => esc_html__( 'Rust', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCF6F5,template_ftcolor:#D1856D,template_fthovercolor:#B6401A,template_titlecolor:#301107,related_title_color:#301107,template_contentcolor:#4B1A0B,firstletter_contentcolor:#4B1A0B,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#D1856D,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4B1A0B,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#D1856D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#D1856D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4B1A0B',
												'display_value' => '#FCF6F5 ,#D1856D,#4B1A0B,#301107',
											),
											'cover_mulberry' => array(
												'preset_name' => esc_html__( 'Mulberry', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCF6FA,template_ftcolor:#DD92C7,template_fthovercolor:#CA55A7,template_titlecolor:#35162C,related_title_color:#35162C,template_contentcolor:#532245,firstletter_contentcolor:#532245,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#532245,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#DD92C7,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#532245,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#532245,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#DD92C7',
												'display_value' => '#FCF6FA ,#DD92C7,#532245,#35162C',
											),
											'cover_green' => array(
												'preset_name' => esc_html__( 'Green', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ECF2E9,template_ftcolor:#4E8835,template_fthovercolor:#226A03,template_titlecolor:#0B2202,related_title_color:#0B2202,template_contentcolor:#123602,firstletter_contentcolor:#123602,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#123602,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#4E8835,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#123602,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#123602,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#4E8835',
												'display_value' => '#ECF2E9 ,#4E8835,#123602,#0B2202',
											),
											'cover_curious' => array(
												'preset_name' => esc_html__( 'Curious', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4F9FD,template_ftcolor:#81BCE0,template_fthovercolor:#3996CE,template_titlecolor:#0F2836,related_title_color:#0F2836,template_contentcolor:#183E55,firstletter_contentcolor:#183E55,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#81BCE0,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#183E55,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#81BCE0,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#81BCE0,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#183E55',
												'display_value' => '#F4F9FD ,#81BCE0,#183E55,#0F2836',
											),
											'cover_highball' => array(
												'preset_name' => esc_html__( 'Highball', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAFAF7,template_ftcolor:#969547,template_fthovercolor:#605F2E,template_titlecolor:#282713,related_title_color:#282713,template_contentcolor:#3E3D1E,firstletter_contentcolor:#3E3D1E,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#3E3D1E,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#3E91AD,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#3E3D1E,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#3E3D1E,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#3E91AD',
												'display_value' => '#FAFAF7 ,#969547,#3E3D1E,#282713',
											),
										),
										'steps'            => array(
											'steps_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_color:#cbcbcb,template_ftcolor:#b7b7b7,template_fthovercolor:#f8c04e,template_titlecolor:#363636,related_title_color:#363636,template_contentcolor:#666666,firstletter_contentcolor:#666666,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#f8c04e,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#363636,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#f8c04e,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#f8c04e,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#363636',
												'display_value' => '#ffffff ,#cbcbcb,#f8c04e,#363636',
											),
											'steps_russett' => array(
												'preset_name' => esc_html__( 'Russett', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F8F6F6,template_color:#DDD7D4,template_ftcolor:#AE9D96,template_fthovercolor:#81675B,template_titlecolor:#221B18,related_title_color:#221B18,template_contentcolor:#42352E,firstletter_contentcolor:#42352E,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#81675B,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#221B18,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#81675B,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#81675B,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#221B18',
												'display_value' => '#F8F6F6 ,#DDD7D4,#81675B,#221B18',
											),
											'steps_neon_blue' => array(
												'preset_name' => esc_html__( 'Neon Blue', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F9F8FD,template_color:#D9D7FD,template_ftcolor:#8B84F7,template_fthovercolor:#4A3EF2,template_titlecolor:#0F0E32,related_title_color:#0F0E32,template_contentcolor:#1E1A63,firstletter_contentcolor:#42352E,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#4A3EF2,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#0F0E32,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#4A3EF2,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#4A3EF2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#0F0E32',
												'display_value' => '#F9F8FD ,#D9D7FD,#4A3EF2,#0F0E32',
											),
										),
										'miracle'          => array(
											'miracle_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#62bf7c,template_fthovercolor:#686868,template_titlecolor:#353535,related_title_color:#353535,template_contentcolor:#252525,firstletter_contentcolor:#252525,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#62bf7c,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#353535,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#62bf7c,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#62bf7c,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#353535',
												'display_value' => '#ffffff ,#62bf7c,#353535,#252525',
											),
											'miracle_lochmara' => array(
												'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F7FAFC,template_ftcolor:#227CAD,template_fthovercolor:#227CAD,template_titlecolor:#051117,related_title_color:#051117,template_contentcolor:#09202D,firstletter_contentcolor:#09202D,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#227CAD,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#051117,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#227CAD,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#227CAD,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#051117',
												'display_value' => '#F7FAFC ,#227CAD,#051117,#09202D',
											),
											'miracle_burgundy' => array(
												'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAF6F7,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#0E0105,related_title_color:#0E0105,template_contentcolor:#1C0109,firstletter_contentcolor:#1C0109,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#6C0124,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#0E0105,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#6C0124,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6C0124,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#0E0105',
												'display_value' => '#FAF6F7 ,#6C0124,#0E0105,#1C0109',
											),
											'miracle_hillary' => array(
												'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCFBFA,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#161610,related_title_color:#161610,template_contentcolor:#2B2A1F,firstletter_contentcolor:#2B2A1F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#A49E77,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#161610,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#A49E77,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#A49E77,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#161610',
												'display_value' => '#FCFBFA ,#A49E77,#161610,#2B2A1F',
											),
											'miracle_amaranth' => array(
												'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF9FA,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#1E070A,related_title_color:#1E070A,template_contentcolor:#2E0B0F,firstletter_contentcolor:#2E0B0F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#DE364A,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#1E070A,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#DE364A,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#DE364A,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#1E070A',
												'display_value' => '#FDF9FA ,#DE364A,#1E070A,#2E0B0F',
											),
											'miracle_manatee' => array(
												'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCFCFD,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#151516,related_title_color:#151516,template_contentcolor:#28282C,firstletter_contentcolor:#28282C,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#9699A7,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#151516,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#9699A7,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#9699A7,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#151516',
												'display_value' => '#FCFCFD ,#9699A7,#151516,#28282C',
											),
										),
										'foodbox'          => array(
											'foodbox_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#6e3d30,template_bgcolor:#f7f4ef,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#312725,template_titlehovercolor:#000000,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#555555,bdp_star_rating_bg_color:#312725,bdp_pricetextcolor:#555555,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#6e3d30,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#312725,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#312725,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#6e3d30',
												'display_value' => '#312725,#000000,#6e3d30,#444444',
											),
											'foodbox_radical' => array(
												'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#4F171C,template_bgcolor:#FDE7E9,template_ftcolor:#4F171C,template_fthovercolor:#7C242C,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#FDE7E9,template_contentcolor:#7C242C,bdp_sale_tagbgcolor:#4F171C,bdp_sale_tagtextcolor:#FDE7E9,bdp_star_rating_color:#7C242C,bdp_star_rating_bg_color:#F34656,bdp_pricetextcolor:#FDE7E9,bdp_wishlist_textcolor:#FDE7E9,bdp_wishlist_backgroundcolor:#4F171C,bdp_wishlist_text_hover_color:#FDE7E9,bdp_wishlist_hover_backgroundcolor:#7C242C,bdp_addtocart_textcolor:#FDE7E9,bdp_addtocart_backgroundcolor:#F34656,bdp_addtocart_text_hover_color:#FDE7E9,bdp_addtocart_hover_backgroundcolor:#7C242C',
												'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
											),
											'Foodbox_tangerine' => array(
												'preset_name' => esc_html__( 'Tangerine', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#efb828,template_bgcolor:#fdf7e7,template_ftcolor:#171101,template_fthovercolor:#473505,template_titlecolor:#473505,template_titlehovercolor:#efb828,template_titlebackcolor:#fdf7e7,template_contentcolor:#171101,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#473505,bdp_pricetextcolor:#171101,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#888888,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#473505,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#473505',
												'display_value' => '#f8df9f,#efb828,#473505,#171101',
											),
											'foodbox_rich-gold' => array(
												'preset_name' => esc_html__( 'Rich Gold', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#A95624,template_bgcolor:#ffd1b6,template_ftcolor:#444444,template_fthovercolor:#A95624,template_titlecolor:#A95624,template_titlehovercolor:#BA7850,template_titlebackcolor:#ffd1b6,template_contentcolor:#444444,bdp_sale_tagbgcolor:#A95624,bdp_sale_tagtextcolor:#f1f1f1,bdp_star_rating_color:#444444,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#BA7850,bdp_wishlist_textcolor:#f1f1f1,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#f1f1f1,bdp_wishlist_hover_backgroundcolor:#BA7850,bdp_addtocart_textcolor:#f1f1f1,bdp_addtocart_backgroundcolor:#A95624,bdp_addtocart_text_hover_color:#f1f1f1,bdp_addtocart_hover_backgroundcolor:#444444',
												'display_value' => '#BA7850,#A95624,#555555,#444444',
											),
										),
										'neaty_block'      => array(
											'neaty_block_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#c4e2f7,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#444444,template_contentcolor:#444444,bdp_sale_tagbgcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#444444,bdp_pricetextcolor:#999999,bdp_wishlist_textcolor:#444444,bdp_wishlist_backgroundcolor:#000000,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#444444,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#444444,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000000',
												'display_value' => '#444444,#000000,#444444,#000000',
											),
											'neaty_block_roof_terracotta' => array(
												'preset_name' => esc_html__( 'Roof Terracotta', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#B06F6D,template_bgcolor:#ffffff,template_bghovercolor:#F1E7E7,template_ftcolor:#555555,template_fthovercolor:#B06F6D,template_titlecolor:#9C4B48,template_titlehovercolor:#B06F6D,template_contentcolor:#999999,bdp_sale_tagbgcolor:#B06F6D,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#B06F6D,bdp_pricetextcolor:#999999,bdp_wishlist_textcolor:#B06F6D,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#B06F6D,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#B06F6D,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#555555',
												'display_value' => '#9C4B48,#B06F6D,#555555,#999999',
											),
											'neaty_block_bronzetone' => array(
												'preset_name' => esc_html__( 'Bronzetone', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#ffffff,template_ftcolor:#555555,template_fthovercolor:#67704E,template_titlecolor:#414C22,template_titlehovercolor:#67704E,template_titlebackcolor:#ffffff,template_contentcolor:#999999,bdp_sale_tagbgcolor:#414C22,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#67704E,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#67704E,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#555555,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#414C22,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#999999',
												'display_value' => '#414C22,#67704E,#555555,#999999',
											),
											'offer_yonder' => array(
												'preset_name' => esc_html__( 'Yonder', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F3F5FA,template_ftcolor:#555555,template_fthovercolor:#8BA3CE,template_titlecolor:#6E8CC2,template_titlehovercolor:#8BA3CE,template_titlebackcolor:#F3F5FA,template_contentcolor:#666666,bdp_sale_tagbgcolor:#6E8CC2,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#666666,bdp_star_rating_bg_color:#555555,bdp_pricetextcolor:#6E8CC2,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#555555,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#8BA3CE,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#6E8CC2,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#666666',
												'display_value' => '#6E8CC2,#8BA3CE,#555555,#666666',
											),
										),
										'wise_block'       => array(
											'wise_block_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#444444,template_bgcolor:#c4e2f7,template_ftcolor:#444444,template_fthovercolor:#000000,template_titlecolor:#444444,template_titlehovercolor:#000000,template_contentcolor:#444444,bdp_sale_tagbgcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#444444,bdp_pricetextcolor:#999999,bdp_wishlist_textcolor:#444444,bdp_wishlist_backgroundcolor:#000000,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#444444,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#444444,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000000',
												'display_value' => '#444444,#000000,#444444,#000000',
											),
											'wise_block_goldenrod' => array(
												'preset_name' => esc_html__( 'Goldenrod', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F4EDDD,template_ftcolor:#3A2A02,template_fthovercolor:#5B4204,template_titlecolor:#5B4204,template_titlehovercolor:#3A2A02,template_titlebackcolor:#ffffff,template_contentcolor:#5B4204,bdp_sale_tagbgcolor:#3A2A02,bdp_sale_tagtextcolor:#F4EDDD,bdp_star_rating_color:#5B4204,bdp_star_rating_bg_color:#B28007,bdp_pricetextcolor:#F4EDDD,bdp_wishlist_textcolor:#F4EDDD,bdp_wishlist_backgroundcolor:#3A2A02,bdp_wishlist_text_hover_color:#F4EDDD, bdp_wishlist_hover_backgroundcolor:#5B4204,bdp_addtocart_textcolor:#F4EDDD,bdp_addtocart_backgroundcolor:#B28007,bdp_addtocart_text_hover_color:#F4EDDD,bdp_addtocart_hover_backgroundcolor:#5B4204',
												'display_value' => '#F4EDDD,#B28007,#5B4204,#3A2A02',
											),
											'wise_block_salem' => array(
												'preset_name' => esc_html__( 'Salem', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#e8f3ed,template_bgcolor:#e8f3ed,template_ftcolor:#198c4b,template_fthovercolor:#666666,template_titlecolor:#333333,template_titlehovercolor:#198c4b,template_titlebackcolor:,template_contentcolor:#666666,bdp_sale_tagbgcolor:#198c4b,bdp_sale_tagtextcolor:#e8f3ed,bdp_star_rating_color:#666666,bdp_star_rating_bg_color:#198c4b,bdp_pricetextcolor:#333333,bdp_wishlist_textcolor:#e8f3ed,bdp_wishlist_backgroundcolor:#198c4b,bdp_wishlist_text_hover_color:#e8f3ed,bdp_wishlist_hover_backgroundcolor:#666666,bdp_addtocart_textcolor:#e8f3ed,bdp_addtocart_backgroundcolor:#333333,bdp_addtocart_text_hover_color:#e8f3ed,bdp_addtocart_hover_backgroundcolor:#198c4b',
												'display_value' => '#e8f3ed,#198c4b,#333333,#666666',
											),
											'wise_block_prussian' => array(
												'preset_name' => esc_html__( 'Prussian', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#99a8ba,template_bgcolor:#e5e9ed,template_ftcolor:#000308,template_fthovercolor:#000b19,template_titlecolor:#193b65,template_titlehovercolor:#000b19,template_titlebackcolor:,template_contentcolor:#000308,bdp_sale_tagbgcolor:#171101,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#171101,bdp_star_rating_bg_color:#193b65,bdp_pricetextcolor:#000308,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#000b19,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#193b65,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#171101,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000308',
												'display_value' => '#99a8ba,#193b65,#000b19,#000308',
											),
											'wise_block_pompadour' => array(
												'preset_name' => esc_html__( 'Pompadour', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EAE1E7,template_ftcolor:#35122A,template_fthovercolor:#220B1B,template_titlecolor:#35122A,template_titlehovercolor:#662451,template_contentcolor:#EAE1E7,template_contentcolor:#35122A,bdp_sale_tagbgcolor:#662451,bdp_sale_tagtextcolor:#EAE1E7,bdp_star_rating_color:#35122A,bdp_star_rating_bg_color:#220B1B,bdp_pricetextcolor:#35122A,bdp_wishlist_textcolor:#EAE1E7,bdp_wishlist_backgroundcolor:#35122A,bdp_wishlist_text_hover_color:#EAE1E7,bdp_wishlist_hover_backgroundcolor:#662451,bdp_addtocart_textcolor:#EAE1E7,bdp_addtocart_backgroundcolor:#662451,bdp_addtocart_text_hover_color:#EAE1E7,bdp_addtocart_hover_backgroundcolor:#220B1B',
												'display_value' => '#EAE1E7,#662451,#220B1B,#35122A',
											),
											'wise_block_go_ben' => array(
												'preset_name' => esc_html__( 'Go Ben', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#EDECE7,template_ftcolor:#3E3B25,template_fthovercolor:#282618,template_titlecolor:#3E3B25,template_titlehovercolor:#787449,template_contentcolor:#EDECE7,template_contentcolor:#3E3B25,bdp_sale_tagbgcolor:#787449,bdp_sale_tagtextcolor:#EDECE7,bdp_star_rating_color:#3E3B25,bdp_star_rating_bg_color:#282618,bdp_pricetextcolor:#3E3B25,bdp_wishlist_textcolor:#EDECE7,bdp_wishlist_backgroundcolor:#3E3B25,bdp_wishlist_text_hover_color:#EDECE7,bdp_wishlist_hover_backgroundcolor:#787449,bdp_addtocart_textcolor:#EDECE7,bdp_addtocart_backgroundcolor:#787449,bdp_addtocart_text_hover_color:#EDECE7,bdp_addtocart_hover_backgroundcolor:#282618',
												'display_value' => '#EDECE7,#787449,#282618,#3E3B25',
											),
										),
										'soft_block'       => array(
											'soft_block_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#444444,template_bgcolor:#f7f4ef,template_ftcolor:#444444,template_fthovercolor:#444444,template_titlecolor:#444444,template_titlehovercolor:#ece0e0,template_contentcolor:#444444,template_bgcolor1:#4fbfc1,template_bgcolor2:#508FC4,template_bgcolor3:#F47882,template_bgcolor4:#F0CF80,template_bgcolor5:#75C77D,template_bgcolor6:#76ABD5,bdp_sale_tagbgcolor:#444444,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#999999,bdp_star_rating_bg_color:#444444,bdp_pricetextcolor:#999999,bdp_wishlist_textcolor:#444444,bdp_wishlist_backgroundcolor:#000000,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#444444,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#444444,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#000000',
												'display_value' => '#444444,#000000,#ece0e0,#ffffff',
											),
											'soft_block_lochmara' => array(
												'preset_name' => esc_html__( 'Lochmara', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#F7FAFC,template_ftcolor:#227CAD,template_fthovercolor:#0E3246,template_titlecolor:#F7FAFC,template_titlehovercolor:#227CAD,template_contentcolor:#F7FAFC,bdp_sale_tagbgcolor:#227CAD,bdp_sale_tagtextcolor:#F7FAFC,bdp_star_rating_color:#09202D,bdp_star_rating_bg_color:#051117,bdp_pricetextcolor:#051117,bdp_wishlist_textcolor:#F7FAFC,bdp_wishlist_backgroundcolor:#051117,bdp_wishlist_text_hover_color:#F7FAFC,bdp_wishlist_hover_backgroundcolor:#227CAD,bdp_addtocart_textcolor:#F7FAFC,bdp_addtocart_backgroundcolor:#227CAD,bdp_addtocart_text_hover_color:#F7FAFC,bdp_addtocart_hover_backgroundcolor:#051117',
												'display_value' => '#F7FAFC,#227CAD,#051117,#09202D',
											),
											'soft_block_burgundy' => array(
												'preset_name' => esc_html__( 'Burgundy', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FAF6F7,template_ftcolor:#6C0124,template_fthovercolor:#2C010E,template_titlecolor:#FAF6F7,template_titlehovercolor:#6C0124,template_contentcolor:#FAF6F7,bdp_sale_tagbgcolor:#6C0124,bdp_sale_tagtextcolor:#FAF6F7,bdp_star_rating_color:#1C0109,bdp_star_rating_bg_color:#0E0105,bdp_pricetextcolor:#0E0105,bdp_wishlist_textcolor:#FAF6F7,bdp_wishlist_backgroundcolor:#0E0105,bdp_wishlist_text_hover_color:#FAF6F7,bdp_wishlist_hover_backgroundcolor:#6C0124,bdp_addtocart_textcolor:#FAF6F7,bdp_addtocart_backgroundcolor:#6C0124,bdp_addtocart_text_hover_color:#FAF6F7,bdp_addtocart_hover_backgroundcolor:#0E0105',
												'display_value' => '#FAF6F7,#6C0124,#0E0105,#1C0109',
											),
											'soft_block_hillary' => array(
												'preset_name' => esc_html__( 'Hillary', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCFBFA,template_ftcolor:#A49E77,template_fthovercolor:#434131,template_titlecolor:#FCFBFA,template_titlehovercolor:#A49E77,template_contentcolor:#FCFBFA,bdp_sale_tagbgcolor:#A49E77,bdp_sale_tagtextcolor:#FCFBFA,bdp_star_rating_color:#2B2A1F,bdp_star_rating_bg_color:#161610,bdp_pricetextcolor:#161610,bdp_wishlist_textcolor:#FCFBFA,bdp_wishlist_backgroundcolor:#161610,bdp_wishlist_text_hover_color:#FCFBFA,bdp_wishlist_hover_backgroundcolor:#A49E77,bdp_addtocart_textcolor:#FCFBFA,bdp_addtocart_backgroundcolor:#A49E77,bdp_addtocart_text_hover_color:#FCFBFA,bdp_addtocart_hover_backgroundcolor:#161610',
												'display_value' => '#FCFBFA,#A49E77,#161610,#2B2A1F',
											),
											'soft_block_amaranth' => array(
												'preset_name' => esc_html__( 'Amaranth', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FDF9FA,template_ftcolor:#DE364A,template_fthovercolor:#491218,template_titlecolor:#FDF9FA,template_titlehovercolor:#DE364A,template_contentcolor:#FDF9FA,bdp_sale_tagbgcolor:#DE364A,bdp_sale_tagtextcolor:#FDF9FA,bdp_star_rating_color:#2E0B0F,bdp_star_rating_bg_color:#1E070A,bdp_pricetextcolor:#1E070A,bdp_wishlist_textcolor:#FDF9FA,bdp_wishlist_backgroundcolor:#1E070A,bdp_wishlist_text_hover_color:#FDF9FA,bdp_wishlist_hover_backgroundcolor:#DE364A,bdp_addtocart_textcolor:#FDF9FA,bdp_addtocart_backgroundcolor:#DE364A,bdp_addtocart_text_hover_color:#FDF9FA,bdp_addtocart_hover_backgroundcolor:#1E070A',
												'display_value' => '#FDF9FA,#DE364A,#1E070A,#2E0B0F',
											),
											'soft_block_manatee' => array(
												'preset_name' => esc_html__( 'Manatee', 'blog-designer-pro' ),
												'preset_value' => 'template_bgcolor:#FCFCFD,template_ftcolor:#9699A7,template_fthovercolor:#3E3E45,template_titlecolor:#FCFCFD,template_titlehovercolor:#9699A7,template_titlebackcolor:#FCFCFD,template_contentcolor:#FCFCFD,bdp_sale_tagbgcolor:#DE364A,bdp_sale_tagtextcolor:#FCFCFD,bdp_star_rating_color:#9699A7,bdp_star_rating_bg_color:#151516,bdp_pricetextcolor:#151516,bdp_wishlist_textcolor:#FCFCFD,bdp_wishlist_backgroundcolor:#151516,bdp_wishlist_text_hover_color:#FCFCFD,bdp_wishlist_hover_backgroundcolor:#9699A7,bdp_addtocart_textcolor:#FCFCFD,bdp_addtocart_backgroundcolor:#9699A7,bdp_addtocart_text_hover_color:#FCFCFD,bdp_addtocart_hover_backgroundcolor:#151516',
												'display_value' => '#FCFCFD,#9699A7,#151516,#28282C',
											),
										),
										'schedule'         => array(
											'schedule_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#e21130,template_bgcolor:#ffffff,template_ftcolor:#a5a5a5,template_fthovercolor:#123123,template_titlecolor:#444444,,template_titlebackcolor:#ffffff,template_titlehovercolor:#123123,,template_contentcolor:#333333,winter_category_color:#a5a5a5',
												'display_value' => '#444444,#000000,#333333,#ffffff',
											),
											'schedule_radical' => array(
												'preset_name' => esc_html__( 'Radical', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#7C242C,template_bgcolor:#FDE7E9,template_ftcolor:#F9A1A9,template_fthovercolor:#7C242C,template_titlecolor:#F34656,template_titlehovercolor:#4F171C,template_titlebackcolor:#FDE7E9,template_contentcolor:#7C242C,bdp_sale_tagbgcolor:#4F171C,bdp_sale_tagtextcolor:#FDE7E9,bdp_star_rating_color:#7C242C,bdp_star_rating_bg_color:#F34656,bdp_pricetextcolor:#FDE7E9,bdp_wishlist_textcolor:#FDE7E9,bdp_wishlist_backgroundcolor:#4F171C,bdp_wishlist_text_hover_color:#FDE7E9,bdp_wishlist_hover_backgroundcolor:#7C242C,bdp_addtocart_textcolor:#FDE7E9,bdp_addtocart_backgroundcolor:#F34656,bdp_addtocart_text_hover_color:#FDE7E9,bdp_addtocart_hover_backgroundcolor:#7C242C,
                                                    winter_category_color:#7C242C',
												'display_value' => '#FDE7E9,#F34656,#7C242C,#4F171C',
											),
											'schedule_mariner' => array(
												'preset_name' => esc_html__( 'Mariner', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#465BAC,template_bgcolor:#EAEDF6,template_ftcolor:#465BAC,template_fthovercolor:#ffffff,template_titlecolor:#465BAC,template_titlehovercolor:#484848,template_titlebackcolor:#eaedf6,template_contentcolor:#7b7b7b,bdp_sale_tagbgcolor:#465BAC,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#465BAC,bdp_pricetextcolor:#484848,bdp_wishlist_textcolor:#ffffff,bdp_wishlist_backgroundcolor:#484848,bdp_wishlist_text_hover_color:#ffffff,bdp_wishlist_hover_backgroundcolor:#465BAC,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#465BAC,bdp_addtocart_text_hover_color:#ffffff,bdp_addtocart_hover_backgroundcolor:#484848,winter_category_color:#465BAC',
												'display_value' => '#465BAC,#EAEDF6,#465BAC,#484848',
											),
											'schedule_flamenco' => array(
												'preset_name' => esc_html__( 'Flamenco', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#683C6F,template_bgcolor:#FFF3ED,template_ftcolor:#683C6F,template_fthovercolor:#ffffff,template_titlecolor:#683C6F,template_titlehovercolor:#484848,template_titlebackcolor:#FFF3ED,template_contentcolor:#7b7b7b,,bdp_sale_tagbgcolor:#683C6F,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#683C6F,
                                                    bdp_wishlist_textcolor:#683C6F,bdp_wishlist_backgroundcolor:#FFF3ED,bdp_wishlist_text_hover_color:#FFF3ED,bdp_wishlist_hover_backgroundcolor:#683C6F,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#683C6F,bdp_addtocart_text_hover_color:#683C6F,bdp_addtocart_hover_backgroundcolor:#FFF3ED,
                                                    winter_category_color:#683C6F',
												'display_value' => '#683C6F,#FFF3ED,#683C6F,#484848',
											),
											'schedule_finch' => array(
												'preset_name' => esc_html__( 'Finch', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#75815B,template_bgcolor:#EFF0EB,template_ftcolor:#75815B,template_fthovercolor:#ffffff,template_titlecolor:#75815B,template_titlehovercolor:#484848,template_titlebackcolor:#EFF0EB,template_contentcolor:#7b7b7b,bdp_sale_tagbgcolor:#75815B,bdp_sale_tagtextcolor:#ffffff,bdp_star_rating_color:#7b7b7b,bdp_star_rating_bg_color:#75815B,
                                                    bdp_wishlist_textcolor:#75815B,bdp_wishlist_backgroundcolor:#EFF0EB,bdp_wishlist_text_hover_color:#EFF0EB,bdp_wishlist_hover_backgroundcolor:#75815B,bdp_addtocart_textcolor:#ffffff,bdp_addtocart_backgroundcolor:#75815B,bdp_addtocart_text_hover_color:#75815B,bdp_addtocart_hover_backgroundcolor:#EFF0EB,winter_category_color:#75815B',
												'display_value' => '#75815B,#EFF0EB,#75815B,#484848',
											),
										),
										'pedal'            => array(
											'pedal_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#04030c,template_fthovercolor:#555555,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#eeeeee,#d35400,#333333,#555555',
											),
											'pedal_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#A2855B,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#eeeeee,#A2855B,#333333,#555555',
											),
											'pedal_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'pedal_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#333333,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
											'pedal_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#333333,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'pedal_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#333333,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
										),
										'quci'             => array(
											'quci_default' => array(
												'preset_name' => esc_html__( 'Default', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#974772,template_ftcolor:#04030c,template_fthovercolor:#974772,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#974772,#d35400,#333333,#555555',
											),
											'quci_mckenzie' => array(
												'preset_name' => esc_html__( 'McKenzie', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#A2855B,template_fthovercolor:#333333,template_titlecolor:#333333,related_title_color:#333333,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#eeeeee,#A2855B,#333333,#555555',
											),
											'quci_fun_green' => array(
												'preset_name' => esc_html__( 'Fun Green', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#3E8563,template_ftcolor:#333333,template_fthovercolor:#3E8563,template_titlecolor:#0E663C,related_title_color:#0E663C,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#0E663C,#3E8563,#333333,#555555',
											),
											'quci_blackberry' => array(
												'preset_name' => esc_html__( 'Blackberry', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#333333,template_fthovercolor:#6D4657,template_titlecolor:#49182D,related_title_color:#49182D,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#49182D,#6D4657,#333333,#555555',
											),
											'quci_buttercup' => array(
												'preset_name' => esc_html__( 'Buttercup', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#333333,template_fthovercolor:#E5A452,template_titlecolor:#DF8D27,related_title_color:#DF8D27,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#DF8D27,#E5A452,#333333,#555555',
											),
											'quci_alizarin' => array(
												'preset_name' => esc_html__( 'Alizarin', 'blog-designer-pro' ),
												'preset_value' => 'template_color:#eeeeee,template_ftcolor:#333333,template_fthovercolor:#ED4961,template_titlecolor:#E81B3A,related_title_color:#E81B3A,template_contentcolor:#555555,firstletter_contentcolor:#555555',
												'display_value' => '#E81B3A,#ED4961,#333333,#555555',
											),
										),

									);
									foreach ( $template_color_preset as $key => $single_template ) {
										?>
										<div class="controls_preset <?php echo esc_attr( $key ); ?>" style="display:none;">
											<?php foreach ( $single_template as $name => $value ) { ?>
												<div class="color-option preset
												<?php
												if ( $bdp_color_preset == $name ) { //phpcs:ignore
													echo ' color_preset_selected';
												}
												?>
												" data-value="<?php echo esc_attr( $value['preset_value'] ); ?>">
													<label>
														<input class="of-radio-color" type="radio" name="bdp_color_preset" value="<?php echo esc_attr( $name ); ?>" <?php checked( $bdp_color_preset, $name ); ?>>
														<?php echo esc_html( $value['preset_name'] ); ?>
													</label>
													<?php Bdp_Utility::admin_color_preset( $value['display_value'] ); ?>
												</div>
												<?php
											}
											?>
										</div>
										<?php
									}
									?>
								</div>
							</li>

							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Single Layout Name', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter single layout name', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="single_layout_name" id="single_layout_name" value="<?php echo esc_attr( $single_template_name ); ?>" placeholder="<?php esc_attr_e( 'Enter single layout name', 'blog-designer-pro' ); ?>">
								</div>
							</li>

							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Override Single Product Design', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( "Apply plugin's single product layout design", 'blog-designer-pro' ); ?></span></span>
									<label>
										<input id="override_single" name="override_single" type="checkbox" value="1" 
										<?php
										if ( isset( $bdp_settings['override_single'] ) ) {
											checked( 1, $bdp_settings['override_single'] );
										}
										?>
										/>
									</label>
								</div>
							</li>

							<li class="override-single-design-li">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Single Product Override Type', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select single product override type', 'blog-designer-pro' ); ?></span></span>
									<?php
									$bdp_single_type = '';
									if ( isset( $bdp_settings['bdp_single_type'] ) ) {
										$bdp_single_type = $bdp_settings['bdp_single_type'];
									}
									if ( 'all' === $custom_single_type ) {
										$all_setting = '';
									} else {
										$all_setting = Bdp_Template::get_all_single_product_template_settings();
									}
									?>
									<select id="bdp_single_type" name="bdp_single_type">
										<option value="all" 
										<?php
										if ( $all_setting ) {
											echo "disabled='disabled'";
										}
										?>
										<?php echo selected( 'all', $bdp_single_type ); ?>><?php esc_html_e( 'All Products', 'blog-designer-pro' ); ?></option>
										<option value="category" <?php echo selected( 'category', $bdp_single_type ); ?>><?php esc_html_e( 'Category Wise', 'blog-designer-pro' ); ?></option>
										<option value="tag" <?php echo selected( 'tag', $bdp_single_type ); ?>><?php esc_html_e( 'Tag Wise', 'blog-designer-pro' ); ?></option>
									</select>
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
										<?php esc_html_e( 'If you select category/tag product override type, you must have to select category/tag type to show product.', 'blog-designer-pro' ); ?>
									</div>
								</div>
							</li>

							<li class="override-single-design-li single_category_list_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Product Categories', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right single_product_category_list_tr">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-select"><span class="bdp-tooltips"><?php esc_html_e( 'Select product categories', 'blog-designer-pro' ); ?></span></span>

									<?php
									$template_category = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : array();
									$categories        = get_categories(
										array(
											'child_of'   => '',
											'hide_empty' => 1,
											'taxonomy'   => 'product_cat',
										)
									);
									$db_categories     = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "category"' ); //phpcs:ignore
									$db_category_list  = array();
									if ( $db_categories ) {
										foreach ( $db_categories as $db_category ) {
											$sub_list = $db_category->sub_categories;
											if ( $sub_list ) {
												$db_category_ids = explode( ',', $sub_list );
												foreach ( $db_category_ids as $db_category_id ) {
													$db_category_list[] = $db_category_id;
												}
											}
										}
									}
									$final_cat = array_diff( $db_category_list, $template_category );
									?>
									<select data-placeholder="<?php esc_attr_e( 'Choose Product Categories', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_category[]" id="template_category">
										<?php
										foreach ( $categories as $category_obj ) :
											?>
											<option value="<?php echo esc_attr( $category_obj->term_id ); ?>" 
												<?php
                                                if ( is_array($template_category) && in_array( $category_obj->term_id, $template_category ) ) { //phpcs:ignore
													echo 'selected="selected"';
												}
        										if ( is_array($final_cat) && in_array( $category_obj->term_id, $final_cat ) ) { //phpcs:ignore
													echo 'disabled="disabled"';
												}
												?>
												><?php echo esc_html( $category_obj->name ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</li>
							<li class="override-single-design-li single_tag_list_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Product Tags', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-select"><span class="bdp-tooltips"><?php esc_html_e( 'Select product tags', 'blog-designer-pro' ); ?></span></span>
									<?php
									$template_tags = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : array();
									$tags          = get_terms( 'product_tag' );
									$db_tags       = $wpdb->get_results( 'SELECT sub_categories FROM ' . $wpdb->prefix . 'bdp_single_product WHERE single_product_template = "tag"' ); //phpcs:ignore
									$db_tag_list   = array();
									if ( $db_tags ) {
										foreach ( $db_tags as $db_tag ) {
											$sub_list = $db_tag->sub_categories;
											if ( $sub_list ) {
												$db_tag_ids = explode( ',', $sub_list );
												foreach ( $db_tag_ids as $db_tag_id ) {
													$db_tag_list[] = $db_tag_id;
												}
											}
										}
									}
									$final_tag = array_diff( $db_tag_list, $template_tags );
									?>
									<select data-placeholder="<?php esc_attr_e( 'Choose Product Tags', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_tags[]" id="template_tags">
										<?php foreach ( $tags as $tag ) : //phpcs:ignore ?>
											<option value="<?php echo esc_attr( $tag->term_id ); ?>" 
												<?php
												if ( is_array($template_tags) && in_array( $tag->term_id, $template_tags ) ) { //phpcs:ignore
													echo 'selected="selected"';
												}
												if ( is_array($final_tag) && in_array( $tag->term_id, $final_tag ) ) { //phpcs:ignore
													echo 'disabled="disabled"';
												}
												?>
											><?php echo esc_html( $tag->name ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</li>
							<li class="override-single-design-li single_all_post_tr">
								<div class="bdp-left"><span class="bdp-key-title"><?php esc_html_e( 'Select Products', 'blog-designer-pro' ); ?></span></div>
								<div class="bdp-right single_all_prodcut_tr">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select product from available products for single product layout', 'blog-designer-pro' ); ?></span></span>
									<?php
									$template_posts = isset( $bdp_settings['template_posts'] ) ? $bdp_settings['template_posts'] : array();
									
									$db_posts       = $wpdb->get_results( 'SELECT single_product_id FROM ' . $wpdb->prefix . 'bdp_single_product' ); //phpcs:ignore
									$db_posts_list  = array();
									if ( $db_posts ) {
										foreach ( $db_posts as $db_post ) {
											$sub_list = $db_post->single_product_id;
											if ( $sub_list ) {
												$db_post_ids = explode( ',', $sub_list );
												foreach ( $db_post_ids as $db_post_id ) {
													$db_posts_list[] = $db_post_id;
												}
											}
										}
									}
									$final_posts = array_diff( $db_posts_list, $template_posts );
									if ( 'tag' === $bdp_single_type ) {
										$tag_ids = isset( $bdp_settings['template_tags'] ) ? $bdp_settings['template_tags'] : array();
										if ( ! empty( $tag_ids ) ) {
											$args              = array(
												'cache_results' => false,
												'no_found_rows' => true,
												'fields' => 'ids',
												'showposts' => '-1',
												'post_status' => 'publish',
												'post_type' => 'product',
											);
											$args['tax_query'] = array( //phpcs:ignore
												array(
													'taxonomy' => 'product_tag',
													'field'    => 'term_id',
													'terms'    => $tag_ids,
												),
											);
										} else {
											$args = array(
												'cache_results' => 'false',
												'fields'  => 'ids',
												'posts_per_page' => -1,
												'post_type' => 'product',
												'orderby' => 'date',
												'order'   => 'desc',
											);
										}
									} elseif ( 'category' === $bdp_single_type ) {
										$cat_ids = isset( $bdp_settings['template_category'] ) ? $bdp_settings['template_category'] : array();
										if ( ! empty( $cat_ids ) ) {
											$args              = array(
												'cache_results' => false,
												'no_found_rows' => true,
												'fields' => 'ids',
												'showposts' => '-1',
												'post_status' => 'publish',
												'post_type' => 'product',
											);
											$args['tax_query'] = array( //phpcs:ignore
												array(
													'taxonomy' => 'product_cat',
													'field'    => 'term_id',
													'terms'    => $cat_ids,
												),
											);
										} else {
											$args = array(
												'cache_results' => 'false',
												'fields'  => 'ids',
												'posts_per_page' => -1,
												'post_type' => 'product',
												'orderby' => 'date',
												'order'   => 'desc',
											);
										}
									} else {
										$args = array(
											'cache_results' => 'false',
											'no_found_rows' => true,
											'fields'    => 'ids',
											'posts_per_page' => -1,
											'post_type' => 'product',
											'orderby'   => 'date',
											'order'     => 'desc',
										);
									}
									$allposts = get_posts( $args );
									if ( $allposts ) {
										$bdp_single_post_hidden = '';
										if ( ! empty( $template_posts ) ) {
											$bdp_single_post_hidden = implode( ',', $template_posts );
										}
										?>
										<input type="hidden" value="product" name="bdp_single_post_type" id="bdp_single_post_type">
										<input type="hidden" value="<?php echo esc_attr( $bdp_single_post_hidden ); ?>" name="bdp_single_post_hidden" id="bdp_single_post_hidden">
										<select data-placeholder="<?php esc_attr_e( 'Choose Products', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_posts[]" id="template_posts">
											<?php
											foreach ( $allposts as $single_product_id ) {
												$single_post = get_post( $single_product_id );
												setup_postdata( $single_post );
												// if ( in_array( $single_post->ID, $final_posts ) ) { //phpcs:ignore
													?>
												<option value="<?php echo esc_attr( $single_post->ID ); ?>"
													<?php
													if ( is_array($bdp_settings['template_posts']) && in_array( $single_post->ID, $bdp_settings['template_posts'] ) ) { //phpcs:ignore
														echo 'selected="selected"';
														echo "1";
													}
													if ( is_array($final_posts) && in_array( $single_post->ID, $final_posts ) ) { //phpcs:ignore
														echo 'disabled="disabled"';
														echo "2";
													}
													?>
												><?php echo esc_html( $single_post->post_title ); ?>
												</option>
													<?php
												// }
											}
											wp_reset_postdata();
											?>
										</select>
										<div class="bdp-setting-description bdp-note">
											<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
											<?php esc_html_e( 'Default All Products Selected', 'blog-designer-pro' ); ?>
										</div>
										<?php
									} else {
										esc_html_e( 'No Products found', 'blog-designer-pro' );
									}
									?>
								</div>
							</li>
							<h3 class="bdp-table-title override-single-design-li bdp-display-settings"><?php esc_html_e( 'Display Settings', 'blog-designer-pro' ); ?></h3>
							<li class="override-single-design-li bdp-display-settings">
								<div class="bdp-typography-wrapper bdp-button-settings ">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Title', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product title in single layout', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_title = isset( $bdp_settings['display_title'] ) ? $bdp_settings['display_title'] : 1; ?>
											<fieldset class="bdp-social-options bdp-display_title buttonset">
												<input id="display_title_1" name="display_title" type="radio" value="1" <?php echo checked( 1, $display_title ); ?> />
												<label for="display_title_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_title_0" name="display_title" type="radio" value="0" <?php echo checked( 0, $display_title ); ?> />
												<label for="display_title_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<?php
										$taxonomy_names = get_object_taxonomies( 'product', 'objects' );
										$taxonomy_names = apply_filters( 'bdp_hide_taxonomies', $taxonomy_names );
									if ( ! empty( $taxonomy_names ) ) {
										foreach ( $taxonomy_names as $taxonomy_name ) {
											if ( ! empty( $taxonomy_name ) ) {
												?>
												<div class="bdp-typography-cover">
													<div class="bdp-typography-label">
														<span class="bd-key-title">
														<?php echo esc_html( $taxonomy_name->label ); ?>
														</span>
														<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php echo esc_html__( 'Show', 'blog-designer-pro' ) . ' ' . esc_html( $taxonomy_name->label ) . ' ' . esc_html__( 'in single layout', 'blog-designer-pro' ); ?></span></span>
													</div>
													<div class="bdp-typography-content">
													<?php
													$_name              = 'display_taxonomy_' . $taxonomy_name->name;
													$display_custom_tax = isset( $bdp_settings[ $_name ] ) ? $bdp_settings[ $_name ] : 0;
													?>
														<fieldset class="bdp-social-options bdp-display_tax buttonset">
															<input id="<?php echo esc_attr( $_name ) . '_1'; ?>" name="<?php echo esc_attr( $_name ); ?>" type="radio" value="1" <?php echo checked( 1, $display_custom_tax ); ?>/>
															<label for="<?php echo esc_attr( $_name ) . '_1'; ?>"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
															<input id="<?php echo esc_attr( $_name ) . '_0'; ?>" name="<?php echo esc_attr( $_name ); ?>" type="radio" value="0" <?php echo checked( 0, $display_custom_tax ); ?> />
															<label for="<?php echo esc_attr( $_name ) . '_0'; ?>"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
														</fieldset>
														</label><label class="disable_link">
															<input id="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" name="disable_link_taxonomy_<?php echo esc_attr( $taxonomy_name->name ); ?>" type="checkbox" value="1" 
															<?php
															if ( isset( $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] ) ) {
																checked( 1, $bdp_settings[ 'disable_link_taxonomy_' . $taxonomy_name->name ] );
															}
															?>
															/> <?php esc_html_e( 'Disable Link', 'blog-designer-pro' ); ?>
														</label>
													</div>
												</div>
												<?php
											}
										}
									}
									?>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Author', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product author', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_author = isset( $bdp_settings['display_author'] ) ? $bdp_settings['display_author'] : 1; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="display_author_1" name="display_author" type="radio" value="1"  <?php checked( 1, $display_author ); ?> />
												<label for="display_author_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_author_0" name="display_author" type="radio" value="0" <?php checked( 0, $display_author ); ?> />
												<label for="display_author_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Date', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product date', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_date = isset( $bdp_settings['display_date'] ) ? $bdp_settings['display_date'] : 1; ?>
											<fieldset class="bdp-social-options bdp-display_date buttonset buttonset-hide ui-buttonset" data-hide="1">
												<input id="display_date_1" name="display_date" type="radio" value="1" <?php checked( 1, $display_date ); ?> />
												<label for="display_date_1" <?php checked( 1, $display_date ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_date_0" name="display_date" type="radio" value="0" <?php checked( 0, $display_date ); ?> />
												<label for="display_date_0" <?php checked( 0, $display_date ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<?php
									if ( 'yes' === get_option( 'woocommerce_enable_reviews', 'yes' ) ) {
										?>
										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Product Comments', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product comments', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $display_comment = isset( $bdp_settings['display_comment'] ) ? $bdp_settings['display_comment'] : 1; ?>
												<fieldset class="bdp-social-options bdp-display_comment buttonset buttonset-hide ui-buttonset" data-hide="1">
													<input id="display_comment_1" name="display_comment" type="radio" value="1" <?php checked( 1, $display_comment ); ?> />
													<label for="display_comment_1" <?php checked( 1, $display_comment ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="display_comment_0" name="display_comment" type="radio" value="0" <?php checked( 0, $display_comment ); ?> />
													<label for="display_comment_0" <?php checked( 0, $display_comment ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
												<label class="disable_link">
													<input id="disable_link_comment" name="disable_link_comment" type="checkbox" value="1" 
													<?php
													if ( isset( $bdp_settings['disable_link_comment'] ) ) {
														checked( 1, $bdp_settings['disable_link_comment'] );
													}
													?>
													/>
													<?php esc_html_e( 'Disable Link for Comments Form', 'blog-designer-pro' ); ?>
												</label>
											</div>
										</div>
										<?php
									}
									?>
									<div class="bdp-typography-cover display-postlike">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Like', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product like', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $display_postlike = isset( $bdp_settings['display_postlike'] ) ? $bdp_settings['display_postlike'] : '0'; ?>
											<fieldset class="buttonset">
												<input id="display_postlike_1" name="display_postlike" type="radio" value="1" <?php echo checked( 1, $display_postlike ); ?> />
												<label for="display_postlike_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_postlike_0" name="display_postlike" type="radio" value="0" <?php echo checked( 0, $display_postlike ); ?> />
												<label for="display_postlike_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover bdp_single_post_published_year">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Product Published Year', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips" style="left:-30px;"><?php esc_html_e( 'Show product published year', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$display_single_story_year = 1;
											if ( isset( $bdp_settings['display_single_story_year'] ) && '' != $bdp_settings['display_single_story_year'] ) { //phpcs:ignore
												$display_single_story_year = $bdp_settings['display_single_story_year'];
											}
											?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="display_single_story_year_1" name="display_single_story_year" type="radio" value="1"  <?php checked( 1, $display_single_story_year ); ?> />
												<label for="display_single_story_year_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="display_single_story_year_0" name="display_single_story_year" type="radio" value="0" <?php checked( 0, $display_single_story_year ); ?> />
												<label for="display_single_story_year_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

								</div>
							</li>
							<li class="override-single-design-li bdp-display-settings bdp-display-date-settings">
								<h3 class="bdp-table-title"><?php esc_html_e( 'Display Date Settings', 'blog-designer-pro' ); ?></h3>
								<div class="bdp-typography-wrapper bdp-button-settings">
									<div class="bdp-typography-cover post_date_from_tr">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Date', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select display product date', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $dsiplay_date_from = isset( $bdp_settings['dsiplay_date_from'] ) ? $bdp_settings['dsiplay_date_from'] : 'publish'; ?>
											<select name="dsiplay_date_from" id="dsiplay_date_from">
												<option value="publish"  <?php echo selected( 'publish', $dsiplay_date_from ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
												<option value="modify"  <?php echo selected( 'modify', $dsiplay_date_from ); ?>><?php esc_html_e( 'Last Modify Date', 'blog-designer-pro' ); ?></option>
											</select>
										</div>
									</div>

									<div class="bdp-typography-cover post_date_format_tr">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Date Format', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select product published format', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $post_date_format = isset( $bdp_settings['post_date_format'] ) ? $bdp_settings['post_date_format'] : 'default'; ?>
											<select name="post_date_format" id="post_date_format">
												<option value="default"  <?php echo selected( 'default', $post_date_format ); ?>><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></option>
												<option value="F j, Y g:i a"  <?php echo selected( 'F j, Y g:i a', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'F j, Y g:i a', true ) ); ?></option>
												<option value="F j, Y"  <?php echo selected( 'F j, Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'F j, Y', true ) ); ?></option>
												<option value="F, Y"  <?php echo selected( 'F, Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'F, Y', true ) ); ?></option>
												<option value="j F  Y"  <?php echo selected( 'j F  Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'j F  Y', true ) ); ?></option>
												<option value="g:i a"  <?php echo selected( 'g:i a', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'g:i a', true ) ); ?></option>
												<option value="g:i:s a"  <?php echo selected( 'g:i:s a', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'g:i:s a', true ) ); ?></option>
												<option value="l, F jS, Y"  <?php echo selected( 'l, F jS, Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'l, F jS, Y', true ) ); ?></option>
												<option value="M j, Y @ G:i"  <?php echo selected( 'M j, Y @ G:i', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'M j, Y @ G:i', true ) ); ?></option>
												<option value="Y/m/d g:i:s A"  <?php echo selected( 'Y/m/d g:i:s A', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'Y/m/d g:i:s A', true ) ); ?></option>
												<option value="Y/m/d"  <?php echo selected( 'Y/m/d', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'Y/m/d', true ) ); ?></option>
												<option value="d.m.Y"  <?php echo selected( 'd.m.Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'd.m.Y', true ) ); ?></option>
												<option value="d-m-Y"  <?php echo selected( 'd-m-Y', $post_date_format ); ?>><?php echo esc_attr( gmdate( 'd-m-Y', true ) ); ?></option>
											</select>
										</div>
									</div>
								</div>
							</li>
							<li class="override-single-design-li">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Product Views', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-textarea"><span class="bdp-tooltips"><?php esc_html_e( 'Show product views', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_post_views = 0;
									if ( isset( $bdp_settings['display_post_views'] ) ) {
										$display_post_views = $bdp_settings['display_post_views'];
									}
									?>
									<fieldset class="bdp-social-size buttonset buttonset-hide green" data-hide='1'>
										<input id="display_post_views_1" name="display_post_views" type="radio" value="1" <?php checked( 1, $display_post_views ); ?> />
										<label id="bdp-options-button" for="display_post_views_1" <?php checked( 1, $display_post_views ); ?>><?php esc_html_e( "Show Today's View", 'blog-designer-pro' ); ?></label>
										<input id="display_post_views_2" name="display_post_views" type="radio" value="2" <?php checked( 2, $display_post_views ); ?> />
										<label id="bdp-options-button" for="display_post_views_2" <?php checked( 2, $display_post_views ); ?>><?php esc_html_e( 'Show All Views', 'blog-designer-pro' ); ?></label>
										<input id="display_post_views_0" name="display_post_views" type="radio" value="0" <?php checked( 0, $display_post_views ); ?> />
										<label id="bdp-options-button" for="display_post_views_0" <?php checked( 0, $display_post_views ); ?>><?php esc_html_e( 'Hide', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>

							<li class="override-single-design-li">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Custom CSS', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-textarea"><span class="bdp-tooltips"><?php esc_html_e( 'Write a "Custom CSS" to add your additional design for single blog page', 'blog-designer-pro' ); ?></span></span>
									<?php
									echo '<textarea class="widefat textarea" name="custom_css" id="custom_css" placeholder=".class_name{ color:#ffffff }">';
									if ( isset( $bdp_settings['custom_css'] ) ) {
										echo wp_unslash( $bdp_settings['custom_css'] ); //phpcs:ignore
									}
									echo '</textarea>';
									?>
									<div class="bdp-setting-description bdp-note">
										<b class=""><?php esc_html_e( 'Example', 'blog-designer-pro' ); ?>:</b>
										<?php echo '.class_name{ color:#ffffff }'; ?>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglestandard" class="postbox postbox-with-fw-options" <?php echo $bdpstandard_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Main Container Class Name', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter main container class name', 'blog-designer-pro' ); ?></span></span>
									<?php $main_container_class = ( isset( $bdp_settings['main_container_class'] ) && '' != $bdp_settings['main_container_class'] ) ? $bdp_settings['main_container_class'] : ''; //phpcs:ignore ?>
									<input type="text" name="main_container_class" id="main_container_class" value="<?php echo esc_attr( $main_container_class ); ?>" placeholder="<?php esc_attr_e( 'Enter main container class name', 'blog-designer-pro' ); ?>">
								</div>
							</li>
							<li class="single-background-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Background Color for Single Products', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select background color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="template_bgcolor" id="template_bgcolor" value="<?php echo isset( $bdp_settings['template_bgcolor'] ) ? esc_attr( $bdp_settings['template_bgcolor'] ) : '#fff'; ?>"/>
								</div>
							</li>
							<li class="blog-templatecolor-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Single Product Template Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select  template color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="template_color" id="template_color" value="<?php echo isset( $bdp_settings['template_color'] ) ? esc_attr( $bdp_settings['template_color'] ) : '#000'; ?>"/>
								</div>
							</li>
							<li class="story-startup-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Story Startup Text', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter story startup text', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="story_startup_text" id="story_startup_text" value="<?php echo isset( $bdp_settings['story_startup_text'] ) ? esc_attr( $bdp_settings['story_startup_text'] ) : esc_html__( 'STARTUP', 'blog-designer-pro' ); ?>"/>
								</div>
							</li>
							<li class="display_bgimage_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Background Image', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select product default image', 'blog-designer-pro' ); ?></span></span>
									<span class="bdp_default_image_holder bdp_bg_image">
										<?php
										if ( isset( $bdp_settings['bdp_bg_image_src'] ) && '' != $bdp_settings['bdp_bg_image_src'] ) { //phpcs:ignore
											echo '<img src="' . esc_url( $bdp_settings['bdp_bg_image_src'] ) . '"/>';
										}
										?>
									</span>
									<?php if ( isset( $bdp_settings['bdp_bg_image_src'] ) && '' != $bdp_settings['bdp_bg_image_src'] ) { //phpcs:ignore ?>
										<input id="bdp-image-action-button" class="button bdp-remove_image_button bdp_bg_image" type="button" value="<?php esc_attr_e( 'Remove Image', 'blog-designer-pro' ); ?>">
									<?php } else { ?>
										<input class="button bdp-upload_image_button bdp_bg_image" type="button" value="<?php esc_attr_e( 'Upload Image', 'blog-designer-pro' ); ?>">
									<?php } ?>
									<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_bg_image_id'] ) ? esc_attr( $bdp_settings['bdp_bg_image_id'] ) : ''; ?>" name="bdp_bg_image_id" id="bdp_bg_image_id">
									<input type="hidden" value="<?php echo isset( $bdp_settings['bdp_bg_image_src'] ) ? esc_attr( $bdp_settings['bdp_bg_image_src'] ) : ''; ?>" name="bdp_bg_image_src" id="bdp_bg_image_src">
									</div>
							</li>
							<li class="story-startup-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Story Startup Background Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select story startup background color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="story_startup_background" id="story_startup_background" value="<?php echo isset( $bdp_settings['story_startup_background'] ) ? esc_attr( $bdp_settings['story_startup_background'] ) : '#ade175'; ?>" data-default-color="<?php echo isset( $bdp_settings['story_startup_background'] ) ? esc_attr( $bdp_settings['story_startup_background'] ) : '#ade175'; ?>"/>
								</div>
							</li>
							<li class="story-startup-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Story Startup Text Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select story startup text color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="story_startup_text_color" id="story_startup_text_color" value="<?php echo isset( $bdp_settings['story_startup_text_color'] ) ? esc_attr( $bdp_settings['story_startup_text_color'] ) : '#333'; ?>" data-default-color="<?php echo isset( $bdp_settings['story_startup_text_color'] ) ? esc_attr( $bdp_settings['story_startup_text_color'] ) : '#333'; ?>"/>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Choose Link Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select link color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="template_ftcolor" id="template_ftcolor" value="<?php echo isset( $bdp_settings['template_ftcolor'] ) ? esc_attr( $bdp_settings['template_ftcolor'] ) : ''; ?>" data-default-color="<?php echo isset( $bdp_settings['template_ftcolor'] ) ? esc_attr( $bdp_settings['template_ftcolor'] ) : ''; ?>"/>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Choose Link Hover Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select link hover color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="template_fthovercolor" id="template_fthovercolor" value=" 
									<?php
									if ( isset( $bdp_settings['template_fthovercolor'] ) ) {
										echo esc_attr( $bdp_settings['template_fthovercolor'] );
									}
									?>
									" data-default-color="
									<?php
									if ( isset( $bdp_settings['template_fthovercolor'] ) ) {
										echo esc_attr( $bdp_settings['template_fthovercolor'] );
									}
									?>
									"/>
								</div>
							</li>
							<li class="winter-category-back-color" style="display: none;">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php echo esc_html( $winter_category_txt ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php echo esc_html( $winter_category_txt ); ?></span></span>
									<input type="text" name="winter_category_color" id="winter_category_color" value="
									<?php
									if ( isset( $bdp_settings['winter_category_color'] ) ) {
										echo esc_attr( $bdp_settings['winter_category_color'] );
									}
									?>
									" data-default-color="
									<?php
									if ( isset( $bdp_settings['winter_category_color'] ) ) {
										echo esc_attr( $bdp_settings['winter_category_color'] );
									}
									?>
									"/>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsingletitle" class="postbox postbox-with-fw-options" <?php echo $bdptitle_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Product Title Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select product title color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="template_titlecolor" id="template_titlecolor" value="<?php echo isset( $bdp_settings['template_titlecolor'] ) ? esc_attr( $bdp_settings['template_titlecolor'] ) : ''; ?>"/>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
							<li class="edd_addtocart_button_box_shadow_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'H-offset (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select horizontal offset value', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_title_top_box_shadow = isset( $bdp_settings['bdp_title_top_box_shadow'] ) ? $bdp_settings['bdp_title_top_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_title_top_box_shadow" name="bdp_title_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_title_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'V-offset (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select vertical offset value', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_title_right_box_shadow = isset( $bdp_settings['bdp_title_right_box_shadow'] ) ? $bdp_settings['bdp_title_right_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_title_right_box_shadow" name="bdp_title_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_title_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Blur (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select blur value', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_title_bottom_box_shadow = isset( $bdp_settings['bdp_title_bottom_box_shadow'] ) ? $bdp_settings['bdp_title_bottom_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_title_bottom_box_shadow" name="bdp_title_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_title_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover bdp-boxshadow-color">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select title shadow color', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
												<?php $bdp_title_box_shadow_color = isset( $bdp_settings['bdp_title_box_shadow_color'] ) ? $bdp_settings['bdp_title_box_shadow_color'] : ''; ?>
												<input type="text" name="bdp_title_box_shadow_color" id="bdp_title_box_shadow_color" value="<?php echo esc_attr( $bdp_title_box_shadow_color ); ?>"/>
										</div>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-typography-wrapper bdp-typography-wrapper1">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font family', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<input type="hidden" name="template_titlefontface_font_type" id="template_titlefontface_font_type" value="<?php echo isset( $bdp_settings['template_titlefontface_font_type'] ) ? esc_attr( $bdp_settings['template_titlefontface_font_type'] ) : 'Serif Fonts'; ?>">
											<div class="select-cover">
												<select name="template_titlefontface" id="template_titlefontface">
													<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
													<?php
													$template_titlefontface = ( isset( $bdp_settings['template_titlefontface'] ) && '' != $bdp_settings['template_titlefontface'] ) ? $bdp_settings['template_titlefontface'] : ''; //phpcs:ignore
													$old_version            = '';
													$cnt                    = 0;
													foreach ( $font_family as $key => $value ) {
														if ( $value['version'] != $old_version ) { //phpcs:ignore
															if ( $cnt > 0 ) {
																echo '</optgroup>';
															}
															echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
															$old_version = $value['version'];
														}
														echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

														if ( '' != $template_titlefontface && ( str_replace( '"', '', $template_titlefontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
															echo ' selected';
														}
														echo '>' . esc_html( $value['label'] ) . '</option>';
														$cnt++;
													}
													if ( $cnt == count( $font_family ) ) { //phpcs:ignore
														echo '</optgroup>';
													}
													?>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Size (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font size', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_titlefontsize = ( isset( $bdp_settings['template_titlefontsize'] ) ) ? $bdp_settings['template_titlefontsize'] : 16; ?>
											<div class="grid_col_space range_slider_fontsize" id="template_titlefontsizeInput" ></div>
											<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="template_titlefontsize" id="template_titlefontsize" value="<?php echo esc_attr( $template_titlefontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font weight', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_weight = isset( $bdp_settings['template_title_font_weight'] ) ? $bdp_settings['template_title_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="template_title_font_weight" id="template_title_font_weight">
													<option value="100" <?php selected( $template_title_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $template_title_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $template_title_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $template_title_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $template_title_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $template_title_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $template_title_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $template_title_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $template_title_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $template_title_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $template_title_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter line height', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<div class="input-type-number">
												<input type="number" name="template_title_font_line_height" id="template_title_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['template_title_font_line_height'] ) ? esc_attr( $bdp_settings['template_title_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display italic font style', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_italic = isset( $bdp_settings['template_title_font_italic'] ) ? $bdp_settings['template_title_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="template_title_font_italic_1" name="template_title_font_italic" type="radio" value="1"  <?php checked( 1, $template_title_font_italic ); ?> />
												<label for="template_title_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="template_title_font_italic_0" name="template_title_font_italic" type="radio" value="0" <?php checked( 0, $template_title_font_italic ); ?> />
												<label for="template_title_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select text transform style', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_text_transform = isset( $bdp_settings['template_title_font_text_transform'] ) ? $bdp_settings['template_title_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_title_font_text_transform" id="template_title_font_text_transform">
													<option <?php selected( $template_title_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select text decoration', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_title_font_text_decoration = isset( $bdp_settings['template_title_font_text_decoration'] ) ? $bdp_settings['template_title_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_title_font_text_decoration" id="template_title_font_text_decoration">
													<option <?php selected( $template_title_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_title_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter letter spacing', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<div class="input-type-number">
												<input type="number" name="template_title_font_letter_spacing" id="template_title_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['template_title_font_letter_spacing'] ) ? esc_attr( $bdp_settings['template_title_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsingleconent" class="postbox postbox-with-fw-options bdp-content-setting1" <?php echo $bdpcontent_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
						<li>
							<div class="bdp-left">
								<span class="bdp-key-title">
									<?php esc_html_e( 'Show Content From', 'blog-designer-pro' ); ?>
								</span>
							</div>
							<div class="bdp-right product_content_description">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'To display text from product content or from product short description', 'blog-designer-pro' ); ?></span></span>
								<?php $template_post_content_from = isset( $bdp_settings['template_post_content_from'] ) ? $bdp_settings['template_post_content_from'] : 'from_content'; ?>
								<select name="template_post_content_from" id="template_post_content_from" >
									<option value="from_content" <?php selected( $template_post_content_from, 'from_content' ); ?> ><?php esc_html_e( 'Product Content', 'blog-designer-pro' ); ?></option>
									<option value="from_excerpt" <?php selected( $template_post_content_from, 'from_excerpt' ); ?>><?php esc_html_e( 'Product Short Description', 'blog-designer-pro' ); ?></option>
								</select>
								<div class="bdp-setting-description bdp-note">
									<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
									<?php esc_html_e( 'If Product short description is empty then Content will get automatically from Product Content.', 'blog-designer-pro' ); ?>
								</div>
							</div>
						</li>
							<li class="content-firstletter-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'First letter of product content as Dropcap', 'blog-designer-pro' ); ?>
									</span>

								</div>
								<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable first letter of product content as dropcap', 'blog-designer-pro' ); ?></span></span>
									<?php $firstletter_big = ( isset( $bdp_settings['firstletter_big'] ) ) ? $bdp_settings['firstletter_big'] : 0; ?>
									<fieldset class="buttonset firstletter_big">
										<input id="firstletter_big_1" name="firstletter_big" type="radio" value="1" <?php checked( 1, $firstletter_big ); ?> />
										<label for="firstletter_big_1" <?php checked( 1, $firstletter_big ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
										<input id="firstletter_big_0" name="firstletter_big" type="radio" value="0" <?php checked( 0, $firstletter_big ); ?> />
										<label for="firstletter_big_0" <?php checked( 0, $firstletter_big ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="firstletter-setting">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'First letter of Product Content Font Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Set font size for first letter of product content', 'blog-designer-pro' ); ?></span></span>
									<?php $firstletter_fontsize = ( isset( $bdp_settings['firstletter_fontsize'] ) && '' != $bdp_settings['firstletter_fontsize'] ) ? $bdp_settings['firstletter_fontsize'] : '35'; //phpcs:ignore ?>
									<div class="grid_col_space range_slider_fontsize" id="firstletter_fontsize_slider"></div>
									<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="firstletter_fontsize" id="firstletter_fontsize" value="<?php echo esc_attr( $firstletter_fontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
								</div>
							</li>
							<li class="firstletter-setting">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'First letter of PProductost Content Font Family', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font family for first letter of product content', 'blog-designer-pro' ); ?></span></span>
                                    <?php $firstletter_font_family = ( isset( $bdp_settings['firstletter_font_family'] ) && '' != $bdp_settings['firstletter_font_family'] ) ? $bdp_settings['firstletter_font_family'] : ''; //phpcs:ignore ?>
									<div class="typo-field">
										<input type="hidden" id="firstletter_font_family_font_type" name="firstletter_font_family_font_type" value="<?php echo isset( $bdp_settings['firstletter_font_family_font_type'] ) ? esc_attr( $bdp_settings['firstletter_font_family_font_type'] ) : ''; ?>">
										<select name="firstletter_font_family" id="firstletter_font_family">
											<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
											<?php
											$old_version = '';
											$cnt         = 0;
											foreach ( $font_family as $key => $value ) {
												if ( $value['version'] != $old_version ) { //phpcs:ignore
													if ( $cnt > 0 ) {
														echo '</optgroup>';
													}
													echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
													$old_version = $value['version'];
												}
												echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

												if ( '' != $firstletter_font_family && ( str_replace( '"', '', $firstletter_font_family ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
													echo ' selected';
												}
												echo '>' . esc_html( $value['label'] ) . '</option>';
												$cnt++;
											}
											if ( $cnt == count( $font_family ) ) { //phpcs:ignore
												echo '</optgroup>';
											}
											?>
										</select>
									</div>
								</div>
							</li>
							<li class="firstletter-setting">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'First letter of Product Content Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select color for first letter of product content', 'blog-designer-pro' ); ?></span></span>
									<?php $firstletter_contentcolor = ( isset( $bdp_settings['firstletter_contentcolor'] ) ) ? $bdp_settings['firstletter_contentcolor'] : ''; ?>
									<input type="text" name="firstletter_contentcolor" id="firstletter_contentcolor" value="<?php echo esc_attr( $firstletter_contentcolor ); ?>"/>
								</div>
							</li>

							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Product Content Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select color of product content', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="template_contentcolor" id="template_contentcolor" value="<?php echo isset( $bdp_settings['template_contentcolor'] ) ? esc_attr( $bdp_settings['template_contentcolor'] ) : ''; ?>"/>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
							<li class="edd_addtocart_button_box_shadow_setting">
								<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'H-offset (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select horizontal offset value', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_content_top_box_shadow = isset( $bdp_settings['bdp_content_top_box_shadow'] ) ? $bdp_settings['bdp_content_top_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_content_top_box_shadow" name="bdp_content_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_content_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'V-offset (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select vertical offset value', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_content_right_box_shadow = isset( $bdp_settings['bdp_content_right_box_shadow'] ) ? $bdp_settings['bdp_content_right_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_content_right_box_shadow" name="bdp_content_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_content_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Blur (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select blur value', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
											<?php $bdp_content_bottom_box_shadow = isset( $bdp_settings['bdp_content_bottom_box_shadow'] ) ? $bdp_settings['bdp_content_bottom_box_shadow'] : '0'; ?>
											<input type="number" id="bdp_content_bottom_box_shadow" name="bdp_content_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_content_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<div class="bdp-boxshadow-cover bdp-boxshadow-color">
										<div class="bdp-boxshadow-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select title shadow color', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-boxshadow-content">
												<?php $bdp_content_box_shadow_color = isset( $bdp_settings['bdp_content_box_shadow_color'] ) ? $bdp_settings['bdp_content_box_shadow_color'] : ''; ?>
												<input type="text" name="bdp_content_box_shadow_color" id="bdp_content_box_shadow_color" value="<?php echo esc_attr( $bdp_content_box_shadow_color ); ?>"/>
										</div>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
							<li>
								<div class="bdp-typography-wrapper bdp-typography-wrapper1">
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font family for product content', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$template_contentfontface = '';
											if ( isset( $bdp_settings['template_contentfontface'] ) ) {
												$template_contentfontface = $bdp_settings['template_contentfontface'];
											}
											?>
											<div class="typo-field">
												<input type="hidden" name="template_contentfontface_font_type" id="template_contentfontface_font_type" value="<?php echo isset( $bdp_settings['template_contentfontface_font_type'] ) ? esc_attr( $bdp_settings['template_contentfontface_font_type'] ) : 'Serif Fonts'; ?>">
												<div class="select-cover">
													<select name="template_contentfontface" id="template_contentfontface">
														<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
														<?php
														$old_version = '';
														$cnt         = 0;
														foreach ( $font_family as $key => $value ) {
															if ( $value['version'] != $old_version ) { //phpcs:ignore
																if ( $cnt > 0 ) {
																	echo '</optgroup>';
																}
																echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																$old_version = $value['version'];
															}
															echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

															if ( '' != $template_contentfontface && ( str_replace( '"', '', $template_contentfontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
																echo ' selected';
															}
															echo '>' . esc_html( $value['label'] ) . '</option>';
															$cnt++;
														}
														if ( $cnt == count( $font_family ) ) { //phpcs:ignore
															echo '</optgroup>';
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Size (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font size of product content', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $content_fontsize = ( isset( $bdp_settings['content_fontsize'] ) ) ? $bdp_settings['content_fontsize'] : 15; ?>
											<div class="grid_col_space range_slider_fontsize" id="content_fontsize_slider" data-value="<?php echo esc_attr( $content_fontsize ); ?>" ></div>
											<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="content_fontsize" id="content_fontsize" value="<?php echo esc_attr( $content_fontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font weight', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_weight = isset( $bdp_settings['template_content_font_weight'] ) ? $bdp_settings['template_content_font_weight'] : 'normal'; ?>
											<div class="select-cover">
												<select name="template_content_font_weight" id="template_content_font_weight">
													<option value="100" <?php selected( $template_content_font_weight, 100 ); ?>>100</option>
													<option value="200" <?php selected( $template_content_font_weight, 200 ); ?>>200</option>
													<option value="300" <?php selected( $template_content_font_weight, 300 ); ?>>300</option>
													<option value="400" <?php selected( $template_content_font_weight, 400 ); ?>>400</option>
													<option value="500" <?php selected( $template_content_font_weight, 500 ); ?>>500</option>
													<option value="600" <?php selected( $template_content_font_weight, 600 ); ?>>600</option>
													<option value="700" <?php selected( $template_content_font_weight, 700 ); ?>>700</option>
													<option value="800" <?php selected( $template_content_font_weight, 800 ); ?>>800</option>
													<option value="900" <?php selected( $template_content_font_weight, 900 ); ?>>900</option>
													<option value="bold" <?php selected( $template_content_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
													<option value="normal" <?php selected( $template_content_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter line height', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<div class="input-type-number">
												<input type="number" name="template_content_font_line_height" id="template_content_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['template_content_font_line_height'] ) ? esc_attr( $bdp_settings['template_content_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display italic font style', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_italic = isset( $bdp_settings['template_content_font_italic'] ) ? $bdp_settings['template_content_font_italic'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-display_author buttonset">
												<input id="template_content_font_italic_1" name="template_content_font_italic" type="radio" value="1"  <?php checked( 1, $template_content_font_italic ); ?> />
												<label for="template_content_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="template_content_font_italic_0" name="template_content_font_italic" type="radio" value="0" <?php checked( 0, $template_content_font_italic ); ?> />
												<label for="template_content_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select text transform', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_text_transform = isset( $bdp_settings['template_content_font_text_transform'] ) ? $bdp_settings['template_content_font_text_transform'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_content_font_text_transform" id="template_content_font_text_transform">
													<option <?php selected( $template_content_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select text decoration option', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $template_content_font_text_decoration = isset( $bdp_settings['template_content_font_text_decoration'] ) ? $bdp_settings['template_content_font_text_decoration'] : 'none'; ?>
											<div class="select-cover">
												<select name="template_content_font_text_decoration" id="template_content_font_text_decoration">
													<option <?php selected( $template_content_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
													<option <?php selected( $template_content_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter letter spacing', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<div class="input-type-number">
												<input type="number" name="template_content_font_letter_spacing" id="template_content_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['template_content_font_letter_spacing'] ) ? esc_attr( $bdp_settings['template_content_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglemedia" class="postbox postbox-with-fw-options bdp-content-setting1" <?php echo $bdpmedia_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li class="bdp_single_custom_media_selection">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Product Featured Image and Gallery Image', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product featured image and gallery image', 'blog-designer-pro' ); ?></span></span>
									<?php $display_thumbnail = isset( $bdp_settings['display_thumbnail'] ) ? $bdp_settings['display_thumbnail'] : 1; ?>
									<fieldset class="bdp-social-options bdp-display_comment buttonset buttonset-hide ui-buttonset" data-hide="1">
										<input id="display_thumbnail_1" name="display_thumbnail" type="radio" value="1" <?php checked( 1, $display_thumbnail ); ?> />
										<label for="display_thumbnail_1" <?php checked( 1, $display_thumbnail ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_thumbnail_0" name="display_thumbnail" type="radio" value="0" <?php checked( 0, $display_thumbnail ); ?> />
										<label for="display_thumbnail_0" <?php checked( 0, $display_thumbnail ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsingleproductsetting" class="postbox postbox-with-fw-options" <?php echo $bdpproductsetting_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
								<span class="bdp-key-title">
									<?php esc_html_e( 'Display SKU', 'blog-designer-pro' ); ?>
								</span>
							</div>
							<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show SKU', 'blog-designer-pro' ); ?></span></span>
								<?php
								$display_sku_product = '1';
								if ( isset( $bdp_settings['display_sku_product'] ) ) {
									$display_sku_product = $bdp_settings['display_sku_product'];
								}
								?>
								<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
									<input id="display_sku_product_1" name="display_sku_product" type="radio" value="1" <?php checked( 1, $display_sku_product ); ?> />
									<label id="bdp-options-button" for="display_sku_product_1" <?php checked( 1, $display_sku_product ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
									<input id="display_sku_product_0" name="display_sku_product" type="radio" value="0" <?php checked( 0, $display_sku_product ); ?> />
									<label id="bdp-options-button" for="display_sku_product_0" <?php checked( 1, $display_sku_product ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
								</fieldset>
							</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Sale Tag', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable sale tag', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_sale_tag = '1';
									if ( isset( $bdp_settings['display_sale_tag'] ) ) {
										$display_sale_tag = $bdp_settings['display_sale_tag'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_sale_tag_1" name="display_sale_tag" type="radio" value="1" <?php checked( 1, $display_sale_tag ); ?> />
										<label id="bdp-options-button" for="display_sale_tag_1" <?php checked( 1, $display_sale_tag ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_sale_tag_0" name="display_sale_tag" type="radio" value="0" <?php checked( 0, $display_sale_tag ); ?> />
										<label id="bdp-options-button" for="display_sale_tag_0" <?php checked( 1, $display_sale_tag ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_sale_setting">
								<?php esc_html_e( 'Sale Tag Settings', 'blog-designer-pro' ); ?>
							</h3>
							<li class="bdp_sale_setting">
								<ul>
									<li class="bdp_sale_tagtext_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag text color', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_sale_tagtextcolor = ( isset( $bdp_settings['bdp_sale_tagtextcolor'] ) && '' != $bdp_settings['bdp_sale_tagtextcolor'] ) ? $bdp_settings['bdp_sale_tagtextcolor'] : ''; //phpcs:ignore
											?>
											<input type="text" name="bdp_sale_tagtextcolor" id="bdp_sale_tagtextcolor" value="<?php echo esc_attr( $bdp_sale_tagtextcolor ); ?>"/>
										</div>
									</li>
									<li class="bdp_sale_tagtext_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag background color', 'blog-designer-pro' ); ?></span></span>
											<?php

											$bdp_sale_tagbgcolor = ( isset( $bdp_settings['bdp_sale_tagbgcolor'] ) && '' != $bdp_settings['bdp_sale_tagbgcolor'] ) ? $bdp_settings['bdp_sale_tagbgcolor'] : ''; //phpcs:ignore
											?>
											<input type="text" name="bdp_sale_tagbgcolor" id="bdp_sale_tagbgcolor" value="<?php echo esc_attr( $bdp_sale_tagbgcolor ); ?>"/>
										</div>
									</li>
									<li class="bdp_sale_tagtext_tr bdp_sale_tagtext_alignment_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag alignment', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_sale_tagtext_alignment = 'left';
											if ( isset( $bdp_settings['bdp_sale_tagtext_alignment'] ) ) {
												$bdp_sale_tagtext_alignment = $bdp_settings['bdp_sale_tagtext_alignment'];
											}
											?>
											<select name="bdp_sale_tagtext_alignment" id="bdp_sale_tagtext_alignment">
											<option value="left-top" <?php echo selected( 'left-top', $bdp_sale_tagtext_alignment ); ?>>
												<?php esc_html_e( 'Left Top', 'blog-designer-pro' ); ?>
											</option>
											<option value="left-bottom" <?php echo selected( 'left-bottom', $bdp_sale_tagtext_alignment ); ?>>
												<?php esc_html_e( 'Left Bottom', 'blog-designer-pro' ); ?>
											</option>
											<option value="right-top" <?php echo selected( 'right-top', $bdp_sale_tagtext_alignment ); ?>>
												<?php esc_html_e( 'Right Top', 'blog-designer-pro' ); ?>
											</option>
											<option value="right-bottom" <?php echo selected( 'right-bottom', $bdp_sale_tagtext_alignment ); ?>>
												<?php esc_html_e( 'Right Bottom', 'blog-designer-pro' ); ?>
											</option>
										</select>
										</div>
									</li>
									<li class="bdp_sale_tagtext_tr bdp_sale_tagtext_alignment_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Angle(0-360 deg)', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag angle', 'blog-designer-pro' ); ?></span></span>
											<?php $bdp_sale_tag_angle = isset( $bdp_settings['bdp_sale_tag_angle'] ) ? $bdp_settings['bdp_sale_tag_angle'] : '0'; ?>
											<input type="number" id="bdp_sale_tag_angle" name="bdp_sale_tag_angle" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tag_angle ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</li>
									<li class="bdp_sale_tagtext_tr bdp_sale_tagtext_alignment_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Border Radius(%)', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag border radius', 'blog-designer-pro' ); ?></span></span>
											<?php $bdp_sale_tag_border_radius = isset( $bdp_settings['bdp_sale_tag_border_radius'] ) ? $bdp_settings['bdp_sale_tag_border_radius'] : '0'; ?>
											<input type="number" id="bdp_sale_tag_border_radius" name="bdp_sale_tag_border_radius" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tag_border_radius ); ?>" onkeypress="return isNumberKey(event)">
										</div>
									</li>
									<h3 class="bdp_sale_tagtext_tr bdp_sale_tagtext_padding_setting_tr bdp-table-title"><?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?></h3>
									<li class="bdp_sale_tagtext_tr bdp_sale_tagtext_padding_setting_tr">
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_paddingleft = isset( $bdp_settings['bdp_sale_tagtext_paddingleft'] ) ? $bdp_settings['bdp_sale_tagtext_paddingleft'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_paddingleft" name="bdp_sale_tagtext_paddingleft" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_paddingleft ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_paddingright = isset( $bdp_settings['bdp_sale_tagtext_paddingright'] ) ? $bdp_settings['bdp_sale_tagtext_paddingright'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_paddingright" name="bdp_sale_tagtext_paddingright" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_paddingright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_paddingtop = isset( $bdp_settings['bdp_sale_tagtext_paddingtop'] ) ? $bdp_settings['bdp_sale_tagtext_paddingtop'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_paddingtop" name="bdp_sale_tagtext_paddingtop" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_paddingbottom = isset( $bdp_settings['bdp_sale_tagtext_paddingbottom'] ) ? $bdp_settings['bdp_sale_tagtext_paddingbottom'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_paddingbottom" name="bdp_sale_tagtext_paddingbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
									<h3 class="bdp_sale_tagtext_tr bdp_sale_tagtext_marging_setting_tr bdp-table-title"><?php esc_html_e( 'Margin', 'blog-designer-pro' ); ?></h3>
									<li class="bdp_sale_tagtext_tr bdp_sale_tagtext_marging_setting_tr">
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_marginleft = isset( $bdp_settings['bdp_sale_tagtext_marginleft'] ) ? $bdp_settings['bdp_sale_tagtext_marginleft'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_marginleft" name="bdp_sale_tagtext_marginleft" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_marginleft ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_marginright = isset( $bdp_settings['bdp_sale_tagtext_marginright'] ) ? $bdp_settings['bdp_sale_tagtext_marginright'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_marginright" name="bdp_sale_tagtext_marginright" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_marginright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_margintop = isset( $bdp_settings['bdp_sale_tagtext_margintop'] ) ? $bdp_settings['bdp_sale_tagtext_margintop'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_margintop" name="bdp_sale_tagtext_margintop" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_margintop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_sale_tagtext_marginbottom = isset( $bdp_settings['bdp_sale_tagtext_marginbottom'] ) ? $bdp_settings['bdp_sale_tagtext_marginbottom'] : '5'; ?>
													<input type="number" id="bdp_sale_tagtext_marginbottom" name="bdp_sale_tagtext_marginbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_sale_tagtext_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_sale_tagtext_tr">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-wrapper bdp-typography-wrapper1">
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag font family', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_sale_tagfontface'] ) && '' != $bdp_settings['bdp_sale_tagfontface'] ) { //phpcs:ignore
														$bdp_sale_tagfontface = $bdp_settings['bdp_sale_tagfontface'];
													} else {
														$bdp_sale_tagfontface = '';
													}
													?>
													<div class="typo-field">
														<input type="hidden" id="bdp_sale_tagfontface_font_type" name="bdp_sale_tagfontface_font_type" value="<?php echo isset( $bdp_settings['bdp_sale_tagfontface_font_type'] ) ? esc_attr( $bdp_settings['bdp_sale_tagfontface_font_type'] ) : ''; ?>">
														<div class="select-cover">
															<select name="bdp_sale_tagfontface" id="bdp_sale_tagfontface">
																<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
																<?php
																$old_version = '';
																$cnt         = 0;
																foreach ( $font_family as $key => $value ) {
																	if ( $value['version'] != $old_version ) { //phpcs:ignore
																		if ( $cnt > 0 ) {
																			echo '</optgroup>';
																		}
																		echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																		$old_version = $value['version'];
																	}
																	echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";
																	if ( '' != $bdp_sale_tagfontface && ( str_replace( '"', '', $bdp_sale_tagfontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
																		echo ' selected';
																	}
																	echo '>' . esc_html( $value['label'] ) . '</option>';
																	$cnt++;
																}
																if ( $cnt == count( $font_family ) ) { //phpcs:ignore
																	echo '</optgroup>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Size (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag font size', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_sale_tagfontsize'] ) && '' != $bdp_settings['bdp_sale_tagfontsize'] ) { //phpcs:ignore
														$bdp_sale_tagfontsize = $bdp_settings['bdp_sale_tagfontsize'];
													} else {
														$bdp_sale_tagfontsize = 14;
													}
													?>
													<div class="grid_col_space range_slider_fontsize" id="bdp_sale_tagfontsizeInput" ></div>
													<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="bdp_sale_tagfontsize" id="bdp_sale_tagfontsize" value="<?php echo esc_attr( $bdp_sale_tagfontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag font weight', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_sale_tag_font_weight = isset( $bdp_settings['bdp_sale_tag_font_weight'] ) ? $bdp_settings['bdp_sale_tag_font_weight'] : 'normal'; ?>
													<div class="select-cover">
														<select name="bdp_sale_tag_font_weight" id="bdp_sale_tag_font_weight">
															<option value="100" <?php selected( $bdp_sale_tag_font_weight, 100 ); ?>>100</option>
															<option value="200" <?php selected( $bdp_sale_tag_font_weight, 200 ); ?>>200</option>
															<option value="300" <?php selected( $bdp_sale_tag_font_weight, 300 ); ?>>300</option>
															<option value="400" <?php selected( $bdp_sale_tag_font_weight, 400 ); ?>>400</option>
															<option value="500" <?php selected( $bdp_sale_tag_font_weight, 500 ); ?>>500</option>
															<option value="600" <?php selected( $bdp_sale_tag_font_weight, 600 ); ?>>600</option>
															<option value="700" <?php selected( $bdp_sale_tag_font_weight, 700 ); ?>>700</option>
															<option value="800" <?php selected( $bdp_sale_tag_font_weight, 800 ); ?>>800</option>
															<option value="900" <?php selected( $bdp_sale_tag_font_weight, 900 ); ?>>900</option>
															<option value="bold" <?php selected( $bdp_sale_tag_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
															<option value="normal" <?php selected( $bdp_sale_tag_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag line height', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
														<input type="number" name="bdp_sale_tag_font_line_height" id="bdp_sale_tag_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['bdp_sale_tag_font_line_height'] ) ? esc_attr( $bdp_settings['bdp_sale_tag_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)" >
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display sale tag italic font style', 'blog-designer-pro' ); ?></span></span><?php $bdp_sale_tag_font_italic = isset( $bdp_settings['bdp_sale_tag_font_italic'] ) ? $bdp_settings['bdp_sale_tag_font_italic'] : '0'; ?>
											</div>
											<div class="bdp-typography-content">
												<fieldset class="bdp-social-options bdp-display_author buttonset">
													<input id="bdp_sale_tag_font_italic_1" name="bdp_sale_tag_font_italic" type="radio" value="1"  <?php checked( 1, $bdp_sale_tag_font_italic ); ?> />
													<label for="bdp_sale_tag_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="bdp_sale_tag_font_italic_0" name="bdp_sale_tag_font_italic" type="radio" value="0" <?php checked( 0, $bdp_sale_tag_font_italic ); ?> />
													<label for="bdp_sale_tag_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag text transform style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_sale_tag_font_text_transform = isset( $bdp_settings['bdp_sale_tag_font_text_transform'] ) ? $bdp_settings['bdp_sale_tag_font_text_transform'] : 'none'; ?>
													<div class="select-cover">
														<select name="bdp_sale_tag_font_text_transform" id="bdp_sale_tag_font_text_transform">
															<option <?php selected( $bdp_sale_tag_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_sale_tag_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_sale_tag_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_sale_tag_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_sale_tag_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
											</div>
										</div>

										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sale tag text decoration style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_sale_tag_font_text_decoration = isset( $bdp_settings['bdp_sale_tag_font_text_decoration'] ) ? $bdp_settings['bdp_sale_tag_font_text_decoration'] : 'none'; ?>
												<div class="select-cover">
													<select name="bdp_sale_tag_font_text_decoration" id="bdp_sale_tag_font_text_decoration">
														<option <?php selected( $bdp_sale_tag_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_sale_tag_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_sale_tag_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_sale_tag_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
													</select>
												</div>
											</div>
										</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter sale tag letter spacing', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
														<input type="number" name="bdp_sale_tag_font_letter_spacing" id="bdp_sale_tag_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_sale_tag_font_letter_spacing'] ) ? esc_attr( $bdp_settings['bdp_sale_tag_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Product Rating', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable product rating', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_product_rating = '0';
									if ( isset( $bdp_settings['display_product_rating'] ) ) {
										$display_product_rating = $bdp_settings['display_product_rating'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_product_rating_1" name="display_product_rating" type="radio" value="1" <?php checked( 1, $display_product_rating ); ?> />
										<label id="bdp-options-button" for="display_product_rating_1" <?php checked( 1, $display_product_rating ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_product_rating_0" name="display_product_rating" type="radio" value="0" <?php checked( 0, $display_product_rating ); ?> />
										<label id="bdp-options-button" for="display_product_rating_0" <?php checked( 1, $display_product_rating ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_star_rating_setting">
								<?php esc_html_e( 'Star Rating Settings', 'blog-designer-pro' ); ?>
							</h3>
							<li class="bdp_star_rating_setting">
								<ul>
									<li class="bdp_star_rating_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Star Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select star rating color', 'blog-designer-pro' ); ?></span></span>
											<?php

											$bdp_star_rating_color = ( isset( $bdp_settings['bdp_star_rating_color'] ) && '' != $bdp_settings['bdp_star_rating_color'] ) ? $bdp_settings['bdp_star_rating_color'] : ''; //phpcs:ignore
											?>
											<input type="text" name="bdp_star_rating_color" id="bdp_star_rating_color" value="<?php echo esc_attr( $bdp_star_rating_color ); ?>"/>
										</div>
									</li>
									<li class="bdp_star_rating_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select star background color', 'blog-designer-pro' ); ?></span></span>
											<?php

											$bdp_star_rating_bg_color = ( isset( $bdp_settings['bdp_star_rating_bg_color'] ) && '' != $bdp_settings['bdp_star_rating_bg_color'] ) ? $bdp_settings['bdp_star_rating_bg_color'] : ''; //phpcs:ignore
											?>
											<input type="text" name="bdp_star_rating_bg_color" id="bdp_star_rating_bg_color" value="<?php echo esc_attr( $bdp_star_rating_bg_color ); ?>"/>
										</div>
									</li>
									<li class="bdp_star_rating_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon "><span class="bdp-tooltips"><?php esc_html_e( 'Select star rating alignment', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_star_rating_alignment = 'left';
											if ( isset( $bdp_settings['bdp_star_rating_alignment'] ) ) {
												$bdp_star_rating_alignment = $bdp_settings['bdp_star_rating_alignment'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_star_rating_alignment_left" name="bdp_star_rating_alignment" type="radio" value="left" <?php checked( 'left', $bdp_star_rating_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_star_rating_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></label>
												<input id="bdp_star_rating_alignment_center" name="bdp_star_rating_alignment" type="radio" value="center" <?php checked( 'center', $bdp_star_rating_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_star_rating_alignment_center" class="bdp_star_rating_alignment_center"><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></label>
												<input id="bdp_star_rating_alignment_right" name="bdp_star_rating_alignment" type="radio" value="right" <?php checked( 'right', $bdp_star_rating_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_star_rating_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</li>
									<h3 class="bdp_star_rating_tr bdp-table-title"><?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?></h3>
									<li class="bdp_star_rating_tr">
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_paddingleft = isset( $bdp_settings['bdp_star_rating_paddingleft'] ) ? $bdp_settings['bdp_star_rating_paddingleft'] : '0'; ?>
													<input type="number" id="bdp_star_rating_paddingleft" name="bdp_star_rating_paddingleft" step="1" min="0" value="<?php echo esc_attr( $bdp_star_rating_paddingleft ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_paddingright = isset( $bdp_settings['bdp_star_rating_paddingright'] ) ? $bdp_settings['bdp_star_rating_paddingright'] : '0'; ?>
													<input type="number" id="bdp_star_rating_paddingright" name="bdp_star_rating_paddingright" step="1" min="0" value="<?php echo esc_attr( $bdp_star_rating_paddingright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_paddingtop = isset( $bdp_settings['bdp_star_rating_paddingtop'] ) ? $bdp_settings['bdp_star_rating_paddingtop'] : '0'; ?>
													<input type="number" id="bdp_star_rating_paddingtop" name="bdp_star_rating_paddingtop" step="1" min="0" value="<?php echo esc_attr( $bdp_star_rating_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_paddingbottom = isset( $bdp_settings['bdp_star_rating_paddingbottom'] ) ? $bdp_settings['bdp_star_rating_paddingbottom'] : '0'; ?>
													<input type="number" id="bdp_star_rating_paddingbottom" name="bdp_star_rating_paddingbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_star_rating_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
									<h3 class="bdp_star_rating_tr bdp-table-title"><?php esc_html_e( 'Margin', 'blog-designer-pro' ); ?></h3>
									<li class="bdp_star_rating_tr">
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_marginleft = isset( $bdp_settings['bdp_star_rating_marginleft'] ) ? $bdp_settings['bdp_star_rating_marginleft'] : '0'; ?>
													<input type="number" id="bdp_star_rating_marginleft" name="bdp_star_rating_marginleft" step="1" value="<?php echo esc_attr( $bdp_star_rating_marginleft ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_marginright = isset( $bdp_settings['bdp_star_rating_marginright'] ) ? $bdp_settings['bdp_star_rating_marginright'] : '0'; ?>
													<input type="number" id="bdp_star_rating_marginright" name="bdp_star_rating_marginright" step="1" value="<?php echo esc_attr( $bdp_star_rating_marginright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_margintop = isset( $bdp_settings['bdp_star_rating_margintop'] ) ? $bdp_settings['bdp_star_rating_margintop'] : '0'; ?>
													<input type="number" id="bdp_star_rating_margintop" name="bdp_star_rating_margintop" step="1" value="<?php echo esc_attr( $bdp_star_rating_margintop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_star_rating_marginbottom = isset( $bdp_settings['bdp_star_rating_marginbottom'] ) ? $bdp_settings['bdp_star_rating_marginbottom'] : '0'; ?>
													<input type="number" id="bdp_star_rating_marginbottom" name="bdp_star_rating_marginbottom" step="1" value="<?php echo esc_attr( $bdp_star_rating_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Price', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable price', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_product_price = '1';
									if ( isset( $bdp_settings['display_product_price'] ) ) {
										$display_product_price = $bdp_settings['display_product_price'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_product_price_1" name="display_product_price" type="radio" value="1" <?php checked( 1, $display_product_price ); ?> />
										<label id="bdp-options-button" for="display_product_price_1" <?php checked( 1, $display_product_price ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_product_price_0" name="display_product_price" type="radio" value="0" <?php checked( 0, $display_product_price ); ?> />
										<label id="bdp-options-button" for="display_product_price_0" <?php checked( 1, $display_product_price ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_price_setting">
								<?php esc_html_e( 'Price Settings', 'blog-designer-pro' ); ?>
							</h3>
							<li class="bdp_price_setting">
								<ul>
									<li class="bdp_pricetext_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text color', 'blog-designer-pro' ); ?></span></span>
											<?php

											$bdp_pricetextcolor = ( isset( $bdp_settings['bdp_pricetextcolor'] ) && '' != $bdp_settings['bdp_pricetextcolor'] ) ? $bdp_settings['bdp_pricetextcolor'] : ''; //phpcs:ignore
											?>
											<input type="text" name="bdp_pricetextcolor" id="bdp_pricetextcolor" value="<?php echo esc_attr( $bdp_pricetextcolor ); ?>"/>
										</div>
									</li>
									<li class="bdp_pricetext_tr bdp_pricetext_alignment_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text alignment', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_pricetext_alignment = 'left';
											if ( isset( $bdp_settings['bdp_pricetext_alignment'] ) ) {
												$bdp_pricetext_alignment = $bdp_settings['bdp_pricetext_alignment'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_pricetext_alignment_left" name="bdp_pricetext_alignment" type="radio" value="left" <?php checked( 'left', $bdp_pricetext_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_pricetext_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></label>
												<input id="bdp_pricetext_alignment_center" name="bdp_pricetext_alignment" type="radio" value="center" <?php checked( 'center', $bdp_pricetext_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_pricetext_alignment_center" class="bdp_pricetext_alignment_center"><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></label>
												<input id="bdp_pricetext_alignment_right" name="bdp_pricetext_alignment" type="radio" value="right" <?php checked( 'right', $bdp_pricetext_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_pricetext_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</li>
									<h3 class="bdp_pricetext_padding_setting_tr bdp-table-title"><?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?></h3>
									<li class="bdp_pricetext_padding_setting_tr">
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_paddingleft = isset( $bdp_settings['bdp_pricetext_paddingleft'] ) ? $bdp_settings['bdp_pricetext_paddingleft'] : '0'; ?>
													<input type="number" id="bdp_pricetext_paddingleft" name="bdp_pricetext_paddingleft" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_paddingleft ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_paddingright = isset( $bdp_settings['bdp_pricetext_paddingright'] ) ? $bdp_settings['bdp_pricetext_paddingright'] : '0'; ?>
													<input type="number" id="bdp_pricetext_paddingright" name="bdp_pricetext_paddingright" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_paddingright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_paddingtop = isset( $bdp_settings['bdp_pricetext_paddingtop'] ) ? $bdp_settings['bdp_pricetext_paddingtop'] : '0'; ?>
													<input type="number" id="bdp_pricetext_paddingtop" name="bdp_pricetext_paddingtop" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_paddingtop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_paddingbottom = isset( $bdp_settings['bdp_pricetext_paddingbottom'] ) ? $bdp_settings['bdp_pricetext_paddingbottom'] : '0'; ?>
													<input type="number" id="bdp_pricetext_paddingbottom" name="bdp_pricetext_paddingbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_paddingbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
									<h3 class="bdp_pricetext_tr bdp_pricetext_marging_setting_tr bdp-table-title"><?php esc_html_e( 'Margin', 'blog-designer-pro' ); ?></h3>
									<li class="bdp_pricetext_tr bdp_pricetext_marging_setting_tr">
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_marginleft = isset( $bdp_settings['bdp_pricetext_marginleft'] ) ? $bdp_settings['bdp_pricetext_marginleft'] : '0'; ?>
													<input type="number" id="bdp_pricetext_marginleft" name="bdp_pricetext_marginleft" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_marginleft ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_marginright = isset( $bdp_settings['bdp_pricetext_marginright'] ) ? $bdp_settings['bdp_pricetext_marginright'] : '0'; ?>
													<input type="number" id="bdp_pricetext_marginright" name="bdp_pricetext_marginright" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_marginright ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_margintop = isset( $bdp_settings['bdp_pricetext_margintop'] ) ? $bdp_settings['bdp_pricetext_margintop'] : '0'; ?>
													<input type="number" id="bdp_pricetext_margintop" name="bdp_pricetext_margintop" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_margintop ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Bottom (px)', 'blog-designer-pro' ); ?>
													</span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_pricetext_marginbottom = isset( $bdp_settings['bdp_pricetext_marginbottom'] ) ? $bdp_settings['bdp_pricetext_marginbottom'] : '0'; ?>
													<input type="number" id="bdp_pricetext_marginbottom" name="bdp_pricetext_marginbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_pricetext_marginbottom ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_pricetext_tr bdp_pricetext_typography_setting_tr">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-wrapper bdp-typography-wrapper1">
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title"><?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?></span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text font family', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_pricefontface'] ) && '' != $bdp_settings['bdp_pricefontface'] ) { //phpcs:ignore
														$bdp_pricefontface = $bdp_settings['bdp_pricefontface'];
													} else {
														$bdp_pricefontface = '';
													}
													?>
													<div class="typo-field">
														<input type="hidden" id="bdp_pricefontface_font_type" name="bdp_pricefontface_font_type" value="<?php echo isset( $bdp_settings['bdp_pricefontface_font_type'] ) ? esc_attr( $bdp_settings['bdp_pricefontface_font_type'] ) : ''; ?>">
														<div class="select-cover">
															<select name="bdp_pricefontface" id="bdp_pricefontface">
																<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
																<?php
																$old_version = '';
																$cnt         = 0;
																foreach ( $font_family as $key => $value ) {
																	if ( $value['version'] != $old_version ) { //phpcs:ignore
																		if ( $cnt > 0 ) {
																			echo '</optgroup>';
																		}
																		echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																		$old_version = $value['version'];
																	}
																	echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";
																	if ( '' != $bdp_pricefontface && ( str_replace( '"', '', $bdp_pricefontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
																		echo ' selected';
																	}
																	echo '>' . esc_html( $value['label'] ) . '</option>';
																	$cnt++;
																}
																if ( $cnt == count( $font_family ) ) { //phpcs:ignore
																	echo '</optgroup>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Size (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text font size', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_pricefontsize'] ) && '' != $bdp_settings['bdp_pricefontsize'] ) { //phpcs:ignore
														$bdp_pricefontsize = $bdp_settings['bdp_pricefontsize'];
													} else {
														$bdp_pricefontsize = 14;
													}
													?>
													<div class="grid_col_space range_slider_fontsize" id="bdp_pricefontsizeInput" ></div>
													<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="bdp_pricefontsize" id="bdp_pricefontsize" value="<?php echo esc_attr( $bdp_pricefontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text font weight', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_price_font_weight = isset( $bdp_settings['bdp_price_font_weight'] ) ? $bdp_settings['bdp_price_font_weight'] : 'normal'; ?>
													<div class="select-cover">
														<select name="bdp_price_font_weight" id="bdp_price_font_weight">
															<option value="100" <?php selected( $bdp_price_font_weight, 100 ); ?>>100</option>
															<option value="200" <?php selected( $bdp_price_font_weight, 200 ); ?>>200</option>
															<option value="300" <?php selected( $bdp_price_font_weight, 300 ); ?>>300</option>
															<option value="400" <?php selected( $bdp_price_font_weight, 400 ); ?>>400</option>
															<option value="500" <?php selected( $bdp_price_font_weight, 500 ); ?>>500</option>
															<option value="600" <?php selected( $bdp_price_font_weight, 600 ); ?>>600</option>
															<option value="700" <?php selected( $bdp_price_font_weight, 700 ); ?>>700</option>
															<option value="800" <?php selected( $bdp_price_font_weight, 800 ); ?>>800</option>
															<option value="900" <?php selected( $bdp_price_font_weight, 900 ); ?>>900</option>
															<option value="bold" <?php selected( $bdp_price_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
															<option value="normal" <?php selected( $bdp_price_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text line height', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
														<input type="number" name="bdp_price_font_line_height" id="bdp_price_font_line_height" step="0.1" min="0" value="<?php echo isset( $bdp_settings['bdp_price_font_line_height'] ) ? esc_attr( $bdp_settings['bdp_price_font_line_height'] ) : '1.5'; ?>" onkeypress="return isNumberKey(event)" >
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display price text italic font style', 'blog-designer-pro' ); ?></span></span><?php $bdp_price_font_italic = isset( $bdp_settings['bdp_price_font_italic'] ) ? $bdp_settings['bdp_price_font_italic'] : '0'; ?>
											</div>
											<div class="bdp-typography-content">
												<fieldset class="bdp-social-options bdp-display_author buttonset">
													<input id="bdp_price_font_italic_1" name="bdp_price_font_italic" type="radio" value="1"  <?php checked( 1, $bdp_price_font_italic ); ?> />
													<label for="bdp_price_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="bdp_price_font_italic_0" name="bdp_price_font_italic" type="radio" value="0" <?php checked( 0, $bdp_price_font_italic ); ?> />
													<label for="bdp_price_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text transform style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_price_font_text_transform = isset( $bdp_settings['bdp_price_font_text_transform'] ) ? $bdp_settings['bdp_price_font_text_transform'] : 'none'; ?>
													<div class="select-cover">
														<select name="bdp_price_font_text_transform" id="bdp_price_font_text_transform">
															<option <?php selected( $bdp_price_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_price_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_price_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_price_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_price_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
											</div>
										</div>

										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select price text decoration style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_price_font_text_decoration = isset( $bdp_settings['bdp_price_font_text_decoration'] ) ? $bdp_settings['bdp_price_font_text_decoration'] : 'none'; ?>
												<div class="select-cover">
													<select name="bdp_price_font_text_decoration" id="bdp_price_font_text_decoration">
														<option <?php selected( $bdp_price_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_price_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_price_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_price_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
													</select>
												</div>
											</div>
										</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter price text letter spacing', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
														<input type="number" name="bdp_price_font_letter_spacing" id="bdp_price_font_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_price_font_letter_spacing'] ) ? esc_attr( $bdp_settings['bdp_price_font_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Add To Cart Button', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable add to cart button', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_addtocart_button = '1';
									if ( isset( $bdp_settings['display_addtocart_button'] ) ) {
										$display_addtocart_button = $bdp_settings['display_addtocart_button'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_addtocart_button_1" name="display_addtocart_button" type="radio" value="1" <?php checked( 1, $display_addtocart_button ); ?> />
										<label id="bdp-options-button" for="display_addtocart_button_1" <?php checked( 1, $display_addtocart_button ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_addtocart_button_0" name="display_addtocart_button" type="radio" value="0" <?php checked( 0, $display_addtocart_button ); ?> />
										<label id="bdp-options-button" for="display_addtocart_button_0" <?php checked( 0, $display_addtocart_button ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_cart_button_setting">
								<?php esc_html_e( 'Cart Button Settings', 'blog-designer-pro' ); ?>
							</h3>

							<li class="bdp_cart_button_setting">
								<ul>
									<li class="bdp_add_to_cart_tr cart_button_alignment">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button alignment', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_addtocartbutton_alignment = 'left';
											if ( isset( $bdp_settings['bdp_addtocartbutton_alignment'] ) ) {
												$bdp_addtocartbutton_alignment = $bdp_settings['bdp_addtocartbutton_alignment'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_addtocartbutton_alignment_left" name="bdp_addtocartbutton_alignment" type="radio" value="left" <?php checked( 'left', $bdp_addtocartbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_addtocartbutton_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></label>
												<input id="bdp_addtocartbutton_alignment_center" name="bdp_addtocartbutton_alignment" type="radio" value="center" <?php checked( 'center', $bdp_addtocartbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_addtocartbutton_alignment_center" class="bdp_addtocartbutton_alignment_center"><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></label>
												<input id="bdp_addtocartbutton_alignment_right" name="bdp_addtocartbutton_alignment" type="radio" value="right" <?php checked( 'right', $bdp_addtocartbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_addtocartbutton_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</li>
									<li class="bdp_add_to_cart_tr bdp_padding_0">
										<div class="bdp-typography-wrapper bdp-typography-wrapper1">
											<div class="bdp-typography-cover">
												<h3 class="bdp-table-title"><?php esc_html_e( 'Normal Settings', 'blog-designer-pro' ); ?></h3>
												<div class="bdp-typography-sub-cover bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_addtocart_textcolor = isset( $bdp_settings['bdp_addtocart_textcolor'] ) ? $bdp_settings['bdp_addtocart_textcolor'] : ''; ?>
														<input type="text" name="bdp_addtocart_textcolor" id="bdp_addtocart_textcolor" value="<?php echo esc_attr( $bdp_addtocart_textcolor ); ?>"/>
														
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_addtocart_backgroundcolor = isset( $bdp_settings['bdp_addtocart_backgroundcolor'] ) ? $bdp_settings['bdp_addtocart_backgroundcolor'] : ''; ?>
														<input type="text" name="bdp_addtocart_backgroundcolor" id="bdp_addtocart_backgroundcolor" value="<?php echo esc_attr( $bdp_addtocart_backgroundcolor ); ?>"/>
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Border Radius(px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php
														$display_addtocart_button_border_radius = '0';
														if ( isset( $bdp_settings['display_addtocart_button_border_radius'] ) ) {
															$display_addtocart_button_border_radius = $bdp_settings['display_addtocart_button_border_radius'];
														}
														?>
														<input type="number" id="display_addtocart_button_border_radius" name="display_addtocart_button_border_radius" step="1" min="0" value="<?php echo esc_attr( $display_addtocart_button_border_radius ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-typography-sub-cover full-width bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Border', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<div class="bdp-border-wrap">
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php       $bdp_addtocartbutton_borderleft = isset( $bdp_settings['bdp_addtocartbutton_borderleft'] ) ? $bdp_settings['bdp_addtocartbutton_borderleft'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_borderleft" name="bdp_addtocartbutton_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_borderleftcolor = isset( $bdp_settings['bdp_addtocartbutton_borderleftcolor'] ) ? $bdp_settings['bdp_addtocartbutton_borderleftcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_borderleftcolor" id="bdp_addtocartbutton_borderleftcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_borderleftcolor ); ?>"/>
																	</div>
																</div>
															</div> 
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_borderright = isset( $bdp_settings['bdp_addtocartbutton_borderright'] ) ? $bdp_settings['bdp_addtocartbutton_borderright'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_borderright" name="bdp_addtocartbutton_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_borderright ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_borderrightcolor = isset( $bdp_settings['bdp_addtocartbutton_borderrightcolor'] ) ? $bdp_settings['bdp_addtocartbutton_borderrightcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_borderrightcolor" id="bdp_addtocartbutton_borderrightcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_borderrightcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_bordertop = isset( $bdp_settings['bdp_addtocartbutton_bordertop'] ) ? $bdp_settings['bdp_addtocartbutton_bordertop'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_bordertop" name="bdp_addtocartbutton_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_bordertopcolor = isset( $bdp_settings['bdp_addtocartbutton_bordertopcolor'] ) ? $bdp_settings['bdp_addtocartbutton_bordertopcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_bordertopcolor" id="bdp_addtocartbutton_bordertopcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_bordertopcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Bottom(px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_borderbuttom = isset( $bdp_settings['bdp_addtocartbutton_borderbuttom'] ) ? $bdp_settings['bdp_addtocartbutton_borderbuttom'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_borderbuttom" name="bdp_addtocartbutton_borderbuttom" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_borderbuttom ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php   $bdp_addtocartbutton_borderbottomcolor = isset( $bdp_settings['bdp_addtocartbutton_borderbottomcolor'] ) ? $bdp_settings['bdp_addtocartbutton_borderbottomcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_borderbottomcolor" id="bdp_addtocartbutton_borderbottomcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_borderbottomcolor ); ?>"/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
												<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Settings', 'blog-designer-pro' ); ?></h3>
												<div class="bdp-typography-sub-cover bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_addtocart_text_hover_color = isset( $bdp_settings['bdp_addtocart_text_hover_color'] ) ? $bdp_settings['bdp_addtocart_text_hover_color'] : ''; ?>
														<input type="text" name="bdp_addtocart_text_hover_color" id="bdp_addtocart_text_hover_color" value="<?php echo esc_attr( $bdp_addtocart_text_hover_color ); ?>"/>
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_addtocart_hover_backgroundcolor = isset( $bdp_settings['bdp_addtocart_hover_backgroundcolor'] ) ? $bdp_settings['bdp_addtocart_hover_backgroundcolor'] : ''; ?>
														<input type="text" name="bdp_addtocart_hover_backgroundcolor" id="bdp_addtocart_hover_backgroundcolor" value="<?php echo esc_attr( $bdp_addtocart_hover_backgroundcolor ); ?>"/>
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Border Radius(px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php
														$display_addtocart_button_border_hover_radius = '0';
														if ( isset( $bdp_settings['display_addtocart_button_border_hover_radius'] ) ) {
															$display_addtocart_button_border_hover_radius = $bdp_settings['display_addtocart_button_border_hover_radius'];
														}
														?>
														<input type="number" id="display_addtocart_button_border_hover_radius" name="display_addtocart_button_border_hover_radius" step="1" min="0" value="<?php echo esc_attr( $display_addtocart_button_border_hover_radius ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-typography-sub-cover full-width bdp_add_to_cart_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Border', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<div class="bdp-border-wrap">
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_borderleft = isset( $bdp_settings['bdp_addtocartbutton_hover_borderleft'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderleft'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_hover_borderleft" name="bdp_addtocartbutton_hover_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_borderleftcolor = isset( $bdp_settings['bdp_addtocartbutton_hover_borderleftcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderleftcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_hover_borderleftcolor" id="bdp_addtocartbutton_hover_borderleftcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_borderleftcolor ); ?>"/>
																	</div>
																</div>
															</div> 
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_borderright = isset( $bdp_settings['bdp_addtocartbutton_hover_borderright'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderright'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_hover_borderright" name="bdp_addtocartbutton_hover_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_borderright ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_borderrightcolor = isset( $bdp_settings['bdp_addtocartbutton_hover_borderrightcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderrightcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_hover_borderrightcolor" id="bdp_addtocartbutton_hover_borderrightcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_borderrightcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_bordertop = isset( $bdp_settings['bdp_addtocartbutton_hover_bordertop'] ) ? $bdp_settings['bdp_addtocartbutton_hover_bordertop'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_hover_bordertop" name="bdp_addtocartbutton_hover_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_bordertopcolor = isset( $bdp_settings['bdp_addtocartbutton_hover_bordertopcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_bordertopcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_hover_bordertopcolor" id="bdp_addtocartbutton_hover_bordertopcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_bordertopcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Bottom(px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_borderbuttom = isset( $bdp_settings['bdp_addtocartbutton_hover_borderbuttom'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderbuttom'] : '0'; ?>
																		<input type="number" id="bdp_addtocartbutton_hover_borderbuttom" name="bdp_addtocartbutton_hover_borderbuttom" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_borderbuttom ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_addtocartbutton_hover_borderbottomcolor = isset( $bdp_settings['bdp_addtocartbutton_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_addtocartbutton_hover_borderbottomcolor'] : ''; ?>
																		<input type="text" name="bdp_addtocartbutton_hover_borderbottomcolor" id="bdp_addtocartbutton_hover_borderbottomcolor" value="<?php echo esc_attr( $bdp_addtocartbutton_hover_borderbottomcolor ); ?>"/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_cart_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?>
											</span>
										</div> 
										<div class="bdp-right bdp-border-cover">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Set cart button padding', 'blog-designer-pro' ); ?></span></span>
											<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Left-Right (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_addtocartbutton_padding_leftright = isset( $bdp_settings['bdp_addtocartbutton_padding_leftright'] ) ? $bdp_settings['bdp_addtocartbutton_padding_leftright'] : '0'; ?>
														<input type="number" id="bdp_addtocartbutton_padding_leftright" name="bdp_addtocartbutton_padding_leftright" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_padding_leftright ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Top-Bottom (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_addtocartbutton_padding_topbottom = isset( $bdp_settings['bdp_addtocartbutton_padding_topbottom'] ) ? $bdp_settings['bdp_addtocartbutton_padding_topbottom'] : '0'; ?>
														<input type="number" id="bdp_addtocartbutton_padding_topbottom" name="bdp_addtocartbutton_padding_topbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocartbutton_padding_topbottom ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_cart_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Margin', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right bdp-border-cover">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Set cart button margin', 'blog-designer-pro' ); ?></span></span>
											<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Left-Right (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_addtocartbutton_margin_leftright = isset( $bdp_settings['bdp_addtocartbutton_margin_leftright'] ) ? $bdp_settings['bdp_addtocartbutton_margin_leftright'] : '0'; ?>
														<input type="number" id="bdp_addtocartbutton_margin_leftright" name="bdp_addtocartbutton_margin_leftright" step="1" value="<?php echo esc_attr( $bdp_addtocartbutton_margin_leftright ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Top-Bottom (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_addtocartbutton_margin_topbottom = isset( $bdp_settings['bdp_addtocartbutton_margin_topbottom'] ) ? $bdp_settings['bdp_addtocartbutton_margin_topbottom'] : '0'; ?>
														<input type="number" id="bdp_addtocartbutton_margin_topbottom" name="bdp_addtocartbutton_margin_topbottom" step="1" value="<?php echo esc_attr( $bdp_addtocartbutton_margin_topbottom ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_cart_tr addtocart_button_box_shadow_setting">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'H-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select horizontal offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_top_box_shadow = isset( $bdp_settings['bdp_addtocart_button_top_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_top_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_top_box_shadow" name="bdp_addtocart_button_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'V-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select vertical offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_right_box_shadow = isset( $bdp_settings['bdp_addtocart_button_right_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_right_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_right_box_shadow" name="bdp_addtocart_button_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Blur (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select blur value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_bottom_box_shadow = isset( $bdp_settings['bdp_addtocart_button_bottom_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_bottom_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_bottom_box_shadow" name="bdp_addtocart_button_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Spread (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select spread value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_left_box_shadow = isset( $bdp_settings['bdp_addtocart_button_left_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_left_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_left_box_shadow" name="bdp_addtocart_button_left_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_left_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover bdp-boxshadow-color">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select box shadow color', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
														<?php $bdp_addtocart_button_box_shadow_color = isset( $bdp_settings['bdp_addtocart_button_box_shadow_color'] ) ? $bdp_settings['bdp_addtocart_button_box_shadow_color'] : ''; ?>
														<input type="text" name="bdp_addtocart_button_box_shadow_color" id="bdp_addtocart_button_box_shadow_color" value="<?php echo esc_attr( $bdp_addtocart_button_box_shadow_color ); ?>"/>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_cart_tr addtocart_button_hover_box_shadow_setting">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'H-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select horizontal offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_hover_top_box_shadow = isset( $bdp_settings['bdp_addtocart_button_hover_top_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_top_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_hover_top_box_shadow" name="bdp_addtocart_button_hover_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_hover_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'V-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select vertical offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_hover_right_box_shadow = isset( $bdp_settings['bdp_addtocart_button_hover_right_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_right_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_hover_right_box_shadow" name="bdp_addtocart_button_hover_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_hover_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Blur (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select blur value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_hover_bottom_box_shadow = isset( $bdp_settings['bdp_addtocart_button_hover_bottom_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_bottom_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_hover_bottom_box_shadow" name="bdp_addtocart_button_hover_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_hover_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Spread (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select spread value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_hover_left_box_shadow = isset( $bdp_settings['bdp_addtocart_button_hover_left_box_shadow'] ) ? $bdp_settings['bdp_addtocart_button_hover_left_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_addtocart_button_hover_left_box_shadow" name="bdp_addtocart_button_hover_left_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_addtocart_button_hover_left_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover bdp-boxshadow-color">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select box shadow color', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_addtocart_button_hover_box_shadow_color = isset( $bdp_settings['bdp_addtocart_button_hover_box_shadow_color'] ) ? $bdp_settings['bdp_addtocart_button_hover_box_shadow_color'] : ''; ?>
													<input type="text" name="bdp_addtocart_button_hover_box_shadow_color" id="bdp_addtocart_button_hover_box_shadow_color" value="<?php echo esc_attr( $bdp_addtocart_button_hover_box_shadow_color ); ?>"/>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_cart_tr">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-wrapper bdp-typography-wrapper1">
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button font family', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_addtocart_button_fontface'] ) && '' != $bdp_settings['bdp_addtocart_button_fontface'] ) { //phpcs:ignore
														$bdp_addtocart_button_fontface = $bdp_settings['bdp_addtocart_button_fontface'];
													} else {
														$bdp_addtocart_button_fontface = '';
													}
													?>
													<div class="typo-field">
														<input type="hidden" id="bdp_addtocart_button_fontface_font_type" name="bdp_addtocart_button_fontface_font_type" value="<?php echo isset( $bdp_settings['bdp_addtocart_button_fontface_font_type'] ) ? esc_attr( $bdp_settings['bdp_addtocart_button_fontface_font_type'] ) : ''; ?>">
														<div class="select-cover">
															<select name="bdp_addtocart_button_fontface" id="bdp_addtocart_button_fontface">
																<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
																<?php
																$old_version = '';
																$cnt         = 0;
																foreach ( $font_family as $key => $value ) {
																	if ( $value['version'] != $old_version ) { //phpcs:ignore
																		if ( $cnt > 0 ) {
																			echo '</optgroup>';
																		}
																		echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																		$old_version = $value['version'];
																	}
																	echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";
																	if ( '' != $bdp_addtocart_button_fontface && ( str_replace( '"', '', $bdp_addtocart_button_fontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
																		echo ' selected';
																	}
																	echo '>' . esc_html( $value['label'] ) . '</option>';
																	$cnt++;
																}
																if ( $cnt == count( $font_family ) ) { //phpcs:ignore
																	echo '</optgroup>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Size (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button font size', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_addtocart_button_fontsize'] ) && '' != $bdp_settings['bdp_addtocart_button_fontsize'] ) { //phpcs:ignore
														$bdp_addtocart_button_fontsize = $bdp_settings['bdp_addtocart_button_fontsize'];
													} else {
														$bdp_addtocart_button_fontsize = 14;
													}
													?>
													<div class="grid_col_space range_slider_fontsize" id="bdp_addtocart_button_fontsizeInput" ></div>
													<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="bdp_addtocart_button_fontsize" id="bdp_addtocart_button_fontsize" value="<?php echo esc_attr( $bdp_addtocart_button_fontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button font weight', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_addtocart_button_font_weight = isset( $bdp_settings['bdp_addtocart_button_font_weight'] ) ? $bdp_settings['bdp_addtocart_button_font_weight'] : 'normal'; ?>
													<div class="select-cover">
														<select name="bdp_addtocart_button_font_weight" id="bdp_addtocart_button_font_weight">
															<option value="100" <?php selected( $bdp_addtocart_button_font_weight, 100 ); ?>>100</option>
															<option value="200" <?php selected( $bdp_addtocart_button_font_weight, 200 ); ?>>200</option>
															<option value="300" <?php selected( $bdp_addtocart_button_font_weight, 300 ); ?>>300</option>
															<option value="400" <?php selected( $bdp_addtocart_button_font_weight, 400 ); ?>>400</option>
															<option value="500" <?php selected( $bdp_addtocart_button_font_weight, 500 ); ?>>500</option>
															<option value="600" <?php selected( $bdp_addtocart_button_font_weight, 600 ); ?>>600</option>
															<option value="700" <?php selected( $bdp_addtocart_button_font_weight, 700 ); ?>>700</option>
															<option value="800" <?php selected( $bdp_addtocart_button_font_weight, 800 ); ?>>800</option>
															<option value="900" <?php selected( $bdp_addtocart_button_font_weight, 900 ); ?>>900</option>
															<option value="bold" <?php selected( $bdp_addtocart_button_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
															<option value="normal" <?php selected( $bdp_addtocart_button_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button line height', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
													<?php
														$display_addtocart_button_line_height = '1.5';
													if ( isset( $bdp_settings['display_addtocart_button_line_height'] ) ) {
														$display_addtocart_button_line_height = $bdp_settings['display_addtocart_button_line_height'];
													}
													?>
													<input type="number" id="display_addtocart_button_line_height" name="display_addtocart_button_line_height" step="1" min="1.5" value="<?php echo esc_attr( $display_addtocart_button_line_height ); ?>" placeholder="<?php esc_attr_e( 'Enter line height', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display cart button italic font style', 'blog-designer-pro' ); ?></span></span><?php $bdp_addtocart_button_font_italic = isset( $bdp_settings['bdp_addtocart_button_font_italic'] ) ? $bdp_settings['bdp_addtocart_button_font_italic'] : '0'; ?>
											</div>
											<div class="bdp-typography-content">
												<fieldset class="bdp-social-options bdp-display_author buttonset">
													<input id="bdp_addtocart_button_font_italic_1" name="bdp_addtocart_button_font_italic" type="radio" value="1"  <?php checked( 1, $bdp_addtocart_button_font_italic ); ?> />
													<label for="bdp_addtocart_button_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="bdp_addtocart_button_font_italic_0" name="bdp_addtocart_button_font_italic" type="radio" value="0" <?php checked( 0, $bdp_addtocart_button_font_italic ); ?> />
													<label for="bdp_addtocart_button_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button text transform style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_addtocart_button_font_text_transform = isset( $bdp_settings['bdp_addtocart_button_font_text_transform'] ) ? $bdp_settings['bdp_addtocart_button_font_text_transform'] : 'none'; ?>
													<div class="select-cover">
														<select name="bdp_addtocart_button_font_text_transform" id="bdp_addtocart_button_font_text_transform">
															<option <?php selected( $bdp_addtocart_button_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtocart_button_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtocart_button_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtocart_button_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtocart_button_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
											</div>
										</div>

										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button text decoration style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_addtocart_button_font_text_decoration = isset( $bdp_settings['bdp_addtocart_button_font_text_decoration'] ) ? $bdp_settings['bdp_addtocart_button_font_text_decoration'] : 'none'; ?>
												<div class="select-cover">
													<select name="bdp_addtocart_button_font_text_decoration" id="bdp_addtocart_button_font_text_decoration">
														<option <?php selected( $bdp_addtocart_button_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_addtocart_button_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_addtocart_button_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_addtocart_button_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
													</select>
												</div>
											</div>
										</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter cart button letter spacing', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number"><input type="number" name="bdp_addtocart_button_letter_spacing" id="bdp_addtocart_button_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_addtocart_button_letter_spacing'] ) ? esc_attr( $bdp_settings['bdp_addtocart_button_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)"></div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<?php
							if ( class_exists( 'YITH_WCWL' ) ) {
								?>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Add To Wishlist Button', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable add to wishlist button', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_addtowishlist_button = '0';
									if ( isset( $bdp_settings['display_addtowishlist_button'] ) ) {
										$display_addtowishlist_button = $bdp_settings['display_addtowishlist_button'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_addtowishlist_button_1" name="display_addtowishlist_button" type="radio" value="1" <?php checked( 1, $display_addtowishlist_button ); ?> />
										<label id="bdp-options-button" for="display_addtowishlist_button_1" <?php checked( 1, $display_addtowishlist_button ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_addtowishlist_button_0" name="display_addtowishlist_button" type="radio" value="0" <?php checked( 0, $display_addtowishlist_button ); ?> />
										<label id="bdp-options-button" for="display_addtowishlist_button_0" <?php checked( 0, $display_addtowishlist_button ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<h3 class="bdp-table-title bdp_wishlist_button_setting">
								<?php esc_html_e( 'Wishlist Button Settings', 'blog-designer-pro' ); ?>
							</h3>
							<li class="bdp_wishlist_button_setting">
								<ul>
									<li class="bdp_add_to_wishlist_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'DisplayButton on', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Show wishlist button on', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_wishlistbutton_on = '1';
											if ( isset( $bdp_settings['bdp_wishlistbutton_on'] ) ) {
												$bdp_wishlistbutton_on = $bdp_settings['bdp_wishlistbutton_on'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_wishlistbutton_on_same_line" name="bdp_wishlistbutton_on" type="radio" value="1" <?php checked( '1', $bdp_wishlistbutton_on ); ?> />
												<label id="bdp-options-button" for="bdp_wishlistbutton_on_same_line"><?php esc_html_e( 'Same Line', 'blog-designer-pro' ); ?></label>
												<input id="bdp_wishlistbutton_on_next_line" name="bdp_wishlistbutton_on" type="radio" value="2" <?php checked( '2', $bdp_wishlistbutton_on ); ?> />
												<label id="bdp-options-button" for="bdp_wishlistbutton_on_next_line"><?php esc_html_e( 'Next Line', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr cart_wishlist_button_alignment">
										<div class="bdp-left">
											<span class="bdp-key-title"><?php esc_html_e( 'Wrapper Alignment', 'blog-designer-pro' ); ?></span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select cart button & wishlist button alignment', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_cart_wishlistbutton_alignment = 'left';
											if ( isset( $bdp_settings['bdp_cart_wishlistbutton_alignment'] ) ) {
												$bdp_cart_wishlistbutton_alignment = $bdp_settings['bdp_cart_wishlistbutton_alignment'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_cart_wishlistbutton_alignment_left" name="bdp_cart_wishlistbutton_alignment" type="radio" value="left" <?php checked( 'left', $bdp_cart_wishlistbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_cart_wishlistbutton_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></label>
												<input id="bdp_cart_wishlistbutton_alignment_center" name="bdp_cart_wishlistbutton_alignment" type="radio" value="center" <?php checked( 'center', $bdp_cart_wishlistbutton_alignment ); ?> />
												<label id="bdp-options-button" class="bdp_cart_wishlistbutton_alignment_center" for="bdp_cart_wishlistbutton_alignment_center"><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></label>
												<input id="bdp_cart_wishlistbutton_alignment_right" name="bdp_cart_wishlistbutton_alignment" type="radio" value="right" <?php checked( 'right', $bdp_cart_wishlistbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_cart_wishlistbutton_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr wishlist_button_alignment">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Alignment', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right">
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button alignment', 'blog-designer-pro' ); ?></span></span>
											<?php
											$bdp_wishlistbutton_alignment = 'left';
											if ( isset( $bdp_settings['bdp_wishlistbutton_alignment'] ) ) {
												$bdp_wishlistbutton_alignment = $bdp_settings['bdp_wishlistbutton_alignment'];
											}
											?>
											<fieldset class="buttonset green" data-hide='1'>
												<input id="bdp_wishlistbutton_alignment_left" name="bdp_wishlistbutton_alignment" type="radio" value="left" <?php checked( 'left', $bdp_wishlistbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_wishlistbutton_alignment_left"><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></label>
												<input id="bdp_wishlistbutton_alignment_center" name="bdp_wishlistbutton_alignment" type="radio" value="center" <?php checked( 'center', $bdp_wishlistbutton_alignment ); ?> />
												<label id="bdp-options-button" class="bdp_wishlistbutton_alignment_center" for="bdp_wishlistbutton_alignment_center"><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></label>
												<input id="bdp_wishlistbutton_alignment_right" name="bdp_wishlistbutton_alignment" type="radio" value="right" <?php checked( 'right', $bdp_wishlistbutton_alignment ); ?> />
												<label id="bdp-options-button" for="bdp_wishlistbutton_alignment_right"><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr bdp_padding_0">
										<div class="bdp-typography-wrapper bdp-typography-wrapper1">
											<div class="bdp-typography-cover">
												<h3 class="bdp-table-title"><?php esc_html_e( 'Normal Settings', 'blog-designer-pro' ); ?></h3>
												<div class="bdp-typography-sub-cover bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_wishlist_textcolor = isset( $bdp_settings['bdp_wishlist_textcolor'] ) ? $bdp_settings['bdp_wishlist_textcolor'] : ''; ?>
														<input type="text" name="bdp_wishlist_textcolor" id="bdp_wishlist_textcolor" value="<?php echo esc_attr( $bdp_wishlist_textcolor ); ?>"/>
														
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_wishlist_backgroundcolor = isset( $bdp_settings['bdp_wishlist_backgroundcolor'] ) ? $bdp_settings['bdp_wishlist_backgroundcolor'] : ''; ?>
														<input type="text" name="bdp_wishlist_backgroundcolor" id="bdp_wishlist_backgroundcolor" value="<?php echo esc_attr( $bdp_wishlist_backgroundcolor ); ?>"/>
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Border Radius(px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php
														$display_wishlist_button_border_radius = '0';
														if ( isset( $bdp_settings['display_wishlist_button_border_radius'] ) ) {
															$display_wishlist_button_border_radius = $bdp_settings['display_wishlist_button_border_radius'];
														}
														?>
														<input type="number" id="display_wishlist_button_border_radius" name="display_wishlist_button_border_radius" step="1" min="0" value="<?php echo esc_attr( $display_wishlist_button_border_radius ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-typography-sub-cover full-width bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Border', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<div class="bdp-border-wrap">
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php       $bdp_wishlistbutton_borderleft = isset( $bdp_settings['bdp_wishlistbutton_borderleft'] ) ? $bdp_settings['bdp_wishlistbutton_borderleft'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_borderleft" name="bdp_wishlistbutton_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_borderleftcolor = isset( $bdp_settings['bdp_wishlistbutton_borderleftcolor'] ) ? $bdp_settings['bdp_wishlistbutton_borderleftcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_borderleftcolor" id="bdp_wishlistbutton_borderleftcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_borderleftcolor ); ?>"/>
																	</div>
																</div>
															</div> 
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_borderright = isset( $bdp_settings['bdp_wishlistbutton_borderright'] ) ? $bdp_settings['bdp_wishlistbutton_borderright'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_borderright" name="bdp_wishlistbutton_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_borderright ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_borderrightcolor = isset( $bdp_settings['bdp_wishlistbutton_borderrightcolor'] ) ? $bdp_settings['bdp_wishlistbutton_borderrightcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_borderrightcolor" id="bdp_wishlistbutton_borderrightcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_borderrightcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_bordertop = isset( $bdp_settings['bdp_wishlistbutton_bordertop'] ) ? $bdp_settings['bdp_wishlistbutton_bordertop'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_bordertop" name="bdp_wishlistbutton_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_bordertopcolor = isset( $bdp_settings['bdp_wishlistbutton_bordertopcolor'] ) ? $bdp_settings['bdp_wishlistbutton_bordertopcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_bordertopcolor" id="bdp_wishlistbutton_bordertopcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_bordertopcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Bottom(px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_borderbuttom = isset( $bdp_settings['bdp_wishlistbutton_borderbuttom'] ) ? $bdp_settings['bdp_wishlistbutton_borderbuttom'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_borderbuttom" name="bdp_wishlistbutton_borderbuttom" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_borderbuttom ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php   $bdp_wishlistbutton_borderbottomcolor = isset( $bdp_settings['bdp_wishlistbutton_borderbottomcolor'] ) ? $bdp_settings['bdp_wishlistbutton_borderbottomcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_borderbottomcolor" id="bdp_wishlistbutton_borderbottomcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_borderbottomcolor ); ?>"/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
												<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Settings', 'blog-designer-pro' ); ?></h3>
												<div class="bdp-typography-sub-cover bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Text Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_wishlist_text_hover_color = isset( $bdp_settings['bdp_wishlist_text_hover_color'] ) ? $bdp_settings['bdp_wishlist_text_hover_color'] : ''; ?>
														<input type="text" name="bdp_wishlist_text_hover_color" id="bdp_wishlist_text_hover_color" value="<?php echo esc_attr( $bdp_wishlist_text_hover_color ); ?>"/>
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Background Color', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php $bdp_wishlist_hover_backgroundcolor = isset( $bdp_settings['bdp_wishlist_hover_backgroundcolor'] ) ? $bdp_settings['bdp_wishlist_hover_backgroundcolor'] : ''; ?>
														<input type="text" name="bdp_wishlist_hover_backgroundcolor" id="bdp_wishlist_hover_backgroundcolor" value="<?php echo esc_attr( $bdp_wishlist_hover_backgroundcolor ); ?>"/>
													</div>
												</div>
												<div class="bdp-typography-sub-cover bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
														<?php esc_html_e( 'Border Radius(px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<?php
														$display_wishlist_button_border_hover_radius = '0';
														if ( isset( $bdp_settings['display_wishlist_button_border_hover_radius'] ) ) {
															$display_wishlist_button_border_hover_radius = $bdp_settings['display_wishlist_button_border_hover_radius'];
														}
														?>
														<input type="number" id="display_wishlist_button_border_hover_radius" name="display_wishlist_button_border_hover_radius" step="1" min="0" value="<?php echo esc_attr( $display_wishlist_button_border_hover_radius ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-typography-sub-cover full-width bdp_add_to_wishlist_tr">
													<div class="bdp-typography-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Border', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-typography-content">
														<div class="bdp-border-wrap">
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Left (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_borderleft = isset( $bdp_settings['bdp_wishlistbutton_hover_borderleft'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderleft'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_hover_borderleft" name="bdp_wishlistbutton_hover_borderleft" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_borderleft ); ?>"  onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_borderleftcolor = isset( $bdp_settings['bdp_wishlistbutton_hover_borderleftcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderleftcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_hover_borderleftcolor" id="bdp_wishlistbutton_hover_borderleftcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_borderleftcolor ); ?>"/>
																	</div>
																</div>
															</div> 
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Right (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_borderright = isset( $bdp_settings['bdp_wishlistbutton_hover_borderright'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderright'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_hover_borderright" name="bdp_wishlistbutton_hover_borderright" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_borderright ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_borderrightcolor = isset( $bdp_settings['bdp_wishlistbutton_hover_borderrightcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderrightcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_hover_borderrightcolor" id="bdp_wishlistbutton_hover_borderrightcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_borderrightcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Top (px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_bordertop = isset( $bdp_settings['bdp_wishlistbutton_hover_bordertop'] ) ? $bdp_settings['bdp_wishlistbutton_hover_bordertop'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_hover_bordertop" name="bdp_wishlistbutton_hover_bordertop" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_bordertop ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_bordertopcolor = isset( $bdp_settings['bdp_wishlistbutton_hover_bordertopcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_bordertopcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_hover_bordertopcolor" id="bdp_wishlistbutton_hover_bordertopcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_bordertopcolor ); ?>"/>
																	</div>
																</div>
															</div>
															<div class="bdp-border-wrapper bdp-border-wrapper1">
																<div class="bdp-border-cover bdp-border-label">
																		<span class="bdp-key-title">
																			<?php esc_html_e( 'Bottom(px)', 'blog-designer-pro' ); ?>
																		</span>
																	</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_borderbuttom = isset( $bdp_settings['bdp_wishlistbutton_hover_borderbuttom'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderbuttom'] : '0'; ?>
																		<input type="number" id="bdp_wishlistbutton_hover_borderbuttom" name="bdp_wishlistbutton_hover_borderbuttom" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_borderbuttom ); ?>" onkeypress="return isNumberKey(event)">
																	</div>
																</div>
																<div class="bdp-border-cover">
																	<div class="bdp-border-content">
																		<?php $bdp_wishlistbutton_hover_borderbottomcolor = isset( $bdp_settings['bdp_wishlistbutton_hover_borderbottomcolor'] ) ? $bdp_settings['bdp_wishlistbutton_hover_borderbottomcolor'] : ''; ?>
																		<input type="text" name="bdp_wishlistbutton_hover_borderbottomcolor" id="bdp_wishlistbutton_hover_borderbottomcolor" value="<?php echo esc_attr( $bdp_wishlistbutton_hover_borderbottomcolor ); ?>"/>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</li>	
									
									<li class="bdp_add_to_wishlist_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Padding', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right bdp-border-cover">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Set wishlist button padding', 'blog-designer-pro' ); ?></span></span>
											<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Left-Right (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_wishlistbutton_padding_leftright = isset( $bdp_settings['bdp_wishlistbutton_padding_leftright'] ) ? $bdp_settings['bdp_wishlistbutton_padding_leftright'] : '10'; ?>
														<input type="number" id="bdp_wishlistbutton_padding_leftright" name="bdp_wishlistbutton_padding_leftright" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_padding_leftright ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Top-Bottom (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_wishlistbutton_padding_topbottom = isset( $bdp_settings['bdp_wishlistbutton_padding_topbottom'] ) ? $bdp_settings['bdp_wishlistbutton_padding_topbottom'] : '10'; ?>
														<input type="number" id="bdp_wishlistbutton_padding_topbottom" name="bdp_wishlistbutton_padding_topbottom" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlistbutton_padding_topbottom ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr">
										<div class="bdp-left">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Margin', 'blog-designer-pro' ); ?>
											</span>
										</div>
										<div class="bdp-right bdp-border-cover">
											<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Set wishlist button margin', 'blog-designer-pro' ); ?></span></span>
											<div class="bdp-padding-wrapper bdp-padding-wrapper1 bdp-border-wrap">
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Left-Right (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_wishlistbutton_margin_leftright = isset( $bdp_settings['bdp_wishlistbutton_margin_leftright'] ) ? $bdp_settings['bdp_wishlistbutton_margin_leftright'] : '10'; ?>
														<input type="number" id="bdp_wishlistbutton_margin_leftright" name="bdp_wishlistbutton_margin_leftright" step="1" value="<?php echo esc_attr( $bdp_wishlistbutton_margin_leftright ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
												<div class="bdp-padding-cover">
													<div class="bdp-padding-label">
														<span class="bdp-key-title">
															<?php esc_html_e( 'Top-Bottom (px)', 'blog-designer-pro' ); ?>
														</span>
													</div>
													<div class="bdp-padding-content">
														<?php $bdp_wishlistbutton_margin_topbottom = isset( $bdp_settings['bdp_wishlistbutton_margin_topbottom'] ) ? $bdp_settings['bdp_wishlistbutton_margin_topbottom'] : '10'; ?>
														<input type="number" id="bdp_wishlistbutton_margin_topbottom" name="bdp_wishlistbutton_margin_topbottom" step="1" value="<?php echo esc_attr( $bdp_wishlistbutton_margin_topbottom ); ?>"  onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr wishlist_button_box_shadow_setting">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'H-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select horizontal offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_top_box_shadow = isset( $bdp_settings['bdp_wishlist_button_top_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_top_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_top_box_shadow" name="bdp_wishlist_button_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'V-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select vertical offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_right_box_shadow = isset( $bdp_settings['bdp_wishlist_button_right_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_right_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_right_box_shadow" name="bdp_wishlist_button_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Blur (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select blur value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_bottom_box_shadow = isset( $bdp_settings['bdp_wishlist_button_bottom_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_bottom_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_bottom_box_shadow" name="bdp_wishlist_button_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Spread (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select spread value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_left_box_shadow = isset( $bdp_settings['bdp_wishlist_button_left_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_left_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_left_box_shadow" name="bdp_wishlist_button_left_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_left_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover bdp-boxshadow-color">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select box shadow color', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
														<?php $bdp_wishlist_button_box_shadow_color = isset( $bdp_settings['bdp_wishlist_button_box_shadow_color'] ) ? $bdp_settings['bdp_wishlist_button_box_shadow_color'] : ''; ?>
														<input type="text" name="bdp_wishlist_button_box_shadow_color" id="bdp_wishlist_button_box_shadow_color" value="<?php echo esc_attr( $bdp_wishlist_button_box_shadow_color ); ?>"/>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr wishlist_button_box_hover_shadow_setting">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Hover Box Shadow Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-boxshadow-wrapper bdp-boxshadow-wrapper1">
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'H-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select horizontal offset value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_hover_top_box_shadow = isset( $bdp_settings['bdp_wishlist_button_hover_top_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_top_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_hover_top_box_shadow" name="bdp_wishlist_button_hover_top_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_hover_top_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'V-offset (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select vertical side value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_hover_right_box_shadow = isset( $bdp_settings['bdp_wishlist_button_hover_right_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_right_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_hover_right_box_shadow" name="bdp_wishlist_button_hover_right_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_hover_right_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Blur (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select blur value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_hover_bottom_box_shadow = isset( $bdp_settings['bdp_wishlist_button_hover_bottom_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_bottom_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_hover_bottom_box_shadow" name="bdp_wishlist_button_hover_bottom_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_hover_bottom_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Spread (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select spread value', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_hover_left_box_shadow = isset( $bdp_settings['bdp_wishlist_button_hover_left_box_shadow'] ) ? $bdp_settings['bdp_wishlist_button_hover_left_box_shadow'] : '0'; ?>
													<input type="number" id="bdp_wishlist_button_hover_left_box_shadow" name="bdp_wishlist_button_hover_left_box_shadow" step="1" min="0" value="<?php echo esc_attr( $bdp_wishlist_button_hover_left_box_shadow ); ?>"  onkeypress="return isNumberKey(event)">
												</div>
											</div>
											<div class="bdp-boxshadow-cover bdp-boxshadow-color">
												<div class="bdp-boxshadow-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Color', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select box shadow color', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-boxshadow-content">
													<?php $bdp_wishlist_button_hover_box_shadow_color = isset( $bdp_settings['bdp_wishlist_button_hover_box_shadow_color'] ) ? $bdp_settings['bdp_wishlist_button_hover_box_shadow_color'] : ''; ?>
													<input type="text" name="bdp_wishlist_button_hover_box_shadow_color" id="bdp_wishlist_button_hover_box_shadow_color" value="<?php echo esc_attr( $bdp_wishlist_button_hover_box_shadow_color ); ?>"/>
												</div>
											</div>
										</div>
									</li>
									<li class="bdp_add_to_wishlist_tr">
										<h3 class="bdp-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer-pro' ); ?></h3>
										<div class="bdp-typography-wrapper bdp-typography-wrapper1">
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Family', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button font family', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_addtowishlist_button_fontface'] ) && '' != $bdp_settings['bdp_addtowishlist_button_fontface'] ) { //phpcs:ignore
														$bdp_addtowishlist_button_fontface = $bdp_settings['bdp_addtowishlist_button_fontface'];
													} else {
														$bdp_addtowishlist_button_fontface = '';
													}
													?>
													<div class="typo-field">
														<input type="hidden" id="bdp_addtowishlist_button_fontface_font_type" name="bdp_addtowishlist_button_fontface_font_type" value="<?php echo isset( $bdp_settings['bdp_addtowishlist_button_fontface_font_type'] ) ? esc_attr( $bdp_settings['bdp_addtowishlist_button_fontface_font_type'] ) : ''; ?>">
														<div class="select-cover">
															<select name="bdp_addtowishlist_button_fontface" id="bdp_addtowishlist_button_fontface">
																<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
																<?php
																$old_version = '';
																$cnt         = 0;
																foreach ( $font_family as $key => $value ) {
																	if ( $value['version'] != $old_version ) { //phpcs:ignore
																		if ( $cnt > 0 ) {
																			echo '</optgroup>';
																		}
																		echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
																		$old_version = $value['version'];
																	}
																	echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";
																	if ( '' != $bdp_addtowishlist_button_fontface && ( str_replace( '"', '', $bdp_addtowishlist_button_fontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
																		echo ' selected';
																	}
																	echo '>' . esc_html( $value['label'] ) . '</option>';
																	$cnt++;
																}
																if ( $cnt == count( $font_family ) ) { //phpcs:ignore
																	echo '</optgroup>';
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Size (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button font size', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php
													if ( isset( $bdp_settings['bdp_addtowishlist_button_fontsize'] ) && '' != $bdp_settings['bdp_addtowishlist_button_fontsize'] ) { //phpcs:ignore
														$bdp_addtowishlist_button_fontsize = $bdp_settings['bdp_addtowishlist_button_fontsize'];
													} else {
														$bdp_addtowishlist_button_fontsize = 14;
													}
													?>
													<div class="grid_col_space range_slider_fontsize" id="bdp_addtowishlist_button_fontsizeInput" ></div>
													<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="bdp_addtowishlist_button_fontsize" id="bdp_addtowishlist_button_fontsize" value="<?php echo esc_attr( $bdp_addtowishlist_button_fontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Font Weight', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button font weight', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<?php $bdp_addtowishlist_button_font_weight = isset( $bdp_settings['bdp_addtowishlist_button_font_weight'] ) ? $bdp_settings['bdp_addtowishlist_button_font_weight'] : 'normal'; ?>
													<div class="select-cover">
														<select name="bdp_addtowishlist_button_font_weight" id="bdp_addtowishlist_button_font_weight">
															<option value="100" <?php selected( $bdp_addtowishlist_button_font_weight, 100 ); ?>>100</option>
															<option value="200" <?php selected( $bdp_addtowishlist_button_font_weight, 200 ); ?>>200</option>
															<option value="300" <?php selected( $bdp_addtowishlist_button_font_weight, 300 ); ?>>300</option>
															<option value="400" <?php selected( $bdp_addtowishlist_button_font_weight, 400 ); ?>>400</option>
															<option value="500" <?php selected( $bdp_addtowishlist_button_font_weight, 500 ); ?>>500</option>
															<option value="600" <?php selected( $bdp_addtowishlist_button_font_weight, 600 ); ?>>600</option>
															<option value="700" <?php selected( $bdp_addtowishlist_button_font_weight, 700 ); ?>>700</option>
															<option value="800" <?php selected( $bdp_addtowishlist_button_font_weight, 800 ); ?>>800</option>
															<option value="900" <?php selected( $bdp_addtowishlist_button_font_weight, 900 ); ?>>900</option>
															<option value="bold" <?php selected( $bdp_addtowishlist_button_font_weight, 'bold' ); ?> ><?php esc_html_e( 'Bold', 'blog-designer-pro' ); ?></option>
															<option value="normal" <?php selected( $bdp_addtowishlist_button_font_weight, 'normal' ); ?>><?php esc_html_e( 'Normal', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
												</div>
											</div>

											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Line Height', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button line height', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
													<?php
													$display_wishlist_button_line_height = '1.5';
													if ( isset( $bdp_settings['display_wishlist_button_line_height'] ) ) {
														$display_wishlist_button_line_height = $bdp_settings['display_wishlist_button_line_height'];
													}
													?>
												<input type="number" id="display_wishlist_button_line_height" name="display_wishlist_button_line_height" step="1" min="1.5" value="<?php echo esc_attr( $display_wishlist_button_line_height ); ?>" placeholder="<?php esc_attr_e( 'Enter line height', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
											<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display wishlist button italic font style', 'blog-designer-pro' ); ?></span></span><?php $bdp_addtowishlist_button_font_italic = isset( $bdp_settings['bdp_addtowishlist_button_font_italic'] ) ? $bdp_settings['bdp_addtowishlist_button_font_italic'] : '0'; ?>
											</div>
											<div class="bdp-typography-content">
												<fieldset class="bdp-social-options bdp-display_author buttonset">
													<input id="bdp_addtowishlist_button_font_italic_1" name="bdp_addtowishlist_button_font_italic" type="radio" value="1"  <?php checked( 1, $bdp_addtowishlist_button_font_italic ); ?> />
													<label for="bdp_addtowishlist_button_font_italic_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="bdp_addtowishlist_button_font_italic_0" name="bdp_addtowishlist_button_font_italic" type="radio" value="0" <?php checked( 0, $bdp_addtowishlist_button_font_italic ); ?> />
													<label for="bdp_addtowishlist_button_font_italic_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Transform', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button text transform style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_addtowishlist_button_font_text_transform = isset( $bdp_settings['bdp_addtowishlist_button_font_text_transform'] ) ? $bdp_settings['bdp_addtowishlist_button_font_text_transform'] : 'none'; ?>
													<div class="select-cover">
														<select name="bdp_addtowishlist_button_font_text_transform" id="bdp_addtowishlist_button_font_text_transform">
															<option <?php selected( $bdp_addtowishlist_button_font_text_transform, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtowishlist_button_font_text_transform, 'capitalize' ); ?> value="capitalize"><?php esc_html_e( 'Capitalize', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtowishlist_button_font_text_transform, 'uppercase' ); ?> value="uppercase"><?php esc_html_e( 'Uppercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtowishlist_button_font_text_transform, 'lowercase' ); ?> value="lowercase"><?php esc_html_e( 'Lowercase', 'blog-designer-pro' ); ?></option>
															<option <?php selected( $bdp_addtowishlist_button_font_text_transform, 'full-width' ); ?> value="full-width"><?php esc_html_e( 'Full Width', 'blog-designer-pro' ); ?></option>
														</select>
													</div>
											</div>
										</div>

										<div class="bdp-typography-cover">
											<div class="bdp-typography-label">
												<span class="bdp-key-title">
												<?php esc_html_e( 'Text Decoration', 'blog-designer-pro' ); ?>
												</span>
												<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select wishlist button text decoration style', 'blog-designer-pro' ); ?></span></span>
											</div>
											<div class="bdp-typography-content">
												<?php $bdp_addtowishlist_button_font_text_decoration = isset( $bdp_settings['bdp_addtowishlist_button_font_text_decoration'] ) ? $bdp_settings['bdp_addtowishlist_button_font_text_decoration'] : 'none'; ?>
												<div class="select-cover">
													<select name="bdp_addtowishlist_button_font_text_decoration" id="bdp_addtowishlist_button_font_text_decoration">
														<option <?php selected( $bdp_addtowishlist_button_font_text_decoration, 'none' ); ?> value="none"><?php esc_html_e( 'None', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_addtowishlist_button_font_text_decoration, 'underline' ); ?> value="underline"><?php esc_html_e( 'Underline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_addtowishlist_button_font_text_decoration, 'overline' ); ?> value="overline"><?php esc_html_e( 'Overline', 'blog-designer-pro' ); ?></option>
														<option <?php selected( $bdp_addtowishlist_button_font_text_decoration, 'line-through' ); ?> value="line-through"><?php esc_html_e( 'Line Through', 'blog-designer-pro' ); ?></option>
													</select>
												</div>
											</div>
										</div>
											<div class="bdp-typography-cover">
												<div class="bdp-typography-label">
													<span class="bdp-key-title">
														<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer-pro' ); ?>
													</span>
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter wishlist button letter spacing', 'blog-designer-pro' ); ?></span></span>
												</div>
												<div class="bdp-typography-content">
													<div class="input-type-number">
														<input type="number" name="bdp_addtowishlist_button_letter_spacing" id="bdp_addtowishlist_button_letter_spacing" step="1" min="0" value="<?php echo isset( $bdp_settings['bdp_addtowishlist_button_letter_spacing'] ) ? esc_attr( $bdp_settings['bdp_addtowishlist_button_letter_spacing'] ) : '0'; ?>" onkeypress="return isNumberKey(event)">
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div id="bdpsinglepostnavigation" class="postbox postbox-with-fw-options bdp-post-navigation-setting" <?php echo $bdpsinglepostnavigation_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title" style="line-height:1.5">
										<?php esc_html_e( 'Display Product Next/Previous Navigation', 'blog-designer-pro' ); ?>&nbsp;
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product navigation', 'blog-designer-pro' ); ?></span></span>
									<?php $display_navigation = isset( $bdp_settings['display_navigation'] ) ? $bdp_settings['display_navigation'] : 0; ?>
									<fieldset class="bdp-social-options bdp-display_comment buttonset buttonset-hide ui-buttonset" data-hide="1">
										<input id="display_navigation_1" name="display_navigation" type="radio" value="1" <?php checked( 1, $display_navigation ); ?> />
										<label for="display_navigation_1" <?php checked( 1, $display_navigation ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_navigation_0" name="display_navigation" type="radio" value="0" <?php checked( 0, $display_navigation ); ?> />
										<label for="display_navigation_0" <?php checked( 0, $display_navigation ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="post-navigation-blocks">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Apply Filter on Product Navigation', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Apply filter on product navigation', 'blog-designer-pro' ); ?></span></span>
									<?php $bdp_post_navigation_filter = isset( $bdp_settings['bdp_post_navigation_filter'] ) ? $bdp_settings['bdp_post_navigation_filter'] : ''; ?>
									<select name="bdp_post_navigation_filter" id="bdp_post_navigation_filter">
										<option value=""><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></option>
										<option <?php selected( $bdp_post_navigation_filter, 'product_cat' ); ?> value="product_cat"><?php esc_html_e( 'Category', 'blog-designer-pro' ); ?></option>
										<option <?php selected( $bdp_post_navigation_filter, 'product_tag' ); ?> value="product_tag"><?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?></option>
									</select>
								</div>
							</li>
							<li class="post-navigation-blocks">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Product Title', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product title', 'blog-designer-pro' ); ?></span></span>
									<?php $display_pn_title = ( isset( $bdp_settings['display_pn_title'] ) ) ? $bdp_settings['display_pn_title'] : 1; ?>
									<fieldset class="buttonset buttonset-hide ui-buttonset" data-hide="1">
										<input id="display_pn_title_1" name="display_pn_title" type="radio" value="1" <?php checked( 1, $display_pn_title ); ?> />
										<label for="display_pn_title_1" <?php checked( 1, $display_pn_title ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_pn_title_0" name="display_pn_title" type="radio" value="0" <?php checked( 0, $display_pn_title ); ?> />
										<label for="display_pn_title_0" <?php checked( 0, $display_pn_title ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
										<?php esc_html_e( 'Show Product title when option is "Yes" otherwise it will display "Previous Product" and "Next Product".', 'blog-designer-pro' ); ?>
									</div>
								</div>
							</li>
							<li class="post-navigation-blocks">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Product Feature Image', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product feature image', 'blog-designer-pro' ); ?></span></span>
									<?php $display_pn_image = ( isset( $bdp_settings['display_pn_image'] ) ) ? $bdp_settings['display_pn_image'] : 1; ?>
									<fieldset class="buttonset buttonset-hide ui-buttonset" data-hide="1">
										<input id="display_pn_image_1" name="display_pn_image" type="radio" value="1" <?php checked( 1, $display_pn_image ); ?> />
										<label for="display_pn_image_1" <?php checked( 1, $display_pn_image ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_pn_image_0" name="display_pn_image" type="radio" value="0" <?php checked( 0, $display_pn_image ); ?> />
										<label for="display_pn_image_0" <?php checked( 0, $display_pn_image ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="post-navigation-blocks">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Product Date', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product date', 'blog-designer-pro' ); ?></span></span>
									<?php $display_pn_date = ( isset( $bdp_settings['display_pn_date'] ) ) ? $bdp_settings['display_pn_date'] : 1; ?>
									<fieldset class="buttonset buttonset-hide ui-buttonset" data-hide="1">
										<input id="display_pn_date_1" name="display_pn_date" type="radio" value="1" <?php checked( 1, $display_pn_date ); ?> />
										<label for="display_pn_date_1" <?php checked( 1, $display_pn_date ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_pn_date_0" name="display_pn_date" type="radio" value="0" <?php checked( 0, $display_pn_date ); ?> />
										<label for="display_pn_date_0" <?php checked( 0, $display_pn_date ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglepostauthor" class="postbox postbox-with-fw-options bdp-post-navigation-setting" <?php echo $bdpauthor_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Author Data', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show author data', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['display_author_data'] ) ) {
										$display_author_data = $bdp_settings['display_author_data'];
									} else {
										$display_author_data = 1;
									}
									?>
									<fieldset class="bdp-social-options bdp-display_author_data buttonset">
										<input id="display_author_data_1" name="display_author_data" type="radio" value="1"  <?php checked( 1, $display_author_data ); ?> />
										<label for="display_author_data_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_author_data_0" name="display_author_data" type="radio" value="0" <?php checked( 0, $display_author_data ); ?> />
										<label for="display_author_data_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="display_author_biography_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Author Biography', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show author biography', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['display_author_biography'] ) ) {
										$display_author_biography = $bdp_settings['display_author_biography'];
									} else {
										$display_author_biography = 1;
									}
									?>
									<fieldset class="bdp-social-options bdp-display_author buttonset">
										<input id="display_author_biography_1" name="display_author_biography" type="radio" value="1"  <?php checked( 1, $display_author_biography ); ?> />
										<label for="display_author_biography_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_author_biography_0" name="display_author_biography" type="radio" value="0" <?php checked( 0, $display_author_biography ); ?> />
										<label for="display_author_biography_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="display_author_biography_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Author Title', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter lable for author title', 'blog-designer-pro' ); ?></span></span>
									<input type="text" id="txtAuthorTitle" name="txtAuthorTitle" value="<?php echo isset( $bdp_settings['txtAuthorTitle'] ) ? esc_attr( $bdp_settings['txtAuthorTitle'] ) : esc_html__( 'About', 'blog-designer-pro' ) . ' [author]'; ?>" placeholder="
									<?php
									esc_html_e( 'About', 'blog-designer-pro' );
									echo ' [author]';
									?>
									">
									<label class="disable_link bdp-link-disable">
										<input id="disable_link_author_div" name="disable_link_author_div" type="checkbox" value="1" 
										<?php
										if ( isset( $bdp_settings['disable_link_author_div'] ) ) {
											checked( 1, $bdp_settings['disable_link_author_div'] );
										}
										?>
										/>
										<?php esc_html_e( 'Disable Link for Author', 'blog-designer-pro' ); ?>
									</label>
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
										<?php
										esc_html_e( 'Use', 'blog-designer-pro' );
										echo ' [author] ';
										esc_html_e( 'to display author name with link dynamically.', 'blog-designer-pro' );
										?>
									</div>
								</div>
							</li>
							<li class="display_author_biography_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Author Title Font Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select font size for author title', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['author_title_fontsize'] ) ) {
										$author_title_fontsize = $bdp_settings['author_title_fontsize'];
									} else {
										$author_title_fontsize = 16;
									}
									?>
									<div class="grid_col_space range_slider_fontsize" id="author_title_fontsize_slider"></div>
									<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="author_title_fontsize" id="author_title_fontsize" value="<?php echo esc_attr( $author_title_fontsize ); ?>" onkeypress="return isNumberKey(event)" /></div>
								</div>
							</li>
							<li class="display_author_biography_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Author Title Font Family', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select author title font family', 'blog-designer-pro' ); ?></span></span>
									<div class="typo-field">
										<input type="hidden" name="author_title_fontface_font_type" id="author_title_fontface_font_type" value="<?php echo isset( $bdp_settings['author_title_fontface_font_type'] ) ? esc_attr( $bdp_settings['author_title_fontface_font_type'] ) : 'Serif Fonts'; ?>">
										<?php
										$author_title_fontface = '';
										if ( isset( $bdp_settings['author_title_fontface'] ) ) {
											$author_title_fontface = $bdp_settings['author_title_fontface'];
										}
										?>
										<select name="author_title_fontface" id="author_title_fontface">
											<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
											<?php
											$old_version = '';
											$cnt         = 0;
											foreach ( $font_family as $key => $value ) {
												if ( $value['version'] != $old_version ) { //phpcs:ignore
													if ( $cnt > 0 ) {
														echo '</optgroup>';
													}
													echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
													$old_version = $value['version'];
												}
												echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

												if ( '' != $author_title_fontface && ( str_replace( '"', '', $author_title_fontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
													echo ' selected';
												}
												echo '>' . esc_html( $value['label'] ) . '</option>';
												$cnt++;
											}
											if ( $cnt == count( $font_family ) ) { //phpcs:ignore
												echo '</optgroup>';
											}
											?>
										</select>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div id="bdpsinglerelated" class="postbox postbox-with-fw-options bdp-content-setting1" <?php echo $bdprelated_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li class="bdp-related-lineheight">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Related Product On Single Page', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Set related product on single page', 'blog-designer-pro' ); ?></span></span>
									<label>
										<input id="display_related_post" name="display_related_post" type="checkbox" value="1" 
										<?php
										if ( isset( $bdp_settings['display_related_post'] ) ) {
											checked( 1, $bdp_settings['display_related_post'] );
										}
										?>
										/>
									</label>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Related Product Display Position', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select related product position', 'blog-designer-pro' ); ?></span></span>
									<?php
									$bdp_display_related_post = 'bottom';
									if ( isset( $bdp_settings['bdp_display_related_post'] ) ) {
										$bdp_display_related_post = $bdp_settings['bdp_display_related_post'];
									}
									?>
									<select id="bdp_display_related_post" name="bdp_display_related_post">
										<option value="bottom" <?php echo selected( 'bottom', $bdp_display_related_post ); ?>><?php esc_html_e( 'Bottom Side', 'blog-designer-pro' ); ?></option>
										<option value="left" <?php echo selected( 'left', $bdp_display_related_post ); ?>><?php esc_html_e( 'Left Side', 'blog-designer-pro' ); ?></option>
										<option value="right" <?php echo selected( 'right', $bdp_display_related_post ); ?>><?php esc_html_e( 'Right Side', 'blog-designer-pro' ); ?></option>
									</select>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Number Of Column Related Posts', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select number of column to display related posts', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['related_post_column'] ) ) {
										$bdp_settings['related_post_column'] = $bdp_settings['related_post_column'];
									} else {
										$bdp_settings['related_post_column'] = 3;
									}
									?>
									<select name="related_post_column" id="related_post_column">
										<option value="1" 
										<?php
										if ( '1' == $bdp_settings['related_post_column'] ) {
											?>
											selected="selected"<?php } ?>>1</option>
										<option value="2" 
										<?php
										if ( '2' === $bdp_settings['related_post_column'] ) {
											?>
											selected="selected"<?php } ?>>2</option>
										<option value="3" 
										<?php
										if ( '3' === $bdp_settings['related_post_column'] ) {
											?>
											selected="selected"<?php } ?>>3</option>
										<option value="4" 
										<?php
										if ( '4' === $bdp_settings['related_post_column'] ) {
											?>
											selected="selected"<?php } ?>>4</option>
									</select>
								</div>
							</li>
							<li class="bdp_single_custom_media_selection_related_post">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select Product Media Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select size of related product media', 'blog-designer-pro' ); ?></span></span>
									<select id="bdp_related_post_media_size" name="bdp_related_post_media_size">
										<option value="full" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && 'full' === $bdp_settings['bdp_related_post_media_size'] ) ? 'selected="selected"' : ''; ?> ><?php esc_html_e( 'Original Resolution', 'blog-designer-pro' ); ?></option>
										<?php
										global $_wp_additional_image_sizes;
										$thumb_sizes = array();
										$image_size  = get_intermediate_image_sizes();
										foreach ( $image_size as $s ) { //phpcs:ignore
											$thumb_sizes [ $s ] = array( 0, 0 );
											if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) { //phpcs:ignore
												?>
												<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && $bdp_settings['bdp_related_post_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_html( $s ) . ' (' . esc_html( get_option( $s . '_size_w' ) ) . 'x' . esc_html( get_option( $s . '_size_h' ) ) . ')'; //phpcs:ignore ?> </option>
												<?php
											} else {
												if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
													?>
													<option value="<?php echo esc_attr( $s ); ?>" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && $bdp_settings['bdp_related_post_media_size'] == $s ) ? 'selected="selected"' : ''; ?>> <?php echo esc_html( $s ) . ' (' . esc_html( $_wp_additional_image_sizes[ $s ]['width'] ) . 'x' . esc_html( $_wp_additional_image_sizes[ $s ]['height'] ) . ')'; //phpcs:ignore ?> </option>
													<?php
												}
											}
										}
										?>
										<option value="custom" <?php echo ( isset( $bdp_settings['bdp_related_post_media_size'] ) && 'custom' === $bdp_settings['bdp_related_post_media_size'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Custom Size', 'blog-designer-pro' ); ?></option>
									</select>
								</div>
							</li>
							<li class="bdp_related_post_media_custom_size_tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Add Cutom Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Enter custom size for product media', 'blog-designer-pro' ); ?></span></span>
									<div class="bdp_media_custom_size_tbl">
										<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Width (px)', 'blog-designer-pro' ); ?> </span> <input type="number" onkeypress="return isNumberKey(event)" min="0" name="related_post_media_custom_width" class="media_custom_width" id="related_post_media_custom_width" value="<?php echo ( isset( $bdp_settings['related_post_media_custom_width'] ) && '' != $bdp_settings['related_post_media_custom_width'] ) ? esc_attr( $bdp_settings['related_post_media_custom_width'] ) : ''; //phpcs:ignore ?>" /></p>
										<p> <span class="bdp_custom_media_size_title"><?php esc_html_e( 'Height (px)', 'blog-designer-pro' ); ?> </span> <input type="number" onkeypress="return isNumberKey(event)" min="0" name="related_post_media_custom_height" class="media_custom_height" id="related_post_media_custom_height" value="<?php echo ( isset( $bdp_settings['related_post_media_custom_height'] ) && '' != $bdp_settings['related_post_media_custom_height'] ) ? esc_attr( $bdp_settings['related_post_media_custom_height'] ) : ''; //phpcs:ignore ?>"/></p>
									</div>
								</div>
							</li>
							<li class="related_post_text">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Related Product Title', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter related product title', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="related_post_title" id="related_post_title" value="<?php
									if ( isset( $bdp_settings['related_post_title'] ) ) { echo esc_attr( $bdp_settings['related_post_title'] ); }?>" placeholder="<?php esc_html_e( 'Enter Related Post Title', 'blog-designer-pro' ); ?>">
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Related Product Title Font Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select related product title font size', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['related_title_fontsize'] ) ) {
										$related_title_fontsize = $bdp_settings['related_title_fontsize'];
									} else {
										$related_title_fontsize = 25;
									}
									?>
									<div class="grid_col_space range_slider_fontsize" id="related_post_fontsize" data-value="<?php echo esc_attr( $related_title_fontsize ); ?>" ></div>
									<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="related_title_fontsize" id="related_title_fontsize" value="<?php echo esc_attr( $related_title_fontsize ); ?>" onkeypress="return isNumberKey(event)" /></div><?php esc_html_e( 'px', 'blog-designer-pro' ); ?>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Related Product Title Font Family', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select related product title font family', 'blog-designer-pro' ); ?>&nbsp;</span></span>
									<div class="typo-field">
										<input type="hidden" name="related_title_fontface_font_type" id="related_title_fontface_font_type" value="<?php echo isset( $bdp_settings['related_title_fontface_font_type'] ) ? esc_attr( $bdp_settings['related_title_fontface_font_type'] ) : 'Serif Fonts'; ?>">
										<?php
										if ( isset( $bdp_settings['related_title_fontface'] ) ) {
											$related_title_fontface = $bdp_settings['related_title_fontface'];
										} else {
											$related_title_fontface = 'Georgia, serif';
										}
										?>
										<select name="related_title_fontface" id="related_title_fontface">
											<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
											<?php
											$old_version = '';
											$cnt         = 0;
											foreach ( $font_family as $key => $value ) {
												if ( $value['version'] != $old_version ) { //phpcs:ignore
													if ( $cnt > 0 ) {
														echo '</optgroup>';
													}
													echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
													$old_version = $value['version'];
												}
												echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";

												if ( '' != $related_title_fontface && ( str_replace( '"', '', $related_title_fontface ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
													echo ' selected';
												}
												echo '>' . esc_html( $value['label'] ) . '</option>';
												$cnt++;
											}
											if ( $cnt == count( $font_family ) ) { //phpcs:ignore
												echo '</optgroup>';
											}
											?>
										</select>
									</div>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Related Product Title Color', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-color"><span class="bdp-tooltips"><?php esc_html_e( 'Select related product title color', 'blog-designer-pro' ); ?></span></span>
									<input type="text" name="related_title_color" id="related_title_color" value="<?php echo isset( $bdp_settings['related_title_color'] ) ? esc_attr( $bdp_settings['related_title_color'] ) : '#333333'; ?>"/>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Show Related Products By', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display related product by category or tag', 'blog-designer-pro' ); ?></span></span>
									<?php
									$related_post_by = isset( $bdp_settings['related_post_by'] ) ? $bdp_settings['related_post_by'] : '';
									?>
									<select name="related_post_by" id="related_post_by">
										<option selected="" value="category" 
										<?php
										if ( 'category' === $related_post_by ) {
											?>
											selected="selected"<?php } ?>>
											<?php esc_html_e( 'Category', 'blog-designer-pro' ); ?>
										</option>
										<option value="tag" 
										<?php
										if ( 'tag' === $related_post_by ) {
											?>
											selected="selected"<?php } ?>>
											<?php esc_html_e( 'Tag', 'blog-designer-pro' ); ?>
										</option>
									</select>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Number Of Related Products', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select number of related products', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['related_post_number'] ) ) {
										$bdp_settings['related_post_number'] = $bdp_settings['related_post_number'];
									} else {
										$bdp_settings['related_post_number'] = 3;
									}
									?>
									<select name="related_post_number" id="related_post_number">
										<option selected="" value="2" 
										<?php
										if ( '2' === $bdp_settings['related_post_number'] ) {
											?>
											selected="selected"<?php } ?>>2</option>
										<option value="3" 
										<?php
										if ( '3' === $bdp_settings['related_post_number'] ) {
											?>
											selected="selected"<?php } ?>>3</option>
										<option value="4" 
										<?php
										if ( '4' === $bdp_settings['related_post_number'] ) {
											?>
											selected="selected"<?php } ?>>4</option>
									</select>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Related Products Order By', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select sorting order of related product', 'blog-designer-pro' ); ?></span></span>
									<?php
									$related_orderby = '';
									if ( isset( $bdp_settings['bdp_related_post_order_by'] ) ) {
										$related_orderby = $bdp_settings['bdp_related_post_order_by'];
									}
									?>
									<select id="bdp_related_post_order_by" name="bdp_related_post_order_by">
										<option value="" <?php echo selected( '', $related_orderby ); ?>><?php esc_html_e( 'Default Sorting', 'blog-designer-pro' ); ?></option>
										<option value="rand" <?php echo selected( 'rand', $related_orderby ); ?>><?php esc_html_e( 'Random', 'blog-designer-pro' ); ?></option>
										<option value="ID" <?php echo selected( 'ID', $related_orderby ); ?>><?php esc_html_e( 'Product ID', 'blog-designer-pro' ); ?></option>
										<option value="author" <?php echo selected( 'author', $related_orderby ); ?>><?php esc_html_e( 'Author', 'blog-designer-pro' ); ?></option>
										<option value="title" <?php echo selected( 'title', $related_orderby ); ?>><?php esc_html_e( 'Product Title', 'blog-designer-pro' ); ?></option>
										<option value="name" <?php echo selected( 'name', $related_orderby ); ?>><?php esc_html_e( 'Product Slug', 'blog-designer-pro' ); ?></option>
										<option value="date" <?php echo selected( 'date', $related_orderby ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
										<option value="modified" <?php echo selected( 'modified', $related_orderby ); ?>><?php esc_html_e( 'Modified Date', 'blog-designer-pro' ); ?></option>
										<option value="meta_value_num" <?php echo selected( 'meta_value_num', $related_orderby ); ?>><?php esc_html_e( 'Product Likes', 'blog-designer-pro' ); ?></option>
									</select>
																		<div class="blg_order">
										<?php
										$related_post_order = 'DESC';
										if ( isset( $bdp_settings['bdp_related_post_order'] ) ) {
											$related_post_order = $bdp_settings['bdp_related_post_order'];
										}
										?>
										<fieldset class="buttonset green" data-hide='1'>
											<input id="bdp_related_post_asc" name="bdp_related_post_order" type="radio" value="ASC" <?php checked( 'ASC', $related_post_order ); ?> />
											<label id="bdp-options-button" for="bdp_related_post_asc"><?php esc_html_e( 'Ascending', 'blog-designer-pro' ); ?></label>
											<input id="bdp_related_post_desc" name="bdp_related_post_order" type="radio" value="DESC" <?php checked( 'DESC', $related_post_order ); ?> />
											<label id="bdp-options-button" for="bdp_related_post_desc"><?php esc_html_e( 'Descending', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Show Content From', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Display content from product content or excerpt', 'blog-designer-pro' ); ?></span></span>
									<?php $template_post_content_from = isset( $bdp_settings['related_post_content_from'] ) ? $bdp_settings['related_post_content_from'] : 'from_content'; ?>
									<select name="related_post_content_from" id="related_post_content_from">
										<option value="from_content" <?php selected( $template_post_content_from, 'from_content' ); ?> ><?php esc_html_e( 'Product Content', 'blog-designer-pro' ); ?></option>
										<option value="from_excerpt" <?php selected( $template_post_content_from, 'from_excerpt' ); ?>><?php esc_html_e( 'Product Excerpt', 'blog-designer-pro' ); ?></option>
									</select>
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b> &nbsp;
										<?php esc_html_e( 'If Product Excerpt is empty then Content will get automatically from Product Content.', 'blog-designer-pro' ); ?>
									</div>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Product Content Length (words)', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter product content length', 'blog-designer-pro' ); ?></span></span>
									<input class="bdp-content-lenth" type="number" id="related_post_content_length" name="related_post_content_length" step="1" min="0" value="<?php echo isset( $bdp_settings['related_post_content_length'] ) ? esc_attr( $bdp_settings['related_post_content_length'] ) : ''; ?>" placeholder="<?php esc_html_e( 'Enter Content length', 'blog-designer-pro' ); ?>" onkeypress="return isNumberKey(event)">
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>: </b>
										<?php esc_html_e( 'Leave it blank if you want to hide content in related product.', 'blog-designer-pro' ); ?>
									</div>
								</div>
							</li>
							<li class="">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Product Price', 'blog-designer-pro' ); ?>
									</span>

								</div>
								<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show product price', 'blog-designer-pro' ); ?></span></span>
									<?php $related_product_price = ( isset( $bdp_settings['related_product_price'] ) ) ? $bdp_settings['related_product_price'] : 0; ?>
									<fieldset class="buttonset related_product_price">
										<input id="related_product_price_1" name="related_product_price" type="radio" value="1" <?php checked( 1, $related_product_price ); ?> />
										<label for="related_product_price_1" <?php checked( 1, $related_product_price ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
										<input id="related_product_price_0" name="related_product_price" type="radio" value="0" <?php checked( 0, $related_product_price ); ?> />
										<label for="related_product_price_0" <?php checked( 0, $related_product_price ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="content-firstletter-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Add To Cart Button', 'blog-designer-pro' ); ?>
									</span>

								</div>
								<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show add to cart button', 'blog-designer-pro' ); ?></span></span>
									<?php $related_product_cart_button = ( isset( $bdp_settings['related_product_cart_button'] ) ) ? $bdp_settings['related_product_cart_button'] : 0; ?>
									<fieldset class="buttonset related_product_cart_button">
										<input id="related_product_cart_button_1" name="related_product_cart_button" type="radio" value="1" <?php checked( 1, $related_product_cart_button ); ?> />
										<label for="related_product_cart_button_1" <?php checked( 1, $related_product_cart_button ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
										<input id="related_product_cart_button_0" name="related_product_cart_button" type="radio" value="0" <?php checked( 0, $related_product_cart_button ); ?> />
										<label for="related_product_cart_button_0" <?php checked( 0, $related_product_cart_button ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="content-firstletter-tr">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Sale tag', 'blog-designer-pro' ); ?>
									</span>

								</div>
								<div class="bdp-right">
								<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show sale tag', 'blog-designer-pro' ); ?></span></span>
									<?php $related_product_sale_tag = ( isset( $bdp_settings['related_product_sale_tag'] ) ) ? $bdp_settings['related_product_sale_tag'] : 0; ?>
									<fieldset class="buttonset related_product_sale_tag">
										<input id="related_product_sale_tag_1" name="related_product_sale_tag" type="radio" value="1" <?php checked( 1, $related_product_sale_tag ); ?> />
										<label for="related_product_sale_tag_1" <?php checked( 1, $related_product_sale_tag ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
										<input id="related_product_sale_tag_0" name="related_product_sale_tag" type="radio" value="0" <?php checked( 0, $related_product_sale_tag ); ?> />
										<label for="related_product_sale_tag_0" <?php checked( 0, $related_product_sale_tag ); ?>><?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Related Product Content', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select display related product content', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_related_postcontent = 'bottom';
									if ( isset( $bdp_settings['bdp_display_related_postcontent'] ) ) {
										$display_related_postcontent = $bdp_settings['bdp_display_related_postcontent'];
									}
									?>
									<select id="bdp_display_related_postcontent" name="bdp_display_related_postcontent">
										<option value="bottom" <?php echo selected( 'bottom', $display_related_postcontent ); ?>><?php esc_html_e( 'Bottom Side', 'blog-designer-pro' ); ?></option>
										<option value="left" <?php echo selected( 'left', $display_related_postcontent ); ?>><?php esc_html_e( 'Left Side', 'blog-designer-pro' ); ?></option>
										<option value="right" <?php echo selected( 'right', $display_related_postcontent ); ?>><?php esc_html_e( 'Right Side', 'blog-designer-pro' ); ?></option>
										<option value="top" <?php echo selected( 'top', $display_related_postcontent ); ?>><?php esc_html_e( 'Top Side', 'blog-designer-pro' ); ?></option>
									</select>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Related Post Date', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable related post date', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['display_related_post_date'] ) ) {
										$display_related_post_date = $bdp_settings['display_related_post_date'];
									} else {
										$display_related_post_date = 0;
									}
									?>
									<fieldset class="bdp-social-options bdp-display_author buttonset">
										<input id="display_related_post_date_1" name="display_related_post_date" type="radio" value="1"  <?php checked( 1, $display_related_post_date ); ?> />
										<label for="display_related_post_date_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_related_post_date_0" name="display_related_post_date" type="radio" value="0" <?php checked( 0, $display_related_post_date ); ?> />
										<label for="display_related_post_date_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Related Post Comment', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable related post comment', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['display_related_post_comment'] ) ) {
										$display_related_post_comment = $bdp_settings['display_related_post_comment'];
									} else {
										$display_related_post_comment = 0;
									}
									?>
									<fieldset class="bdp-social-options bdp-display_author buttonset">
										<input id="display_related_post_comment_1" name="display_related_post_comment" type="radio" value="1"  <?php checked( 1, $display_related_post_comment ); ?> />
										<label for="display_related_post_comment_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_related_post_comment_0" name="display_related_post_comment" type="radio" value="0" <?php checked( 0, $display_related_post_comment ); ?> />
										<label for="display_related_post_comment_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<?php if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) { ?>
				<div id="bdpacffieldssetting" class="postbox postbox-with-fw-options" <?php echo $bdpacffieldssetting_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Display Acf Field', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show ACF field', 'blog-designer-pro' ); ?></span></span>
									<?php
									$display_acf_field = '0';
									if ( isset( $bdp_settings['display_acf_field'] ) ) {
										$display_acf_field = $bdp_settings['display_acf_field'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide" data-hide='1'>
										<input id="display_acf_field_1" name="display_acf_field" type="radio" value="1" <?php checked( 1, $display_acf_field ); ?> />
										<label id="bdp-options-button" for="display_acf_field_1" <?php checked( 1, $display_acf_field ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="display_acf_field_0" name="display_acf_field" type="radio" value="0" <?php checked( 0, $display_acf_field ); ?> />
										<label id="bdp-options-button" for="display_acf_field_0" <?php checked( 1, $display_acf_field ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class="bdp_setting_acf_field">
								<?php
								$post_id = get_posts( //phpcs:ignore
									array(
										'fields'         => 'ids',
										'posts_per_page' => -1,
									)
								);
								$groups  = acf_get_field_groups(
									array(
										'post_id'   => $post_id,
										'post_type' => 'product',
									)
								);
								if ( ! empty( $groups ) ) {
									?>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Select ACF Field', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-select"><span class="bdp-tooltips"><?php esc_html_e( 'Filter post via category', 'blog-designer-pro' ); ?></span></span>
									<?php
									$bdp_acf_field = isset( $bdp_settings['bdp_acf_field'] ) ? $bdp_settings['bdp_acf_field'] : array();

									?>
									<select data-placeholder="<?php esc_attr_e( 'Choose acf field', 'blog-designer-pro' ); ?>" class="chosen-select" multiple style="width:220px;" name="bdp_acf_field[]" id="bdp_acf_field">
										<?php
										foreach ( $groups as $group ) {
											$group_id                                 = $group['ID'];
											$group_title                              = $group['title'];
											$all_acf_data[ $group_id ]                = array();
											$all_acf_data[ $group_id ]['group_id']    = $group_id;
											$all_acf_data[ $group_id ]['group_title'] = $group_title;
											$fields                                   = acf_get_fields( $group_id );
											if ( $fields ) {
												$all_acf_data[ $group_id ]['fields'] = array();
												$val_fields                          = 0;
												foreach ( $fields as $field ) {
													$field_id    = $field['ID'];
													$field_label = $field['label'];
													$field_key   = $field['key'];
													?>
													<option value="<?php echo esc_attr( $field_id ); ?>" 
														<?php
														if ( @in_array( $field_id, $bdp_acf_field ) ) { //phpcs:ignore
															echo 'selected="selected"';
														}
														?>
													><?php echo esc_html( $field_label ); ?></option>
													<?php
												}
											}
										}
										?>
									</select>
								</div>
								<?php } ?>
							</li>
						</ul>
					</div>
				</div>
				<?php } ?>
				<div id="bdpsinglesocial" class="postbox postbox-with-fw-options" <?php echo $bdpsocial_class_show; //phpcs:ignore ?>>
					<div class="inside">
						<ul class="bdp-settings bdp-lineheight">
							<li>
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable social share link', 'blog-designer-pro' ); ?></span></span>
									<?php $social_share = isset( $bdp_settings['social_share'] ) ? $bdp_settings['social_share'] : 1; ?>
									<fieldset class="bdp-social-options buttonset buttonset-hide" data-hide='1'>
										<input id="social_share_1" name="social_share" type="radio" value="1" <?php checked( 1, $social_share ); ?> />
										<label id="" for="social_share_1" <?php checked( 1, $social_share ); ?>><?php esc_html_e( 'Enable', 'blog-designer-pro' ); ?></label>
										<input id="social_share_0" name="social_share" type="radio" value="0" <?php checked( 0, $social_share ); ?> />
										<label id="" for="social_share_0" <?php checked( 0, $social_share ); ?>> <?php esc_html_e( 'Disable', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>

							<li class ="social_share_options">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share Style', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select social share style', 'blog-designer-pro' ); ?></span></span>
									<?php
									$social_style = '1';
									if ( isset( $bdp_settings['social_style'] ) ) {
										$social_style = $bdp_settings['social_style'];
									}
									?>
									<fieldset class="bdp-social-style buttonset buttonset-hide green" data-hide='1'>
										<input id="social_style_0" name="social_style" type="radio" value="0" <?php checked( 0, $social_style ); ?> />
										<label id="bdp-options-button" for="social_style_0" <?php checked( 0, $social_style ); ?>><?php esc_html_e( 'Default', 'blog-designer-pro' ); ?></label>
										<input id="social_style_1" name="social_style" type="radio" value="1" <?php checked( 1, $social_style ); ?> />
										<label id="bdp-options-button" for="social_style_1" <?php checked( 1, $social_style ); ?>><?php esc_html_e( 'Custom', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class ="social_share_options shape_social_icon">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Shape of Social Icon', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select shape of social icon', 'blog-designer-pro' ); ?></span></span>
									<?php
									$social_icon_style = isset( $bdp_settings['social_icon_style'] ) ? $bdp_settings['social_icon_style'] : 1;
									?>
									<fieldset class="bdp-social-shape buttonset buttonset-hide green" data-hide='1'>
										<input id="social_icon_style_0" name="social_icon_style" type="radio" value="0" nhp-opts-button-hide-below <?php checked( 0, $social_icon_style ); ?> />
										<label id="bdp-options-button" for="social_icon_style_0" <?php checked( 0, $social_icon_style ); ?>><?php esc_html_e( 'Circle', 'blog-designer-pro' ); ?></label>
										<input id="social_icon_style_1" name="social_icon_style" type="radio" value="1" nhp-opts-button-hide-below <?php checked( 1, $social_icon_style ); ?> />
										<label id="bdp-options-button" for="social_icon_style_1" <?php checked( 1, $social_icon_style ); ?>><?php esc_html_e( 'Square', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class ="social_share_options size_social_icon">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Size of Social Icon', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select size of social icon', 'blog-designer-pro' ); ?></span></span>
									<?php
									$social_icon_size = isset( $bdp_settings['social_icon_size'] ) ? $bdp_settings['social_icon_size'] : '0';
									?>
									<fieldset class="bdp-social-size buttonset buttonset-hide green bdp-social-icon-size" data-hide='1'>
										<input id="social_icon_size_2" name="social_icon_size" type="radio" value="2" <?php checked( 2, $social_icon_size ); ?> />
										<label id="bdp-options-button" for="social_icon_size_2" <?php checked( 2, $social_icon_size ); ?>><?php esc_html_e( 'Extra Small', 'blog-designer-pro' ); ?></label>
										<input id="social_icon_size_1" name="social_icon_size" type="radio" value="1" <?php checked( 1, $social_icon_size ); ?> />
										<label id="bdp-options-button" for="social_icon_size_1" <?php checked( 1, $social_icon_size ); ?>><?php esc_html_e( 'Small', 'blog-designer-pro' ); ?></label>
										<input id="social_icon_size_0" name="social_icon_size" type="radio" value="0" <?php checked( 0, $social_icon_size ); ?> />
										<label id="bdp-options-button" for="social_icon_size_0" <?php checked( 0, $social_icon_size ); ?>><?php esc_html_e( 'Large', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class ="social_share_options default_icon_layouts">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Available Icon Themes', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon bdp-tooltips-icon-social"><span class="bdp-tooltips"><?php esc_html_e( 'Select icon theme from available icon theme', 'blog-designer-pro' ); ?></span></span>
									<?php
									$default_icon_theme = 1;
									if ( isset( $bdp_settings['default_icon_theme'] ) ) {
										$default_icon_theme = $bdp_settings['default_icon_theme'];
									}
									?>
									<div class="social-share-theme">
										<?php
										for ( $i = 1; $i <= 10; $i++ ) {
											?>
											<div class="social-cover social_share_theme_<?php echo esc_attr( $i ); ?>">
												<label><input type="radio" id="default_icon_theme_<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $i ); ?>" name="default_icon_theme" <?php checked( $i, $default_icon_theme ); ?> />
													<span class="bdp-social-icons facebook-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons twitter-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons linkdin-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons pin-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons whatsup-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons telegram-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons pocket-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons mail-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons reddit-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons digg-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons tumblr-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons skype-icon bdp_theme_wrapper"></span>
													<span class="bdp-social-icons wordpress-icon bdp_theme_wrapper"></span>
												</label>
											</div>
											<?php
										}
										?>
									</div>
								</div>
							</li>
							<h3 class="bdp-table-title social_share_options bdp-display-settings bdp-social-share-options">Social Share Links Settings</h3>
							<li class ="social_share_options bdp-display-settings bdp-social-share-options">
								
								<div class="bdp-typography-wrapper bdp-social-share-link">

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Facebook Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable facebook share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$facebook_link = isset( $bdp_settings['facebook_link'] ) ? $bdp_settings['facebook_link'] : 1;
											?>
											<fieldset class="bdp-social-options bdp-facebook_link buttonset buttonset-hide" data-hide='1'>
												<input id="facebook_link_1" name="facebook_link" type="radio" value="1" <?php checked( 1, $facebook_link ); ?> />
												<label id=""for="facebook_link_1" <?php checked( 1, $facebook_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="facebook_link_0" name="facebook_link" type="radio" value="0" <?php checked( 0, $facebook_link ); ?> />
												<label id="" for="facebook_link_0" <?php checked( 0, $facebook_link ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="social_link_with_count">
												<input id="facebook_link_with_count" name="facebook_link_with_count" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['facebook_link_with_count'] ) ) {
													checked( 1, $bdp_settings['facebook_link_with_count'] );
												}
												?>
												/>
												<?php esc_html_e( 'Hide Facebook Share Count', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Twitter Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable twitter share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$twitter_link = isset( $bdp_settings['twitter_link'] ) ? $bdp_settings['twitter_link'] : 1;
											?>
											<fieldset class="bdp-social-options bdp-twitter_link buttonset buttonset-hide" data-hide='1'>
												<input id="twitter_link_1" name="twitter_link" type="radio" value="1" <?php checked( 1, $twitter_link ); ?> />
												<label for="twitter_link_1" <?php checked( 1, $twitter_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="twitter_link_0" name="twitter_link" type="radio" value="0" <?php checked( 0, $twitter_link ); ?> />
												<label for="twitter_link_0" <?php checked( 0, $twitter_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Linkedin Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable linkedin share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php
											$linkedin_link = isset( $bdp_settings['linkedin_link'] ) ? $bdp_settings['linkedin_link'] : 1;
											?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset buttonset-hide" data-hide='1'>
												<input id="linkedin_link_1" name="linkedin_link" type="radio" value="1" <?php checked( 1, $linkedin_link ); ?> />
												<label for="linkedin_link_1" <?php checked( 1, $linkedin_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="linkedin_link_0" name="linkedin_link" type="radio" value="0" <?php checked( 0, $linkedin_link ); ?> />
												<label for="linkedin_link_0" <?php checked( 0, $linkedin_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Pinterest Share link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable pinterest share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $pinterest_link = isset( $bdp_settings['pinterest_link'] ) ? $bdp_settings['pinterest_link'] : 1; ?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset buttonset-hide" data-hide='1'>
												<input id="pinterest_link_1" name="pinterest_link" type="radio" value="1" <?php checked( 1, $pinterest_link ); ?> />
												<label for="pinterest_link_1" <?php checked( 1, $pinterest_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="pinterest_link_0" name="pinterest_link" type="radio" value="0" <?php checked( 0, $pinterest_link ); ?> />
												<label for="pinterest_link_0" <?php checked( 0, $pinterest_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
											<label class="social_link_with_count">
												<input id="pinterest_link_with_count" name="pinterest_link_with_count" type="checkbox" value="1" 
												<?php
												if ( isset( $bdp_settings['pinterest_link_with_count'] ) ) {
													checked( 1, $bdp_settings['pinterest_link_with_count'] );
												}
												?>
												/>
												<?php esc_html_e( 'Hide Pinterest Share Count', 'blog-designer-pro' ); ?>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Show Pinterest on Featured Image', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable pinterest share button on feature image', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<label>
												<?php $pinterest_image_share = isset( $bdp_settings['pinterest_image_share'] ) ? $bdp_settings['pinterest_image_share'] : 1; ?>
												<fieldset class="bdp-social-options bdp-linkedin_link buttonset buttonset-hide" data-hide='1'>
													<input id="pinterest_image_share_1" name="pinterest_image_share" type="radio" value="1" <?php checked( 1, $pinterest_image_share ); ?> />
													<label for="pinterest_image_share_1" <?php checked( 1, $pinterest_image_share ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
													<input id="pinterest_image_share_0" name="pinterest_image_share" type="radio" value="0" <?php checked( 0, $pinterest_image_share ); ?> />
													<label for="pinterest_image_share_0" <?php checked( 0, $pinterest_image_share ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
												</fieldset>
											</label>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Skype Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable skype share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $skype_link = isset( $bdp_settings['skype_link'] ) ? $bdp_settings['skype_link'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-twitter_link buttonset buttonset-hide" data-hide='1'>
												<input id="skype_link_1" name="skype_link" type="radio" value="1" <?php checked( 1, $skype_link ); ?> />
												<label for="skype_link_1" <?php checked( 1, $skype_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="skype_link_0" name="skype_link" type="radio" value="0" <?php checked( 0, $skype_link ); ?> />
												<label for="skype_link_0" <?php checked( 0, $skype_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Pocket Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable pocket share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<?php $pocket_link = isset( $bdp_settings['pocket_link'] ) ? $bdp_settings['pocket_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-pocket_link buttonset buttonset-hide" data-hide='1'>
												<input id="pocket_link_1" name="pocket_link" type="radio" value="1" <?php checked( 1, $pocket_link ); ?> />
												<label for="pocket_link_1" <?php checked( 1, $pocket_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="pocket_link_0" name="pocket_link" type="radio" value="0" <?php checked( 0, $pocket_link ); ?> />
												<label for="pocket_link_0" <?php checked( 0, $pocket_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Telegram Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable telegram share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<?php $telegram_link = isset( $bdp_settings['telegram_link'] ) ? $bdp_settings['telegram_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-telegram_link buttonset buttonset-hide" data-hide='1'>
												<input id="telegram_link_1" name="telegram_link" type="radio" value="1" <?php checked( 1, $telegram_link ); ?> />
												<label for="telegram_link_1" <?php checked( 1, $telegram_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="telegram_link_0" name="telegram_link" type="radio" value="0" <?php checked( 0, $telegram_link ); ?> />
												<label for="telegram_link_0" <?php checked( 0, $telegram_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Reddit Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable reddit share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<?php $reddit_link = isset( $bdp_settings['reddit_link'] ) ? $bdp_settings['reddit_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-reddit_link buttonset buttonset-hide" data-hide='1'>
												<input id="reddit_link_1" name="reddit_link" type="radio" value="1" <?php checked( 1, $reddit_link ); ?> />
												<label for="reddit_link_1" <?php checked( 1, $reddit_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="reddit_link_0" name="reddit_link" type="radio" value="0" <?php checked( 0, $reddit_link ); ?> />
												<label for="reddit_link_0" <?php checked( 0, $reddit_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Digg Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable digg share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<?php $digg_link = isset( $bdp_settings['digg_link'] ) ? $bdp_settings['digg_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-reddit_link buttonset buttonset-hide" data-hide='1'>
												<input id="digg_link_1" name="digg_link" type="radio" value="1" <?php checked( 1, $digg_link ); ?> />
												<label for="digg_link_1" <?php checked( 1, $digg_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="digg_link_0" name="digg_link" type="radio" value="0" <?php checked( 0, $digg_link ); ?> />
												<label for="digg_link_0" <?php checked( 0, $digg_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Tumblr Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable tumblr share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<?php $tumblr_link = isset( $bdp_settings['tumblr_link'] ) ? $bdp_settings['tumblr_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-tumblr_link buttonset buttonset-hide" data-hide='1'>
												<input id="tumblr_link_1" name="tumblr_link" type="radio" value="1" <?php checked( 1, $tumblr_link ); ?> />
												<label for="tumblr_link_1" <?php checked( 1, $tumblr_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="tumblr_link_0" name="tumblr_link" type="radio" value="0" <?php checked( 0, $tumblr_link ); ?> />
												<label for="tumblr_link_0" <?php checked( 0, $tumblr_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'WordPress Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable WordPress share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<?php $wordpress_link = isset( $bdp_settings['wordpress_link'] ) ? $bdp_settings['wordpress_link'] : '0'; ?>
										<div class="bdp-typography-content">
											<fieldset class="bdp-social-options bdp-wordpress_link buttonset buttonset-hide" data-hide='1'>
												<input id="wordpress_link_1" name="wordpress_link" type="radio" value="1" <?php checked( 1, $wordpress_link ); ?> />
												<label for="wordpress_link_1" <?php checked( 1, $wordpress_link ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="wordpress_link_0" name="wordpress_link" type="radio" value="0" <?php checked( 0, $wordpress_link ); ?> />
												<label for="wordpress_link_0" <?php checked( 0, $wordpress_link ); ?>><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'Share via Mail', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable mail share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $email_link = isset( $bdp_settings['email_link'] ) ? $bdp_settings['email_link'] : 0; ?>
											<fieldset class="bdp-social-options bdp-linkedin_link buttonset">
												<input id="email_link_1" class="bdp-opts-button" name="email_link" type="radio" value="1" <?php checked( 1, $email_link ); ?> />
												<label id="bdp-opts-button" for="email_link_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="email_link_0" class="bdp-opts-button" name="email_link" type="radio" value="0" <?php checked( 0, $email_link ); ?> />
												<label id="bdp-opts-button" for="email_link_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bdp-typography-cover">
										<div class="bdp-typography-label">
											<span class="bdp-key-title">
												<?php esc_html_e( 'WhatsApp Share Link', 'blog-designer-pro' ); ?>
											</span>
											<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable whatsapp share link', 'blog-designer-pro' ); ?></span></span>
										</div>
										<div class="bdp-typography-content">
											<?php $whatsapp_link = isset( $bdp_settings['whatsapp_link'] ) ? $bdp_settings['whatsapp_link'] : '0'; ?>
											<fieldset class="bdp-social-options bdp-whatsapp_link buttonset">
												<input id="whatsapp_link_1" class="bdp-opts-button" name="whatsapp_link" type="radio" value="1" <?php checked( 1, $whatsapp_link ); ?> />
												<label id="bdp-opts-button" for="whatsapp_link_1"><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
												<input id="whatsapp_link_0" class="bdp-opts-button" name="whatsapp_link" type="radio" value="0" <?php checked( 0, $whatsapp_link ); ?> />
												<label id="bdp-opts-button" for="whatsapp_link_0"><?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
											</fieldset>
										</div>
									</div>

								</div>
							</li>
							<li class="social_share_options fb_access_token_div">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Facebook Access Token', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Facebook access token', 'blog-designer-pro' ); ?></span></span>
									<?php
									$facebook_token = '';
									if ( isset( $bdp_settings['facebook_token'] ) ) {
										$facebook_token = $bdp_settings['facebook_token'];
									}
									?>
									<input type="text" name="facebook_token" id="facebook_token" value="<?php echo esc_attr( $facebook_token ); ?>" >
								</div>
							</li>
							<li class ="social_share_options mail_share_content">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Mail Share Content', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Mail share content', 'blog-designer-pro' ); ?></span></span>
									<?php $mail_subject = ( isset( $bdp_settings['mail_subject'] ) && '' != $bdp_settings['mail_subject'] ) ? $bdp_settings['mail_subject'] : '[post_title]'; //phpcs:ignore ?>
									<input type="text" name="mail_subject" id="mail_subject" value="<?php echo esc_attr( $mail_subject ); ?>" placeholder="<?php esc_attr_e( 'Enter Mail Subject', 'blog-designer-pro' ); ?>">

									<?php
									$settings = array(
										'wpautop'       => true,
										'media_buttons' => true,
										'textarea_name' => 'mail_content',
										'textarea_rows' => 10,
										'tabindex'      => '',
										'editor_css'    => '',
										'editor_class'  => '',
										'teeny'         => false,
										'dfw'           => false,
										'tinymce'       => true,
										'quicktags'     => true,
									);
									if ( isset( $bdp_settings['mail_content'] ) && '' != $bdp_settings['mail_content'] ) { //phpcs:ignore
										$contents = $bdp_settings['mail_content'];
									} else {
										$contents = esc_html__( 'My Dear friends', 'blog-designer-pro' ) . '<br/><br/>' . esc_html__( 'I read one good blog link and I would like to share that same link for you. That might useful for you', 'blog-designer-pro' ) . '<br/><br/>[post_link]<br/><br/>' . esc_html__( 'Best Regards', 'blog-designer-pro' ) . ',<br/>' . esc_html__( 'Blog Designer', 'blog-designer-pro' );
									}

									wp_editor( html_entity_decode( $contents ), 'mail_content', $settings );
									?>
									<div class="div-pre">
										<p> [post_title] => <?php esc_html_e( 'Product Title', 'blog-designer-pro' ); ?> </p>
										<p> [post_link] => <?php esc_html_e( 'Product Link', 'blog-designer-pro' ); ?> </p>
										<p> [post_thumbnail] => <?php esc_html_e( 'Product Featured Image', 'blog-designer-pro' ); ?> </p>
										<p> [sender_name] => <?php esc_html_e( 'Mail Sender Name', 'blog-designer-pro' ); ?> </p>
										<p> [sender_email] => <?php esc_html_e( 'Mail Sender Email Address', 'blog-designer-pro' ); ?> </p>
									</div>
								</div>
							</li>
							<li class="social_share_options mail_share_content">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Remove Reply to email', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
									<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable Replay to email', 'blog-designer-pro' ); ?></span></span>
									<?php
									$reply_to_mail = isset( $bdp_settings['reply_to_mail'] ) ? $bdp_settings['reply_to_mail'] : 0;
									?>
									<fieldset class="bdp-social-options buttonset buttonset-hide" data-hide='1'>
										<input id="reply_to_mail_1" name="reply_to_mail" type="radio" value="1" <?php checked( 1, $reply_to_mail ); ?> />
										<label id="" for="reply_to_mail_1" <?php checked( 1, $reply_to_mail ); ?>><?php esc_html_e( 'Yes', 'blog-designer-pro' ); ?></label>
										<input id="reply_to_mail_0" name="reply_to_mail" type="radio" value="0" <?php checked( 0, $reply_to_mail ); ?> />
										<label id="" for="reply_to_mail_0" <?php checked( 0, $reply_to_mail ); ?>> <?php esc_html_e( 'No', 'blog-designer-pro' ); ?></label>
									</fieldset>
								</div>
							</li>
							<li class ="social_share_options">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share Count Position', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select social share count position', 'blog-designer-pro' ); ?></span></span>
									<?php
									$social_count_position = 'bottom';
									if ( isset( $bdp_settings['social_count_position'] ) ) {
										$social_count_position = $bdp_settings['social_count_position'];
									}
									?>
									<div class="typo-field">
										<select name="social_count_position" id="social_sharecount">
											<option value="bottom" <?php echo selected( 'bottom', $social_count_position ); ?>><?php esc_html_e( 'Bottom', 'blog-designer-pro' ); ?></option>
											<option value="right" <?php echo selected( 'right', $social_count_position ); ?>><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></option>
											<option value="top" <?php echo selected( 'top', $social_count_position ); ?>><?php esc_html_e( 'Top', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</div>
							</li>
							<li class ="social_share_options">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share Text', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right social-share-section">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enter text for share product', 'blog-designer-pro' ); ?></span></span>
									<?php
									$txt_social_icon = isset( $bdp_settings['txtSocialIcon'] ) ? sanitize_text_field( $bdp_settings['txtSocialIcon'] ) : 'fas fa-share';
									$txt_social_text = isset( $bdp_settings['txtSocialText'] ) ? sanitize_text_field( $bdp_settings['txtSocialText'] ) : esc_html__( 'Share This Product', 'blog-designer-pro' );
									?>
									<input class="icon" type="text" id="txtSocialIcon" name="txtSocialIcon" value="<?php echo esc_attr( $txt_social_icon ); ?>" placeholder="<?php echo esc_html_e( 'Enter font awesome class', 'blog-designer-pro' ); ?>">
									<input class="text" type="text" id="txtSocialText" name="txtSocialText" value="<?php echo esc_attr( $txt_social_text ); ?>" placeholder="<?php esc_html_e( 'Enter text for share product', 'blog-designer-pro' ); ?>">
									<div class="bdp-setting-description bdp-note">
										<b class="note"><?php esc_html_e( 'Note', 'blog-designer-pro' ); ?>:</b>
										<?php echo esc_html__( 'To find font awesome class,', 'blog-designer-pro' ) . ' <a href="https://fontawesome.com/icons" target="_blank">' . esc_html__( 'click here', 'blog-designer-pro' ) . '</a>'; ?>
									</div>
									<div id="dialogbox" class="dialogbox single_layout" title="<?php esc_html_e( 'Select Icon', 'blog-designer-pro' ); ?>" style="display:none">
										<div class="icon_div"> </div>
									</div>
							</li>
							<li class ="social_share_options">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share Text Font', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select social share text font family', 'blog-designer-pro' ); ?></span></span>
									<div class="typo-field">
										<input type="hidden" name="txtSocialTextFont_font_type" id="txtSocialTextFont_font_type" value="<?php echo isset( $bdp_settings['txtSocialTextFont_font_type'] ) ? esc_attr( $bdp_settings['txtSocialTextFont_font_type'] ) : 'Serif Fonts'; ?>">
										<select name="txtSocialTextFont" id="txtSocialTextFont">
											<option value=""><?php esc_html_e( 'Select Font Family', 'blog-designer-pro' ); ?></option>
											<?php
											$txt_social_text_font = '';
											if ( isset( $bdp_settings['txtSocialTextFont'] ) ) {
												$txt_social_text_font = $bdp_settings['txtSocialTextFont'];
											}
											$old_version = '';
											$cnt         = 0;
											foreach ( $font_family as $key => $value ) {
												if ( $value['version'] != $old_version ) { //phpcs:ignore
													if ( $cnt > 0 ) {
														echo '</optgroup>';
													}
													echo '<optgroup label="' . esc_attr( $value['version'] ) . '">';
													$old_version = $value['version'];
												}
												echo "<option value='" . esc_attr( str_replace( '"', '', $value['label'] ) ) . "'";
												if ( '' != $txt_social_text_font && ( str_replace( '"', '', $txt_social_text_font ) == str_replace( '"', '', $value['label'] ) ) ) { //phpcs:ignore
													echo ' selected';
												}
												echo '>' . esc_html( $value['label'] ) . '</option>';
												$cnt++;
											}
											if ( $cnt == count( $font_family ) ) { //phpcs:ignore
												echo '</optgroup>';
											}
											?>
										</select>
									</div>
								</div>
							</li>
							<li class ="social_share_options">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share Text Size', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right">
										<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select social share text font size', 'blog-designer-pro' ); ?></span></span>
									<?php
									if ( isset( $bdp_settings['txtSocialTextSize'] ) ) {
										$txt_social_text_size = $bdp_settings['txtSocialTextSize'];
									} else {
										$txt_social_text_size = 18;
									}
									?>
									<div class="grid_col_space range_slider_fontsize" id="social_share_fontsize" data-value="<?php echo esc_attr( $txt_social_text_size ); ?>"></div>
									<div class="slide_val"><span></span><input class="grid_col_space_val range-slider__value" name="txtSocialTextSize" id="txtSocialTextSize" value="<?php echo esc_attr( $txt_social_text_size ); ?>" onkeypress="return isNumberKey(event)" /></div>
								</div>
							</li>
							<li class="social_share_options social_share_position_option">
								<div class="bdp-left">
									<span class="bdp-key-title">
										<?php esc_html_e( 'Social Share Position', 'blog-designer-pro' ); ?>
									</span>
								</div>
								<div class="bdp-right"><span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Select social share position', 'blog-designer-pro' ); ?></span></span>
									<?php
									$social_share_position = 'left';
									if ( isset( $bdp_settings['social_share_position'] ) ) {
										$social_share_position = $bdp_settings['social_share_position'];
									}
									?>
									<div class="typo-field">
										<select name="social_share_position" id="social_share_position">
											<option value="left" <?php echo selected( 'left', $social_share_position ); ?>><?php esc_html_e( 'Left', 'blog-designer-pro' ); ?></option>
											<option value="center" <?php echo selected( 'center', $social_share_position ); ?>><?php esc_html_e( 'Center', 'blog-designer-pro' ); ?></option>
											<option value="right" <?php echo selected( 'right', $social_share_position ); ?>><?php esc_html_e( 'Right', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div id="popupdiv-single" class="bdp-template-popupdiv" style="display:none">
		<?php
		foreach ( $tempate_list as $key => $value ) {
			$classes = explode( ' ', $value['class'] );
			foreach ( $classes as $class ) {
				$all_class[] = $class;
			}
		}
		$count = array_count_values( $all_class );
		?>
		<ul class="bdp_template_tab">
			<li class="current_tab">
				<a href="#all"><?php esc_html_e( 'All', 'blog-designer-pro' ); ?></a>
			</li>
			<li><a href="#full-width"><?php echo esc_html__( 'Full Width', 'blog-designer-pro' ) . ' (' . esc_html( $count['full-width'] ) . ')'; ?></a></li>
			<li><a href="#grid"><?php echo esc_html__( 'Grid', 'blog-designer-pro' ) . ' (' . esc_html( $count['grid'] ) . ')'; ?></a></li>
			<li><a href="#masonry"><?php echo esc_html__( 'Masonry', 'blog-designer-pro' ) . ' (' . esc_html( $count['masonry'] ) . ')'; ?></a></li>
			<li><a href="#magazine"><?php echo esc_html__( 'Magazine', 'blog-designer-pro' ) . ' (' . esc_html( $count['magazine'] ) . ')'; ?></a></li>
			<li><a href="#timeline"><?php echo esc_html__( 'Timeline', 'blog-designer-pro' ) . ' (' . esc_html( $count['timeline'] ) . ')'; ?></a></li>
			<li><a href="#slider"><?php echo esc_html__( 'Slider', 'blog-designer-pro' ) . ' (' . esc_html( $count['slider'] ) . ')'; ?></a></li>
			<div class="bdp-single-blog-template-search-cover">
				<input type="text" class="bdp-template-search" id="bdp-template-search" placeholder="<?php esc_html_e( 'Search Template', 'blog-designer-pro' ); ?>" />
				<span class="bdp-template-search-clear"></span>
			</div>
		</ul>
		<?php
		echo '<div class="bdp-blog-template-cover">';
		foreach ( $tempate_list as $key => $value ) {
			?>
            <div class="template-thumbnail <?php echo esc_attr( $value['class'] ); ?>" <?php echo ( isset( $value['data'] ) && '' != $value['data'] ) ? 'data-value="' . esc_attr( $value['data'] ) . '"' : ''; //phpcs:ignore ?>>
				<div class="template-thumbnail-inner">
					<img src="<?php echo esc_attr( BLOGDESIGNERPRO_URL ) . '/admin/images/single/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
					<div class="hover_overlay">
						<div class="popup-template-name">
							<div class="popup-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer-pro' ); ?></a></div>
							<div class="popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer-pro' ); ?></a></div>
						</div>
					</div>
				</div>
				<span class="bdp-span-template-name"><?php echo esc_html( $value['template_name'] ); ?></span>
			</div>
			<?php
		}
		echo '</div>';
		echo '<h3 class="no-template" style="display: none;">' . esc_html__( 'No template found. Please try again', 'blog-designer-pro' ) . '</h3>';
		?>
	</div>
</div>
