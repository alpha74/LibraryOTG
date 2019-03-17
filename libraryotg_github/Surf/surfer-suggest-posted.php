<?php
	require 'connect_libraryotg2.php' ;

	session_start() ;
	
	if( isset( $_SESSION[ 'regno' ] ) )
	{
		echo '</br> SA' ;
	}
	
	else
	{
		echo 'Please login to continue!' ;
		echo "</br></br> Tresspassing not allowed" ;
		die() ;
	}		

	// Acquiring session
	$generator = $_SESSION[ 'regno' ] ;
	$year = $_SESSION[ 'year' ] ;
	$gen_name = $_SESSION[ 'name' ] ;

		// Getting POSTED values
		if( $_POST != NULL )
		 $content = $_POST[ 'content' ] ;
		else
		 die( 'Operation failed!' ) ;
		
		// To prevent SQL injection
		$content = stripcslashes( $content ) ;
		
		$content = mysqli_real_escape_string( $conn, $content ) ;
		
		$task1 = 0 ;
		
		// Query : To check if the surfer is valid
		$qusercheck = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$generator."';" ;
		
		if( $qrun = mysqli_query( $conn, $qusercheck ) )
		{
			$count_id = mysqli_num_rows( $qrun ) ;
			
			if( $count_id != 1 )
			{
				echo "</br></br> You cannot pass through! " ;
				die() ;
			}
		}
		else
		{
			$ERR_CODE = 1 ;
			echo "</br> Operation failed: ".$ERR_CODE ;
			die() ;
		}
		
		// Query1 : To make new entry
		$q1_ent = "INSERT INTO `maintenance-box` (`srno`, `generator`, `gen_name`, `content`, `status`) VALUES (NULL, '".$generator."', '".$gen_name."','".$content."', '0') ; " ;
		
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
						<link rel = 'stylesheet' type = 'text/css' href = 'surfer-suggest.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '120;url=http://192.168.77.4/libraryotg/destroy-session.php'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
															
								<div id = 'small-text1'>
									Logged in as:".$generator."
								</div>
								</br>
								<form action = 'getdata.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Home'>
								</form>								
											
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									 Suggestion Posted Window
								</div>
								</br></br>
								
								<div>
								
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
