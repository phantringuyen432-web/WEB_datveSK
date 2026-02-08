<?php
session_start();
include 'php/connect.php';
?>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="font/themify-icons/themify-icons.css">

<section class="header">
    <div class="logo">
        <a href="/WEB/index.php">
            <img src="/WEB/img/header-logo-white (1).png" alt="logo" class="ticket-box">
        </a>
    </div>

    <div class="search">
        <input type="text" id="search-text" placeholder="  Bạn tìm gì hôm nay?" class="search-text">
        <button id="search-button" class="search-button">
            Tìm kiếm <i class="search-icon ti-search"></i>
        </button>
    </div>

    <div class="my-ticket">
        <?php if (isset($_SESSION['HoTen'])): ?>
            <a href="/WEB/php/vecuatoi.php" class="ticket-link">Vé của tôi</a>
        <?php else: ?>
            <a href="/WEB/php/dangnhap.php" onclick="return alert('⚠️ Bạn cần đăng nhập để xem vé của mình!')">Vé của tôi</a>
        <?php endif; ?>
    </div>

    <div class="account">
        <?php if (isset($_SESSION['HoTen'])): ?>
            <strong><?php echo htmlspecialchars($_SESSION['HoTen']); ?></strong> |
            <a href="/WEB/php/dangxuat.php" class="logout">Đăng xuất</a>
        <?php else: ?>
            <a href="/WEB/php/dangnhap.php" class="login">Đăng nhập</a> |
            <a href="/WEB/php/dangky.php" class="erroll">Đăng ký</a>
        <?php endif; ?>
    </div>
</section>

<!-- Menu
<section class="main-menu">
    <ul class="menu-list">
        <li class="all">Tất cả thể loại</li>
        <li class="music">Âm nhạc</li>
        <li class="sport">Thể thao</li>
        <li class="course">Nghệ thuật</li>
        <li class="else">Khác</li>
    </ul>
</section> -->
