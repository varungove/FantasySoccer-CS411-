<?php
	include '../db_connection/config.php';
	include '../db_connection/opendb.php';
	
	// Get all the variables
	$lid = $_POST['lid'];
	$user_player = $_POST['user_player'];
	$opp_player = $_POST['opp_player'];
	$user_uid = $_POST['user_uid'];
	$opp_uid = $_POST['opp_uid'];
	
				
	// Get team name and pid of the player that requested the transfer	
	$STH = $conn->prepare("SELECT team,pid FROM players WHERE name =:user_player  AND lid=:lid");
	$STH->bindParam(':user_player', $user_player);
	$STH->bindParam(':lid', $lid);
	$STH->execute();
	$row=$STH->fetch();
	$user_team = $row['team'];
	$user_pid = $row['pid'];
	
	// Get team name and pid of the player that received the request	
	$STH = $conn->prepare("SELECT team, pid FROM players WHERE name = :opp_player AND lid=:lid");
	$STH->bindParam(':opp_player', $opp_player);
	$STH->bindParam(':lid', $lid);
	$STH->execute();
	$row=$STH->fetch();
	$opp_team = $row['team'];
	$opp_pid = $row['pid'];
		
	//Transfer players but changing team names
	$STH = $conn->prepare("UPDATE players SET team=:opp_team WHERE name = :user_player AND lid=:lid");
	$STH->bindParam(':opp_team', $opp_team);
	$STH->bindParam(':user_player', $user_player);
	$STH->bindParam(':lid', $lid);
	$STH->execute();
	
	$STH = $conn->prepare("UPDATE players SET team=:user_team WHERE name = :opp_player AND lid=:lid");
	$STH->bindParam(':user_team', $user_team);
	$STH->bindParam(':opp_player', $opp_player);
	$STH->bindParam(':lid', $lid);
	$STH->execute();
	
	// Delete the transfer request from the table
	$STH = $conn->prepare("DELETE from transfer_requests WHERE lid=:lid AND user_pid=:user_pid AND opp_pid=:opp_pid AND user_uid=:user_uid AND opp_uid=:opp_uid");
	$STH->bindParam(':lid', $lid);
	$STH->bindParam(':user_pid', $user_pid);
	$STH->bindParam(':opp_pid', $opp_pid);	
	$STH->bindParam(':user_uid', $user_uid);
	$STH->bindParam(':opp_uid', $opp_uid);	
	$STH->execute();
	
	// Delete any other requests for the players that just moved in the same league
	$STH = $conn->prepare("DELETE from transfer_requests WHERE lid=:lid AND opp_pid=:user_pid");
	$STH->bindParam(':lid', $lid);
	$STH->bindParam(':user_pid', $user_pid);
	$STH->execute();
	
	$STH = $conn->prepare("DELETE from transfer_requests WHERE lid=:lid AND opp_pid=:opp_pid");
	$STH->bindParam(':lid', $lid);
	$STH->bindParam(':opp_pid', $opp_pid);
	$STH->execute();

?>