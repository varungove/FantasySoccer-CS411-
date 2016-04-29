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
	<script>
		$(document).ready(function(){
    			$("#undrafted").click(function(){
    				var undrafted_visible = $("#transfer_undrafted").is(":visible");
    				var drafted_visible = $("#transfer_drafted").is(":visible");
    				
    				if (!undrafted_visible) 
					$("#transfer_undrafted").toggle();
				
				if (drafted_visible) 
					$("#transfer_drafted").toggle();
   	 		});
   	 		
   	 		$("#drafted").click(function(){
   	 			var drafted_visible = $("#transfer_drafted").is(":visible");
    				var undrafted_visible = $("#transfer_undrafted").is(":visible");
    				
    				if (!drafted_visible) 
					$("#transfer_drafted").toggle();
					
				if (undrafted_visible) 
					$("#transfer_undrafted").toggle();
   	 		});
		});
	</script>
</head>


<html>
	<body>
		<?php
		
			if (isset($_POST['transfer_undrafted_submit'])) {
				
				$selectOption_user = $_POST['user_undrafted']; //undrafted selection 
				$selectOption_undrafted = $_POST['undrafted']; //current users player
				
				transferUndrafted($selectOption_user, $selectOption_undrafted);
			}
			
			if (isset($_POST['transfer_drafted_submit'])) {
				
				$selectOption_user = $_POST['user_drafted']; //undrafted selection 
				$selectOption_drafted = $_POST['drafted']; //current users player
				
				transferDrafted($selectOption_user, $selectOption_drafted);
			}
			
			function transferDrafted($cu, $op) {
			
				include 'db_connection/config.php';
				include 'db_connection/opendb.php';
									
				$STH = $conn->prepare("SELECT position FROM players WHERE name=:cu");
				$STH->bindParam(':cu', $cu);
				$STH->execute();
				$row1 = $STH->fetch();
				
				$STH = $conn->prepare("SELECT position FROM players WHERE name=:op");
				$STH->bindParam(':op', $op);
				$STH->execute();
				$row2 = $STH->fetch();
						
						
				if($row1!=$row2) {
					echo "<script>alert('Player positions do not match. Please try again with different players');</script>";
				} else {
					echo "<script>alert('Player positions match. Transfer request has been sent');</script>";
					
					// Get lid and current user's uid 			
					$user_uid = $_GET['uid'];;
					$lid = $_GET['lid'];
					
					// Get the pid of the current users player that he wants to exchange
					$STH = $conn->prepare("SELECT * FROM players WHERE name=:cu AND lid =:lid");
					$STH->bindParam(':cu', $cu);
					$STH->bindParam(':lid', $lid);
					$STH->execute();
					$row = $STH->fetch();
					$user_pid = $row['pid'];
					
					// Get the pid and team name of the opposition users player that he wants to exchange
					$STH = $conn->prepare("SELECT * FROM players WHERE name=:op AND lid =:lid");
					$STH->bindParam(':op', $op);
					$STH->bindParam(':lid', $lid);
					$STH->execute();
					$row = $STH->fetch();
					$opp_pid = $row['pid'];
					$opp_team = $row['team'];
					
					// Get uid of the opposition players team
					$STH = $conn->prepare("SELECT uid FROM users WHERE tname=:tname");
					$STH->bindParam(':tname', $opp_team);
					$STH->execute();
					$row = $STH->fetch();
					$opp_uid = $row['uid'];
					
					// Add data to the transfer_requests table in the db
					$STH = $conn->prepare("INSERT INTO transfer_requests (lid, user_pid, opp_pid, user_uid, opp_uid) 
								VALUES (:lid, :user_pid, :opp_pid, :user_uid, :opp_uid)");
					$STH->bindParam(':lid', $lid);
					$STH->bindParam(':user_pid', $user_pid);
					$STH->bindParam(':opp_pid', $opp_pid);
					$STH->bindParam(':user_uid', $user_uid);
					$STH->bindParam(':opp_uid', $opp_uid);
					$STH->execute();
				}											
			}
			
			function transferUndrafted($cu, $op) {
			
				include 'db_connection/config.php';
				include 'db_connection/opendb.php';
									
				$STH = $conn->prepare("SELECT position FROM players WHERE name=:cu");
				$STH->bindParam(':cu', $cu);
				$STH->execute();
				$row1 = $STH->fetch();
				
				$STH = $conn->prepare("SELECT position FROM players WHERE name=:op");
				$STH->bindParam(':op', $op);
				$STH->execute();
				$row2 = $STH->fetch();
						
						
				if($row1!=$row2) {
					echo "<script>alert('Player positions do not match. Please try again with different players');</script>";
				} else {
					echo "<script>alert('Player positions match. Transfer has been made');</script>";
							
					$uid = $_GET['uid'];;
					$league = $_GET['lid'];
					
					$STH = $conn->prepare("SELECT tname FROM users WHERE uid=:uid");
					$STH->bindParam(':uid', $uid);
					$STH->execute();
					$row1 = $STH->fetch();
					$team = $row1['tname'];
					
					$STH = $conn->prepare("DELETE FROM players WHERE name=:cu AND lid=:league AND team= (SELECT tname FROM users WHERE uid=:uid)");
					$STH->bindParam(':cu', $cu);
					$STH->bindParam(':uid', $uid);
					$STH->bindParam(':league', $league);
					$STH->execute();
								
					$STH = $conn->prepare("SELECT * FROM players WHERE name = :op");
					$STH->bindParam(':op', $op);
					$STH->execute();
					$row = $STH->fetch();
					$pid = $row['pid'];
					$cost = $row['cost'];
					$points = $row['points'];
									
					$STH = $conn->prepare("INSERT INTO players (pid, team, name, position, points, lid) 
								VALUES (:pid, :team, :name, :position, :points, :lid) ");
					$STH->bindParam(':pid', $pid);						
					$STH->bindParam(':name', $op);
					$STH->bindParam(':position', $pos);
					
					$STH->bindParam(':points', $points);
					$STH->bindParam(':team', $team);
					$STH->bindParam(':lid', $league);
					$pos = $row2['position'];
					$STH->execute();
					
				}
						
						
					
			}
			
			function options($type) {
				include 'db_connection/config.php';
				include 'db_connection/opendb.php';
				

				$options = '';
				$uid = $_GET['uid'];
				$lid = $_GET['lid'];
				
				$STH = $conn->prepare("SELECT tname FROM users WHERE uid=:uid");
				$STH->bindParam(':uid', $uid);
				$STH->execute();
				$row1 = $STH->fetch();
				$tname = $row1['tname'];									
				
				if ($type == "User Team") {
				
					$STH = $conn->prepare("SELECT name FROM players WHERE lid = :lid AND team=:tname");
					$STH->bindParam(':lid', $lid);
					$STH->bindParam(':tname', $tname);
					$temp = 'user_undrafted';
					
				} else if ($type == "User Team Drafted") {
				
					$STH = $conn->prepare("SELECT name FROM players WHERE lid = :lid AND team=:tname");
					$STH->bindParam(':lid', $lid);
					$STH->bindParam(':tname', $tname);
					$temp = 'user_drafted';
				
				} else if ($type =="Undrafted Players") {
				
					$STH = $conn->prepare("SELECT * 
								FROM (SELECT * FROM players WHERE lid = 0) AS T
								WHERE T.pid NOT IN (SELECT pid FROM players WHERE lid = :lid)");
					$STH->bindParam(':lid', $lid);
					$temp = 'undrafted';
				
				} else if($type == "Drafted Players") {
				
					$STH = $conn->prepare("SELECT name FROM players WHERE lid = :lid AND team != :tname");
					$STH->bindParam(':lid', $lid);
					$STH->bindParam(':tname', $tname);
					$temp = 'drafted';
				
				}
					
				$STH->execute();
	
				while($row2 = $STH->fetch()) {
				    $options .="<option>" . $row2['name'] . "</option>";
				}
					
	
				$menu= "<select name='$temp' id='$temp'>
					      	" . $options . "
					</select>";
					   	 	
				echo $menu;
				
		  	}
		?>
				
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
		
		<input type="button" class="btn btn-primary" id="undrafted" name="undrafted" value="Transfer with undrafted players"/>
		<input type="button" class="btn btn-primary"id="drafted" name="drafted" value="Transfer with drafted players"/>
		<form style="display:none" id="transfer_undrafted" name="transfer_undrafted" action="" method="post">
			Select your team player:   
			<?php
				options("User Team");
				echo "<br><br>";
			?>
			Select undrafted player:   
			<?php
				options("Undrafted Players");
				echo "<br><br>";
			?>
			<input type="submit" class="btn btn-primary" name="transfer_undrafted_submit" id="transfer_undrafted_submit" value="Submit"/>
		</form>
		
		<form style="display:none" id="transfer_drafted" name="transfer_drafted" action="" method="post">
			Select your team player:   
			<?php
				options("User Team Drafted");
				echo "<br><br>";
			?>
			Select player from other team:   
			<?php
				options("Drafted Players");
				echo "<br><br>";
			?>
			<input type="submit" class="btn btn-primary" name="transfer_drafted_submit" id="transfer_drafted_submit" value="Submit"/>
		</form>
		
		<form name="tranfers_rec_form" id="transfers_rec_form" action="transferRec.php?uid=<?php echo $_GET['uid']; ?>&lid=<?php echo $_GET['lid']; ?>" method="post">
			<br><br>
			<input class="btn btn-primary" type="submit" id="transferRec" name="transferRec" value="View Transfer Recommendations"/>
		</form>
		
		</div>
		
		</div>
		</div>
		</div>
	</body>
</html>