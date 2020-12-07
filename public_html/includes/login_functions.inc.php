<?php

function redirect_user($page = 'index.php') { //1. defining a new function
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']); //2. Starting define URL
// 4. Remove any trailing slashes:
    $url = rtrim($url, '/\\');

// 5. Add the page:
    $url .= '/' . $page;

// 6. Redirect the user:
    header("Location: $url");
    exit(); // Quit the script.
}

// End of redirect_user( ) function.
//-----------------7. Begin new function----------------------------

function check_login($dbc, $email = '', $pass = '') {
//echo "email=, $email and password=, $pass";
    $errors = array(); // Initialize error array.
// Validate the email address:
//    if (empty($email)) {
//        $errors[ ] = 'You forgot to enter your email address.';
//    } else {
//        $e = mysqli_real_escape_string($dbc, trim($email));
//    }    
//    // Validate the password:e
//    if (empty($pass)) {
//        $errors[ ] = 'You forgot to enter your password.';
//    } else {
//        $p = mysqli_real_escape_string($dbc, trim($pass));
//    }
    if (empty($errors)) { // If everything's OK.
// Retrieve the user_id and first_name for that email/password combination:
        $q = "SELECT user_id,first_name,last_name,email,pass,user_level,active FROM users WHERE email='$email' AND pass='$pass' AND active IS NULL";
        $r = @mysqli_query($dbc, $q);
// Run the query.

        if (mysqli_num_rows($r) == 1) {
// Fetch the record:
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
// Return true and the record:
            return array(true, $row);
        } else { // Not a match!
//echo 'The email address and password entered do not match those on file.';
            $current = $current = explode('?', $_SERVER["HTTP_REFERER"], 2)[0];
            header("Location: ".$current. "?error=Incorrect email or password!");
//            header("Location: " . $_SERVER["HTTP_REFERER"] );
            exit();
        }
    } // End of empty($errors) IF.    
// Return false and the errors:
    return array(false, $errors);
}



function check_pass($dbc, $user_id = '', $pass = '') {

    $errors = array(); // Initialize error array.

    if (empty($errors)) { // If everything's OK.
// Retrieve the user_id and first_name for that email/password combination:
        
        $q = "select user_id,pass from users where user_id='$user_id' and pass='$pass'";
        $r = mysqli_query($dbc, $q);
// Run the query.

        if (mysqli_num_rows($r) == 1) {
// Fetch the record:
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
// Return true and the record:
            return array(true, $row);
        } else { // Not a match!
//echo 'The email address and password entered do not match those on file.';
            
           
//            exit();
        }
    } // End of empty($errors) IF.    
// Return false and the errors:
    return array(false, $errors);
}

// End of check_login( ) function.


