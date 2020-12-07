<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Add New Product</title>
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

<style>
    #addForm{
        margin-left: 1%;
        margin-top: 5%;
        width:98%;
    }
</style>

</head>

<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    
    
// Validate the incoming data...
$errors = array( );

// Check for a product name:
if (!empty($_POST['name'])) {
    $name = trim($_POST['name']);
}else{
    $errors[ ] = 'Please enter the product name!';
}

// Validate the category...
if ( isset($_POST['cat']) && filter_var($_POST['cat'], FILTER_VALIDATE_INT, array('min_range' => 1)) ) {
$cat = $_POST['cat'];
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


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($errors)){
        header("Location: adminviewproduct.php?success=2");
    }
}

// Display the form...
    include 'includes/header.html';
?>

    <form id="addForm" enctype="multipart/form-data" action="" method="post">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="exampleFormControlInput1">Product Name</label>
         <input type="text" name="name" class="form-control" id="exampleFormControlInput1" placeholder=""
            value="<?php if (isset($_POST['name'])) echo htmlspecialchars($_POST['name']); ?>"
        />
    </div>
      
    <div class="form-group col-md-6">
      <label for="exampleFormControlSelect1">Product Category</label>
    <select name="cat" class="form-control"><option selected disabled>Select One</option>
            
           <?php // Retrieve all the artists and add to the pull-down menu.
           $q = "SELECT * FROM product_category";
           $r = mysqli_query ($dbc, $q);

           if (mysqli_num_rows($r) > 0) {
                while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) 
                {
           ?>
                <option value="<?php echo $row[0] ?>">
                    <?php echo $row[1] ?>
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
    
    <select name="avail" class="form-control">
            <option value="1">
                 Available
            </option>

            <option value="0">
                 Unavailable
            </option>
        </select>
      
    </div>
      
    <div class="form-group col-md-6">
      <label for="exampleFormControlInput1">Product Price</label>
    <input type="text" name="price" maxlength="10" class="form-control" placeholder=""
    value="<?php if(isset($_POST['price'])) echo $_POST['price']; ?>" /> <small>Do not include the dollar sign or commas.</small>
    </div>
  </div>
    
  <div class="form-group">
    <label for="exampleFormControlFile1">Product Image</label>
         <input type="file" name="imgSrc" class="form-control-file" value="<?php if(isset($_POST['imgSrc'])) echo $_POST['imgSrc']; ?>"/>
  </div>
    
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Product Description</label>
    <textarea name="descr" class="form-control"><?php if (isset($_POST['descr'])) echo $_POST['descr']; ?></textarea>
  </div>
    
  <button type="submit" name="addDone" class="btn btn-primary">Add</button>
</form>
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
