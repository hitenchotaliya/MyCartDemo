

//JQuery
$(document).ready(function () {
    var origin = window.location.origin;
    var path = window.location.pathname.split('/');
    var URL = origin + '/' + path[1] + '/';

    // Check login
    $("#adminLogin").submit(function (e) {
        e.preventDefault();

        // Clear previous error messages
        $("#usernameError").html("");
        $("#passwordError").html("");

        var username = $("#username").val();
        var password = $("#password").val();
        console.log(username);
        console.log(password);

        // Validate and show errors
        if (username.trim() === "") {
            $("#usernameError").html("Please fill in the username");
        }
        if (password.trim() === "") {
            $("#passwordError").html("Please fill in the password");
        }

        // If no errors, proceed with the form submission or other actions
        if (username.trim() !== "" && password.trim() !== "") {
            $.ajax({
                url: "./login.php",
                type: "POST",
                data: { submit: 1, username: username, password: password },
                success: function (response) {
                    // console.log("Response from server:", response);
                    $(".alert").remove(); // Clear previous alerts
                    try {
                        var res = JSON.parse(response);

                        if (res.hasOwnProperty('success')) {
                            $("#adminLogin").prepend("<div class='alert alert-success'>Logged in successfully</div>");
                            setTimeout(function () {
                                window.location = URL + 'dashboard.php';
                            }, 1000);
                        } else if (res.hasOwnProperty('error')) {
                            $("#adminLogin").prepend("<div class='alert alert-danger'>" + res.error + "</div>");
                        } else {
                            console.error("Unexpected response:", res);
                        }

                        // Check for additional errors
                        if (res.hasOwnProperty('additionalErrors')) {
                            for (var i = 0; i < res.additionalErrors.length; i++) {
                                console.error("Additional Error:", res.additionalErrors[i]);
                            }
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX request failed:", error);

                    // Display AJAX error
                    $("#adminLogin").prepend("<div class='alert alert-danger'>AJAX request failed: " + error + "</div>");
                }
            });

        }
    });

    //Display code
    // $('tr.parent').click(function () {
    //     var parentId = $(this).data('id');
    //     $('tr.child-of-' + parentId).toggle();
    // });

    //Multiple delete selector

    $('#select_all').on('click', function () {
        if (this.checked) {
            $('.checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    ///subCategory

    $('.checkbox').on('click', function () {
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('#select_all').prop('checked', true);
        } else {
            $('#select_all').prop('checked', false);
        }
    });
    // var searchRequest;

    // var searchTimeout;
    // var $searchInput = $("#search");

    // $searchInput.on("keyup", function () {
    //     clearTimeout(searchTimeout);

    //     // Save the reference to $(this) in a variable
    //     var $this = $(this);

    //     // Delay the AJAX request by 500 milliseconds after the user stops typing
    //     searchTimeout = setTimeout(function () {
    //         var s = $this.val();

    //         // Abort any existing AJAX request
    //         if (searchRequest) {
    //             searchRequest.abort();
    //         }

    //         searchRequest = $.ajax({
    //             url: "search.php",
    //             type: "POST",
    //             data: { search: s },
    //             dataType: "json",  // Specify JSON dataType
    //             success: function (data) {
    //                 // Display the results in the #table-data element
    //                 $("#table-data").html(buildTree(data));
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error("AJAX error:", status, error);
    //             }
    //         });
    //     }, 500);
    // });

    // function buildTree(categories) {
    //     // Check if the result set is empty
    //     if (categories.length === 0) {
    //         return '<tr><td colspan="7">No records found</td></tr>';
    //     }
    //     var html = '';
    //     var rowNumber = 1;

    //     // Assume you want to display a maximum of 10 results per page
    //     var limit = 10;

    //     for (var i = 0; i < categories.length; i++) {
    //         var category = categories[i];
    //         var isActive = (category['is_active'] == 1) ? 'Active' : 'Inactive';
    //         html += '<tr>';
    //         html += '<td>' + rowNumber++ + '</td>';
    //         html += '<td>' + category['category_id'] + '</td>';
    //         html += '<td>' + category['parent_category_id'] + '</td>';
    //         html += '<td>' + category['title'] + '</td>';
    //         html += '<td>' + isActive + '</td>';
    //         html += '<td>';
    //         html += '<form method="POST" action="update.php">';
    //         html += '<input type="hidden" name="id" value="' + category['category_id'] + '">';
    //         html += '<input type="submit" value="Update">';
    //         html += '</form>';
    //         html += '</td>';
    //         html += '<td>';
    //         html += '<form method="POST" action="delete.php">';
    //         html += '<input type="hidden" name="id" value="' + category['category_id'] + '">';
    //         html += '<input type="submit" value="Delete">';
    //         html += '</form>';
    //         html += '</td>';
    //         html += '</tr>';
    //     }

    //     return html;
    // }



});
