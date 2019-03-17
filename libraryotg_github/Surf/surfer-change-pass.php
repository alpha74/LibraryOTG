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
	$regno = $_SESSION[ 'regno' ] ;
	$year = $_SESSION[ 'year' ] ;
		
		
		// Query : To check if the surfer is valid
		$qusercheck = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."';" ;
		
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
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'surfer-change-pass.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '300;url=http://192.168.77.4/libraryotg/destroy-session.php'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$regno."
								</div>
								
								</br>
								
								<form action = 'getdata.php' method = 'post'>
									<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Home'> 
								</form>
								
								<form action = 'surfer-suggest.php' method = 'POST'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Suggest' title = 'Suggest for better'>
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
									 Max. Password Length: 12
									 </br>
									 Min. Password Length: 1
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
								<form id = "form-book-book" action = "surfer-change-pass-done.php" method = "post">
									<input id = "form-input-box" type = "password" name = "passold" placeholder = "Old Password" required>
									</br>
									<hr>
									</br>
									<input id = "form-input-box" type = "password" name = "passnew1" placeholder = "New Password- Max:12" required>
									<input id = "form-input-box" type = "password" name = "passnew2"  placeholder = "Confirm Password- Min: 1" required>
									
									</br></br>
									<input id = "form-book-book-submit" type = "submit" value = "Change">
								</form>
							</div>
							
							</br>
						
						</div>
					' ; 
			
			
			
			echo "</div>" ;
?>
