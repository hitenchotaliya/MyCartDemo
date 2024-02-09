<?php

include './header.php';
include './php-files/config.php';

$obj = new Database();

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



// $offset = ($page - 1) * $setLimit;
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $obj->escapeString($_GET['search']);
    $obj->search(
        "products",
        "products.product_id, products.title, products.description, products.is_active, products.category_id, c.title AS category_title",
        "categories AS c ON products.category_id = c.category_id",
        null,
        null,
        null,
        $search,
        "c.title"
    );

    $result = $obj->getResult();
    $search = $obj->escapeString($_GET['search']);
    $obj->search(
        "products",
        "products.product_id, products.title, products.description, products.is_active, products.category_id, c.title AS category_title",
        "categories AS c ON products.category_id = c.category_id",
        "CONCAT(products.title, ' ', products.description, ' ', c.title) LIKE '%$search%'",
        null,
        null,
        null
    );

    $result = $obj->getResult();
} else if (isset($orderby)) {
    $obj->select(
        "products",
        "products.product_id, products.title, products.description, products.is_active, products.category_id, categories.title AS category_title, product_images.image_path,product_images.is_primary",
        "categories LEFT JOIN product_images ON products.product_id = product_images.product_id",
        "categories.category_id = products.category_id",
        $orderby,
        $setLimit,
    );
    $result = $obj->getResult();
} else if (isset($SortID)) {
    $obj->select(
        "products",
        "products.product_id, products.title, products.description, products.is_active, products.category_id, categories.title AS category_title, product_images.image_path, product_images.is_primary",
        "categories LEFT JOIN product_images ON products.product_id = product_images.product_id",
        "categories.category_id = $SortID AND categories.category_id = products.category_id",
        null,
        $setLimit
    );
    $result = $obj->getResult();
} else {
    $obj->select(
        "products",
        "DISTINCT products.product_id, products.title, products.description, products.is_active, products.category_id, categories.title AS category_title, product_images.image_path, product_images.is_primary",
        "categories LEFT JOIN product_images ON products.product_id = product_images.product_id AND product_images.is_primary = 1", // Consider only primary images
        "categories.category_id = products.category_id",
        null,
        $setLimit,
    );
    $result = $obj->getResult();
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
        $html .= '<td>' . $productId . '</td>';
        $html .= '<td>' . $row['title'] . '</td>';
        $html .= '<td>' . $row['category_title'] . '</td>';
        $html .= '<td>' . $row['description'] . '</td>';
        $html .= '<td>' . $isActive . '</td>';
        $html .= '<td>';
        // Display the primary image for the product
        if (!empty($imagePath)) {
            $html .= '<img src="' . $imagePath . '" alt="Product Image" style="max-width: 50px;">';
        }
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form method="POST" action="NewUpdate.php">';
        $html .= '<input type="hidden" name="id" value="' . $productId . '">';
        $html .= '<input type="submit" value="Update">';
        $html .= '</form>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form method="POST" action="php-files/delete.php">';
        $html .= '<input type="hidden" name="product_id" value="' . $productId . '">';
        $html .= '<input class="delete_confirm" type="submit" value="Delete">';
        $html .= '</form>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<form method="POST" action="ImageManage.php">';
        $html .= '<input type="hidden" name="id" value="' . $productId . '">';
        $html .= '<input type="submit" value="Click">';
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
    <script src="./js/js.js"></script>

</head>

<body>
    <div class="main">
        <h1>Product</h1>


        <div class="col-md-7">
            <form action="product.php" method="GET">
                <div class="input-group search">
                    <input type="text" name="search" placeholder="Search for...">
                    <span>
                        <input type="submit" value="Search" />
                    </span>
                </div>
            </form>
        </div>
        <table border="1">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all" value="" /></th>
                    <th>Index</th>
                    <th>Product ID</th>
                    <th>Title</th>
                    <th>Category title</th>
                    <th>Product Description</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Update</th>
                    <th>Delete</th>
                    <th>Manage Image</th>
                </tr>
            </thead>
            <tbody>
                <?php echo ShowProduct($result); ?>
            </tbody>
        </table>

        <div class="status">
            <form name="bulk_action_form" action="./php-files/multi-delete.php" method="POST" onSubmit="return delete_confirm('products');">
                <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete" />
            </form>

            <form name="bulk_edit_form" action="./php-files/multi-active.php" method="POST" onSubmit="return status_confirm('products');">
                <label>Status: </label>
                <select id="action" name="action">
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                </select>
                <input type="submit" class="btn btn-danger" name="bulk_edit_submit" value="Apply" />
            </form>
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


            <button onclick="window.location.href='<?php echo $product; ?>'">Clear</button>

        </div>

        <div class="pagination">
            <?php
            echo $obj->Pagination("products", null, null, $setLimit);
            ?>
        </div>
    </div>

    <script>
        function status_confirm(tableName) {
            // Collect all the selected checkboxes
            var selectedIds = [];
            $('.checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // Check if any checkboxes are selected
            if (selectedIds.length > 0) {
                // Append the selected IDs and table name to the form data
                $('form[name="bulk_edit_form"]').append('<input type="hidden" name="checked_id" value="' + selectedIds.join(',') + '">');
                $('form[name="bulk_edit_form"]').append('<input type="hidden" name="table_name" value="' + tableName + '">');

                // Ask for confirmation
                var result = confirm("Are you sure to change status of selected items?");
                if (result) {
                    return true; // Proceed with the action
                } else {
                    return false; // Cancel the action
                }
            } else {
                // No checkboxes selected, show an alert
                alert('Select at least 1 record to change status.');
                return false; // Cancel the action
            }
        }

        function delete_confirm(tableName) {
            // Collect all the selected checkboxes
            var selectedIds = [];
            $('.checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length > 0) {
                // Append the selected IDs and table name to the form data
                $('form[name="bulk_action_form"]').append('<input type="hidden" name="checked_id" value="' + selectedIds.join(',') + '">');
                $('form[name="bulk_action_form"]').append('<input type="hidden" name="table_name" value="' + tableName + '">');

                // Ask for confirmation
                var result = confirm("Are you sure you want to delete the selected item(s)?");
                if (result) {
                    return true; // Proceed with the action
                } else {
                    return false; // Cancel the action
                }
            } else {
                alert('Select at least 1 record to delete.');
                return false;
            }
        }
    </script>
</body>

</html>