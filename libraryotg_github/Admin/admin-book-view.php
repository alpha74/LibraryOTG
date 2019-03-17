
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
	$bookid = $_POST[ 'bookid' ] ;
	
	// Not uses count_req: The number of requests
	
	echo "
			<html>
				<head> 
					<link rel = 'stylesheet' type = 'text/css' href = 'admin-book-view.css'>
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
								
								<form action = 'admin-home.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'requests'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Home'>
								</form>													
								
								<form action = 'admin-bsearch.php' method = 'POST' title = 'Search for author or bookname'>
									<input class = 'hidden' type = 'text' name = 'key' value = '  '>
									<input id = 'button-tag-upper' type = 'submit' value = 'Book Search'>
								</form>
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
							</div>
							
						</div>
						
						</br>
						<div id = 'box-left2'>
							
							
								<div id = 'box-left2-text'>
									Book View Window
								</div>
								</br></br>
								
								<div>
									<!-- All -->
									<form action = 'admin-home.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'home'>	
										<input id = 'button-tag' type = 'submit' value = 'Home'>
									</form>																
									
									<!-- Track and Collect -->
									<form action = 'admin-collect.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'collect'>	
										<input id = 'button-tag' type = 'submit' value = 'Track/Collect Book'>
									</form>																
									
									<!-- Manual Issue -->
									<form action = 'admin-issue.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'issue'>	
										<input id = 'button-tag' type = 'submit' value = 'Manual Issue'>
									</form>										
									
									<!-- Issue Requests -->
									<form action = 'admin-requests.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'window' value = 'requests'>	
										<input id = 'button-tag' type = 'submit' value = 'Book Requests'>
									</form>																
								</div>
						</div>
						
					 
					</div>
					
				</body> 
			
			</html>
		 " ;
	
	$query = "SELECT * FROM `bookdb`" ;
	
	if( $if_query_run = mysqli_query( $conn, $query ) ) 
	{
		//echo ">Query Ran" ;		
		
		while( $query_execute = mysqli_fetch_assoc( $if_query_run ) )
		{
			if( $query_execute[ 'bookid' ] == $bookid )
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
									
									<div id = "book-bookings">
										
									</div>
									
									<strong> Tags: </strong>'.$query_execute[ 'booktag' ].'
																	
								</div>							
											
								</br>
								<hr>
								<strong> About the Book : </strong>
								</br></br>
								
								<div id = "book-describe">
									Cell: <b> 
					' ;

				if( $query_execute[ 'rackcell' ] == '0' )
					echo 'NOT AVAL' ;
				
				else if( $query_execute[ 'rackcell' ] == '1' )
					echo 'MAGAZINE TABLE' ;
				
				else
					echo $query_execute[ 'rackcell' ] ;
				
				echo '	
									</b>
									</br></br>
									'.$query_execute[ 'bookdescribe' ].'
								</div>
										
							</div>	
							
							<div id = "book-right-col">
															
								</br>		
								<a href = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" target = "_blank" title = "View Book Image">
									<img id = "book-image" src = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" alt = "Image Unavailable">
								</a>
								</br></br>
								
								' ;
										
			}
			
		}
			
		
	}
	else
	{
		echo "</br> Request failed internally" ;
	}
	
?>