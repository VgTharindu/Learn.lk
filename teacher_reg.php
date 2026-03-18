<?php 
$fullName = $_POST['fullName'];
$nic = $_POST['nic'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$alStream = $_POST['alStream'];
$t_id = $_POST['teacherId'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$description = $_POST['description'];

// File upload
$target_dir = "uploads/";
$profile_pic = $_FILES["profile_pic"];

if ($profile_pic["error"] === UPLOAD_ERR_OK) {
    $file_name = time() . "_" . basename($profile_pic["name"]);
    $target_file = $target_dir . $file_name;

    if (!move_uploaded_file($profile_pic["tmp_name"], $target_file)) {
        $target_file = null;
    }
} else {
    $target_file = null;
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
if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);	
} else {
    // Update query to remove confirmPassword field from database insert
    $stmt = $conn->prepare("INSERT INTO teacher (teacher_id, t_name, nic, detail, gender, phone, al_stream, email, password, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $t_id, $fullName, $nic, $description, $gender, $phone, $alStream, $email, $hashedPassword, $target_file);
    
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
