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
    <style>
        form {
            width: 50%;
            /* Adjust the width as needed */
            margin: auto;
            /* Center the form horizontally */
            margin-bottom: 20px;
        }

        form input[type="text"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form select {
            height: 40px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <!-- <div class="main"> -->

    <div class="main">


        <form action="" method="POST">

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
    <!-- </div> -->
</body>

</html>