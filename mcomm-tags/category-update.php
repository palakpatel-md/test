<?php
/**
 * Function for Update Category list
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
	function category_update () {
		global $wpdb;
		$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
		$category = isset( $_POST["category"] ) ? $_POST["category"] : "";
		//update
		if(isset($_POST['updatecat'])){
			$wpdb->update('wp_categories',
		          array('category' => $category),
		          array('id' => $id),
		          array('%s'),
		          array('%d'));
		}
		else{//selecting value to update	
			$cats = $wpdb->get_results($wpdb->prepare("SELECT id,category from wp_categories where id=%d",$id));
			foreach ($cats as $cat ){
				$catid = $cat->id;
				$category=$cat->category;
			}
		}
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/custom-tags/style-admin.css" rel="stylesheet" />
	<div class="wrap">
		<h2>Category</h2>
			<div class="form-wrap">
				<h3>Edit Category</h3>
					
						<form method="post" action="<?php echo admin_url("admin.php?page=category_list&id=$catid"); ?>">
							<div class="term-name-wrap">
								<label>Category Name</label>
								<input type="text" name="category" value="<?php echo isset( $category ) ? $category : ""; ?>" size="40" id="tag-name"/>
								<p>The name is how it appears on your site.</p>
							</div>
							<p><input type='submit' name="updatecat" value='Update' class='button button-primary' onclick="return emptycat()"></p>
							<p><input type='submit' name="deletecat" value='Delete' class='button button-primary' onclick="return confirm('Want to delete Category?')" style="display:none;"></p>
						</form>

			</div><!--End of form-wrap-->
	</div><!--End of wrap-->

		<script type="text/javascript">
			function emptycat() {
				var cat_empty;
				cat_empty = document.getElementById("tag-name").value;
				if (cat_empty == "") {
					alert("Please enter Category Name");
					return false;
				};
			}
		</script>

<?php } //End of category_update ()