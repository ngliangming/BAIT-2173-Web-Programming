<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register</title>

        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                background-image: url('includes/registercover.jpg');
                background-size: cover;
                background-repeat: no-repeat;
            }
            * {box-sizing: border-box;}
            .rigth{
                width: 100%;
            }
            input[type=text], input[type=password], input[type=email] {
                width: 100%;
                padding: 10px;
                margin: 5px 0 22px 0;
                display: inline-block;
                border: none;
                background: #f1f1f1;
            }


            input[type=text]:focus, input[type=password], input[type=password]:focus {
                background-color: #ddd;
                outline: none;
            }


            input[type=submit] {
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                cursor: pointer;
                width: 100%;
                opacity: 0.9;
            }

            input[type=submit]:hover {
                opacity:1;
            }

            .signupbtn {
                float: left;
                width: 50%;
            }


            .container {
                padding: 16px;
            }

            .modal-content {
                background-color: #fefefe;
                margin: -1% auto 0 auto;
                border: 1px solid #888;
                width: 50%; 
            }


            hr {
                border: 1px solid #f1f1f1;
                margin-bottom: 25px;
            }

            .clearfix::after {
                content: "";
                clear: both;
                display: table;
            }
            .error{
                font-size: .7rem;
                color: red;
            }

            @media screen and (max-width: 300px) {
                input[type=submit] {
                    width: 100%;
                }
            }

        </style>

    </head>
    <body>

        <div class="right">
            <div class="container">
                 
                <form class="modal-content" action="register.php" method="post">
                    <div class="container">
                        <?php
                        require 'includes/config.inc.php';
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form
// Need the database connection:
                            require (MYSQL);

// Trim all the incoming data:
                            $trimmed = array_map('trim', $_POST);

// Assume invalid values:
                            $fn = $ln = $e = $p = FALSE;

// Check for a first name:
                            if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
                                $fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
                            } else {
                                echo '<p class="error">Please enter your first name without special character!</p>';
                            }

// Check for a last name:
                            if (preg_match('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
                                $ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
                            } else {
                                echo '<p class="error">Please enter your last name without special character!</p>';
                            }

// Check for an email address:
                            if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
                                $e = mysqli_real_escape_string($dbc, $trimmed['email']);
                            } else {
                                echo '<p class="error">Please enter a valid email address!</p>';
                            }

// Check for a password and match against the confirmed password:
                            if (preg_match('/^\w{4,20}$/', $trimmed['password1'])) {
                                if ($trimmed['password1'] == $trimmed['password2']) {
                                    $p = mysqli_real_escape_string($dbc, $trimmed['password1']);
                                } else {
                                    echo '<p class="error">Your password did not match the confirmed password!</p>';
                                }
                            } else {
                                echo '<p class="error">Please enter a valid password that must be between 4 and 20 characters long!</p>';
                            }

                            if ($fn && $ln && $e && $p) { // If everything's OK...
// Make sure the email address is available:
                                $q = "SELECT user_id FROM users WHERE email='$e'";
                                $r = mysqli_query($dbc, $q) or trigger_error("\n\nQuery: $q\n<br />MySQL Error: " . mysqli_error($dbc));

                                if (mysqli_num_rows($r) == 0) { // Available.
// Create the activation code:
                                    $a = md5(uniqid(rand(), true));

// Add the user to the database:

                                    $q = "INSERT INTO users (first_name,last_name,email, pass, active, registration_date) VALUES ('$fn', '$ln','$e', '$p', '$a', NOW( ) )";
                                    $r = mysqli_query($dbc, $q) or trigger_error("\n\nQuery: $q\n<br/>MySQL Error: " . mysqli_error($dbc));

                                    if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
// Send the email:
                                        
                                        $url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                                        $url .= $_SERVER['SERVER_NAME'];
                                        $url .= $_SERVER['REQUEST_URI'];
                                        $url .= 'public_html/activate.php';
                                        
                                        $body = "Thank you for registering at REI website. To activate your account,please click on this link:\n\n";
                                        $body .= dirname(dirname($url)). '/activate.php?x=' . urlencode($e) . "&y=$a";
                                        mail($trimmed['email'], 'Registration Confirmation', $body, 'From: loiqipeng0225@gmail.com');

// Finish the page:
                                        echo '<h3>Thank you for registering! A confirmation email has been sent to
                                        your address. Please click on the link in that email in order to activate your
                                        account.</h3>';
                                        
                                        exit(); // Stop the page.
                                    } else { // If it did not run OK.
                                        echo '<p class="error">You could not be registered due to a system error. We
apologize for any inconvenience.</p>';
                                    }
                                } else { // The email address is not available.
                                    echo '<p class="error">That email address has already been registered.
</p>';
                                }
                            } else { // If one of the data tests failed.
                                echo '<p class="error">Please try again.</p>';
                            }

                            mysqli_close($dbc);
                        } // End of the main Submit conditional.
                        ?>
                        <h2>Sign Up</h2>

                        <hr>
                        <label for="first_name"><b>First Name</b></label>
                        <input type="text" placeholder="First Name" name="first_name" required 
                               value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>"/>

                        <label for="last_name"><b>Last Name</b></label>
                        <input type="text" placeholder="Last Name" name="last_name" required
                               value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>"/>                               

                        <label for="email"><b>Email</b></label>
                        <input type="email" placeholder="Enter Email" name="email" 
                               value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>"/>

                        <label for="password1"><b>Password</b></label>
                        <input type="password" placeholder="Enter Password" name="password1" required 
                               value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" /> 


                        <label for="password2"><b>Repeat Password</b></label>
                        <input type="password" placeholder="Repeat Password" name="password2" required 
                               value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>"/>


                        <div class="clearfix">                 
                            <input type="submit" name="submit" value="Sign Up" />
                        </div>
                    </div>
                </form>
            </div>
        </div>




    </body>
</html>

