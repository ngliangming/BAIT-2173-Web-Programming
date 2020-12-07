<?php
    //page title
    $page_title = "Aeki - Cart";
    //page background
    $bg = "none";
    
    if (!(isset($_COOKIE['user_id'])))
        $loginStat = 0;
    else
        $loginStat = 1;
    
    include 'includes/header.html';
    require 'includes/cart_functions.inc.php'    
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
    
    .btn-outline-secondary{
        background-color: lightskyblue;
    }
    
    .btn-outline-success{
        background-color: greenyellow;
    }
    
    .qtyBox{
        width: 5rem;
    }
</style>
<?php
    function displayItem($pid, $itemNo, $name, $price, $imgSrc ,$qty){
        echo "
            <tr class='titem'>
                <th scope='row'>$itemNo</th>
                <td style='width:12rem;'>
                    <img class=img-fluid border border-dark src='$imgSrc'>
                    <br>$name</td>
                <td>RM ".number_format($price, 2)."</td>
                 <td><input type=\"number\" size=\"3\" class=\"qtyBox\" name=\"qty[$pid]\"value=\"{$_SESSION['cart'][$pid]['qty']}\" /></td>
                <td>RM ".number_format($price*$qty, 2)."</td>
            </tr>
        ";
    }
    
//        <td><input type="number" class="qtyBox" name="qty" value="1" class="qtyBox" value="'.$qty.'"/></td>
    function displayLine($text, $amt){
        echo '
            <tr class="titem">
                <th scope="row"></th>
                <td style="width:12rem;">'.$text.'</td>
                <td></td>
                <td></td>
                <td>RM '.number_format($amt, 2).'</td>
            </tr>
        ';
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    // Change any quantities:
    foreach ($_POST['qty'] as $k => $v) {  

    // Must be integers!
    $pid = (int) $k;
    $qty = (int) $v;

    if ( $qty == 0 ) { // Delete.
        removeItem($pid);
     } elseif ( $qty > 0 ) { // Change quantity.
        changeItemQty($pid, $qty);
    }

    } // End of FOREACH.

    } // End of SUBMITTED IF.

    
?>
<div class="main_container mr-auto ml-auto mx-auto p-0 d-flex flex-column">

    <!--//Main starts here, enter your shit here -->
    <div class="jumbotron text-center">
        <h1>Shopping Cart</h1>      
    </div>

    <main>              
        <form class="text-center" method="post">
            <div class="table-responsive">
                <table class="table mx-auto text-center mt-auto bt-auto" style="font-weight: 700;">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Items</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
//                                displayItem($itemNo, $name, $price, $imgSrc ,$qty)
//                                addItem(1, 1);
//                                addItem(2, 1);
//                                clearCart();
//                                changeItemQty(4, 6);
                                if(!empty($_SESSION['cart'])){
                                    $i = 1;
                                    $total = 0;
                                    
                                    foreach ($_SESSION['cart'] as $pid => $item){
                                        $pid;
                                        
                                        $name = $item['name'];
                                        $price = $item['price'];
                                        $qty = $item['qty'];
                                        $imgSrc = $item['imgSrc'];
                                        $descr = $item['descr'];
                                        
                                        $total+= $price * $qty;
                                        
                                        displayItem($pid, $i, $name, $price, $imgSrc, $qty);
                                        $i++;
                                      //displayItem($itemNo, $name, $price, $imgSrc ,$qty)
                                    }
                                    displayLine('Subtotal', $total);
                                    displayLine('Shipping', 7);
                                    displayLine('Total', $total + 7);
                                }else
                                    displayLine('No Item In Cart',0);
                                
                            ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-outline-success custBtn" <?php if(empty($_SESSION['cart'])) echo 'disabled';?>><b>Update Cart</b></button>
            <button type="button" class='btn btn-outline-secondary custBtn' <?php if($loginStat == 1 ) echo "onclick=\"window.location.href='check-out.php'\""; else echo "data-toggle='modal' data-target='#modalLoginForm'"; if(empty($_SESSION['cart'])) echo 'disabled';?>><b>Continue To Checkout</b></button>
            
            
        </form>
    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>