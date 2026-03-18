<?php
session_start();

$conn = new mysqli("localhost", "root", "", "new_learn_lk");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];  // teacher_id or student_id
$password = $_POST['password'];



// Check if it's a teacher
$t_query = $conn->prepare("SELECT password FROM teacher WHERE teacher_id = ?");
$t_query->bind_param("s", $username);
$t_query->execute();
$t_result = $t_query->get_result();

if ($t_result->num_rows > 0) {
    $teacher = $t_result->fetch_assoc();
    if (password_verify($password, $teacher['password'])) {
        $_SESSION['user_role'] = 'teacher';
        $_SESSION['user_id'] = $username;
        header("Location: TeDashboard.php");
        exit();
    }
}

// Check if it's a student
$s_query = $conn->prepare("SELECT password FROM student WHERE st_id = ?");
$s_query->bind_param("s", $username);
$s_query->execute();
$s_result = $s_query->get_result();

if ($s_result->num_rows > 0) {
    $student = $s_result->fetch_assoc();
    if (password_verify($password, $student['password'])) {
        $_SESSION['user_role'] = 'student';
        $_SESSION['user_id'] = $username;
        header("Location: StDashboard.php");
        exit();
    }
}

// If no match
echo "<script>alert('Invalid username or password'); window.location.href='login.html';</script>";
exit();
?>
