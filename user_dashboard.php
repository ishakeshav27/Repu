<?php
session_start();

// Check if user is logged in as a student
if (!isset($_SESSION['user_logged_in']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student's own marks
$username = $_SESSION['username'];
$sql = "SELECT users.name, marks.english, marks.science, marks.maths, 
        (marks.english + marks.science + marks.maths) AS total 
        FROM user AS users
        LEFT JOIN marks ON users.id = marks.id 
        WHERE users.user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #c92e56;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        input[type="submit"],
        .button {
            background-color: #c92e56;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        .button:hover {
            background-color: #871835;
        }

        .logout {
            background-color: #c92e56;
        }

        .logout:hover {
            background-color: #871835;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <h3>Your Marks</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>English</th>
                <th>Science</th>
                <th>Maths</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= htmlspecialchars($student['english']) ?></td>
                <td><?= htmlspecialchars($student['science']) ?></td>
                <td><?= htmlspecialchars($student['maths']) ?></td>
                <td><?= htmlspecialchars($student['total']) ?></td>
            </tr>
        </table>
        <form action="user_dashboard.php" method="post">
            <input type="submit" name="logout" value="Logout" class="logout">
        </form>
    </div>
</body>

</html>