<?php
/**
 * Function for Author List
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
function author_list() {

	global $wpdb, $updatemessage;
	$updatemessage = '';
	$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
	$author_name = isset( $_POST["author-name"] ) ? $_POST["author-name"] : "";
	$author_description = isset( $_POST["author-description"] ) ? stripslashes($_POST["author-description"]) : "";
	//Logic for Update Author

	if( isset( $_POST['updateauthor'] ) ) {
		$wpdb->update('wp_authors',
			array('author_name' => $author_name, 'author_description' => $author_description),
			array('id' => $id),
			array('%s', '%s'),
			array('%s', '%s'),
			array('%d')
		);

		$updatemessage.="Author Updated Successfully";
	}


	//Logic for Pagination for Author

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 10;
	$offset = ( $pagenum - 1 ) * $limit;
	?>

	<div class="wrap">

		<h2>Custom Author
			<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=author_create'); ?>">Add New Author</a>
		</h2>

		<?php
		if( isset( $_POST['insertauthor'] ) ) {

			$message = '';

			$datum = $wpdb->get_results( "SELECT * FROM `wp_authors` WHERE author_name = '$author_name'" );

			if($wpdb->num_rows > 0) { ?>

				<div class="wrap">
					<div class="error"><p>Author already exists</p></div> <!-- wp class error for error notices --->
				</div>

				<?php
			} else {
				//Logic for Insert Author
				$author_insert_result = $wpdb->insert(
					'wp_authors', //table name
					array('id' => '','author_name' => $author_name,'author_description' => $author_description ), //data
					array('%d','%s','%s') //data format
				);

				//check author inserted or not
				if( $author_insert_result ) {
					$message.="Author added Successfully";
				} else {
					$message.="Author Not Inserted";
				}

			}
		}

		if( isset( $message ) ): ?><div class="updated"><p><?php echo $message;?></p></div><?php endif;
		$results = $wpdb->get_results( "SELECT id,author_name from `wp_authors` " );
		?>
		<div class="successmsg" style="display:none;"></div>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<?php
			echo "<table class='tagtable wp-list-table widefat fixed striped' width='100%'>";
			echo "<thead><tr><th width='85%'><a href='#'>Custom Author</a></th><th colspan='2' align='center'><a href='#'>Actions</a></th></tr></thead>";
			if ( !empty($results) ) {
				foreach ( $results as $result ) {
					echo "<tr id='trc_".$result->id."'>";
					echo "<td>$result->author_name</td>";
					echo "<td colspan='2'><a href='".admin_url('admin.php?page=author_update&id='.$result->id)."'>Edit</a> | <a id='deletec_".$result->id."' class='delete_author'  href='".admin_url('admin.php?page=author_list&id='.$result->id)."'>Delete</a></td>";
					echo "</tr>";
				}
			} else {
				echo "<tr>";
				echo "<td colspan='3'>No Author Added.</td>";
				echo "</tr>";
			}
			echo "<tfoot><tr><th width='80%'><a href='#'>Custom Author</a></th><th colspan='2' align='center'><a href='#'>Actions</a></th></tr></tfoot>";
			echo "</table>";
			?>
		</form>

		<?php
		$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM `wp_authors` " );
		$num_of_pages = ceil( $total / $limit );
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'pagenum', '%#%' ),
			'format' => '',
			'prev_text' => __( '&laquo;', 'aag' ),
			'next_text' => __( '&raquo;', 'aag' ),
			'total' => $num_of_pages,
			'current' => $pagenum
		) );

		if( $page_links ) {
			echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
		?>
	</div><!--End of wrap-->

	<script>
		var $ = jQuery;
		$('.delete_author').click(function () {
			var element = $(this);
			var del_id = element.attr("id");
			var getId = del_id.replace("deletec_", "");
			var remove_row = $(".tagtable #trc_" +getId);

			if( confirm("Are you sure you want to delete this author?" ) ){
				$.ajax({
					url: "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
					type:'GET',
					data : {action: "delete_author_popup", getId : getId},
					success: function(data,textStatus, XMLHttpRequest) {
						if("OK"){
							remove_row.remove();
							$(".updated").css("display", "none");
							$(".successmsg").css("display", "block");
							$(".successmsg").html("Author Deleted Successfully");
						} else {
							$(".successmsg").html("Please Try Again");
						}
					}
				});
			}
			return false;
		});
	</script>

<?php } //End of author_list ()
