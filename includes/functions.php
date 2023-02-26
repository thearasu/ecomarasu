<?php
/*
by: Elavafrasan
on: 16/02/2023
*/

# This file contains all the functions

function eca_autoloader($classname){
    include INCPATH.'/classes/'.$classname.'.php';
}

function end_session(){
    $_SESSION = array();
    session_destroy();
}

function authUser(){
    if(isset($_SESSION['loggedIn']) == false){
        header('Location: '.APPPATH.'/login.php');
        exit();
    }
}

function isUserLoggedIn(){
    if(isset($_SESSION['loggedIn']) == true){
        return 1;
    }else{
        return 0;
    }
}

function uploadFile($uploaded_file){
$error = false;
$success = true;
$file_type = ['image/png','image/jpg'];
$file_name = md5(rand());
$target_file = ABSPATH.'/public/images/'.$file_name;
if($uploaded_file['size']>1000000){
    $error = "file size should be less than 1MB.";
    $success = false;
}elseif(!in_array($uploaded_file['type'],$file_type)){
    $error = "Invalid file type";
    $success = false;
} elseif(move_uploaded_file($uploaded_file['tmp_name'], $target_file)){
    $error = false;
    $success = $file_name;
}else{
    $error = "unable to upload the file";
    $success = false;
}

return['success' => $success,'error' => $error];
}


spl_autoload_register('eca_autoloader');