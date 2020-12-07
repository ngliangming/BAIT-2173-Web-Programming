<?php
    //page title
    $page_title = "Aeki - Products";
    //page background
    $bg = "none";

    include 'includes/header.html';
    require 'includes/cart_functions.inc.php';
?>
<style>
    .card{
        position: relative;
        min-height: 30rem;
    }
    
    
    .card-body{
        position: relative;
        height: 15rem;
    }
    
    .card-main{
        position: relative;
        width: 100%;
        height: 70%;
        margin-bottom: 0;
    }
    
    .desc{
        position: absolute;
        top: 0;
        left: 0;
    }
    
    .card-interface{
        position: absolute;
        right: 0;
        bottom: 0;
        align-items: right;
        margin: 0;
    }
    
    .card-interface form{
        padding: 0 0;
        margin: 0 0;
    }
    
    .cartBtn{
        position: absolute;
        right: 0;
        width: 6.5rem;
        height: 2rem;
        bottom: 0;
    }
    
    .qtyTxt{
        position: absolute;
        right: 10.5rem;
        width: 5rem;
        font-size: 1rem;
        bottom: 0.25rem;
        padding: 0 0;
        text-align: right;
    }
    
    .qtyBox{
        position: absolute;
        right: 7rem;
        width: 3rem;
        height: 2rem;
        bottom: 0;
        padding: 0 0;
        text-align: right;
    }
    
    .avMsg{
        position: absolute;
        bottom: .25rem;
        left: .25rem;
        color: red;
    }
    
</style>
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
    
    function displayProduct($pid, $name, $price, $imgSrc ,$desc, $available){
        $avMsg = '';
                
        if($available == 'Available'){
            $status = '';
        }
        else {
            $status = 'disabled';
            $avMsg = 'Unavailable';
        }
        echo '
            <div class="col-lg-4 mb-4">
                <div class="card"> 
                    <div style="height: 15rem; width: 100%; padding: 1rem 0" class="text-center mx-auto">
                        <img class="" src="'.$imgSrc.'" alt="" style="max-height: 100%; max-width: 100%;"> 
                    </div>
                    <div class="card-body"> 
                        <h5 class="card-title">'.$name.'</h5> 
                        <h6 class="card-title">RM '.number_format($price, 2).'</h6> 
                        <div class="card-main d-flex"">
                            <div class="avMsg ">
                                <b>'.$avMsg.'</b>
                            </div>
                            <div class="desc">
                                <p class="card-text float-left">'.$desc.'</p> 
                            </div>
                            <div class="card-interface">
                                <form method="post">
                                    <span class="qtyTxt">Qty : </span><input type="number" class="qtyBox" name="qty" value="1" class="qtyBox" '.$status.'/>
                                    <button type="submit" name="pid" value="'.$pid.'" class="btn btn-outline-primary btn-sm cartBtn" '.$status.'>Add To Cart</button>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div>
        ';
    }
?>
<div class="main_container d-flex flex-column">
    
    <!--//Main starts here, enter your shit here -->

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

            <div class="row"> 
                
                <?php 
                    if(isset($_POST['pid']))
                        //Add product to cart
                        addItem($_POST['pid'], $_POST['qty']);
                    
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
                    
//                    echo $q;
                    while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
                    // Display each record:
                        
                    displayProduct($row['pid'], $row['name'], $row['price'], $row['imgSrc'],  $row['descr'], $row['available']);
                    } // End of while loop.
                ?>
            </div>

        </div>
        
    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>