<?php
//  Cài múi giờ Việt Nam để tránh sai lệch
   date_default_timezone_set('Asia/Ho_Chi_Minh');
include "include/connect.php";
session_start();
if (!isset($_SESSION['email'])) {
    header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
    exit();
}


$id = $_GET['id'];
$sql = "SELECT * FROM sukien WHERE MaSuKien='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
// Xử lý readonly
$now = date("Y-m-d H:i:s");
$bd=date('Y-m-d H:i:s',strtotime($row['NgayBatDau']));
$kt=date('Y-m-d H:i:s',strtotime($row['NgayKetThuc']));
// Kiểm tra xem sự kiện có vé đã bán chưa
$sql_check = "SELECT COUNT(*) AS SoVe FROM Ve WHERE MaSuKien='$id'";
$check = mysqli_fetch_assoc(mysqli_query($conn, $sql_check));
$so_ve = $check['SoVe'];

// Lấy danh sách dãy ghế
$sql_ghe = "SELECT DayGhe, COUNT(*) AS SoLuong, Gia FROM Ghe WHERE MaSuKien='$id' GROUP BY DayGhe, Gia";
$result_ghe = mysqli_query($conn, $sql_ghe);

if (isset($_POST['luu'])) {
    $ten = $_POST['ten'];
    $tg = date('Y-m-d H:i:s',strtotime($_POST['thoigian']));
    $tg_bd=date('Y-m-d H:i:s',strtotime($_POST['thoigian_batdau']));  
    $tg_kt=date('Y-m-d H:i:s',strtotime($_POST['thoigian_ketthuc'])); 
    
    $dd = $_POST['diadiem'];
    $mota = $_POST['mota'];
    //Kiểm tra xem người quản trị có load ảnh mới lên không
    $name_img_new = $_FILES['anhmoi']['name'];

    $row_old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT HinhAnh FROM sukien WHERE MaSuKien='$id'"));
    $name_img = $row_old['HinhAnh'];

    if (!empty($name_img_new)) {
        $name_img = $name_img_new;
        move_uploaded_file($_FILES['anhmoi']['tmp_name'], 'image/' . $name_img);
    }
    //Nếu sự kiện đã diễn ra chỉ cập nhật lại các dữ liệu khác thời gian
    //Đồng thời chỉ cập nhật 
    if(strtotime($now) < strtotime($bd))
    {
        if (strtotime($tg_bd) >= strtotime($tg_kt)) {
    echo "<script>alert('Thời gian mở bán phải trước thời gian kết thúc mở bán!'); history.back();</script>";
    exit;
} 
    else if (strtotime($tg_kt) >= strtotime($tg)) {
    echo "<script>alert('Thời gian kết thúc mở bán phải trước thời gian diễn ra sự kiện!'); history.back();</script>";
    exit;
} 
    else if (strtotime($tg_bd) <= strtotime($now)) {
    echo "<script>alert('Thời gian mở bán phải sau thời điểm hiện tại!'); history.back();</script>";
    exit;
} 
    else {
        $sql1 = "UPDATE SuKien 
             SET TenSuKien='$ten', NgayGio='$tg', DiaDiem='$dd', MoTa='$mota', HinhAnh='$name_img',NgayBatDau='$tg_bd',NgayKetThuc='$tg_kt' 
             WHERE MaSuKien='$id'";
        mysqli_query($conn, $sql1);

        // Nếu chưa có vé nào thì cho phép cập nhật lại thông tin ghế
        if ($so_ve == 0) {
        $so_day = $_POST['so_day'];
        // Xóa dữ liệu ghế cũ
        mysqli_query($conn, "DELETE FROM Ghe WHERE MaSuKien='$id'");
        // Thêm lại theo dữ liệu mới
        for ($i = 1; $i <= $so_day; $i++) {
            $ten_day = $_POST["ten_day_$i"];
            $so_ghe = $_POST["so_ghe_$i"];
            $gia = $_POST["gia_$i"];
            for ($j = 1; $j <= $so_ghe; $j++) {
                mysqli_query($conn, "INSERT INTO Ghe (MaSuKien, DayGhe, SoGhe, Gia)
                                     VALUES ('$id', '$ten_day', '$j', '$gia')");
            }
        }
    }

    header("Location: sukien.php");
    exit();

    }
    //Ngược lại thì cập nhật giá trị cũ của thời gian
    }
    else {
        $sql1 = "UPDATE SuKien 
             SET TenSuKien='$ten', NgayGio='$tg', DiaDiem='$dd', MoTa='$mota', HinhAnh='$name_img',NgayBatDau='$tg_bd',NgayKetThuc='$tg_kt' 
             WHERE MaSuKien='$id'";
        mysqli_query($conn, $sql1);

        // Nếu chưa có vé nào thì cho phép cập nhật lại thông tin ghế
        if ($so_ve == 0) {
        $so_day = $_POST['so_day'];
        // Xóa dữ liệu ghế cũ
        mysqli_query($conn, "DELETE FROM Ghe WHERE MaSuKien='$id'");
        // Thêm lại theo dữ liệu mới
        for ($i = 1; $i <= $so_day; $i++) {
            $ten_day = $_POST["ten_day_$i"];
            $so_ghe = $_POST["so_ghe_$i"];
            $gia = $_POST["gia_$i"];
            for ($j = 1; $j <= $so_ghe; $j++) {
                mysqli_query($conn, "INSERT INTO Ghe (MaSuKien, DayGhe, SoGhe, Gia)
                                     VALUES ('$id', '$ten_day', '$j', '$gia')");
            }
        }
    }

    header("Location: sukien.php");
    exit();

    }
    
    
}
?>

<link rel="stylesheet" href="style.css">

<div class="nen_edit">
    <div class="form_edit">
        <div class="content_edit">
            <form action="" method="POST" enctype="multipart/form-data">
                <h1>Chỉnh sửa sự kiện</h1>

                <b>Tên sự kiện</b>
                <input type="text" name="ten" value="<?php echo $row['TenSuKien']; ?>">

                <b>Thời gian diễn ra sự kiện</b>
                <input type="datetime-local" name="thoigian" <?php if(strtotime($now) >= strtotime($bd)){echo 'readonly';} ?>
                       value="<?php echo date('Y-m-d\TH:i', strtotime($row['NgayGio'])); ?>">
                <b>Thời gian mở bán vé</b>
                <input type="datetime-local" name="thoigian_batdau" <?php if(strtotime($now)>= strtotime($bd)){echo 'readonly';} ?>
                       value="<?php echo date('Y-m-d\TH:i', strtotime($row['NgayBatDau'])); ?>">
                <b>Thời gian kết thúc mở bán</b>
                <input type="datetime-local" name="thoigian_ketthuc" <?php if(strtotime($now)>= strtotime($bd)){echo 'readonly';} ?>
                       value="<?php echo date('Y-m-d\TH:i', strtotime($row['NgayKetThuc'])); ?>">

                <b>Địa điểm</b>
                <input type="text" name="diadiem" value="<?php echo $row['DiaDiem']; ?>">

                <b>Hình ảnh sự kiện</b>
                <div class="hinhanhsukien">
                    <img id="preview" src="image/<?php echo $row['HinhAnh']; ?>" alt="Hình ảnh sự kiện">
                </div>
                <input type="file" name="anhmoi" accept="image/*" onchange="loadFile(event)">

                <b>Mô tả</b>
                <textarea id="mota_sk" name="mota"><?php echo $row['MoTa']; ?></textarea>

                <b>Thông tin ghế</b>

                <?php if ($so_ve > 0): ?>
                    <p style="color:red;">Sự kiện này đã có vé, không thể chỉnh sửa ghế.</p>
                    <table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">
                        <tr><th>Dãy</th><th>Số lượng ghế</th><th>Giá vé</th></tr>
                        <?php while ($rowg = mysqli_fetch_assoc($result_ghe)) { ?>
                            <tr>
                                <td><?php echo $rowg['DayGhe']; ?></td>
                                <td><?php echo $rowg['SoLuong']; ?></td>
                                <td><?php echo number_format($rowg['Gia']); ?>₫</td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php else: ?>
                    <?php
                        // nếu chưa có vé -> cho phép chỉnh sửa ghế
                        mysqli_data_seek($result_ghe, 0); // đưa con trỏ kết quả về đầu
                        $count_day = mysqli_num_rows($result_ghe);
                    ?>
                    <input type="hidden" name="so_day" value="<?php echo $count_day; ?>">
                    <div id="taodayghe">
                        <?php 
                        $i = 1;
                        while ($rowg = mysqli_fetch_assoc($result_ghe)) { ?>
                            <div>
                                <b>Dãy <?php echo $i; ?></b><br>
                                <input type="text" name="ten_day_<?php echo $i; ?>" value="<?php echo $rowg['DayGhe']; ?>" required>
                                <input type="number" name="so_ghe_<?php echo $i; ?>" value="<?php echo $rowg['SoLuong']; ?>" min="1" required>
                                <input type="number" name="gia_<?php echo $i; ?>" value="<?php echo $rowg['Gia']; ?>" min="0" required>
                                <br><br>
                            </div>
                        <?php $i++; } ?>
                    </div>
                <?php endif; ?>

                <button class="btn_sk" name="luu" type="submit">Lưu thay đổi</button>
            </form>
        </div>
    </div>
</div>

<script>
function loadFile(event) {
    const output = document.getElementById('preview');
    output.src = URL.createObjectURL(event.target.files[0]);
}
</script>
