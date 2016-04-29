<html>
	<body>
		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			if (isset($_POST['search'])) { 
				if (empty($_POST['position'])) {
		            		echo "<script>alert('Enter the position');</script>";
		       		} else {
			       		$STH = $conn->prepare("SELECT name FROM players WHERE position = :position");
					$STH->bindParam(':position', $position);
					$position = $_POST['position'];
					$STH->execute();							

					while($row = $STH->fetch()) {
						echo "Name :{$row['name']} <br>";						
					}
				}
			} 	
		?>
		
		<h1>Search</h1> 
			<form action="search.php" method="post"> 
			Position:<br />
			<input type="text" name="position" value="" />
			<br /><br />
			<input type="submit" name="search" value="Search" />
		</form>	
			
	</body>
</html>