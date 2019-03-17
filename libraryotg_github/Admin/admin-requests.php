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
	
	$regnofilter = 'ALL' ;
	
	// Acquiring session
	$username = $_SESSION[ 'username' ] ;
	
		// Getting POSTED values
		$window = $_POST[ 'window' ] ;
		if( @$_POST[ 'regnofilter' ] != NULL )
		 $regnofilter = $_POST[ 'regnofilter' ] ;
		
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
		
		// Query to get number of requests
		if( $regnofilter == 'ALL' || $regnofilter == 'all' )
		 $query_getcount = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`issuestatus` = 0 ;" ;
		else
		 $query_getcount = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`issuestatus` = 0 AND `bookrequest`.`regno` = '".$regnofilter."' ;" ;
	
		$query_getc_run = mysqli_query( $conn, $query_getcount ) ;
		$count_req =  mysqli_num_rows( $query_getc_run ) ;
			
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-requests.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '600'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$username."
								</div>
								
								</br>
								
								<form action = 'admin-requests.php' method = 'POST'>
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
									
									<!-- Issue and Filter Requests -->
									<form action = 'admin-requests.php' method = 'POST' title = 'Enter Reg. No. to filter results: Use ALL to show all'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'requests'>	
										<input id = 'button-tag' type = 'submit' value = 'Book Requests(".$count_req.")'>
										<input id = 'input-bsearch' type = 'text' name = 'regnofilter' value = ".$regnofilter." placeholder = 'Filter Reg. No.' maxlength = 10>	
									</form>								
									
								</div>
								
							</div>
							
						 
						</div>
						
					</body> 
				
				</html>
			 " ;
		
		// Getting Request List
		// Query to get number of requests
		if( $regnofilter == 'ALL' )
		 $query_req = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`issuestatus` = 0 ;" ;
		else
		 $query_req = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`issuestatus` = 0 AND `bookrequest`.`regno` = '".$regnofilter."' ;" ;
		
		if( $if_query_run = mysqli_query( $conn, $query_req ) ) 
		{
			//echo ">Query Ran" ;
			
			echo '<div id = "wrap">' ;			
			
			echo " 
						<div id = 'title-bar'>
							<div id = 'title-bar-text'>
								".$win_key.": " ;
								
								echo $count_req ;
								echo "
							
							</div>
						</div>
						</br>
					 " ;
			
			while( $query_execute_req = mysqli_fetch_assoc( $if_query_run ) )
			{								
				echo '
						<div id = "record" title = "CAUTION: The operations on this window do not have Confirm page">
							<div id = "book-record"><strong>Booking ID: '.$query_execute_req[ 'srno' ].'</strong></div>
						
							</br>
							<div id = "author-record">
								<!--Surfer ID: -->
							
								<form id = "form-view-book" action = "track-book-regno.php" method = "post" title = "View this surfer">
									<input class = "hidden" type = "text" name = "regno" value = "'.$query_execute_req[ 'regno'].'">
									<input class = "hidden" type = "text" name = "year" value = "'.$query_execute_req[ 'year'].'">
									<input id = "form-view-book-glass" type = "submit" value = "'.$query_execute_req[ 'regno' ].'">
								</form>								
								
							</div>		
																			
							<div id = "book-request"> 
								<strong>Book ID: '.$query_execute_req[ 'bookid' ].'</strong>
							</div>	
							
							<div id = "buttons-record"> 
								<form id = "form-view-book" action = "admin-book-view.php" method = "post">
									<input class = "hidden" type = "text" name = "bookid" value = "'.$query_execute_req[ 'bookid'].'">
									<input class = "hidden" type = "text" name = "count_req" value = "'.$count_req.'">
									<input id = "form-view-book-submit" type = "submit" value = "View Book">
								</form>								
							</div>
							<div id = "book-record"> 
								<form id = "form-book-book" action = "admin-issue-mconf.php" method = "post">
									<input class = "hidden" type = "text" name = "srno" value = "'.$query_execute_req[ 'srno' ].'">
									<input class = "hidden" type = "text" name = "regno" value = "'.$query_execute_req[ 'regno' ].'">
									<input class = "hidden" type = "text" name = "year" value = "'.$query_execute_req[ 'year' ].'">
									<input class = "hidden" type = "text" name = "bookid" value = "'.$query_execute_req[ 'bookid' ].'">
									<input id = "issue-confirm-input" type = "text" name = "stampno"  placeholder = "Acc.No." required>
									
									<input id = "form-book-book-submit" type = "submit" value = "Issue">
								</form>
							</div>
							<div id = "book-record"> 
								<form id = "form-book-reject" action = "admin-request-reject.php" method = "post">					
									<input class = "hidden" type = "text" name = "srno" value = "'.$query_execute_req[ 'srno' ].'">
									<input class = "hidden" type = "text" name = "regno" value = "'.$query_execute_req[ 'regno' ].'">
									<input class = "hidden" type = "text" name = "year" value = "'.$query_execute_req[ 'year' ].'">
									<input class = "hidden" type = "text" name = "bookid" value = "'.$query_execute_req[ 'bookid' ].'">
									
									<input id = "form-book-reject-submit" type = "submit" value = "Reject">
								</form>
							</div>
						</div>
					' ; 
							
			}
			
			echo "</div>" ;
			
		}
		else
		{
			echo ">Query failed" ;
		}
	
?>
