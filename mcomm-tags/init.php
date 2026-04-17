<?php
/*
Plugin Name: Mcomm Tags
Description: This plugin creates seprate tags with category and can be used in whole site.
Version: 1
Author: multidots
*/

// menu items
add_action('admin_menu','add_new_tags');
function add_new_tags() {

	//this is the main item for the menu
	add_menu_page('Mcomm Tags', //page title
		'Mcomm Tags', //menu title
		'manage_options', //capabilities
		'tag_list', //menu slug
		'tag_list' //function
	);

	add_submenu_page('null', //parent slug
		'Add New category', //page title
		'Add New category', //menu title
		'manage_options', //capability
		'category_create', //menu slug
		'category_create');

	//this is a submenu
	add_submenu_page('null', //parent slug
		'Add New Tag', //page title
		'Add New tag', //menu title
		'manage_options', //capability
		'tag_create', //menu slug
		'tag_create'); //function

	//this is a submenu
	add_submenu_page('null', //parent slug
		'Add New Author', //page title
		'Add New Author', //menu title
		'manage_options', //capability
		'author_create', //menu slug
		'author_create'); //function

	add_submenu_page('tag_list', //parent slug
		'Mcomm Categories', //page title
		'Mcomm Categories', //menu title
		'manage_options', //capability
		'category_list', //menu slug
		'category_list'); //function

	add_submenu_page('tag_list', //parent slug
		'Mcomm Author', //page title
		'Mcomm Author', //menu title
		'manage_options', //capability
		'author_list', //menu slug
		'author_list'); //function

	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
		'Update Tag', //page title
		'Update', //menu title
		'manage_options', //capability
		'tag_update', //menu slug
		'tag_update'); //function

	add_submenu_page(null, //parent slug
		'Category Update', //page title
		'Update', //menu title
		'manage_options', //capability
		'category_update', //menu slug
		'category_update');

	add_submenu_page(null, //parent slug
		'Author Update', //page title
		'Update', //menu title
		'manage_options', //capability
		'author_update', //menu slug
		'author_update');
}

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'tag-list.php');
require_once(ROOTDIR . 'tag-update.php');
require_once(ROOTDIR . 'category-update.php');
require_once(ROOTDIR . 'category-list.php');
require_once(ROOTDIR . 'author-update.php');
require_once(ROOTDIR . 'author-list.php');
require_once(ROOTDIR . 'author-create.php');

/**
 * Function for Mcomm tag metabox
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function tag_metabox() {

	$option_name              = 'mcomm_custom_post_type_listing';
	$list_of_custom_post_type = get_option( $option_name );
	$custom_post_type_arr     = json_decode( $list_of_custom_post_type );

	foreach ( $custom_post_type_arr as $screen ) {
		add_meta_box("display-tag-metabox", "Page Tags", "tagsetting", $screen , "side", "high");
	}
}
add_action( 'add_meta_boxes', 'tag_metabox' );

function tagsetting() {
	global $post, $wpdb;
	$rows = $wpdb->get_results("SELECT tags, category from tags ORDER BY category");
	$prevcat = '';
	$tagrows = get_post_meta($post->ID,'tag-rows',true);
	$tagsecrows = get_post_meta($post->ID,'tag-secrows',true);
	$tagsecrows = explode(',',$tagsecrows);
	?>

    <div style="width:100%; overflow-x: hidden; max-height: 250px; padding-right: 4px">
        <table  width="100%">
            <tr><td>Select Primary Tag</td></tr>
            <tr>
                <td>
                    <select name="mcomm-tags">
                        <option>Please select</option>
						<?php
						foreach ($rows as $row) {
							$samecat = $row->category;
							if($prevcat == '' || $prevcat!= $samecat) { ?>
                                <optgroup label="<?php echo $row->category; ?>">
								<?php
							}
							$prevcat = $row->category;
							if($tagrows == $row->tags) {
								?>
                                <option value="<?php echo $row->tags; ?>" selected="selected"><?php echo $row->tags; ?> </option>
							<?php } else { ?>
                                <option value="<?php echo $row->tags; ?>"><?php echo $row->tags; ?> </option>
							<?php }
							if($prevcat == '' || $prevcat!= $samecat) { ?>
                                </optgroup>
								<?php
							}
						} ?>
                    </select>
                </td>
            </tr>
            <tr><td>Select Secondary Tags</td></tr>
            <tr>
                <td>
					<?php
					foreach ($rows as $row) {
						$samecat = $row->category;
						if($prevcat == '' || $prevcat!= $samecat) { ?>
                            <label for="<?php echo $row->category; ?>"><?php echo $row->category; ?></label><br />
							<?php
						}
						$prevcat = $row->category;
						if (in_array($row->tags, $tagsecrows)) {
							?>
                            <input type="checkbox" value="<?php echo $row->tags; ?>" name="mcomm-sectags[]" id="<?php echo $row->tags; ?>" checked="checked"  />
                            <label for="<?php echo $row->tags; ?>"><?php echo $row->tags; ?></label><br />
						<?php } else { ?>
                            <input type="checkbox" value="<?php echo $row->tags; ?>" name="mcomm-sectags[]" id="<?php echo $row->tags; ?>"  />
                            <label for="<?php echo $row->tags; ?>"><?php echo $row->tags; ?></label><br />
						<?php } } ?>
                </td>
            </tr>
        </table>
    </div>
	<?php
}

function savetags(){
	global $post, $wpdb;

	$option_name              = 'mcomm_custom_post_type_listing';
	$list_of_custom_post_type = get_option( $option_name );
	$custom_post_type_arr     = json_decode( $list_of_custom_post_type );

	if( $post ) {
		if ( in_array( $post->post_type, $custom_post_type_arr ) ) {

			if ( isset( $_POST['mcomm-sectags'] ) ) {
				update_post_meta( $post->ID, 'tag-secrows', implode( ',', $_POST['mcomm-sectags'] ) );
			} else {
				update_post_meta( $post->ID, 'tag-secrows', '' );
			}
			$tag_rows = $_POST['mcomm-tags'];
			update_post_meta( $post->ID, 'tag-rows', $tag_rows );
		}
	}
}

add_action('save_post', 'savetags');


/**
 * Function for creating database for Categories
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function my_plugin_create_db() {

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'categories';

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		category varchar(255) DEFAULT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook(__FILE__,'my_plugin_create_db');

