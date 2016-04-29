<?php
	include '../db_connection/config.php';
	include '../db_connection/opendb.php';
			
	if (empty($_POST['uid']) || empty($_POST['pass'])) {
		echo "<script>alert('Enter both Fields');</script>";
	} else {
		$STH = $conn->prepare("SELECT uid,pass FROM users WHERE uid = :uid");
		$STH->bindParam(':uid', $uid);
							
		$uid = $_POST['uid'];
		$pass = $_POST['pass'];
					
		$STH->execute();							
		if($row = $STH->fetch()) {
			if ($row['pass'] == $pass) {
				echo "Logged in Successfully";
			} else {
				echo "<script>alert('Username or Password Incorrect');</script>";
			}
					
		} else {
			echo "<script>alert('Incorrect Username');</script>";
		}
					
	}
			
?>