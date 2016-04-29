<html>
	<body>
		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			if (isset($_POST['add'])) { 
				if (empty($_POST['name']) || empty($_POST['cost']) || empty($_POST['position'])) {
		            		echo "<script>alert('Name, Cost and position are required fields');</script>";
		            		header("Location: newplayer.php"); 
       				 	die("Redirecting to login.php");
		       		} else {
			       		$STH = $conn->prepare("INSERT INTO players (name, cost, position, points) VALUES (:name, :cost, :position, :points)");		        			
					$STH->bindParam(':name', $name);
					$STH->bindParam(':cost', $cost);
					$STH->bindParam(':position', $position);
					$STH->bindParam(':points', $points);
							
					$name = $_POST['name'];
					$cost = $_POST['cost'];
					$position = $_POST['position'];
					$points = $_POST['points'];
					
												
					if($STH->execute()) {
						echo "New player added";	
					} else {
						echo "<script>alert('Error connecting to database');</script>";
					}
					
				}
			} 	
		?>
		
		<h1>Add New Player</h1> 
		<form action="newplayer.php" method="post"> 
			Name:<br />
			<input type="text" name="name" value="" />
			<br /><br />
			Cost:<br />
			<input type="number" step="0.1" name="cost" value="" />
			<br /><br />
			Position:<br /> 
			<input type="text" name="position" value="" /> 
			<br /><br /> 
			Points:<br /> 
			<input type="number" name="points" value="" /> 
			<br /><br />
			<input type="submit" name="add" value="add player" />
		</form>	
			
	</body>
</html>