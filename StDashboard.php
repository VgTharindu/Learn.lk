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

// ✅ Handle join class request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_class_id'])) {
    $class_id = $_POST['join_class_id'];

    // Check if already joined
    $check = $conn->prepare("SELECT * FROM st_class WHERE st_id = ? AND class_id = ?");
    $check->bind_param("ss", $student_id, $class_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows == 0) {
        // Insert student and class only (assumes st_class has only st_id and class_id)
        $insert = $conn->prepare("INSERT INTO st_class (st_id, class_id) VALUES (?, ?)");
        $insert->bind_param("ss", $student_id, $class_id);
        $insert->execute();
        $insert->close();
        echo "<script>alert('You have successfully joined the class.'); window.location.href='StDashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('You are already enrolled in this class.');</script>";
    }
    $check->close();
}

$stmt = $conn->prepare("SELECT st_name, profile_pic FROM student WHERE st_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

$profileImage = !empty($student['profile_pic']) ? $student['profile_pic'] : 'img/default_profile.png';
$studentName = htmlspecialchars($student['st_name']);

$year = $_GET['year'] ?? null;
$subject = $_GET['subject'] ?? null;

$classes = [];
if ($year && $subject) {
    $query = "SELECT c.class_id, c.class_name, c.class_datetime, c.year, c.subject,
                     t.t_name, t.detail, t.profile_pic
              FROM class c
              JOIN t_class tc ON c.class_id = tc.class_id
              JOIN teacher t ON tc.teacher_id = t.teacher_id
              WHERE c.year = ? AND c.subject = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $year, $subject);
    $stmt->execute();
    $class_result = $stmt->get_result();
    while ($row = $class_result->fetch_assoc()) {
        $classes[] = $row;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Learn.lk</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/Sdashboard.css">
  <link rel="stylesheet" href="css/bottom.css">
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
                <a href="StudentExamView.php">
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
      
      </div>
    </div><br>
    
    <!--notice board-->
    <div class="notice-board"></div><br><br>

    <!--Course List-->
    <div class="main-top">
      <h1>Classes</h1>
    </div>
    <div class="main-skills">
      <div class="card">
        <i class="bx bxs-school"></i>
        <h3>Biology</h3>
        <p>join over one million student</p>
        <button>Get more</button>
      </div>
      <div class="card">
        <i class="bx bxs-school"></i>
        <h3>Mathematics</h3>
        <p>join over one million student</p>
        <button>Get more</button>
      </div>
      <div class="card">
        <i class="bx bxs-school"></i>
        <h3>Commerce</h3>
        <p>join over one million student</p>
        <button>Get more</button>
      </div>
      <div class="card">
        <i class="bx bxs-school"></i>
        <h3>Technology</h3>
        <p>join over one million student</p>
        <button>Get more</button>
      </div>
    </div>

    <!--Search class-->
    <section class="main-course">
      <h1>Join class</h1>
      <div class="course-box">
        <ul>
          <li class="active">Enter your Advance Level year & Subject . . .</li>
        </ul>
        <form method="get" action="StDashboard.php">
          <div class="course">
            <div class="box">
              <h4>Select Year</h4>
              <p>
                <select name="year" required>
                  <option value="">Select Year</option>
                  <option value="2025">2025</option>
                  <option value="2026">2026</option>
                  <option value="2027">2027</option>
                </select>
              </p>
            </div>
            <div class="box">
              <h4>Select Subject</h4>
              <p>
                <div class="dropdown-container">
                  <input type="text" name="subject" id="searchInput" class="search-box" placeholder="Search..." onfocus="showDropdown()">
                  <div id="dropdown" class="dropdown-list">
                    <div>Applied Math</div>
                    <div>pure Math</div>
                    <div>Chemistry</div>
                    <div>Physic</div>
                    <div>Biology</div>
                    <div>Accounting</div>
                    <div>Business Studies</div>
                    <div>Economics</div>
                    <div>Engineering Technology</div>
                    <div>Bio System Technology</div>
                    <div>Science For Technology</div>
                    <div>Information communication Technology</div>
                  </div>
                </div>
              </p>
            </div>
            <button type="submit">Search</button>
          </div>
        </form>
      </div>
    </section>

          <section class="main-course">
            <div class="course-box">
              <?php if ($year && $subject): ?>
                  <h3>Showing Classes for <?php echo htmlspecialchars($subject); ?> - <?php echo htmlspecialchars($year); ?></h3>
                  <?php if (count($classes) > 0): ?>
                      <?php foreach ($classes as $class): ?>
                        <div class="box1">
                          <div class="profile_details">
                            <img src="<?php echo !empty($class['profile_pic']) ? $class['profile_pic'] : 'img/default_profile.png'; ?>" alt="profile image">
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
                          <form method="post" onsubmit="return confirmJoin();">
                            <input type="hidden" name="join_class_id" value="<?php echo htmlspecialchars($class['class_id']); ?>">
                            <button type="submit" class="join-btn">Join</button>
                          </form>
                        </div>
                        <br>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <p>No classes found for selected year and subject.</p>
                  <?php endif; ?>
              <?php endif; ?>
            </div>
          </section>


    <hr>

    <!--<div class="footer">
      <p> &copy; 2025 Learn.lk. All rights reserved.</p>
      <div class="footer-icon">
        <div class="con con1">
          <i class="bx bx-world"></i>
        </div>
        <div class="con">
          <i class="bx bxl-facebook"></i>
        </div>
        <div class="con">
          <i class="bx bxl-google"></i>
        </div>
        <div class="con">
          <i class="bx bxl-whatsapp"></i>
        </div>
      </div>
      <p> Developed by <a href="#">@vgtharindu</a></p>
    </div>-->

  </section>
     
     
  <!-- Scripts -->
    <script>
      function confirmJoin() {
        return confirm("You want to join this class. You must pay Rs.3000.00 for month");
      }
    </script>

    <script>
      const searchInput = document.getElementById('searchInput');
      const dropdown = document.getElementById('dropdown');
      const items = dropdown.querySelectorAll('div');

      function showDropdown() {
        dropdown.style.display = 'block';
      }

      searchInput.addEventListener('input', () => {
        const filter = searchInput.value.toLowerCase();
        let hasMatch = false;

        items.forEach(item => {
          if (item.textContent.toLowerCase().includes(filter)) {
            item.style.display = 'block';
            hasMatch = true;
          } else {
            item.style.display = 'none';
          }
        });

        dropdown.style.display = hasMatch ? 'block' : 'none';
      });

      // Select item
      items.forEach(item => {
        item.addEventListener('click', () => {
          searchInput.value = item.textContent;
          dropdown.style.display = 'none';
        });
      });

      // Close when clicking outside
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown-container')) {
          dropdown.style.display = 'none';
        }
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="js/sidebar.js"></script>
</body>
</html>
<!--https://youtu.be/MAqjLU3Taac?si=mULzFrRtw0TtlxDH section video link-->
<!--https://themesbrand.com/skote-cakephp/layouts/icons-boxicons.html boxicons link-->
