<?php
  include 'account.php';
  if(!isset($_SESSION)) 
  { 
    session_start(); 
  } 
  if ($_SESSION['first']){
      header('Location: changepass.php');
      exit();
  }
  if (!isset($_SESSION['username']) || $_SESSION['position'] != "Giám đốc") {
      header('Location: index.php');
      exit();
  }
  $_SESSION['position'];
  //$idpb="";
  $conn=open_database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="Sahil Kumar">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>User Management</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <!-- jQuery library -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
</head>

<body>
    <!-- header -->
    <?php include 'header_nosearch.php' ?>

  <div class="container-fluid hscroll">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <h3 class="text-center text-dark mt-2 font-weight-bold">QUẢN LÝ NHÂN VIÊN</h3>
        <hr>
        <?php if (isset($_SESSION['response'])) { ?>
        <div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <b><?= $_SESSION['response']; ?></b>
        </div>
        <?php } unset($_SESSION['response']); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 card p-3">
        <h3 style="color:#8D4E85;" class="text-center">Thêm nhân viên</h3>
        <form action="account.php" id="form-add-task" method="post" enctype="multipart/form-data" novalidate class="needs-validation">
          <input type="hidden" name="id" value="<?= $id; ?>">
          <div class="form-group">
            <input type="text" name="name" value="<?= $name; ?>" class="form-control" placeholder="Họ tên" required>
            <div class="invalid-feedback">Không được để trống</div>
          </div>
          <div class="form-group">
            <input type="text" name="username" value="<?= $username; ?>" class="form-control" placeholder="Tên đăng nhập" required>
            <div class="invalid-feedback">Không được để trống</div>
          </div>
          <div class="form-group">
          <select  class="custom-select" style="height: auto;"  id="selectnv" name="idpb" required >
              <option value="" selected disabled>--Chọn Phòng Ban--</option>
              <?php  
                  $sql = "SELECT * FROM department";      
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                        ?>
                          <option value=<?=$row['idpb']?> <?php if($idpb!=null && $idpb==$row['idpb']){ echo 'selected';}  ?> ><?=$row['namepb']?></option>
                        <?php  
                      }
                  }
              ?>
           </select>
           <div class="invalid-feedback">Không được để trống</div>
        </div>
        <div class="form-group">
            <input type="text" name="position" value="<?php if ($position==null){ echo "Nhân viên";} else echo $position?>" class="form-control" placeholder="Chức vụ" readonly>
          </div>
          <div class="form-group">
            <input type="number" name="number" value="<?php if ($number==null){ echo 12;} else echo $number?>" class="form-control" placeholder="Số ngày nghỉ" readonly>
          </div>
          <div class="form-group">
            <input type="tel" name="phone" value="<?= $phone; ?>" class="form-control" placeholder="Số điện thoại">
          </div>
          <div class="form-group">
            <input type="date" name="birthday" value="<?= $birthday; ?>" class="form-control" placeholder="Ngày sinh" >
          </div>
          <div class="form-group">
            <input type="hidden" name="oldimage" value="<?= $photo; ?>">
            <input type="file" name="image" class="custom-file" accept="image/*">
            <?php
              if($photo!=null){
                ?>
                  <img src="uploads/<?= $photo; ?>" width="120" class="img-thumbnail">
                <?php
              }
            ?> 
          </div>
          <div class="form-group">
            <input type="text" name="address" value="<?= $address; ?>" class="form-control" placeholder="Địa chỉ">
          </div>
          <div class="form-group">
            <?php if ($update == true) { ?>
              <input data-target="#confirmModal" data-toggle="modal" type="button" class="btn" style="border: none; background-color:#E9DCE5; color:#8D4E85; margin-bottom:20px; float:right;" value="Reset mật khẩu">
            <input type="submit" name="update" class="btn btn-block" style="border: none; background-color:#8D4E85; color:white;" value="Lưu">
            <?php } else { ?>
            <input type="submit" name="add" class="btn btn-block" style="border: none; background-color:#8D4E85; color:#E9DCE5;" value="Thêm">
            <?php } ?>
          </div>
        </form>
      </div>
      <div class="col-md-9">
        <?php
          $query = 'SELECT * FROM user, department WHERE user.idpb=department.idpb AND user.position<>"Giám đốc"' ;
     
          $stmt = $conn->prepare($query);
          $stmt->execute();
          $result = $stmt->get_result();
        ?>
        <h3 style="color:#8D4E85;" class="text-center">Danh sách nhân viên</h3>
        <table class="table table-hover" id="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ảnh</th>
              <th>Họ tên</th>
              <th>Tên đăng nhập</th>
              <th>Số điện thoại</th>
              <th>Chức vụ</th>
              <th>Phòng Ban</th>
              <th>Tác vụ</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['id']; ?></td>
              <td><img src="uploads/<?=$row['avatar'];?>" width="50px" height="50px" style="object-fit:cover;"></td>
              <td><?= $row['name']; ?></td>
              <td><?= $row['username']; ?></td>
              <td><?= $row['phone']; ?></td>
              <td><?= $row['position']; ?></td>
              <td><?= $row['namepb']; ?></td>
              <td>
                <a href="details.php?details=<?= $row['id']; ?>" class="btn" style="border: none; background-color:#E9DCE5; color:#8D4E85;">Xem</a>
                <a href="user.php?edit=<?= $row['id']; ?>" class="btn" style="border: none; background-color:#8D4E85; color:white;">Sửa</a>
              </td> 
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="confirmModal" role="dialog">   
    <form action="account.php" method="post">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">         
          <h4 class="modal-title">Reset mật khẩu</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p>Bạn có muốn reset mật khẩu nhân viên "<?=$name?>" về mặt định</p>
          <input hidden type="number" name="id" value=<?=$id?>>
          <input hidden type="text" name="username" value=<?=$username?>>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
          <button type="submit" name="resetpassword" class="btn btn-info">Đồng ý</button>
        </div>
      </div> 
    </div>
    </form>
  </div>


  <!-- Popper JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <script type="text/javascript" src="./main.js?v=1"></script>

</body>

</html>