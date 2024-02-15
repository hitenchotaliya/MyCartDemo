<?php

if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['admin_name'])) {
    header("location:{$baseurl}");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <div class="header">
        <div class="header-left">ONLINE SHOPPING</div>
        <div class="header-right">HI ADMIN</div>
        <div class="logout"><a href="./php-files/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"><span class="tooltiptext">Logout</span></i></a></div>
    </div>
    <div class="sidebar">
        <ul class="menu">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="categories.php">Category</a></li>
            <li><a href="insert.php">Add Category</a></li>
            <li><a href="product.php">Product</a></li>
            <li><a href="insertProduct.php">Add Product</a></li>
            <li><a href="">About us</a></li>
        </ul>
    </div>

    <script src="./js/js.js"></script>
    <script src="./js/table-sort.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>