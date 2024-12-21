<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "qldoanmypham";

$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tendangnhap = $_POST['tendangnhap'] ?? '';
    $matkhau = $_POST['matkhau'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE tendangnhap = ? AND matkhau = ?");
    $stmt->bind_param("ss", $tendangnhap, $matkhau);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lưu ID người dùng vào session
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['idnguoidung'];
        $_SESSION['tendangnhap'] = $tendangnhap;
        if($user['vaitro'] == 0)
        header("Location: home-da-dang-nhap.php");
        else
        header("Location: admin.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
        header("Location: home.php");
        exit;
    }
    $stmt->close();
}

$conn->close();
?>
