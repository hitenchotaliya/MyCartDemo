<?php
include 'config.php';

$obj = new Database();

//For Category 
$id = $_POST['id'];

//For productDelete
$productId = $_POST['product_id'];

//For image delete
$imageId = $_POST['image_id'];
$pid = $_POST['product_id'];



if (isset($id)) {
    if ($obj->delete("categories", "category_id=$id")) {
        header("location: " . $cat);
    } else {
        echo "<pre>";
        print_r($obj->getResult());
    }
} else if (isset($imageId)) {
    if ($obj->delete("product_images", "image_id = $imageId")) {

        $absolutePath = "http://localhost/MyCart/ImageManage.php?id=$pid";
        header("Location: $absolutePath");

        exit;
    } else {
        echo "<pre>";
        print_r($obj->getResult());
    }
} else if (isset($productId)) {
    if ($delete = $obj->delete("products", "product_id=$productId"))

        header("location: " . $product);
} else {
    print_r($obj->getResult());
}
