<?php
    session_start();
    if($_SESSION['first']){
        header('Location: changepass.php');
        exit();  
    }
    if (!isset($_SESSION['username']) || ($_SESSION['position']!="Trưởng phòng" && $_SESSION['position']!="Giám đốc")) {
        header('Location: index.php');
        exit();
    } 
    require_once('database.php');
    $conn = open_database();
    $idtp = $_SESSION['id'];
    $idpb =  $_SESSION['idpb'];
    $p = $_SESSION['position'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt nghỉ phép</title>
    <!-- cdn bs4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css?v=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
</head>

<body>
    <!-- header -->
    <div>
        <nav class="navbar navbar-expand-lg navbar-light h2">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn dashboard">
                    <i class="fas fa-align-left"></i>
                    <span>Menu</span>
                </button>
                <div class="hsearch_container">
                    <input type="text" placeholder="Tìm kiếm..." id="search_duyet">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>

                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-align-justify"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a href="./profile.php">
                                <img
                                <?php
                                    $p = $_SESSION['position'];
                                    $sql = "SELECT * FROM user WHERE position ='$p'";
                                    $tmp=$conn->query($sql);
                                    if ($tmp->num_rows > 0) {
                                        $us = $tmp->fetch_assoc();
                                    }
                                    if($us['avatar']!=null){
                                        $avt = $us['avatar'];
                                        echo "src='uploads/$avt'";
                                    }else{
                                        $tmp='avt_tmp.jpg';
                                        echo "src='images/$tmp'";
                                    }
                                ?>
                                class="rounded-circle" height="32" width="32"
                                style="object-fit:cover;"
                                loading="lazy" />
                            </a>
                        </li>
                        <li class="nav-item">
                            <button  onclick="location.href='logout.php'">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <!-- slidebar -->
    <?php include 'slidebar.php' ?>
    </div>

    <div class="wrapper">
        <div class="row">
            <div class="col-12 col-sm-4 col-md-3 col-lg-2" id="left-bar">
                <hr />
                <div>
                <div class="hsidebar-filter hsidebar-filter-active" id="tatca">Tất cả</div>
                <div class="hsidebar-filter" id="dangdoi">Đang đợi</div>
                <div class="hsidebar-filter" id="dongy">Đồng ý</div>
                <div class="hsidebar-filter" id="tuchoi">Từ chối</div>
                </div>
                <hr />
            </div>
            <div class="col-12 col-sm-8 col-md-9 col-lg-10">
                <div class="">
                    <div id="list" class="hscroll">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Ngày yêu cầu</th>
                                    <th scope="col">Người yêu cầu</th>
                                    <th scope="col">Số ngày xin nghỉ</th>
                                    <th scope="col">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $sql = "SELECT dayoff.id, dayoff.date,dayoff.status, dayoff.numday, user.name FROM dayoff INNER JOIN user WHERE user.id=dayoff.iduser AND user.idpb=$idpb AND user.id!=$idtp ORDER BY dayoff.id DESC";
                                if ($_SESSION['position']=='Giám đốc'){
                                    $sql= "SELECT dayoff.id, dayoff.date,dayoff.status, dayoff.numday, user.name FROM dayoff INNER JOIN user WHERE user.id=dayoff.iduser AND user.position='Trưởng phòng' ORDER BY dayoff.id DESC"; 
                                }
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr class="<?php echo $row['status']; ?>" onclick="location.href='ctnghiphep.php?id=<?=$row['id']?>'">
                                            <th scope="row"><?php echo $i; ?></th>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['numday']; ?></td>

                                            <?php
                                            if ($row['status'] == 'approved') {
                                            ?>
                                                <td class="text-success"><?php echo $row['status']; ?></td>

                                            <?php } ?>
                                            <?php
                                            if ($row['status'] == 'refused') {
                                            ?>
                                                <td class="text-danger"><?php echo $row['status']; ?></td>

                                            <?php } ?>
                                            <?php
                                            if ($row['status'] == 'waiting') {

                                            ?>
                                                <td class="text-warning"><?php echo $row['status']; ?></td>

                                            <?php } ?>
                                        </tr>
                                <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="main.js?v=1"></script>
</body>

</html>