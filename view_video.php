<?php
// Check if file path is provided
if (!isset($_GET['file'])) {
    echo "No video selected.";
    exit();
}

$videoPath = urldecode($_GET['file']);
if (!file_exists($videoPath)) {
    echo "Video not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Watch Video</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            background: black;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        video {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <video controls autoplay>
        <source src="<?php echo htmlspecialchars($videoPath); ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</body>
</html>
