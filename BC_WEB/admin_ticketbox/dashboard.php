<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
    include "include/header.php";
    //session_start(); đã tồn tại ở header.php
    if (!isset($_SESSION['email']))
    {
        header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
        exit(); //Thoát không chạy các câu lệnh phía sau
    }

    
    include "include/connect.php";
    $sql = "select count(MaNguoiDung) as SL from NguoiDung ";
    $result = mysqli_query($conn,$sql);
    $row=mysqli_fetch_array($result);
    $nguoidung=$row['SL'];

    $sql1 = "select count(MaVe) as SL, sum(SoTienThanhToan) as DoanhThu from Ve ";
    $result1 = mysqli_query($conn,$sql1);
    $row1=mysqli_fetch_array($result1);
    $ve=$row1['SL'];
    $doanhthu=$row1['DoanhThu'];

    $sql2 = "select count(MaSuKien) as SL from SuKien ";
    $result2 = mysqli_query($conn,$sql2);
    $row2=mysqli_fetch_array($result2);
    $sukien=$row2['SL'];





?>
<h1>Dashboard</h1>
<input id="themsk"type="button" value="+ Tạo sự kiện" onclick="window.location.href='addsk.php'">
<div class="content">
    <div class="cards">
        <div class="card" onclick="window.location.href='ve.php'">
            <h3>Tổng số vé</h3>
            <p> <?php echo $ve?> </p>
        </div>
        <div class="card" onclick="window.location.href='sukien.php'">
            <h3>Sự kiện</h3>
            <p> <?php echo $sukien?> </p>
        </div>
        <div class="card" onclick="window.location.href='nguoidung.php'">
            <h3>Người dùng</h3>
            <p> <?php echo $nguoidung?> </p>
        </div>
        <div class="card" onclick="window.location.href='thongke.php'">
            <h3>Doanh thu</h3>
            <p id="doanhthu"> <?= number_format($doanhthu, 0, ',', '.') ?>đ </p>
        </div>
    </div>
</div>




<?php
    include "include/footer.php";
?>