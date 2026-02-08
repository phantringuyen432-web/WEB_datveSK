<?php
ob_start();
session_start();
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];
    $confirm = $_POST['confirm_matkhau'];
    $sdt = $_POST['sdt'];

    // Kiểm tra mật khẩu nhập lại 
    if ($matkhau !== $confirm) {
        echo "<script>alert('❌ Mật khẩu xác nhận không khớp!'); window.history.back();</script>";
        exit;
    }

    // Mã hóa mật khẩu
    $matkhau_hashed = password_hash($matkhau, PASSWORD_DEFAULT);

    //  Kiểm tra email 
    $check = $conn->query("SELECT * FROM NguoiDung WHERE Email='$email'");
    if ($check && $check->num_rows > 0) {
        echo "<script>alert('❌ Email đã được sử dụng!'); window.history.back();</script>";
        exit;
    }

    //  Thêm vào CSDL
    $sql = "INSERT INTO NguoiDung (HoTen, Email, MatKhau, SoDienThoai) 
            VALUES ('$hoten','$email','$matkhau_hashed','$sdt')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('🎉 Đăng ký thành công!'); window.location.href='dangnhap.php';</script>";
        exit;
    } else {
        echo "❌ Lỗi: " . $conn->error;
    }

}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="./dangky.css">
</head>
<body>
  <div class="register-container">
    <h2>Đăng ký tài khoản</h2>
    <form action="" method="post"> <!-- gửi form về chính file này -->
      <div class="form-group">
        <label for="hoten">Họ và tên:</label>
        <input type="text" id="hoten" name="hoten" placeholder="Nhập họ và tên" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Nhập email" required>
      </div>

      <div class="form-group">
        <label for="matkhau">Mật khẩu:</label>
        <input type="password" id="matkhau" name="matkhau" placeholder="Nhập mật khẩu" required>
      </div>

      <div class="form-group">
        <label for="confirm_matkhau">Xác nhận mật khẩu:</label>
        <input type="password" id="confirm_matkhau" name="confirm_matkhau" placeholder="Nhập lại mật khẩu" required>
      </div>

      <div class="form-group">
        <label for="sdt">Số điện thoại:</label>
        <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại" required>
      </div>

      <button type="submit" class="btn-register">Đăng ký</button>
    </form>

    <p class="login-link">
      Đã có tài khoản? <a href="https://ticketbox.gt.tc/WEB/php/dangnhap.php">Đăng nhập</a> 
    </p>
  </div>
</body>
</html>
