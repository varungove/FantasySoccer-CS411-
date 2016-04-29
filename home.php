<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<!-- Meta, title, CSS, favicons, etc. -->
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link href="css/bootstrap.min.css" rel="stylesheet">
  	<link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  	<link href="css/animate.min.css" rel="stylesheet">
  	<link href="css/custom.css" rel="stylesheet">
  	<link href="css/icheck/flat/green.css" rel="stylesheet">
  	<script src="js/jquery.min.js"></script>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
  	<script src="js/progressbar/bootstrap-progressbar.min.js"></script>
  	<script src="js/icheck/icheck.min.js"></script>
  	<script src="js/custom.js"></script>
  	<script src="js/pace/pace.min.js"></script>
	</head>
	
	<body>
		
		<div class="container body">
			<div class="main_container">
				<div class="top_nav">
      		        <div class="nav_menu">
          		   <nav class="" role="navigation">
          		   </nav>
        		</div>
        	     </div>


      <div role="main">
        <div class="">

          <div class="page-title">
            <div class="well" style="text-align: center;">
              <h3 style="color: black;">Fantasy Premier League Draft</h3>
            </div>
          </div>
          	<br>
          	<br>
          	<br>

          	<div class="well">
          		<h2> Click on your choice of league </h2> 
			<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			
			
			$STH = $conn->prepare("SELECT * FROM league");
			$STH->execute();
					
			echo "<table border='1'>
				<tr>
					<th>League Name</th>
					<th>Spots Available</th>
				</tr>";
			
			$uid = $_GET['uid'];
		        $draft_link = "draft.php?uid=".$uid."&lid=";
		        $viewteam_link = "viewteam.php?uid=".$uid."&lid=";
		        $lid = 1;
		        
		        $STH_getLeagues = $conn->prepare("SELECT lid FROM teams WHERE uid = :uid");
		        $STH_getLeagues->bindParam(':uid', $uid);
			$STH_getLeagues->execute();
			
			$leagues = array();
			
			while($row1 = $STH_getLeagues->fetch()) {
		        	$leagues[] = $row1['lid'];
			}

		        while($row2 = $STH->fetch()) {
		        	$in_league = false;
		        	for ($i=0; $i < count($leagues); $i++) {
					if ($lid == $leagues[$i]) {
						$in_league = true;
						break;
					} 
					
				}
				
		        	echo "<tr>";
			        	if ($in_league) {
						echo "<td><a href=".$viewteam_link.$lid.">" . $row2['name'] . "</a></td>";
					} else if ($row2['spots'] == 0) {
						echo "<td>" . $row2['name'] . "</td>";
					} else {
						echo "<td><a href=".$draft_link.$lid.">" . $row2['name'] . "</a></td>";
					}
					
					echo "<td>" . $row2['spots'] . "</td>";
					
					if ($in_league) {
						echo "<td> Already part of this league </td>";
					} else if ($row2['spots'] == 0) {
						echo "<td> League is full </td>";
					} else {
						echo "<td> Not part of this league </td>";
					}
				echo "</tr>";
				
				$lid++;
			}
					
			echo "</table>";
			
			if (isset($_POST['join'])) {
				if (empty($_POST['uid']) || empty($_POST['pass']) || empty($_POST['lname'])) {
            				echo "<script>alert('Enter all Fields');</script>";
        			} else {
				
					$STH = $conn->prepare("SELECT 1 FROM users WHERE uid =:uid AND pass = :pass");
					$STH->bindParam(':uid', $uid);
					$STH->bindParam(':pass', $pass);
					$uid = $_POST['uid'];
					$pass = $_POST['pass'];
					
					$STH->execute();
					
					if($row2 = $STH->fetch()) {
					   	
					} else {
						die("Inavlid Username/Password");
					}
					
					$STH = $conn->prepare("SELECT 1 FROM league WHERE name = :lname and spots>0");
					$STH->bindParam(':lname', $lname);
					$lname = $_POST['lname'];
					
					$STH->execute();
					
					if($row2 = $STH->fetch()) {
					   	
					} else {
						die("League Does Not Exist or League is Full");
					}
					
					
					$STH = $conn->prepare("INSERT INTO teams (tname, lid) 
	    							 SELECT tname, lid FROM users, league WHERE users.uid = :uid AND league.name = :lname;");
					$STH->bindParam(':lname', $lname);
					$STH->bindParam(':uid', $uid);
					$uid = $_POST['uid'];
					$lname = $_POST['lname'];
					$STH->execute();
					
					
					$STH = $conn->prepare("UPDATE league SET spots = spots - 1 WHERE name = :lname");
					$STH->bindParam(':lname', $lname);
					$lname = $_POST['lname'];
					
					if($STH->execute()) {
						echo "Team Added to League";
					}
				}
			} 	
		?>

				</div>
	</div>
	</div>
	</body>
</html>