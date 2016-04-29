<!DOCTYPE html>
<html lang="en">

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
</head>


<body class="nav-md">
      <?php
	      include 'db_connection/config.php';
	      include 'db_connection/opendb.php';
	      
	      $uid = $_GET['uid'];
	      $league = $_GET['lid'];
	      
	      if (isset($_POST['submit'])) {
		       	$uid = $_GET['uid'];
		        $league = $_GET['lid'];
		        
		        // Get team name from db
		        $STH = $conn->prepare("SELECT tname FROM users WHERE uid = :uid");
		        $STH->bindParam(':uid', $uid);
		        $STH->execute();
		        $row = $STH->fetch(); 
		        $team = $row['tname'];
		              
		        // Add new team to the teams table
		        $STH = $conn->prepare("INSERT INTO teams (tname, tpoints, uid, lid) VALUES (:team, 0, :uid, :lid)");
		        $STH->bindParam(':team', $team);
		        $STH->bindParam(':uid', $uid);
		        $STH->bindParam(':lid', $league);
		        $STH->execute();
		        
		        //Update leagues table and decremant spots remaining
		        $STH = $conn->prepare("UPDATE league SET spots = spots - 1 WHERE lid = :lid");
		        $STH->bindParam(':lid', $league);
		        $STH->execute();        
		        
		        $selectOption = $_POST['filter_GK0'];
		        insert($selectOption, 1, $conn, 'GK', $team, $league);
		        
		        $selectOption = $_POST['filter_GK1'];
		        insert($selectOption, 2, $conn, 'GK', $team, $league);
		        
		        $selectOption = $_POST['filter_GK2'];
		        insert($selectOption, 3, $conn, 'GK', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF0'];
		        insert($selectOption, 1, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF1'];
		        insert($selectOption, 2, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF2'];
		        insert($selectOption, 3, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF3'];
		        insert($selectOption, 4, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF4'];
		        insert($selectOption, 5, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF5'];
		        insert($selectOption, 6, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF6'];
		        insert($selectOption, 7, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_DEF7'];
		        insert($selectOption, 8, $conn, 'DEF', $team, $league);
		        
		        $selectOption = $_POST['filter_MID0'];
		        insert($selectOption, 1, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID1'];
		        insert($selectOption, 2, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID2'];
		        insert($selectOption, 3, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID3'];
		        insert($selectOption, 4, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID4'];
		        insert($selectOption, 5, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID5'];
		        insert($selectOption, 6, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID6'];
		        insert($selectOption, 7, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_MID7'];
		        insert($selectOption, 8, $conn, 'MID', $team, $league);
		        
		        $selectOption = $_POST['filter_FW0'];
		        insert($selectOption, 1, $conn, 'FW', $team, $league);
		        
		        $selectOption = $_POST['filter_FW1'];
		        insert($selectOption, 2, $conn, 'FW', $team, $league);
		        
		        $selectOption = $_POST['filter_FW2'];
		        insert($selectOption, 3, $conn, 'FW', $team, $league);
		        
		        $selectOption = $_POST['filter_FW3'];
		        insert($selectOption, 4, $conn, 'FW', $team, $league);
		        
		        $selectOption = $_POST['filter_FW4'];
		        insert($selectOption, 5, $conn, 'FW', $team, $league);
		        
		        $selectOption = $_POST['filter_FW5'];
		        insert($selectOption, 6, $conn, 'FW', $team, $league);
      		} 
      
	      function insert($picks, $num, $conn, $pos, $team, $league) {
	        
	        
	        
		        $STH = $conn->prepare("INSERT INTO draft (pid, lid, tname, pick, pos) VALUES
		                   ((SELECT pid FROM players WHERE players.name=:selectOption AND lid=0), :league, :team, :num, :pos)");
		        $STH->bindParam(':selectOption', $picks);
		        $STH->bindParam(':num', $num);
		        $STH->bindParam(':pos', $pos);
		        $STH->bindParam(':team', $team);
		        $STH->bindParam(':league', $league);
		        $STH->execute();
	        
	      }
      
          
	      function options($pos, $count) {
		        include 'db_connection/config.php';
		        include 'db_connection/opendb.php';
		        
		        for ($i = 0; $i < $count; $i++) {
			          $options = '';
			          
			          $STH = $conn->prepare("SELECT name FROM players WHERE position = :position AND lid = 0 ORDER BY name ASC");
			          $STH->bindParam(':position', $position);
			          $position = $pos;
			          
			          $STH->execute();
			  
			          while($row2 = $STH->fetch()) {
			            	$options .="<option>" . $row2['name'] . "</option>";
			          }
			          
			          $temp = 'filter_'.$pos.$i;
			  
			          $menu= "<select name='$temp' id='$temp'>
			                    	" . $options . "
			               	  </select>";
			                
			          echo $menu;
		        }
	 
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
          <div class="clearfix"></div>
          <div class="row">

            
            <div class="col-md-6 col-sm-6 col-xs-12" style="margin-left: 60px; margin-top: 50px; width: 30% ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Set your draft pick</h2>
                  <div class="clearfix"></div>
                </div>
                <br>
                <div class="x_content">
                  
                  <form action="draft.php?uid=<?php echo $_GET['uid']; ?>&lid=<?php echo $_GET['lid']; ?>" method="post"> 
                      GoalKeeper:<br /> 
                      <?php
                            options("GK", 3);
                            echo "<br><br>";
                          ?>
                          
                          Defender:<br /> 
                          <?php
                            options("DEF", 8);
                            echo "<br><br>";
                          ?>
                          Midfielder:<br /> 
                          <?php
                            options("MID", 8);
                            echo "<br><br>";
                          ?>
                          
                          Forward:<br /> 
                          <?php
                            options("FW", 6);
                            echo "<br><br>";
                      ?>
      
                          <input type="submit" name="submit" value="Submit" />
                  </form>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
  </div>

  <script src="js/bootstrap.min.js"></script>
  <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="js/icheck/icheck.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="js/pace/pace.min.js"></script>
</body>

</html>
