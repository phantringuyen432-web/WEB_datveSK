<?php
include "include/header.php";
if (!isset($_SESSION['email']))
    {
        header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
        exit(); //Thoát không chạy các câu lệnh phía sau
    }
include "include/connect.php";

// Xử lý lọc theo tháng/năm (nếu có chọn)
$thang = isset($_GET['thang']) ? $_GET['thang'] : '';
$nam = isset($_GET['nam']) ? $_GET['nam'] : '';

// Câu SQL: tính tổng tiền theo sự kiện
$sql = "SELECT s.MaSuKien, s.TenSuKien, COALESCE(SUM(v.SoTienThanhToan), 0)  AS TongTien
        FROM Ve v
        JOIN SuKien s ON v.MaSuKien = s.MaSuKien";

$dk = [];
if (!empty($thang)) $dk[] = "MONTH(v.NgayDat) = '$thang'";
if (!empty($nam)) $dk[] = "YEAR(v.NgayDat) = '$nam'";
if (count($dk) > 0) {
    $sql .= " WHERE " . implode(" AND ", $dk);
}

$sql .= " GROUP BY s.MaSuKien, s.TenSuKien ORDER BY TongTien DESC";

$result = mysqli_query($conn, $sql);

// Tính tổng toàn bộ doanh thu
$total = 0;
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
    $total += $row['TongTien'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê doanh thu - TicketBox</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .bieudo {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 400px;
            margin-top: 40px;
        }
        .bar-group {
            text-align: center;
            margin: 0 15px;
        }
        .bar {
            width: 60px;
            background: linear-gradient(180deg, #2DC275; #1e804dff);
            border-radius: 8px 8px 0 0;
            position: relative;
            height: var(--bar-height);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .bar span {
            position: absolute;
            top: -25px;
            font-size: 14px;
            color: #333;
        }
        .label-top, .label-bottom {
            font-size: 14px;
            margin: 5px 0;
        }
        .thang_nam select {
            padding: 7px;
            margin: 0 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

        <h1>Thống kê doanh thu sự kiện</h1>

        <form method="GET" class="thang_nam">
            <b>Tháng:</b>
            <select name="thang">
                <option value="">Tất cả</option>
                <?php for ($i=1; $i<=12; $i++) { ?>
                    <option value="<?= $i ?>" <?= ($thang==$i)?'selected':'' ?>><?= $i ?></option>
                <?php } ?>
            </select>

            <b>Năm:</b>
            <select name="nam">
                <?php
                $namhientai = date("Y");
                for ($n=$namhientai; $n>=2020; $n--) {
                    echo "<option value='$n' " . ($nam==$n?'selected':'') . ">$n</option>";
                }
                ?>
            </select>
            <button type="submit" style="padding:7px 15px;border:none;background:#2DC275;color:#fff;border-radius:5px;cursor:pointer;">
                Lọc
            </button>
        </form>

        <div class="bieudo">
            <?php
            if (count($data) > 0) {
                foreach ($data as $row) {
                    $height = $row['TongTien'] / 100000; // quy đổi thành pixel
                    $tien = number_format($row['TongTien'], 0, ',', '.');
                    echo "
                    <div class='bar-group'>
                        <div class='label-top'>{$row['TenSuKien']}</div>
                        <div class='bar' style='--bar-height: {$height}px;'><span>{$tien}đ</span></div>
                        <div class='label-bottom'>{$row['MaSuKien']}</div>
                    </div>
                    ";
                }
            } else {
                echo "<p>Không có dữ liệu cho khoảng thời gian này.</p>";
            }
            ?>
        </div>

        <h3 style="margin-top: 40px;">Tổng doanh thu: <?= number_format($total, 0, ',', '.') ?>đ</h3>
    </div>
</div>
</body>
</html>
