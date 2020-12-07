<?php
    //page title
    $page_title = "Aeki - About Us";
    //page background
    $bg = "none";

?>


    <?php    include 'includes/header.html';?>

<style>
    p{
       font-size: 1rem;
       padding-left: 15px;
       padding-right: 15px;
       margin-top: 15px;
       color: black; 
    }
    
    h1{
        font-family:serif;
        font-size: 40px;
    }
    
    .title-text{
        display: table-cell;
        color: #fff;
        background-color: rgb(0,0,0, .35);
        padding: 1.5rem 1.5rem;
        width: 4000rem;
        height: 12.5rem;
        line-height: 12.5rem;
        vertical-align: middle;

        border-bottom: .25em solid #333;
    }
    
    main{
        background-image: url(https://www.inforcelife.com/wp-content/uploads/2017/03/about-us-banner.jpg);
        background-position: center;
        width: 100%;
        height: 100%;
    }
    
    .img{
        position: relative;
        height: 15rem;
        /*width: 100%;*/
    }
    
    .img img{
        height: 100%;
    }
    
    </style>
<div class="main_container p-0 d-flex flex-column">
    <main>
        <div class="text-center title-text">
            <h1>Aeki company</h1>
        </div>
        <div style="position: absolute; margin-left: 5rem; margin-right: 5rem; margin-top: 1rem; top: 16rem;" class="clearfix">
            <div class="img text-center cover-container cover-head mx-auto mt-auto amb-auto">
                <img src="includes/logo-trans.png">
            </div>
            <div class='text-center'>
                <p align="justify"><strong>Aeki company</strong> is a Malaysia retailer company of furnitures that organised by two person, John Chan and Adam Lim  in Kuala Lumpur.And Ivan lee was a long experience in marketing. The company have retail the products in sofa ,table,bed and mattresses . Currently Aeki company has been established as the leading
                Malaysia specialist retailer of furniture.Furthermore, this company also was regarded as the most innovative visual retailer of furniture with the best and more exclusivey lish range.</p>
            </div>
        </div>
    </main>

<?php 
    include 'includes/footer.html';
?>