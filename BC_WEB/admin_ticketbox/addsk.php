<?php
session_start();
include "include/connect.php";

if (!isset($_SESSION['email'])) {
    header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
    exit();
}

$sql = "SELECT * FROM LoaiSuKien";
$result = mysqli_query($conn, $sql);

if (isset($_POST['luu'])) {
    $ten = $_POST['ten'];
    $tg = date('Y-m-d H:i:s',strtotime($_POST['thoigian']));
    $tg_bd=date('Y-m-d H:i:s',strtotime($_POST['thoigian_batdau']));  
    $tg_kt=date('Y-m-d H:i:s',strtotime($_POST['thoigian_ketthuc'])); 
    $dd = $_POST['diadiem'];
    $mota = $_POST['mota'];
    $maloai = $_POST['maloai'];
    $so_day = $_POST['day'];
    $now = date("Y-m-d H:i:s");
    // Xử lý ảnh
    $name_img = $_FILES['anhmoi']['name']; //lấy tên ảnh
    $path_img = $_FILES['anhmoi']['tmp_name'];//lấy đường dẫn của ảnh
    if (!empty($name_img)) {
        move_uploaded_file($path_img, 'image/' . $name_img); //nếu chưa chọn ảnh thì rỗng
    } else {
        $name_img = '';
    }
    //Kiểm tra ngày bắt đầu, kết thúc, diễn ra
    // ✅ Cài múi giờ Việt Nam để tránh sai lệch
   date_default_timezone_set('Asia/Ho_Chi_Minh');
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
        // Chèn sự kiện
        $sql_insert_sk = "INSERT INTO SuKien (TenSuKien, NgayGio, DiaDiem, MaLoai, HinhAnh, MoTa, NgayBatDau, NgayKetThuc)
                      VALUES ('$ten', '$tg', '$dd', '$maloai', '$name_img', '$mota','$tg_bd','$tg_kt')";
        mysqli_query($conn, $sql_insert_sk);

        // Lấy mã sự kiện vừa thêm
        $mask = mysqli_insert_id($conn);

        // Chèn ghế theo từng dãy
        for ($i = 1; $i <= $so_day; $i++) {
        $ten_day = $_POST["ten_day_$i"];
        $so_ghe = $_POST["so_ghe_$i"];
        $gia = $_POST["gia_$i"];

        for ($j = 1; $j <= $so_ghe; $j++) {
            $sql_insert_ghe = "INSERT INTO Ghe (MaSuKien, DayGhe, SoGhe, Gia) 
                               VALUES ('$mask', '$ten_day', '$j', '$gia')";
            mysqli_query($conn, $sql_insert_ghe);
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
                <h1>Thêm sự kiện</h1>

                <b>Tên sự kiện</b>
                <input type="text" name="ten" placeholder="Tên sự kiện" required>

                <b>Loại sự kiện</b>
                <select style="width:100%;  padding: 10px; border: 1px solid #ccc; border-radius: 6px;
                    font-size: 15px;" name="maloai" class="maloai" required>
                    <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <option value="<?php echo $row['MaLoai']; ?>">
                            <?php echo $row['TenLoai']; ?>
                        </option>
                    <?php } ?>
                </select> 

                <b>Thời gian diễn ra sự kiện</b>
                <?php date_default_timezone_set('Asia/Ho_Chi_Minh'); ?>
                <input type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>" name="thoigian" required>

                <b>Thời gian bắt đầu mở bán vé</b>
                <?php date_default_timezone_set('Asia/Ho_Chi_Minh'); ?>
                <input type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>" name="thoigian_batdau" required>

                <b>Thời gian kết thúc mở bán vé</b>
                <?php date_default_timezone_set('Asia/Ho_Chi_Minh'); ?>
                <input type="datetime-local" value="<?php echo date('Y-m-d\TH:i'); ?>" name="thoigian_ketthuc" required>

                <b>Địa điểm</b>
                <input type="text" name="diadiem" placeholder="Địa điểm" required>

                <b>Hình ảnh sự kiện</b>
                <div class="hinhanhsukien">
                    <img id="preview" src="" alt="Hình ảnh sự kiện" >
                </div>
                <input type="file" name="anhmoi" accept="image/*" onchange="loadFile(event)">

                <b>Mô tả</b>
                <textarea id="mota_sk" name="mota" placeholder="Mô tả sự kiện"></textarea>

                <b>Chọn số lượng dãy ghế</b>
                <select name="day" id="day" onchange="TaoDayGhe()">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <!-- Nơi tạo dãy ghế -->
                <div id="taodayghe"></div>

                <button class="btn_sk" name="luu" type="submit">Thêm sự kiện</button>
            </form>
        </div>
    </div>
</div>

<script>
function loadFile(event) {
    const output = document.getElementById('preview');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function TaoDayGhe() {
    var sl = document.getElementById('day').value;
    var dayghe = document.getElementById('taodayghe');
    dayghe.innerHTML = ""; // xóa nội dung cũ
    sl = parseInt(sl);
    for (let i = 1; i <= sl; i++) {
        const div = document.createElement("div"); // tạo ra div thêm vào dãy ghế
        div.innerHTML = `
            <b>Dãy ${i}</b><br>
            <input type="text" name="ten_day_${i}" placeholder="Tên dãy (A, B, C...)" required>
            <input type="number" name="so_ghe_${i}" placeholder="Số lượng ghế" min="1" required>
            <input type="number" name="gia_${i}" placeholder="Giá vé" min="0" required>
            <br><br>
        `;
        dayghe.appendChild(div);
    }
}
</script>
