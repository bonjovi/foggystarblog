<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.solwininfotech.com/
 * @since             1.0.0
 * @package           Blog_Designer_PRO
 *
 * @wordpress-plugin
 * Plugin Name:       Blog Designer PRO
 * Plugin URI:        https://www.solwininfotech.com/product/wordpress-plugins/blog-designer-pro/
 * Description:       Blog Designer PRO is a step ahead WordPress plugin that allows you to modify blog page, single page and archive page layouts and design.
 * Version:           3.3
 * Author:            Solwin Infotech
 * Author URI:        https://www.solwininfotech.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.0
 * Tested up to:      5.8.1
 * Text Domain:       blog-designer-pro
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'BLOGDESIGNERPRO_TEXTDOMAIN', 'blog-designer-pro' );
define( 'BLOGDESIGNERPRO_DIR', plugin_dir_path( __FILE__ ) );
define( 'BLOGDESIGNERPRO_URL', plugins_url() . '/blog-designer-pro' );
update_option('bdp_username', 'username');
update_option('bdp_purchase_code', 'purchase_code');
require_once 'admin/class-bdp-admin-functions.php';
require_once 'admin/class-bdp-utility.php';
require_once 'admin/class-bdp-posts.php';
require_once 'admin/class-bdp-author.php';
require_once 'admin/class-bdp-woocommerce.php';
require_once 'admin/class-bdp-edd.php';
require_once 'admin/class-bdp-template-acf.php';
require_once 'admin/class-bdp-template.php';
require_once 'admin/class-bdp-ajax-actions.php';
require_once 'admin/class-bdp-support.php';
require_once 'admin/class-blog-designer-pro-widget.php';
require_once 'admin/class-bdp-widget-recent-post.php';
require_once 'public/css/single/single_page_dynamic_style.php';

$bdp_admin_page  = false;
$bdp_admin_pages = array( 'layouts', 'archive_layouts', 'add_shortcode', 'single_post', 'bdp_add_archive_layout', 'bdp_add_product_archive_layout', 'single_product', 'bdp_export', 'single_layouts', 'bdp_getting_started', 'designer_welcome_page', 'product_archive_layouts', 'single_product_layouts', 'single_edd_download', 'single_edd_layouts', 'edd_archive_layouts', 'add_edd_archive' );
if ( isset( $_GET['page'] ) && ( in_array( $_GET['page'], $bdp_admin_pages ) ) ) { //phpcs:ignore
	$bdp_admin_page = true;
}
if ( $bdp_admin_page ) {
	add_action( 'admin_notices', array( 'Bdp_Utility', 'admin_notice' ) );
}
$blog_designer_setting                   = get_option( 'wp_blog_designer_settings' );
$create_layout_from_blog_designer_notice = get_option( 'bdp_admin_notice_create_layout_from_blog_designer_dismiss', false );
if ( false == $create_layout_from_blog_designer_notice && '' != $blog_designer_setting ) {  //phpcs:ignore
	if ( $bdp_admin_page ) {
		add_action( 'admin_notices', array( 'Bdp_Template', 'create_layout_from_blog_designer_notice' ) );
	}
} else {
	$sample_layout_notice = get_option( 'bdp_admin_notice_pro_layouts_dismiss', false );
	if ( false == $sample_layout_notice ) { //phpcs:ignore
		if ( $bdp_admin_page ) {
			add_action( 'admin_notices', array( 'Bdp_Template', 'sample_layout_notice' ) );
		}
	}
}
require_once 'public/class-bdp-front-functions.php';
add_action( 'admin_init', 'bdp_activate_au' );

if ( ! function_exists( 'bdp_activate_au' ) ) {
	/**
	 * Add auto update
	 */
	function bdp_activate_au() {
		include_once 'admin/assets/class-bdp-wp-auto-update.php';
		new Bdp_Wp_Auto_Update();
	}
}
if(!function_exists('bdp_remove_more_link')) {
    function bdp_remove_more_link($link) {
        $link = '';
        return $link;
    }
}
require_once 'public/class-bdp-like.php';
require_once 'public/patch-function.php';
