<!-- slidebar -->
<nav id="sidebar">
    <div id="dismiss">
        <i class="fas fa-arrow-left"></i>
    </div>
    <div class="sidebar-header">
        <img id="logonmenu" src="images/logo.png" alt="" srcset="">
    </div>

    <ul class="list-unstyled components">
        <?php 
            if ($_SESSION['position']=='Giám đốc'){
            ?>
                <li class="pdemuc">
                    <a href="./user.php">Quản lý nhân viên</a>
                </li>
                <li class="pdemuc">
                    <a href="./department.php">Quản lý phòng ban</a>
                </li>
            <?php
            }
        ?>          
        <?php 
            if ($_SESSION['position']=='Trưởng phòng'){
            ?>
                <li class="pdemuc">
                    <a href="./truongphong.php">Quản lý công việc</a>
                </li>
            <?php
            }
        ?>
        <?php 
            if ($_SESSION['position']=='Nhân viên'){
            ?>
                <li class="pdemuc">
                    <a href="./nhanvien.php">Quản lý công việc</a>
                </li>
            <?php
            }
        ?>

        <li>
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">Nghỉ phép</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <?php 
                    if ($_SESSION['position']!='Nhân viên'){
                    ?>
                        <li class="pdemuc">
                            <a href="./duyetnghiphep.php">Duyệt nghỉ phép</a>
                        </li>
                    <?php
                    }
                ?>
                <?php 
                    if ($_SESSION['position']!='Giám đốc'){
                    ?>
                        <li class="pdemuc">
                        <a href="./nghiphep.php">Xin nghỉ phép</a>
                        </li>
                    <?php
                    }
                ?>
            </ul>
        </li>
        <li>
            <a href="./profile.php">Thông tin cá nhân</a>
        </li>
    </ul>
</nav>