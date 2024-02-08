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
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <div class="header">
        <div class="header-left">ONLINE SHOPPING</div>
        <div class="header-right">HI ADMIN</div>
        <div class="logout"><a href="./php-files/logout.php">Logout</a></div>
    </div>
    <div class="sidebar">
        <ul class="menu">
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="categories.php">Category</a></li>
            <li><a href="insert.php">Add Category</a></li>
            <li><a href="product.php">Product</a></li>
            <li><a href="">About us</a></li>
        </ul>
    </div>

    <script src="./js/js.js"></script>
</body>

</html>