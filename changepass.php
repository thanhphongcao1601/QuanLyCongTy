<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['first'] == false) {
    header('Location: index.php');
    exit();
}
require_once('database.php');
$conn = open_database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    $old = '';
    $error = '';
    $pass1 = '';
    $pass2 = '';
    if (isset($_POST['change'])) {
        if (isset($_POST['password']) && isset($_POST['password2'])) {
            $pass1 = $_POST['password'];
            $pass2 = $_POST['password2'];
            if (empty($pass1) || empty($pass2)) {
                $error = 'Pass1 or Pass2 null';
            } else {
                if ($pass1 == $pass2) {
                    if ($pass1 == $_SESSION['username']) {
                        $old = 'Mật khẩu giống mật khẩu mặc định';
                    } else {
                        $newpass = password_hash($pass1, PASSWORD_DEFAULT);
                        $id = $_SESSION['id'];
                        $sql = "UPDATE user SET password='$newpass' WHERE id=$id";
                        if ($conn->query($sql) === TRUE) {
                            $_SESSION['first'] = false;
                            if ($_SESSION['position'] == 'Trưởng phòng') {
                                header('Location: truongphong.php');
                            } elseif ($_SESSION['position'] == 'Nhân viên') {
                                header('Location: nhanvien.php');
                            }
                        } else {
                            echo "Error updating record: " . $conn->error;
                        }
                    }
                } else {
                    $error = 'Pass1 != Pass2';
                }
            }
        }
    }

    ?>
    <div class="">
        <div class="container">
            <div class="row d-flex vh-100">
                <div class="login-card m-auto p-5 card col-sm-12 col-md-7 col-lg-6 ">
                    <h1 class="text-center login-label">ĐỔI MẬT KHẨU MỚI</h1>
                    <h5 class="text-center login-label">(Do đăng nhập lần đầu)</h5>
                    <img class="user m-auto" src="images/userr.png" alt="">
                    <form method="POST" class="needs-validation" id="form-add-task" novalidate>

                        <div class="form-group">
                            <label class="login-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text icon-color" id="password-icon"><i class="fas fa-unlock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu!" required>
                                <div class="input-group-prepend showpass">
                                    <span onclick="showPassword()" class="input-group-text icon-color"><i id="eye-icon" class="fas fa-eye"></i></span>
                                </div>
                                <div class="invalid-feedback ">
                                    Mật khẩu không được trống!
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="login-label">Nhập lại mật khẩu</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text icon-color" id="password-icon"><i class="fas fa-unlock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password2" name="password2" placeholder="Nhập lại mật khẩu!" required>
                                <div class="input-group-prepend showpass">
                                    <span onclick="showPassword2()" class="input-group-text icon-color"><i id="eye-icon2" class="fas fa-eye"></i></span>
                                </div>
                                <div class="invalid-feedback ">
                                    Mật khẩu không được trống!
                                </div>
                            </div>
                        </div>
                        <?php if ($error != "") { ?>
                            <div class='text-danger'>
                                Nhập chưa chính xác.
                            </div>
                        <?php } ?>
                        <?php if ($old != "") { ?>
                            <div class='text-danger'>
                                Hãy đổi mật khẩu khác mật khẩu mặc định.
                            </div>
                        <?php } ?>
                        <br>
                        <input type="submit" class="btn btn-login w-100" value="Đổi mật khẩu" name="change"></input>
                        <hr>
                        <input type="button" class="btn btn-login w-100" value="Đăng xuất" onclick="location.href='logout.php'"></input>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="main.js?v=1"></script>
</body>

</html>