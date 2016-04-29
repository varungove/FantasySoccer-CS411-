<html>
	<body>
		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			if (isset($_POST['update'])) { 
				if (empty($_POST['pid']) || empty($_POST['points'])) {
		            		echo "<script>alert('Enter both Fields');</script>";
		       		} else {
			       		$STH = $conn->prepare("UPDATE players SET points = :points WHERE pid = :pid");
					$STH->bindParam(':pid', $pid);
					$STH->bindParam(':points', $points);							
					$pid = $_POST['pid'];
					$points = $_POST['points'];
					
					if($STH->execute()) {
						echo("Updated Points");
					} else {
						die("Error");
					}
					
				}
			} 	
		?>
		
		<h1>UPDATE</h1> 
		<form action="update.php" method="post"> 
			Player ID:<br />
			<input type="number" name="pid" value="" />
			<br />
			Updated Gameweek Points:<br />
			<input type="number" name="points" value="" />
			<br />
			<input type="submit" name="update" value="Update" />
		</form>
			
	</body>
</html>