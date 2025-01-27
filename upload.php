<?php
$target_dir = "uploads/";
$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// Generate a unique filename by appending a formatted date and time
$unique_name = date('Ymd_His') . '_' . pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_FILENAME) . '.' . $imageFileType;
$target_file = $target_dir . $unique_name;

$uploadOk = 1;

// Check if the file is valid
if (isset($_POST["submit"])) {
    $fileMime = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
    if (strpos($fileMime, 'image/') !== false || strpos($fileMime, 'video/') !== false) {
        echo "File is valid - " . $fileMime . ".<br>";
        $uploadOk = 1;
    } else {
        echo "File is not a valid image or video.<br>";
        $uploadOk = 0;
    }
}

// Check file size (limit: 25MB)
if ($_FILES["fileToUpload"]["size"] > 25000000) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}

// Allow certain file formats
$allowedFormats = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'pdf', 'mp4', 'mkv', 'webm', 'avi'];
if (!in_array($imageFileType, $allowedFormats)) {
    echo "Sorry, file extension not allowed.<br>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($unique_name)) . " has been uploaded.<br>";
    } else {
        echo "Sorry, there was an error uploading your file.<br>";
    }
}

// Display the files from the directory
$files = scandir($target_dir);

// Filter out the '.' and '..' entries
$files = array_diff($files, array('.', '..'));

// Create an array to store files with their timestamps
$files_with_timestamps = [];

foreach ($files as $file) {
    // Extract the timestamp from the filename
    $timestamp_str = explode('_', $file)[0];
    $timestamp = strtotime($timestamp_str);
    $files_with_timestamps[$file] = $timestamp;
}

// Sort the files by timestamp in descending order
arsort($files_with_timestamps);

// Display the files
echo "<h3>Uploaded Files</h3>";
echo "<div id='gallery'>";

foreach ($files_with_timestamps as $file => $timestamp) {
    $filePath = $target_dir . $file;
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    echo "<div class='pictures'>";
    echo "<div class='image'>";

    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'svg'])) {
        echo "<img src='$filePath' alt='$file' style='max-width: none; border-radius: 5px; cursor: pointer;'>";
    } elseif (in_array($fileExtension, ['mp4', 'avi', 'webm', 'mkv'])) {
        echo "<video controls>
                <source src='$filePath' type='video/$fileExtension'>
                Your browser does not support the video tag.
              </video>";
    }
    echo "</div>";
    echo "<p>Published by anonymous</p>";
    echo "</div>";
}

echo "</div>";
?>
