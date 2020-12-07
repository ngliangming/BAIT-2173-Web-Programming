<?php
//page title
$page_title = "Admin View Order";
//page background
$bg = "none";
//current page tag (only matter for header highlight)
$current = "orders";

session_start();
if (!isset($_COOKIE['user_id'])) {
    require ('includes/login_functions.inc.php');
    redirect_user();
}

if(isset($_COOKIE['user_level'])){
    if($_COOKIE['user_level']<2){
        require ('includes/login_functions.inc.php');
        redirect_user();
    }
}

include 'includes/header.html';

require 'mysql_connect.php';

?>

<?php
    $cat = '';
    $search = '';
    if(isset($_GET['cat']))
        $cat = $_GET['cat'];
    
    if(!isset($_GET['search']) || $_GET['search']== ''){
        $search = '';
        $searchDis = 'All Categories';
    }else{
    $search = (filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS));
    
    $searchDis = str_replace('_',' ',$search);
    $search = str_replace(' ','_',trim($search));
    }
    
    function displayNavBtn($category, $categoryTag){
        echo '<div class="navItem"><a href="?cat='.$categoryTag.'&search='.$GLOBALS['search'].'" class="navBtn';
        if($categoryTag==$GLOBALS['cat'])
            echo ' navActive';
        echo '">'.$category.'</a></div>';
    }
    
?>


<?php
    if(isset($_POST['addBtn'])){
        
        $catName= $_POST['addCat'];
        
        $q = 'INSERT INTO product_category (category_name) VALUES (?)';
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_bind_param($stmt,'s', $catName);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) == 1) {
             echo '<p>The category has been added.</p>';
        $_POST = array( );
        } else { // Error!
             $error = 'The new category could not be added to the database!';
        }
        
        if (isset($error)) {
            echo '<h1>Error!</h1>
            <p style="font-weight: bold; color: #C00">' . $error . ' Please try again.</p>';
        }
        
        // Check the results...
        if (mysqli_stmt_affected_rows($stmt) == 1) {

            // Rename the image:
            $id = mysqli_stmt_insert_id($stmt); // Get the product ID.

            // Clear $_POST:
            $_POST = array( );
        }
        
        $msg = "new category added successfully";
        
    }
?>

<?php
    if(isset($_POST['editcategoryBtn'])){

        $cid = $_POST['edit_cid'];
        $editCat = $_POST['editCat'];

        $query="UPDATE product_category SET category_name='$editCat' WHERE category_id='$cid'";

        $query_run = @mysqli_query($dbc,$query);
        
        echo '<div class="alert alert-success text-left" role="alert" style="position: fixed; left: 13%; top: 2%; width: 86%;">';
        
        echo 'Category has been updated';

        echo'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
        
        $msg = "category edited successfully";
    }
?>

<?php
    if(isset($_POST['deletecategoryBtn'])){

        $did = $_POST['delete_cid'];

        $query="UPDATE product p INNER JOIN product_category pc ON p.category = pc.category_id SET p.category = 0 WHERE category_id='$did'";

        $query_run = @mysqli_query($dbc,$query);
        
        $query="DELETE FROM product_category WHERE category_id='$did'";

        $query_run = @mysqli_query($dbc,$query);
        
        echo '<div class="alert alert-success text-left" role="alert" style="position: fixed; left: 13%; top: 2%; width: 86%;">';
        
        echo 'Category has been deleted';

        echo'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';

    }
?>

<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("table");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; 
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>

<div class="main_container d-flex flex-column">
  
    <main class="mt-0 mb-auto">
        
        <div class="container cust-nav text-center mx-0 mb-0" style="min-height: 100%;">
            <div class="navList">

                <form action="#">
                    <input type="text" name="cat" value="<?php if(isset($_GET['cat'])) echo $_GET['cat']; ?>" style="display:none;">
                    <div class="navSearch"><input type="text" class="navSearchTxt" name="search" value="<?php if(isset($_GET['search']) && $_GET['search']!='') echo $searchDis; else echo '';?>">
                    <button type="submit" class="btn btn-light navSearchBtn" style="background-color: #f9f9f9; color: #333;"><b>Search</b></button></div>
                </form>
                
                <div class="navItem">
                    <a type="button" class="navBtn navAddBtn" data-toggle="modal" data-target="#addCategory">
                        Add category
                    </a>
                </div>
                
            </div>
        </div>
        
        <div class="navPage">
            <?php
                if (isset($_GET['search']))
                    if(ctype_space($_GET['search']))
                        $search = $defaultCat;
            ?>
            <div class="jumbotron text-center" >
                <h1><?php echo strtoupper($searchDis) ?></h1>      
            </div>

        <!--Add category form-->
        
        <!-- Modal -->
        <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                
            <form action="adminviewcategory.php" method="POST">
              <div class="modal-body">
                    <p><b>Category name:</b> 
                    <input type="text" name="addCat" size="20" maxlength="20"/>

              </div>
                
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="submit" name="addBtn" value="Add" class="btn btn-primary"/> 
              </div>
                    
            </form>
                
            </div>
          </div>
        </div>

        <div class="table-responsive">
            <table class="table mx-auto text-center mt-auto bt-auto w-100" style="font-weight: 700;" id="table">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)" scope="col" style='vertical-align: middle;'>Category ID</th>
                        <th onclick="sortTable(1)" scope="col" style='vertical-align: middle;'>Category Name</th>
                        <th onclick="sortTable(2)" scope="col" style='vertical-align: middle;'>Functions</th>
                </tr>
                </thead>
                <tbody>
                    
                   <?php
                        // Default query for this page:
                        $q = "SELECT * FROM product_category ORDER BY category_id DESC";
                        
                        if($search!=''){
                            $q="SELECT * FROM product_category WHERE category_name LIKE '%$search%' OR category_id like '%$search%' ORDER BY category_id DESC";
                        }
                        
                        // Display all the prints, linked to URLs:
                        $r = mysqli_query($dbc,$q);
                        
                        while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) 
                        { 
                    ?>
                    
                    
                <!-- Modal -->
                <div class="modal fade" id="editcategory<?php echo $row['category_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>

                    <form action="" method="POST">
                        <div class="modal-body">
                              <div><b>Category Id: <?php echo $row['category_id'] ?></b></div>
                              <div><b>Category Name: </b><input type="text" name="editCat" value="<?php echo $row['category_name'] ?>"></text></div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="edit_cid" value="<?php echo $row['category_id'] ?>">
                            <button type="submit" name="editcategoryBtn" class="btn btn-primary" id="editDone">Update</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>   
                    </form>

                    </div>
                  </div>
                </div>
                
                
                <!--delete form-->
                <div class="modal fade" id="deletecategory<?php echo $row['category_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>

                    <form action="" method="POST">
                        <div class="modal-body">
                              <div><b>Category Id: <?php echo $row['category_id'] ?></b></div>
                              <div><b>Category Name: </b><?php echo $row['category_name'] ?></text></div>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="delete_cid" value="<?php echo $row['category_id'] ?>">
                            <button type="submit" name="deletecategoryBtn" class="btn btn-primary" id="editDone">Delete</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>   
                    </form>

                    </div>
                  </div>
                </div>
                    
                        <tr class="titem">
                                <td style='vertical-align: middle;'><?php echo $row['category_id'] ?></td>
                                <td style='vertical-align: middle;'><?php echo $row['category_name'] ?></td>

                                <td class="colBtn" style='vertical-align: middle;'>
                                    
                                    <button type="button" data-toggle="modal" data-target="#editcategory<?php echo $row['category_id']?>" style="margin: 0 .5rem;">
                                        Edit
                                    </button>
                                    
                                    
                                    <button type="button" data-toggle="modal" data-target="#deletecategory<?php echo $row['category_id']?>" style="margin: 0 .5rem;">
                                        Delete
                                    </button>
                                    
                                </td>
                            </tr>
                        <?php        
                        }
                        ?>

                    </tbody>

                    <?php
                        mysqli_close($dbc);
                    ?>
                    
               </tbody>
            </table>
        </div>
        </div>
        <?php
        if(isset($msg)){
            
            echo '<div class="alert alert-success text-left" role="alert" style="position: fixed; left: 12%; top: 2%; width: 86%;">';
                echo ucfirst($msg);

            echo'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
        }
        ?>
    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>

    
