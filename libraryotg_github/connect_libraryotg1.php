<?php

	$mysql_host = 'localhost' ;
	$mysql_user = 'root' ;
	//$mysql_password = '$onNEPTUNE65' ;
	$mysql_passowrd = '' ;
	$mysql_db = 'libraryotg' ;

	$conn = mysqli_connect( $mysql_host, $mysql_user, $mysql_password, $mysql_db) ;
	
	// Checking connection
	if( $conn )
	{
		//echo " >Connected to DB" ;
		echo "" ;
	}		
	else
	{
		echo " > Connection to DB failed" ;
		die() ;
	}

	echo " 
			<html>
				<head>
					<link rel = 'icon' href = 'Images/lotg-icon-small.png'>
					<meta name = 'theme-color' content = '#CA6550'>
				</head>
			</html>
		 " ;

?>
