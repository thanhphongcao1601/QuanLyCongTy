<?php
  //session_start();
  include 'room.php';
  if ($_SESSION['first']){
    header('Location: changepass.php');
    exit();
  }
  if (!isset($_SESSION['username']) || $_SESSION['position'] != "Giám đốc") {
      header('Location: index.php');
      exit();
  }
  $conn=open_database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="author" content="Sahil Kumar">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>department Management</title>
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
        <h3 class="text-center text-dark mt-2 font-weight-bold">QUẢN LÝ PHÒNG BAN</h3>
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
        <h3 class="text-center" style="color:#8D4E85;">Thêm phòng ban</h3>
        <form id="form-add-task" action="room.php" method="post" enctype="multipart/form-data" novalidate class="needs-validation">
          <input type="hidden" name="idpb" value="<?= $id; ?>">
          <div class="form-group">
            <input type="text" name="namepb" value="<?= $namepb; ?>" class="form-control" placeholder="Tên phòng ban" required>
            <div class="invalid-feedback">Không được để trống</div>
          </div>
          <div class="form-group">
            <input type="text" name="description" value="<?= $description; ?>" class="form-control" placeholder="Mô tả" required>
            <div class="invalid-feedback">Không được để trống</div>
          </div>
          <div class="form-group">
            <input type="text" name="numberRoom" value="<?= $numberRoom; ?>"  class="form-control" placeholder="Số phòng" required>
            <div class="invalid-feedback">Không được để trống</div>
          </div>
          <div class="form-group">
            <?php if ($update == true) { ?>
            <input type="submit" name="update" class="btn btn-block" style="background-color:#8D4E85; color: white;" value="Cập Nhật">
            <?php } else { ?>
            <input type="submit" name="add" class="btn btn-block" style="border: none; background-color:#8D4E85; color: white;" value="Thêm">
            <?php } ?>
          </div>
        </form>
      </div>
      <div class="col-md-9">
        <?php
          $query = 'SELECT * FROM department';
          $conn=open_database();
          $stmt = $conn->prepare($query);

          $stmt->execute();
          $result = $stmt->get_result();
        ?>
        <h3 class="text-center" style="color:#8D4E85;">Danh sách phòng ban</h3>
        <table class="table table-hover" style="text-align: center;" id="data-table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Tên Phòng Ban</th>
              <th>Mô Tả</th>
              <th>Số Phòng</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['idpb']; ?></td>
              <td><?= $row['namepb']; ?></td>
              <td><?= $row['description']; ?></td>
              <td><?= $row['numberRoom']; ?></td>
              <td>
                <a href="details_department.php?details_department=<?= $row['idpb']; ?>" 
                    class="btn" style="border: none; background-color:#E9DCE5; color:#8D4E85;">Xem</a>
                <a href="department.php?edit=<?= $row['idpb']; ?>" 
                    class="btn" style="border: none; background-color:#8D4E85; color:white;">Sửa</a>
              </td> 
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <script type="text/javascript" src="./main.js?v=1"></script>
</body>

</html>