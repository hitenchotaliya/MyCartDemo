<?php
include 'config.php';

if (isset($_POST['categoryId'])) {
    $categoryId = $_POST['categoryId'];

    $obj = new Database();
    $obj->select("categories", "*", null, "parent_category_id = $categoryId");
    $subcategories = $obj->getResult();

    echo json_encode($subcategories);
}
