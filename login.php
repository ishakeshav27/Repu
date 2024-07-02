<?php
/////////////////////USER LOGIN///////////////////////////////////
session_start();

// Connect Database 
$conn = mysqli_connect('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from database
    $sql = "SELECT * FROM user WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];

        // Redirect to appropriate page
        if ($user['role'] == 'student') {
            header("Location: user_dashboard.php");
        } else {
            header("Location: login.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #6b051f;
            font-family: 'Times New Roman', Times, serif;
            color: black;
            text-align: center;
            font-size: 20px;
        }

        h3 {
            text-shadow: 1px 1px 5px red;
            font-size: 25px;
        }

        .login-box {
            width: 400px;
            margin: 90px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 10px 10px 5px black;
        }

        .login-box h3 {
            margin-bottom: 40px;
        }

        .login-box label {
            display: inline-block;
            width: 100px;
            text-align: left;
            margin-bottom: 10px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: calc(100% - 120px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .login-box input[type="submit"] {
            background-color: #c92e56;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-box input[type="submit"]:hover {
            background-color: grey;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h3>User Login</h3>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>
        <?php
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        ?>
    </div>
</body>

</html>