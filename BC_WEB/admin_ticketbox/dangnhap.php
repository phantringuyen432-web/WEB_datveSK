<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
    if(isset($_GET['error']))
    {
        echo "<script> alert('".$_GET['error']."'); </script>";
    }
    session_start();
    include "include/connect.php";
    $thongbao="";
    if(isset($_POST['dangnhap'])){
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $sql = "select * from NguoiDung where Email='$email' and MatKhau='$pass'";
        $result = mysqli_query($conn,$sql);
        if (mysqli_num_rows($result)>0)
        {
            $row=mysqli_fetch_array($result);
            if($row['VaiTro']==="admin")
            {
                echo "Tài khoản chính xác";
                //Nếu tài khoản chính xác thì lấy email và tên người dùng
                $_SESSION['email']=$_POST['email'];
                //$_SESSION['MaNguoiDung']=$row['MaNguoiDung'];
                $_SESSION['HoTen']=$row['HoTen'];
                header('location: https://ticketbox.gt.tc/admin_ticketbox/dashboard.php');
                exit();
            }
        }
        else
        {
            $thongbao="Tài khoản hoặc mật khẩu không chính xác";
        }
    }
?>


<link rel="stylesheet" href="style.css">

<div class="khung_dn">        
    <div class="dangnhap">
        <form action="" method="POST">
            <h1>Đăng nhập</h1>
            <?php
                if ($thongbao!="")
                {
                    echo "<p style='color:red; font-weight:bold;'>$thongbao</p>";
                }
            ?>
            <label for="">Email</label>
            <input type="email" name="email" id="" placeholder="Email">
            <br><label for="">Mật khẩu</label>
            <input type="password" name="password" id="" placeholder="Mật khẩu">
            <br><input type="submit" name="dangnhap" value="Đăng nhập">
        </form>  
    </div>
</div>