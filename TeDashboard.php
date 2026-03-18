<?php
session_start();

// ✅ Check if teacher is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    echo "<script>alert('Access denied. Please log in as a teacher.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

$teacher_id = $_SESSION['user_id'];

// ✅ Connect to database
$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch teacher details
$stmt = $conn->prepare("SELECT t_name, profile_pic FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();
$conn->close();

// ✅ Fallbacks
$profileImage = !empty($teacher['profile_pic']) ? $teacher['profile_pic'] : 'img/default_profile.png';
$teacherName = htmlspecialchars($teacher['t_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn.lk</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/Tdashboard.css">
    <link rel="stylesheet" href="css/Sdashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
                    <img src="<?php echo $profileImage; ?>" alt="profile image">
                    <div class="profile_content">
                        <div class="name"><?php echo $teacherName; ?></div>
                        <div class="designation">Teacher</div>
                    </div>
                </div>
                <i class="bx bx-log-out" id="log_out"><a href="login.html"></a></i>
            </li>
            <br><br>
            <li>
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
                    <span class="link_name">Manage class</span>
                </a>
                <span class="tooltip">Manage class</span>
            </li>
            <!--<li>
                <a href="TeacherExamCreate.php">
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
        <div class="page-top">
            <img src="logo/Black and orange Minimalist Education Logo (4).png">
            <div class="main-theam">The best way to Learn from Home<br>
                <span>Get ready to make your path . . .</span>
            </div>
            <!--<div class="contac">
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
            </div>-->
        </div><br>

        <section class="main-course">
            <h3>Create class</h3>
            <div class="T-course-box">
                <ul>
                    <li class="active">Create you'r class hear . . .</li>
                </ul>
                <div class="T-course">
                    <div class="T-box">
                        <form action="class.php"  method="post">
                            <h5>Class Name</h5>
                            <p>
                                <input type="text" placeholder="(Subject Name)-(class specific)" name="classname">
                            </p><br>
                            <h5>Date & Time</h5>
                            <p>
                                <input type="text" placeholder="(Monday)-(8.00 AM - 12.00 PM)" name="date&time">
                            </p><br>
                            <h5>Select Year</h5>
                            <p>
                                <select required name="year">
                                    <option value="">Select Year</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                </select>
                            </p><br>
                            <h5>Select Subject</h5>
                            <p>
                                <select required id="sub" name="subject" onchange="class_id()">
                                    <option value="">Select Subject</option>
                                    <option value="Biology">Biology</option>
                                    <option value="Chemistry">Chemistry</option>
                                    <option value="Applied Maths">Applied Maths</option>
                                    <option value="pure Maths">pure Maths</option>
                                    <option value="Physic">Physic</option>
                                    <option value="Accounting">Accounting</option>
                                    <option value="Business Studies">Business Studies</option>
                                    <option value="Economics">Economics</option>
                                    <option value="Engineering Technology">Engineering Technology</option>
                                    <option value="Bio System Technology">Bio System Technology</option>
                                    <option value="Science For Technology">Science For Technology</option>
                                    <option value="Information communication Technology">Information communication Technology</option>
                                </select>
                            </p><br>
                            <h5>Class ID</h5>
                            <p>
                                <input type="text" name="classid" id="clzId">
                            </p><br> 
                            
                            <button type="submit">Create</button>
                        </form>
                    </div><br><br>
                </div><br>
            </div><br><br>
        </section>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="js/sidebar.js"></script>
    <script src="js/class_id.js"></script>
</body>
</html>