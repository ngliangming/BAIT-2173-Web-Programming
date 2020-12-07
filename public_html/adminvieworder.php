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
    if($_COOKIE['user_level']<1){
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
        $searchDis = 'All Orders';
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
    if(isset($_POST['editorderBtn'])){

        $eid=$_POST['edit_oid'];
        $chgstatus = $_POST['chgstatus'];

        $query="UPDATE orders SET status='$chgstatus' WHERE order_id='$eid'";

        $query_run = @mysqli_query($dbc,$query);
        
        $msg = 'Order has been updated';
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

        <div class="table-responsive">
            <table class="table mx-auto text-center mt-auto bt-auto w-100" style="font-weight: 700;" id="table">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)" scope="col" style='vertical-align: middle;'>Order id</th>
                        <th onclick="sortTable(1)"scope="col" style='vertical-align: middle;'>Status</th>
                        <th onclick="sortTable(2)" scope="col" style='vertical-align: middle;'>Email</th>
                        <th onclick="sortTable(3)"scope="col" style='vertical-align: middle;'>Customer Name</th>
                        <th onclick="sortTable(4)"scope="col" style='vertical-align: middle;'>Category Name</th>
                        <th onclick="sortTable(5)"scope="col" style='vertical-align: middle;'>Product Name</th>
                        <th onclick="sortTable(6)"scope="col" style='vertical-align: middle;'>Unit Price (RM)</th>
                        <th onclick="sortTable(7)"scope="col" style='vertical-align: middle;'>Quantity</th>
                        <th onclick="sortTable(8)"scope="col" style='vertical-align: middle;'>Subtotal (RM)</th>
                        <th onclick="sortTable(9)"scope="col" style='vertical-align: middle;'>Product Availability</th>
                        <th onclick="sortTable(10)"scope="col" style='vertical-align: middle;' colspan="2">Functions</th>
                </tr>
                </thead>
                <tbody>

                   <?php
                        //Default
                        $q = "SELECT * FROM orders o INNER JOIN product p on o.pid=p.pid INNER JOIN users u on o.user_id = u.user_id INNER JOIN product_category pc on pc.category_id = p.category";
                        
                   
                        //If $search OR $cat exists
                        if($search != '' || $cat != '')
                            $q .= ' WHERE ';

                        //If search exists
                        if($search != ''){
                            //append query for text search
                            $q .= "(o.order_id LIKE '%$search%' OR o.status LIKE '%$search%' OR p.name LIKE '%$search%' OR  u.first_name LIKE '%$search%' OR  u.last_name LIKE '%$search%' OR  u.email LIKE '%$search%' OR pc.category_name LIKE '%$search%' OR p.descr LIKE '%$search%' OR p.available LIKE '%$search%')";

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
                        
                        //Set ORDER BY
                        $q .= " ORDER BY o.order_id DESC";

                        // Display all the prints, linked to URLs:
                        $r = mysqli_query($dbc,$q);

                        while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) 
                        {   
                    ?>
                        
        <div class="modal fade" id="editOrder<?php echo $row['order_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  

                  
            <form action="" method="POST">
                    <div><b>Order id: </b><?php echo $row['order_id'] ?></div>
                        <div><b>Status :</b>
                            <select name="chgstatus">
                              <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected';?>">
                                   Pending
                              </option>

                              <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo 'selected';?>>
                                   Delivered
                              </option>

                              <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected';?>>
                                   Cancelled
                              </option>
                            </select>
                        </div>
                  
                  <br>
                  
                  <input type="hidden" name="edit_oid" value="<?php echo $row['order_id'] ?>">
                  
                  <div class="modal-footer">
                    
                    <button type="submit" name="editorderBtn" class="btn btn-primary" id="editDone" >Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
   
            </div>
                
            </div>
          </div>
        </div>

                <tr class="titem">
                        <td style='vertical-align: middle;'><?php echo $row['order_id'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['status'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['email'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['first_name'].' '.$row['last_name'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['category_name'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['name'] ?></td>
                        <td style='vertical-align: middle;'><?php echo number_format($row['price'], 2) ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['qty'] ?></td>
                        <td style='vertical-align: middle;'><?php echo number_format($row['price']*$row['qty'], 2) ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['available'] ?></td>

                        <td class="colBtn" style='vertical-align: middle;'>  
                            <button type="button" data-toggle="modal" data-target="#editOrder<?php echo $row['order_id'] ?>">
                                Edit
                            </button>
                        </td>
                </tr>
                <?php        
                }
                        //Default
                        $q = "SELECT * FROM orders o INNER JOIN product p on o.pid=p.pid INNER JOIN users u on o.user_id = u.user_id WHERE p.category = 0";
                        
                        $q .= " ORDER BY o.order_id DESC";

                        // Display all the prints, linked to URLs:
                        $r = mysqli_query($dbc,$q);

                while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) 
                        {   
                    ?>
                        
        <div class="modal fade" id="editOrder<?php echo $row['order_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  

                  
            <form action="" method="POST">
                    <div><b>Order id: </b><?php echo $row['order_id'] ?></div>
                        <div><b>Status :</b>
                            <select name="chgstatus">
                              <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected';?>">
                                   Pending
                              </option>

                              <option value="Delivered" <?php if ($row['status'] == 'Delivered') echo 'selected';?>>
                                   Delivered
                              </option>

                              <option value="Cancelled" <?php if ($row['status'] == 'Cancelled') echo 'selected';?>>
                                   Cancelled
                              </option>
                            </select>
                        </div>
                  
                  <br>
                  
                  <input type="hidden" name="edit_oid" value="<?php echo $row['order_id'] ?>">
                  
                  <div class="modal-footer">
                    
                    <button type="submit" name="editorderBtn" class="btn btn-primary" id="editDone" >Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
   
            </div>
                
            </div>
          </div>
        </div>

                <tr class="titem">
                        <td style='vertical-align: middle;'><?php echo $row['order_id'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['status'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['email'] ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['first_name'].' '.$row['last_name'] ?></td>
                        <td style='vertical-align: middle;'><?php echo 'None' ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['name'] ?></td>
                        <td style='vertical-align: middle;'><?php echo number_format($row['price'], 2) ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['qty'] ?></td>
                        <td style='vertical-align: middle;'><?php echo number_format($row['price']*$row['qty'], 2) ?></td>
                        <td style='vertical-align: middle;'><?php echo $row['available'] ?></td>

                        <td class="colBtn" style='vertical-align: middle;'>  
                            <button type="button" data-toggle="modal" data-target="#editOrder<?php echo $row['order_id'] ?>">
                                Edit
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
    