// function status_confirm(tableName) {
//     // Collect all the selected checkboxes
//     var selectedIds = [];
//     $('.checkbox:checked').each(function () {
//         selectedIds.push($(this).val());
//     });

//     // Check if any checkboxes are selected
//     if (selectedIds.length > 0) {
//         // Append the selected IDs and table name to the form data
//         $('form[name="bulk_edit_form"]').append('<input type="hidden" name="checked_id" value="' + selectedIds.join(',') + '">');
//         $('form[name="bulk_edit_form"]').append('<input type="hidden" name="table_name" value="' + tableName + '">');

//         // Ask for confirmation
//         var result = confirm("Are you sure to change status of selected items?");
//         if (result) {
//             return true; // Proceed with the action
//         } else {
//             return false; // Cancel the action
//         }
//     } else {
//         // No checkboxes selected, show an alert
//         alert('Select at least 1 record to change status.');
//         return false; // Cancel the action
//     }
// }

function status_confirm(tableName) {
    // Collect all the selected checkboxes
    var selectedIds = [];
    $('.checkbox:checked').each(function () {
        selectedIds.push($(this).val());
    });
    // console.log(selectedIds);
    if (selectedIds.length > 0) {
        // Ask for confirmation using SweetAlert
        swal({
            title: "Are you sure?",
            text: "You want change status",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                var action = $('#action').val();

                // console.log("Action:", action);

                // Log the form elements to ensure they have the expected values
                //console.log("Checked IDs:", selectedIds.join(','));
                //console.log("Table Name:", tableName);

                var checkedIdsString = selectedIds.join(',');
                var tableNameValue = tableName;
                var ActionValue = action;

                // Send AJAX request to delete the selected items
                $.ajax({
                    url: 'php-files/multi-active.php',
                    type: 'POST',
                    data: { checkedIds: checkedIdsString, tableName: tableNameValue, action: ActionValue }, // Serialize form data
                    success: function (response) {
                        console.log("Response is: " + response);
                        if (response == 1) {
                            swal("Action Successful!", {
                                icon: "success",
                            });
                        } else {
                            swal("Error Occurred!", {
                                icon: "error",
                            });
                        }
                    },
                    error: function () {
                        // Handle error response
                        swal("Error!", "Failed to delete selected item(s)!", "error");
                    }
                });
            } else {
                // Cancel the action
                swal("Cancelled", "Your data is safe :)", "info");
            }
        });
    } else {
        // No items selected
        swal("Error!", "Select at least 1 record to delete.", "error");
    }
}

// function delete_confirm(tableName) {
//     // Collect all the selected checkboxes
//     var selectedIds = [];
//     $('.checkbox:checked').each(function () {
//         selectedIds.push($(this).val());
//     });

//     if (selectedIds.length > 0) {
//         // Append the selected IDs and table name to the form data
//         $('form[name="bulk_action_form"]').append('<input type="hidden" name="checked_id" value="' + selectedIds.join(',') + '">');
//         $('form[name="bulk_action_form"]').append('<input type="hidden" name="table_name" value="' + tableName + '">');

//         // Ask for confirmation
//         var result = confirm("Are you sure you want to delete the selected item(s)?");
//         if (result) {
//             return true; // Proceed with the action
//         } else {
//             return false; // Cancel the action
//         }
//     } else {
//         alert('Select at least 1 record to delete.');
//         return false;
//     }
// }



function delete_confirm(tableName) {
    // Collect all the selected checkboxes
    var selectedIds = [];
    $('.checkbox:checked').each(function () {
        selectedIds.push($(this).val());
    });
    // console.log(selectedIds);
    if (selectedIds.length > 0) {
        // Ask for confirmation using SweetAlert
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover the selected item(s)!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                // Log the form elements to ensure they have the expected values
                // console.log("Checked IDs:", selectedIds.join(','));
                //console.log("Table Name:", tableName);

                var checkedIdsString = selectedIds.join(',');
                var tableNameValue = tableName;

                // Send AJAX request to delete the selected items
                $.ajax({
                    url: 'php-files/multi-delete.php',
                    type: 'POST',
                    data: { checkedIds: checkedIdsString, tableName: tableNameValue }, // Serialize form data
                    success: function (response) {
                        // Handle success response
                        // console.log("Data sent to PHP:", { checkedIds: checkedIdsString, tableName: tableNameValue });

                        // console.log("Response from PHP:", response);
                        const parseValue = JSON.parse(response);
                        // console.log(parseValue.success);
                        // Check if response indicates success
                        if (parseValue.success == true) {
                            swal("Success!", "Selected item(s) have been deleted successfully!", "success");
                            $('.checkbox:checked').closest("tr").fadeOut();
                        } else {
                            swal("Error!", "Can't delete parent records", "error");
                        }

                        // Perform any additional actions if needed
                        // swal("Success!", "Selected item(s) have been deleted successfully!", "success");

                    },
                    error: function () {
                        // Handle error response
                        swal("Error!", "Failed to delete selected item(s)!", "error");
                    }
                });
            } else {
                // Cancel the action
                swal("Cancelled", "Your data is safe :)", "info");
            }
        });
    } else {
        // No items selected
        swal("Error!", "Select at least 1 record to delete.", "error");
    }
}



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
        // console.log(username);
        //console.log(password);

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
                            $("#errorUP").prepend("<div class='alert alert-danger'>" + res.error + "</div>");
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


    //Single Delete Confirm
    // $('.deleted_confirm').on('click', function (e) {
    //     // Prevent the default form submission
    //     e.preventDefault();

    //     // Store the reference to the current form
    //     var form = $(this).closest('form');

    //     // Display SweetAlert confirmation dialog
    //     swal({
    //         title: "Are you sure?",
    //         text: "Once deleted, you will not be able to recover this!",
    //         icon: "warning",
    //         buttons: true,
    //         dangerMode: true,
    //     }).then((willDelete) => {
    //         // If the user confirms deletion, submit the form
    //         if (willDelete) {
    //             swal("Deleted Successfully!");
    //             form.submit();

    //         } else {
    //             // Otherwise, do nothing
    //             swal("Your record is safe!");
    //         }
    //     });
    // });


    //Delete
    $(document).on("click", ".deleted_confirm", function () {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this record!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                var id = $(this).data("id");
                var product_id = $(this).data("product_id");
                var image_id = $(this).data("image_id");

                var element = this;

                $.ajax({
                    url: "php-files/delete.php",
                    type: "POST",
                    data: { id: id, product_id: product_id, image_id: image_id },
                    success: function (response) {
                        // Parse JSON response
                        var data = JSON.parse(response);
                        // console.log(data);
                        if (data.hasOwnProperty('success')) {
                            swal("Deleted Successfully!", {
                                icon: "success",
                            }).then(() => {
                                // Remove closest table row if applicable
                                $(element).closest("tr").fadeOut();
                                // Remove closest image container if applicable
                                $(element).closest(".image-container").fadeOut();
                            });
                        } else if (data.hasOwnProperty('error')) {
                            // Display error message
                            swal(data.error, {
                                icon: "error",
                            });
                        } else {
                            // Unexpected response
                            swal("Error", "Unexpected response from server", "error");
                        }
                    },
                    error: function () {
                        // AJAX request failed
                        swal("Error", "AJAX request failed", "error");
                    }
                });
            }
        });
    });



    $(document).on("change", ".active_confirm", function () {
        var id = $(this).siblings('input[name="id"]').val(); // Get the ID value
        var checked = $(this).is(':checked'); // Check if the checkbox is checked or not
        var actionText = checked ? "deactivate" : "activate"; // Determine the action text based on checkbox state
        var confirmationText = checked ? "Once you deactivate this item, you may no longer see products related to it!" : "Are you sure you want to activate this item?";

        swal({
            title: "Are you sure?",
            text: confirmationText,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willProceed) => {
            if (willProceed) {
                $.ajax({
                    url: "./php-files/change.php",
                    type: "POST",
                    data: {
                        id: id,
                        toggle: checked ? "Active" : "Inactive" // Send the state of the checkbox
                    },
                    success: function (response) {
                        // console.log("Response is " + response);
                        if (response == 1) {
                            swal("Action Successful!", {
                                icon: "success",
                            });
                        } else {
                            swal("Error Occurred!", {
                                icon: "error",
                            });
                        }
                    }
                });
            } else {
                // If the user cancels the action
                $(this).prop('checked', !checked); // Toggle back the checkbox if the action is canceled
            }
        });
    });




    //Sub category Get 
    $('#categoryID').change(function () {
        var categoryId = $(this).val();
        $.ajax({
            url: './php-files/get_subcategories.php', // PHP script to fetch subcategories
            type: 'post',
            data: {
                categoryId: categoryId
            },
            dataType: 'json',
            success: function (response) {
                var options = '<option value="">Select Subcategory</option>';
                for (var i = 0; i < response.length; i++) {
                    options += '<option value="' + response[i].category_id + '">' + response[i].title + '</option>';
                }
                $('#subcategoryID').html(options);
            }
        });
    });
    // Form submission validation
    $('.productForm').submit(function (event) {
        console.log("Form submitted");
        var selectedCategory = $('#categoryID').val();
        console.log("Selected Category:", selectedCategory);
        if (selectedCategory === 'NULL') {
            $('#errorMessage').show();
            console.log("Error message displayed"); // error message is displayed
            event.preventDefault(); // Prevent form submission
        }
    });


    //Image Preview
    $('#file-input').change(function () {
        $('#image-preview').empty();

        var fileList = this.files;

        for (var i = 0; i < fileList.length; i++) {
            var file = fileList[i];
            var reader = new FileReader();

            reader.onload = function (event) {
                var img = $('<img>').attr('src', event.target.result)
                    .css({
                        'max-width': '200px',
                        'max-height': '200px',
                        'margin-right': '10px'
                    });


                // Create div to contain image and remove button
                var previewContainer = $('<div>').addClass('preview-image')
                    .append(img);


                $('#image-preview').append(previewContainer);
            };

            reader.readAsDataURL(file);
        }
    });


    //Search Validation
    $('input[name="search"]').on('input', function () {
        var searchTerm = $(this).val().trim(); // Trim to remove leading and trailing spaces
        var regex = /^[a-zA-Z0-9]+(?: [a-zA-Z0-9]+)*$/; // Regular expression to allow only letters, numbers, and spaces between words

        // Check if the input is empty or contains only spaces
        if (searchTerm === '') {
            $('.error-message').text('');
            $('input[type="submit"]').prop('disabled', true);
            return;
        }

        if (!regex.test(searchTerm)) {
            // Display error message if special characters are found
            $('.error-message').text('Please provide a valid input for search.');
            $('input[type="submit"]').prop('disabled', true); // Disable submit button
        } else {
            $('.error-message').text(''); // Clear error message 
            $('input[type="submit"]').prop('disabled', false);
        }
    });

    $('#productFormInsert input, #productFormInsert select').on('input change', function () {
        var fieldName = $(this).attr('name');
        var fieldValue = $(this).val().trim();
        var errorMessage = '';

        switch (fieldName) {
            case 'title':
                errorMessage = fieldValue === '' ? 'Please enter a valid title.' : '';
                $('#title-error').text(errorMessage);
                break;
            case 'description':
                errorMessage = fieldValue === '' ? 'Please enter a valid description.' : '';
                $('#description-error').text(errorMessage);
                break;
            case 'is_active':
                errorMessage = fieldValue === '' ? 'Please select an option for active status.' : '';
                $('#is_active-error').text(errorMessage);
                break;
            case 'categoryID':
                errorMessage = fieldValue === '' ? 'Please select a category.' : '';
                $('#categoryID-error').text(errorMessage);
                break;
        }

        // Validate file input separately
        if (fieldName === 'doc') {
            var fileCount = $('#doc')[0].files.length;
            errorMessage = fileCount === 0 ? 'Please select at least one image.' : '';
            $('#doc-error').text(errorMessage);
        }

        // Check if any error message exists
        var hasError = $('.error-message').filter(function () {
            return $(this).text().trim() !== '';
        }).length > 0;

        // Enable/disable submit button based on error messages
        $('button[type="submit"]').prop('disabled', hasError).css('background-color', hasError ? 'grey' : '');
    });


    $('input[name="categoryname"]').on('input', function () {
        // Get the category name from the input field
        var categoryName = $(this).val().trim().toLowerCase();

        // Reset error message
        $('#title-error').text('');

        // Check if the category name is less than 3 characters
        if (categoryName.length < 3) {
            $('#title-error').text('Category name must be at least 3 characters.');
            $('button[type="submit"]').prop('disabled', true);
        } else {
            $('#title-error').text(''); // Clear error message if category name is valid
        }

        // Get the list of existing category names from the select element
        var existingCategories = [];
        $('#parentCategoryID option').each(function () {
            existingCategories.push($(this).text().trim().toLowerCase());
        });

        // Check if the entered category name already exists
        if (existingCategories.includes(categoryName)) {
            $('#title-error').text('Category name already exists.');
            $('button[type="submit"]').prop('disabled', true);
        }

        // Enable/disable submit button based on error messages
        var hasError = $('#title-error').text().trim() !== '';
        $('button[type="submit"]').prop('disabled', hasError);

        // Change button background color based on error status
        $('button[type="submit"]').css('background-color', hasError ? 'grey' : '');

    });
    $('#categoryForm').on('submit', function (e) {
        var selectedSubcategory = $("#parentCategoryID").val();
        if (selectedSubcategory === '') {
            e.preventDefault(); // Prevent default form submission

            // If no subcategory is selected, show a SweetAlert message
            swal({
                title: 'No sub-category selected.',
                text: 'This will be treated as a parent category.',
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'OK',
                        value: true,
                        visible: true,
                        className: 'btn-primary',
                        closeModal: true
                    },
                    cancel: 'Cancel' // Add cancel button
                }
            }).then((value) => {
                // Proceed with form submission only after the user clicks "OK"
                if (value) {
                    // Submit the form
                    $("#categoryForm").off('submit').submit();
                }
            });
        }
    });

    //Image Slider



    console.log("Document ready, delete confirmation script running...");
});
