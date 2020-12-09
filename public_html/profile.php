<div class="mx-auto p-0 h-100 d-flex flex-column" style="color: #fff;">
    <?php
    //page title
    $page_title = "Aeki - Profile";
    //page background
    $bg = "none";
    
    require 'includes/login_functions.inc.php';
    
    if (!(isset($_COOKIE['user_id']))) {
        redirect_user();
    }
    
    require 'includes/config.inc.php';
    require 'mysql_connect.php';
    
    if (isset($_GET['delete'])){
        if($_GET['delete'] == 1)
            $q = 'DELETE FROM users WHERE user_id = '.$_COOKIE['user_id'];
            $r = mysqli_query ($dbc, $q);
            
            $q = 'UPDATE orders SET deleted = 1 WHERE user_id = '.$_COOKIE['user_id'];
            $r = mysqli_query ($dbc, $q);
            
            $q = 'UPDATE orders SET status = "Cancelled" WHERE (user_id = '.$_COOKIE['user_id'].' AND status = "Pending")';
            $r = mysqli_query ($dbc, $q);
            
            redirect_user('logout.php');
//            INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `pass`, `user_level`, `active`, `registration_date`) VALUES ('2', 'Joshua', 'Ng', 'joshuang990924@gmail.com', 'asd123', '0', NULL, '2020-12-03 19:26:13');
    }
    
    include 'includes/header.html';
    ?>
    <style>

        input[type=text]{
            border:none;
            border-bottom: 1px solid black;
            background-color: transparent;
        }
        label{
            color: black;
        }
        .changepassform{
            padding-left: 5%;
            padding-right: 5%;
        }
        .userprofile-left{
            /*            border: 1px solid black;*/
            width: 50%;
            position: absolute;
            padding-left: 5%;
            padding-right: 5%;
        }
        .userprofile-right{
            /*            border: 1px solid black;*/
            float: right;

            width: 50%;
        }
        h1{
            margin-left: 15%;
        }
        @media screen and (max-width: 768px) {
            .userprofile-left{
                /*            border: 1px solid black;*/
                width: 100%;
                position: relative;
            }
            .userprofile-right{
                /*            border: 1px solid black;*/
                float: left;
               
                width: 100%;
            }
        }
    
        .btn-outline-danger{
            background-color: lightcoral;
        }
    </style>
    <br><br>
    <!--//Main starts here, enter your shit here -->
    <main class="mt-4">
        <div class="userprofile-left">
            <h1 style="color:black">User Information</h1><hr>
            <div class="ml-5">
                <div class="ml-5">
                    <label for="first_name"><b>First Name</b></label><br>
                    <p name="first_name" style="color:black"><?php echo ($_COOKIE['first_name']) ?></p>

                    <label for="last_name"><b>Last Name</b></label><br>         
                    <p name="first_name" style="color:black"><?php echo ($_COOKIE['last_name']) ?></p>

                    <label for="email"><b>Email</b></label><br>            
                    <p name="first_name" style="color:black"><?php echo ($_COOKIE['email']) ?></p>
                    <div class="text-center" style="margin-top: 10rem">
                        <button type="button" class="btn btn-outline-danger custBtn" data-toggle="modal" data-target="#delete"><b>Delete Account</b></button>
                        <div class="modal fade" id="delete">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header text-center">
                                        <h4 class="modal-title font-weight-bold" style="color:#333">DELETE ACCOUNT</h4>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true" style="font-size:4rem">&times;</span>
                                        </button>
                                    </div>
                                    <br>
                                        <div class="modal-body text-center">

                                            <div class="md">
                                                <span class="oi" data-glyph="envelope-closed"></span>
                                                <label style="color:#333">Are you sure you want to delete your account?</label><br><br>
                                                <label style="color:#333">Once your account is deleted, all orders will be cancelled immediately along with any data associated with this account!</label><br><br>
                                            </div>
                                            <div class="d-flex justify-content-center mb-lg-2">
                                                <button class="btn btn-outline-danger custBtn" onclick="window.location.href='?delete=1'" >Confirm</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            

        </div>

        <div class="userprofile-right">
            <form action="profile.php" method="post" class='changepassform'>
                <?php
    
                $msgColor = 'red';
                $msgText = '';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (!isset($_COOKIE['user_id'])) {
                        redirect_user();
                    } else {
                        $user_id = $_COOKIE['user_id'];
                    }
                    // Trim all the incoming data:
                    $trimmed = array_map('trim', $_POST);
                    $trimmed['currentpass'];
                    // Check for a password and match against the confirmed password:

                    list($check, $data) = check_pass($dbc, $user_id, $trimmed['currentpass']);
                    
                    if ($check) {
                        if (preg_match('/^\w{4,20}$/', $trimmed['password1'])) {
                            if ($trimmed['password1'] == $trimmed['password2']) {
                                $p = mysqli_real_escape_string($dbc, $trimmed['password1']);
                                $changepass = $trimmed['password1'];
                                $q = "UPDATE users set pass='$changepass' where user_id='$user_id'";
                                $r = mysqli_query($dbc, $q);
                                if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
                                    // Finish the page:
                                    $msgColor = 'green';
                                    $msgText = 'Password changed!';

//                        exit(); // Stop the page.
                                } else if($trimmed['currentpass'] == $trimmed['password1']){
                                    $msgText = 'New password can not be the same as old password!';
                                } else { // If it did not run OK.
                                    $msgText = 'You could not changes password, try again.';
                                }
                            } else {
                                $msgText = 'Your password did not match the confirmed password!';
                            }
                        } else {
                            $msgText = 'Please enter a valid password that must be between 4 and 20 characters long!';
                        }
                    } else {
                    $msgText = 'Wrong current password!';
                    }
                    
                    mysqli_close($dbc);
                }
                ?>
                <h1 style="color:black">Change Password</h1>
                <hr>
                <label for="currentpass"><b>Current Password</b></label><br>
                <input type="password" name="currentpass" required
                       value="<?php if (isset($trimmed['currentpass'])) echo $trimmed['currentpass']; ?>" /> <br>

                <label for="password1"><b>New Password</b></label><br>
                <input type="password" name="password1" required 
                       value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" /> <br>


                <label for="password2"><b>Confirm Password</b></label><br>
                <input type="password" name="password2" required
                       value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" /><br><br>
                <input type="submit" value="Change">
                <div style="margin-top: 2.5rem">
                    <p style="color:<?php echo $msgColor;?>"><b><?php echo $msgText;?></b></p>
                </div>
            </form>
        </div>

    </main>
    <!--//Main ends here -->

    <?php include 'includes/footer.html' ?>
