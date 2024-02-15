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
</head>

<body>
    <div class="main">
        <form action="" method="post" enctype="multipart/form-data" class="custom-form">
            Product Name: <input type="text" name="title" required><br><br>
            Product Description: <input type="text" name="description" required><br><br>
            is_active :
            <select name="is_active" id="">
                <option value="1">Active</option>
                <option value="0">InActive</option>
            </select><br><br>
            Category:
            <select name="categoryID" id="categoryID" required>
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
</body>

</html>