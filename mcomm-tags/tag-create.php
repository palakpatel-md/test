<?php
/**
 * Function for adding tags
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function tag_create () {
global  $post, $wpdb;
$tag = $_POST["tag"];
$category = $_POST["cat1"];
//insert
// $catresults = $wpdb->get_results("SELECT (wp_categories.id) FROM wp_categories INNER JOIN tags ON ( tags.category = wp_categories.category )  WHERE tags.category =  '$category'");
$cats = $wpdb->get_results("SELECT id from wp_categories where category = '$category'");
foreach ($cats as $cat) {
	$cat_id = $cat->id;
	if(isset($_POST['inserttag'])){
		global $wpdb;
		$wpdb->insert(
			'tags', //table
			array('id' => '','tags' => $tag,'category' => $category, 'catid' => $cat_id ), //data
			array('%d','%s','%s', '%d') //data format			
		);
		$message.="Tag inserted";
	}
}
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/custom-tags/style-admin.css" rel="stylesheet" />
<div class="wrap">
<h2>Tags</h2>
	<div class="form-wrap">
		<h3>Add New Tag</h3>
		<?php if (isset($message)): ?><div class="updated"><p><?php echo $message;?></p></div><?php endif;?>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<div class="term-name-wrap">
			<label for="tag-name">Tag</label>
			<input type="text" name="tag" value="<?php echo $tags; ?>" size="40" id="tag-name"/>
			<p>The name is how it appears on your site.</p>
		</div>
		<div class="form-field term-parent-wrap">
			<label>Category</label>
			<select name="cat1" id="parent" class="postform">
						<?php
						$rows = $wpdb->get_results("SELECT category from wp_categories");
						foreach ($rows as $row) {  ?>
									<option value="<?php echo $row->category; ?>"><?php echo $row->category; ?></option>
						<?php 	}  ?>
			</select> 
			<p>Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children tags for Bebop and Big Band.</p>
		</div>
		<p class="submit">
		<input type='submit' name="inserttag" value='Add new Tag' class='button button-primary'>
		</p>
		
		</form>
	</div>
</div>
<?php 
}  

