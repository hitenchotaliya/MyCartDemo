<?php
include './header.php';
include './php-files/config.php';

$obj = new Database();
$id = $_POST['id'] ?? null; // Ensure $id is properly initialized

// Fetch data for loading in the form
$obj->select("categories");
$Categories = $obj->getResult();

// Fetch product details
$obj->select("products", "*", null, "product_id = $id");
$selectedProduct = $obj->getResult();
$spid = $selectedProduct[0]['category_id'];

// Fetch subcategories
$obj->select("categories", "*", null, "category_id = $spid");
$Subcategories = $obj->getResult();

if (isset($_POST['submit'])) {
    // Process form submission
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $active = $_POST['is_active'];
    $subcategoryId = $_POST['subcategoryID'] ?? '';

    if ($subcategoryId == '') {
        $subcategoryId = $_POST['categoryID'];
    }

    // Update product information
    $updateProduct = $obj->update(
        "products",
        [
            "title" => $title,
            "description" => $desc,
            "is_active" => $active,
            "category_id" => $subcategoryId
        ],
        "product_id = $id"
    );

    if (!$updateProduct) {
        // Handle error if product update fails
        $err =  "Failed to update product information.\n";
        //exit;
    }
    header("Location: product.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div class="main">


        <form action="" method="post" enctype="multipart/form-data" class="custom-form" id="productForm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            Product Name: <input type="text" name="title" value="<?php echo $selectedProduct[0]['title']; ?>" required><br><br>
            Product Description: <input type="text" name="description" value="<?php echo $selectedProduct[0]['description']; ?>" required><br><br>
            is_active :
            <select name="is_active" id="">
                <option value="1" <?php echo ($selectedProduct[0]['is_active'] == 1) ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo ($selectedProduct[0]['is_active'] == 0) ? 'selected' : ''; ?>>InActive</option>
            </select><br><br>

            Categories:
            <select name="categoryID" id="categoryID" required>
                <option value="NULL">None</option>
                <?php
                foreach ($Categories as $category) {
                    $categoryId = $category["category_id"];
                    $categoryName = $category["title"];
                    $selected = ($categoryId == $selectedProduct[0]["category_id"]) ? 'selected' : '';
                    echo "<option value='$categoryId' $selected>$categoryName</option>";
                }
                ?>
            </select><br><br>

            Subcategory:
            <select name="subcategoryID" id="subcategoryID">
                <!-- Options will be populated dynamically -->
            </select>
            <button type="submit" name="submit">Submit</button>
        </form>
        <div class="err" id="errorMessage" style="color: red; display: none;">You can't save the product without selecting a category.</div>

    </div>
</body>

</html>