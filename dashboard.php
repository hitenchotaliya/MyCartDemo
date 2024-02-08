<?php
include './php-files/config.php';
if (!session_id()) {
    session_start();
}
if (!isset($_SESSION['admin_name'])) {
    header("location:{$baseurl}");
}
$name = $_SESSION['admin_name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <h1>Welcome <?php echo $name; ?></h1>

    <ul>
        <li><a href="categories.php">Category</a></li>
        <li><a href="product.php">Product</a></li>
    </ul>
    <button><a href="./php-files/logout.php">Logout</a></button>
</body>

</html>