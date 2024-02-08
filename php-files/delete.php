<?php
include 'config.php';

$obj = new Database();

$id = $_POST['id'];
if (isset($id)) {
    if ($obj->delete("categories", "category_id=$id")) {
        header("location: " . $cat);
    } else {
        echo "<pre>";
        print_r($obj->getResult());
    }
}
