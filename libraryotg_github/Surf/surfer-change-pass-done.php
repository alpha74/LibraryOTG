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
		
		
		// Getting POSTED values
		if( $_POST == NULL )
			die( "</br> Cannot open page!" ) ;
		
		$passold = $_POST[ 'passold' ] ;
		$passnew1 = $_POST[ 'passnew1' ] ;
		$passnew2 = $_POST[ 'passnew2' ] ;
		
		// To prevent SQL injection
		$passold = stripcslashes( $passold ) ;
		$passnew1 = stripcslashes( $passnew1 ) ;
		$passnew2 = stripcslashes( $passnew2 ) ;
		
		$passold = mysqli_real_escape_string( $conn, $passold ) ;
		$passnew1 = mysqli_real_escape_string( $conn, $passnew1 ) ;
		$passnew2 = mysqli_real_escape_string( $conn, $passnew2 ) ;
		
		//echo "</br>".$passold ;
		//echo "</br>".$passnew1 ;
		//echo "</br>".$passnew2 ;
		
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
		
		$task1 = 0 ;
		$task2 = 0 ;
		$task3 = 0 ;
		
		if( $passnew1 == $passnew2 )
		{	
			$task1 = 1 ;
			
			// Query2 : For reading old password
			$q2_getp = "SELECT `password` FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."'  ;" ;
			
			if( $q2_run = mysqli_query( $conn, $q2_getp ) )
			{
				$q2_exe = mysqli_fetch_assoc( $q2_run ) ;
				$cpass = $q2_exe[ 'password' ] ;
				
				if( $cpass == $passold )
				{
					$task2 = 1 ;
					
					// Query 3 : Insert new password
					$q3_newp = "UPDATE `studentuser".$year."` SET `password` = '".$passnew2."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ;
					
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
						<link rel = 'stylesheet' type = 'text/css' href = 'surfer-change-pass.css'>
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
					
					<form action = 'surfer-change-pass.php' method = 'post'>
						<input class = 'hidden' type = 'text' name = 'filter' value = 'all'>
						<input id = 'form-book-book-submit' type = 'submit' value = 'OK'> 
					</form>				
						</div>
					</div>" ;
?>
