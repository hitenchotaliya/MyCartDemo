<?php
include 'config.php';
$obj = new Database();

// If record delete request is submitted 
if (isset($_POST['bulk_delete_submit'])) {
    // If id array is not empty 
    if (!empty($_POST['checked_id']) && !empty($_POST['table_name'])) {
        // Get all selected IDs and convert it to a string 
        // $idStr = implode(',', $_POST['checked_id']);
        $tablename = $_POST['table_name'];
        $idStr = $_POST['checked_id'];
        if ($tablename == "products") {
            $obj->delete($tablename, "product_id", $idStr);
            header("location: " . $product);
        }
        if ($tablename == "categories") {
            $delete =  $obj->delete($tablename, "category_id", $idStr);
        }

        if ($delete) {
            header("location: " . $cat . "?success=deleted");
            exit;
        } else {
            echo "Error: Failed to delete records.";
            exit;
        }
    } else {
        echo "Error: No records selected for deletion.";
        exit;
    }
}
