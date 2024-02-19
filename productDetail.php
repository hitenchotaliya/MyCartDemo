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
            justify-content: space-evenly;
            align-items: center;
        }

        .productContent {
            margin-left: 0;
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

        #slider-container {
            width: 200px;
            height: auto;
            overflow: hidden;
            position: relative;
            margin-left: 0;
            margin-right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Center items horizontally */
        }

        #slider-images {
            display: flex;
            align-items: center;
            /* Center images vertically */
        }

        .slide {
            flex: 0 0 100%;
            display: flex;
            justify-content: center;
            /* Center image horizontally */
            align-items: center;
            /* Center image vertically */
        }

        .slide img {
            max-width: 100%;
            /* Ensure images don't exceed slide width */
            max-height: 100%;
            /* Ensure images don't exceed slide height */
        }

        #prev,
        #next {
            position: absolute;
            font-size: 24px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.5);
            padding: 10px;
            border-radius: 50%;
            z-index: 1;
            /* Ensure buttons are above images */
        }

        #prev {
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        #next {
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .dot {
            width: 10px;
            height: 10px;
            background-color: grey;
            border-radius: 50%;
            display: inline-block;
            margin: 0 5px;
        }

        .dot.active {
            background-color: black;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="detailmain">
            <div id="slider-container">
                <div id="slider-images">
                    <?php foreach ($imagePaths as $index => $imagePath) : ?>
                        <div class="slide"><img src="<?php echo $imagePath; ?>" alt="Product Image"></div>
                    <?php endforeach; ?>
                </div>
                <div id="prev">&#10094;</div>
                <div id="next">&#10095;</div>
                <div class="dots">
                    <?php foreach ($imagePaths as $index => $imagePath) : ?>
                        <span class="dot<?php echo ($index === 0) ? ' active' : ''; ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="productContent">
                <h2 class="title"><label for="title">Title:</label><?php echo $title; ?></h2>
                <p class="description"><label for="Description">Description:</label><?php echo $description; ?></p>
                <p class="is_active">Is Active: Yes</p>
                <p class="category"><label for="Category">Category:</label><?php echo $category; ?></p>
                <div class="sort">
                    <form action="product.php">
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const $sliderImages = $('#slider-images');
            const $slides = $('.slide');
            const $dots = $('.dot');
            const $prevBtn = $('#prev');
            const $nextBtn = $('#next');
            let currentIndex = 0;

            function goToSlide(index) {
                $sliderImages.css('transform', `translateX(-${index * 100}%)`);
                $dots.removeClass('active');
                $dots.eq(index).addClass('active');
            }

            function prevSlide() {
                currentIndex = (currentIndex === 0) ? $slides.length - 1 : currentIndex - 1;
                goToSlide(currentIndex);
            }

            function nextSlide() {
                currentIndex = (currentIndex === $slides.length - 1) ? 0 : currentIndex + 1;
                goToSlide(currentIndex);
            }

            $prevBtn.click(prevSlide);
            $nextBtn.click(nextSlide);

            $dots.each(function(index) {
                $(this).click(function() {
                    currentIndex = index;
                    goToSlide(currentIndex);
                });
            });
        });
    </script>
</body>

</html>