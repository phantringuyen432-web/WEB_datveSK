<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đặt vé xem sự kiện Ticketbox</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <img width="160px" height="30px" src="image/ticketbox.png" alt="Ticketbox Logo">
        </div>
        <?php
            if(isset($_SESSION['email']))
            {
                echo "<div class='user_info'>Xin chào ". $_SESSION['email']. "!\t" ."<a href='dangxuat.php'>Thoát</a></div>";
            }
        ?>
    </div>

    <!-- Nội dung -->
    <div class="noidung">
        <div class="dsql">
            <h1>Admin TicketBox.vn</h1>
            <ul>
                <li class="item item1"><a href="dashboard.php">Dashboard</a></li>
                <li class="item item2"><a href="sukien.php">Sự kiện</a></li>
                <li class="item item3"><a href="ve.php">Vé</a></li>
                <li class="item item6"><a href="nguoidung.php">Người dùng</a></li>
                <li class="item item7"><a href="thongke.php">Thống kê</a></li>
            </ul>
        </div>

        <!-- Nội dung bên phải -->
        <div class="slide2">
