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
    $conn=open_database();

    $phongban = $_SESSION['idpb'];
    $truongphong =  $_SESSION['id'];
    $lastdate="0000-00-00";
    $idyeucau=$_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết yêu cầu</title>
        <!-- cdn bs4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css?v=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
</head>
<body>
    <!-- header -->
    <?php include 'header.php'?>

    <?php
        $currentDate = date('Y-m-d');
        if(isset($_POST['dongy'])){
            $sql = "UPDATE dayoff SET status='approved', daterep='$currentDate' WHERE id=$idyeucau";
            if ($conn->query($sql) === TRUE) {
                echo("<meta http-equiv='refresh' content='0'>");
            } else {
            echo "Error updating record: " . $conn->error;
            }
        }
        if(isset($_POST['tuchoi'])){
            $sql = "UPDATE dayoff SET status='refused',daterep='$currentDate' WHERE id=$idyeucau";
            if ($conn->query($sql) === TRUE) {
                echo("<meta http-equiv='refresh' content='0'>");
            } else {
            echo "Error updating record: " . $conn->error;
            }
        }
    ?>

    <div class="container hscroll">
    <?php
             $sql = "SELECT * FROM dayoff WHERE id =".$idyeucau."";
             $result = $conn->query($sql);
             if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if($row['status']=="waiting"){
                    echo "<div class='hnotice-ct hnotice-waiting'>";
                }
                if($row['status']=="refused"){
                    echo "<div class='hnotice-ct hnotice-rejected'>";
                }
                if($row['status']=="approved"){
                    echo "<div class='hnotice-ct hnotice-success'>";
                }
                $sqlnv = "SELECT * FROM user WHERE id =".$row['iduser']."";
                $tmpnv=$conn->query($sqlnv);
                if ($tmpnv->num_rows > 0) {
                    $nv = $tmpnv->fetch_assoc();
                }
                echo "  
                        <span style='font-weight: bold;'>YÊU CẦU XIN NGHỈ PHÉP</span>
                        <hr>
                        <div class='task_title'><small>Người gừi</small> ".$nv['name']."
                            <span style='float:right;'>
                                <small>Ngày gửi: </small> ".$row['date']."
                            </span>
                        </div>
                        <hr>
                        <div> <small>Lý do xin nghỉ: </small> ".$row['reson']."</div>
                        <br>";
                if($row['file']!=null){
                    $file = explode(',',$row['file']);
                    foreach($file as $key=>$val){
                       echo "<div class='btn btn-outline-primary mr-2' style='max-width:100%;' onclick='download(\"".$val."\")'><i class='fas fa-paperclip'></i> ".$val." </div>"; 
                    }   
                }
                echo "<form method='POST' action=''>";
                if($row['status']=="waiting"){
                    echo "<div style='height: 50px;'>
                            <button name='tuchoi' type='submit' class='btn btn-danger mt-1 ml-2' style='float: right;' data-toggle='modal' data-target='#lamlai-modal'>Từ chối</button>
                            <button name='dongy' type='submit' class='btn btn-success mt-1' style='float: right;' data-toggle='modal' data-target='#dongy-modal'>Đồng ý</button>
                          </div>";
                }
                echo "</form>";
                echo "</div>";
             }
        ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
    integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
    integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="main.js?v=1"></script>
</body>
</html>