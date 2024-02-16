<?php
include "./header.php";
include "./php-files/config.php";

$obj = new Database();

$id = $_POST["id"];

$obj->select(
    "products",
    "products.title, products.description,products.is_active ,categories.title AS Category, GROUP_CONCAT(product_images.image_path) AS ImagePaths ",
    "categories ON products.category_id = categories.category_id
    LEFT JOIN 
    product_images ON products.product_id = product_images.product_id",
    "categories.is_active = 1 AND
    products.product_id = $id GROUP BY 
    products.product_id",
    null,
    null
);

$result = ($obj->getResult());

// echo "<pre>";
// print_r($result);

$title = $result[0]['title'];
$category = $result[0]['Category'];
$description = $result[0]['description'];

$imagePathsString = $result[0]['ImagePaths'];
$imagePaths = explode(',', $imagePathsString);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .detailmain {
            display: flex;
            justify-content: space-between;
        }

        .sideimage {
            flex: 0 0 30%;
            max-width: 30%;
        }

        .sideimage .image-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 10px;
        }

        .sideimage img {
            width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .productContent {
            flex: 0 0 65%;
            max-width: 65%;
        }

        .productContent h2.title {
            font-size: 24px;
            margin-top: 0;
        }

        .productContent p {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="detailmain">

            <div class="sideimage">
                <div class="image-container">
                    <?php foreach ($imagePaths as $imagePath) : ?>
                        <img src="<?php echo $imagePath; ?>" alt="Product Image">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="productContent">
                <h2 class="title"><label for="title">Title:</label><?php echo $title; ?></h2>
                <p class="description"><label for="Description">Description:</label><?php echo $description; ?></p>
                <p class="is_active">Is Active: Yes</p>
                <p class="category"><label for="Category">Category:</label><?php echo $category; ?></p>
                <div class="sort">

                    <form action="product.php" method="POST">
                        <button>Back</button>
                    </form>

                    <form method="POST" action="NewUpdate.php">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <form action="ImageManage.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit">Images</button>
                    </form>

                </div>
            </div>

        </div>

</body>

</html>