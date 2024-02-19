<?php
include '../php-files/database.php';

$obj = new Database();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    GetChild($id, $obj);
} else {
    echo "Please select a category.";
}

function GetChild($parentId, $obj)
{
    $obj->select("categories", "category_id,title", null, "parent_category_id = $parentId");

    $result  = $obj->getResult();
    if (!empty($result)) {
        echo "<ul>";
        foreach ($result as $category) {
            echo "<li>{$category['title']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<li>Add your product</li>";
    }
}
