<?php
include './config.php';

$searchVal = $_POST['search'];

if ($searchVal) {
}
if ($_GET['search'] == '') {
    header("location:" . $baseurl);
} else {

    $obj = new Database();
    $search = $obj->escapeString($_GET['search']);
    $obj->sql("SELECT * FROM categories WHERE `title` LIKE '%$search%' ");
    $result = $obj->getResult();

    if (empty($result)) {
        echo "No records found for the search query: $search";
    } else {
        // Display your search results here
        // echo "<pre>";
        // print_r($result);
    }
}
