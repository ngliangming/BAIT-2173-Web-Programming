<div class="mx-auto p-0 h-100 d-flex flex-column">
    <?php
        //page title
        $page_title = "Aeki - Purchase Confirmation";
        //page background
        $bg = "none";
        
        if (!(isset($_COOKIE['user_id']))) {
            require ('includes/login_functions.inc.php');
            redirect_user();
        }
        
        include 'includes/header.html';
        require 'includes/cart_functions.inc.php';
        
        $msgColor = 'red;';
        $msg = 'Your order could not be processed due to a system error. We apologize for the inconvenience.';

        if(addOrder() == 1){
            $msgColor = 'green';
            $msg = 'Thank you for your purchases.';
        }
    ?>
<style>
    header{
        box-shadow: 0 0 5rem rgba(0, 0, 0, .5);
    }
    
    .main-text{
/*    color: #fff;
    background-color: rgb(0,0,0, .35);
    padding: 1.5rem 1.5rem;
    
    border-style: solid;
    border-color: rgb(0,0,0, .25);
    border-width: 1rem;
*/
    }
    
</style>

    <!--//Main starts here, enter your shit here -->
    <main class="text-center cover-container cover-head mx-auto mt-auto amb-auto">
        <div class="main-text" style="color: <?php echo $msgColor; ?>">
            <h1><center><?php echo $msg; ?></center></h1>
        </div>

    </main>
    <!--//Main ends here -->

<?php include 'includes/footer.html'?>