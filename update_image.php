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
    <style>
        /* Main container */

        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        /* Form container */
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Form inputs */
        .form-input {
            margin-bottom: 10px;
        }

        /* File input */
        .file-input {
            display: block;
            margin-bottom: 10px;
        }

        /* Select input */
        .select-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Submit button */
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Back button */
        .back-btn {
            background-color: #ccc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        /* Error message */
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="main">

        <div class="form-container">
            <form action="" method="post" enctype="multipart/form-data">
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
            </form>

            <?php if (!empty($uploadErrors)) : ?>
                <ul>
                    <?php foreach ($uploadErrors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <button class="back-btn"><a href="ImageManage.php?id=<?php echo $pid; ?>">Back</a></button>
        </div>
    </div>
</body>

</html>