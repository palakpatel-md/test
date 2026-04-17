<?php
/**
 * Function for display Tag list
 * @author Chetan
 * @package Mcomm
 * @subpackage Phase 1
 */
	function tag_list () {

		$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
		
		global $wpdb, $updatemessage;
        $id = isset( $_GET["id"] ) ? $_GET["id"] : "";
		$tag = isset( $_POST["tag"] ) ? $_POST["tag"] : "";
		$category = isset( $_POST["cat1"] ) ? $_POST["cat1"] : "";
		if(isset ( $_POST[ 'update' ] ) ) {

			$datum1 = $wpdb->get_results("SELECT * FROM tags WHERE tags = '$tag' AND category = '$category'");
			if($wpdb->num_rows > 0) {
				//Display duplicate entry error message and exit
				?>
				<div class="wrap">
					<div class="error"><p>Tag with assigned category already exists</p></div> <!-- wp class error for error notices --->
				</div>
				<?php
			} else {

				$wpdb->update('tags',
					array('tags' => $tag, 'category' => $category),
					array('id' => $id),
					array('%s', '%s'),
					array('%d'));
				$updatemessage.="Tag Updated Successfully";

			}



		}
		if ( isset( $updatemessage ) ): ?><div class="updated"><p><?php echo isset( $updatemessage ) ? $updatemessage : ""; ?></p></div><?php endif;
?> 
<?php
/**
 * Logic for Pagination for Tag
 * @author Snehi
 * @package OE
 * @subpackage Phase 1
 */
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 10;
	$offset = ( $pagenum - 1 ) * $limit;
?>
<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/custom-tags/style-admin.css" rel="stylesheet" />
	<div class="wrap">
		<h2>Tags
			<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=tag_create'); ?>">Add New</a>
		</h2>
<?php
global $wpdb;
$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
$tag = isset( $_POST["tag"] ) ? $_POST["tag"] : "";
$category = isset( $_POST["cat1"] ) ? $_POST["cat1"] : "";

if(isset($_POST['inserttag'])){
$datum1 = $wpdb->get_results("SELECT * FROM tags WHERE tags = '$tag' AND category = '$category'");
    if($wpdb->num_rows > 0) {
        //Display duplicate entry error message and exit
        ?>
        <div class="wrap">
            <div class="error"><p>Tag with assigned category already exists</p></div> <!-- wp class error for error notices --->
        </div>
        <?php
    } else {
//insert
$cats = $wpdb->get_results("SELECT id from wp_categories where category = '$category'");
foreach ($cats as $cat) {
	$cat_id = $cat->id;
		global $wpdb, $message;
		$wpdb->insert(
			'tags', //table
			array('id' => '','tags' => $tag,'category' => $category, 'catid' => $cat_id ), //data
			array('%d','%s','%s', '%d') //data format			
		);
		$message.="Tag added Successfully";
	}
}
}
if (isset($message)): ?><div class="updated"><p><?php echo isset( $message ) ? $message : "";?></p></div><?php endif;
		
// select query
$rows = $wpdb->get_results("SELECT id,tags,category from tags LIMIT $offset, $limit");
?>
	<div class="successmsg" style="display:none;"></div>
	<p></p>
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<?php
			echo "<table class='tagtable wp-list-table widefat fixed striped' width='100%'>";
			echo "<thead><tr><th width='43%'><a href='#'>Tags</a></th><th width='43%'><a href='#'>Category</a></th><th colspan='2' align='center'><a href='#'>Actions</a></th></tr></thead>";
			//check tag is available or not
            if( !empty( $rows ) ) {
                foreach ($rows as $row ){
                    $a = $row->id;
                    echo "<tr id='tr_".$row->id."'>";
                    echo "<td>$row->tags</td>";
                    echo "<td>$row->category</td>";
                    echo "<td colspan='2'><a href='".admin_url('admin.php?page=tag_update&id='.$row->id)."'>Edit</a> | <a id='delete_".$row->id."' class='delete_tag'  href='".admin_url('admin.php?page=tag_list&id='.$row->id)."'>Delete</a></td>";
                    // echo "<td><a href='".admin_url('admin.php?page=tag_list&id='.$row->id)."'>Delete</a></td>";
                    // echo "<td><input type='submit' name='delete' value='Delete' class='button button-primary' onclick='return confirm('Want to delete Tag?')'></td>";
                    echo "</tr>";}
            } else {
                echo "<tr><td colspan='4'>No tag founds</td></tr>";
            }

			echo "<tfoot><tr><th width='45%'><a href='#'>Tags</a></th><th width='45%'><a href='#'>Category</a></th><th colspan='2' align='center'><a href='#'>Actions</a></th></tr></tfoot>";
			echo "</table>"; 
			echo "<div class='test'></div>";
			?>
		</form> 
<?php
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM tags" );
$num_of_pages = ceil( $total / $limit );
$page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'aag' ),
    'next_text' => __( '&raquo;', 'aag' ),
    'total' => $num_of_pages,
    'current' => $pagenum
) );
 
if ( $page_links ) {
    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>
</div> <!--End of wrap-->

<script>
var $ = jQuery;
$(document).on('click', '.delete_tag', function(){
	 var element = $(this);
           var del_id = element.attr("id");
           var getId = del_id.replace("delete_", "");
           var remove_row = $(".tagtable #tr_" +getId); 
          if(confirm("Are you sure you want to delete this Tag?")){
          	$.ajax({
					url: "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
					type:'GET',
					data : {action: "delete_tag_popup", getId : getId},
					success: function(data,textStatus, XMLHttpRequest)
					{
						if("OK"){
	                          remove_row.remove();
	                          $(".updated").css("display", "none");
	                          $(".successmsg").css("display", "block");
	                       	  $(".successmsg").html("Tag Deleted Successfully");
	                   }
	                   else{
	                       $(".successmsg").html("Please Try Again");
	                   }
					}
					});
           }
       return false;
	
});

	      
</script>

<?php } //End of tag_list ()

/**
 * Function for Add Tags
 * @author Snehi
 * @package OE
 * @subpackage Phase 1
 */
function tag_create () {
global  $post, $wpdb, $tag, $category;
$tag = isset( $_POST["tag"] ) ? $_POST["tag"] : "";
$category = isset( $_POST["cat1"] ) ? $_POST["cat1"] : "";

	if(isset($_POST['inserttag'])){	
	  if (empty($_POST['tag'])) {
	    $nameErr = "Name is required";
	  }
	}
  
	//insert
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
						<form id="insert-val" method="post" action="<?php echo admin_url('admin.php?page=tag_list');  ?>">
							<div class="term-name-wrap">
								<label for="tag-name">Tag</label>
								<input type="text" name="tag" value="<?php echo isset( $tags ) ? $tags : ""; ?>" size="40" id="tag-name"/>
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
								<input type='submit' name="inserttag" value='Add new Tag' class='button button-primary insert-tag' onclick="return emptytag()" />
							</p>

					 </form>
			</div><!--End form-wrap-->
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
<?php } //End of tag_create ()
