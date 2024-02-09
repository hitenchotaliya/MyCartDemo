<?php
include './php-files/config.php';

$obj = new Database();

$ImageID = $_POST['image_id'];
$pid = $_POST['product_id'];
$uploadErrors = array();

if (isset($_POST['submit'])) {
    if (isset($_FILES['imageurl']) && !empty($_FILES['imageurl']['name'])) {

        $uploadedFiles = $obj->uploadFiles($_FILES['imageurl'], 'upload');

        if (is_array($uploadedFiles) && !empty($uploadedFiles)) {
            foreach ($uploadedFiles as $imagePath) {
                $verifyImage = $obj->update(
                    "product_images",
                    ["image_path" => $imagePath],
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
            $uploadErrors[] = "No files uploaded or invalid file format.";
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
</head>

<body>

    <form action="" method="post" enctype="multipart/form-data">
        Image:
        <input type="hidden" name="image_id" value="<?php echo $ImageID; ?>">
        <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
        <input type="file" name="imageurl[]" />
        <button type="submit" name="submit">Submit</button>
    </form>

    <?php if (!empty($uploadErrors)) : ?>
        <ul>
            <?php foreach ($uploadErrors as $error) : ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>

</html>