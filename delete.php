<html>
	<body>
		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			if (isset($_POST['delete'])) { 
				if (empty($_POST['pid'])) {
		            		echo "<script>alert('Enter id of the player to delete');</script>";
		       		} else {
		        			
			       		$STH = $conn->prepare("DELETE FROM players WHERE pid = :pid");
					$STH->bindParam(':pid', $pid);
							
					$pid = $_POST['pid'];
					
					if($STH->execute()) {							
						echo("Deleted");
					} else {
						echo "<script>alert('Error connecting to database');</script>";
					}
				}
			} 	
		?>
		
		<h1>DELETE</h1> 
			<form action="delete.php" method="post"> 
			Player ID:<br />
			<input type="number" name="pid" value="" />
			<br />	
			<br />
			<input type="submit" name="delete" value="Delete" />
		</form>
			
	</body>
</html>