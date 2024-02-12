    <?php
    include './php-files/config.php';
    include './header.php';
    $obj = new Database();

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

    // Check if a search query is present
    if (isset($_GET['search']) && $_GET['search'] !== '') {
        $search = $obj->escapeString($_GET['search']);
        $obj->sql("SELECT * FROM categories WHERE `title` LIKE '%$search%' ");
        $categories = $obj->getResult();
    } else if (isset($_GET['sort']) && $_GET['sort'] !== '') {
        $obj->select("categories", "*", null, null, $orderby, $setLimit);
        $categories = $obj->getResult();
    } else if (isset($_GET['dsort']) && $_GET['dsort'] !== '') {
        $obj->select("categories", "*", null, null, $datesort, $setLimit);
        $categories = $obj->getResult();
    } else {
        // No search query, retrieve all categories
        $obj->select("categories", "*", null, null, $orderby, $setLimit);
        $categories = $obj->getResult();
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
            // $html .= '<td>' . $category['category_id'] . '</td>';
            // $html .= '<td>' . $category['parent_category_id'] . '</td>';
            $html .= '<td>' . $category['title'] . '</td>';
            $html .= '<td>' . $isActive . '</td>';
            $html .= '<td>' . $date_added . '</td>';
            $html .= '<td>' . $date_updated . '</td>';
            $html .= '<td>';
            $html .= '<form method="POST" action="update.php">';
            $html .= '<input type="hidden" name="id" value="' . $category['category_id'] . '">';
            $html .= '<input type="submit" value="Update">';
            $html .= '</form>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<form method="POST" action="./php-files/delete.php">';
            $html .= '<input type="hidden" name="id" value="' . $category['category_id'] . '">';
            $html .= '<input  class="deleted_confirm" type="submit" value="Remove">';
            $html .= '</form>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<form method="POST" action="./php-files/change.php">';
            $html .= '<input type="hidden" name="id" value="' . $category['category_id'] . '">';
            $html .= '<input type="submit" value="' . $isInActive . '">';
            $html .= '</form>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
    ?>


    <body>


        <!-- <div id="search-bar">
            <label>Search</label>
            <input type="text" id="search" autocomplete="off">
        </div><br> -->

        <div class="main">
            <h1>Categories</h1>

            <div class="col-md-7">
                <form action="categories.php" method="GET">
                    <div class="input-group search">
                        <input type="text" name="search" placeholder="Search for...">
                        <span>
                            <input type="submit" value="Search" />
                        </span>
                    </div>
                </form>
            </div>
            <div id="table-data">
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
                <!-- Add this dropdown to your form -->
                <div class="delete">
                    <?php
                    if (isset($_GET['success']) && $_GET['success'] === 'deleted') {
                        echo "Records deleted successfully!";
                    } else if (isset($_GET['updated']) && $_GET['updated'] === 'updated') {
                        echo "Records Updated successfully!";
                    }
                    ?>

                </div>
            </div>
            <div class="error">
                <?php
                if (isset($_GET['error_message'])) {
                    echo "You can not delete parent record directly " . $_GET['error_message'];
                }
                ?>
            </div>

            <div class="status">
                <form name="bulk_action_form" action="./php-files/multi-delete.php" method="POST" onSubmit="return delete_confirm('categories');">
                    <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete" />
                </form>

                <form name="bulk_edit_form" action="./php-files/multi-active.php" method="POST" onSubmit="return status_confirm('categories');">
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
                        <!-- SELECT * FROM categories ORDER BY title DESC; -->
                        <!-- SELECT * FROM categories ORDER BY title ASC; -->
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

            // $(document).ready(function() {
            //     $('.deleted_confirm').on('click', function(e) {
            //         // Prevent the default form submission
            //         e.preventDefault();

            //         // Confirm deletion
            //         var confirmation = confirm("Are you sure you want to delete this record?");

            //         // If user confirms deletion, submit the form
            //         if (confirmation) {
            //             $(this).closest('form').submit();
            //         }
            //     });
            // })
        </script>
    </body>