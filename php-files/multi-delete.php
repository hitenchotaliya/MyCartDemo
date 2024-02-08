<?php
include 'config.php';
$obj = new Database();

// If record delete request is submitted 
if (isset($_POST['bulk_delete_submit'])) {
    // If id array is not empty 
    if (!empty($_POST['checked_id'])) {
        // Get all selected IDs and convert it to a string 
        // $idStr = implode(',', $_POST['checked_id']);

        $idStr = $_POST['checked_id'];
        $delete =  $obj->delete("categories", "category_id", $idStr);

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
