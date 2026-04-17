<?php
/**
 * Function for Update Author
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function author_update() {
	global $wpdb;
	$id = $_GET["id"];
	$author_name = isset( $_POST["author-name"] ) ? $_POST["author-name"] : "";
	$author_description = isset( $_POST["author-description"] ) ? stripslashes($_POST["author-description"]) : "";
	$author_table_name = $wpdb->prefix . 'authors';

	//Logic for Update Author
	if( isset( $_POST[ 'updateauthor' ] ) ) {
		$wpdb->update( 'wp_authors',
			array( 'author_name' => $author_name ),
			array( 'author_description' => $author_description ),
			array( 'id' => $id ),
			array( '%s' ),
			array( '%s' ),
			array( '%d' )
		);

	} else {

		//Selecting author to Update
		$selected_author = $wpdb->get_results( $wpdb->prepare( "SELECT * from $author_table_name where id=%d",$id ) );
		foreach ( $selected_author as $author_list ){
			$author_id = $author_list->id;
			$author_name = $author_list->author_name;
			$author_description = $author_list->author_description;
		}
	}
	?>
	<div class="wrap">
		<h2>Custom Author</h2>
		<div class="form-wrap">
			<h3>Edit Author</h3>
			<form method="post" action="<?php echo admin_url("admin.php?page=author_list&id=$author_id"); ?>">
				<div class="term-name-wrap">
					<label>Author Name</label>
					<input type="text" name="author-name" value="<?php echo $author_name; ?>" size="40" id="cat-name"/>
					<label>Author Description</label>
					<?php
					$editor_id = 'author-description';
					wp_editor( $author_description, $editor_id );	 ?>
					<p>The name is how it appears on your site.</p>
				</div>
				<p><input type='submit' name="updateauthor" value='Update' class='button button-primary' ></p>
				<p><input type='submit' name="deleteauthor" value='Delete' class='button button-primary' onclick="return confirm('Want to delete Author?')" style="display:none;"></p>
			</form>
		</div><!--End of form-wrap-->
	</div><!--End of wrap-->
<?php } //End of author_update ()