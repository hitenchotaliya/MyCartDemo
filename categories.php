    <?php
    include './php-files/config.php';
    include './header.php';
    include './class/category.php';
    $obj = new Database();
    $categoriesClass = new category($obj);

    // Setting limit of page record
    $setLimit = 10;
    $orderby = "";
    $datesort = "";

    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === 'a-z') {
            $orderby = " title ASC";
        } else if ($_GET['sort'] === 'z-a') {
            $orderby = " title DESC";
        }
    }
    if (isset($_GET['activestatus'])) {
        if ($_GET['activestatus'] === 'active') {
            $activecategory = " is_active = 1";
        } else if ($_GET['activestatus'] === 'deactive') {
            $activecategory = " is_active = 0";
        }
    }

    if (isset($_GET['dsort'])) {
        if ($_GET['dsort'] === 'r-added') {
            $datesort = " date_added DESC";
        } elseif ($_GET['dsort'] === 'l-added') {
            $datesort = " date_added ASC";
        } else if ($_GET['dsort'] === 'r-updated') {
            $datesort = " date_updated DESC";
        } elseif ($_GET['dsort'] === 'l-updated') {
            $datesort = " date_updated ASC";
        }
    }

    if (isset($_GET['search'])) {
        $svalue = $_GET['search'];
    }
    // Check if a search query is present
    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $categories = $categoriesClass->searchCategories($_GET['search']);
    } else if (isset($_GET['sort']) && $_GET['sort'] !== '') {
        $categories = $categoriesClass->sortCategories($orderby, $setLimit);
    } else if (isset($_GET['dsort']) && $_GET['dsort'] !== '') {
        $categories = $categoriesClass->sortCategoriesByDate($datesort, $setLimit);
    } else if (isset($_GET['activestatus']) && $_GET['activestatus'] !== '') {
        $categories = $categoriesClass->getActiveCategories($activecategory, $setLimit);
    } else {
        $categories = $categoriesClass->getAllCategories($page, $setLimit);
    }


    function buildTree($categories)
    {
        // Check if the result set is empty
        if (empty($categories)) {
            return '<tr><td colspan="7">No records found</td></tr>';
        }
        $html = '';
        $rowNumber = 1;

        // Check if the page is greater than 1 and calculate the starting row number accordingly
        if (isset($_GET['page']) && $_GET['page'] > 1) {
            $rowNumber = ($_GET['page'] - 1) * 10 + 1;
        }

        foreach ($categories as $category) {

            //Set Status view
            $isActive = ($category['is_active'] == 1) ? 'Active' : 'Inactive';
            $isInActive = ($category['is_active'] == 1) ? 'Inactive' : 'Active';

            // Format date_added and date_updated fields
            $date_added = date('d-m-Y', strtotime($category['date_added']));
            $date_updated = date('d-m-Y', strtotime($category['date_updated']));

            $html .= '<tr>';
            $html .= '<td><input type="checkbox" name="checked_id[]" class="checkbox" value="' . $category['category_id'] . '" /></td>';
            $html .= '<td>' . $rowNumber++ . '</td>';
            $html .= '<td>' . $category['title'] . '</td>';
            $html .= '<td>' . $isActive . '</td>';
            $html .= '<td>' . $date_added . '</td>';
            $html .= '<td>' . $date_updated . '</td>';
            $html .= '<td>';
            $html .= '<form method="POST" action="update.php">';
            $html .= '<input type="hidden" name="id" value="' . $category['category_id'] . '">';
            $html .= '<button type="submit" class="update-btn"><i class="fa-regular fa-pen-to-square"></i></button>';
            $html .= '</form>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<button class="deleted_confirm delete-btn" data-id="' . $category["category_id"] . '">
                         <i class="fa fa-trash"></i>
                         </button>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<input type="hidden" name="id" value="' . $category['category_id'] . '">';
            $html .= '<input class="active_confirm input-switch" type="checkbox" id="toggle_' . $category['category_id'] . '" name="toggle" ' . ($isInActive == "Active" ? "checked" : "") . '>';
            $html .= '<label class="label-switch" for="toggle_' . $category['category_id'] . '"></label>';

            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
    ?>

    <head>
        <link rel="stylesheet" href="./css/main.css">
        <link rel="stylesheet" href="./css/table-sort.css">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" /> -->
    </head>

    <body>

        <div class="main">
            <h1>Categories</h1>

            <div class="col-md-7">
                <form action="categories.php" method="GET">
                    <div class="input-group search">
                        <input type="text" name="search" placeholder="Search for..." value="<?php if (isset($svalue)) {
                                                                                                echo $svalue;
                                                                                            } ?>">
                        <span>
                            <input type="submit" value="Search" />
                        </span>

                    </div>
                </form>
                <div class="error-message" style="color: red;"></div>
            </div>
            <div id="table-data" class="table-sortable">
                <table border="1">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select_all" value="" /></th>
                            <th>Index</th>
                            <!-- <th>Category ID</th> -->
                            <!-- <th>Parent Category ID</th> -->
                            <th>Title</th>
                            <th>Is Active</th>
                            <th>Date Added</th>
                            <th>Date Updated</th>
                            <th>Update</th>
                            <th>Delete</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo buildTree($categories); ?>
                    </tbody>
                </table>
                <br>
            </div>
            <div class="delete">
                <?php
                if (isset($_GET['success']) && $_GET['success'] === 'deleted') {
                    echo "Records deleted successfully!";
                } else if (isset($_GET['updated']) && $_GET['updated'] === 'updated') {
                    echo "Records Updated successfully!";
                }
                ?>

            </div>
            <div class="error">
                <?php
                if (isset($_GET['error_message'])) {
                    echo "You can not delete parent record directly " . $_GET['error_message'];
                }
                ?>
            </div>

            <div class="status">

                <button type="button" class="btn delete-btn" onclick="delete_confirm('categories')"><i class="fa fa-trash"></i></button>


                <label>Status: </label>
                <select id="action" name="action">
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                </select>
                <input type="submit" class="btn btn-danger" name="bulk_edit_submit" value="Apply" onclick="status_confirm('categories')" />
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
                    <label for="sort">Sort by date:</label>
                    <select name="dsort" id="sort">
                        <option value="">Select Option</option>
                        <option value="r-added" <?php if (isset($_GET['dsort']) && $_GET['dsort'] == "r-added") {
                                                    echo "selected";
                                                } ?>>Recently-Added</option>
                        <option value="l-added" <?php if (isset($_GET['dsort']) && $_GET['dsort'] == "l-added") {
                                                    echo "selected";
                                                } ?>>Last-Added</option>
                        <option value="r-updated" <?php if (isset($_GET['dsort']) && $_GET['dsort'] == "r-updated") {
                                                        echo "selected";
                                                    } ?>>Recently-Updated</option>
                        <option value="l-updated" <?php if (isset($_GET['dsort']) && $_GET['dsort'] == "l-updated") {
                                                        echo "selected";
                                                    } ?>>Last-Updated</option>

                    </select>
                    <input type="submit" value="Submit">
                </form>
                <form action="" method="GET">
                    <label for="activestatus">Sort:</label>
                    <select name="activestatus" id="sort">
                        <option value="">Select Option</option>
                        <option value="active" <?php if (isset($_GET['activestatus']) && $_GET['activestatus'] == "active") {
                                                    echo "selected";
                                                } ?>>Active</option>
                        <option value="deactive" <?php if (isset($_GET['activestatus']) && $_GET['activestatus'] == "deactive") {
                                                        echo "selected";
                                                    } ?>>Deactive</option>
                    </select>
                    <input type="submit" value="Submit">
                </form>
                <button onclick="window.location.href='<?php echo $cat ?>'">Clear</button>

            </div>
            <div class="pagination">
                <?php
                // Use appropriate pagination method based on search status
                if (isset($_GET['search']) && $_GET['search'] !== '') {
                    echo $obj->Pagination("categories", null, $setLimit);
                } else {
                    echo $obj->Pagination("categories", null, null, $setLimit);
                }
                ?>
            </div>
        </div>


        <script src="./js/table-sort.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </body>