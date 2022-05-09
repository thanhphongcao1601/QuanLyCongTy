<?php
  include 'account.php';
  if ($_SESSION['first']){
      header('Location: changepass.php');
      exit();
  }
  if (!isset($_SESSION['username']) || $_SESSION['position'] != "Giám đốc") {
      header('Location: index.php');
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="author" content="Sahil Kumar">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Details User</title>
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
      <!-- Our Custom CSS -->
      <link rel="stylesheet" href="style.css">
      <!-- cdn bs4 -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
</head>

<body>  
    <!-- header -->
    <?php include 'header_nosearch.php' ?>
      <div class="container">

        <div class="row justify-content-center">
        <table class="table" style="width:800px; text-align: center;">
          <tr>
            <td colspan=2 ><img src="uploads/<?=$vphoto?>"
                style="width:150px;height:150px;border-radius:50%;object-fit:cover;border: solid #8D4E85;" class="img-thumbnail"></td>
          </tr>
          <tr>
            <td class="font-weight-bold">ID</td>
            <td style="background-color:#E9DCE5;"><?= $vid?></td>
          </tr>
          <tr>
            <td style="background-color:#E9DCE5;" class="font-weight-bold">Họ tên</td>
            <td><?= $vname?></td>
          </tr>
          <tr>
            <td class="font-weight-bold">Tên đăng nhập</td>
            <td style="background-color:#E9DCE5;"><?= $vusername?></td>
          </tr>
          <tr>
            <td style="background-color:#E9DCE5;" class="font-weight-bold">Số điện thoại</td>
            <td><?= $vphone?></td>
          </tr>
          <tr>
            <td class="font-weight-bold">Sinh nhật</td>
            <td style="background-color:#E9DCE5;"><?= $vbirthday?></td>
          </tr>
          <tr>
            <td style="background-color:#E9DCE5;" class="font-weight-bold">Địa chỉ</td>
            <td><?= $vaddress?></td>
          </tr>
          <tr>
            <td class="font-weight-bold">Chức vụ</td>
            <td style="background-color:#E9DCE5;"><?= $vposition?></td>
          </tr>
          <tr>
            <td style="background-color:#E9DCE5;" class="font-weight-bold">Số ngày nghỉ</td>
            <td><?= $vnumber?></td>
          </tr>
          <tr>
            <td class="font-weight-bold">Phòng ban</td>
            <td style="background-color:#E9DCE5;"><?= $vpb?></td>
          </tr>
        </table>
      </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script type="text/javascript" src="./main.js?v=1"></script>
</body> 

</html>