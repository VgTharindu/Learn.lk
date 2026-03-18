<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    echo "<script>alert('Please log in as a teacher.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

$teacher_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get teacher info
$stmt_teacher = $conn->prepare("SELECT t_name, detail, profile_pic FROM teacher WHERE teacher_id = ?");
$stmt_teacher->bind_param("s", $teacher_id);
$stmt_teacher->execute();
$result_teacher = $stmt_teacher->get_result();
$teacher = $result_teacher->fetch_assoc();
$stmt_teacher->close();

// Get classes for this teacher
$stmt_class = $conn->prepare("SELECT c.* FROM class c INNER JOIN t_class t ON c.class_id = t.class_id WHERE t.teacher_id = ?");
$stmt_class->bind_param("s", $teacher_id);

if ($stmt_class->execute()) {
    $class_result = $stmt_class->get_result();
} else {
    $class_result = null;
}

$stmt_class->close();

$profileImage = !empty($teacher['profile_pic']) ? $teacher['profile_pic'] : 'img/default_profile.png';
$teacherName = $teacher['t_name'];
$teacherDetails = $teacher['detail'];

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Learn.lk</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/myClasses.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"crossorigin="anonymous"/>
</head>
<body>
    <!--sidebar-->
    <div class="sidebar">
        <div class="logo_details">
            <i class="icon"></i>
            <div class="logo_name">Learn . LK</div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li class="profile">
                <div class="profile_details">
                   <img src="<?php echo $profileImage; ?>" alt="profile image" style="width: 60px; height: 60px; border-radius: 50%;">
                    <div class="profile_content">
                        <div class="name"><?php echo htmlspecialchars($teacherName); ?></div>
                        <div class="designation">Teacher</div>
                    </div>
                </div>
                <i class="bx bx-log-out" id="log_out"></i>
            </li>
            <br /><br />
            <li>
                <a href="TeDashboard.php">
                    <i class="bx bx-grid-alt"></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="TeacherClass.php">
                    <i class="bx bx-store-alt"></i>
                    <span class="link_name">My Classes</span>
                </a>
                <span class="tooltip">My Classes</span>
            </li>
            <!--<li>
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
            </li>-->
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

    <!--logo & name-->
    <section class="home-section">
        <!--<div class="page-top">
            <img src="logo/Black and orange Minimalist Education Logo (4).png" />
            <div class="main-theam">
                The best way to Learn from Home<br />
                <span>Get ready to make your path . . .</span>
            </div>
            <div class="contac">
                <div class="con con1">
                    <i class="bx bx-world"></i>
                    <span> www.Learn.lk</span>
                </div>
                <div class="con">
                    <i class="bx bxl-facebook"></i>
                    <span> www.facebook.com</span>
                </div>
                <div class="con">
                    <i class="bx bxl-google"></i>
                    <span>learnlk.com</span>
                </div>
                    <div class="con">
                    <i class="bx bxl-whatsapp"></i>
                    <span>+9477 12 34 678</span>
                </div>
            </div>
        </div>-->
        <br/>

      <!--course list-->
        <section class="main-course">
            <div class="course-box">
                <h3>Your classes</h3>

               <?php
                    if ($class_result && $class_result->num_rows > 0) {
                        while ($class = $class_result->fetch_assoc()) {
                            echo '
                            <div class="box1">
                                <div class="profile_details">
                                    <img src="' . $profileImage . '" alt="profile image" />
                                    <div class="profile_content">
                                        <div class="name">' . htmlspecialchars($teacherName) . '</div>
                                        <div class="designation">' . htmlspecialchars($teacherDetails) . '</div>
                                        <div class="designation">BSc. University of Colombo</div>
                                    </div>
                                </div>
                                <div class="class-name">' . htmlspecialchars($class['subject']) . ' - ' . htmlspecialchars($class['year']) . ' &nbsp;&nbsp; ' . htmlspecialchars($class['class_name']) . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Class ID - ' . htmlspecialchars($class['class_id']) . '</div>
                                <div class="class-time">
                                    <i class="bx bx-time-five"></i>
                                    <span>' . htmlspecialchars($class['class_datetime']) . '</span>
                                </div>
                                <a href="T_class_inside.php?class_id=' . urlencode($class['class_id']) . '" class="join-btn">Enroll</a>
                            </div>
                            <br />';
                        }
                    } else {
                        echo "<p style='color: red;'>No classes found for this teacher.</p>";
                    }
                ?>

                <br /><br />
            
            </div>
        </section>
    </section>

    <!-- Scripts -->
    <script src="js/sidebar.js"></script>
</body>
</html>
<!--https://youtu.be/MAqjLU3Taac?si=mULzFrRtw0TtlxDH section video link-->
<!--https://themesbrand.com/skote-cakephp/layouts/icons-boxicons.html boxicons link-->
