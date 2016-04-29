<html>
	<body>
		<?php
		
			if (isset($_POST['join'])) {
				include 'db_connection/config.php';
				include 'db_connection/opendb.php';
	
				$lid = $_POST['lid'];

				$STH = $conn->prepare("SELECT DISTINCT tname FROM draft WHERE lid=:lid");
				$STH->bindParam(':lid', $lid);
				$STH->execute();
				
				$team_names = array();
				
				while($row1 = $STH->fetch()) {
					$team_names[] = $row1['tname'];
				}
				
				
				//Start draft
				startDraft($team_names, $conn, $lid);

			}//end of if
				
			function startDraft ($team_names, $conn, $lid) {
				draftGK($team_names, $conn, $lid, "GK", 3);
				draftDEF($team_names, $conn, $lid, "DEF", 8);
				draftMID($team_names, $conn, $lid, "MID", 8);
				draftFW($team_names, $conn, $lid, "FW", 6);
			}
			
			function draftGK ($team_names, $conn, $lid, $pos, $preferences_count) {
				draftPlayer($team_names, $conn, $lid, $pos, 0, $preferences_count, 1);
			}
			
			function draftDEF ($team_names, $conn, $lid, $pos, $preferences_count) {
				draftPlayer($team_names, $conn, $lid, $pos, 1, $preferences_count, 1);
				draftPlayer($team_names, $conn, $lid, $pos, 2, $preferences_count, 2);
				draftPlayer($team_names, $conn, $lid, $pos, 3, $preferences_count, 3);
				draftPlayer($team_names, $conn, $lid, $pos, 4, $preferences_count, 4);
			
			}
			
			function draftMID ($team_names, $conn, $lid, $pos, $preferences_count) {
				draftPlayer($team_names, $conn, $lid, $pos, 5, $preferences_count, 1);
				draftPlayer($team_names, $conn, $lid, $pos, 6, $preferences_count, 2);
				draftPlayer($team_names, $conn, $lid, $pos, 7, $preferences_count, 3);
				draftPlayer($team_names, $conn, $lid, $pos, 8, $preferences_count, 4);

			}
			
			function draftFW ($team_names, $conn, $lid, $pos, $preferences_count) {
				draftPlayer($team_names, $conn, $lid, $pos, 9, $preferences_count, 1);
				draftPlayer($team_names, $conn, $lid, $pos, 10, $preferences_count, 2);
			
			}
			
			function draftPlayer($team_names, $conn, $lid, $pos, $offset, $preferences_count, $current_pref) {
				echo "<br><h1>".$pos."</h1><br><br>";
				//Go through each team and assign GK based on prefernce
				for ($i = 0; $i < count($team_names); $i++) {
					$team = $team_names[(($i + $offset)%count($team_names))];
					
					$drafted = false;
					$pick_preference = $current_pref;
					
					// Assign one of the prefered players
					while ($drafted == false && $pick_preference <= $preferences_count) {
					
						//Get the player id of the preferred pick from db
						$STH = $conn->prepare("SELECT pid FROM draft WHERE lid = :lid AND tname = :tname AND pick = :pick AND pos = :pos");
						$STH->bindParam(':lid', $lid);						
						$STH->bindParam(':tname', $team);						
						$STH->bindParam(':pick', $pick_preference);						
						$STH->bindParam(':pos', $pos);					
						$STH->execute();
		
						$row = $STH->fetch();
						$pid = $row['pid'];
						
						//Check if player is assigned to a team in the same league
						$STH = $conn->prepare("SELECT team FROM players WHERE pid = :pid AND lid = :lid");
						$STH->bindParam(':lid', $lid);						
						$STH->bindParam(':pid', $pid);
						$STH->execute();
						if ($row = $STH->fetch()) {
							// Player has already been picked. Move on to the next preference
							$STH = $conn->prepare("SELECT * FROM players WHERE pid = :pid");
							$STH->bindParam(':pid', $pid);
							$STH->execute();
							$row = $STH->fetch();
							$name = $row['name'];
							echo "".$pos.": rejected ".$name." for ".$team."<br>";
							$pick_preference++;
						} else {
							//Player has not been picked yet. 
							//create new player entry in the players table and assign him to current team and league
							
							//Get Player info
							$STH = $conn->prepare("SELECT * FROM players WHERE pid = :pid");
							$STH->bindParam(':pid', $pid);
							$STH->execute();
							$row = $STH->fetch();
							$name = $row['name'];
							$cost = $row['cost'];
							$points = $row['points'];
							
							echo "".$pos.": Assigned ".$name." to ".$team."<br>";

							// Insert new player in the db with same details but does not belong to any team or league
							$STH = $conn->prepare("INSERT INTO players (pid, team, name, position, points, lid) VALUES (:pid, :team, :name, :position, :points, :lid) ");
					       		$STH->bindParam(':pid', $pid);						
							$STH->bindParam(':name', $name);
							$STH->bindParam(':position', $pos);
							$STH->bindParam(':points', $points);
							$STH->bindParam(':team', $team);
							$STH->bindParam(':lid', $lid);
							$STH->execute();
							
							$drafted = true;
						}			
						
						// None of the prefernces were available. Assign a random player
						if ($pick_preference > $preferences_count) {
						
							//Get the number of players for the position not yet assigned to anyteam
							$STH = $conn->prepare("SELECT COUNT(*) 
										FROM (SELECT * FROM players WHERE lid = 0 AND position = :position) AS T
										WHERE T.pid NOT IN (SELECT pid FROM players WHERE lid = 1) AND position = :position");	
							$STH->bindParam(':position', $pos);
							$STH->execute();
							$count = $STH->fetchColumn();
							$index = mt_rand(1,$count);

							// Get all the players for the position not yet assigned to anyteam
							$STH = $conn->prepare("SELECT * 
										FROM (SELECT * FROM players WHERE lid = 0 AND position = :position) AS T
										WHERE T.pid NOT IN (SELECT pid FROM players WHERE lid = 1 AND position = :position)");	
							$STH->bindParam(':position', $pos);
							$STH->execute();
							
							// Get random player info							
							for ($j = 1; $j < $index ; $j++) {
								$row = $STH->fetch();
							}
							$row = $STH->fetch();
							$pid_rand = $row['pid'];
							$name = $row['name'];
							$cost = $row['cost'];
							$points = $row['points'];
							
							// Insert new player in the db with same details but does not belong to any team or league
							$STH = $conn->prepare("INSERT INTO players (pid, team, name, position, cost, points, lid) VALUES (:pid, :team, :name, :position, :cost, :points, :lid) ");
					       		$STH->bindParam(':pid', $pid_rand);						
							$STH->bindParam(':name', $name);
							$STH->bindParam(':position', $pos);
							$STH->bindParam(':cost', $cost);
							$STH->bindParam(':points', $points);
							$STH->bindParam(':team', $team);
							$STH->bindParam(':lid', $lid);
							$STH->execute();
							echo "".$pos.": Assigned ".$name." to ".$team."<br>";
							
							$drafted = true;
						}
					} 
					
					
				}
			}
			

		?>
		
		<form action="initializeteams.php" method="post"> 
			Run Draft:<br />
			Input League ID to draft: <br>
			<input type="text"  name="lid" id="lid">
			<input type="submit" name="join" value="Run Draft" />
		</form>	
		
	</body>

</html>