<?php
/*
by: Elavarasan
on: 18/02/2023
*/

if(isset($_GET['id'])){
    $products = new Product();
    $product = $products->getProductByID($_GET['id']);
?>

<div class="container-xl">
    <div class="d-flex flex-wrap m-5 clearfix">
        <div class="col-sm-12 col-lg-6 p-3 d-flex flex-align-items flex-justify-center"><img src="public/images/<?php echo $product['file_name'] ?>" alt="#" class="col-8"></div>
        <div class="col-sm-12 col-lg-6 p-3">
            <h1 class="pb-2"><?php echo $product['name']; ?></h1>
            <h2 class="pb-2">$<?php echo $product['price']; ?></h2>
            <button class="btn btn-outline mt-3 p-2 d-block">Add to cart</button>
            <button class="btn btn-primary mt-3 p-2 d-block">Buy now</button>
        </div>
        <div class="col-12 p-3"><?php echo $product['description']; ?></div>
    </div>
</div>

<?php
}
else{
    header('Location: '.APPPATH);
    exit;
}
?>