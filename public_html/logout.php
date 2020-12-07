<?php
    //page title
    $page_title = "Aeki - Logout";
    //page background
    $bg = "default";
    
    require ('includes/login_functions.inc.php');
    
    session_start();
    if (!(isset($_COOKIE['user_id']))) {
        redirect_user();
    }
    



if (!isset($_COOKIE['user_id'])) {
    require ('includes/login_functions.inc.php');
    redirect_user();
} else {
    setcookie('user_id', '', time() - 3600, '/', '', 0, 0);
    setcookie('user_level', '', time() - 3600, '/', '', 0, 0);
    setcookie('first_name', '', time() - 3600, '/', '', 0, 0);
    setcookie('last_name','',time()-3600,'/','',0,0);
    setcookie('email','',time()-3600,'/','',0,0);
}
?>
<div class="main_container mr-auto ml-auto mx-auto p-0 d-flex flex-column">

    <!--//Main starts here, enter your shit here -->

    <main>

        <?php
            echo "<h1>Logged Out!</h1><p>You are now logged out!</p>{$_COOKIE['first_name']}";
            $current = $current = explode('?', $_SERVER["HTTP_REFERER"], 2)[0];
            header("Location: ".$current);
        ?>
    </main>
    <!--//Main ends here, stop entering your shit here -->

    <?php include 'includes/footer.html' ?>