<?php
session_start();
if ($_SESSION['first']) {
    header('Location: changepass.php');
    exit();
}
if (!isset($_SESSION['username']) || $_SESSION['position'] != "Nhân viên") {
    header('Location: index.php');
    exit();
}
require_once('database.php');
$conn = open_database();

$idnv = $_SESSION['id'];
$phongban =  $_SESSION['idpb'];
$idtask = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết task</title>
    <!-- cdn bs4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css?v=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
</head>

<body>
    <!-- header -->
    <?php include 'header.php' ?>

    <!-- thong bao alert -->
    <?php 
        if (isset($_SESSION['response'])) { 
        ?>
            <div id="testhide" class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <b><?= $_SESSION['response']; ?></b>
            </div>
        <?php 
        } 
        unset($_SESSION['response']); 
    ?>
    <?php
    $err_mess = "";
    if (isset($_POST['inprogress'])) {
        $sql = "UPDATE task SET status='inprogress' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    if (isset($_POST['good'])) {
        $sql = "UPDATE task SET react='GOOD' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $sql = "UPDATE task SET status='completed' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    if (isset($_POST['ok'])) {
        $sql = "UPDATE task SET react='OK' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $sql = "UPDATE task SET status='completed' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    if (isset($_POST['bad'])) {
        $sql = "UPDATE task SET react='BAD' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $sql = "UPDATE task SET status='completed' WHERE idtask=$idtask";
        if ($conn->query($sql) === TRUE) {
            echo ("<meta http-equiv='refresh' content='0'>");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    ?>

    <div class="container hscroll">
        <!--Task hien tai-->
        <?php
        $sql = "SELECT * FROM task WHERE idtask =" . $idtask . " AND status!='canceled'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $dealine = $row['deadline'];
            if ($row['status'] == "completed") {
                echo "<div class='hnotice-ct hnotice-success'>";
            }
            if ($row['status'] == "new") {
                echo "<div class='hnotice-ct hnotice-new'>";
            }
            if ($row['status'] == "waiting") {
                echo "<div class='hnotice-ct hnotice-waiting'>";
            }
            if ($row['status'] == "rejected") {
                echo "<div class='hnotice-ct hnotice-rejected'>";
            }
            if ($row['status'] == "inprogress") {
                echo "<div class='hnotice-ct hnotice-inprogress'>";
            }
            $sqlnv = "SELECT * FROM user WHERE id =" . $row['idnv'] . "";
            $tmpnv = $conn->query($sqlnv);
            if ($tmpnv->num_rows > 0) {
                $nv = $tmpnv->fetch_assoc();
            }
            $idnv = $nv['id'];
            echo "  <strong class='htask_status'>" . $row['status'] . "</strong>
                        <div>Nhân viên thực hiện: <span style='font-weight: bold;'>" . $nv['name'] . "</span></div>
                        <br>
                        <div class='task_title'>" . $row['title'] . "
                            <span style='color: black; font-weight: bold; float:right;'>
                                <small>Deadline: </small> " . $row['deadline'] . "
                            </span>
                        </div>
                        <hr>
                        <div >" . $row['content'] . "</div>
                        <br>";
            if ($row['filedelivered'] != null) {
                $file = explode(',', $row['filedelivered']);
                foreach ($file as $key => $val) {
                    echo "<div class='btn btn-outline-primary mr-2'style='max-width:100%;' onclick='download(\"" . $val . "\")'><i class='fas fa-paperclip'></i> " . $val . " </div>";
                }
            }
            echo "<form method='POST' action=''>";
            if ($row['status'] == "new") {
                echo "<div style='height: 50px;'>
                            <button name='inprogress' type='submit' class='btn btn-success' style='float: right;'>Bắt đầu</button>
                          </div>";
            }
            if ($row['status'] == "inprogress" || $row['status'] == "rejected") {
                echo "<div style='height: 50px;'>
                            <button name='' type='button' class='btn btn-primary mt-1 ml-2' style='float: right;' data-toggle='modal' data-target='#nop-modal'>Nộp</button>
                          </div>";
            }
            echo "</form>";
            if ($row['status'] == "completed") {
                if ($row['react'] == 'GOOD') {
                    echo "<div style='height:40px'> <img class='react' src='./images/good.png'> </div>";
                }
                if ($row['react'] == 'OK') {
                    echo "<div style='height:45px'> <img class='react' src='./images/ok.png'> </div>";
                }
                if ($row['react'] == 'BAD') {
                    echo " <div style='height:40px'> <img class='react' src='./images/bad.png'> </div>";
                }
            }

            echo "</div>";
        }

        $sql = "SELECT * FROM history WHERE idtask =$idtask ORDER BY idhis DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

        ?>
            <br>
            <hr>
            <div style="margin-bottom: 10px; font-weight: bold; font-size: 20px;">Lịch sử</div>
        <?php
            while ($row = $result->fetch_assoc()) {
                $sqlsent = "SELECT * FROM user WHERE id =" . $row['idSent'] . "";
                $tmp = $conn->query($sqlsent);
                if ($tmp->num_rows > 0) {
                    $sent = $tmp->fetch_assoc();
                }
                echo " <div id='dongthoigian'>
                                <div class='hnotice-ct'>
                                    <span style='float:right; font-style: italic;'>" . $row['date'] . "</span>
                                    <div>From: <strong style='font-size:17px'> " . $sent['name'] . "</strong></div>
                                    <div class='htask_title'>" . $row['title'] . "</div>
                                    <div>" . $row['content'] . "</div><br>";
                if ($row['file'] != null) {
                    $file = explode(',', $row['file']);
                    foreach ($file as $key => $val) {
                        echo "<div class='btn btn-outline-primary mr-2' style='max-width:100%;' onclick='download(\"" . $val . "\")'><i class='fas fa-paperclip'></i> " . $val . " </div>";
                    }
                }
                echo       " </div>
                            </div>";
            }
        }

        ?>
    </div>

    <?php
    if (isset($_POST['waiting'])) {
        $mess = "";
        $Dir = "files/";
        $file = $_FILES['file']['name'];
        $newName = array();
        $fileName = "";
        if (isset($_POST['content']) && isset($_POST['title'])) {
            if ($file[0] != null) {
                foreach ($file as $key => $val) {
                    $salt = time();
                    array_push($newName, $salt . "_" . $val);
                    $path = $Dir . $salt . "_" . $val;
                    move_uploaded_file($_FILES['file']['tmp_name'][$key], $path);
                }
                $fileName = implode(",", $newName);
            }
            $currentDate = date('Y-m-d');
            $sql = "INSERT INTO  history (idtask,idnv,content,title,file,idSent,date) 
            VALUES ('" . $idtask . "','" . $idnv . "','" . $_POST['content'] . "','" . $_POST['title'] . "','" . $fileName . "','" . $idnv . "','".$currentDate."')";
            if ($conn->query($sql) === FALSE) {
                echo "Error updating record: " . $conn->error;
            } else {
                $sql = "UPDATE task SET status='waiting' WHERE idtask=$idtask";
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['response']="Nộp task thành công!";
                    $_SESSION['res_type']="success";
                    echo("<meta http-equiv='refresh' content='0'>");
                } else {
                    echo "Error updating record: " . $conn->error;
                    $_SESSION['response']="Nộp task thất bại!";
                    $_SESSION['res_type']="danger";
                    echo("<meta http-equiv='refresh' content='0'>");
                }
            }
        }
    }
    ?>

    <!-- Modal submit -->
    <div class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" id="nop-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nộp</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="needs-validation" enctype="multipart/form-data" id="form-add-task" novalidate>
                        <div class="form-group">
                            <label for="">Tiêu đề</label>
                            <input type="text" class="form-control" name="title" required>
                            <div class="invalid-feedback">
                                Chưa đặt tiêu đề cho task
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Nội dung</label>
                            <textarea type="text" class="form-control" name="content" required></textarea>
                            <div class="invalid-feedback">
                                Chưa đặt nội dung cho task
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Thêm đính kèm (&lt;200MB)</label>
                            <input type="file" class=" custom-file-input" id="filepost" 
                                accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf,.zip,.rar"
                                multiple hidden name="file[]" onchange="updateList()">
                            <label for="filepost" class="btn btn-primary btn-sm form-control">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 20px;"></i>
                            </label>
                            <div id="fileList"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button id="btn_tao" type="submit" class="btn btn-primary" name="waiting">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="main.js?v=1"></script>
</body>

</html>