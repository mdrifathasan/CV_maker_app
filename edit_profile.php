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

// সেশন চেক করা
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

// ফর্ম সাবমিট হলে আপডেট করা
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $profile_picture = $_FILES['profile_picture']['name'];
    $target_file = "uploads/" . basename($profile_picture);

    if (!empty($profile_picture)) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        $target_file = $user['profile_picture'];
    }

    // আপডেট SQL কোড
    $update_sql = "UPDATE users SET fullname=?, email=?, phone=?, dob=?, address=?, profile_picture=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssi", $fullname, $email, $phone, $dob, $address, $target_file, $user_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
        header("Location: profile.php");
    } else {
        echo "Error updating profile!";
    }

    $stmt->close();
}

// প্রোফাইল ফর্ম দেখানো
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
    <input type="date" name="dob" value="<?php echo $user['dob']; ?>" required>
    <textarea name="address" required><?php echo $user['address']; ?></textarea>
    <input type="file" name="profile_picture">
    <button type="submit">Update Profile</button>
</form>

<?php
$conn->close();
?>
