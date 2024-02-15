<?php
include 'config.php';
$obj = new Database();

// Check if data is received via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the necessary parameters are set
    if (isset($_POST['checkedIds']) && isset($_POST['tableName'])) {
        // Get the checked IDs and table name from the POST data
        $checkedIds = $_POST['checkedIds'];
        $tableName = $_POST['tableName'];
        // echo $checkedIds;
        // Convert the checked IDs string to an array
        //   $idArray = explode(',', $checkedIds);
        // $idStr = $checkedIds;

        // Perform the delete operation based on the table name
        switch ($tableName) {
            case 'products':
                // Assuming delete() method expects IDs as array
                $result = $obj->delete($tableName, "product_id", $checkedIds);
                break;
            case 'categories':
                // Assuming delete() method expects IDs as array
                $result = $obj->delete($tableName, "category_id", $checkedIds);
                break;
            default:
                // Handle unknown table name
                $response = array("success" => false, "message" => "Unknown table name.");
                echo json_encode($response);
                exit;
        }

        // if the delete operation was successful
        if ($result) {
            //success message
            $response = array("success" => true, "message" => "Records deleted successfully.");
            echo json_encode($response);
            exit;
        } else {
            //error message
            $response = array("success" => false, "message" => "Failed to delete records.");
            echo json_encode($response);
            exit;
        }
    } else {
        //error message for missing parameters
        $response = array("success" => false, "message" => "Required parameters are missing.");
        echo json_encode($response);
        exit;
    }
} else {
    // error message for non-POST requests
    $response = array("success" => false, "message" => "This endpoint only accepts POST requests.");
    echo json_encode($response);
    exit;
}
