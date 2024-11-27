<?php
session_start();
// ডাটাবেস সংযোগ
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// কানেকশন চেক
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ফর্ম ডাটা প্রক্রিয়া
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ফর্ম ডেটা গ্রহণ
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // পাসওয়ার্ড হ্যাশিং

    // প্রোফাইল ছবি আপলোড
    $profile_picture = $_FILES['profile_picture']['name'];
    $target_dir = "uploads/"; 
    $target_file = $target_dir . basename($profile_picture);
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = $_FILES['profile_picture']['type'];



    // ফাইল যাচাই
    if (!in_array($file_type, $allowed_types)) {
        echo "Only JPG, PNG, and GIF files are allowed.";
        exit;
    }

    // ফাইল সাইজ চেক
    if ($_FILES['profile_picture']['size'] > 5000000) { 
        echo "File is too large.";
        exit;
    }
    
    // ফাইল আপলোড করা
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        // SQL ইনসার্ট করার প্রস্তুতি
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, dob, address, profile_picture, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fullname, $email, $phone, $dob, $address, $target_file, $password);

        // এক্সিকিউট করা
        if ($stmt->execute()) {
            echo "Registration successful!";
            $_SESSION['user_id'] = $stmt->insert_id; // ইউজারের ID সেশন এ সেট করা
            header("Location: profile.php"); // প্রোফাইল পেজে রিডাইরেক্ট করা
        } else {
            echo "Error: " . $stmt->error;
        }

        // স্টেটমেন্ট বন্ধ করা
        $stmt->close();
    } else {
        echo "Error uploading profile picture.";
    }
}

$conn->close();
?>
