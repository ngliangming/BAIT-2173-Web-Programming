<?php
    //page title
    $page_title = "Aeki - Contact Us";
    //page background
    $bg = "none";

    include 'includes/header.html';
    ?>
<style>
.wrapper-main{
    width: 80%;
    margin: 30px auto;

}

.container{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
}

.container img{
    width: 45%;
    border-radius: 30px;
}

.container .text{
    display: block;
    margin: 30px 0;
}
.container .text strong{
    font-size: 55px;
}
.container .text p{
    font-size: 30px;
    color: rgba(82, 82, 82, 0.795);
}

.container1{
    display: flex;
    flex-direction: row;
    width: 100%;
    padding: 10px;
}
.container1 .box{
    width: 50%;
}
.container1 strong{
    font-size: 35px;
}
.container1 p{
    font-size: 20px;
}
.container1 p a{
    color: rgba(82, 82, 82, 0.795);
}
.container1 p a:hover{
    color: black;
    text-decoration: underline;
}

.location strong{
    font-size: 35px;
}
        
</style>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"/>


<div class="main_container mr-auto ml-auto mx-auto p-0 d-flex flex-column">

    <!--//Main starts here, enter your shit here -->

    <main>

        
<div class="wrapper-main">
    <div class="container">
        <div class="text">
            <strong>We're here to help!</strong>
            <p>Got a suggestion, compliment or complaint? Get in touch with us, we'd love to hear from you.</p>
        </div>
        <img src="includes/contact-us.jpg">  
        
    </div>
    <br><br>
    <div class="location">
        <strong>Location</strong>
        <iframe src="https://maps.google.com/maps?q=Tunku%20Abdul%20Rahman%20University%20College%20kuala&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="450" frameborder="0" style="border:solid black 2px;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    
    <div class="container1">
        <div class="box">
            <strong>Address <i class="fa fa-map-marker" aria-hidden="true"></i></strong>
            <p>No.3, Jalan genting, 45300,<br> KUALA LUMPUR ,WILAYAH</p>
        </div>
        <div class="box">
            <strong>Contact Number <i class="fa fa-phone" aria-hidden="true"></i></strong>
            <p>03-1145888</p>
            <br>
            <strong>Email <i class="fa fa-envelope" aria-hidden="true"></i></strong>
            <p><a href="mailto:chuanzhi20@gmail.com">aeki_company@gmail.com</a></p>
        </div>
    </div>
    <br><br><br>
</div>


    </main>
    <!--//Main ends here, stop entering your shit here -->

<?php include 'includes/footer.html'?>