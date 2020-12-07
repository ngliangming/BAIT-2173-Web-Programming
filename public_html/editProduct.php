<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Edit Product</title>
    </head>
    
    
   <style>
    #editForm{
        margin-left: 1%;
        margin-top: 5%;
        width:98%;
    }
</style>

<body>
<?php

$bg = "none";

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


require ('mysql_connect.php');

?>
    
<?php
if(isset($_POST['saveBtn'])){
    // Validate the incoming data...
    $errors = array( );

    // Check for a product name:
    if (!empty($_POST['editname'])) {
        $name = trim($_POST['editname']);
    }else{
        $errors[ ] = 'Please enter the product name!';
    }

    // Validate the category...
    if ( isset($_POST['editcat']) && filter_var($_POST['editcat'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
    $cat = $_POST['editcat'];
    } else { // No category_name selected.
    $errors[ ] = 'Please select the product category !';
    }

     // Check for a price:
    if (is_numeric($_POST['editprice']) && ($_POST['editprice'] > 0)) {
        $price = (float) $_POST['editprice'];
    } else {
        $errors[ ] = 'Please enter the product\'s price!';
    }


    // Check for an image:
    $target_dir = "products/";
    $target_file = $target_dir . basename($_FILES["editimgSrc"]["name"]); 
    move_uploaded_file($_FILES["editimgSrc"]["tmp_name"], $target_file);
    
    $editid=$_POST['edit_id'];
    
    if($_FILES['editimgSrc']['name']==''){
        $q = "SELECT * FROM product WHERE pid = $editid";
        $r = mysqli_query($dbc,$q);
        
        $row = mysqli_fetch_array($r,MYSQLI_ASSOC);
        $target_file = $row['imgSrc'];
        
    }

    if (empty($errors)) {
        $editname=$_POST['editname'];
        $editcat=$_POST['editcat'];
        $editprice=$_POST['editprice'];
        $editimgSrc=$_FILES["editimgSrc"]['name'];
        $editdescr=$_POST['editdescr'];
        $editavail=$_POST['editavail'];

        $query="UPDATE product SET name='$editname', category='$editcat', price='$editprice', imgSrc='$target_file', descr='$editdescr', available='$editavail' WHERE pid='$editid'";

        $query_run = @mysqli_query($dbc,$query);

        if(empty($errors)){
            header("Location: adminviewproduct.php?success=1");
        }

        // Display the form...
    //        include 'includes/header.html';
    }

    
}

?>
    
<?php
include ('includes/header.html');  
        $pid=$_POST['edit_id'];
        $q = "SELECT * FROM product p INNER JOIN product_category pc ON pc.category_id = p.category WHERE pid=$pid";
        $r = mysqli_query($dbc,$q);
        
        if(mysqli_num_rows($r) == 0){
            $q = "SELECT * FROM product p WHERE pid=$pid";
            $r = mysqli_query($dbc,$q);
            $nullCat = 1;
        }
        
        // Display all the prints, linked to URLs:
        while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
        {
?>
    
<form enctype="multipart/form-data" id="editForm" action="" method="post">
    <input type="hidden" name="edit_id" value="<?php echo $row['pid'] ?>">
    
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="exampleFormControlInput1">Product Name</label>
         <input type="text" name="editname" class="form-control"
                value="<?php echo $row['name'] ?>"/>
    </div>
      
    <div class="form-group col-md-6">
      <label for="exampleFormControlSelect1">Product Category</label>
    <select class="form-control" name="editcat"><option selected disabled>Select One</option>
               <?php // Retrieve all the artists and add to the pull-down menu.
               $q2 = "SELECT * FROM product_category";
               $r2 = mysqli_query ($dbc, $q2);

               if (mysqli_num_rows($r2) > 0) {
                    while ($row2 = mysqli_fetch_array ($r2, MYSQLI_NUM)) 
                    {
               ?>
                    <option value="<?php echo $row2[0] ?>" <?php if(!isset($nullCat)){if($row['category_id'] == $row2[0]) echo'selected';}?>>
                        <?php echo $row2[1] ?>
                    </option> 
               <?php
                    }
               } else {
                    echo '<option>Please add a new category first.</option>';
               }
                mysqli_close($dbc); // Close the database connection.
               ?>
             </select>
    </div>
  </div>
    
  <div class="form-row">
    <div class="form-group col-md-6">
        <label for="exampleFormControlSelect1">Product Availability</label>
    
    <select class="form-control" name="editavail">
        <option value="Available" <?php if($row['available'] == "Available") echo "selected"; ?>>
                Available
           </option>

           <option value="Out of stock" <?php if($row['available'] == "Out of stock") echo "selected"; ?>>
                Out of stock
           </option>
       </select>
      
    </div>
      
    <div class="form-group col-md-6">
      <label for="exampleFormControlInput1">Product Price</label>
    <input type="text" name="editprice" size="10" maxlength="10" class="form-control"
            value="<?php echo $row['price'] ?>" /> <small>Do not include the dollar sign or commas.</small>
    </div>
  </div>
    
  <div class="form-group">
    <label for="exampleFormControlFile1">Product Image</label>
         <input type="file" name="editimgSrc" class="form-control-file"/>
  </div>
    
  <div class="form-group">
      <label for="exampleFormControlTextarea1">Product Description  <small>(optional)</sma></label>
    <textarea name="editdescr" class="form-control"><?php echo $row['descr'] ?></textarea> 
  </div>
    
  <button type="submit" name="saveBtn" class="btn btn-primary">Save</button>
</form>    
    
<?php
    }
?>

<div>
    <?php
        if ( !empty($errors) && is_array($errors)) {
            echo '<h1>Error!</h1>
            <p style="font-weight: bold; color: #C00">The following error(s) occurred:<br />';

        foreach ($errors as $msg) {
            echo " - $msg<br />\n";
        }
            echo 'Please reselect the product image and try again.</p>';
        }
    ?>
</div>
</body>
</html>

