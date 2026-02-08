<?php
    include "include/header.php";
    if (!isset($_SESSION['email']))
    {
        header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
        exit(); //Thoát không chạy các câu lệnh phía sau
    }
    include "include/connect.php";
    $sql = "select * from NguoiDung where vaitro='admin' ";
    $result = mysqli_query($conn,$sql);
    $sql1 = "select * from NguoiDung where vaitro='khachhang' ";
    $result1 = mysqli_query($conn,$sql1);

?>
<h1>Quản lý người dùng hệ thống</h1>
<h2>Tài khoản người quản trị</h2>
                
                
<table class="quantri">
    <tr>
        <th class="id">ID</th>
        <th class="ten">Họ tên</th>
        <th class="email">Email</th>
        <th class="sdt">Số điện thoại</th>
        <th class="ngaytao">Ngày tạo</th>

    <tr>
    <?php
        while ($row=mysqli_fetch_array($result))
        {
    ?>
    <tr>
            <!-- code html nhập bảng admin-->
         <td> <?php echo $row['MaNguoiDung']?> </td>
         <td> <?php echo $row['HoTen']?> </td>
         <td> <?php echo $row['Email']?> </td>
         <td> <?php echo $row['SoDienThoai']?> </td>
         <td> <?php echo $row['NgayTao']?> </td>
         
    </tr>
        
    <?php } ?>
</table>

<h2>Tài khoản người dùng</h2>
<table class="nguoidung">
    <tr>
        <th class="id">ID</th>
        <th class="ten">Họ tên</th>
        <th class="email">Email</th>
        <th class="sdt">Số điện thoại</th>
        <th class="ngaytao">Ngày tạo</th>

    <tr>
    <?php
        while ($row=mysqli_fetch_array($result1))
        {
    ?>
    <tr>
            <!-- code html nhập bảng admin-->
         <td> <?php echo $row['MaNguoiDung']?> </td>
         <td> <?php echo $row['HoTen']?> </td>
         <td> <?php echo $row['Email']?> </td>
         <td> <?php echo $row['SoDienThoai']?> </td>
         <td> <?php echo $row['NgayTao']?> </td>
         
    </tr>
        
    <?php } ?>
</table>

<?php
    include "include/footer.php";
?>
