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

// ইউজারের সেশন চেক
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// ইউজারের তথ্য ডাটাবেস থেকে নেওয়া
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ইউজারের প্রোফাইল তথ্য দেখানো
if ($user) {
    echo "<h2>Welcome, " . $user['fullname'] . "</h2>";
    echo "<img src='" . $user['profile_picture'] . "' alt='Profile Picture' width='150' height='150'>";
    echo "<p>Email: " . $user['email'] . "</p>";
    echo "<p>Phone: " . $user['phone'] . "</p>";
    echo "<p>Date of Birth: " . $user['dob'] . "</p>";
    echo "<p>Address: " . $user['address'] . "</p>";
    echo "<a href='edit_profile.php'>Edit Profile</a>";
} else {
    echo "No user data found!";
}

$conn->close();
?>
