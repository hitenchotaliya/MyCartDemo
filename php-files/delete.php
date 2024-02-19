<?php
include 'config.php';

$obj = new Database();

$id = isset($_POST['id']) ? $_POST['id'] : null;
$productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
$imageId = isset($_POST['image_id']) ? $_POST['image_id'] : null;

if (isset($id)) {
    // Check if there are any sub-categories associated with this category
    $obj->select('categories', '*', null, "parent_category_id = '$id'", null, 0);
    $subCategories = $obj->getResult();

    // Check if there are any products associated with this category
    $obj->select('products', '*', null, "category_id = '$id'", null, 0);
    $products = $obj->getResult();

    // Initialize an array to store the names of related child records
    $relatedRecords = array();

    // Check if there are any related sub-categories or products
    if (!empty($subCategories)) {
        foreach ($subCategories as $subCategory) {
            $relatedRecords[] = $subCategory['title'];
        }
    }

    if (!empty($products)) {
        foreach ($products as $product) {
            $relatedRecords[] = $product['title'];
        }
    }

    if (!empty($relatedRecords)) {
        // If related records exist, return an error message with the names of related records
        $relatedRecordsNames = implode(", ", $relatedRecords);
        echo json_encode(array("error" => "You can't delete this category because it has related child records ($relatedRecordsNames). Please delete the related sub-categories and products first."));
    } else {
        // If no related records exist, proceed with deleting the category
        if ($obj->delete("categories", "category_id=$id")) {
            echo json_encode(array("success" => "Category deleted successfully."));
        } else {
            echo json_encode(array("error" => "Error deleting category."));
        }
    }
} else if (isset($imageId)) {
    if ($obj->delete("product_images", "image_id = $imageId")) {
        echo json_encode(array("success" => "Category deleted successfully."));
    } else {
        echo json_encode(array("error" => "Error deleting category."));
    }
} elseif (isset($productId)) {
    // Check if there are any product images associated with this product
    $obj->select('product_images', '*', null, "product_id = '$productId'", null, 0);
    $productImages = $obj->getResult();

    // Check if there are any related product images
    if (!empty($productImages)) {
        // If related records exist, return an error message
        $imageCount = count($productImages);
        echo json_encode(array("error" => "You can't delete this product because it has $imageCount associated product images. Please delete the product images first."));
    } else {
        // If no related records exist, proceed with deleting the product
        if ($obj->delete("products", "product_id=$productId")) {
            echo json_encode(array("success" => "Product deleted successfully."));
        } else {
            echo json_encode(array("error" => "Error deleting product."));
        }
    }
} else {
    echo json_encode(array("error" => "No valid action specified."));
}
