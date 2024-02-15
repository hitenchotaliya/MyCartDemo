<?php

include './header.php';
include './php-files/config.php';
$obj = new Database();
if (isset($_POST['submit'])) {
    $title = $_POST['categoryname']; // Corrected name
    $active = $_POST['is_active'];
    $categoryId = $_POST['parentCategoryID'];
    if (empty($categoryId)) {
        $success =  $obj->insert(
            "categories",
            [
                "title" => $title, // Corrected variable
                "is_active" => $active
            ]
        );
        if ($success) {
            header("location: " . $cat);
        }
    } else {


        $success =  $obj->insert(
            "categories",
            [
                "parent_category_id" => $categoryId,
                "title" => $title, // Corrected variable
                "is_active" => $active
            ]
        );
        if ($success) {
            header("location: " . $cat);
        }
    }
}

// echo "<pre>";
// print_r($obj->getResult());

$obj->select("categories");
$Categories = $obj->getResult();
//Testing
// echo "<pre>";
// print_r($Categories[0]["title"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <div class="main">
        <form action="" method="POST" class="custom-form">

            CategoryName: <input type="text" name="categoryname" required><br><br>
            is_active : <select name="is_active" id="" required>
                <option value="1">Active</option>
                <option value="0">InActive</option>
            </select><br><br>
            <select name="parentCategoryID" id="">
                <option value="">Select Parent Category</option> <!-- Add this option for null parent category -->
                <?php
                foreach ($Categories as $category) {
                    $categoryId = $category["category_id"];
                    $categoryName = $category["title"];
                    echo "<option value='$categoryId'>$categoryName</option>";
                }
                ?>
            </select>
            <br><br>
            <button type="submit" name="submit">Submit</button>

        </form>
    </div>
</body>

</html>