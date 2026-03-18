<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    echo "<script>alert('Access denied'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resource_file'])) {
    $class_id = $_POST['class_id'];
    $file = $_FILES['resource_file'];
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($file['name']);
    $targetPath = $uploadDir . time() . "_" . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
        $stmt = $conn->prepare("INSERT INTO class_resource (class_id, file_name, file_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $class_id, $filename, $targetPath);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: T_class_inside.php?class_id=" . urlencode($class_id));
        exit();
    } else {
        echo "<script>alert('File upload failed'); history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request'); history.back();</script>";
}
?>
