<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['MaNguoiDung'])) {
    header("Location: dangnhap.php");
    exit;
}

if (isset($_GET['id'])) {
    $MaVe = intval($_GET['id']);
    $MaNguoiDung = $_SESSION['MaNguoiDung'];

    //  Chỉ cho phép xóa vé thuộc người đang đăng nhập và CHƯA THANH TOÁN
    $sql = "DELETE FROM Ve 
            WHERE MaVe = '$MaVe' 
            AND MaNguoiDung = '$MaNguoiDung' 
            AND TrangThai = 'chuathanhtoan'";

    if ($conn->query($sql)) {
        echo "<script>
            alert('🗑️ Xóa vé thành công!');
            window.location.href='vecuatoi.php';
        </script>";
    } else {
        echo "❌ Lỗi khi xóa vé: " . $conn->error;
    }
} else {
    header("Location: vecuatoi.php");
    exit;
}
?>
