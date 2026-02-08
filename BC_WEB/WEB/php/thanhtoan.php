<?php
session_start();
include 'connect.php';

//  Kiểm tra đăng nhập
if (!isset($_SESSION['MaNguoiDung'])) {
    echo "<script>alert('⚠️ Bạn cần đăng nhập để thanh toán!'); window.location.href='dangnhap.php';</script>";
    exit;
}

//  Lấy dữ liệu từ form chi tiết
$MaNguoiDung = $_SESSION['MaNguoiDung'];
$MaSuKien = $_POST['id_sukien'] ?? null;
$tongtien = $_POST['tongtien'] ?? 0;
$ds_ghe_json = $_POST['ghe_chon'] ?? '[]';
$ds_ghe = json_decode($ds_ghe_json, true);

if (!$MaSuKien || empty($ds_ghe)) {
    echo "<script>alert('⚠️ Dữ liệu không hợp lệ!'); window.location.href='index.php';</script>";
    exit;
}

// Lấy thông tin sự kiện và ghế để hiển thị (không lưu vé)
$veList = [];
$tongtien = 0;

foreach ($ds_ghe as $gheCode) {
    $day = preg_replace('/[^A-Z]/i', '', $gheCode);
    $so = preg_replace('/[^0-9]/', '', $gheCode);

    $sql_ghe = "SELECT Ghe.MaGhe, Ghe.Gia AS GiaGhe, Ghe.DayGhe, Ghe.SoGhe, SuKien.TenSuKien
                FROM Ghe
                JOIN SuKien ON Ghe.MaSuKien = SuKien.MaSuKien
                WHERE Ghe.MaSuKien='$MaSuKien' AND Ghe.DayGhe='$day' AND Ghe.SoGhe='$so'";
    $result_ghe = $conn->query($sql_ghe);

    if ($result_ghe && $row = $result_ghe->fetch_assoc()) {
        $tongtien += $row['GiaGhe'];
        $veList[] = $row;
    }
}

//  QR thanh toán
$bank = "BIDV";
$account = "0339332276";
$name = "PHAN TRI NGUYEN"; // không dấu
$qr_link = "https://img.vietqr.io/image/{$bank}-{$account}-compact2.jpg?amount={$tongtien}&addInfo=ThanhToanVe&accountName={$name}";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán vé</title>
<link rel="stylesheet" href="vecuatoi.css">

<style>
body {
    background-color: #f9f9f9;
    font-family: Arial, sans-serif;
}
.my-tickets {
    width: 90%;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    padding: 20px 30px;
}
.my-tickets h1 {
    font-size: 26px;
    margin-bottom: 20px;
    border-left: 5px solid #4CAF50;
    padding-left: 10px;
}
.my-tickets p {
    font-size: 16px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 15px;
}
table th, table td {
    border: 1px solid #ddd;
    padding: 10px 12px;
    text-align: left;
}
table th {
    background-color: #000;
    color: white;
}
table tr:nth-child(even) {
    background-color: #f9f9f9;
}
table tr:hover {
    background-color: #f1f1f1;
}
.pay-btn {
    background-color: #2dc275;
    color: white;
    border: none;
    padding: 12px 24px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}
.pay-btn:hover {
    background-color: #0dc465ff;
}
</style>
</head>
<body>
<section class="my-tickets">
  <h1>💳 Thanh toán vé</h1>
  <p><strong>Tổng tiền: <?php echo number_format($tongtien); ?>đ</strong></p>

  <table>
      <tr>
          <th>Sự kiện</th>
          <th>Ghế</th>
          <th>Giá</th>
      </tr>
      <?php foreach ($veList as $ve): ?>
      <tr>
          <td><?php echo $ve['TenSuKien']; ?></td>
          <td><?php echo $ve['DayGhe'] . $ve['SoGhe']; ?></td>
          <td><?php echo number_format($ve['GiaGhe']); ?>đ</td>
      </tr>
      <?php endforeach; ?>
  </table>

  <p><b>Quét mã QR bên dưới để thanh toán:</b> </p>
  <div style="text-align:center; margin: 20px 0;">
      <img src="<?php echo $qr_link; ?>" alt="QR thanh toán" width="300">
  </div>

  <p>Nhấn nút để giả lập thanh toán thành công !</p>

  <form method="POST" action="xacnhan.php">
      <input type="hidden" name="MaSuKien" value="<?php echo $MaSuKien; ?>">
      <input type="hidden" name="SoTien" value="<?php echo $tongtien; ?>">
      <input type="hidden" name="ghe_chon" value='<?php echo json_encode($ds_ghe); ?>'>
      <button type="submit" class="pay-btn"> Tôi đã thanh toán</button>
  </form>
</section>
</body>
</html>
