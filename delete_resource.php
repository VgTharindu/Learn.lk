<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    echo "<script>alert('Access denied'); window.location.href='login.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $class_id = $_POST['class_id'];

    $conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
    $stmt = $conn->prepare("SELECT file_path FROM class_resource WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $file = $res->fetch_assoc();
    $stmt->close();

    if ($file && file_exists($file['file_path'])) {
        unlink($file['file_path']);
    }

    $stmt = $conn->prepare("DELETE FROM class_resource WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: T_class_inside.php?class_id=" . urlencode($class_id));
    exit();
} else {
    echo "<script>alert('Invalid request'); history.back();</script>";
}
?>
