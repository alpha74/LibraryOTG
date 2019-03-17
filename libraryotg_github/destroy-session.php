<?php

	// Connecting to DB
	require 'connect_libraryotg1.php' ;

	// Date
	date_default_timezone_set( 'Asia/Kolkata' ) ;
	$curr_date = date( 'Y-m-d H:i:s a', time() ) ; 

	// Acquiring session
	session_start() ;
	
	if( $_SESSION[ 'usertype' ] == 25 ) // usertype 25 is for admin, 1 is for surfers
	 $user = $_SESSION[ 'username' ] ;	
	else
	 $user = $_SESSION[ 'regno' ] ;
	
	// Query1 : Set Logout fields in Loginlog
		$q1_logout = "UPDATE `loginlog` SET `loginlog`.`logouttime` = '".$curr_date."', `loggedout` = '1' WHERE `loginlog`.`user` = '".$user."' AND `loginlog`.`loggedout` = 0 " ;
		
		//echo "</br> ".$q1_logout ;
		
		if( $q1_run = mysqli_query( $conn, $q1_logout ) )
		{
			$task1 = 1 ;
		}
		else
		{
			//echo $q1_log ;
			$ERR_CODE = 1 ;
			echo "</br> Operation failed ".$ERR_CODE ;
			die() ;
		}
	
	session_destroy() ;
	
	echo "</br> You have been logged out" ;
	//echo "<meta http-equiv = 'refresh' content = '1;url=http://192.168.77.4/libraryotg/lib_index.php'>" ;
	
	echo " 
			<html>
				<head>
					<link rel = 'icon' href = 'Images/lotg-icon-small.png'>
					<meta name = 'theme-color' content = '#CA6550'>
				</head>
			</html>
		 " ;

	echo "</br> Close this window" ;
	echo "</br></br> <a href = 'http://192.168.77.4/libraryotg/lib_index.php' target = '_blank'> Click here </a> to go to home page" ;

?>
