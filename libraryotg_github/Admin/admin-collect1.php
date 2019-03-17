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
	$username = $_SESSION[ 'username' ] ;

		// Getting POSTED data
		$window = $_POST[ 'window' ] ;
		
		$query_getcount = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`issuestatus` = 0 ;" ;
		$query_getc_run = mysqli_query( $conn, $query_getcount ) ;
		$count_req =  mysqli_num_rows( $query_getc_run ) ;
		
		switch( $window )
		{
			case "home": 	$win_key = "Home" ;
							break ;
			case "collect": $win_key = "Book Collect" ;
							break ;
			case "issue": 	$win_key = "Manual Issue" ;
							break ;
			case "requests": 	$win_key = "Book Requests" ;
							break ;							
			default: $win_key = "Home Window" ;
		}
				
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-collect1.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '3600'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$username."
								</div>
								
								</br>
								
								<form action = 'admin-issue.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = '".$window."'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Refresh'>
								</form>								
								
								<form action = 'maint-suggest.php' method = 'POST'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Suggest to Maintenance'>
								</form>
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									 ".$win_key." Window
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

									<!-- Book/ Author search -->
									<form action = 'admin-bsearch.php' method = 'POST' title = 'Search for author or bookname: MAX: 30'>
										<input id = 'input-bsearch' type = 'text' name = 'key' placeholder = 'Search Book/ Author' maxlength = 30 required>
										<input id = 'button-tag-bsearch' type = 'submit' value = 'Go'>
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
								".$win_key."
							</div>
						</div>
				
					 " ;
				
				echo '
						<div id = "record">
													
							</br></br>					
							
							<div id = "book-record"> 
							
								<strong> Instructions for Book Return </strong> </br>
								<ul>
									<li> Enter Acc. number on book and Reg. No. of Surfer. Press Submit. </li>
									<li> If details of Surfer match with the Standing Person, press Collect </li>
									<li> If not, Collect operation is not possible </li>
								</ul>
								
								<form id = "form-book-book" action = "admin-collect2.php" method = "post">
									<input id = "form-input-box" type = "text" name = "stampno" placeholder = "Acc. No." maxlength = 5 required>	
									</br>
									<input id = "form-input-box" type = "text" name = "regno" placeholder = "Registration No." maxlength = 10 required>
									</br></br>
									<input id = "form-book-book-submit" type = "submit" value = "Submit"> 
								</div>
																		
									
							</div>
							
							</br>
						
						</div>
					' ; 
			
			
			
			echo "</div>" ;
	
?>
