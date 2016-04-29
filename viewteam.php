<head>
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
	<script>
		$(document).ready(function() {
			$('.transfer').click(function(e) { 
     				var id = this.id;
     				var index = id.replace ( /[^\d.]/g, '' );
     				var type = id.replace(/\d+/g, '')

				var opp_user_cell = "tc1_" + index;
     				var opp_player_cell = "tc2_" + index;	
				var user_player_cell = "tc3_" + index;
				
				var opp_user = $("#transfer_table #" + opp_user_cell).text();
				var opp_player = $("#transfer_table #" + opp_player_cell).text();
				var user_player = $("#transfer_table #" + user_player_cell).text();
     				var lid = getUrlParameter("lid");
     				var uid = getUrlParameter("uid");
     				
     				if (type =="accept") {
     					$.post( 
			                	"queries/accept_transfer.php",
			                  	{lid:lid, user_player:opp_player, opp_player:user_player, user_uid:opp_user, opp_uid:uid},
			                  	function(data) {
			                  	
			                  	}
			               );
			               
     				} else if (type =="reject") {
     					$.post( 
			                	"queries/reject_transfer.php",
			                  	{lid:lid, user_player:opp_player, opp_player:user_player, user_uid:opp_user, opp_uid:uid},
			                  	function(data) {
			                  	
			              		}
			              	);
     				} 
     				location.reload();
				
			});
			
			function getUrlParameter(sParam) {
    				var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        			sURLVariables = sPageURL.split('&'),
        			sParameterName,
        			i;

    				for (i = 0; i < sURLVariables.length; i++) {
        				sParameterName = sURLVariables[i].split('=');

        				if (sParameterName[0] === sParam) {
            					return sParameterName[1] === undefined ? true : sParameterName[1];
        				}
    				}
			};		
		});
		
	</script>
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

		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
				
			function showTransferRequests() {
				include 'db_connection/config.php';
				include 'db_connection/opendb.php';
								
				$uid = $_GET['uid'];

				$STH = $conn->prepare("SELECT * FROM transfer_requests WHERE opp_uid=:uid");
				$STH->bindParam(':uid', $uid);
				$STH->execute();
				
				if ($row=$STH->fetch()) {
					$i =0;
					
					$STH1 = $conn->prepare("SELECT name FROM players WHERE pid=:pid");
					$STH1->bindParam(':pid', $pid);
					$pid = $row['opp_pid'];
					$STH1->execute();
					$row1=$STH1->fetch();
					$user_player = $row1['name'];
					
					$STH1 = $conn->prepare("SELECT name FROM players WHERE pid=:opp_pid");
					$STH1->bindParam(':opp_pid', $opp_pid);
					$opp_pid = $row['user_pid'];
					$STH1->execute();
					$row1=$STH1->fetch();
					$opp_player = $row1['name'];
					
					$accept = "accept".$i;
					$reject = "reject".$i;
					$table_cell1 = "tc1_".$i;
					$table_cell2 = "tc2_".$i;
					$table_cell3 = "tc3_".$i;

					echo "<br><br>
						<table id='transfer_table' border='1'>
							<tr>
								<th>Offer From</th>
								<th>Opponent player</th>
								<th>Your player</th>
								<th>Action</th>
							</tr>
							<tr>
								<td id='$table_cell1'>" . $row['user_uid'] . "</td>
								<td id='$table_cell2'>" . $opp_player . "</td>
								<td id='$table_cell3'>" . $user_player . "</td>
								<td>
								<input type='button' name='$accept' id='$accept' value='Accept' class=transfer>
								<input type='button' name='$reject' id='$reject' value='Reject' class=transfer>
								</td>
							</tr>";
					while ($row=$STH->fetch()) {
						$i++;
						
						$STH1 = $conn->prepare("SELECT name FROM players WHERE pid=:pid");
						$STH1->bindParam(':pid', $pid);
						$pid = $row['opp_pid'];
						$STH1->execute();
						$row1=$STH1->fetch();
						$user_player = $row1['name'];
					
						$STH1 = $conn->prepare("SELECT name FROM players WHERE pid=:opp_pid");
						$STH1->bindParam(':opp_pid', $opp_pid);
						$opp_pid = $row['user_pid'];
						$STH1->execute();
						$row1=$STH1->fetch();
						$opp_player = $row1['name'];
						
						$accept = "accept".$i;
						$reject = "reject".$i;
						$table_cell1 = "tc1_".$i;
						$table_cell2 = "tc2_".$i;
						$table_cell3 = "tc3_".$i;
					
						echo "<tr>
								<td id='$table_cell1'>" . $row['user_uid'] . "</td>
								<td id='$table_cell2'>" . $opp_player . "</td>
								<td id='$table_cell3'>" . $user_player . "</td>
								<td>
									<input type='button' name='$accept' id='$accept' value='Accept' class=transfer>
									<input type='button' name='$reject' id='$reject' value='Reject' class=transfer>
								</td>
							</tr>";
					}
					
					echo "</table>";
				}				
			}
			
			$STH = $conn->prepare("SELECT * FROM players WHERE lid = :lid and team= (SELECT tname from users WHERE uid=:uid)");
			$STH->bindParam(':uid', $uid);
			$STH->bindParam(':lid', $lid);			
			$uid = $_GET['uid'];				
			$lid = $_GET['lid'];
			$STH->execute();
			$points=0;
			
			if ($row1 = $STH->fetch()) {
			
				echo "<h1>Your team for this league</h1>";
								
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
						$points=$points+$row1['points'];
							
					echo "</tr>";
					
				}
				echo "<td><b>" . "Total Points" . "</b></td>";
				echo "<td>" . $points . "</td>";
			} else {
				echo "<h2>Your team is yet to be drafted</h2>";						
			}
							
			echo "</table>";
			
			showTransferRequests();						
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
	</body>
</html>