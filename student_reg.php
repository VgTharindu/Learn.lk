<?php 
// Get form data
$fullName = $_POST['fullName'];
$address = $_POST['address'];
$dob = $_POST['dob'];
$nic = $_POST['nic'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$alYear = $_POST['alYear'];
$alStream = $_POST['alStream'];
$st_id = $_POST['studentId'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Handle profile picture upload
$target_dir = "uploads/";
$profile_pic = $_FILES["profile_pic"];
$target_file = null;

if ($profile_pic["error"] === UPLOAD_ERR_OK) {
    $file_name = time() . "_" . basename($profile_pic["name"]);
    $target_file = $target_dir . $file_name;

    if (!move_uploaded_file($profile_pic["tmp_name"], $target_file)) {
        $target_file = null; // upload failed
    }
}

// Password confirmation check
if ($password !== $confirmPassword) {
    echo "<script>alert('Passwords do not match.');</script>";
    echo "<script>window.history.back();</script>";
    exit();
}

// Hash the password before storing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Database connection
$conn = new mysqli('localhost','root','','new_learn_lk');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);	
} else {
    // Insert query without confirmPassword column
    $stmt = $conn->prepare("INSERT INTO student (st_id, st_name, dob, nic, address, gender, p_no, al_year, al_stream, email, password, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $st_id, $fullName, $dob, $nic, $address, $gender, $phone, $alYear, $alStream, $email, $hashedPassword, $target_file);
    
    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful.');</script>";
        echo "<script>window.location.href ='login.html';</script>";
    } else {
        echo "<script>alert('Error: Registration Failed.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
