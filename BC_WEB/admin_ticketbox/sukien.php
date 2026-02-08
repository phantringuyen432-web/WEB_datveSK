<?php
    include "include/header.php";
    if (!isset($_SESSION['email']))
    {
        header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
        exit(); //Thoát không chạy các câu lệnh phía sau
    }
    include "include/connect.php";
    $search="";
    $sql="";
    if(isset($_GET['search']))
    {
        $search=$_GET['search'];
        $sql = "select * from SuKien where TenSuKien like '%$search%'";
    }
    else
    {
         $sql = "select * from SuKien";
    }
    
    $result = mysqli_query($conn,$sql);


?>
<h1>Quản lý sự kiện</h1>
<div class="search">
    <form action="" method="GET">
        <input type="button" value="+ Thêm sự kiện" onclick="window.location.href='addsk.php'"> 
        <div style="margin-top: 20px;">
            <span style="color: #2DC275; font-weight: bold;" >Search</span>
            <input type="text" class="search_value"
            name="search" placeholder="Tìm kiếm theo tên sự kiện . . ." value="<?php if(isset($_GET['search'])) echo $_GET['search']; else echo "" ?>">
            <input type="submit"  value="Tìm kiếm" >
        </div>
    </form>   
</div>
<table class="sukien">
    <tr>
        <th class="id">ID</th>
        <th class="ten">Tên sự kiện</th>
        <th class="time">Thời gian</th>
        <th class="dd">Địa điểm</th>
        <th class="hd">Hành động</th>
    </tr>

    <?php 
        while ($row=mysqli_fetch_array($result))
        {
    ?>
        <tr>
            <td><?php echo $row['MaSuKien'] ?></td>
            <td><?php echo $row['TenSuKien'] ?></td>
            <td><?php echo $row['NgayGio'] ?></td>
            <td><?php echo $row['DiaDiem'] ?></td>
            <td class="action">
                <input type="button" value="Sửa" id="sua" onclick="window.location.href='edit.php?id=<?php echo $row['MaSuKien']; ?>'">
                <input type="button" value="Xoá" id="xoa"
                        onclick="if(confirm('Bạn có chắc chắn muốn xoá sự kiện này không?')) //nếu người dùng chấp nhận thì xoá
                        window.location.href='deletesk.php?id=<?php echo $row['MaSuKien']; ?>'">
                <input type="button" value="Chi tiết" id="xemchitiet" onclick="window.location.href='chitietsk.php?id=<?php echo $row['MaSuKien']; ?>'">
            </td>

        </tr>
            


    <?php 
        }
    ?>
</table>




<?php
    include "include/footer.php";
?>