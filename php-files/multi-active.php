<?php
include 'config.php';
$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // echo $_POST;
    // If id array is not empty 
    if (!empty($_POST['checkedIds']) && !empty($_POST['tableName'])) {

        //Store id in variable
        $idStr = $_POST['checkedIds'];

        //Get the action
        $isActive = $_POST['action'];


        //Change it to requirement
        if ($isActive === 'activate') {
            // echo "true";
            $isActive = 1;
        } else {
            // echo "false";
            $isActive = 0;
        }

        $tablename = $_POST['tableName'];
        if ($tablename == "categories") {
            $update = $obj->update(
                $tablename,
                [
                    "is_active" => $isActive,
                ],
                "category_id",
                $idStr
            );
            if ($update) {
                echo 1;
            } else {
                echo 0;
            }
        }
        if ($tablename == "products") {
            $update = $obj->update(
                $tablename,
                [
                    "is_active" => $isActive,
                ],
                "product_id",
                $idStr
            );
            if ($update) {
                echo 1;
            } else {
                echo 0;
            }
        }
    } else {
        echo "No ID is selected";
    }
}
