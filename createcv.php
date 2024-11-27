<?php
// Step 1: Database connection
$servername = "localhost"; // MySQL সার্ভার
$username = "root"; // MySQL ইউজারনেম (ডিফল্ট: root)
$password = ""; // MySQL পাসওয়ার্ড (ডিফল্ট: খালি)
$database = "cv_maker"; // ডাটাবেসের নাম

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Retrieve form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$religion = $_POST['religion'];
$nationality = $_POST['nationality'];
$marital_status = $_POST['marital_status'];
$hobbies = $_POST['hobbies'];
$languages = $_POST['languages'];
$address = $_POST['address'];

// Step 3: Prepare and execute SQL query
$sql = "INSERT INTO personal_info (full_name, email, mobile, dob, gender, religion, nationality, marital_status, hobbies, languages, address) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss", $full_name, $email, $mobile, $dob, $gender, $religion, $nationality, $marital_status, $hobbies, $languages, $address);

if ($stmt->execute()) {
    echo "Data saved successfully!";
} else {
    echo "Error: " . $conn->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
