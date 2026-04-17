<?php
/**
 * Function for adding new categories 
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function category_create() {
$category = isset( $_POST["category"] ) ? $_POST["category"] : "";
$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
//insert
if(isset($_POST['insert'])){
	global $wpdb;
	if (empty($_POST['category'])) {
		$nameErr = "Category name is required";
	} else {
	$wpdb->insert(
		'wp_categories', //table
		array('category' => $category), //data
		array('%s') //data format			
	);
	$message.="Category inserted";
	}
}
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/custom-tags/style-admin.css" rel="stylesheet" />
<div class="wrap">
<h2>Categories</h2>
	<div class="form-wrap">
		<h3>Add New Category</h3>
		<?php if (isset($message)): ?><div class="updated"><p><?php echo $message;?></p></div><?php endif;?>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<div class="term-name-wrap">
				<label>Category Name</label>
				<input type="text" name="category" value="<?php echo isset( $category ) ? $category : ""; ?>" size="40" id="tag-name"/>
				<span class="error">* <?php echo isset( $nameErr ) ? $nameErr : "";?></span>
				<p>The name is how it appears on your site.</p>
			</div>
		
			<p><input type='submit' name="insert" value='Add new Category' class='button button-primary'></p>
		</form>
	
	</div>
</div>
<?php
}