<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
// ✅ Cài múi giờ Việt Nam để tránh sai lệch
   date_default_timezone_set('Asia/Ho_Chi_Minh');
    $now = date("Y-m-d H:i:s");
session_start();
include 'php/connect.php';

// 1️⃣ Lấy 4 sự kiện ngẫu nhiên cho "Dành cho bạn"
$sql_random = "SELECT * FROM SuKien ORDER BY RAND() LIMIT 4";
$random_events = $conn->query($sql_random);

// 2️⃣ Lấy 4 sự kiện mỗi loại
$sql_music = "SELECT * FROM SuKien WHERE MaLoai = 1 ORDER BY NgayGio ASC ";
$music_events = $conn->query($sql_music);

$sql_sport = "SELECT * FROM SuKien WHERE MaLoai = 2 ORDER BY NgayGio ASC ";
$sport_events = $conn->query($sql_sport);

$sql_art = "SELECT * FROM SuKien WHERE MaLoai = 3 ORDER BY NgayGio ASC ";
$art_events = $conn->query($sql_art);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font/themify-icons/themify-icons.css">
</head>

<body>
    <!-- header -->
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
                <a href="php/vecuatoi.php" class="ticket-link">Vé của tôi</a>
            <?php else: ?>
                <a href="php/dangnhap.php" onclick="return alert('⚠️ Bạn cần đăng nhập để xem vé của mình!')">Vé của tôi</a>
            <?php endif; ?>
        </div>
        <div class="account">
        <?php if (isset($_SESSION['HoTen'])): ?>
            <strong><?php echo $_SESSION['HoTen']; ?></strong> |
            <a href="https://ticketbox.gt.tc/WEB/php/dangxuat.php" class="logout">Đăng xuất</a>
        <?php else: ?>
            <a href="https://ticketbox.gt.tc/WEB/php/dangnhap.php" class="login">Đăng nhập</a> |
            <a href="https://ticketbox.gt.tc/WEB/php/dangky.php" class="erroll">Đăng ký</a>
        <?php endif; ?>
</div>


    </section>
    <!-- End header -->
     <!-- Menu -->
    <section class="main-menu">
      <ul class="menu-list">
            <li class="all">Tất cả thể loại</li>
            <li class="music">Âm nhạc</li>
            <li class="sport">Thể thao</li>
            <li class="course">Nghệ thuật</li>
            <li class="else">Khác</li>
      </ul>
    </section>
    <!-- End menu -->
    <!-- Content -->
    <section class="main-content">
        <!-- Slider -->
        <div class="slider">
            <div class="slides">
                <img src="img/56f671a503acf1e65046f7fcb4668976.png" alt="silder 1">
                <img src="img/vucattuong.png" alt="silder 2">
                <img src="img/vucattuong2.jpg" alt="silder 3">
                <img src="img/vu.jpg" alt="silder 4">
            </div>
            <button class="slide-btn prev">&#10094;</button>
            <button class="slide-btn next">&#10095;</button>
        </div>
        <!-- End slider -->
        <!-- Trend -->
        <h2>
            <i class="ti-star"></i>
            Sự kiện xu hướng
        </h2>
        <div class="trend-container">
            <button class="trend-btn prev">&#10094;</button>

            <div class="event-list">
                <div class="event-card">
                    <span class="number">1</span>
                    <img src="img/56f671a503acf1e65046f7fcb4668976.png" alt="Sự kiện 1">
                </div>
                <div class="event-card">
                    <span class="number">2</span>
                    <img src="img/vucattuong.png" alt="Sự kiện 2">
                </div>
                <div class="event-card">
                    <span class="number">3</span>
                    <img src="img/emxinhsayhi.webp" alt="Sự kiện 3">
                </div>
                <div class="event-card">
                    <span class="number">4</span>
                    <img src="img/water.webp" alt="Sự kiện 4">
                </div>
                <div class="event-card">
                    <span class="number">5</span>
                    <img src="img/anhtu.jpg" alt="Sự kiện 5">
                </div>
                <div class="event-card">
                    <span class="number">6</span>
                    <img src="img/vucattuong2.jpg" alt="Sự kiện 6">
                </div>
                <div class="event-card">
                    <span class="number">7</span>
                    <img src="img/atrai.jpg" alt="Sự kiện 7">
                </div>
            </div>

            <button class="trend-btn next">&#10095;</button>
        </div>
        <!-- End trend -->
         <!-- Event-4-you -->
        <section class="event4you">
            <h2 class="name">Dành cho bạn</h2>
            <?php while($row = $random_events->fetch_assoc()): ?>
                <div class="event">
                <img src="img/<?php echo $row['HinhAnh']; ?>" alt="Sự kiện" class="event-img">
                <div class="event-text">
                    <?php 
                    if ( (strtotime($now) >= strtotime($row['NgayBatDau'])) && (strtotime($now) <= strtotime($row['NgayKetThuc'])) )
                    {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:#2dc275;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đang diễn ra</span>";
                    }   else if ( (strtotime($now) < strtotime($row['NgayBatDau'])) && (strtotime($now) < strtotime($row['NgayKetThuc'])) ) {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:orange;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Sắp diễn ra</span>";
                    }   else{
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:gray;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đã kết thúc</span>";
                    }
                    ?>
                    <h3 class="name"><?php echo $row['TenSuKien']; ?></h3>
                    <p class="cost">Từ <?php $mask=$row['MaSuKien']; $gia= mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where MaSuKien='$mask'"));
                    echo number_format($gia['MIN']); ?>đ</p>
                    <p class="date">
                    <i class="ti-calendar"></i>
                    <?php echo date("d", strtotime($row['NgayGio'])) . " tháng " . date("m, Y", strtotime($row['NgayGio'])); ?>
                    </p>
                    <!-- Sửa chỗ này -->
                    <!-- <form action="php/themve.php" method="POST"> -->
                    <form action="php/datve.php?id=<?php echo $row['MaSuKien']; ?>" method="POST">
                        <input type="hidden" name="su_kien_id" value="<?php echo htmlspecialchars($row['MaSuKien']); ?>">
                        <button type="submit" class="add-ticket">Đặt vé</button>
                    </form>
                </div>
                </div>
            <?php endwhile; ?>
        </section>

        <!-- End enent-4-you -->
         <!-- Event-music -->
<section id="music-section" class="event4you slider-container">
    <h2 class="name">Âm nhạc</h2>

    <button class="slider-btn prev">&#10094;</button>
    <div class="slider-track">
        <?php while($row = $music_events->fetch_assoc()): ?>
            <div class="event">
                <img src="img/<?php echo $row['HinhAnh']; ?>" alt="Sự kiện" class="event-img">
                <div class="event-text">
                    <?php 
                    if ( (strtotime($now) >= strtotime($row['NgayBatDau'])) && (strtotime($now) <= strtotime($row['NgayKetThuc'])) )
                    {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:#2dc275;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đang diễn ra</span>";
                    }   else if ( (strtotime($now) < strtotime($row['NgayBatDau'])) && (strtotime($now) < strtotime($row['NgayKetThuc'])) ) {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:orange;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Sắp diễn ra</span>";
                    }   else{
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:gray;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đã kết thúc</span>";
                    }
                    ?>
                    <h3 class="name"><?php echo $row['TenSuKien']; ?></h3>
                    <!-- Sửa chỗ này -->
                    <p class="cost">Từ <?php $mask=$row['MaSuKien']; $gia= mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where MaSuKien='$mask'"));
                    echo number_format($gia['MIN']); ?>đ</p>
                    <p class="date"><i class="ti-calendar"></i>
                        <?php echo date("d", strtotime($row['NgayGio'])) . " tháng " . date("m, Y", strtotime($row['NgayGio'])); ?>
                    </p>
                    <!-- <form action="php/themve.php" method="POST"> -->
                    <form action="php/datve.php?id=<?php echo $row['MaSuKien']; ?>" method="POST">
                        <input type="hidden" name="su_kien_id" value="<?php echo htmlspecialchars($row['MaSuKien']); ?>">
                        <button type="submit" class="add-ticket">Đặt vé</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <button class="slider-btn next">&#10095;</button>
</section>

        <!-- End event-music -->
<img src="img/qcao.webp" alt="" class="qcao"> 
        <!-- event-sport -->
<section id="sport-section" class="event4you slider-container">
    <h2 class="name">Thể thao</h2>

    <button class="slider-btn prev">&#10094;</button>
    <div class="slider-track">
        <?php while($row = $sport_events->fetch_assoc()): ?>
            <div class="event">
                <img src="img/<?php echo $row['HinhAnh']; ?>" class="event-img">
                <div class="event-text">
                    <?php 
                    if ( (strtotime($now) >= strtotime($row['NgayBatDau'])) && (strtotime($now) <= strtotime($row['NgayKetThuc'])) )
                    {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:#2dc275;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đang diễn ra</span>";
                    }   else if ( (strtotime($now) < strtotime($row['NgayBatDau'])) && (strtotime($now) < strtotime($row['NgayKetThuc'])) ) {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:orange;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Sắp diễn ra</span>";
                    }   else{
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:gray;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đã kết thúc</span>";
                    }
                    ?>
                    <h3><?php echo $row['TenSuKien']; ?></h3>
                    <!--  -->
                    <p class="cost">Từ <?php $mask=$row['MaSuKien']; $gia= mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where MaSuKien='$mask'"));
                    echo number_format($gia['MIN']); ?>đ</p>
                    <p class="date"><i class="ti-calendar"></i>
                        <?php echo date("d", strtotime($row['NgayGio'])) . " tháng " . date("m, Y", strtotime($row['NgayGio'])); ?>
                    </p>
                    <!-- <form action="php/themve.php" method="POST"> -->
                    <form action="php/datve.php?id=<?php echo $row['MaSuKien']; ?>" method="POST">
                        <input type="hidden" name="su_kien_id" value="<?php echo htmlspecialchars($row['MaSuKien']); ?>">
                        <button type="submit" class="add-ticket">Đặt vé</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <button class="slider-btn next">&#10095;</button>
</section>
        <!-- End enent-sport -->
        <!-- Event-art -->
<section id="art-section" class="event4you slider-container">
    <h2 class="name">Nghệ thuật</h2>

    <button class="slider-btn prev">&#10094;</button>
    <div class="slider-track">
        <?php while($row = $art_events->fetch_assoc()): ?>
            <div class="event">
                <img src="img/<?php echo $row['HinhAnh']; ?>" alt="Sự kiện" class="event-img">
                <div class="event-text">
                    <?php 
                    if ( (strtotime($now) >= strtotime($row['NgayBatDau'])) && (strtotime($now) <= strtotime($row['NgayKetThuc'])) )
                    {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:#2dc275;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đang diễn ra</span>";
                    }   else if ( (strtotime($now) < strtotime($row['NgayBatDau'])) && (strtotime($now) < strtotime($row['NgayKetThuc'])) ) {
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:orange;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Sắp diễn ra</span>";
                    }   else{
                        echo "<span style='display:inline-block;align-self:flex-start;width:auto;margin:2px 4px;padding:2px 6px;font-size:11px;font-weight:600;background:gray;color:#fff;border-radius:8px;line-height:1;white-space:nowrap;'>Đã kết thúc</span>";
                    }
                    ?>
                    <h3 class="name"><?php echo $row['TenSuKien']; ?></h3>
                    <!--  -->
                    <p class="cost">Từ <?php $mask=$row['MaSuKien']; $gia= mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where MaSuKien='$mask'"));
                    echo number_format($gia['MIN']); ?>đ</p>
                    <p class="date">
                        <i class="ti-calendar"></i>
                        <?php echo date("d", strtotime($row['NgayGio'])) . " tháng " . date("m, Y", strtotime($row['NgayGio'])); ?>
                    </p>
                    <!-- <form action="php/themve.php" method="POST"> -->
                    <form action="php/datve.php?id=<?php echo $row['MaSuKien']; ?>" method="POST">
                        <input type="hidden" name="su_kien_id" value="<?php echo htmlspecialchars($row['MaSuKien']); ?>">
                        <button type="submit" class="add-ticket">Đặt vé</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <button class="slider-btn next">&#10095;</button>
</section>


        <!-- End enent-art -->
         <!-- Places -->
          <h2>Điểm đến thú vị</h2>
          <div class="places">
                <div class="place">
                    <img src="img/tphcm.webp" alt="tp.HCM" class="city">
                    <span class="city-name">TP Hồ Chí Minh</span>
                </div>
                <div class="place">
                    <img src="img/hanoi.webp" alt="Hà Nội" class="city">
                    <span class="city-name">Hà Nội</span>
                </div>
                <div class="place">
                    <img src="img/dalat.webp" alt="Đà Lạt" class="city">
                    <span class="city-name">Đà Lạt</span>
                </div>
                <div class="place">
                    <img src="img/vitri.webp" alt="Vị trí khác" class="city">
                    <span class="city-name">Vị trí khác</span>
                </div>
          </div>
    </section>
    
<footer class="footer">
    <div class="footer-container">
        <!-- Cột 1 -->
        <div class="footer-column">
            <h4>Hotline</h4>
            <p>📞 Thứ 2 - Chủ Nhật (8:00 - 23:00)</p>
            <p class="highlight">1900.6408</p>

            <h4>Email</h4>
            <p>📧 support@ticketbox.vn</p>

            <h4>Văn phòng chính</h4>
            <p>🏢 Tầng 12, Tòa nhà Viettel, 285 Cách Mạng Tháng Tám, 
            P.Hòa Hưng, TP. Hồ Chí Minh</p>
        </div>

        <!-- Cột 2 -->
        <div class="footer-column">
            <h4>Dành cho Khách hàng</h4>
            <p>Điều khoản sử dụng cho khách hàng</p>

            <h4>Dành cho Ban Tổ chức</h4>
            <p>Điều khoản sử dụng cho ban tổ chức</p>
        </div>

        <!-- Cột 3 -->
        <div class="footer-column">
            <h4>Về công ty chúng tôi</h4>
            <p>Quy chế hoạt động</p>
            <p>Chính sách bảo mật thông tin</p>
            <p>Cơ chế giải quyết tranh chấp/ khiếu nại</p>
            <p>Chính sách bảo mật thanh toán</p>
            <p>Chính sách đổi trả và kiểm hàng</p>
            <p>Điều kiện vận chuyển và giao nhận</p>
            <p>Phương thức thanh toán</p>
        </div>
    </div>
</footer>
<!-- popup tìm kiếm -->
 <!-- POPUP KẾT QUẢ TÌM KIẾM -->
<div id="search-popup" class="popup-overlay">
    <div class="popup-box">
        <div class="popup-header">
            <h2>Kết quả tìm kiếm</h2>
            <button id="close-popup" class="close-btn">✖</button>
        </div>
        <div id="popup-results" class="popup-grid">
            <!-- Kết quả AJAX sẽ chèn vào đây -->
        </div>
    </div>
</div>

<script src="/WEB/function.js"></script>

</body>
</html>