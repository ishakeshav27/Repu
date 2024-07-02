<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'test');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all students and their marks
$sql = "SELECT users.id, users.name, marks.english, marks.science, marks.maths, 
        (marks.english + marks.science + marks.maths) AS total 
        FROM user AS users
        LEFT JOIN marks ON users.id = marks.id 
        WHERE users.role = 'student'";
$result = $conn->query($sql);

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Add or update student marks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    if (isset($_POST['english']) && isset($_POST['science']) && isset($_POST['maths'])) {
        $student_id = $_POST['student_id'];
        $english = $_POST['english'];
        $science = $_POST['science'];
        $maths = $_POST['maths'];

        // Check if the student already has marks
        $check_sql = "SELECT * FROM marks WHERE id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Update marks if the student already has marks
            $update_sql = "UPDATE marks SET english = ?, science = ?, maths = ? WHERE id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("iiii", $english, $science, $maths, $student_id);
        } else {
            // Insert new marks if the student doesn't have marks yet
            $insert_sql = "INSERT INTO marks (id, english, science, maths) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iiii", $student_id, $english, $science, $maths);
        }

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Add new student and their marks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_name'])) {
    $name = $_POST['new_name'];
    $english = $_POST['new_english'];
    $science = $_POST['new_science'];
    $maths = $_POST['new_maths'];

    // Insert new student into users table
    $insert_user_sql = "INSERT INTO user (name, role) VALUES (?, 'student')";
    $stmt = $conn->prepare($insert_user_sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $new_student_id = $stmt->insert_id; // Get the auto-generated ID of the new student
    $stmt->close();

    // Insert new marks into marks table
    $insert_marks_sql = "INSERT INTO marks (id, english, science, maths) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_marks_sql);
    $stmt->bind_param("iiii", $new_student_id, $english, $science, $maths);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid resubmission on refresh
    header("Location: admin_dashboard.php");
    exit();
}

// Delete student and their marks
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $student_id = $_POST['student_id'];

    // Delete student from users table
    $delete_user_sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($delete_user_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();

    // Delete student's marks from marks table
    $delete_marks_sql = "DELETE FROM marks WHERE id = ?";
    $stmt = $conn->prepare($delete_marks_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid resubmission on refresh
    header("Location: admin_dashboard.php");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: #c92e56;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
        }

        add_student {}

        .container {
            width: 80%;
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

        th {
            background-color: #f2f2f2;
        }

        input[type="number"],
        input[type="text"] {
            width: 60px;
            padding: 5px;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <h3>Add New Student</h3>
        <form action="admin_dashboard.php" class="add_student" method="post">
            <label for="new_name">Name:</label>
            <input type="text" id="new_name" name="new_name" required><br><br>
            <label for="new_user">User name:</label>
            <input type="text" id="new_user" name="new_user" required><br><br>
            <label for="new_english">English:</label>
            <input type="number" id="new_english" name="new_english" required><br><br>
            <label for="new_science">Science:</label>
            <input type="number" id="new_science" name="new_science" required><br><br>
            <label for="new_maths">Maths:</label>
            <input type="number" id="new_maths" name="new_maths" required><br><br>
            <input type="submit" name="add_student" value="Add Student">
        </form>

        <hr>

        <h3>List of Students and Their Marks</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>English</th>
                <th>Science</th>
                <th>Maths</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['english']) ?></td>
                    <td><?= htmlspecialchars($student['science']) ?></td>
                    <td><?= htmlspecialchars($student['maths']) ?></td>
                    <td><?= htmlspecialchars($student['total']) ?></td>
                    <td>
                        <form method="post" action="admin_dashboard.php" style="display:inline-block;">
                            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                            <input type="number" name="english" placeholder="English" value="<?= $student['english'] ?>"
                                required>
                            <input type="number" name="science" placeholder="Science" value="<?= $student['science'] ?>"
                                required>
                            <input type="number" name="maths" placeholder="Maths" value="<?= $student['maths'] ?>" required>
                            <input type="submit" name="update_marks" value="Update Marks">
                        </form>
                        <form method="post" action="admin_dashboard.php" style="display:inline-block;">
                            <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                            <input type="submit" name="delete_student" value="Delete" class="button">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <form action="admin_dashboard.php" method="post">
            <input type="submit" name="logout" value="Logout" class="logout">
        </form>
    </div>
</body>

</html>