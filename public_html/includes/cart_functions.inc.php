<?php
    require_once 'mysql_connect.php';
    
    function addItem($pid, $qty){
        if(isset($_SESSION['cart'][$pid])){
            $_SESSION['cart'][$pid]['qty']+= $qty;
        }else{
            $q = "SELECT name, price, imgSrc, descr FROM product WHERE pid = $pid";
            $r = mysqli_query ($GLOBALS['dbc'], $q);
            
            if (mysqli_num_rows($r) == 1) {
                list($name, $price, $imgSrc, $descr) = mysqli_fetch_array ($r, MYSQLI_NUM);
            }
            
            $_SESSION['cart'][$pid] = array('name' => $name,'price' => $price,'qty' => $qty,'imgSrc' => $imgSrc,'descr' => $descr);
        }
        
//        name, imgSrc, descr;
    }
    
    function addItemManual($pid, $name, $price, $qty, $imgSrc, $descr){
        if(isset($_SESSION['cart'][$pid])){
            $_SESSION['cart'][$pid]['qty']+= $qty;
        }else{
            $_SESSION['cart'][$pid] = array('name' => $name,'price' => $price,'qty' => $qty,'imgSrc' => $imgSrc,'descr' => $descr);
        }
    }
    
    function clearCart(){
        unset($_SESSION['cart']);
    }
    
    function removeItem($pid){
            unset($_SESSION['cart'][$pid]);

    }
    
    function changeItemQty($pid, $qty){
            unset($_SESSION['cart'][$pid]);
    
            $q = "SELECT name, price, imgSrc, descr FROM product WHERE pid = $pid";
            $r = mysqli_query ($GLOBALS['dbc'], $q);
            
            if (mysqli_num_rows($r) == 1) {
                list($name, $price, $imgSrc, $descr) = mysqli_fetch_array ($r, MYSQLI_NUM);
            }
            
            $_SESSION['cart'][$pid] = array('name' => $name,'price' => $price,'qty' => $qty,'imgSrc' => $imgSrc,'descr' => $descr);
    }
    
    function addOrder(){
        //Exit if missing data
        if(!isset($_SESSION['cart']) || !isset($_COOKIE['user_id']))
            return 0;
        
        // Need the order ID:
        $oid = mysqli_insert_id($GLOBALS['dbc']);

        // Insert the specific order contents into the database...

        // Prepare the query:

        $q = "INSERT INTO orders(order_id, user_id, pid, price, qty, status, deleted) VALUES(?, ?, ?, ?, ?, ?, 0)";
        $stmt = mysqli_prepare($GLOBALS['dbc'], $q);
        mysqli_stmt_bind_param ($stmt, 'iiidis', $oid, $user_id, $pid, $price, $qty, $status);
        
        $user_id = $_COOKIE['user_id'];
        $status = "Pending";

        $affected=0;
        
        foreach ($_SESSION['cart'] as $pid => $item){
            $price = $item['price'];
            $qty = $item['qty'];
            
            mysqli_stmt_execute($stmt);
            $affected += mysqli_stmt_affected_rows($stmt);
        }
        mysqli_stmt_close($stmt);

        if($affected == count($_SESSION['cart'])){
            mysqli_commit($GLOBALS['dbc']);
            unset($_SESSION['cart']);
            return 1;
        }else{
            mysqli_rollback($GLOBALS['dbc']);
            return 0;
        }

    };
    
?>

