<?php
session_start();

// ✅ Check if a user is logged in and is a teacher
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    echo "<script>alert('Access denied. Please log in as a teacher.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

// Get teacher ID from session
$teacher_id = $_SESSION['user_id'];

// Get form data
$class_id = $_POST['classid'];
$class_name = $_POST['classname'];
$class_datetime = $_POST['date&time']; // Ex: "Monday 8.00 AM - 12.00 PM"
$year = $_POST['year'];
$subject = $_POST['subject'];

// Connect to DB
$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Insert into class table
$stmt1 = $conn->prepare("INSERT INTO class (class_id, class_name, class_datetime, year, subject) VALUES (?, ?, ?, ?, ?)");
$stmt1->bind_param("sssis", $class_id, $class_name, $class_datetime, $year, $subject);
$success1 = $stmt1->execute();
$stmt1->close();

// Insert into t_class table (link teacher to class)
if ($success1) {
    $stmt2 = $conn->prepare("INSERT INTO t_class (teacher_id, class_id) VALUES (?, ?)");
    $stmt2->bind_param("ss", $teacher_id, $class_id);
    $success2 = $stmt2->execute();
    $stmt2->close();

    if ($success2) {
        echo "<script>alert('Class created and linked successfully.');</script>";
        echo "<script>window.location.href = 'TeDashboard.php';</script>"; // Teacher dashboard
    } else {
        echo "<script>alert('Class created, but failed to link teacher.');</script>";
    }
} else {
    echo "<script>alert('Failed to create class.');</script>";
}

$conn->close();
?>
