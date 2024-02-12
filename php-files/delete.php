<?php
include 'config.php';

$obj = new Database();

// For Category 
$id = isset($_POST['id']) ? $_POST['id'] : null;

// For productDelete
$productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;

// For image delete
$imageId = isset($_POST['image_id']) ? $_POST['image_id'] : null;
$pid = isset($_POST['product_id']) ? $_POST['product_id'] : null;

if (isset($id)) {
    if ($obj->delete("categories", "category_id=$id")) {
        header("location: " . $cat);
    } else {
        $errorMessage = urlencode("Error deleting category with ID: $id");
        header("location: " . $cat . "?error_message=$errorMessage");
    }
} else if (isset($imageId)) {
    if ($obj->delete("product_images", "image_id = $imageId")) {
        $absolutePath = "http://localhost/MyCart/ImageManage.php?id=$pid";
        header("Location: $absolutePath");
        exit;
    } else {
        $errorMessage = urlencode("Error deleting image with ID: $imageId");
        $absolutePath = "http://localhost/MyCart/ImageManage.php?id=$pid&error_message=$errorMessage";
        header("Location: $absolutePath");
    }
} else if (isset($productId)) {
    if ($delete = $obj->delete("products", "product_id=$productId")) {
        header("location: " . $product);
    } else {
        $errorMessage = urlencode("Error deleting product with ID: $productId");
        header("location: " . $product . "?error_message=$errorMessage");
    }
} else {
    $errorMessage = urlencode("No valid action specified");
    header("location: " . $product . "?error_message=$errorMessage");
}
