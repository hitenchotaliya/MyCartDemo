<?php

include './header.php';
include './php-files/config.php';
include './class/product.php';

$obj = new Database();
$productClass = new product($obj);

$setLimit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'a-z') {
        $orderby = " title ASC";
    } else if ($_GET['sort'] === 'z-a') {
        $orderby = " title DESC";
    }
}
if (isset($_GET['categoryID'])) {
    $SortID = $_GET['categoryID'];
}

if (isset($_GET['search'])) {
    $svalue = $_GET['search'];
}


// $offset = ($page - 1) * $setLimit;
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $result = $productClass->searchProduct($_GET['search']);
} else if (isset($orderby)) {
    $result = $productClass->orderByProducts($orderby, $setLimit);
} else if (isset($SortID)) {
    $result = $productClass->sortProduct($SortID, $setLimit);
} else {
    $result = $productClass->getAllProducts($page, $setLimit);
}




function ShowProduct($result)
{

    if (empty($result)) {
        return '<tr><td colspan="11">No records found</td></tr>';
    }

    $html = '';
    $rowNumber = 1;

    if (isset($_GET['page']) && $_GET['page'] > 1) {
        $rowNumber = ($_GET['page'] - 1) * 5 + 1;
    }
    foreach ($result as $row) {
        $productId = $row['product_id'];
        $isActive = ($row['is_active'] == 1) ? 'Active' : 'Inactive';
        $imagePath = ''; // Initialize image path variable

        // Check if the row has a primary image
        if (!empty($row['image_path'])) {
            $imagePath = $row['image_path']; // Assign image path
        }


        $html .= '<tr>';
        $html .= '<td><input type="checkbox" name="checked_id[]" class="checkbox" value="' . $productId . '" /></td>';
        $html .= '<td>' . $rowNumber++ . '</td>';
        // $html .= '<td>' . $productId . '</td>';
        $html .= '<td>' . $row['title'] . '</td>';
        $html .= '<td>' . $row['category_title'] . '</td>';
        // $html .= '<td>' . $row['description'] . '</td>';
        $html .= '<td>' . $isActive . '</td>';
        $html .= '<td>';
        // Display the primary image for the product
        if (!empty($imagePath)) {
            $html .= '<img src="' . $imagePath  . '" alt="Product Image" style="max-width: 50px;">';
        } else {
            $html .= 'No primary image';
        }
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form method="POST" action="NewUpdate.php">';
        $html .= '<input type="hidden" name="id" value="' . $productId . '">';
        // $html .= '<input type="submit" value="Update">';
        $html .= '<button type="submit" class="update-btn"><i class="fa-regular fa-pen-to-square"></i></button>';

        $html .= '</form>';
        $html .= '</td>';
        $html .= '<td>';
        // $html .= '<form method="POST" action="php-files/delete.php">';
        // $html .= '<input type="hidden" name="product_id" value="' . $productId . '">';
        // $html .= '<input class="deleted_confirm" type="submit" value="Delete">';
        // $html .= '</form>';
        $html .= '<button class="deleted_confirm delete-btn" data-product_id="' . $productId . '">
        <i class="fa fa-trash"></i>
    </button>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form class="record-custom-form" method="POST" action="ImageManage.php">';
        $html .= '<input type="hidden" name="id" value="' . $productId . '">';
        $html .= '<input type="submit" value="Images">';
        $html .= '</form>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form class="record-custom-form" method="POST" action="productDetail.php">';
        $html .= '<input type="hidden" name="id" value="' . $productId . '">';
        $html .= '<input type="submit" value="Detail">';
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
    <link rel="stylesheet" href="./css/table-sort.css">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

</head>

<body>
    <div class="main">
        <h1>Product</h1>


        <div class="col-md-7 search">
            <form action="product.php" method="GET">
                <div class="input-group">
                    <input type="text" id="liveSearch" name="search" placeholder="Search for..." value="<?php if (isset($svalue)) {
                                                                                                            echo $svalue;
                                                                                                        } ?>">
                    <span class="input-group-btn">
                        <input type="submit" value="Search" class="btn btn-primary" />
                    </span>
                    <div id="searchResults"></div>
                </div>
            </form>
            <div class="error-message" style="color: red;"></div>
        </div><br>

        <table class="table-sortable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all" value="" /></th>
                    <th>Index</th>
                    <!-- <th>Product ID</th> -->
                    <th>Title</th>
                    <th>Category title</th>
                    <!-- <th>Product Description</th> -->
                    <th>Status</th>
                    <th>Image</th>
                    <th>Update</th>
                    <th>Delete</th>
                    <th>Actions</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php echo ShowProduct($result); ?>
            </tbody>
        </table>

        <div class="status">
            <!-- <form name="bulk_action_form" action="./php-files/multi-delete.php" method="POST" onSubmit="return delete_confirm('products');">
                <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete" />
            </form> --> <br>
            <button type="button" class="btn delete-btn" onclick="delete_confirm('products')"><i class="fa fa-trash"></i></button> &nbsp;


            <!-- <form name="bulk_edit_form" action="./php-files/multi-active.php" method="POST" onSubmit="return status_confirm('products');"> -->
            <label>Status: </label>
            <select id="action" name="action">
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
            </select>
            <input type="submit" class="btn btn-danger" name="bulk_edit_submit" value="Apply" onclick="status_confirm('products')" />
            <!-- </form> -->
        </div>
        <div class="sort">
            <form action="" method="GET">
                <label for="sort">Sort:</label>
                <select name="sort" id="sort">
                    <option value="">Select Option</option>
                    <option value="a-z" <?php if (isset($_GET['sort']) && $_GET['sort'] == "a-z") {
                                            echo "selected";
                                        } ?>>A-Z</option>
                    <!-- SELECT * FROM categories ORDER BY title ASC; -->
                    <option value="z-a" <?php if (isset($_GET['sort']) && $_GET['sort'] == "z-a") {
                                            echo "selected";
                                        } ?>>Z-A</option>
                    <!-- SELECT * FROM categories ORDER BY title DESC; -->
                </select>
                <input type="submit" value="Submit">
            </form>

            <form action="" method="GET">
                <label for="sort">Sort by category:</label>
                <select name="categoryID" id="sort">
                    <?php
                    $obj->select("categories");
                    $r = $obj->getResult();
                    foreach ($r as $category) {
                        $categoryId = $category["category_id"];
                        $categoryName = $category["title"];
                        echo "<option value='$categoryId'>$categoryName</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Submit">
            </form>
            <button onclick="window.location.href='<?php echo $product; ?>'"><i class="fa-solid fa-broom"></i></button>
        </div>

        <div class="pagination">
            <?php
            echo $obj->Pagination("products", null, null, $setLimit);
            ?>
        </div>
        <div class="error">
            <?php
            if (isset($_GET['error_message'])) {
                echo "You can not delete parent record directly " . $_GET['error_message'];
            }
            ?>
        </div>
    </div>

    <script src="./js/table-sort.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>