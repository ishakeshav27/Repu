<?php
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['mail'];
    $phone = $_POST['phone'];
    $username = $_POST['user'];
    $password = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role']; // student or teacher

    // Insert user into database
    $sql = "INSERT INTO user (name, email, phone, user, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $phone, $username, $password, $role);

    if ($stmt->execute()) {
        echo "<h1>Registration successful!</h1>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #6b051f;
            font-family: 'Times New Roman', Times, serif;
            color: black;
            text-align: center;
        }

        h3 {
            text-shadow: 1px 1px 5px red;
            font-size: 25px;
        }

        div.repu {
            width: 250px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            text-align: center;
            float: right;

        }



        .signup-box {
            width: 400px;
            margin: 90px auto;
            padding: 35px 70px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 10px 10px 5px black;
        }

        .signup-box h3 {
            margin-bottom: 20px;
        }

        .signup-box label {
            display: inline-block;
            width: 100px;
            text-align: left;
            margin-bottom: 10px;
        }

        .signup-box input[type="text"],
        .signup-box input[type="tel"],
        .signup-box input[type="password"],
        .signup-box select {
            width: calc(100% - 120px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .signup-box input[type="submit"] {
            background-color: #c92e56;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .signup-box button[type="button"] {
            background-color: #c92e56;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .signup-box button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .signup-box input[type="submit"]:hover {
            background-color: #871835;
        }

        .signup-box button[type="button"]:hover {
            background-color: #871835;
        }
    </style>
</head>

<body>
    <!-- <div class="repu">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHJPCws5yXAQQJXvyZ3pL5mZhkIjjPtScN4A&s"
            alt="rep" style="width:100%">
    </div> -->
    <div class="signup-box">
        <h3>Sign Up/Register</h3>

        <form method="post" action="signup.php">
            <label for="name">Name:</label>
            <input name="name" type="text" id="name" required><br><br>

            <label for="mail">Email:</label>
            <input name="mail" type="text" id="mail" required><br><br>

            <label for="phone">Phone:</label>
            <input name="phone" type="tel" id="phone" required><br><br>

            <label for="user">User Name:</label>
            <input name="user" type="text" id="user" required><br><br>

            <label for="pass">Password:</label>
            <input name="pass" type="password" id="pass" required><br><br>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select><br><br>

            <input type="submit" value="Sign Up">
            <button type="button" class="admin-button" onclick="window.location.href='admin.php'">Admin Login</button>
            <button type="button" class="user-button" onclick="window.location.href='login.php'">User Login</button>
        </form>
    </div>
</body>

</html>