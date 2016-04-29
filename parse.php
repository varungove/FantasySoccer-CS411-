<html>
	<body>
		<?php
			include 'db_connection/config.php';
			include 'db_connection/opendb.php';
			
			
			function add($firstname, $secondname, $pos, $points){
			
				include 'db_connection/config.php';
				include 'db_connection/opendb.php';
				$firstname.=" ";
				$firstname.=$secondname;
				
				if($pos=="Goalkeeper")
					$position = "GK";
					
				if($pos=="Defender")
					$position = "DEF";
					
				if($pos=="Midfielder")
					$position = "MID";
					
				if($pos=="Forward")
					$position = "FW";
					
				$STH = $conn->prepare("INSERT INTO players (name, position, points) VALUES (:name, :position, :points)");		        			
				$STH->bindParam(':name', $firstname);
				$STH->bindParam(':position', $position);
				$STH->bindParam(':points', $points);
					
					
				$STH->execute();
			}//end of add
			
			function par($link) {
				$name = "first_name";
				$last = "second_name";
				$cost = "now_cost";
				$tyn = "type_name";
				$tn = "team_name";
				$points = "total_points";
				$cf = "current_fixture";
				$html = file_get_contents($link);
				
				//first name
				$x = strpos($html, $name);
				$y = strpos($html, $last);
				$i = ($y-($x+13))-3;
				$str = substr($html, ($x+13), $i);
				echo $str;
				
				//second name
				$y = strpos($html, $last);
				$c = strpos($html, $cost);
				$i = ($c-($y+14))-3;
				$str1 = substr($html, ($y+14), $i);
				echo $str1;
				
				//position
				$y = strpos($html, $tyn);
				$c = strpos($html, $tn);
				$i = ($c-($y+12))-3;
				$str2 = substr($html, ($y+12), $i);
				echo $str2;
				
				//points
				$y = strpos($html, $points);
				$c = strpos($html, $cf);
				$i = ($c-($y+14))-2;
				$str3 = substr($html, ($y+14), $i);
				echo $str3;
				
				add($str, $str1, $str2, $str3);
				
			}//end of function
				
			if (isset($_POST['parse'])) { 
				
				for($i =1; $i<=707; $i++){
					$url = "http://fantasy.premierleague.com/web/api/elements/";
					$url.=$i;
					$url.="/";
					par($url);
			
				}
			
			} 	
						
		?>
		
		<h1>PARSE</h1> 
			<form action="parse.php" method="post"> 
			
			<input type="submit" name="parse" value="Parse" />
		</form>
			
	</body>
</html>