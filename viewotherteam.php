<html>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

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
          	
		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			
			
			
			$STH = $conn->prepare("SELECT tname FROM teams WHERE lid=:lid ");
			$STH->bindParam(':lid', $lid);
			$lid = $_GET['lid'];
			$STH->execute();
			echo "<h2>Other Teams in this League</h2>";
								
				echo "<table border='1'>
					<tr>
						<th>Team</th>
						<th>Points</th>
						
						
								
					</tr>";						
					
			while($row1 = $STH->fetch()){
				echo "<tr>";
				echo "<td>" . $row1['tname'] . "</td>";
				
				//Calculating Poins tally of each team
				$STH1 = $conn->prepare("SELECT * FROM players WHERE lid = :lid and team=:team");
				$STH1->bindParam(':team', $row1['tname']);
				$STH1->bindParam(':lid', $lid);						
				$lid = $_GET['lid'];
				$STH1->execute();
				$points=0;							
				while($row2 = $STH1->fetch()) 
					$points=$points+$row2['points'];
				
				
				echo "<td>" . $points . "</td>";		
					
					
				
								
				echo "</tr>";
			}//end of while
			echo "</table>";
								
		?>
		</div>
</div>
		
	</body>
</html>