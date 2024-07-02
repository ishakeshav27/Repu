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

        .signup-box {
            width: 400px;
            margin: 90px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
        .signup-box input[type="password"] {
            width: calc(100% - 120px);
            /* Adjust input width */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .signup-box input[type="submit"] {
            background-color: #4CAF50;
            /* Green submit button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .signup-box button {
            background-color: #4CAF50;
            /* Green submit button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .signup-box input[type="submit"]:hover {
            background-color: #45a049;
            /* Darker green on hover */
        }
    </style>
</head>

<body>
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
            <input name="role" type="text" id="role" required><br><br>

            <input type="submit" value="Sign Up">
            <button type="button" class="admin-button" onclick="window.location.href='admin.php'">Admin Login</button>
        </form>
    </div>
</body>

</html>