<?php
session_start();

if (!isset($_POST['user_id']) || !isset($_POST['user_role'])) {
    echo "<script>alert('Invalid request.'); window.location.href = 'login.html';</script>";
    exit();
}

$user_id = $_POST['user_id'];
$user_role = $_POST['user_role'];

$table = $user_role === 'student' ? 'student' : 'teacher';
$id_field = $user_role === 'student' ? 'st_id' : 'teacher_id';
$name_field = $user_role === 'student' ? 'st_name' : 't_name';
$phone_field = $user_role === 'student' ? 'p_no' : 'phone';

$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete Account
if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE $id_field = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    echo "<script>alert('Account deleted.'); window.location.href = 'home.html';</script>";
    exit();
}

// Validate current password
$current_password = $_POST['current_password'];
$stmt = $conn->prepare("SELECT password FROM $table WHERE $id_field = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($db_password);
$stmt->fetch();
$stmt->close();

if ($current_password !== $db_password) {
    echo "<script>alert('Current password is incorrect.'); window.history.back();</script>";
    exit();
}

// New password match check
$new_password = $_POST['new_password'];
$repeat_password = $_POST['repeat_password'];
if (!empty($new_password) && $new_password !== $repeat_password) {
    echo "<script>alert('New passwords do not match.'); window.history.back();</script>";
    exit();
}

// Upload profile pic
$profile_pic = '';
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
    $target_dir = "uploads/";
    $file_name = basename($_FILES['profile_pic']['name']);
    $target_file = $target_dir . time() . '_' . $file_name;
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
        $profile_pic = $target_file;
    }
}

// Update user info
$update_query = "UPDATE $table SET $name_field = ?, email = ?, address = ?, $phone_field = ?, dob = ?";
$params = [$_POST['name'], $_POST['email'], $_POST['address'], $_POST['phone'], $_POST['dob']];
$types = "sssss";

if (!empty($new_password)) {
    $update_query .= ", password = ?";
    $params[] = $new_password;
    $types .= "s";
}
if (!empty($profile_pic)) {
    $update_query .= ", profile_pic = ?";
    $params[] = $profile_pic;
    $types .= "s";
}

$update_query .= " WHERE $id_field = ?";
$params[] = $user_id;
$types .= "s";

$stmt = $conn->prepare($update_query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->close();

$conn->close();

echo "<script>alert('Profile updated successfully.'); window.location.href = 'profile_setting.php';</script>";
exit();
?>
