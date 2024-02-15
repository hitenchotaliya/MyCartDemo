<?php
// session_start();

include './php-files/config.php';


if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        echo json_encode(array("error" => "Please enter a username"));
    } elseif (empty($_POST['password'])) {
        echo json_encode(array("error" => "Please enter a password"));
    } else {
        $db = new Database();

        $username = $db->escapeString($_POST['username']);
        $userEnteredPassword = $_POST['password'];

        $db->select('admin', '*', null, "admin_name = '$username'", null, 0);
        $result = $db->getResult();

        if (!empty($result)) {
            $hashPassword = $result[0]['admin_password'];

            if (password_verify($userEnteredPassword, $hashPassword)) {
                // Passwords match
                // Start New session
                session_start();
                // set session variable
                $_SESSION['admin_name'] = $result[0]['admin_name'];
                $_SESSION['admin_role'] = "admin";
                echo json_encode(array('success' => 'true'));
                exit;
            } else {
                // Passwords do not match
                echo json_encode(array("error" => "Username and password <br> do not match"));
                exit;
            }
        } else {
            // Admin name not found
            echo json_encode(array("error" => "User not found"));
            exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        body,
        html {
            background: #DBE2EF;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-form {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .login-form h2 {
            margin-bottom: 20px;
        }

        .login-form label {
            font-weight: bold;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #3F72AF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #315a8b;
        }

        .err {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="" method="post" id="adminLogin" class="login-form">
            <h2>Login</h2>
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br>
            <div class="err" id="usernameError"></div>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br>
            <div class="err" id="passwordError"></div>

            <button type="submit">Submit</button>
        </form>
    </div>
    <script src="./js/js.js"></script>
</body>

</html>