<?php
/*
by: Elavarasan
on: 17/02/2023
*/

# This file will load all the files that are needed to run the application

session_start();

// Defining paths
if(!defined('ABSPATH')){
    define('ABSPATH', __DIR__.'/');
    define('INCPATH', ABSPATH.'/includes');
    define('TMPPATH', ABSPATH.'/templates');
}

if(!defined('HTTPPATH')){
    define('HTTPPATH', $_SERVER['HTTP_HOST']);
    define('APPPATH', 'http://'.HTTPPATH.'/ecomarasu');
}

require_once(INCPATH.'/functions.php');