<?php
    include "include/connect.php";
    $id= $_GET['id']; //lấy id sự kiện
    $sql_day = "SELECT DISTINCT DayGhe FROM Ghe WHERE MaSuKien='$id'";
    $result_day = mysqli_query($conn, $sql_day);

    $row_sk = mysqli_fetch_array(mysqli_query($conn,"select * from SuKien where MaSuKien='$id'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        .khung_chitiet{
            width:100%;
            height:400px;
            background:linear-gradient(145deg, #434343, #000000); /* gradient đen xám */;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .khung_chitiet .chitiet {
            width:80%;
            height:300px;
            display:flex;
            justify-content: center;

            
        }

        .noidung.sk{
            background-color: #2e3137;
            width:40%;
            border:none;
            border-radius:30px;
            border-right: 5px dashed #191a1dff;
            
        }
        .ttsk.content{
            margin:30px;
            line-height:1.5;
            margin-bottom:0;
        }

        .noidung.img{
            /* background-color: #b2ba1fff; */
            width: 60%;
            border:none;
            border-radius:30px;
            /* border-left: 5px dashed #191a1dff; */
            
        }
        img{
            width: 100%;
            border-radius:30px;
            height: 100%;
            order-left: 4px dashed black;
        }
        h3{
            color:white;
            font-weight:bold;

        }
        span{
            padding: 12px;
            /* transform: translateY(-10px); */
            color: #2dc275;
            font-weight:bold;
        }
        svg{
            padding-top:17px;
        }
        /* dãy ghế */
        .dayghe {
  margin-bottom: 15px;
}

.danhsach_ghe {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.ghe_sk {
  width: 40px;
  height: 40px;
  border: 1px solid #333;
  border-radius: 6px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 5px;
  cursor: pointer;
  transition: 0.2s;
}

/* Ghế trống */
.ghe_sk.trong {
  background-color: white;
  border : 2px solid #2dc275;
}

/* Ghế đã đặt */
.ghe_sk.dat {
  background-color: #f44336;
  color: white;
  cursor: not-allowed;
  opacity: 0.7;
  border : 2px solid #ef1e0fff;
}

/* Ghế đang được chọn */
.ghe_chon {
  background-color: #2dc275 !important;
  color: white;
}
/*  */
.form_themvaovecuatoi , .gioithieu {
    margin: 30px;
}
    </style>
</head>
<body>
    <div class="khung_chitiet">
        <div class="chitiet">
            <div class="noidung sk">
                <div style="height:calc(0.7*300px);" class="ttsk content">
                    <!-- Tên Sự kiện -->
                    <h3><?php echo $row_sk['TenSuKien'];?></h3>
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg> -->
                    <img style="width:18px;height:18px; border-radius:0px;margin-top:5px;"src="./image/date.png" alt="">
                    <!-- Thời gian -->
                    <span><?php
                    $ngaygio = $row_sk['NgayGio']; 
                    $timestamp = strtotime($ngaygio);
                    echo date('j', $timestamp) . " tháng " . date('n, Y', $timestamp) . " – Từ " . date('H:i', $timestamp);
                    ?>
                    </span> <br>
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    </svg> -->
                    <img style="width:18px;height:18px; border-radius:0px;margin-top:5px;"src="./image/checkin.png" alt="">
                    <!-- Địa điểm -->
                    <span><?php echo $row_sk['DiaDiem'];?></span>
                </div>
                <div style="height:100px;" class="ttsk btn">
                    <hr style=" margin-bottom: 15px;" >
                    <span style="color:white;font-size:20px; margin: 10px ;">Giá từ : </span>
                    <span style="color:#2dc275;font-size:25px;">
                        <?php $min=mysqli_fetch_array(mysqli_query($conn,"select min(gia) as MIN from Ghe where masukien='$id'")); 
                        echo number_format($min['MIN']).'đ' ?>
                    </span>
                </div>
                
            </div>

            <div class="noidung img">
                <img src="<?php echo 'image/'.$row_sk['HinhAnh'];?>" alt="hình ảnh">
            </div>
        </div>
    </div>

    <!-- Giới thiệu -->
     <div class="gioithieu">
        <h1 style="color:gray;">Giới thiệu</h1>
        <hr style="margin-top:20px;">
        <div class="mota" style="margin:20px 0px;">
            <!-- Đây là nội dung mô tả của sự kiện !!! -->
             <?php echo $row_sk['MoTa'];?>
        </div>
        <hr>
     </div>

     <!-- Form thanh toán -->
      <div class="form_themvaovecuatoi">
            <form action="thanhtoan.php" method="POST">
  <h1 style="color:gray;">Danh sách ghế ngồi</h1>

  <?php while ($row_day = mysqli_fetch_assoc($result_day)) { 
      $day = $row_day['DayGhe'];
      $sql_ghe = "SELECT SoGhe, TrangThai, Gia 
            FROM Ghe 
            WHERE MaSuKien='$id' AND DayGhe='$day' 
            ORDER BY CAST(SoGhe AS UNSIGNED) ASC";
      $result_ghe = mysqli_query($conn, $sql_ghe);
  ?>
      <div class="dayghe">
          <h4 style="font-weight:bold; margin:10px 10px;font-size:20px;">Dãy <?php echo $day; ?></h4>
          <div class="danhsach_ghe">
              <?php while ($row_ghe = mysqli_fetch_assoc($result_ghe)) { ?>
                  <div class="ghe_sk <?php echo " ".$row_ghe['TrangThai'] ?>"  
                       data-gia="<?php echo $row_ghe['Gia']; ?>"> 
                      <?php echo $day . $row_ghe['SoGhe']; ?>
                  </div>
              <?php } ?>
          </div>
      </div>
  <?php } ?>

  <!-- Ghế chọn -->
  <!-- <div style="display:flex;margin-top:20px;" class="list_ghe_chon">
      <div style="font-weight:bold; font-size: 20px; color: #2dc275;">Ghế đã chọn: </div>
      <div style="font-weight:bold; font-size: 20px; color: black; margin-left:10px;" id="ghedachon">    
      </div>  
  </div> -->

  <!-- <div style="display:flex; margin-top:20px;" class="tongtien">
      <div style="font-weight:bold; font-size: 20px; color: #2dc275;">Tổng tiền: </div>
      <div style="font-weight:bold; font-size: 20px; color: black; margin-left:10px;" id="tongtien">0</div> 
      <b>đ</b>
  </div> -->

  <!-- Nút gửi -->
  <!-- <div style="margin-top:20px;">
      <button type="submit" 
              style="padding: 12px 25px; background-color: #2dc275; border: none; border-radius: 10px;
                     color: white; font-weight: bold; cursor: pointer; font-size: 18px;">
          Thêm vào vé của tôi
      </button>
  </div>
</form>
      </div> -->


     
        

    <!-- <script>
    var dachon = document.getElementById('ghedachon');
    var tongtien = document.getElementById('tongtien');
    var tinh =0;
    document.addEventListener("DOMContentLoaded", function() {
    // Lấy tất cả phần tử có class "ghe_sk"
    var gheList = document.getElementsByClassName("ghe_sk");

    // Duyệt qua từng ghế
    for (var i = 0; i < gheList.length; i++) {
        gheList[i].onclick = function() {
        // Nếu ghế đã đặt => không cho chọn
        if (this.classList.contains("dat")) {
            alert("Ghế này đã được đặt!");
            return;
        }
        var gia = parseInt(this.getAttribute("data-gia"));
        // Nếu ghế đang chọn => bỏ chọn, ngược lại chọn
        if (this.classList.contains("ghe_chon")) {
            this.classList.remove("ghe_chon");
                tinh-=gia;
            
        } else {
        this.classList.add("ghe_chon");
        tinh+=gia;
        
        }
        capNhatDanhSach();
        tongtien.innerText=tinh;
    };
  }
});
function capNhatDanhSach() {
    var gheChon = document.getElementsByClassName("ghe_chon");
    var ds = [];

    for (var j = 0; j < gheChon.length; j++) {
        //lấy ghế
      var ghe = gheChon[j].innerText;
       
      ds.push(ghe);
    }

    // Hiển thị ra HTML
    if (ds.length > 0) {
      dachon.innerText = ds.join(", ");
    } else {
      dachon.innerText = "";
    }
    
  }

</script> -->

</body>
</html>