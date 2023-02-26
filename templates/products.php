<?php
/*
by: Elavarasan
on: 17/02/2023
*/

# This file contains the template which shows the products

$product = new Product();
$products = $product->getAllProducts();
?>

<div class="container-xl">
        <div class="d-flex flex-coulumn flex-wrap clear-fix m-5">
            <?php foreach($products as $product){ ?>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 flex-self-stretch d-flex flex-items-stretch">
                <a class="Box Box-condensed color-fg-default no-underline d-flex flex-column m-3 width-fit color-shadow-medium rounded-0" href="product.php?id=<?php echo $product['id']; ?>">
                    <div class="Box-body height-full d-flex flex-align-center flex-justify-center flex-items-center"> <img class="col-8 hight-fit" src="public/images/<?php echo $product['file_name']; ?>" alt=""></div>
                    <div class="Box-header rounded-0">
                        <h4><?php echo $product['name']; ?></h4>
                        <h5>$<?php echo $product['price']; ?></h5>
                        <button class="btn width-full btn-outline mt-2">Add to cart</button>
                        <button class="btn width-full btn-primary mt-2">Buy now</button>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>