<?php
/*
by: Elavarasan
on: 18/02/2023
*/

# This file contins simple rest api and admin access is needed to access this api

include(dirname(__DIR__).'/load.php');

authUser();

$products = new Product();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        if(isset($_POST['action'])){
            $action = $_POST['action'];
            switch ($action) {
                case 'swap':
                    if(isset($_POST['fromId'], $_POST['toId'])){
                        echo $products->swapProducts($_POST['fromId'],$_POST['toId']);
                    }
                    break;
                case 'add':
                    $response = uploadFile($_FILES['file']);
                    if($response['success'] && isset($_POST['name'],$_POST['price'],$_POST['description'],$_POST['sort_order']) ){
                        $result = $products->createNewProduct($_POST['name'],$_POST['price'],$_POST['description'],$_POST['sort_order'],$response['success']);
                        if($result==false){
                            $response['success'] = false;
                            $response['error'] = 'unable to create a product.';
                        }
                        $response['success'] = 1;
                    }else{
                        if($response['success']){
                            $response['success'] = false;
                            $response['error'] = 'Not enough data to create a product.';
                        }
                    }
                    echo json_encode($response);
                case 'delete':
                    if(isset($_POST['id'])){
                        $status = $products->deleteProduct($_POST['id']);
                        echo $status;
                    }
                    break;
                case 'edit':
                    if($_FILES['file']['error'] === UPLOAD_ERR_OK){
                        $response = uploadFile($_FILES['file']);
                        if(isset($_POST['id'],$_POST['name'],$_POST['price'],$_POST['description']) && $response['success']){
                            $result = $products->editProduct($_POST['id'],$_POST['name'],$_POST['price'],$_POST['description'],$response['success']);
                            if($result==false){
                                $response['success'] = false;
                                $response['error'] = 'unable to edit product.';
                            }
                            $response['success'] = 1;
                        }
                        echo json_encode($response);
                    }else{
                        if(isset($_POST['id'],$_POST['name'],$_POST['price'],$_POST['description'])){
                            $result = $products->editProduct($_POST['id'],$_POST['name'],$_POST['price'],$_POST['description']);
                            if($result==false){
                                $response['success'] = false;
                                $response['error'] = 'unable to edit product.';
                            }
                            $response['success'] = 1;
                            $response['error'] = false;
                        }
                        echo json_encode($response);
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }
        break;
    
        case 'GET':
            if(isset($_GET['action'])){
                $action = $_GET['action'];
                switch ($action) {
                    case 'get-all-products':
                        echo json_encode($products->getAllProducts());
                        break;
                    case 'get-default-sort':
                        echo $products->getDefaultSort();
                        break;
                    case 'check-sort-exists':
                        if(isset($_GET['sort_order'])){
                            echo $products->checkSortExists($_GET['sort_order'])?1:0;
                        }
                        break;
                    case 'get-product':
                        if(isset($_GET['id'])){
                            echo json_encode($products->getProductByID($_GET['id']));
                        }
                        break;
                    case 'check-logged-in':
                        echo isUserLoggedIn();
                        break;
                    default:
                        # code...
                        break;
                }
            }
            break;

    default:
        # code...
        break;
}

