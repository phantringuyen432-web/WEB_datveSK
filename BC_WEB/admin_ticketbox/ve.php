<?php
    include "include/header.php";
    if (!isset($_SESSION['email']))
    {
        header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
        exit(); //Thoát không chạy các câu lệnh phía sau
    }
    include "include/connect.php";
    $sql= "select * from Ve";
    $result=mysqli_query($conn,$sql);


?>
<h1>Quản lý vé</h1>
<table class="ve">
    <tr>
        <th class="mave">ID</th>
        <th class="sukien">Sự kiện</th>
        <th class="ghe">Ghế</th>
        <th class="ngaydat">Ngày đặt</th>
        <th class="thanhtoan">Số tiền thanh toán</th> 
        <th class="trangthai">Trạng thái</th>  
                       
    </tr>

    <?php
        while ($row=mysqli_fetch_array($result))
        {
    ?>
    <tr>
        <td> <?php echo $row['MaVe'] ?> </td>
        <td> <?php $ma =$row['MaSuKien']; $row2=mysqli_fetch_array(mysqli_query($conn,"select TenSuKien from SuKien where MaSuKien='$ma'"));
                    echo $row2['TenSuKien'];
         ?> 
         </td>
         <td> <?php $maghe=$row['MaGhe']; $row3= mysqli_fetch_array(mysqli_query($conn,"select CONCAT(DayGhe, SoGhe) AS KH from Ghe where MaGhe='$maghe'")); 
         echo $row3['KH']; ?> 
         </td>
        
        <td> <?php echo date("d-m-Y H:i:s",strtotime($row['NgayDat'])); ?> </td>
        <td> <?php echo $row['SoTienThanhToan'] ?> </td>
        <td> <?php echo $row['TrangThai'] ?> </td>
    </tr>
    <?php } ?>

</table>    
<?php
    include "include/footer.php";
?>