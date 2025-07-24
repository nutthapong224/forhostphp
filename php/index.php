<?php
session_start();

$servername = "db2";      // ชื่อ service docker-compose
$dbuser = "testuser";
$dbpass = "testpass";
$dbname = "testdb";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli($servername, $dbuser, $dbpass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // เตรียม statement เพื่อป้องกัน SQL injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ตรวจสอบ password ด้วย password_verify
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user'] = $user;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password incorrect";
        }
    } else {
        $error = "User not found";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="post" action="">
    <input type="text" name="username" placeholder="Username" required autofocus><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<p><a href="create.php">Register new user</a></p>
</body>
</html>
