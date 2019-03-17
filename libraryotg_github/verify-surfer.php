
<?php
	require 'connect_libraryotg1.php' ;
	
	// Date
	date_default_timezone_set( 'Asia/Kolkata' ) ;
	$curr_date = date( 'd/m/Y h:i:s a', time() ) ;
	// Client IP
	
	$client_ip = $_SERVER[ 'REMOTE_ADDR' ] ;
	echo $client_ip ;
	
	// Track log
	$track_log = 1 ;
	
	if( $_POST == NULL )
	{
		echo "</br> Access Denied!" ;
		echo "</br> Tresspasers will be deleted!" ;
	}
	else
	{
	
		$year = $_POST[ 'year' ] ;
		$regno = $_POST[ 'regno' ] ;
		$password = $_POST[ 'password' ] ;
		
		// To prevent mysql injection
		$year = stripcslashes( $year ) ;
		$regno = stripcslashes( $regno ) ;
		$password = stripcslashes( $password ) ;
		
		$year = mysqli_real_escape_string( $conn, $year ) ;
		$regno = mysqli_real_escape_string( $conn, $regno ) ;
		$password = mysqli_real_escape_string( $conn, $password ) ;
		
		// Some variables
		$year2016 = 2016 ;
		$year2017 = 2017 ;
				
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'verify-surfer.css'>
						<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
						<meta http-equiv = 'refresh' content = '300;url=http://192.168.77.4/libraryotg/destroy-session.php'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								<strong> Library OTG </strong> 
								<button id = 'mobile-button1' onclick = 'ShowInst()'> More </button>
								<button id = 'mobile-button2' onclick = 'ShowButtons()'> Profile </button>
							</div>
							".$curr_date."

							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									
								</div>
								</br>
								
								<div>
									See all instructions by pressing Instructions button.
							
									</br></br>
									<strong><a href = 'lib_rules.html'>Instructions</a></strong>
									<ul>
										<li> You can have either 2 Book requests or 2 issued books at max at a time </li>
										<li> Book request can only be cancelled by Library Admin. Be cautious while requesting.</li>
										<li> Use Uppercase for Registration No. in Login field </li>
									</ul>
								</div>
								
							</div>
							
						 
						</div>
						
					</body> 
				
				</html>
			 " ;
		
		$task1 = 0 ;
		$task2 = 0 ;
			
		
		if( $year == $year2016 || $year == $year2017 )
		{
			// Query1
			$query = 'SELECT * FROM `studentuser'.$year.'` where REGNO = "'.$regno.'" && PASSWORD = "'.$password.'" ;' ;
			
			if( $if_query_run = mysqli_query( $conn, $query ) ) 
			{
				//echo "</br> Query ran " ;
				$task1 = 1 ;
				$query_execute = mysqli_fetch_assoc( $if_query_run ) ;
				
				echo '<div id = "output"> ' ;
				
				if( $query_execute[ 'regno' ] == $regno )
				{
					if( $query_execute[ 'password' ] == $password )
					{
						// Setting the session
						session_start() ;
						
						$_SESSION[ 'name' ] = $query_execute[ 'name' ] ;
						$_SESSION[ 'regno' ] = $query_execute[ 'regno' ] ;
						$_SESSION[ 'year' ] = $year ;
						$_SESSION[ 'usertype' ] = 4 ; // Type 25 is for admin, 4 is for surfers					
						// Logs track
						// Query2 
						$q2_log = "INSERT INTO `loginlog` (`srno`, `localip`, `user`) VALUES (NULL, '".$client_ip."', '".$regno."') " ;
						
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
								Welcome!
								</br>
								<div>
									<div id = 'correct-text1'>
										".$query_execute[ 'name' ]."
									</div>
								
									<div id = 'correct-text2'>
										".$query_execute[ 'regno' ]."
									</div>
								
									<div id ='profile-buttons'>
										</br>
										<form id = 'form1' action = 'Surf/getdata.php' method = 'post'>
											<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
											<input id = 'action_button2' type = 'submit' value = 'Surf Library'> 
										</form>
										
										<form id = 'form1' action = 'Surf/surfer-change-pass.php' method = 'POST'>
											<input id = 'action_button1' type = 'submit' value = 'Change Password'> 
										</form>
										
										<a id = 'form1' href = 'http://192.168.77.4/libraryotg/lib_rules.html' target = '_blank'>
											<button id = 'action_button3' title = 'See Rules'> Instructions </button>
										</a>
										
										<form id = 'form1' action = 'Surf/surfer-suggest.php' method = 'POST'>
											<input id = 'action_button3' type = 'submit' value = 'Suggest' title = 'Suggest for better'>
										</form>
																	
										</br>
										<a id = 'form1' href = 'destroy-session.php'>
											<button id = 'action_button4'> Sign Out </button>
										</a>
										
									</div>
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
		else
		{
			echo "</br> Credential error !" ;
			echo "
					<a href = 'lib_index.php'>
					<button id = 'action_button1'> Go to Home </button>
					</a>
				" ;
		}
	}
	
?>
<script>
	
	function ShowInst()
	{
		document.getElementById( "box-left2" ).style.display = "inline-block" ;
		document.getElementById( "output" ).style.display = "none" ;
		
		document.getElementById( "mobile-button1" ).style.display = "none" ;
		document.getElementById( "mobile-button2" ).style.display = "inline-block" ;
	}
	function ShowButtons()
	{
		document.getElementById( "box-left2" ).style.display = "none" ;
		document.getElementById( "output" ).style.display = "inline-block" ;
		
		document.getElementById( "mobile-button1" ).style.display = "inline-block" ;
		document.getElementById( "mobile-button2" ).style.display = "none" ;
	}

</script>
