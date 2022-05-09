<?php
	session_start();
	include 'database.php';
	$update=false;
	$idpb="";
	$namepb="";
	$description="";
	$numberRoom="";

	//Them phong ban
	if(isset($_POST['add'])){
		if(isset($_POST['namepb']) && isset($_POST['description']) && isset($_POST['numberRoom'])){
			$namepb=$_POST['namepb'];
			$description=$_POST['description'];
			$numberRoom=$_POST['numberRoom'];
			if(isset($_POST['add']))
			{
				$conn=open_database();
				$sqlcheck = "SELECT * FROM department WHERE namepb=?";
				$stmtcheck = $conn->prepare($sqlcheck);
				$stmtcheck->bind_param("s",$namepb);
				$stmtcheck->execute();
				$resultcheck=$stmtcheck->get_result();
				if ($resultcheck->num_rows==0){
					$sql="INSERT INTO department(namepb,description,numberRoom)VALUES(?,?,?)";
					$conn=open_database();
					$stmt=$conn->prepare($sql);
					$stmt->bind_param("sss",$namepb,$description,$numberRoom);
					$stmt->execute();
					header('location:department.php');
					$_SESSION['response']="Bạn đã thêm phòng ban thành công";
					$_SESSION['res_type']="success";
				}
				else {
					header('location:user.php');
					$_SESSION['response']="Tên phòng ban ".$namepb." đã tồn tại, sử dụng ô tìm kiếm để kiểm tra trước!";
					$_SESSION['res_type']="danger";
				}
			}
		}
    }

	//Click btn sua
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];
		$query="SELECT * FROM department WHERE idpb=?";
		$conn=open_database();
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		$idpb=$row['idpb'];
		$namepb=$row['namepb'];
		$description=$row['description'];
		$numberRoom=$row['numberRoom'];
		$update=true;
	}

	//Sua phong ban
	if(isset($_POST['update'])){
		if(isset($_POST['namepb']) && isset($_POST['description']) && isset($_POST['numberRoom'])){
			$idpb=$_POST['idpb'];
			$namepb=$_POST['namepb'];
			$description=$_POST['description'];
			$numberRoom=$_POST['numberRoom'];
			
			$query="UPDATE department SET namepb=?,description=?,numberRoom=? WHERE idpb=?";
			$conn=open_database();
			$stmt=$conn->prepare($query);
			$stmt->bind_param("sssi",$namepb,$description,$numberRoom, $idpb);
			$stmt->execute();
			$_SESSION['response']="Bạn đã cập nhật thành công";
			$_SESSION['res_type']="primary";
			header('location:department.php');
			exit;
		}
	}
	if(isset($_GET['details_department'])){
        $idpb=$_GET['details_department'];
        $query= "SELECT * FROM user WHERE idpb=?";
		$conn=open_database();
        $stmt= $conn->prepare($query);
		$stmt->bind_param("i",$idpb);
		$stmt->execute();
		$result=$stmt->get_result();

		//get thong tin phong ban hien tai
		$query2= "SELECT * FROM department WHERE idpb=?";
		$stmt2= $conn->prepare($query2);
		$stmt2->bind_param("i",$idpb);
		$stmt2->execute();
		$result2=$stmt2->get_result();
	}

	//click btn bo nhiem, lay thong tin
    if(isset($_POST['update_details'])){
		if (isset($_POST['id_click']) && isset($_POST['id_click_pb'])){
			$id=$_POST['id_click'];
			$idpb=$_POST['id_click_pb'];
			$conn=open_database();
			$query2= "SELECT * FROM user WHERE id=?";
			$stmt2= $conn->prepare($query2);
			$stmt2->bind_param("i",$id);
			$stmt2->execute();
			$result2=$stmt2->get_result();
			$row2=$result2->fetch_assoc();
			$_SESSION['nametp'] = $row2['name'];
			$_SESSION['idphongban'] = $row2['idpb'];
			$_SESSION['idtruongphong'] = $row2['id'];
			header("location:details_department.php?details_department=$idpb");
		}
	}

	if(isset($_POST['set_truongphong'])){
		if (isset($_POST['id_click']) && isset($_POST['id_click_pb'])){
			$id=$_POST['id_click'];
			$idpb=$_POST['id_click_pb'];

			unset($_SESSION['nametp']);
			unset($_SESSION['idphongban']);
			unset($_SESSION['idtruongphong']);

			$sql="UPDATE user SET position='Nhân viên',numofdaysoff=12 WHERE user.idpb = $idpb";
			$conn=open_database();
			$query=$conn->query($sql);

			$position="Trưởng phòng";
			$query="UPDATE user SET position=?, numofdaysoff=15 WHERE id=?";
			$stmt=$conn->prepare($query);
			$stmt->bind_param("si",$position, $id);
			$stmt->execute();
			$_SESSION['response']="Bạn đã cập nhật Trưởng Phòng Thành công";
			$_SESSION['res_type']="primary";
			header("location:details_department.php?details_department=$idpb");
		}
	}
?>