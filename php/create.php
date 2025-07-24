<?php
session_start();

$servername = "db2";
$dbuser = "testuser";
$dbpass = "testpass";
$dbname = "testdb";

$conn = new mysqli($servername, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($username) || empty($password)) {
        $error = "กรุณากรอก username และ password";
    } elseif ($password !== $password_confirm) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจสอบว่ามี username นี้ในระบบหรือยัง
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username นี้มีผู้ใช้แล้ว";
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);

            if ($stmt->execute()) {
                $success = "สมัครสมาชิกเรียบร้อย สามารถล็อกอินได้เลย";
            } else {
                $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h2>Register</h2>
<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>
<form method="post" action="">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="password" name="password_confirm" placeholder="Confirm Password" required><br><br>
    <button type="submit">Register</button>
</form>
<p><a href="index.php">กลับไปหน้า Login</a></p>
</body>
</html>
