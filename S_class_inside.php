<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    echo "<script>alert('Please log in as a student.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

$student_id = $_SESSION['user_id'];
$class_id = $_GET['class_id'] ?? '';

$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Join Class button POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_class'])) {
    $classToJoin = $_POST['class_id'];

    // Check if student already joined
    $checkStmt = $conn->prepare("SELECT * FROM st_class WHERE st_id = ? AND class_id = ?");
    $checkStmt->bind_param("ss", $student_id, $classToJoin);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('You have already joined this class.');</script>";
    } else {
        // Insert student-class relationship
        $insertStmt = $conn->prepare("INSERT INTO st_class (st_id, class_id) VALUES (?, ?)");
        $insertStmt->bind_param("ss", $student_id, $classToJoin);
        if ($insertStmt->execute()) {
            echo "<script>alert('Successfully joined the class!');</script>";
        } else {
            echo "<script>alert('Failed to join the class. Please try again later.');</script>";
        }
        $insertStmt->close();
    }

    $checkStmt->close();
}

// Fetch student info
$stmt = $conn->prepare("SELECT st_name, profile_pic FROM student WHERE st_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$student_result = $stmt->get_result()->fetch_assoc();
$stmt->close();

$studentName = $student_result['st_name'];
$profileImage = !empty($student_result['profile_pic']) ? $student_result['profile_pic'] : 'img/default_profile.png';

// Fetch class, teacher and resource info
$stmt = $conn->prepare("SELECT c.class_id, c.class_name, c.class_datetime, c.year, c.subject, t.t_name, t.detail, t.profile_pic as t_pic 
                        FROM class c 
                        JOIN t_class tc ON c.class_id = tc.class_id 
                        JOIN teacher t ON tc.teacher_id = t.teacher_id 
                        WHERE c.class_id = ?");
$stmt->bind_param("s", $class_id);
$stmt->execute();
$class_info = $stmt->get_result()->fetch_assoc();
$stmt->close();

$teacherName = $class_info['t_name'];
$teacherDetails = $class_info['detail'];
$teacherPic = !empty($class_info['t_pic']) ? $class_info['t_pic'] : 'img/default_profile.png';
$className = $class_info['class_name'];
$classTime = $class_info['class_datetime'];
$classYear = $class_info['year'];
$subject = $class_info['subject'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Learn.lk</title>
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/Sdashboard.css" />
    <link rel="stylesheet" href="css/S_class_inside.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<!--side bar-->
<div class="sidebar">
    <div class="logo_details">
        <i class="icon"></i>
        <div class="logo_name">Learn . LK</div>
        <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
        <li class="profile">
            <div class="profile_details">
                <img src="<?php echo $profileImage; ?>" alt="profile image" />
                <div class="profile_content">
                    <div class="name"><?php echo $studentName; ?></div>
                    <div class="designation">Student</div>
                </div>
            </div>
            <i class="bx bx-log-out" id="log_out"></i>
        </li><br /><br />
        <li>
            <a href="StDashboard.php">
                <i class="bx bx-grid-alt"></i>
                <span class="link_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="myClasses.php">
                <i class="bx bx-store-alt"></i>
                <span class="link_name">My Classes</span>
            </a>
            <span class="tooltip">My Classes</span>
        </li>
        <!--
        <li>
            <a href="#">
                <i class="bx bx-spreadsheet"></i>
                <span class="link_name">Exam</span>
            </a>
            <span class="tooltip">Exam</span>
        </li>
        <li>
            <a href="#">
                <i class="bx bx-bar-chart"></i>
                <span class="link_name">Attendance</span>
            </a> 
            <span class="tooltip">Attendance</span>
        </li>
        -->
        <li>
            <a href="Profile_Setting.php">
                <i class="bx bx-cog"></i>
                <span class="link_name">Settings</span>
            </a>
            <span class="tooltip">Settings</span>
        </li>
        <li>
            <a href="login.html">
                <i class="bx bx-log-out-circle"></i>
                <span class="link_name">Log Out</span>
            </a>
            <span class="tooltip">Log Out</span>
        </li>
    </ul>
</div>
<!--end sidebar-->

<section class="home-section">
    <section class="main-course">
        <div class="course-box">
            <h3>Class Details</h3>

            <div class="box1">
                <div class="profile_details">
                    <img src="<?php echo $teacherPic; ?>" alt="teacher image" />
                    <div class="profile_content">
                        <div class="name"><?php echo htmlspecialchars($teacherName); ?></div>
                        <div class="designation"><?php echo htmlspecialchars($teacherDetails); ?></div>
                        <div class="designation">BSc. University of Colombo</div>
                    </div>
                </div>
                <div class="class-name"><?php echo htmlspecialchars($subject) . " - " . htmlspecialchars($classYear); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Class ID - <?php echo htmlspecialchars($class_id); ?></div>
                <div class="class-time">
                    <i class="bx bx-time-five"></i>
                    <span><?php echo htmlspecialchars($classTime); ?></span>
                </div>

                <!-- Join Class Button -->
                <form method="post" action="" style="margin-top: 15px;">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                    <button type="submit" name="join_class" class="btn btn-primary">Join Class</button>
                </form>

                <h5 class="mt-4">Class Resources</h5>
                <div class="resources">
                    <h6>PDFs:</h6>
                    <?php
                    $pdfs = $conn->query("SELECT file_name, file_path FROM class_resource WHERE class_id = '$class_id' AND file_name LIKE '%.pdf'");
                    while ($pdf = $pdfs->fetch_assoc()) {
                        echo '<div><a href="' . $pdf['file_path'] . '" target="_blank">' . $pdf['file_name'] . '</a> | <a href="' . $pdf['file_path'] . '" download>Download</a></div>';
                    }
                    ?>

                    <h6 class="mt-3">Videos:</h6>
                    <?php
                    $videos = $conn->query("SELECT file_name, file_path FROM class_resource WHERE class_id = '$class_id' AND file_name NOT LIKE '%.pdf'");
                    while ($video = $videos->fetch_assoc()) {
                        echo '<div><a href="view_video.php?file=' . urlencode($video['file_path']) . '" target="_blank">Watch ' . $video['file_name'] . '</a></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/sidebar.js"></script>
</body>
</html>
