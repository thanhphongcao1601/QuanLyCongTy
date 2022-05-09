<?php
    session_start();
    require_once('database.php');
    $conn = open_database();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Management: Login</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    function login($user, $pass)
    {
        $conn = open_database();
        $stm = $conn->prepare("select * from user where username=?");
        $stm->bind_param('s', $user);
        if (!$stm->execute()) {
            return null;
        }
        $result = $stm->get_result();
        if ($result->num_rows == 0) {
            return null;
        }
        $data = $result->fetch_assoc();
        $hashed_password = $data['password'];
        if (!password_verify($pass, $hashed_password)) {
            return null;
        } else {
            return $data;
        }
    }
    //
    $error = '';
    $user = '';
    $pass = '';
    if (isset($_POST['login'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            if (empty($user)) {
                $error = 'Please enter your username';
            } else if (empty($pass)) {
                $error = 'Please enter your password';
            } else {
                $data = login($user, $pass);
                if ($data) {
                    if (!isset($_POST['remember'])) {
                        $checkboxValue = false;
                    } else {
                        $checkboxValue = $_POST['remember'];
                    }
                    if ($checkboxValue) {
                        setcookie("username", $_POST['username']);
                        setcookie("password", $_POST['password']);
                    } else {
                        setcookie("username", "");
                        setcookie("password", "");
                    }
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['idpb'] = $data['idpb'];
                    $_SESSION['position'] = $data['position'];
                    $_SESSION['name'] = $data['name'];
                    $_SESSION['username'] = $data['username'];
                    if($user==$pass){
                        $_SESSION['first'] = true;
                        header('Location: changepass.php');
                    }else{
                        $_SESSION['first'] = false;
                        if($data['position']=='Nhân viên'){
                        header('Location: nhanvien.php');
                        }
                        if($data['position']=='Trưởng phòng'){
                            header('Location: truongphong.php');
                        }
                        if($data['position']=='Giám đốc'){
                            header('Location: user.php');
                        }
                    }
                    exit();
                } else {
                    $error = 'Invalid username or password';
                }
            }
        }
    }

    ?>
    <div class="">
        <div class="background "></div>
        <div class="background2"></div>
        <div class="container">
            <div class="row d-flex vh-100">
                <img class="img m-auto col-md-5 col-lg-6" src="images/image.png" alt="">
                <div class="login-card m-auto p-5 card col-sm-12 col-md-7 col-lg-6 ">
                    <h1 class="text-center login-label">ĐĂNG NHẬP</h1>
                    <img class="user m-auto" src="images/userr.png" alt="">
                    <form method="POST" class="needs-validation" id="form-add-task" novalidate>
                        <div class="form-group ">
                            <label class="login-label">Tên đăng nhập</label>
                            <div class="input-group ">
                                <div class="input-group-prepend">
                                    <span class="input-group-text icon-color" id="user-icon"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng của bạn!" value="<?php if(isset($_COOKIE["username"])) echo $_COOKIE["username"];?>" required>
                                <div class="invalid-feedback ">
                                    Tên đăng nhập không được trống!
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="login-label">Mật khẩu</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text icon-color" id="password-icon"><i class="fas fa-unlock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu của bạn!" value="<?php if(isset($_COOKIE["password"])) echo $_COOKIE['password'];?>" required>
                                <div class="input-group-prepend showpass">
                                    <span onclick="showPassword()" class="input-group-text icon-color"><i id="eye-icon" class="fas fa-eye"></i></span>
                                </div>
                                <div class="invalid-feedback ">
                                    Mật khẩu không được trống!
                                </div>
                            </div>
                        </div>
                        <?php if($error!=""){ ?>
                        <div class='text-danger'>
                            Tên đăng nhập hoặc mật khẩu không đúng.
                        </div>
                        <?php } ?>
                        <div class="form-check w-100">
                            <input type="checkbox" class="form-check-input" name="remember" id="checkbox">
                            <label class="form-check-label text-secondary">Ghi nhớ đăng nhập</label>
                            <a class="forgot" href="">Quên mật khẩu?</a>
                        </div>
                        <input type="submit" class="btn btn-login w-100" value="Đăng nhập" name="login"></input>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="main.js"></script>
</body>

</html>