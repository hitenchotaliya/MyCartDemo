<?php

include './php-files/database.php';

$obj = new Database();

if (isset($_POST['parentCategoryId'])) {
    $parentCategoryId = $_POST['parentCategoryId'];

    // Fetching child categories
    $obj->sql("SELECT category_id, title FROM categories WHERE parent_category_id = $parentCategoryId");
    $result = $obj->getResult();

    if ($result) {
        echo '<tbody>'; // Only output table body, not the entire table structure

        foreach ($result as $row) {
            $id = $row['category_id'];
            $name = $row['title'];
            echo "<tr>";
            echo "<td>$id</td>";
            echo "<td>$name</td>";
            echo "<td><button onclick=\"fetchChildCategories($id)\">Click</button></td>";
            echo "</tr>";
        }

        echo '</tbody>';
    } else {
        echo 'No child categories found. Add a product.';
    }
}
