
<?php
    session_start();
    require_once('database.php');
    $conn=open_database();
    //$iduser=$_GET['id'];
    $iduser = $_SESSION['id'];
    $pos = $_SESSION['position'];

    //lay thong tin ca nhan cua user
    $sql = "SELECT * FROM user WHERE id =".$iduser."";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $uid = $row['id'];
        $uname = $row['name'];
        $uusername = $row['username'];
        $ugender = $row['gender'];
        $upassword = $row['password'];

        if ($ugender == 0){
            $ugenderStr = 'Nữ';
        }else{
            $ugenderStr = 'Nam';
        }
        $uphone = $row['phone'];
        $ubirthday = $row['birthday'];
        $uavatar = $row['avatar'];
        $uaddress = $row['address'];
        $uposition = $row['position'];
        $ucmnd = $row['cmnd'];
        $uemail = $row['email'];
        $uidpb = $row['idpb'];
    } else {
        echo 'buggggggg';
    }

    //lay ten phong ban cua user
    if ($uidpb!=null){
        $sqlpb = "SELECT * FROM department WHERE idpb =".$uidpb."";
        $result2 = $conn->query($sqlpb);
        if ($result2->num_rows > 0) {
            $row = $result2->fetch_assoc();
            $unamepb = $row['namepb'];
        }else {
            echo 'buggggggg22222';
        }
    } else{
        $unamepb = 'VIP';
    }


    $sqltask = "";
    //lay thong ke task cua user
    if($pos=="Trưởng phòng"){
        $sqltask = "SELECT * FROM task WHERE idtp =$iduser";
    }
    if($pos=="Nhân viên"){
        $sqltask = "SELECT * FROM task WHERE idnv =$iduser";
    }

    $numNew = 0;
    $numInprogress = 0;
    $numCancel= 0;
    $numWaiting= 0;
    $numRejected= 0;
    $numCompleted= 0;
    $numTotal = 0;

    if ($sqltask!=""){
        $resultTask = $conn->query($sqltask);
        if ($resultTask->num_rows > 0) {
            while($row = $resultTask->fetch_assoc()) {
                if($row['status']=="completed"){
                    $numCompleted +=1;
                }
                if($row['status']=="new"){
                    $numNew +=1;
                }
                if($row['status']=="rejected"){
                   $numRejected +=1;
                }
                if($row['status']=="waiting"){
                    $numWaiting +=1;
                }
                if($row['status']=="inprogress"){
                    $numInprogress +=1;
                }
                if($row['status']=="canceled"){
                    $numCancel +=1;
                }
                $numTotal +=1;
            }
        }
    }
?>


    <?php
    if(isset($_POST['updateAvatar'])){
        $Dir = "uploads/";
        $file = $_FILES['file']['name'];

        $newName="";
        $fileName="";
        if($file!=null){
            $salt = time();
            $newName=$salt."_".$file;
            $path = $Dir.$salt."_".$file;
            move_uploaded_file($_FILES['file']['tmp_name'],$path);
        }

        $sql = "UPDATE user SET avatar='$newName' WHERE id='$iduser'";
        if ($conn->query($sql) === FALSE) {
            echo "Error updating record: " . $conn->error;
        }else{
            if ($conn->query($sql) === TRUE) {
                if($uavatar!=null){
                    unlink("uploads/".$uavatar);
                }
                // $_SESSION['response']="Cập nhật ảnh đại diện thành công!";
                // $_SESSION['res_type']="success";
                echo("<meta http-equiv='refresh' content='0'>");
            } else {
                $_SESSION['response']="Cập nhật ảnh đại diện thất bại!".$conn->error;
                $_SESSION['res_type']="danger";
                echo("<meta http-equiv='refresh' content='0'>");
            }
        }   
    } 
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css?=1">


</head>
<body>       
    <!-- header -->
    <?php include 'header_nosearch.php' ?>
    <div class="container profile-container hscroll">
        <div class="row">
            <img style="object-fit:cover;" src="images/anhbia.jpg" alt="" class="anhbia">
            <div class="avt-group m-auto">
                <?php 
                    if ($uavatar==null){
                        echo '<img src="https://secure.gravatar.com/avatar/964663d0d333d8b679708d98a01a45c9/?s=48&d=https://images.binaryfortress.com/General/UnknownUser1024.png" id="my-avt" class="avatar">';
                    } else{
                        echo '<img src="uploads/'.$uavatar.'" id="my-avt" class="avatar">';
                    }
                ?>
                <div onclick="" class="edit-avt d-flex justify-content-center align-items-center">
                    <input type="file" name="" id="input-avt" hidden>
                    <button class="btn-setavt" data-toggle="modal" data-target="#myModalAvatar"><i class="fas fa-camera"></i></label></button>
                </div>
            </div>
        </div>
        <!-- thong bao alert -->
        <?php 
            if (isset($_SESSION['response'])) { 
            ?>
                <div id="testhidez" class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <b><?= $_SESSION['response']; ?></b>
                </div>
            <?php 
            } 
            unset($_SESSION['response']); 
        ?>

        <div class="row p-5">
        <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card card-pf p-3 mb-3">
                    <div class="d-flex ">
                        <label><b>Tất cả: </b><?=$numTotal?></label>
                    </div>
                    <?php 
                        if ($numNew>0){
                            ?>
                            <div class="d-flex">
                                <label>New  </label>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                    style="width: <?=$numNew/$numTotal*100?>%" aria-valuenow="<?=$numNew/$numTotal*100?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <?php 
                        if ($numInprogress>0){
                            ?>
                            <div class="d-flex">
                                <label>In progress</label>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                    style="background-color:chocolate; width: <?=$numInprogress/$numTotal*100?>%" aria-valuenow="<?=$numInprogress/$numTotal*100?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <?php 
                        if ($numCancel>0){
                            ?>
                            <div class="d-flex">
                                <label>Cancel</label>
                                <div class="progress">
                                    <div class="progress-bar bg-secondary" role="progressbar" 
                                    style="width: <?=$numCancel/$numTotal*100?>%" aria-valuenow="<?=$numCancel/$numTotal*100?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <?php 
                        if ($numWaiting>0){
                            ?>
                            <div class="d-flex">
                                <label>Waiting</label>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                    style="width: <?=$numWaiting/$numTotal*100?>%" aria-valuenow="<?=$numWaiting/$numTotal*100?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <?php 
                        if ($numRejected>0){
                            ?>
                            <div class="d-flex">
                                <label>Rejected</label>
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" 
                                    style="width: <?=$numRejected/$numTotal*100?>%" aria-valuenow="<?=$numRejected/$numTotal*100?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <?php 
                        if ($numCompleted>0){
                            ?>
                            <div class="d-flex">
                                <label>Completed</label>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                    style="width: <?=$numCompleted/$numTotal*100?>%" aria-valuenow="<?=$numCompleted/$numTotal*100?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card card-pf p-2 mb-4">
                    <h5 class="card-info-label">Thông tin cá nhân</h5>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> MÃ NHÂN VIÊN </label>
                        <div class="info-content col-9"><?=$iduser?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> HỌ TÊN </label>
                        <div class="info-content col-9"><?=$uname?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> GIỚI TÍNH </label>
                        <div class="info-content col-9"><?=$ugenderStr?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> SINH NHẬT </label>
                        <div class="info-content col-9"><?=$ubirthday?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> CMND </label>
                        <div class="info-content col-9"><?=$ucmnd?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> PHÒNG BAN</label>
                        <div class="info-content col-9"><?=$unamepb?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> CHỨC VỤ </label>
                        <div class="info-content col-9"><?=$uposition?></div>
                    </div>
                </div>
                <div class="card card-pf p-2 mb-4">
                    <h5 class="card-info-label">THÔNG TIN LIÊN HỆ</h5>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> SỐ ĐIỆN THOẠI </label>
                        <div class="info-content col-9"><?=$uphone?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> EMAIL </label>
                        <div class="info-content col-9"><?=$uemail?></div>
                    </div>
                    <div class="d-flex row-info">
                        <label class="info-label col-3"> ĐỊA CHỈ </label>
                        <div class="info-content col-9"><?=$uaddress?></div>
                    </div>
                    <div>
                        <a style="float:right;" href="#" data-toggle="modal" data-target="#myModalResetPassword">Đổi mật khẩu</a>
                    </div>
                </div>
                <div>
                    <button class="btn btn-edit-profile" data-toggle="modal" data-target="#myModal"> SỬA</button>
                </div>
            </div>
        </div>


        <?php
            if(isset($_POST['updateProfile'])){
                $sGender = $_POST['gender'];
                $sBirthday = $_POST['birthday'];
                $sIdentityCard = $_POST['identityCard'];
                $sPhone = $_POST['phone'];
                $sEmail = $_POST['email'];
                $sAddress = $_POST['address'];

                $sql = "UPDATE user SET phone=?, birthday=?, cmnd=?, email = ?, address=?, gender = ? WHERE id=?";
                $stmt=$conn->prepare($sql);
                $stmt->bind_param("sssssii",$sPhone,$sBirthday,$sIdentityCard,$sEmail,$sAddress,$sGender,$iduser);
                    
                if ($stmt->execute() === TRUE) {
                    $_SESSION['response']="Cập nhật thông tin thành công!";
                    $_SESSION['res_type']="success";
                    echo("<meta http-equiv='refresh' content='0'>");
                } else {
                    $_SESSION['response']="Cập nhật thông tin thất bại!";
                    $_SESSION['res_type']="danger";
                    echo("<meta http-equiv='refresh' content='0'>");
                }
            }
        ?>
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title login-label">Sửa</h4>
                    </div>
                    <div class="modal-body">
                        <!--  -->
                            <form method="POST" novalidate>
                               <div class="">
                                    <div class="form-group col-12">
                                        <label>Giới tính</label>
                                        <div>
                                            <input id="male" type="radio" name="gender" value=1 <?php 
                                                if ($ugender==1){
                                                    echo 'checked';
                                                }
                                            ?>>
                                            <label for="male">Nam</label>
                                            <input id="female" type="radio" name="gender" value=0 <?php 
                                                if ($ugender==0){
                                                    echo 'checked';
                                                }
                                            ?>>
                                            <label for="female">Nữ</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Sinh nhật</label>
                                        <input value="<?=$ubirthday?>" type="date" class="form-control " name="birthday" placeholder="Birthday" required>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Chứng minh thư</label>
                                        <input type="text" class="form-control " name="identityCard" placeholder="Identity card" value="<?=$ucmnd?>" required>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Số điện thoại</label>
                                        <input value="<?=$uphone?>" type="text" class="form-control " name="phone" placeholder="Phone number" required>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Email</label>
                                        <input type="email" class="form-control " name="email" placeholder="Email" value="<?=$uemail?>" required>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Địa chỉ</label>
                                        <input value="<?=$uaddress?>" type="text" class="form-control " name="address" placeholder="Address" required>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="submit" name="updateProfile" class="btn btn-primary">Lưu</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    </div>
                               </div>
                            </form>
                        <!--  -->
                    </div>
                    
                </div>
            
            </div>
        </div>
        <!--end modal -->

            <!-- Modal Avatar -->
        <div class="modal fade" id="myModalAvatar" role="dialog">
            <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title login-label">Sửa</h4>
                        </div>
                        <div class="modal-body">
                            <!--  -->
                                <form method="POST" novalidate enctype="multipart/form-data">
                                <div class="">
                                    <div class="form-group col-12">
                                            <label for="" >Thêm ảnh đại diện</label>
                                            <input type="file" class=" custom-file-input" 
                                                accept="image/*"
                                                id="filepost" accept="image/*" hidden name="file" onchange="updateList()">
                                            <label for="filepost" class="btn btn-primary btn-sm form-control" >
                                                <i class="fas fa-cloud-upload-alt" style="font-size: 20px;"></i>
                                            </label>
                                            <div id="fileList"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="updateAvatar" class="btn btn-primary">Lưu</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                        </div>
                                </div>
                                </form>
                            <!--  -->
                        </div>
                        
                    </div>
                
                </div>
            </div>
        </div>                                        

    <?php
        if(isset($_POST['resetPassword'])){
            $soldpass = $_POST['oldpass'];
            $snewpass = $_POST['newpass'];
            $snewpass2 = $_POST['newpass2'];
                
            if (isset($soldpass) && isset($snewpass) && isset($snewpass2)){
                if(password_verify($soldpass, $upassword) && $snewpass==$snewpass2 ) {
                    $hashed_password = password_hash($snewpass, PASSWORD_DEFAULT);
                    $sql = "UPDATE user SET password='$hashed_password'
                        WHERE id='$iduser'";
                    if ($conn->query($sql) === FALSE) {
                        echo "Error updating record: " . $conn->error;
                    }else{
                        if ($conn->query($sql) === TRUE) {
                            //echo 'doi mat khau thanh congggggg!';
                            $_SESSION['response']="Đổi mật khẩu thành công!";
                            $_SESSION['res_type']="success";
                            echo("<meta http-equiv='refresh' content='0'>");
                        } else {
                            echo "Error updating record: " . $conn->error;
                        }
                    }
                } else{
                    //echo 'doi mat khau that baiiiiiiiiiiiiiiiiiiiiiii';
                    $_SESSION['response']="Đổi mật khẩu thất bại!";
                    $_SESSION['res_type']="danger";
                    echo("<meta http-equiv='refresh' content='0'>");
                }
            }
        }
        ?>
    <!--Modal Resetpassword-->
    <div class="modal fade" id="myModalResetPassword" role="dialog">
        <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title login-label">Sửa</h4>
                    </div>
                    <div class="modal-body">
                        <!--  -->
                            <form id="form-add-task" method="POST" novalidate enctype="multipart/form-data" class="need-validated">
                               <div class="">
                                    <div class="form-group col-12" > 
                                        <label >Nhập mật khẩu cũ</label>
                                        <input type="password" class="form-control " name="oldpass" placeholder="" required>
                                        <div class="invalid-feedback">Không được để trống</div>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Nhập mật khẩu mới</label>
                                        <input type="password" class="form-control " name="newpass" placeholder="" required>
                                        <div class="invalid-feedback">Không được để trống</div>
                                    </div>
                                    <div class="form-group col-12" > 
                                        <label >Nhập lại mật khẩu mới</label>
                                        <input type="password" class="form-control " name="newpass2" placeholder="" required>
                                        <div class="invalid-feedback">Không được để trống</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="resetPassword" class="btn btn-primary">Đổi mật khẩu</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                    </div>
                               </div>
                            </form>
                        <!--  -->
                    </div>
                    
                </div>
            
            </div>
        </div>
    </div>          
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="./main.js?v=1"></script>
</body>
</html>