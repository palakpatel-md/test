<?php
/**
 * Function for Add new Author
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function author_create() {

	global $author_name, $author_description,$wpdb;

	$author_name = isset( $_POST["author-name"] ) ? $_POST["author-name"] : "";
	$author_description = isset( $_POST["author-description"] ) ? stripslashes($_POST["author-description"]): "";


	//Logic for Insert Author
	if( isset( $_POST['insertauthor'] ) ) {
		global $wpdb;
		$wpdb->insert(
			'wp_authors', //table
			array('id' => '','author_name' => $author_name,'author_description' => $author_description ), //data
			array('%d','%s','%s') //data format
		);

		echo "<pre>";
		print_r( $wpdb );
		exit();

		$message.="Author inserted";
	}

	?>
	<div class="wrap">
		<h2>Custom Author</h2>
		<div class="form-wrap">
			<h3>Add New Author</h3>
			<?php if ( isset ( $message ) ): ?><div class="updated"><p><?php echo $message;?></p></div><?php endif;?>
			<form method="post" action="<?php echo admin_url('admin.php?page=author_list'); ?>">
				<div class="term-name-wrap">
					<label>Author Name</label>
					<input type="text" name="author-name" value="<?php echo $author_name; ?>" size="40" id="author-name"/>
					<label>Author Description</label>
					<?php
					$editor_id = 'author-description';
					wp_editor( $author_description, $editor_id );	 ?>
					<p>The name is how it appears on your site.</p>
				</div>
				<p><input type='submit' name="insertauthor" value='Add new Author' class='button button-primary' onclick='return emptyauthor()'></p>
			</form>
		</div><!--End of form-wrap-->
	</div><!--End of wrap-->

	<script type="text/javascript">
		function emptyauthor() {
			var author_empty;
			var author_description;
			author_empty = document.getElementById("author-name").value;
			author_description = document.getElementById("author-description").value;
			if( author_empty == "" ) {
				alert("Please enter author name");
				document.getElementById("author-name").focus();
				return false;
			} else if( author_description == "" ) {
				alert("Please enter author description");
				document.getElementById("author-description").focus();
				return false;
			};
		}
	</script>

<?php }  // End of author_create()

