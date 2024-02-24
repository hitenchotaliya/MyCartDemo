<?php
include './database.php';

$obj = new Database();
$search_value = isset($_POST['search']) ? $_POST['search'] : '';
$table = isset($_POST['source']) ? $_POST['source'] : '';
$response = array();

if (!empty($search_value)) {
    $obj->search($table, "title", null, "title LIKE '%$search_value%'");
    $response = $obj->getResult();

    // $response['success'] = true;
    // $response['data'] = "Search term received: " . $search_value;
} else {
    $response['success'] = false;
    $response['error'] = "No search value provided.";
}


// Encode the response array to JSON format and output it
echo json_encode($response);
