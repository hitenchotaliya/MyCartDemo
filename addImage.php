<?php
include './php-files/config.php';

$obj = new Database();

$uploadErrors = array();

$pid = $_POST['id'];

if (isset($_FILES['doc']) && !empty($_FILES['doc']['name'][0])) {

    $uploadedFiles = $obj->uploadFiles($_FILES['doc'], 'upload');

    $uploadErrors = $obj->getResult();

    if (empty($uploadErrors)) {

        foreach ($uploadedFiles as $imagePath) {

            $verifyImage = $obj->insert(
                "product_images",
                [
                    "product_id" => $pid,
                    "image_path" => $imagePath
                ]
            );

            if (!$verifyImage) {
                $uploadErrors[] = "Failed to insert image into product_images table.";
            }
        }

        header("location: ImageManage.php?id=$pid");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $pid; ?>">
        Images: <input type="file" name="doc[]" multiple />
        <?php if (!empty($uploadErrors)) : ?>
            <ul>
                <?php foreach ($uploadErrors as $error) : ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <button type="submit" name="submit">Submit</button>
    </form>
</body>

</html>