<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.html");
    exit();
}

$class_id = $_GET['class_id'] ?? '';
// You can add more logic to track joined students here.

?>
<!DOCTYPE html>
<html>
<head>
    <title>Join Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>You have joined the class <?php echo htmlspecialchars($class_id); ?> successfully!</h2>
    <p>Class streaming or live session can be integrated here.</p>
    <a href="S_class_inside.php?class_id=<?php echo htmlspecialchars($class_id); ?>" class="btn btn-primary">Back to Class</a>
</body>
</html>
