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
	
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-change-pass.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '1200'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$username."
								</div>
								
								</br>
								
								<form action = 'admin-home.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'home'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Home'>
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
									 Change Password Window
									 </br></br>
									 Max. Length of Password: 12
									 </br>
									 Min. Length of Password: 1
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
								Change Password
							</div>
						</div>
				
					 " ;
				
				echo '
						<div id = "record">
													
							</br></br>					
							
							<div id = "book-record"> 
								<form id = "form-book-book" action = "admin-change-pass-done.php" method = "post">
									<input id = "form-input-box" type = "password" name = "passold" placeholder = "Old Password" maxlength = 12 required>
									</br>
									<hr>
									</br>
									<input id = "form-input-box" type = "password" name = "passnew1" placeholder = "New Password" maxlength = 12 required>
									<input id = "form-input-box" type = "password" name = "passnew2"  placeholder = "Confirm Password" maxlength = 12 required>
									
									</br></br>
									<input id = "form-book-book-submit" type = "submit" value = "Change">
								</form>
							</div>
							
							</br>
						
						</div>
					' ; 
			
			
			
			echo "</div>" ;
?>
