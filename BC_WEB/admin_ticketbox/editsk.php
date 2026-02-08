<?php
include "include/connect.php";
session_start();
if (!isset($_SESSION['email']))
    {
        header('location: dangnhap.php?error=Bạn phải đăng nhập trước khi vào hệ thống!!!');
        exit(); //Thoát không chạy các câu lệnh phía sau
    }
$id = $_GET['id'];
$sql = "SELECT * FROM SuKien WHERE MaSuKien='$id'";
$result = mysqli_query($conn, $sql);

if (isset($_POST['luu'])) {
    $ten = $_POST['ten'];
    $tg = $_POST['thoigian'];
    $dd = $_POST['diadiem'];
    $mota = $_POST['mota'];
    $name_img_new = $_FILES['anhmoi']['name'];

    // Lấy ảnh hiện tại từ CSDL
    $row_old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT HinhAnh FROM SuKien WHERE MaSuKien='$id'"));
    $name_img = $row_old['HinhAnh'];

    if (!empty($name_img_new)) {
        $name_img = $name_img_new;
        $path_img = $_FILES['anhmoi']['tmp_name'];
        move_uploaded_file($path_img, 'image/' . $name_img);
    }

    $sql1 = "UPDATE SuKien 
             SET TenSuKien='$ten', NgayGio='$tg', DiaDiem='$dd', MoTa='$mota', HinhAnh='$name_img' 
             WHERE MaSuKien='$id'";

    mysqli_query($conn, $sql1);
    header("Location: sukien.php"); // quay lại trang danh sách
}
?>

<link rel="stylesheet" href="style.css">

<div class="nen_edit">
    <div class="form_edit">
        <div class="content_edit">
            <form action="" method="POST" enctype="multipart/form-data">
                <h1>Thông tin sự kiện</h1>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <b>Tên sự kiện</b>
                    <input type="text" name="ten" value="<?php echo $row['TenSuKien']; ?>">

                    <b>Thời gian</b>
                    <input type="datetime-local" name="thoigian" value="<?php echo $row['NgayGio']; ?>">

                    <b>Địa điểm</b>
                    <input type="text" name="diadiem" value="<?php echo $row['DiaDiem']; ?>">

                    <b>Hình ảnh sự kiện</b>
                    <div class="hinhanhsukien">
                        <img id="preview" src="<?php echo 'image/' . $row['HinhAnh']; ?>" alt="Hình ảnh sự kiện">
                    </div>
                    <input type="file" name="anhmoi" accept="image/*" onchange="loadFile(event)">

                    <b>Mô tả</b>
                    <textarea id="mota_sk" name="mota"><?php echo $row['MoTa']; ?></textarea>

                    <button class="btn_sk" name="luu" type="submit">Lưu thay đổi</button>
                <?php } ?>
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
