<?php
	require 'connect_libraryotg1.php' ;
	
	// Date
	date_default_timezone_set( 'Asia/Kolkata' ) ;
	$curr_date = date( 'd/m/Y H:i:s a', time() ) ;

	// Client IP
	$client_ip = $_SERVER[ 'REMOTE_ADDR' ] ;
	echo $client_ip ;
	
	// Track log
	$track_log = 1 ;
	
	if( $_POST == NULL )
	{
		echo "</br> Access Denied!" ;
		echo "</br> Tresspasers will be terminated!" ;
	}
	else
	{
		$username = $_POST[ 'username' ] ;
		$password = $_POST[ 'password' ] ;
		
		// To prevent mysql injection
		$username = stripcslashes( $username ) ;
		$password = stripcslashes( $password ) ;
		
		$username = mysqli_real_escape_string( $conn, $username ) ;
		$password = mysqli_real_escape_string( $conn, $password ) ;
		
		// Some variables
		$year2016 = 2016 ;
		$year2017 = 2017 ;
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'verify-admin.css'>
						<meta http-equiv = 'refresh' content = '300;url=http://192.168.77.4/libraryotg/destroy-session.php'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								<strong> Library OTG </strong>
							</div>
							".$curr_date."
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									Current Window Display 
								</div>
								</br>
								
								<div>
									Page Options Display
								</div>
								
							</div>
							
						 
						</div>
						
					</body> 
				
				</html>
			 " ;
		
			// Query1
			$query = 'SELECT * FROM `adminuser` where USERNAME = "'.$username.'" && PASSWORD = "'.$password.'" ;' ;
			
			$task1 = 0 ;
			$task2 = 0 ;
			
			if( $if_query_run = mysqli_query( $conn, $query ) ) 
			{
				//echo "</br> Query ran " ;
				
				$query_execute = mysqli_fetch_assoc( $if_query_run ) ;
				
				echo '<div id = "output"> ' ;
				
				if( $query_execute[ 'username' ] == $username )
				{
					if( $query_execute[ 'password' ] == $password )
					{
						$task1 = 1 ;
						// Setting the session
						session_start() ;
						
						$_SESSION[ 'username' ] = $query_execute[ 'username' ] ;
						$_SESSION[ 'name' ] = $query_execute[ 'name' ] ;
						$_SESSION[ 'usertype' ] = 25 ; // usertype 25 is for admin, 4 for surfers
						
						// Logs
						// Query2 
						$q2_log = "INSERT INTO `loginlog` (`srno`,`localip`,`user`) VALUES (NULL,'".$client_ip."','".$username."') " ;
						
						if( $q2_run = mysqli_query( $conn, $q2_log ) )
						{
							$task2 = 1 ;
						}
						else
						{
							$ERR_CODE = 1 ;
							echo "</br> Cannot load ".$ERR_CODE ;
							die() ;
						}
						
						echo "
								Welcome Admin!
								</br>
								<div>
									<div id = 'correct-text1'>
										".$query_execute[ 'name' ]."
									</div>
								
								</br>
								<form id = 'form1' action = 'Admin/admin-home.php' method = 'post'>
									<input class = 'hidden' type = 'text' name = 'username' value = ".$query_execute[ 'username' ].">
									<input class = 'hidden' type = 'text' name = 'window' value = 'home'>
									<input id = 'action_button2' type = 'submit' value = 'Open Workspace'> 
								</form>
								</br>
								<form action = 'Admin/admin-change-pass.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'username' value = ".$query_execute[ 'username' ].">
									<input id = 'action_button1' type = 'submit' value = 'Change Password'> 
								</form>
								
								<a href = 'lib_rules.html'>
									<button id = 'action_button3' title = 'See Rules and Regulations'> See Rules </button>
								</a>
								</br>
								
								<form action = 'Admin/maint-suggest.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'generator' value = ".$username.">
									<input id = 'action_button3' type = 'submit' value = 'Suggest to Maintenance'>
								</form>
								
											
								<a id = 'form1' href = 'destroy-session.php'>
									<button id = 'action_button4'> Sign Out </button>
								</a>
								
								
							" ;
								 
					}
					else
					{
						echo "
							</br> 
							<div id = 'wrong-text'>
								Wrong password 
							</div>
							
							</br>
								<a href = 'lib_index.php'>
									<button id = 'action_button1'> Go to Home </button>
								</a>
							" ;
					}
				}
				else
				{
					echo "
							<div id = 'wrong-text'>
								Wrong credentials! 
							</div>
								</br>
								<a href = 'lib_index.php'>
									<button id = 'action_button1'> Go to Home </button>
								</a>
						" ;
				}
				
				echo '</div>' ;
			}
			else 
			{
				echo "</br> Query failed " ;
			}
				
	}
?>
