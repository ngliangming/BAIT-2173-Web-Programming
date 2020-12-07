<?php
    //page title
    $page_title = "Aeki - Payment";
    //page background
    $bg = "none";
    
    if (!(isset($_COOKIE['user_id']))) {
        require ('includes/login_functions.inc.php');
        redirect_user();
    }
    
    include 'includes/header.html';
?>
<?php
    

    // Display error message of the input validation
    if(isset($_GET['error']))
    {
        if($_GET['error'] == "emptyfields"){
            echo '<script>alert("Please fill in all the fields provided.");</script>';
        }
        else if($_GET['error'] == "name"){
            echo '<script>alert("Please fill in the appropriate name.");</script>';
        }
        else if($_GET['error'] == "cardno"){
            echo '<script>alert("Please fill in the appropriate card numbers.");</script>';
        }
        else if($_GET['error'] == "expMonth"){
            echo '<script>alert("Please fill in the appropriate expiry month.");</script>';
        }
        else if($_GET['error'] == "expYear"){
            echo '<script>alert("Please fill in the appropriate expiry year.");</script>';
        }
        else if($_GET['error'] == "cardcvv"){
            echo '<script>alert("Please fill in the appropriate CVV numbers.");</script>';
        }
    }

    if(isset($_GET['discount'])){
        if($_GET['discount'] == "notmatch"){
            echo '<script>alert("Invalid discount code.");</script>';
        }
    }
?>
<html>
    <style>
        
.product-name{
    float: left;
}
.product-price{
    position: absolute;
    right: 2.5rem;
    text-align: left;
}

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
form{
    font-size: 1.2rem;
}
.card{
    margin-bottom: 1.2rem;
    padding: 0 .5rem;
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

.input-box input:focus{
    border: .1rem solid #5fa8d3;
}

.input-box input:focus + span{
    top: 0;
    font-size: 1.2rem;
    left: 1rem;
    color: #5fa8d3;
    padding: 0 .1rem;
    background: linear-gradient(180deg, #FFFFFF 50%, #F6F6F6 50%);
}

.input-box input:valid + span{
    top: 0;
    font-size: 1rem;
    left: .1rem;
    padding: 0 .1rem;
    background: linear-gradient(180deg, #FFFFFF 50%, #F6F6F6 50%);
}

.card-row{
    width: 100%;
    display: flex;
    margin: 0;
}
.card-row .input-box{
    margin-top: .25rem;
    display: flex;
    justify-content: space-around;
}

.button-row{
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    margin-top: .25rem;
}
.button-row button{
    cursor: pointer;
    width: 12.5rem;
    height: 4rem;
    border: none;
    border-radius: 25rem;
    font-size: 1rem;
    transition: 0.2s;
}

.completeBtn{
    color: white;
    background-color: #53554c;
}
.completeBtn:hover{
    background-color: #8e9671;
}

.cancelBtn{    
    background-color: #dddddd;
}
.cancelBtn:hover{    
    background-color: #c9c9c9;
}


/* Containers*/
.container-left{
    float: left;
    /*width: 35%;*/
    /*width: 25%;*/
    padding-left : 0;
}

hr{
    margin: 1.5rem 0;
}
.container-right{
    position: relative;
    float: right;
    width: 35rem;
    background-color: #f0f0f0;
    padding: 1rem;
}

.container-total{
    position: absolute;
    width: 100%;
    left: 1rem;
    padding-right: 2rem;
    bottom: 1rem;
    align-items: center;
}
.container-total .total-row{
    display: flex;
    justify-content: space-between;
    font-size: 1ren;
}

.main{
    font-weight: 900;
}

.card-ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.card-li {
  float: left;
}

.card-li img{
  margin: .5rem .5rem;
}

        </style>
<head>
    <title>Payment</title>
   
</head>
<body>


    <main>
<div class="main_container d-flex clearfix" style="padding: 2.5rem 10rem;">
    <div class="container container-left  mx-auto">
        <div class="logo">
            <img src="includes/logo-trans.png" alt="">
        </div>
        <div class="nav-checkout">
            <a href="cart.php">Shopping Cart</a> > 
            <a href="check-out.php">Information</a> > 
            <strong>Payment</strong>
        </div>
        <br><br>

        <form action="updateOrder.php" method="POST">
            <div class="card">
                <p>Accepted Cards</p>
                <ul class="card-ul">
                    <li class="card-li"><img src="includes/visa.png" alt="" style="height: 5rem"></li>
                    <li class="card-li"><img src="includes/master.png" alt="" style="height: 5rem"></li>
                    <li class="card-li"><img src="includes/maybank.png" alt="" style="height: 5rem"></li>
                    <li class="card-li"><img src="includes/cimb.png" alt="" style="height: 5rem"></li>
                    <li class="card-li"><img src="includes/hongleong.png" alt="" style="height: 5rem"></li>
                    <li class="card-li"><img src="includes/publicbank.png" alt="" style="height: 5rem"></li>
                </ul>
            </div>

            <div class="input-box">
                <?php
                    if(!empty($_GET['cardname']))
                        echo '<input type="text" name="card-name" required="required" value="'.$_GET['cardname'].'">';
                    else
                        echo '<input type="text" name="card-name" required="required">';
                ?>
                <span>Name On Card<span>
            </div>

            <div class="input-box">
                <?php
                    if(!empty($_GET['cardno']))
                        echo '<input type="number" name="card-no" required="required" maxlength="16" value="'.$_GET['cardno'].'">';
                    else
                        echo '<input type="number" name="card-no" required="required" maxlength="16">';
                ?>
                <span>Credit Card Number<span>
            </div>

            <div class="input-box">
                <?php
                    if(!empty($_GET['expMonth']))
                        echo '<input type="number" name="card-exp-month" required="required" value="'.$_GET['expMonth'].'">';
                    else
                        echo '<input type="number" name="card-exp-month" required="required">';
                ?>
                <span>Exp Month<span>
            </div>

            <div class="card-row">
                <div class="input-box" style="margin-right: 1.5rem;">
                    <?php
                        if(!empty($_GET['expYear']))
                            echo '<input type="number" name="card-exp-year" required="required" maxlength="4" value="'.$_GET['expYear'].'">';
                        else
                            echo '<input type="number" name="card-exp-year" required="required" maxlength="4">';
                    ?>
                    <span>Exp Year<span>
                </div>
                <div class="input-box">
                    <?php
                        if(!empty($_GET['cardcvv']))
                            echo '<input type="number" name="card-cvv" required="required" maxlength="3" value="'.$_GET['cardcvv'].'">';
                        else
                            echo '<input type="number" name="card-cvv" required="required" maxlength="3">';
                    ?>
                    <span>CVV<span>
                </div>
            </div>

            <div class="button-row">
                <button type="submit" class="completeBtn" name="order-submit">Complete Order</button>
                <button type="reset" onclick="window.location.href='index.php'" class="cancelBtn">Cancel Order</button>
            </div>
        </form>
    </div>

    <div class="container container-right mx-auto">
        <div class="product-list">
            <?php
            $amt = 0;
            if(isset($_SESSION['cart']))
            {
                $totalPayment = 0;
                foreach($_SESSION['cart'] as $row => $items)
                {
            ?>
            
            <div class="product clearfix">
                <div class="product-name"><?php echo $items['name'];?></div>
                <div class="product-price">RM <?php echo sprintf("%.2f", $items['price'] * $items['qty']);?></div>
            </div>

            <?php
                $totalPayment = $totalPayment + ($items['price'] * $items['qty']);
                }
                $amt = $totalPayment;
            }
            ?>
            
            <div class="container-total">
                <hr>
                <?php
                    $shippingPrice = 7.0;
                    $discountPrice = 0;
                ?>
                <div class="total-row">
                    <span class="title">Subtotal</span>
                    <span class="price">RM <?php echo sprintf("%.2f", $amt);?></span>
                </div>
                <div class="total-row">
                    <span class="title">Shipping</span>
                    <span class="price">RM <?php echo sprintf("%.2f", $shippingPrice);?></span>
                </div>



                <hr>
                <div class="total-row main">
                    <span class="title">Total</span>
                    <span class="price">RM <?php echo sprintf("%.2f", $amt = ($amt + $shippingPrice ));?></span>
                </div>
            </div>
        </div>

                        
            </form>
        </div>

    </div>

    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>