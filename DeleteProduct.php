<?php

include './php-files/config.php';

$obj = new Database();
$id = $_POST['id'];


$delete = $obj->delete("products", "product_id=$id");

if ($delete) {
    header("location: " . $product);
} else {
    print_r($obj->getResult());
}
