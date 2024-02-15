<?php
include './config.php';

$id = $_POST['id'];

if (!empty($id)) {

    $obj = new Database();
    $obj->select("categories", "is_active", null, "category_id = $id", null, null);

    $resultArray = $obj->getResult(); // Get the result array

    // Check if the array is not empty
    if (!empty($resultArray)) {
        $isActive = $resultArray[0]['is_active'];
    } else {
        $isActive = null;
    }

    //Changing value
    if ($isActive == 0) {
        $isActive = 1;
    } else {
        $isActive = 0;
    }

    //Updating value 
    $result = $obj->update(
        "categories",
        [
            "is_active" => "$isActive",
        ],
        "category_id = $id"
    );

    if ($result) {
        echo 1;
    } else {
        echo 0;
    }
}
