<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    echo "<script>alert('Access denied. Please log in as a teacher.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

$teacher_id = $_SESSION['user_id'];
$class_id = $_GET['class_id'] ?? '';

$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get teacher info
$stmt = $conn->prepare("SELECT t_name, detail, profile_pic FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$teacher_result = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get class info
$stmt = $conn->prepare("SELECT class_name, class_datetime, year, subject FROM class WHERE class_id = ?");
$stmt->bind_param("s", $class_id);
$stmt->execute();
$class_result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id_post = $_POST['class_id'] ?? '';
    if (!empty($class_id_post)) {
        $status = null;
        if (isset($_POST['start_class'])) {
            $status = 'started';
        } elseif (isset($_POST['stop_class'])) {
            $status = 'stopped';
        }
        if ($status) {
            $update_stmt = $conn->prepare("UPDATE class SET class_status = ? WHERE class_id = ?");
            $update_stmt->bind_param("ss", $status, $class_id_post);
            $update_stmt->execute();
            $update_stmt->close();
            // Reload page to reflect status change
            header("Location: T_class_inside.php?class_id=" . urlencode($class_id_post));
            exit();
        }
    }
}


$conn->close();
$profileImage = !empty($teacher_result['profile_pic']) ? $teacher_result['profile_pic'] : 'img/default_profile.png';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn.lk</title>
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/Sdashboard.css">
    <link rel="stylesheet" href="css/T_class_inside.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <div class="logo_details">
        <i class="icon"></i>
        <div class="logo_name">Learn . LK</div>
        <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
        <li class="profile">
            <div class="profile_details">
                <img src="<?php echo $profileImage; ?>" alt="profile image">
                <div class="profile_content">
                    <div class="name"><?php echo htmlspecialchars($teacher_result['t_name']); ?></div>
                    <div class="designation">Teacher</div>
                </div>
            </div>
            <i class="bx bx-log-out" id="log_out"></i>
        </li><br><br>
        <li>
            <a href="TeDashboard.php">
                <i class="bx bx-grid-alt"></i>
                <span class="link_name">Dashboard</span>
                <span class="tooltip">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="TeacherClass.php">
                <i class="bx bx-store-alt"></i>
                <span class="link_name">Manage Class</span>
                <span class="tooltip">Manage Class</span>
            </a>
        </li>
        <!--<li>
            <a href="#">
                <i class="bx bx-spreadsheet"></i>
                <span class="link_name">Exam</span>
                <span class="tooltip">Exam</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="bx bx-bar-chart"></i>
                <span class="link_name">Attendance</span>
                <span class="tooltip">Attendance</span>
            </a>
        </li>-->
        <li>
            <a href="Profile_Setting.php">
                <i class="bx bx-cog"></i>
                <span class="link_name">Settings</span>
                <span class="tooltip">Settings</span>
            </a>
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

<section class="home-section">
    <section class="main-course">
        <div class="course-box">
            <h3>Manage Class Resources</h3>

            <div class="box1">
                <div class="profile_details">
                    <img src="<?php echo $profileImage; ?>" alt="profile image">
                    <div class="profile_content">
                        <div class="name"><?php echo htmlspecialchars($teacher_result['t_name']); ?></div>
                        <div class="designation"><?php echo htmlspecialchars($teacher_result['detail']); ?></div>
                        <div class="designation">bsc. university of colombo</div>
                    </div>
                </div>
                <div class="class-name">
                    <?php echo htmlspecialchars($class_result['subject']) . ' - ' . htmlspecialchars($class_result['year']) . ' &nbsp;&nbsp; ' . htmlspecialchars($class_result['class_name']) . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Class ID - ' . htmlspecialchars($class_id); ?>
                </div>
                <div class="class-time">
                    <i class="bx bx-time-five"></i>
                    <span><?php echo htmlspecialchars($class_result['class_datetime']); ?></span>
                </div>
                <!--<div class="text-center mt-3">
                    <a href="T_class_inside.php?class_id=<?php echo htmlspecialchars($class_id); ?>" class="btn btn-primary">Enroll</a>
                </div>-->
                <!-- Buttons to start and stop class -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                    <button type="submit" name="start_class" class="btn btn-primary">Start Class</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                    <button type="submit" name="stop_class" class="btn btn-danger">Stop Class</button>
                </form>
                <br><br>

                <br><br>

                <form action="upload_resource.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
                    <label>Upload PDF or Video:</label><br>
                    <input type="file" name="resource_file" accept="application/pdf,video/*" required>
                    <button type="submit" class="btn btn-upload">Upload</button>
                </form><br>

                <div class="resources">
                    <h5>Uploaded PDFs</h5>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
                    $stmt = $conn->prepare("SELECT id, file_name, file_path FROM class_resource WHERE class_id = ? AND file_name LIKE '%.pdf'");
                    $stmt->bind_param("s", $class_id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($row = $res->fetch_assoc()): ?>
                        <div>
                            <a href="<?php echo $row['file_path']; ?>" target="_blank"><?php echo $row['file_name']; ?></a>
                            <form method="post" action="delete_resource.php" style="display:inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    <?php endwhile; $stmt->close(); ?>

                    <h5 class="mt-4">Uploaded Videos</h5>
                    <?php
                    $stmt = $conn->prepare("SELECT id, file_name, file_path FROM class_resource WHERE class_id = ? AND file_name NOT LIKE '%.pdf'");
                    $stmt->bind_param("s", $class_id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($row = $res->fetch_assoc()): ?>
                        <div>
                            <a href="<?php echo $row['file_path']; ?>" target="_blank"><?php echo $row['file_name']; ?></a>
                            <form method="post" action="delete_resource.php" style="display:inline">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">&nbsp;&nbsp;
                                <button type="submit" class="delete">Delete</button>
                            </form>
                        </div>
                    <?php endwhile; $stmt->close(); $conn->close(); ?>
                </div>
            </div>
        </div>
    </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/sidebar.js"></script>
</body>
</html>
