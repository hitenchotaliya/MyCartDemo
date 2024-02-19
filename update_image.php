<?php
include './header.php';
include './php-files/config.php';

$obj = new Database();

$ImageID = $_POST['image_id'];
$pid = $_POST['product_id'];
$uploadErrors = array();

$obj->select("product_images", "image_path", null, "image_id= $ImageID");
$ImagePathArray =  $obj->getResult();
$imagePth = $ImagePathArray[0]['image_path'];

if (isset($_POST['submit'])) {
    if (isset($_FILES['imageurl']) && !empty($_FILES['imageurl']['name'])) {


        $primary = $_POST['is_primary'];
        $uploadedFiles = $obj->uploadFiles($_FILES['imageurl'], 'upload');

        if (is_array($uploadedFiles) && !empty($uploadedFiles)) {
            foreach ($uploadedFiles as $imagePath) {
                $verifyImage = $obj->update(
                    "product_images",
                    ["image_path" => $imagePath, "is_primary" => $primary],
                    "image_id = $ImageID"
                );

                if (!$verifyImage) {
                    $uploadErrors[] = "Failed to update image in product_images table.";
                } else {
                    $absolutePath = "http://localhost/MyCart/ImageManage.php?id=$pid";
                    // echo $absolutePath;
                    header("Location: $absolutePath");
                }
            }
        } else {
            $obj->select("product_images", "product_id", null, "image_id=$ImageID");
            $result = $obj->getResult();
            $resultProductId =  $result[0]['product_id'];

            $obj->select("product_images", "*", null, "product_id=$resultProductId");
            $resultProduct = $obj->getResult();

            // Count the total number of images for the product
            $totalImages = count($resultProduct);

            // Check if there are any images for the product
            if ($totalImages > 0) {
                // Iterate through each image to update is_primary
                foreach ($resultProduct as $image) {
                    // Check if the current image is the one being set as primary
                    if ($image['image_id'] == $ImageID) {
                        $obj->update("product_images", ["is_primary" => 1], "image_id = {$image['image_id']}");
                    } else {
                        // Set is_primary to 0 for all other images
                        $obj->update("product_images", ["is_primary" => 0], "image_id = {$image['image_id']}");
                    }
                }
            }

            $absolutePath = "http://localhost/MyCart/ImageManage.php?id=$pid";
            // echo $absolutePath;
            header("Location: $absolutePath");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Image</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="main">

        <div class="form-container">
            <div class="image-container-display">
                <div id="imageDisplay">
                    <img src="<?php echo $imagePth; ?>" alt="Image Not Found">
                </div>
            </div>
            <form class="custom-form" action="" method="post" enctype="multipart/form-data">
                <div class="form-input">
                    <br> <label for="image">Image:</label>
                    <input type="hidden" name="image_id" value="<?php echo $ImageID; ?>">
                    <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                    <input type="file" name="imageurl[]" id="image" class="file-input" />
                </div>
                <div class="form-input">
                    <label for="is_primary">Select Primary:</label>
                    <select name="is_primary" id="is_primary" class="select-input">
                        <option value="">None</option>
                        <option value="1">Primary</option>
                        <option value="0">Non-Primary</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="submit-btn">Submit</button>
                <button class="back-btn" style="margin-top: 10px; background-color: #3F72AF;"><a href="ImageManage.php?id=<?php echo $pid; ?>">Back</a></button>
            </form>

            <?php if (!empty($uploadErrors)) : ?>
                <ul>
                    <?php foreach ($uploadErrors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>