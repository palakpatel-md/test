<?php
/**
 * Function for Update Tag list
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */

	function tag_update () {
		global $wpdb;
		$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
		$tag = isset( $_POST["tag"] ) ? $_POST["tag"] : "";
		$category = isset( $_POST["cat1"] ) ? $_POST["cat1"] : "";
		//update
		if(isset ( $_POST[ 'update' ] ) ) {	
			$wpdb->update('tags', 
		          array('tags' => $tag, 'category' => $category), 
		          array('id' => $id), 
		          array('%s', '%s'), 
		          array('%d')); 	
		}
		else{//selecting value to update	
			$schools = $wpdb->get_results($wpdb->prepare("SELECT id,tags,category from tags where id=%d",$id));
				foreach ($schools as $s ){
					$id1 = $s->id;
					$tag=$s->tags;
				$category=$s->category;
			}
		}	
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/custom-tags/style-admin.css" rel="stylesheet" />
	<div class="wrap">
		<h2>Tags</h2>
			<div class="form-wrap">
					<h3>Edit Tag</h3>
						<form method="post" action="<?php echo admin_url("admin.php?page=tag_list&id=$id1"); ?>">
							<div class="term-name-wrap">
								<label for="tag-name">Tag</label>
								<input type="text" name="tag" value="<?php echo isset( $tag ) ? $tag : ""; ?>" size="40" id="tag-name"/>
								<p>The name is how it appears on your site.</p>
							</div>
							<div class="form-field term-parent-wrap">
								<label>Category</label>
								<select name="cat1" id="parent" class="postform">
									<option value="<?php echo $category; ?>"><?php echo $category; ?></option>
									<?php
									$rows = $wpdb->get_results("SELECT category from wp_categories WHERE category != '$category'");
									foreach ($rows as $row) {  ?>
												<option value="<?php echo $row->category; ?>"><?php echo $row->category; ?></option>
									<?php 	}  ?>
								</select> 
								<p>Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children tags for Bebop and Big Band.</p>
							</div>
							<p class="submit">
								<input type='submit' name="update" value='Update' class='button button-primary'  onclick="return emptytag()">
							</p>
							<p class="submit" style="display:none;">
								<input type='submit' name="delete" value='Delete' class='button button-primary' onclick="return confirm('Want to delete Tag?')">
							</p>
						</form>

			</div><!--End of form-wrap-->
	</div><!--End of wrap-->

		<script type="text/javascript">
			function emptytag() {
				var x;
				x = document.getElementById("tag-name").value;
				if (x == "") {
					alert("Please enter Tag Name");
					return false;
				};
			}
		</script>

<?php } //End of tag_update ()