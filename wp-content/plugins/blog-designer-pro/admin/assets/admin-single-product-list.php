<?php
/**
 * Adin Single Product List
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
global $wpdb;
$paged = filter_input( INPUT_GET, 'paged' ) ? absint( filter_input( INPUT_GET, 'paged' ) ) : 1; //phpcs:ignore
if ( ! is_numeric( $paged ) ) {
	$paged = 1; //phpcs:ignore
}
$limit         = 10;
$user          = get_current_user_id();
$screen        = get_current_screen();
$screen_option = $screen->get_option( 'per_page', 'option' );
$limit         = get_user_meta( $user, $screen_option, true );
if ( empty( $limit ) || $limit < 1 ) {
	// get the default value if none is set.
	$limit = $screen->get_option( 'per_page', 'default' );
}
$offset = ( $paged - 1 ) * $limit;

$where  = '';
$search = ''; //phpcs:ignore
if ( isset( $_REQUEST['s'] ) && '' != $_REQUEST['s'] ) { //phpcs:ignore
	$search = esc_attr( $_REQUEST['s'] ); //phpcs:ignore
	$where  = "WHERE single_product_name LIKE '%$search%'";
}

if ( isset( $_POST['btnSearchShortcode'] ) || ( isset( $_POST['s'] ) && '' != $_POST['s'] ) ) { //phpcs:ignore
	$delete_action = '';
	if ( isset( $_POST['take_action'] ) && isset( $_POST['bdp-action-top'] ) ) {
		$delete_action = 'multiple_delete';
	}
	?>
	<script type="text/javascript">
		var paged = '<?php echo esc_attr( $paged ); ?>';
		var s = ['<?php echo esc_attr( $search ); ?>'];
		var action = ['<?php echo esc_attr( $delete_action ); ?>'];
		window.location = "?page=single_product_layouts&paged=" + paged + "&s=" + s + "&action=" + action;
	</script>
	<?php
}
$ord = 0;
if ( isset( $_REQUEST['orderby'] ) && 0 == $_REQUEST['order'] ) { //phpcs:ignore
	$order_by    = 'desc';
	$ord         = 1;
	$order_field = 'single_product_name';
} elseif ( isset( $_REQUEST['orderby'] ) && 1 == $_REQUEST['order'] ) { //phpcs:ignore
	$order_by    = 'asc';
	$ord         = 0;
	$order_field = 'single_product_name';
} else {
	$order_by    = 'desc';
	$order_field = 'id';
}


$total        = $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . $wpdb->prefix . 'bdp_single_product ' . $where ); //phpcs:ignore
$num_of_pages = ceil( $total / $limit );

$next_page = (int) $paged + 1;

if ( $next_page > $num_of_pages ) {
	$next_page = $num_of_pages;
}
$prev_page = (int) $paged - 1;
if ( $prev_page < 1 ) {
	$prev_page = 1;
}
// Get the shortcode information.
$shortcodes = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'bdp_single_product ' . $where . ' order by ' . $order_field . ' ' . $order_by . ' limit ' . $offset . ', ' . $limit ); //phpcs:ignore
?>
<div class="bdp-admin wrap bdp-single-list">
	<?php
	if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['id'] ) && ! empty( $_GET['id'] ) && isset( $_GET['page'] ) && 'single_product_layouts' === $_GET['page'] && wp_verify_nonce( $_GET['_wpnonce'] ) ) { //phpcs:ignore
		?>
		<div class="updated">
			<p>
				<?php esc_html_e( 'Single Layout deleted successfully.', 'blog-designer-pro' ); ?>
			</p>
		</div>
		<?php
	}
	if ( isset( $_POST['bdp-action-top'] ) && 'delete_pr' === esc_html( $_POST['bdp-action-top'] ) ) { //phpcs:ignore
		if ( isset( $_POST['chk_remove_all'] ) && ! empty( $_POST['chk_remove_all'] ) ) {
			?>
			<div class="updated">
				<p>
					<?php esc_html_e( 'Layouts are deleted successfully.', 'blog-designer-pro' ); ?>
				</p>
			</div>
			<?php
		}
	}
	if ( isset( $_GET['msg'] ) || ( isset( $_GET['action'] ) ) && 'multiple_delete' === $_GET['action'] ) { //phpcs:ignore
		?>
		<div class="updated">
			<p>
				<?php
				if ( isset( $_GET['action'] ) && 'multiple_delete' === $_GET['action'] ) {
					esc_html_e( 'Layouts are deleted successfully.', 'blog-designer-pro' );
				}
				if ( isset( $_GET['msg'] ) && 'added' === $_GET['msg'] ) {
					esc_html_e( 'Designer Settings Added.', 'blog-designer-pro' );
				}
				if ( isset( $_GET['msg'] ) && 'updated' === $_GET['msg'] ) {
					esc_html_e( 'Designer Settings updated.', 'blog-designer-pro' );
				}
				?>
			</p>
		</div>
		<?php
	}
	?>
	<!-- Create new Shortcode button -->
	<h1>
		<?php esc_html_e( 'Single Product Layouts', 'blog-designer-pro' ); ?>
		<a class="page-title-action" href="?page=single_product">
			<?php esc_html_e( 'Create New Single Layout', 'blog-designer-pro' ); ?>
		</a>
	</h1>
	<form method="post">
		<ul class="subsubsub">
			<li class="all">
				<a class="current" href="?page=single_product_layouts"><?php esc_html_e( 'All', 'blog-designer-pro' ); ?>
					<span class="count">(
					<?php
					if ( $total > 0 ) {
						echo esc_html( $total );
					} else {
						echo '0';
					}
					?>
						)
					</span>
				</a>
			</li>
		</ul>
		<p class="search-box">
			<input id="shortcode-search-input" type="search" value="<?php echo esc_attr( $search ); ?>" name="s">
			<input id="search-submit" class="button" type="submit" name="btnSearchShortcode" value="<?php esc_attr_e( 'Search Layout', 'blog-designer-pro' ); ?>">
		</p>
		<div class="tablenav top">
			<select name="bdp-action-top">
				<option selected="selected" value="none"><?php esc_html_e( 'Bulk Actions', 'blog-designer-pro' ); ?></option>
				<option value="delete_pr"><?php esc_html_e( 'Delete Permanently', 'blog-designer-pro' ); ?></option>
				<option value="bdp_export"><?php esc_html_e( 'Export Layout', 'blog-designer-pro' ); ?></option>
			</select>
			<input id="take_action" name="take_action" class="button action" type="submit" value="<?php esc_attr_e( 'Apply', 'blog-designer-pro' ); ?>" >
			<div class="tablenav-pages" 
			<?php
			if ( (int) $num_of_pages <= 1 ) {
				echo 'style="display:none;"';
			}
			?>
			>
				<span class="displaying-num"><?php echo number_format_i18n( $total ) . ' ' . sprintf( _n( 'item', 'items', $total, 'blog-designer-pro' ), number_format_i18n( $total ) );; //phpcs:ignore ?></span>
				<span class="pagination-links">
					<?php if ( '1' == $paged ) { ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
					<?php } else { ?>
						<a class="first-page" href="<?php echo '?page=single_product_layouts&paged=1&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the first page', 'blog-designer-pro' ); ?>">&laquo;</a>
						<a class="prev-page" href="<?php echo '?page=single_product_layouts&paged=' . esc_attr( $prev_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the previous page', 'blog-designer-pro' ); ?>">&lsaquo;</a>
					<?php } ?>
					<span class="paging-input">
						<span class="total-pages"><?php echo esc_html( $paged ); ?></span>
						<?php esc_html_e( 'of', 'blog-designer-pro' ); ?>
						<span class="total-pages"><?php echo esc_html( $num_of_pages ); ?></span>
					</span>
					<?php if ( $paged == $num_of_pages ) { //phpcs:ignore ?>
						<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
						<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
					<?php } else { ?>
						<a class="next-page " href="<?php echo '?page=single_product_layouts&paged=' . esc_attr( $next_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the next page', 'blog-designer-pro' ); ?>">&rsaquo;</a>
						<a class="last-page " href="<?php echo '?page=single_product_layouts&paged=' . esc_attr( $num_of_pages ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the last page', 'blog-designer-pro' ); ?>">&raquo;</a>
					<?php } ?>
				</span>
			</div>
		</div>
		<table class="wp-list-table widefat fixed striped bdp-sliders-list bdp-table bdp-sliders-list bdp-table">
			<thead>
				<tr>
					<td class="manage-column column-cb check-column" id="cb"><input type="checkbox" name="delete-all-shortcodes-1" id="delete-all-shortcodes-1" value="0"></td>
					<th class="manage-column column-single_product_name column-primary column-title sorted <?php echo esc_attr( $order_by ); ?>" scope="col" id="single_product_name">
						<a href="?page=single_product_layouts&orderby=single_product_name&order=<?php echo esc_attr( $ord ); ?>">
							<span><?php esc_html_e( 'Single Layout Name', 'blog-designer-pro' ); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th class="manage-column column-template-name" id="template_name"><?php esc_html_e( 'Template Name', 'blog-designer-pro' ); ?></th>
					<th class="manage-column column-categories" id="shortcode_categories" ><?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?></th>
					<th class="manage-column column-tags" id="shortcode_tags" ><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?></th>
				</tr>
			</thead>
			<tbody id="the_list">
				<?php
				if ( ! $shortcodes ) {
					echo '<tr>';
					echo '<td colspan="5" style="text-align: center;">';
					esc_html_e( 'No Single Layout found.', 'blog-designer-pro' );
					echo '</td>';
					echo '</tr>';
				} else {
					$shortcode_cnt = 0;
					foreach ( $shortcodes as $shortcode ) {
						$allsettings = $shortcode->settings;
						if ( is_serialized( $allsettings ) ) {
							$bdp_settings = unserialize( $allsettings ); //phpcs:ignore
						}
						$cat = '—'; //phpcs:ignore
						$tag = '—'; //phpcs:ignore
						if ( isset( $shortcode->single_product_template ) && 'tag' === $shortcode->single_product_template ) {
							if ( isset( $bdp_settings['template_tags'] ) && ! empty( $bdp_settings['template_tags'] ) ) {
								$tags = $bdp_settings['template_tags'];
								$tag  = array(); //phpcs:ignore
								foreach ( $tags as $t ) {
									$tag_name = get_term_by( 'id', $t, 'product_tag' );
									$tag[]    = $tag_name->name; //phpcs:ignore
								}
								$tag = join( ', ', $tag ); //phpcs:ignore
							}
						}
						if ( isset( $shortcode->single_product_template ) && 'category' === $shortcode->single_product_template ) {
							if ( isset( $bdp_settings['template_category'] ) && ! empty( $bdp_settings['template_category'] ) ) {
								$categories = $bdp_settings['template_category']; //phpcs:ignore
								$cat        = array(); //phpcs:ignore
								foreach ( $categories as $t ) {
									$cat_name = get_term_by( 'id', $t, 'product_cat' );
									$cat[]    = $cat_name->name; //phpcs:ignore
								}
								$cat = join( ', ', $cat ); //phpcs:ignore
							}
						}
						$single_product_name = $shortcode->single_product_name;
						$shortcode_cnt++;
						echo '<tr>';
						?>
					<th class="check-column">
						<input type="checkbox" class="chk_remove_all" name="chk_remove_all[]" id="chk_remove_all" value="<?php echo esc_attr( $shortcode->id ); ?>">
					</th>
					<td class="title column-title column-primary">
						<strong>
							<a href="<?php echo '?page=single_product&action=edit&id=' . esc_attr( $shortcode->id ); ?>">
								<?php
								if ( ! empty( $single_product_name ) ) {
									echo esc_html( $single_product_name );
								} else {
									echo '(' . esc_html__( 'no title', 'blog-designer-pro' ) . ')';
								}
								?>
							</a>
						</strong>
						<div class="row-actions">
							<span class="edit">
								<a title="<?php esc_attr_e( 'Edit this item', 'blog-designer-pro' ); ?>" href="<?php echo '?page=single_product&action=edit&id=' . esc_attr( $shortcode->id ); ?>"><?php esc_html_e( 'Edit', 'blog-designer-pro' ); ?></a>
								|
							</span>
							<span class="duplicate">
								<a title="<?php esc_attr_e( 'Duplicate this item', 'blog-designer-pro' ); ?>" href="<?php echo esc_url( add_query_arg( 'action', 'duplicate_single_product_in_edit', admin_url( 'admin.php?layout=' . $shortcode->id ) ) ); //phpcs:ignore ?>"><?php esc_html_e( 'Duplicate', 'blog-designer-pro' ); ?></a>
								|
							</span>
							<span class="delete">
								<a title="<?php esc_attr_e( 'Delete this item', 'blog-designer-pro' ); ?>" href="<?php echo wp_nonce_url( '?page=single_product_layouts&action=delete&id=' . $shortcode->id ); ?>" onclick="return confirm('Do you want to delete this layout?');"><?php esc_html_e( 'Delete', 'blog-designer-pro' ); //phpcs:ignore ?></a>
							</span>
						</div>
						<button class="toggle-row" type="button">
							<span class="screen-reader-text">Show more details</span>
						</button>
					</td>
					<td class="column-template-name">
						<?php
						if ( isset( $bdp_settings['template_name'] ) ) {
							echo esc_html( str_replace( '_', '-', $bdp_settings['template_name'] ) );
						}
						?>
					</td>
						<?php
						echo '<td class="categories column-categories">' . esc_html( $cat ) . '</td>';
						echo '<td class="tags column-tags">' . esc_html( $tag ) . '</td>';
						echo '</tr>';
					}
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td class="manage-column check-column"><input type="checkbox" name="delete-all-shortcodes-2" id="delete-all-shortcodes-2" value="0"></td>
					<td class="manage-column column-single_product_name"><?php esc_html_e( 'Single Layout Name', 'blog-designer-pro' ); ?></td>
					<th class="manage-column column-template-name"><?php esc_html_e( 'Template Name', 'blog-designer-pro' ); ?></th>
					<td class="manage-column column-categories"><?php esc_html_e( 'Categories', 'blog-designer-pro' ); ?></td>
					<td class="manage-column column-tags"><?php esc_html_e( 'Tags', 'blog-designer-pro' ); ?></td>
				</tr>
			</tfoot>
		</table>
		<div class="bottom-delete-form">
			<select name="bdp-action-top2">
				<option selected="selected" value="none"><?php esc_html_e( 'Bulk Actions', 'blog-designer-pro' ); ?></option>
				<option value="delete_pr"><?php esc_html_e( 'Delete Permanently', 'blog-designer-pro' ); ?></option>
				<option value="bdp_export"><?php esc_html_e( 'Export Layout', 'blog-designer-pro' ); ?></option>
			</select>
			<input id="take_action" name="take_action" class="button action" type="submit" value="<?php esc_attr_e( 'Apply', 'blog-designer-pro' ); ?>" >
			<?php if ( $shortcodes ) { ?>
				<div class="tablenav bottom">
					<div class="tablenav-pages" 
					<?php
					if ( (int) $num_of_pages <= 1 ) {
						echo 'style="display:none;"';
					}
					?>
					>
						<span class="displaying-num"><?php echo number_format_i18n( $total ) . ' ' . sprintf( _n( 'item', 'items', $total, 'blog-designer-pro' ), number_format_i18n( $total ) ); //phpcs:ignore ?></span>
						<span class="pagination-links">
							<?php if ( '1' == $paged ) { ?>
								<span class="tablenav-pages-navspan" aria-hidden="true">&laquo;</span>
								<span class="tablenav-pages-navspan" aria-hidden="true">&lsaquo;</span>
							<?php } else { ?>
								<a class="first-page" href="<?php echo '?page=single_product_layouts&paged=1&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the first page', 'blog-designer-pro' ); ?>">&laquo;</a>
								<a class="prev-page" href="<?php echo '?page=single_product_layouts&paged=' . esc_attr( $prev_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the previous page', 'blog-designer-pro' ); ?>">&lsaquo;</a>
							<?php } ?>
							<span class="paging-input">
								<span class="total-pages"><?php echo esc_html( $paged ); ?></span>
								<?php esc_html_e( 'of', 'blog-designer-pro' ); ?>
								<span class="total-pages"><?php echo esc_html( $num_of_pages ); ?></span>
							</span>
							<?php if ( $paged == $num_of_pages ) { //phpcs:ignore ?>
								<span class="tablenav-pages-navspan" aria-hidden="true">&rsaquo;</span>
								<span class="tablenav-pages-navspan" aria-hidden="true">&raquo;</span>
							<?php } else { ?>
								<a class="next-page " href="<?php echo '?page=single_product_layouts&paged=' . esc_attr( $next_page ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the next page', 'blog-designer-pro' ); ?>">&rsaquo;</a>
								<a class="last-page " href="<?php echo '?page=single_product_layouts&paged=' . esc_attr( $num_of_pages ) . '&s=' . esc_attr( $search ); ?>" title="<?php esc_attr_e( 'Go to the last page', 'blog-designer-pro' ); ?>">&raquo;</a>
							<?php } ?>
						</span>
					</div>
				</div>
			<?php } ?>
		</div>
	</form>
</div>
