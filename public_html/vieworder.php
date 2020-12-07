<?php
    //page title
    $page_title = "Aeki - Orders";
    //page background
    $bg = "none";
    
//    session_start();
    if (!(isset($_COOKIE['user_id']))) {
        require ('includes/login_functions.inc.php');
        redirect_user();
    }
    
    require 'includes/cart_functions.inc.php';
    include 'includes/header.html';
?>

<style>
    .custBtn{
        width: 20rem; 
        height: 5rem; 
        /*background-color: #333;*/ 
        font-size: 1.5rem; 
        /*color: #fff;*/
        margin-left: 5rem;
        margin-right: 5rem;
    }
    
    .btn-outline-danger{
        background-color: lightcoral;
    }
    
    .btn-outline-success{
        background-color: greenyellow;
    }
    
</style>
<?php
    function displayItem($itemNo, $name, $price, $imgSrc ,$qty, $status){
        echo '
            <tr class="titem">
                <th scope="row">'.$itemNo.'</th>
                <td style="width:12rem;">
                    <img class="img-fluid border border-dark" src="'.$imgSrc.'">
                    <br>'.$name.'</td>
                <td>RM '.number_format($price, 2).'</td>
                <td>'.$qty.'</td>
                <td>RM '.number_format($price*$qty, 2).'</td>
                <td>'.$status.'</td>
            </tr>
        ';
    }
    if (isset($_GET['clear'])){
        if($_GET['clear'] == 1)
            $q = 'UPDATE orders SET deleted = 1 WHERE status = "Delivered" AND user_id = '.$_COOKIE['user_id'];
        else if($_GET['clear'] == 2)
            $q = 'UPDATE orders SET deleted = 1 WHERE status = "Cancelled" AND user_id = '.$_COOKIE['user_id'];
            $r = mysqli_query ($dbc, $q);
    }
    
?>
<div class="main_container mr-auto ml-auto mx-auto p-0 d-flex flex-column">

    <!--//Main starts here, enter your shit here -->
    <div class="jumbotron text-center">
        <h1>Your Order Status</h1>      
    </div>

    <main>              
        <form class="text-center">
            <div class="table-responsive">
                <table class="table mx-auto text-center mt-auto bt-auto" style="font-weight: 700;">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Items</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            $uid = $_COOKIE['user_id'];
                            
                            $q = "SELECT * FROM orders o JOIN product p on p.pid=o.pid WHERE user_id = $uid AND deleted != 1";

                            $r = mysqli_query ($dbc, $q);

                            $i = 1;
                            while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {

                            // Display each record:

                            displayItem($i, $row['name'], $row['price'], $row['imgSrc'], $row['qty'], $row['status']);
                            $i++;
    //                        displayProduct($row['pid'], $row['name'], $row['price'], $row['imgSrc'],  $row['descr']);

                            }
                        ?>

                    </tbody>
                </table>
            </div>
            <button onclick="window.location.href='?clear=1'" type="button" class="btn btn-outline-success custBtn" data-toggle="tooltip" title="This deletes all delivered order entries."><b>Clear Delivered</b></button>
            <button onclick="window.location.href='?clear=2'" type="button" class="btn btn-outline-success custBtn" data-toggle="tooltip" title="This deletes all cancelled order entries."><b>Clear Cancelled</b></button>
        </form>
    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>