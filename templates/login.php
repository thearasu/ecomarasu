<?php
/*
by: Elavarasan
on: 18/02/2023
*/

if(isset($_POST['email']) && isset($_POST['password'])){
    $users = new User();
    $user = $users->userLogIn($_POST['email'],$_POST['password']);
    if($user){
        $_SESSION['loggedIn'] = true;
        $_SESSION['email'] = $user['email'];
        $_SESSION['isAdmin'] = $user['is_admin'];
        header('Location: '.APPPATH);
    }
    else{
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/@primer/css@^20.2.4/dist/primer.css" rel="stylesheet" />
    <title>Log into EComArasu</title>
</head>
<body>
    <header class="text-center m-5"><a href="./" class="Header-link color-fg-default">EComArasu</a></header>
    <?php if(isset($error)){ ?>
    <div class="text-center m-2 color-fg-danger">Your email and password does not match our records!</div>
    <?php } ?>
    <div class="container-lg">
        <div class="d-flex col-12 flex-align-center flex-justify-center">
            <form class="Box col-10 col-md-8 col-lg-6 mr-2 pb-4 ml-2 rounded-0 d-flex  flex-column flex-align-center flex-justify-center" method="POST">
                <div class="form-group flex-self-center col-10">
                    <div class="form-group-header"><label for="email">Email:</label></div>
                    <div class="form-group-body"><input type="email" class="form-control width-full" name="email" id="email" required></div>
                </div>
                <div class="form-group flex-self-center col-10">
                    <div class="form-group-header"><label for="password">Password:</label></div>
                    <div class="form-group-body"><input type="password" class="form-control width-full" name="password" id="password" required></div>
                </div>
                <div class="flex-self-center col-10">
                    <button class="btn btn-primary" type="submit">Log in</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="text-center m-5">copyright &copy; 2023 EComArasu</footer>
</body>
</html>