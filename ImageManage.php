<?php
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./js/js.js"></script>
</head>

<body>

    <?php
    if ($images) {
        foreach ($images as $image) {
            $imagePath = $image['image_path'];
            $imageId = $image['image_id'];
            // Display the image
            echo "<img src='$imagePath' alt='Product Image' style='max-width: 100px;'>";

            // Display delete button for each image
            echo "<form action='php-files/delete.php' method='post'>";
            echo "<input type='hidden' name='image_id' value='$imageId'>";
            echo "<input type='hidden' name='product_id' value='$pid'>";
            echo "<button class='delete_confirm' type='submit' name='delete'>Delete</button>";
            echo "</form>";

            // Display update button for each image
            echo "<form action='update_image.php' method='post'>";
            echo "<input type='hidden' name='image_id' value='$imageId'>";
            echo "<input type='hidden' name='product_id' value='$pid'>";
            echo "<button type='submit' name='update'>Update</button>";
            echo "</form>";
        }
    } else {
        echo "Images not Found";
    }
    ?>

    <form action="addImage.php" method="post">
        <input type="hidden" name="id" value="<?php echo $pid ?>">
        <input type="submit" value="Add images">
    </form>
    <button><a href="product.php">Back</a></button>

</body>

</html>