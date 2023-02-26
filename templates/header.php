<?php
/*
by: Elavarasan
on: 16/02/2023
*/

# this file contains the header template
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/@primer/css@^20.2.4/dist/primer.css" rel="stylesheet" />
    <title>EComArasu</title>
    
</head>
<body>
    <div class="Header">
        <div class="Header-item">
            <a href="./" class="Header-link">EComArasu</a>
        </div>
        <div class="Header-item Header-item--full"></div>
        <?php
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){ ?>
        <div class="Header-item">
            <p class="m-0">Hi <?php echo $_SESSION['email']; ?></p>
        </div>
        <?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']){?>
        <div class="Header-item">
            <a href="./admin" class="Link">Admin</a>
        </div>
        <?php } ?>
        <div class="Header-item mr-0">
            <a href="./logout.php" class="Link">log out</a>
        </div>
        <?php }else{ ?>
        <div class="Header-item">
            <a href="./login.php" class="Link">log in</a>
        </div>
        <!--div class="Header-item mr-0">
            <a href="#" class="Link">Sign up</a>
        </div-->
        <?php } ?>
    </div>