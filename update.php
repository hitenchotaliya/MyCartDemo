<?php
include './header.php';
include './php-files/config.php';
$obj = new Database();
$id = $_POST['id'];

// Fetch all categories for the drop-down
$obj->select("categories", "*", null, "category_id != $id");
$allCategories = $obj->getResult();

// Fetch the category details for the given ID
$obj->select("categories", "*", null, "category_id = $id");
$selectedCategory = $obj->getResult();

// Check if the form is submitted for updating
if (isset($_POST['submit'])) {
    // Extract values from the form
    $title = $_POST['categoryname'];
    $active = $_POST['is_active'];
    $parentId = $_POST['parentCategoryID'];

    // Update the category
    $update =  $obj->update(
        "categories",
        [
            "title" => $title,
            "is_active" => $active,
            "parent_category_id" => $parentId
        ],
        "category_id = $id"
    );
    if ($update) {
        header("location: " . $cat);
        exit;
    } else {
        $err = "Something is wrong here";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
    <style>
        form {
            width: 50%;
            /* Adjust the width as needed */
            margin: auto;
            /* Center the form horizontally */
            margin-bottom: 20px;
        }

        form input[type="text"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form select {
            height: 40px;
        }

        form button {
            width: 100%;
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
    </style>
</head>

<body>
    <div class="main">


        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">


            CategoryName: <input type="text" name="categoryname" value="<?php echo $selectedCategory[0]['title']; ?>"><br><br>
            is_active :
            <select name="is_active" id="">
                <option value="1" <?php echo ($selectedCategory[0]['is_active'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo ($selectedCategory[0]['is_active'] == 0) ? 'selected' : ''; ?>>InActive</option>
            </select><br><br>

            <!-- Drop-down for parent category with default value -->

            <select name="parentCategoryID" id="">
                <option value="NULL">None</option>
                <?php
                foreach ($allCategories as $category) {
                    $categoryId = $category["category_id"];
                    $categoryName = $category["title"];
                    // Check if the selected category has no parent category
                    if ($selectedCategory[0]["parent_category_id"] === null) {
                        $selected = ($categoryId == "NULL") ? 'selected' : '';
                    } else {
                        $selected = ($categoryId == $selectedCategory[0]["parent_category_id"]) ? 'selected' : '';
                    }
                    echo "<option value='$categoryId' $selected>$categoryName</option>";
                }
                ?>
            </select><br><br>


            <button type="submit" name="submit">Update Category</button>

        </form>
        <?php
        if (isset($err)) {
            echo $err;
        }
        ?>
    </div>
</body>

</html>