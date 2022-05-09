<?php
	session_start(); 
	include 'database.php';
	$update=false;
	$id="";
	$name="";
	$username="";
	$password="";
	$phone="";
	$birthday="";
	$photo="";
	$address="";
	$number="";
	$position="";
	$idpb="";

	//Them nhan vien
	if(isset($_POST['add'])){
		if(isset($_POST['idpb']) && isset($_POST['username']) && isset($_POST['name'])){
			$idpb=$_POST['idpb'];
			$name=$_POST['name'];
			$username=$_POST['username'];
			$password = password_hash($username, PASSWORD_DEFAULT);
			$phone=$_POST['phone'];
			$birthday=$_POST['birthday'];
			$position="Nhân viên";
			$number = 12;
			$photo=time().$_FILES['image']['name'];
			$address=$_POST['address'];
			$upload="uploads/".$photo;

			$conn=open_database();
			$sqlcheck = "SELECT * FROM user WHERE username=?";
			$stmtcheck = $conn->prepare($sqlcheck);
			$stmtcheck->bind_param("s",$username);
			$stmtcheck->execute();
			$resultcheck=$stmtcheck->get_result();
			if ($resultcheck->num_rows==0){
				$sql="INSERT INTO user(name,username,password,phone,birthday,avatar,address,position,idpb,numofdaysoff)VALUES(?,?,?,?,?,?,?,?,?,?)";

				$stmt=$conn->prepare($sql);
				$stmt->bind_param("ssssssssii",$name,$username,$password,$phone,$birthday,$photo,$address,$position,$idpb,$number);
				$stmt->execute();
				
				move_uploaded_file($_FILES['image']['tmp_name'], $upload);
				header('location:user.php');
				$_SESSION['response']="Thêm nhân viên thành công!";
				$_SESSION['res_type']="success";
			}
			else {
				header('location:user.php');
				$_SESSION['response']="Username ".$username." đã tồn tại, sử dụng ô tìm kiếm để kiểm tra trước!";
				$_SESSION['res_type']="danger";
			}

			
		}
	}

	//click btn sua
	if(isset($_GET['edit'])){
		$id=$_GET['edit'];
		$query="SELECT * FROM user WHERE id=?";
		$conn=open_database();
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		//du lieu hien thi ra view
		$id=$row['id'];
		$name=$row['name'];
		$username=$row['username'];
		$password=$row['password'];
		$phone=$row['phone'];
		$birthday=$row['birthday'];
		$photo=$row['avatar'];
		$address=$row['address'];
		$position=$row['position'];
		$idpb=$row['idpb'];
		$number=$row['numofdaysoff'];
		$update=true;
	}

	//Update nhan vien
	if(isset($_POST['update'])){
		if(isset($_POST['id'])){
			$id=$_POST['id'];
			$name=$_POST['name'];
			$username=$_POST['username'];
			$phone=$_POST['phone'];
			$birthday=$_POST['birthday'];
			$address=$_POST['address'];
			$position=$_POST['position'];
			$idpb=$_POST['idpb'];
			$number=0;
			if(isset($position)){
				if ($position=="Nhân viên"){
					$number=12;
				}
				if ($position=="Trưởng phòng"){
					$number=15;
				}
			}
			$oldimage=$_POST['oldimage'];

			if(isset($_FILES['image']['name'])&&($_FILES['image']['name']!="")){
				$newimage=time().$_FILES['image']['name'];
				$pathnewimage='uploads/'.time().$_FILES['image']['name'];
				unlink($oldimage);//xoa link avt cu
				move_uploaded_file($_FILES['image']['tmp_name'], $pathnewimage);
			}
			else{
				$newimage=$oldimage;
			}
			$query="UPDATE user SET name=?,username=?,phone=?,birthday=?,avatar=?,address=?,position=?,idpb=?,numofdaysoff=? WHERE id=?";
			$conn=open_database();
			$stmt=$conn->prepare($query);
			$stmt->bind_param("sssssssiii",$name,$username,$phone,$birthday,$newimage,$address,$position,$idpb, $number, $id);
			$stmt->execute();

			$_SESSION['response']="Bạn đã cập nhật thành công";
			$_SESSION['res_type']="primary";
			header('location:user.php');
		}
	}

	//doi mat khau
	if(isset($_POST['resetpassword'])){
		if (isset($_POST['id']) && isset($_POST['username'])){
			$id=$_POST['id'];
			$username=$_POST['username'];
			$hashed_password = password_hash($username, PASSWORD_DEFAULT);
	
			$query="UPDATE user SET password=? WHERE id=?";
			$conn=open_database();
			$stmt=$conn->prepare($query);
			$stmt->bind_param("si",$hashed_password,$id);
			$stmt->execute();
	
			$_SESSION['response']="Reset mật khẩu thành công";
			$_SESSION['res_type']="primary";
			header('location:user.php');
		}
	}

	//xem chi tiet nhan vien
	if(isset($_GET['details'])){
		$id=$_GET['details'];
		$query="SELECT * FROM user WHERE id=?";
		$conn=open_database();
		$stmt=$conn->prepare($query);
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();

		//get thong tin phong ban hien tai
		$query2= "SELECT * FROM department WHERE idpb=?";
		$stmt2= $conn->prepare($query2);
		$stmt2->bind_param("i",$row['idpb']);
		$stmt2->execute();
		$result2=$stmt2->get_result();
		$row2=$result2->fetch_assoc();

		//du lieu de hien thi ra view
		$vpb=$row2['namepb'];
		$vid=$row['id'];
		$vname=$row['name'];
		$vusername=$row['username'];
		$vpassword=$row['password'];
		$vphone=$row['phone'];
		$vbirthday=$row['birthday'];
		$vphoto=$row['avatar'];
		$vaddress=$row['address'];
		$vposition=$row['position'];
		$vnumber=$row['numofdaysoff'];
	}

?>