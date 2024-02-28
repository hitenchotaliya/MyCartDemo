<?php

include './php-files/database.php';

$obj = new Database();

// Fetching and displaying parent categories
$obj->sql("SELECT category_id, title FROM categories WHERE parent_category_id IS NULL");
$result = $obj->getResult();

echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>Id</th>';
echo '<th>Name</th>';
echo '<th>Click</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

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
echo '</table>';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>

<body>

    <div id="childCategories"></div>

    <script>
        var expandedCategories = {}; // Object to store expanded categories and their children IDs

        function fetchChildCategories(parentCategoryId) {
            $.ajax({
                url: 'fetch_child_categories.php',
                type: 'POST',
                data: {
                    parentCategoryId: parentCategoryId
                },
                success: function(response) {
                    if (expandedCategories.hasOwnProperty(parentCategoryId)) {
                        // Replace existing children
                        $('#' + parentCategoryId).next('tbody').replaceWith(response);
                    } else {
                        // Append new children
                        $('#childCategories').append(response);
                        expandedCategories[parentCategoryId] = true;
                    }
                }
            });
        }
    </script>


</body>

</html>