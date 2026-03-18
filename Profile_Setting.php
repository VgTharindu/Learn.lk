<?php
session_start();

if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['student', 'teacher'])) {
    echo "<script>alert('Access denied. Please log in.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$conn = new mysqli('localhost', 'root', '', 'new_learn_lk');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = $user_role === 'student' ? 'student' : 'teacher';
$id_field = $user_role === 'student' ? 'st_id' : 'teacher_id';
$name_field = $user_role === 'student' ? 'st_name' : 't_name';

// Fetch profile info
$stmt = $conn->prepare("SELECT * FROM $table WHERE $id_field = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profileImage = !empty($user_data['profile_pic']) ? $user_data['profile_pic'] : 'img/default_profile.png';
$name = $user_data[$name_field];
$email = $user_data['email'];
$address = $user_data['address'] ?? '';
$phone = $user_data['phone'] ?? $user_data['p_no'] ?? '';
$dob = $user_data['dob'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Learn.lk</title>
    <!-- Link Styles -->
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/profile_setting.css" />
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

    
    <section class="home-section">
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <div class="container light-style flex-grow-1 container-p-y">
                <h4 class="font-weight-bold py-3 mb-4">Account settings</h4>
                <div class="card overflow-hidden">
                    <div class="row no-gutters row-bordered row-border-light">
                        <div class="col-md-9">
                            <div class="card-body media align-items-center">
                                <img src="<?php echo $profileImage; ?>" alt class="d-block ui-w-80" style="width:80px;height:80px;">
                                <div class="media-body ml-4">
                                    <label class="btn btn-outline-primary">
                                        Upload new photo <input type="file" name="profile_pic" class="account-settings-fileinput">
                                    </label>
                                    <button type="button" class="btn btn-default md-btn-flat" onclick="window.location.reload()">Reset</button>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="user_role" value="<?php echo $user_role; ?>">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($address); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input type="date" class="form-control" name="dob" value="<?php echo $dob; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="new_password">
                                </div>
                                <div class="form-group">
                                    <label>Repeat New Password</label>
                                    <input type="password" class="form-control" name="repeat_password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-3">
                    <button type="submit" name="save" class="btn btn-primary">Save changes</button>
                    <button type="reset" class="btn btn-default">Cancel</button>
                    <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
                </div><br><br>
            </div>
        </form>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebar.js"></script>
</body>
</html>