<?php
/**
 * Function for display category list
 * @author jaydeep
 * @package OE
 * @subpackage Phase 1
 */
	function category_list () {
		$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
		
		global $wpdb, $updatemessage;
        $id = isset( $_GET["id"] ) ? $_GET["id"] : "";
		$category = isset( $_POST["category"] ) ? $_POST["category"] : "";
		//update
		if(isset($_POST['updatecat'])){

			$datum = $wpdb->get_results("SELECT * FROM wp_categories WHERE category = '$category'");
			if($wpdb->num_rows > 0) {
				//wp_safe_redirect('https://codex.wordpress.org');
				//Display duplicate entry error message and exit
				?>
				<div class="wrap">
					<div class="error"><p>Category already exists</p></div> <!-- wp class error for error notices --->
				</div>
				<?php
			} else {

				//insert
				global $wpdb, $message;
				$wpdb->update('wp_categories',
					array('category' => $category),
					array('id' => $id),
					array('%s'),
					array('%d'));
				$message.="Category added Successfully";
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
<h2>Categories
	<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=category_create'); ?>">Add New Category</a>
</h2>
<?php
global $wpdb;
$category = isset( $_POST["category"] ) ? $_POST["category"] : "";
$id = isset( $_GET["id"] ) ? $_GET["id"] : "";

if(isset($_POST['insert'])) {
	$datum = $wpdb->get_results("SELECT * FROM wp_categories WHERE category = '$category'");
    if($wpdb->num_rows > 0) {
    	//wp_safe_redirect('https://codex.wordpress.org');
        //Display duplicate entry error message and exit
        ?>
        <div class="wrap">
            <div class="error"><p>Category already exists</p></div> <!-- wp class error for error notices --->
        </div>
        <?php
    } else {
       
//insert
		global $wpdb, $message;
		$wpdb->insert(
			'wp_categories', //table
			array('category' => $category), //data
			array('%s') //data format			
		);
		$message.="Category added Successfully";
	}
}
	
	if (isset($message)): ?><div class="updated"><p><?php echo isset( $message ) ? $message : "";?></p></div><?php endif;
	
	$results = $wpdb->get_results("SELECT id,category from wp_categories LIMIT $offset, $limit");
?>
		<div class="successmsg" style="display:none;"></div>
		<p></p>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<?php
					echo "<table class='tagtable wp-list-table widefat fixed striped' width='100%'>";
					echo "<thead><tr><th width='85%'><a href='#'>Category</a></th><th colspan='2' align='center'><a href='#'>Actions</a></th></tr></thead>";
					//check category result is empty or not
                    if( !empty( $results ) ) {
                        foreach ($results as $result ){
                            echo "<tr id='trc_".$result->id."'>";
                            // echo "<td>$result->id</td>";
                            echo "<td>$result->category</td>";
                            echo "<td colspan='2'><a href='".admin_url('admin.php?page=category_update&id='.$result->id)."'>Edit</a> | <a id='deletec_".$result->id."' class='delete_cat'  href='".admin_url('admin.php?page=category_list&id='.$result->id)."'>Delete</a></td>";
                            //echo "<td><a href='".admin_url('admin.php?page=category_list&id='.$result->id)."'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No category founds</td></tr>";
                    }

						echo "<tfoot><tr><th width='80%'><a href='#'>Category</a></th><th colspan='2' align='center'><a href='#'>Actions</a></th></tr></tfoot>";
					echo "</table>"; 
				?>
			</form> 
	<?php
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM wp_categories" );
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
	</div><!--End of wrap-->
	
<script>
var $ = jQuery;
	$('.delete_cat').click(function () {
           var element = $(this);
           var del_id = element.attr("id");
           var getId = del_id.replace("deletec_", "");
           var remove_row = $(".tagtable #trc_" +getId); 
          if(confirm("Are you sure you want to delete this Category?")){
          	$.ajax({
					url: "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
					type:'GET',
					data : {action: "delete_cat_popup", getId : getId},
                success: function(data,textStatus, XMLHttpRequest)
					{
						if("OK"){
	                          remove_row.remove();
	                          $(".updated").css("display", "none");
	                          $(".successmsg").css("display", "block");
	                       	  $(".successmsg").html("Category Deleted Successfully");
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

<?php } //End of category_list ()

/**
 * Function for Add new category 
 * @author Snehi
 * @package OE
 * @subpackage Phase 1
 */
function category_create() {
$category = isset( $_POST["category"] ) ? $_POST["category"] : "";
$id = isset( $_GET["id"] ) ? $_GET["id"] : "";
//insert
	if(isset($_POST['insert'])){
		global $wpdb;
		$wpdb->insert(
			'wp_categories', //table
			array('category' => $category), //data
			array('%s') //data format			
		);
		$message.="Category inserted";
	
	}	
   // exit();  
?>

<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/custom-tags/style-admin.css" rel="stylesheet" />
	<div class="wrap">
		<h2>Categories</h2>
			<div class="form-wrap">
				<h3>Add New Category</h3>
				<?php if (isset($message)): ?><div class="updated"><p><?php echo $message;?></p></div><?php endif;?>
				<form method="post" action="<?php echo admin_url('admin.php?page=category_list'); ?>">
					<div class="term-name-wrap">
						<label>Category Name</label>
						<input type="text" name="category" value="<?php echo $category; ?>" size="40" id="cat-name"/>
						<p>The name is how it appears on your site.</p>
					</div>
						<p><input type='submit' name="insert" value='Add new Category' class='button button-primary' onclick='return emptycat()'></p>
				</form>
			</div><!--End of form-wrap-->
	</div><!--End of wrap-->
	
	
<script type="text/javascript">
function emptycat() {
   var cat_empty;
   cat_empty = document.getElementById("cat-name").value;
   if (cat_empty == "") {
       alert("Please enter Category Name");
       return false;
   };
}	    
</script>

<?php }  // End of category_create()