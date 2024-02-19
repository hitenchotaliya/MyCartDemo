<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
</head>

<body>
    <?php
    include '../php-files/database.php';

    $obj = new Database();

    $obj->select("categories", "category_id,title", null, "parent_category_id IS NULL");

    $result  = $obj->getResult();

    echo "<ul>";
    foreach ($result as $category) {
        echo "<li><a href='get.php?id={$category['category_id']}'>{$category['title']}</a></li>";
    }
    echo "</ul>";
    ?>
    <div id="child-categories">
        <!-- Child categories will be displayed here -->
    </div>

    <script>
        // JavaScript for handling category clicks
        document.querySelectorAll('ul li a').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior

                // Extract category_id from the link's href attribute
                const categoryId = this.getAttribute('href').split('=')[1];

                // Make an AJAX request or redirect to get.php with categoryId as parameter
                fetch(`get.php?id=${categoryId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('child-categories').innerHTML = data;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>

</html>