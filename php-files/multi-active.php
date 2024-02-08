<?php
include 'config.php';
$obj = new Database();

if (isset($_POST['bulk_edit_submit'])) {


    // If id array is not empty 
    if (!empty($_POST['checked_id'])) {

        //Store id in variable
        $idStr = $_POST['checked_id'];

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

        //Pass data to function
        $update = $obj->update(
            "categories",
            [
                "is_active" => $isActive,
            ],
            "category_id",
            $idStr
        );

        if ($update) {
            header("location: " . $cat . "?updated=updated");
        }
        // // echo '<pre>';
        // // print_r($_POST);
        // // echo '</pre>';
        // $isActive = $_POST['action'];
        // // echo $isActive;
        // // echo "<br>";

        // if ($isActive === 'activate') {
        //     // echo "true";
        //     $isActive = 1;
        // } else {
        //     // echo "false";
        //     $isActive = 0;
        // }

        // // Get all selected IDs
        // // $selectedIds = explode(',', $_POST['checked_id']);
        // $idStr = implode(',', $_POST['checked_id']);

        // // Print out t  he IDs for demonstration
        // // echo "Selected IDs: ";
        // // foreach ($selectedIds as $id) {
        // //     echo $id . " ";
        // // }

        // $obj->update(
        //     "categories",
        //     [
        //         "is_active" => $isActive,
        //     ],
        //     "category_id",
        //     $idStr
        // );
        // echo '<pre>';
        // print_r($obj->getResult());
        // echo '</pre>';
    } else {
        echo "No ID is selected";
    }
}
