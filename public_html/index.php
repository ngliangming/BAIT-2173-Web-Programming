<div class="mx-auto p-0 h-100 d-flex flex-column" style="color: #fff;">
    <?php
        //page title
        $page_title = "Aeki";
        //page background
        $bg = "cover";  
        
        include 'includes/header.html';
    ?>
<style>
    header{
        box-shadow: 0 0 5rem rgba(0, 0, 0, .5);
    }
    
    .main-text{
    color: #fff;
    background-color: rgb(0,0,0, .35);
    padding: 1.5rem 1.5rem;
    
    border-style: solid;
    border-color: rgb(0,0,0, .25);
    border-width: 1rem;
    }
    
</style>

    <!--//Main starts here, enter your shit here -->
    <main class="text-center cover-container cover-head mx-auto mt-auto amb-auto">
        <div class="main-text">
            <h1>Welcome to Aeki</h1>
            <p>
                Here at Aeki Company, we sell an assortment of furniture ranging from tables to bed mattresses. We pride ourselves on our quality furniture and customer service. We hope you'll be able to find that our wide range of selection suits your taste.
            </p>
            
            <a href="products.php" class="btn btn-lg btn-secondary cust-btn">Proceed to store</a>
        </div>

    </main>
    <!--//Main ends here -->

<?php include 'includes/footer.html'?>
