<?php
	require 'connect_libraryotg2.php' ;

	session_start() ;
	
	if( isset( $_SESSION[ 'username' ] ) )
	{
		echo '</br> SSA' ;
	}
	else
	{
		echo '</br> Login to continue! ' ;
		echo '</br> Tresspassing not allowed!' ;
		die() ;
	}
	
	// Acquiring session
	$generator = $_SESSION[ 'username' ] ;
	$gen_name = $_SESSION[ 'name' ] ;
	
		$content = $_POST[ 'content' ] ;
		
		$query_getcount = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`issuestatus` = 0 ;" ;
		$query_getc_run = mysqli_query( $conn, $query_getcount ) ;
		$count_req =  mysqli_num_rows( $query_getc_run ) ;
		
		$task1 = 0 ;
		
		// Query1 : To make new entry
		$q1_ent = "INSERT INTO `maintenance-box` (`srno`, `generator`, `gen_name`,`content`, `status`) VALUES (NULL, '".$generator."', '".$gen_name."','".$content."', '0') ; " ;
		
		if( $q1_run = mysqli_query( $conn, $q1_ent ) )
		{
			$task1 = 1 ;
		}
		else
		{
			$ERR_CODE = 1 ;
			echo ".".$ERR_CODE ;
			// Query for entering new content in maint-suggest is not working
		}
		
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'maint-suggest.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '1800'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$generator."
								</div>
								</br>
								<form action = 'admin-home.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'home'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Home'>
								</form>												
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									 Suggestion Window
								</div>
								</br></br>
								
								<div>
									<!-- Home -->
									<form action = 'admin-home.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'home'>	
										<input id = 'button-tag' type = 'submit' value = 'Home'>
									</form>																
									
									<!-- Track and Collect -->
									<form action = 'admin-collect1.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'collect'>	
										<input id = 'button-tag' type = 'submit' value = 'Collect Book'>
									</form>																
									
									<!-- Manual Issue -->
									<form action = 'admin-issue.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'issue'>	
										<input id = 'button-tag' type = 'submit' value = 'Manual Issue'>
									</form>										
									
									<!-- Issue Requests -->
									<form action = 'admin-requests.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'requests'>	
										<input id = 'button-tag' type = 'submit' value = 'Book Requests(".$count_req.")'>
									</form>																
									
								</div>
								
							</div>
							
						 
						</div>
						
					</body> 
				
				</html>
			 " ;
		
		
			
			echo '<div id = "wrap">' ;
			
			
			
				echo " 
						<div id = 'title-bar'>
							<div id = 'title-bar-text'>
								Suggestions Posted
							</div>
						</div>
				
					 " ;
				
				echo '
						<div id = "record">
													
							</br></br>					
							
							<div id = "book-record"> 
								Suggestion Submitted
							</div>
							
							</br>
						
						</div>
					' ; 
			
			
			
			echo "</div>" ;
	
?>
