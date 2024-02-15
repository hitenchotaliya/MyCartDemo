<?php
include './header.php';
include './php-files/config.php';

$obj = new Database();

$ImageID = $_POST['image_id'];
$pid = $_POST['product_id'];
$uploadErrors = array();

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
            $obj->update(
                "product_images",
                ["is_primary" => $primary],
                "image_id = $ImageID"
            );
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
            <form class="custom-form" action="" method="post" enctype="multipart/form-data">
                <div class="form-input">
                    <label for="image">Image:</label>
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