<?php

include './php-files/config.php';

$obj = new Database();

$setLimit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// $offset = ($page - 1) * $setLimit;

$obj->select(
    "products",
    "products.product_id, products.title, products.description, products.is_active, products.category_id, categories.title AS category_title, product_images.image_path",
    "categories LEFT JOIN product_images ON products.product_id = product_images.product_id",
    "categories.category_id = products.category_id",
    null,
    $setLimit,
);

$result = $obj->getResult();

function ShowProduct($result)
{
    if (empty($result)) {
        return '<tr><td colspan="5">No records found</td></tr>';
    }

    $html = '';
    $rowNumber = 1;

    // Create an associative array to group products by their IDs
    $groupedProducts = [];
    foreach ($result as $r) {
        $productId = $r['product_id'];
        if (!isset($groupedProducts[$productId])) {
            $groupedProducts[$productId] = [
                'product_id' => $productId,
                'title' => $r['title'],
                'category_title' => $r['category_title'],
                'image_paths' => [] // Initialize an array to store image paths for the product
            ];
        }
        // Add image path to the array for the current product
        $groupedProducts[$productId]['image_paths'][] = $r['image_path'];
    }

    // Iterate over the grouped products to generate HTML
    foreach ($groupedProducts as $productId => $product) {
        $html .= '<tr>';
        $html .= '<td><input type="checkbox" name="checked_id[]" class="checkbox" value="' . $productId . '" /></td>';
        $html .= '<td>' . $rowNumber++ . '</td>';
        $html .= '<td>' . $productId . '</td>';
        $html .= '<td>' . $product['title'] . '</td>';
        $html .= '<td>' . $product['category_title'] . '</td>';
        $html .= '<td>';
        // Display images for the current product
        foreach ($product['image_paths'] as $imagePath) {
            $html .= '<img src="' . $imagePath . '" alt="Product Image" style="max-width: 100px;">';
        }
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form method="POST" action="NewUpdate.php">';
        $html .= '<input type="hidden" name="id" value="' . $productId . '">';
        $html .= '<input type="submit" value="Update">';
        $html .= '</form>';
        $html .= '</td>';
        $html .= '</tr>';
    }

    return $html;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>

<body>
    <h1>Product</h1>
    <a href="insertProduct.php">Add</a><br>
    <div class="col-md-7">
        <form action="product.php" method="GET">
            <div class="input-group search">
                <input type="text" name="search" placeholder="Search for...">
                <span>
                    <input type="submit" value="Search" />
                </span>
            </div>
        </form>
    </div><br>
    <div class="main">
        <table border="1">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all" value="" /></th>
                    <th>Index</th>
                    <th>Product ID</th>
                    <th>Title</th>
                    <th>Category title</th>
                    <th>Image</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php echo ShowProduct($result); ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <?php
        echo $obj->Pagination("products", null, null, $setLimit);
        ?>
    </div>

    <script src="./js/js.js"></script>
</body>

</html>