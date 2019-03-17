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
		
		// Getting POSTED values
		$passold = $_POST[ 'passold' ] ;
		$passnew1 = $_POST[ 'passnew1' ] ;
		$passnew2 = $_POST[ 'passnew2' ] ;
		
		//echo "</br>".$passold ;
		//echo "</br>".$passnew1 ;
		//echo "</br>".$passnew2 ;
		
		$task1 = 0 ;
		$task2 = 0 ;
		$task3 = 0 ;
		
		if( $passnew1 == $passnew2 )
		{	
			$task1 = 1 ;
			
			// Query2 : For reading old password
			$q2_getp = "SELECT `password` FROM `adminuser` WHERE `adminuser`.`username` = '".$username."'  ;" ;
			
			if( $q2_run = mysqli_query( $conn, $q2_getp ) )
			{
				$q2_exe = mysqli_fetch_assoc( $q2_run ) ;
				$cpass = $q2_exe[ 'password' ] ;
				
				if( $cpass == $passold )
				{
					$task2 = 1 ;
					
					// Query 3 : Insert new password
					$q3_newp = "UPDATE `adminuser` SET `password` = '".$passnew2."' WHERE `adminuser`.`username` = '".$username."' ; " ;
					
					if( $q3_run = mysqli_query( $conn, $q3_newp ) )
					{
						$task3 = 1 ;
					}
					else
					{
						$ERR_CODE = 4 ;
						echo ".".$ERR_CODE ;
						// Query for setting new password is not working
					}
				}
				else
				{
					$ERR_CODE = 3 ;
					echo ".".$ERR_CODE ;
					// Old passwords do not match
				}
			}
			else
			{
				$ERR_CODE = 2 ;
				echo ".".$ERR_CODE ;
				// Query for getting password is not working
			}
		}
		else
		{
			$ERR_CODE = 1 ;
			echo ".".$ERR_CODE ;
			// New passwords do not match
		}
		
		
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
									 Change Password Status
								</div>
								</br></br>
								
								<div>
			" ;

			if( $task1 == 0 )
				echo "<div id = 'error'> FAIL1 </div> " ;
			else
				echo "<div id = 'success'> DONE1 </div> " ;
			
			if( $task2 == 0 )
				echo "<div id = 'error'> FAIL2 </div> " ;
			else
				echo "<div id = 'success'> DONE2 </div> " ;
			
			if( $task3 == 0 )
				echo "<div id = 'error'> FAIL3 </div> " ;
			else
				echo "<div id = 'success'> DONE3 </div> " ;
			
			echo "
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
								Change Password Status
							</div>
						</div>
				
					 
				
					
							<div id = 'record'>
														
								</br></br>					
					" ;			
					
				if( $task3 == 1 )
				{
					echo '
								<div id = "book-record"> 
									<div id = "success"> Password Changed </div>
								</div>
								
								</br>
						' ; 
				
				}
				else
				{
					if( $task1 == 0 )
					{
						echo "<div id = 'error'> New passwords do not match </div>" ;
					}
					else
					{
						if( $task2 == 0 )
						{
							echo "<div id = 'error'> Old password is wrong </div>" ;
						}
					}
				}
			
			echo "
					</br>
					<form action = 'admin-home.php' method = 'POST'>
						<input class = 'hidden' type = 'text' name = 'window' value = 'home'>
						<input id = 'form-book-book-submit' type = 'submit' value = 'OK'>
					</form>								
						</div>
					</div>" ;
	
?>