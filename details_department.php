<?php
    include 'room.php';
    if ($_SESSION['first']){
        header('Location: changepass.php');
        exit();
    }
    if (!isset($_SESSION['username']) || $_SESSION['position'] != "Giám đốc") {
        header('Location: index.php');
        exit();
    }
    //echo $_SESSION['nametp'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Sahil Kumar">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Details User</title>
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
    <?php if (isset($_SESSION['response'])) { ?>
        <div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <b><?= $_SESSION['response']; ?></b>
        </div>
        <?php } unset($_SESSION['response']); ?>
    <div class="container ">
    <?php
        if($result2->num_rows>0)
        { 
            while($row2=$result2->fetch_assoc()) {
                ?>
                <table class="table">
                    <tr style="text-align: center; vertical-align:middle;">
                        <td colspan=3><h3 class="font-weight-bold" style="color:#8D4E85; text-transform: uppercase;">PHÒNG <?=$row2['namepb'];?></h3></td></tr>
                    <tr>
                        <td><span>ID: </span><?=$row2['idpb'];?></td>
                        <td><span>SỐ:</span> <?=$row2['numberRoom'];?></td>
                        <td><?=$row2['description'];?><td>
                    </tr>
                </table>
                <?php
            }
        }
    ?>
            <table class="table table-hover" style="text-align: center;" id="data-table">
                <thead style="color:#8D4E85; background-color:#E9DCE5;">
                    <tr>
                        <th>Id</th>
                        <th>Tên</th>
                        <th>Vị Trí</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                if($result->num_rows>0)
                { 
                while($row=$result->fetch_assoc()) {
              ?>
                    <tr>
                        <td><?=$row['id'];?></td>
                        <td><?=$row['name'];?></td>
                        <td><?=$row['position'];?></td>
                        
                        <td>
                          <?php
                            if($row['position']!="Trưởng phòng")
                            {
                          ?>
                            <form action="room.php" method="POST">
                                <input type="hidden" name="id_click" value="<?= $row['id']?>">
                                <input type="hidden" name="id_click_pb" value="<?= $row['idpb']?>">
                                <input type="hidden" name="position" value="<?= $row['position']?>">
                                <button class="btn" style="border: none; background-color:#8D4E85; color:white;" name="update_details">
                                    Bổ nhiệm
                                </button>
                            </form>
                          <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
              }
              ?>
                </tbody>
            </table>
    </div>

  <?php 
    if (isset($_SESSION['idtruongphong']) && isset($_SESSION['idphongban']) && isset($_SESSION['nametp'])){
      ?>
        <!-- Modal -->
        <div class="modal fade" id="confirmTruongPhong" role="dialog">   
          <form action="room.php" method="post">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">         
                <h4 class="modal-title">Chọn trưởng phòng</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <p>Bạn có muốn chọn "<?=$_SESSION['nametp']?>" làm trưởng phòng?</p>
                <input hidden type="number" name="id_click" value=<?=$_SESSION['idtruongphong']?>>
                <input hidden type="number" name="id_click_pb" value=<?=$_SESSION['idphongban']?>>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="submit" name="set_truongphong" class="btn btn-info">Đồng ý</button>
              </div>
            </div> 
          </div>
          </form>
        </div>
      <?php
    }
  ?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <script type="text/javascript" src="./main.js?v=1"></script>

    <script>

    </script>

</body>

</html>