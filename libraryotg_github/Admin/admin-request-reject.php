
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
		$srno = $_POST[ 'srno' ] ;
		$regno = $_POST[ 'regno' ] ;
		$year = $_POST[ 'year' ] ;
		$bookid = $_POST[ 'bookid' ] ;
		//$stampno = $_POST[ 'stampno' ] ;

		// Date
		date_default_timezone_set( 'Asia/Kolkata' ) ;
		$curr_date = date( 'Y/m/d h:i:s a', time() ) ;
		echo $curr_date ;
		
		//echo "</br>".$bookid ;
		//echo "</br>".$username ;
		//echo "</br>".$srno ;
		//echo "</br>".$regno ;
		//echo "</br>".$year ;
	
	// Main tasks
	
	$task1 = 0 ;
	$task2 = 0 ;

	$ERR_CODE = 0 ;
	
	// Query1 : Change issuestatus to 2 ( REJECTED ) in bookrequest
	// No double checking
	
	$q1_rej = "UPDATE `bookrequest` SET `issuestatus` = '2' WHERE `bookrequest`.`srno` = '".$srno."' ; " ;
	//echo "<br>".$q1_rej ;
	
	if( $q1_run = mysqli_query( $conn, $q1_rej ) )
	{
		//echo "</br> Query1 Ran " ;
		$task1 = 1 ;		
	}
	else
	{
		$ERR_CODE = 1 ;
		// Query for changing issue status to 2 is not working
	}
	
	// Query2 : Decrement book requests in studentuser_
	// No double checking
	
	$q2_getreq = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ; 
	
	if( $q2_run = mysqli_query( $conn, $q2_getreq ) )
	{		
		$q2_exe = mysqli_fetch_assoc( $q2_run ) ;
		
		$value1 = $q2_exe[ 'bookrequests' ] ;
		$value1 = $value1 - 1 ;
		
		$q2_decreq = "UPDATE `studentuser".$year."` SET `bookrequests` = '".$value1."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ; 
				
		if( $q2_run = mysqli_query( $conn, $q2_decreq ) )
		{
			//echo "</br> Query2-2 Ran" ;
			$task2 = 1 ;
		}
		else
		{
			$ERR_CODE = 3 ;
			// Decrementing book request in studentuser_ is not working
		}
	}
	else
	{
		$ERR_CODE = 2 ;
		// Getting number of request query on studentuser_ is not working
	}		
	
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-request-reject.css'>
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
								<div>
									
									<form action = 'admin-requests.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'requests'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Go Back'>
									</form>													
							
									<!-- Home -->
									<form action = 'admin-home.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'home'>	
										<input id = 'button-tag-upper' type = 'submit' value = 'Home'>
									</form>																
									
									<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
										<button id = 'button-tag-upper'> Sign Out </button>
									</a>
								</div>
								
							</div>
							
							</br>
							<div id = 'box-left2'>					
									<div id = 'box-left2-text'>
										Reject Status
										
										" ;
		if( $task1 == 0 )
		 echo "</br><div id = 'error'> FAIL1 </div>" ;
		else
		 echo "</br><div id = 'success'> DONE1 </div>" ;
		
		if( $task2 == 0 )
		 echo "<div id = 'error'> FAIL2 </div>" ;
		else
		 echo "<div id = 'success'> DONE2 </div>" ;
												
		echo "							
									</div>
									</br></br>
									
									</div>
							</div>
						</div>
					</body> 
				</html>
			 " ;
			
		$query = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."' ;" ;
		
		if( $if_query_run = mysqli_query( $conn, $query ) ) 
		{
			//echo ">Query Ran" ;	
				$query_execute = mysqli_fetch_assoc( $if_query_run ) ;
			
				if( @$query_execute[ 'bookid' ] == $bookid )
				{				
					echo '
							<div id = "record">
							
								<div id = "book-left-col">
							
									<div id = "book-record"><strong>'.$query_execute[ 'bookname' ].'</strong> </div>
							
									<div id = "author-record">'.$query_execute[ 'bookauthor' ].'</div>
							
									</br></br>
									<hr>
									</br>
															
									<div id = "book-left"> 
										<strong> Left: '.$query_execute[ 'totalcopies' ].' </strong></br>
										
										<strong> Tags: </strong>'.$query_execute[ 'booktag' ].'
																		
									</div>							
												
									</br>
									<hr>
									</br>
									<strong> Issue Details : </strong>
									</br></br>
									
									<div id = "book-describe">
										Cancelled
									</div>
									
									</br>	
								</div>	
									
									' ;						
				}
				else
				{
					echo '
							<div id = "record">
							
								<div id = "book-left-col">
							
									<div id = "book-record"><strong> Invalid Book ID </strong> </div>							
									</br></br>
									<hr>
									</br>
															
									
									<strong> Issue Details : </strong>
									</br></br>
									
									<div id = "book-describe">
										Cancelled
									</div>
									
									</br>	
								</div>	
									
							' ;						
				}			
			
		}
		
		else
		{
			echo "</br> Request failed internally" ;
			
			echo "	
					</br></br>
					<ul>
						<li> Book Issue operation is completed </li>
						<li> Table not reachable </li>
					</ul>			
				 " ;
		}
	echo "</br> ERROR : ".$ERR_CODE ;	
?>