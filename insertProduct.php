<?php
include './header.php';
include './php-files/config.php';

$obj = new Database();

$obj->select("categories");
$Categories = $obj->getResult();
$uploadedFiles = array(); //empty array to store uploaded file paths
$uploadErrors = array(); //empty array to store upload error messages

// Check if files were uploaded
if (isset($_FILES['doc']) && !empty($_FILES['doc']['name'][0])) {
    // Call the uploadFiles method only if files were uploaded
    $uploadedFiles = $obj->uploadFiles($_FILES['doc'], 'upload');

    // Check for upload errors
    $uploadErrors = $obj->getResult();
}

if (isset($_POST['submit'])) {
    // Check if there are upload errors
    if (!empty($uploadErrors)) {
        // Display upload errors
        echo "<pre>";
        print_r($uploadErrors);
    } else {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $active = $_POST['is_active'];
        $subcategoryId = $_POST['subcategoryID'];
        // $categoryId  = $_POST['categoryID'];

        if ($subcategoryId == '') {
            $subcategoryId = $_POST['categoryID'];
        }

        // Insert product details
        $verify = $obj->insert(
            "products",
            [
                "title" => $title,
                "description" => $desc,
                "is_active" => $active,
                "category_id" => $subcategoryId
            ]
        );

        if ($verify) {
            $result = $obj->getResult();
            // print_r($result);
            $product_id = $result[0];
            // echo $product_id;

            // Insert product images if files were uploaded
            foreach ($uploadedFiles as $imagePath) {
                $verifyImage = $obj->insert(
                    "product_images",
                    [
                        "product_id" => $product_id,
                        "image_path" => $imagePath
                    ]
                );
                // Check if image insertion was successful
                if (!$verifyImage) {
                    // Handle error if image insertion fails
                    echo "Failed to insert image into product_images table.\n";
                    print_r($obj->getResult());
                } else {
                    header("location: " . $product);
                }
            }
        } else {
            // Handle error if product insertion fails
            echo "<pre>";
            print_r($obj->getResult());
        }
    }
}

$result = $obj->getResult();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

        /* Style for file input */
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="main">
        <form action="" method="post" enctype="multipart/form-data">
            Product Name: <input type="text" name="title"><br><br>
            Product Description: <input type="text" name="description"><br><br>
            is_active :
            <select name="is_active" id="">
                <option value="1">Active</option>
                <option value="0">InActive</option>
            </select><br><br>
            Category:
            <select name="categoryID" id="categoryID">
                <?php
                foreach ($Categories as $category) {
                    $categoryId = $category["category_id"];
                    $categoryName = $category["title"];
                    echo "<option value='$categoryId'>$categoryName</option>";
                }
                ?>
            </select><br><br>
            Subcategory:
            <select name="subcategoryID" id="subcategoryID">
                <!-- Subcategory options will be populated dynamically -->
            </select><br><br>
            Images: <input type="file" name="doc[]" multiple />
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {

            $('#categoryID').change(function() {
                var categoryId = $(this).val();
                $.ajax({
                    url: './php-files/get_subcategories.php', // PHP script to fetch subcategories
                    type: 'post',
                    data: {
                        categoryId: categoryId
                    },
                    dataType: 'json',
                    success: function(response) {
                        var options = '<option value="">Select Subcategory</option>';
                        for (var i = 0; i < response.length; i++) {
                            options += '<option value="' + response[i].category_id + '">' + response[i].title + '</option>';
                        }
                        $('#subcategoryID').html(options);
                    }
                });
            });
        })
    </script>
</body>

</html>