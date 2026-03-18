<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    echo "<script>alert('Access denied. Please log in as a student.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

$student_id = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle leave class request
if (isset($_POST['leave_class_id'])) {
    $leave_id = $_POST['leave_class_id'];
    $stmt = $conn->prepare("DELETE FROM st_class WHERE st_id = ? AND class_id = ?");
    $stmt->bind_param("ss", $student_id, $leave_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('You have left the class.'); window.location.href='myClasses.php';</script>";
    exit();
}

// Get student info
$stmt = $conn->prepare("SELECT st_name, profile_pic FROM student WHERE st_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

$profileImage = !empty($student['profile_pic']) ? $student['profile_pic'] : 'img/default_profile.png';
$studentName = htmlspecialchars($student['st_name']);

// Get enrolled classes
$query = "SELECT c.class_id, c.class_name, c.class_datetime, c.year, c.subject,
                 t.t_name, t.detail, t.profile_pic
          FROM class c
          JOIN t_class tc ON c.class_id = tc.class_id
          JOIN teacher t ON tc.teacher_id = t.teacher_id
          JOIN st_class sc ON c.class_id = sc.class_id
          WHERE sc.st_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$class_result = $stmt->get_result();
$stmt->close();
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
                    <img src="<?php echo $profileImage; ?>" alt="profile image" >
                    <div class="profile_content">
                        <div class="name"><?php echo $studentName; ?></div>
                        <div class="designation">Student</div>
                    </div>
                </div>
                <i class="bx bx-log-out" id="log_out"></i>
            </li>
            <br /><br />
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
                <h3>Your Paid Classes</h3>
                <?php while ($class = $class_result->fetch_assoc()): ?>
                <div class="box1">
                    <div class="profile_details">
                    <img src="<?php echo !empty($class['profile_pic']) ? $class['profile_pic'] : 'img/default_profile.png'; ?>" alt="profile image" />
                    <div class="profile_content">
                        <div class="name"><?php echo htmlspecialchars($class['t_name']); ?></div>
                        <div class="designation"><?php echo htmlspecialchars($class['detail']); ?></div>
                        <div class="designation">BSc. University of Colombo</div>
                    </div>
                    </div>
                    <div class="class-name">
                    <?php echo htmlspecialchars($class['class_name']) . ' - ' . htmlspecialchars($class['year']); ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php echo htmlspecialchars($class['class_id']); ?>
                    </div>
                    <div class="class-time">
                    <i class="bx bx-time-five"></i>
                    <span><?php echo htmlspecialchars($class['class_datetime']); ?></span>
                    </div>
                    <div class="btt">
                        
                       <!-- Link to S_class_inside.php -->
                        <a href="S_class_inside.php?class_id=<?php echo urlencode($class['class_id']); ?>" class="join-btn">Enrolled</a>


                        <!-- Leave button -->
                        <form method="post" style="display: inline;">
                        <input type="hidden" name="leave_class_id" value="<?php echo htmlspecialchars($class['class_id']); ?>">
                        <button type="submit" class="join-btn1">Leave</button>
                        </form>
                    </div>
                </div>
                <br>
                <?php endwhile; ?>
            </div>
        </section>
    </section>

    <!-- Scripts -->
    <script src="js/sidebar.js"></script>
</body>
</html>
<!--https://youtu.be/MAqjLU3Taac?si=mULzFrRtw0TtlxDH section video link-->
<!--https://themesbrand.com/skote-cakephp/layouts/icons-boxicons.html boxicons link-->
