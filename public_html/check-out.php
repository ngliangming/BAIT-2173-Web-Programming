<?php
    //page title
    $page_title = "Aeki - Checkout";
    //page background
    $bg = "none";

    if (!(isset($_COOKIE['user_id']))) {
        require ('includes/login_functions.inc.php');
        redirect_user();
    }
    
    include 'includes/header.html';
?>

<html>
    <style>

.logo img{
    width: 15rem;
}


.nav-checkout{
    font-size: 1rem;
    color: #868686;
}
.nav-checkout a{
    text-decoration: none;
    color: #868686;
}
.nav-checkout a:hover{
    text-decoration: underline;
}
.nav-checkout strong{
    color: black;
}


/* Form */

/* Contact Info */
.contact-info{
    width: 100%;
}

/* Shipping Address */
.shipping-address .sa-row{
    width: 100%;
    display: flex;
}
.shipping-address .sa-row .input-box{
    display: flex;
    justify-content: space-around;
}
.shipping-address .sa-row select,
.shipping-address .sa-row input{
    width: 100%;
}

.shipping-address .sa-row select,
.shipping-address .sa-row-l input{
    margin-right: 5rem;
}


    


/* Input Animation */
.input-box{
    position: relative;
    width: 100%;
    height: 2.5rem;
    margin: 1rem 0;
    display: flex;
    justify-content: space-around;
}
.input-box select,
.input-box input{
    left: 0;
    width: 100%;
    height: 100%;
    font-size: 1rem;
    padding: 0 0.25rem;
    border: 0.1rem solid #CCC;
    border-radius: .5rem;
    background-color: #F6F6F6;
    float: left;
}
.input-box span{
    position: absolute;
    font-size: 1.2rem;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
    transition: 0.5s;
    pointer-events: none;
}
.input-box select:focus,
.input-box input:focus{
    border: .1rem solid #5fa8d3;
}
.input-box select:focus + span,
.input-box input:focus + span{
    top: 0;
    font-size: 1.2rem;
    left: 1rem;
    color: #5fa8d3;
    padding: 0 .1rem;
    background: linear-gradient(180deg, #FFFFFF 50%, #F6F6F6 50%);
}
.input-box select:valid + span,
.input-box input:valid + span{
    top: 0;
    font-size: 1rem;
    left: .1rem;
    padding: 0 .1rem;
    background: linear-gradient(180deg, #FFFFFF 50%, #F6F6F6 50%);
}

.button-row{
    height: 2rem;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 0 .1rem;
}
.button-row a{
    text-decoration: none;
    font-size: 1.2rem;
    color: #868686;
}
.button-row a:hover{
    text-decoration: underline;
}
form button{
    height: 2.5rem;
    border-radius: .5rem;
    background-color: #53554c;
    color: white;
    font-size: 1rem;
    border: none;
    transition: 0.2s;
    cursor: pointer;
}
form button:hover{
    background-color: #8e9671;
}

        </style>




    <!--//Main starts here, enter your shit here -->

    <main>
<div class="main_container d-flex flex-column" style="padding: 2.5rem 10rem;">
    <div class="container-left">
        <div class="logo">
            <img src="includes/logo-trans.png" alt="">
        </div>
        <div class="nav-checkout">
            <a href="cart.php">Shopping Cart</a> > 
            <strong>Information</strong> > 
            <span>Payment</span>
        </div>
        <br><br>
        
                 <form class="text-center" action="payment.php" method="post">
                
            <br>
            <div class="shipping-address">
                <strong>Shipping Address</strong>

                <div class="address">
                    <div class="input-box">
                        <?php
                            if(!empty($_GET['address']))
                                echo '<input type="text" name="sa-address" required="required" value="'.$_GET['address'].'">';
                            else
                                echo '<input type="text" name="sa-address" required="required">';
                        ?>
                        <span>Address<span>
                    </div>
                    <div class="sa-row">
                        <div class="input-box sa-row-l">
                            <?php
                                if(!empty($_GET['postcode']))
                                    echo '<input type="number" name="sa-postcode" required="required" value="'.$_GET['postcode'].'">';
                                else
                                    echo '<input type="number" name="sa-postcode" required="required">';
                            ?>
                            <span>Postcode<span>
                        </div>
                        <div class="input-box">
                            <?php
                                if(!empty($_GET['city']))
                                    echo '<input type="text" name="sa-city" required="required" value="'.$_GET['city'].'">';
                                else
                                    echo '<input type="text" name="sa-city" required="required">';
                            ?>
                            <span>City<span>
                        </div>
                    </div>
                    <div class="sa-row">
                        <div class="input-box sa-row-l">
                            <select name="sa-state" required id="state">
                                <option value="" disabled selected>-</option>
                                <option value="Johor" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Johor') ? 'selected' : ''; ?>>Johor</option>
                                <option value="Kedah" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Kedah') ? 'selected' : ''; ?>>Kedah</option>
                                <option value="Kelantan" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Kelantan') ? 'selected' : ''; ?>>Kelantan</option>
                                <option value="Kuala Lumpur" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Kuala Lumpur') ? 'selected' : ''; ?>>Kuala Lumpur</option>
                                <option value="Labuan" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Labuan') ? 'selected' : ''; ?>>Labuan</option>
                                <option value="Melaka" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Melaka') ? 'selected' : ''; ?>>Melaka</option>
                                <option value="Negeri Sembilan" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Negeri Sembilan') ? 'selected' : ''; ?>>Negeri Sembilan</option>
                                <option value="Pahang" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Pahang') ? 'selected' : ''; ?>>Pahang</option>
                                <option value="Perak" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Perak') ? 'selected' : ''; ?>>Perak</option>
                                <option value="Penang" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Penang') ? 'selected' : ''; ?>>Penang</option>
                                <option value="Perlis" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Perlis') ? 'selected' : ''; ?>>Perlis</option>
                                <option value="Putrajaya" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Putrajaya') ? 'selected' : ''; ?>>Putrajaya</option>
                                <option value="Selangor" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Selangor') ? 'selected' : ''; ?>>Selangor</option>
                                <option value="Sabah" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Sabah') ? 'selected' : ''; ?>>Sabah</option>
                                <option value="Sarawak" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Sarawak') ? 'selected' : ''; ?>>Sarawak</option>
                                <option value="Terengganu" <?php echo (isset($_GET['state']) && $_GET['state'] === 'Terengganu') ? 'selected' : ''; ?>>Terengganu</option>
                            </select>
                            <span>State/Territory<span>
                        </div>
                        <div class="input-box">
                            <input type="text" name="sa-country" required="required" value="Malaysia">
                            <span>Country/Region<span>
                        </div>
                    </div>
                    <div class="input-box">
                        <?php
                            if(!empty($_GET['phoneNo']))
                                echo '<input type="number" name="sa-phone" required="required" value="'.$_GET['phoneNo'].'">';
                            else
                                echo '<input type="number" name="sa-phone" required="required">';
                        ?>
                        <span>Phone<span>
                    </div>
                </div>
            </div>
            <div class="button-row">
                <a href="cart.php">< Return to cart</a>
                <button type="submit" name="checkout-info-submit">Continue to payment</button>
            </div>
        </form>
    </div>

    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>