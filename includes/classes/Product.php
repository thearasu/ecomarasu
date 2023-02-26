<?php
/*
by: Elavarasan
on: 17/02/2023
*/

# This file contains product class

class Product{
    private $connection;

    public function __construct(){
        $db = new Database();
        $this->connection = $db->connection;
    }

    public function getAllProducts(){
        $query = "SELECT * FROM products ORDER BY sort_order ASC";
        $result = $this->connection->query($query);
        return $result->fetchAll();
    }

    public function getProductByID($id){
        $query = "SELECT * FROM products WHERE id=:id";
        $result = $this->connection->prepare($query);
        $result->bindParam('id',$id);
        $result->execute();
        return $result->fetch();
    }

    public function swapProducts($fromId, $toId){
        $fromIdOrderChangeto = $this->getSortOrder($toId);
        $toIdOrderChangeto = $this->getSortOrder($fromId);
        $this->changeOrder($fromId,$fromIdOrderChangeto);
        $this->changeOrder($toId,$toIdOrderChangeto);
    }

    public function getDefaultSort(){
        $query = "SELECT MAX(sort_order) FROM products";
        $result = $this->connection->query($query);
        $sort_order = $result->fetch();
        return $sort_order[0]+1;
    }

    public function checkSortExists($sort_order){
        $query = "SELECT sort_order FROM products WHERE sort_order=:sort_order";
        $result = $this->connection->prepare($query);
        $result->bindParam('sort_order',$sort_order);
        $result->execute();
        $sort = $result->fetch();
        if(isset($sort['sort_order']))
        return true;
        else
        return false;
    }

    public function createNewProduct($name,$price,$description,$sort_order,$file_name){
        if($this->checkSortExists($sort_order)){
            $id = $this->getIdbySortOrder($sort_order);
            $defaultSort = $this->getDefaultSort();
            $this->changeOrder($id, $defaultSort);
        }

        $query = "INSERT INTO products (name, price, description, sort_order, file_name) VALUES (:name, :price, :description, :sort_order, :file_name)";
        $result = $this->connection->prepare($query);
        $result->bindParam('name',$name);
        $result->bindParam('price',$price);
        $result->bindParam('description',$description);
        $result->bindParam('sort_order',$sort_order);
        $result->bindParam('file_name',$file_name);
        $result->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function editProduct($id,$name,$price,$description,$file_name=null){
        if($file_name == null){
            $query = "UPDATE products SET name=:name, price=:price, description=:description WHERE id=:id";
            $result = $this->connection->prepare($query);
            $result->bindParam('name',$name);
            $result->bindParam('price',$price);
            $result->bindParam('description',$description);
            $result->bindParam('id',$id);
            $result->execute();
            if($result){
                return 1;
            }else{
                return 0;
            }
        }else{
            $query = "UPDATE products SET name=:name, price=:price, description=:description, file_name=:file_name WHERE id=:id";
            $result = $this->connection->prepare($query);
            $result->bindParam('name',$name);
            $result->bindParam('price',$price);
            $result->bindParam('description',$description);
            $result->bindParam('file_name',$file_name);
            $result->bindParam('id',$id);
            $result->execute();
            if($result){
                return 1;
            }else{
                return 0;
            }
        }
    }

    /*public function editProduct($id,$name,$price,$description,$file_name){

    }*/

    public function deleteProduct($id){
        $query = "DELETE FROM products WHERE id=:id";
        $result = $this->connection->prepare($query);
        $result->bindParam('id',$id);
        $result->execute();
        return true;
    }

    private function getSortOrder($id){
        $query = "SELECT sort_order FROM products WHERE id=:id";
        $result = $this->connection->prepare($query);
        $result->bindParam('id',$id);
        $result->execute();
        $sort_order = $result->fetch();
        return $sort_order['sort_order'];
    }

    private function getIdbySortOrder($sort_order){
        $query = "SELECT id FROM products WHERE sort_order=:sort_order";
        $result = $this->connection->prepare($query);
        $result->bindParam('sort_order',$sort_order);
        $result->execute();
        $id = $result->fetch();
        return $id['id'];
    }

    private function changeOrder($id, $order){
        $query = "UPDATE products SET sort_order=:sort_order WHERE id=:id";
        $result = $this->connection->prepare($query);
        $result->bindParam('sort_order', $order);
        $result->bindParam('id', $id);
        $result->execute();
    }
}