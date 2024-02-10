<?php
include './header.php';
include './php-files/config.php';


$obj = new Database();
$pid = null;

if (isset($_POST['id'])) {
    $pid = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $pid = $_GET['id'];
}

$obj->select("product_images", "*", null, "product_id = $pid");
$images = $obj->getResult();
// echo "<pre>";
// print_r($obj->getResult());

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Main container */
        .main {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            /* Align items to the top */
        }

        /* Product image container */
        .image-container {
            margin-right: 20px;
            margin-bottom: 20px;
            text-align: center;
            /* Center align images and buttons */
        }

        /* Product image styles */
        .product-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            /* Maintain aspect ratio */
            display: block;
            margin-bottom: 10px;
            /* Space between image and buttons */
        }

        /* Button styles */
        .button-container button {
            padding: 5px 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Delete button styles */
        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        /* Update button styles */
        .update-btn {
            background-color: #4caf50;
            color: white;
        }

        /* Add images button styles */
        .add-images-btn {
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            margin-bottom: 20px;
            /* Add margin at the bottom */
        }

        /* Back button styles */
        .back-btn {
            background-color: #999;
            color: white;
            margin-bottom: 20px;
            /* Add margin at the bottom */
        }

        /* Image upload form styles */
        .upload-form {
            margin-top: 20px;
        }

        /* Fixed position for buttons */
        .fixed-buttons {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            z-index: 999;
            /* Ensure buttons are above other content */
        }
    </style>
</head>

<body>
    <div class="main">
        <?php
        $count = 0;
        if ($images) {
            foreach ($images as $image) {
                $imagePath = $image['image_path'];
                $imageId = $image['image_id'];
        ?>
                <div class="image-container">
                    <img src="<?php echo $imagePath ?>" alt="Product Image" class="product-image">
                    <div class="button-container">
                        <form action="php-files/delete.php" method="post">
                            <input type="hidden" name="image_id" value="<?php echo $imageId ?>">
                            <input type="hidden" name="product_id" value="<?php echo $pid ?>">
                            <button class="delete-btn" class="delete_confirm" type="submit" name="delete">Delete</button>
                        </form>
                        <form action="update_image.php" method="post">
                            <input type="hidden" name="image_id" value="<?php echo $imageId ?>">
                            <input type="hidden" name="product_id" value="<?php echo $pid ?>">
                            <button class="update-btn" type="submit" name="update">Update</button>
                        </form>
                    </div>
                </div>
        <?php
                $count++;
                // Break to new line after 5 images
                if ($count % 5 == 0) {
                    echo '<br>'; // Add line break
                }
            }
        } else {
            echo "Images not Found";
        }
        ?>
    </div>

    <div class="fixed-buttons">
        <form action="addImage.php" method="post" class="upload-form">
            <input type="hidden" name="id" value="<?php echo $pid ?>">
            <input type="submit" value="Add images" class="add-images-btn">
        </form>

        <!-- Back button -->
        <button class="back-btn"><a href="product.php">Back</a></button>
    </div>
</body>

</html>