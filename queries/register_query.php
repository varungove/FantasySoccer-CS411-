<?php
	include '../db_connection/config.php';
	include '../db_connection/opendb.php';
			
	if (empty($_POST['uid']) || empty($_POST['pass']) || empty($_POST['tname'])) {
		echo "<script>alert('Enter all Fields');</script>";
	} else {		       			
		$STH = $conn->prepare("SELECT 1 FROM users WHERE uid = :uid");
		$STH->bindParam(':uid', $uid);
		$uid = $_POST['uid'];
		        		
		$STH->execute();							
		if($row = $STH->fetch()) {
			echo "<script>alert('User Already Exits');</script>";	
		} else {
					
			$STH = $conn->prepare("SELECT 1 FROM teams WHERE tname = :tname");
			$STH->bindParam(':tname', $tname);
			$tname = $_POST['tname'];
			        		
		        $STH->execute();							
			if($row = $STH->fetch()) {	
				echo "<script>alert('Team Name Already Exits');</script>";	
			} else {
						
				$STH = $conn->prepare("INSERT INTO users (uid, pass, tname) VALUES ( :uid, :pass, :tname)");
				$STH->bindParam(':uid', $uid);
				$STH->bindParam(':pass', $pass);
				$STH->bindParam(':tname', $tname);					
				$uid = $_POST['uid'];
				$pass = $_POST['pass'];
				$tname = $_POST['tname'];
				$STH->execute();
				
				echo "User created successfully";
			}
		}	
	}
				
?>