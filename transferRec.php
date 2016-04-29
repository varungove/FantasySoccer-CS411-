<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
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

<html>
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

		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
				
			
			
			$STH = $conn->prepare("SELECT * FROM players WHERE lid = :lid and team= (SELECT tname from users WHERE uid=:uid)");
			$STH->bindParam(':uid', $uid);
			$STH->bindParam(':lid', $lid);			
			$uid = $_GET['uid'];				
			$lid = $_GET['lid'];
			$STH->execute();
			$points=0;
			
			
			$lowD=1000;
			$lowM=1000;
			$lowF=1000;
			$lowG=0;
		      
					
			$lowDEF; 
			$lowMID; 			
			$lowFW; 
			$lowGK;
			
		
			
				echo "<h2>Your team for this league</h2>";
								
				echo "<table border='1'>
					<tr>
						<th>Player Name</th>
						<th>Points</th>
								
					</tr>						
					<tr>
						<td>" . $row1['name'] . "</td>
						<td>" . $row1['points'] . "</td>
							
					</tr>";
				while($row1 = $STH->fetch()) {
					echo "<tr>";
						echo "<td>" . $row1['name'] . "</td>";
						echo "<td>" . $row1['points'] . "</td>";
						
						// Calculating Lowest Defender
						if(($row1['position']=='DEF') && ($row1['points']<$lowD))
						{								
							$lowD=$row1['points'];
							$lowDEF=$row1['name'];
							
						}
						
						// Calculating Lowest MID
						if(($row1['position']=='MID') && ($row1['points']<$lowM))
						{
							
							$lowM=$row1['points'];
							$lowMID=$row1['name'];
							
						}
						
						// Calculating Lowest FW
						if(($row1['position']=='FW') && ($row1['points']<$lowF))
						{
							
							$lowF=$row1['points'];
							$lowFW=$row1['name'];
							
						}
						if($row1['position']=='GK')
						{
						$lowGK = $row1['name'];
						$lowG = $row1['points'];
						}
						
					echo "</tr>";		
					
					
				}
				
				
							
			echo "</table>";
			
				
			
			//Getting the Top Undrafted Players
					$STH = $conn->prepare("SELECT * 
								FROM (SELECT * FROM players WHERE lid = 0) AS T
								WHERE T.pid NOT IN (SELECT pid FROM players WHERE lid = :lid)");
					$STH->bindParam(':lid', $lid);
					$STH->execute();
					
					$highDu=0;
					$highMu=0;
					$highFu=0;
					$highGu=0;
				      
							
					$highDEFu; 
					$highMIDu; 			
					$highFWu;
					$highGKu;
			
				while($row = $STH->fetch())
				{
				
						// Calculating Highest Undrafted GK
						if(($row['position']=='GK') && ($row['points']>$highGKu))
						{
							
							$highGu=$row['points'];
							$highGKu=$row['name'];
							
						}
						
						// Calculating Highest Undrafted Defender
						if(($row['position']=='DEF') && ($row['points']>$highDu))
						{
							
							$highDu=$row['points'];
							$highDEFu=$row['name'];
							
						}
						
						// Calculating Highest Undrafted MID
						if(($row['position']=='MID') && ($row['points']>$highMu))
						{
							
							$highMu=$row['points'];
							$highMIDu=$row['name'];
							
						}
						
						// Calculating Highest Undrafted FW
						if(($row['position']=='FW') && ($row['points']>$highFu))
						{
							
							$highFu=$row['points'];
							$highFWu=$row['name'];
							
						}
				} // end of while
			
			
			//Getting the Top Drafted Players
					$STH = $conn->prepare("SELECT tname FROM users WHERE uid=:uid");
					$STH->bindParam(':uid', $uid);
					$STH->execute();
					$row1 = $STH->fetch();
					$tname = $row1['tname'];
					
					$STH = $conn->prepare("SELECT * FROM players WHERE lid = :lid AND team != :tname");
					$STH->bindParam(':lid', $lid);
					$STH->bindParam(':tname', $tname);
					$STH->execute();
					
					$highDd=0;
					$highMd=0;
					$highFd=0;
					$highGd=0;
				      
							
					$highDEFd; 
					$highMIDd; 			
					$highFWd;
					$highGKd;
			
				while($row = $STH->fetch())
				{
					
						// Calculating Highest Undrafted GK
						if(($row['position']=='GK') && ($row['points']>$highGKd))
						{
							 
							$highGd=$row['points'];
							$highGKd=$row['name'];
							
						}
						
						// Calculating Highest Undrafted Defender
						if(($row['position']=='DEF') && ($row['points']>$highDd))
						{
							
							$highDd=$row['points'];
							$highDEFd=$row['name'];
							
						}
						
						// Calculating Highest Undrafted MID
						if(($row['position']=='MID') && ($row['points']>$highMd))
						{
							
							$highMd=$row['points'];
							$highMIDd=$row['name'];
							
						}
						
						// Calculating Highest Undrafted FW
						if(($row['position']=='FW') && ($row['points']>$highFd))
						{
							
							$highFd=$row['points'];
							$highFWd=$row['name'];
							
						}
				} // end of while
				
				if($highGu<$lowG)
				$highGKu=" X ";
				if($highGd<$lowG)
				$highGKd=" X ";
				
				if($highMu<$lowM)
				$highMIDu=" X ";
				if($highMd<$lowM)
				$highMIDd=" X ";
				
				if($highDu<$lowD)
				$highDEFu=" X ";
				if($highDd<$lowD)
				$highDEFd=" X ";
				
				if($highFu<$lowF)
				$highFWu=" X ";
				if($highFd<$lowF)
				$highFWd=" X ";
				
				echo "<h2>Suggested Transfers</h2>";
								
				echo "<table border='1'>
					<tr>
						<th>Your Player</th>
						<th>Undrafted Transfer Option</th>
						<th>Drafted Transfer Option</th>
						
								
					</tr>
						
							
					<tr>
						<td>" . $lowGK. "</td>
						<td>" . $highGKu . "</td>
						<td>" . $highGKd . "</td>
							
					</tr>
					
					<tr>
						<td>" . $lowDEF. "</td>
						<td>" . $highDEFu . "</td>
						<td>" . $highDEFd . "</td>
							
					</tr>
					
					<tr>
						<td>" . $lowMID. "</td>
						<td>" . $highMIDu . "</td>
						<td>" . $highMIDd . "</td>
							
					</tr>
					
					<tr>
						<td>" . $lowFW. "</td>
						<td>" . $highFWu . "</td>
						<td>" . $highFWd . "</td>
							
					</tr>";
					
					echo "</table>";
			
								
		?>
		
		<form name="tranfers_form" id="transfers_form" action="transfer.php?uid=<?php echo $_GET['uid']; ?>&lid=<?php echo $_GET['lid']; ?>" method="post">
			<br><br>
			<input type="submit" id="transfers" name="transfers" value="Go to Transfers"/>
		</form>
		<form name="viewotherteam_form" id="viewotherteam_form" action="viewotherteam.php?uid=<?php echo $_GET['uid']; ?>&lid=<?php echo $_GET['lid']; ?>" method="post">
			<br><br>
			<input type="submit" id="viewotherteam" name="viewotherteam" value="View League Standings"/>
		</form>
		<p id="comments"></p>
		</div>
		
		</div>
		</div>
		</div>
	</body>
</html>