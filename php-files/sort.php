<?php

include './config.php';
$db = new Database();

// if (!empty($_GET)) {
//     echo "HEllo";
// } else {
//     echo "Hey";
// }
$orderby = " title ASC";

$db->select("categories", "*", null, null, $orderby, null);

echo "<pre>";
print_r($db->getResult());
