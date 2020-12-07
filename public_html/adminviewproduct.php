<?php
//page title
$page_title = "Admin View Product";
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
        $searchDis = 'All Products';
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

<!--delete button function-->
<?php
if(isset($_POST['deleteproductBtn'])){
    $id=$_POST['delete_pid'];
    
    $query="DELETE FROM product WHERE pid=$id";

    $query_run = @mysqli_query($dbc,$query);
    
    $msg = "Product has been deleted";
}
?>


<?php
if(isset($_POST['addBtn'])){
   
// Validate the incoming data...
$errors = array( );

// Check for a product name:
if (!empty($_POST['name'])) {
    $name = trim($_POST['name']);
}else{
    $errors[ ] = 'Please enter the product name!';
}

// Validate the category...
if ( isset($_POST['addcat']) && filter_var($_POST['addcat'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
$cat = $_POST['addcat'];
} else { // No category_name selected.
$errors[ ] = 'Please select the product category !';
}

 // Check for a price:
if (is_numeric($_POST['price']) && ($_POST['price'] > 0)) {
    $price = (float) $_POST['price'];
} else {
    $errors[ ] = 'Please enter the product\'s price!';
}

// Check for an image:
$target_dir = "products/";
$target_file = $target_dir . basename($_FILES["imgSrc"]["name"]); 
move_uploaded_file($_FILES["imgSrc"]["tmp_name"], $target_file);

// Check for a description (not required):
$descr = $_POST['descr'];

// Check product's availability:
$available = $_POST['avail'];

if (empty($errors)) { // If everything's OK.
// 
        // Add the product to the database    
        $q = 'INSERT INTO product (category, name, price, imgSrc, descr, available) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_bind_param($stmt,'ssdssi', $cat, $name, $price, $target_file, $descr, $available);
        mysqli_stmt_execute($stmt);

    // Check the results...
    if (mysqli_stmt_affected_rows($stmt) == 1) {

        // Print a message:
        echo '<p>The product has been added.</p>';

        // Rename the image:
        $id = mysqli_stmt_insert_id($stmt); // Get the product ID.

        // Clear $_POST:
        $_POST = array( );

    } else { // Error!
        echo '<p style="font-weight: bold; color: #C00">Your submission could not be processed due to a system error.</p>';
    }

    mysqli_stmt_close($stmt);

} // End of $errors IF.

// Delete the uploaded file if it still exists:
if ( isset($temp) && file_exists ($temp) && is_file($temp) ) {
    unlink ($temp);
}

} // End of the submission IF.

// Check for any errors and product them:
if ( !empty($errors) && is_array($errors) ) {
    echo '<h1>Error!</h1>
    <p style="font-weight: bold; color: #C00">The following error(s) occurred:<br />';
    
foreach ($errors as $msg) {
    echo " - $msg<br />\n";
}
    echo 'Please reselect the product image and try again.</p>';
}

// Display the form...
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
                    <a type="button" class="navBtn navAddBtn" href="addProduct.php">
                        Add Product
                    </a>
                </div>
                
                <?php
                    displayNavBtn('All Products', '');
//                    displayNavBtn($_GET['cat'], 'test');
                    
                    $q = 'SELECT * FROM product_category ORDER BY category_id';
                    
                    $r = mysqli_query ($dbc, $q);
                    while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {

                    // Display each record:
                    displayNavBtn($row['category_name'], $row['category_name']);
                    }
                ?>
                
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
        <div class="main_container mr-auto ml-auto mx-auto p-0 d-flex flex-column">

        <div class="table-responsive">
            <table class="table mx-auto text-center mt-auto bt-auto w-100" style="font-weight: 700;" id="table">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)" scope="col" style='vertical-align: middle;'>Product id</th>
                        <th onclick="sortTable(1)" scope="col" style='vertical-align: middle;'>Product category</th>
                        <th onclick="sortTable(2)" scope="col" style='vertical-align: middle;'>Product Name</th>
                        <th onclick="sortTable(3)" scope="col" style='vertical-align: middle;'>Price</th>
                        <th onclick="sortTable(4)" scope="col" style='vertical-align: middle;'>Image</th>
                        <th onclick="sortTable(5)" scope="col" style='vertical-align: middle;'>Product Description</th>
                        <th onclick="sortTable(6)" scope="col" style='vertical-align: middle;'>Product's availability</th>
                        <th onclick="sortTable(7)" scope="col" style='vertical-align: middle;' colspan="2">Functions</th>
                </tr>
                </thead>
                <tbody>
                    
                    <?php
                        //Default
                    $q = 'SELECT * FROM product p INNER JOIN product_category pc ON pc.category_id = p.category';
                    
                    //If $search OR $cat exists
                    if($search != '' || $cat != '')
                        $q .= ' WHERE ';
                    
                    //If search exists
                    if($search != ''){
                        //append query for text search
                        $q .= "(pc.category_name LIKE '%$search%' OR name LIKE '%$search%' OR descr LIKE '%$search%')";
                        
                        //If $cat, add 'AND' to query at the end
                        if($cat != '')
                            //append query for 'AND' statement to combine both
                            $q .= ' AND ';
                    }
                    
                    //If $cat exists
                    if($cat != ''){
                        //append query for category search
                        $q .= "pc.category_name = '$cat'";
                    }
                    
                    $r = mysqli_query ($dbc, $q);

                        while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
                        
                        {    
                        $pid=$row['pid'];
                    ?>
                    
                        <tr class="titem">
                            
                            <td style='vertical-align: middle;'><?php echo $pid ?></td>
                            
                            <td style='vertical-align: middle;'>
                                <?php echo $row['category_name'];?>
                                <!--delete form-->
                                <div class="modal fade" id="deleteprod<?php echo $row['pid']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 style="color:black" class="modal-title" id="exampleModalLabel">Delete product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>

                                    <form action="" method="POST">
                                        <div class="modal-body">
                                              <div style="color:black">Product Id: <?php echo $row['pid'] ?></div>
                                              <div style="color:black">Product Name: <?php echo $row['name'] ?></text></div>
                                        </div>

                                        <div class="modal-footer">
                                            <input type="hidden" name="delete_pid" value="<?php echo $row['pid'] ?>">
                                            <button type="submit" name="deleteproductBtn" class="btn btn-primary" id="editDone">Delete</button>
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>   
                                    </form>

                                    </div>
                                  </div>
                                </div>
                            </td>
                            
                            <td style='vertical-align: middle;'><?php echo $row['name'] ?></td>
                            <td style='vertical-align: middle;'>RM <?php echo $row['price'] ?></td>
                                
                            <td style='vertical-align: middle;'>
                                <img src="<?php echo $row['imgSrc'] ?>" alt="image" style='width: 10rem;'>
                            </td>
                            
                            <td style='vertical-align: middle;'><?php echo $row['descr'] ?></td>
                            <td style='vertical-align: middle;'>
                                <?php 
                                      echo $row['available'];
                                ?>
                            </td>
                            
                            <td class="colBtn" style='vertical-align: middle;'>
                                <form action="editProduct.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="edit_id" value="<?php echo $row['pid'] ?>">
                                    <button type="submit" name="editBtn" >Edit</button>
                                </form>
                            </td>
                            
                            <td class="colBtn" style="vertical-align: middle;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['pid'] ?>">
                                    <button type="button" data-toggle="modal" data-target="#deleteprod<?php echo $row['pid']?>">
                                            Delete
                                    </button>
                            </td>
                        </tr>
                    <?php        
                    }
                    ?>
                        
                        <?php
                        //Default
                    $q = 'SELECT * FROM product p WHERE category = 0';
                    
                    $r = mysqli_query ($dbc, $q);

                        while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
                        
                        {    
                        $pid=$row['pid'];
                    ?>
                    
                        <tr class="titem">
                            
                            <td style='vertical-align: middle;'><?php echo $pid ?></td>
                            
                            <td style='vertical-align: middle;'>
                                <?php echo 'None';?>
                                <!--delete form-->
                                <div class="modal fade" id="deleteprod<?php echo $row['pid']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 style="color:black" class="modal-title" id="exampleModalLabel">Delete product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>

                                    <form action="" method="POST">
                                        <div class="modal-body">
                                              <div style="color:black">Product Id: <?php echo $row['pid'] ?></div>
                                              <div style="color:black">Product Name: <?php echo $row['name'] ?></text></div>
                                        </div>

                                        <div class="modal-footer">
                                            <input type="hidden" name="delete_pid" value="<?php echo $row['pid'] ?>">
                                            <button type="submit" name="deleteproductBtn" class="btn btn-primary" id="editDone">Delete</button>
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>   
                                    </form>

                                    </div>
                                  </div>
                                </div>
                            </td>
                            
                            <td style='vertical-align: middle;'><?php echo $row['name'] ?></td>
                            <td style='vertical-align: middle;'>RM <?php echo $row['price'] ?></td>
                                
                            <td style='vertical-align: middle;'>
                                <img src="<?php echo $row['imgSrc'] ?>" alt="image" style='width: 10rem;'>
                            </td>
                            
                            <td style='vertical-align: middle;'><?php echo $row['descr'] ?></td>
                            <td style='vertical-align: middle;'>
                                <?php 
                                      echo $row['available'];
                                ?>
                            </td>
                            
                            <td class="colBtn" style='vertical-align: middle;'>
                                <form action="editProduct.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="edit_id" value="<?php echo $row['pid'] ?>">
                                    <button type="submit" name="editBtn" >Edit</button>
                                </form>
                            </td>
                            
                            <td class="colBtn" style="vertical-align: middle;">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['pid'] ?>">
                                    <button type="button" data-toggle="modal" data-target="#deleteprod<?php echo $row['pid']?>">
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
            </table>    
        </div>
        </div>
        <?php
        
            
            if(isset($_GET['success']) && !isset($msg)){
                $msg = '';

                if($_GET['success']==1)
                    $msg = 'Update successful';
                else if($_GET['success']==2)
                    $msg = 'New product added successfully';
            }
            
            if(!isset($msg))
                $msg = '';
            
            if($msg != ''){
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
   
<?php include 'includes/footer.html' ?>
