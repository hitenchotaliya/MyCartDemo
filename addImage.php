<?php
include './header.php';
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .preview-image {
            display: inline-block;
            position: relative;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: rgba(255, 255, 255, 0.7);
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            line-height: 1;
            font-size: 14px;
            cursor: pointer;
        }

        form input[type="file"] {
            width: 500px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        form button {
            width: 100px;
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

        .back-btn {
            background-color: #999;
            color: white;
            margin-bottom: 20px;
            /* Add margin at the bottom */
        }
    </style>

</head>

<body>
    <div class="main">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $pid; ?>">
            <label style="font-weight: bold;">Images:</label> <input type="file" name="doc[]" multiple id="file-input" />
            <!-- Container for displaying selected images -->
            <div id="image-preview" style="margin-top: 10px;"></div>
            <?php if (!empty($uploadErrors)) : ?>
                <ul>
                    <?php foreach ($uploadErrors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <button type="submit" name="submit">Submit</button>
        </form>
        <button class="back-btn"><a href="ImageManage.php?id=<?php echo $pid; ?>">Back</a></button>
    </div>
    <script>
        $(document).ready(function() {
            $('#file-input').change(function() {
                $('#image-preview').empty();

                var fileList = this.files;

                for (var i = 0; i < fileList.length; i++) {
                    var file = fileList[i];
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        var img = $('<img>').attr('src', event.target.result)
                            .css({
                                'max-width': '200px',
                                'max-height': '200px',
                                'margin-right': '10px'
                            });

                        // Create remove button
                        var removeBtn = $('<button>').html('&times;')
                            .addClass('remove-btn')
                            .click(function() {
                                // Remove the parent container of the image from the preview
                                $(this).parent().remove();

                                // Find the index of the removed image in the preview
                                var index = $(this).parent().index();

                                // Remove the corresponding file from the file input's files array
                                var input = $('#file-input')[0];
                                if (input.files && input.files[index]) {
                                    var files = Array.from(input.files);
                                    files.splice(index, 1);
                                    input.files = new FileList({
                                        length: files.length,
                                        item: function(i) {
                                            return files[i];
                                        }
                                    });
                                }
                            });

                        // Create div to contain image and remove button
                        var previewContainer = $('<div>').addClass('preview-image')
                            .append(img)
                            .append(removeBtn);

                        $('#image-preview').append(previewContainer);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

</body>

</html>