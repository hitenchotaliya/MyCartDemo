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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>

<body>
    <div class="main">
        <div class="main-image">
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
                            <!-- <form action="php-files/delete.php" method="post">
                                <input type="hidden" name="image_id" value="<?php echo $imageId ?>">
                                <input type="hidden" name="product_id" value="<?php echo $pid ?>">
                                <button class="delete-btn deleted_confirm" type="submit" name="delete">Delete</button>
                            </form> -->

                            <form action="update_image.php" method="post">
                                <input type="hidden" name="image_id" value="<?php echo $imageId ?>">
                                <input type="hidden" name="product_id" value="<?php echo $pid ?>">
                                <!-- <button class="update-btn" type="submit" name="update">Update</button> -->
                                <button type="submit" name="update" class="update-btn"><i class="fa-regular fa-pen-to-square"></i></button>
                            </form>

                            <button class="deleted_confirm delete-btn" data-image_id="<?php echo $imageId; ?>">
                                <i class="fa fa-trash"></i>
                            </button>
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
            <div class="button-group">
                <form action="addImage.php" method="post" class="upload-form">
                    <input type="hidden" name="id" value="<?php echo $pid ?>">
                    <input type="submit" value="Add images" class="add-images-btn">
                </form>

                <button class="back-btn"><a href="product.php">Back</a></button>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>

</html>