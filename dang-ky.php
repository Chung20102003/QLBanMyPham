<?php
session_start(); // Bắt đầu phiên làm việc

// Kết nối tới CSDL
include 'db.php';

// Kiểm tra khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = $_POST['matkhau'];
    $sdt = $_POST['sdt'];

    // Kiểm tra xem tên người dùng đã tồn tại chưa
    $stmt = $pdo->prepare("SELECT * FROM nguoidung WHERE tendangnhap = :tendangnhap");
    $stmt->bindParam(':tendangnhap', $tendangnhap);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Nếu tên người dùng đã tồn tại
        $_SESSION['message'] = "Đăng ký thất bại: Username đã tồn tại!";
        $_SESSION['message_type'] = 'error'; // Đánh dấu là lỗi
        header("Location: home.php#registerModal"); // Chuyển hướng về trang home.php và mở modal đăng ký
        exit;
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO nguoidung (hoten, tendangnhap, matkhau, sdt, email) VALUES (:hoten, :tendangnhap, :matkhau, :sdt, :email)");
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':tendangnhap', $tendangnhap);
        $stmt->bindParam(':matkhau', $matkhau);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            // Đăng ký thành công
            $_SESSION['message'] = "Đăng ký thành công!";
            $_SESSION['message_type'] = 'success'; // Đánh dấu là thành công
            header("Location: home.php#registerModal"); // Quay lại trang home.php
            exit;
        } else {
            // Lỗi trong khi thêm người dùng
            $_SESSION['message'] = "Đăng ký thất bại: Có lỗi xảy ra!";
            $_SESSION['message_type'] = 'error'; // Đánh dấu là lỗi
            header("Location: home.php#registerModal"); // Quay lại trang home.php và mở modal đăng ký
            exit;
        }
    }
}
?>
