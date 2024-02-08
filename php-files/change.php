<?php
include './config.php';

$id = $_POST['id'];

if (!empty($id)) {

    $obj = new Database();
    $obj->select("categories", "is_active", null, "category_id = $id", null, null);


    echo "<pre>";
    $resultArray = $obj->getResult(); // Get the result array

    // Check if the array is not empty
    if (!empty($resultArray)) {

        $isActive = $resultArray[0]['is_active'];

        echo "is_active value: " . $isActive;
    } else {
        echo "No result found";
    }

    //Changing value
    if ($isActive == 0) {
        $isActive = 1;
    } else {
        $isActive = 0;
    }

    //Updating value 
    $obj->update(
        "categories",
        [
            "is_active" => "$isActive",
        ],
        "category_id = $id"
    );
    header("location:" . $cat);
}
