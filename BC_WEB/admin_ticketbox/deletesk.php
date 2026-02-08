<?php 
    include "include/connect.php";
    $id = $_GET['id'];
    $sql="delete from SuKien where MaSuKien=$id";
    $check_ghe = mysqli_query($conn,"select * from Ghe where MaSuKien='$id'");
    if (mysqli_num_rows($check_ghe)>0)
    {
        echo "<script> alert('Không thể xoá sự kiện !!!'); window.location.href='sukien.php'; </script>";
        exit;
        
    }
    else{
        mysqli_query($conn,$sql);
        header('location: sukien.php');
    }
    
    
?>