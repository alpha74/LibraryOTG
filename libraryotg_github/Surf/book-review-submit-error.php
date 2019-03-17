
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
	$generator = $_SESSION[ 'regno' ] ;
	$year = $_SESSION[ 'year' ] ;
	
	// Getting POSTED values
	if( $_POST != NULL )
	{
		$bookid = $_POST[ 'bookid' ] ;
		$content = $_POST[ 'content' ] ;
		
		// To prevent SQL injection
		$bookid = stripcslashes( $bookid ) ;
		$content = stripcslashes( $content ) ;
		
		$bookid = mysqli_real_escape_string( $conn, $bookid ) ;
		$content = mysqli_real_escape_string( $conn, $content ) ;		

		// Checking
		
	}
	else
	{
		die( "</br>Operation failed!" ) ;
	}
	// Query : To check if the surfer is valid
		$qusercheck = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$generator."';" ;
		
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
					<link rel = 'stylesheet' type = 'text/css' href = 'book-review-submit.css'>
					<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
					<meta http-equiv = 'refresh' content = '300;url=http://192.168.77.4/libraryotg/destroy-session.php'>
				</head>
			
				<body>
					
					<div id = 'box-left'>
						
						<div id = 'box-left1'>
							
							<strong> Library OTG </strong>
							<div id = 'small-text1'>
								Logged in as:".$generator."
							</div>
							
							</br>
							<div>
							
								<form action = 'book-view.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'bookid' value = '".$bookid."'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Go Back'>
								</form>
							
								<form action = 'getdata.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Surf Home'>
								</form>
						
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
							</div>
							
						</div>
						
						</br>
						<div id = 'box-left2'>
							
							<div id = 'box-left2-text'>
								<div id = 'success'> Book Review Submitted</div>
							</div>
							</br>
													
						</div>
						
					 
					</div>
					
				</body> 
			
			</html>
		 " ;
	
	// Date
	date_default_timezone_set( 'Asia/Kolkata' ) ;
	$curr_date = date( 'Y/m/d h:i:s a', time() ) ;
	
	$bookings = 0 ;
	$task1 =  0 ;
	$task2 = 0 ;
	
	// Query1 : For getting number of bookings done on that book
	
	$q1_getrn = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`bookid` = '".$bookid."' AND `bookrequest`.`issuestatus` = '0' ; " ;

	if( $q1_run = mysqli_query( $conn, $q1_getrn ) ) 
	{
		$bookings = mysqli_num_rows( $q1_run ) ;
		$task1 = 1 ;
	}
	else
	{
		$ERR_CODE = 1 ;
		echo ".".$ERR_CODE ;
		// Query for getting number of bookings is not working
	}
	
	// Query2 : Make entry in book-review
	
	$q2_inp = "INSERT INTO `book-review` (`srno`, `generator`, `year`, `bookid`, `content`, `date`, `status`, `genuine`) VALUES (NULL, '".$generator."', '".$year."', '".$bookid."', '".$content."', '".$curr_date."','0', '1') ; " ; 
	
	if( $q2_run = mysqli_query( $conn, $q2_inp ) ) 
	{
		$task2 = 1 ;
	}
	else
	{
		$ERR_CODE = 3 ;
		echo ".".$ERR_CODE ;
		// Query for inputting content in table is not working
	}
		
	
	// Query3 : For getting details of selected book
	$query = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."'  ; " ;
	
	if( $if_query_run = mysqli_query( $conn, $query ) ) 
	{
		//echo ">Query Ran" ;		
			$query_execute = mysqli_fetch_assoc( $if_query_run ) ;
		
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
										Bookings Done: '.$bookings.'
									</div>
									
									<strong> Tags: </strong>'.$query_execute[ 'booktag' ].'
																	
								</div>							
											
								</br>
								<hr>
								<strong> About the Book : </strong>
								</br></br>
								
								<div id = "book-describe">
									'.$query_execute[ 'bookdescribe' ].'
								</div>
										
							</div>	
							
							<div id = "book-right-col">
															
								</br>		
								<img id = "book-image" src = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" alt = "Image Unavailable">
								
								</br></br>
								
								' ;
							if( $query_execute[ 'totalcopies' ] == 0 )
							{
								echo '
									<div id = "book-rating">
										Book Rating Details: NOT_AVAL
									</br>
									</div>

									</div></div>
									 ' ;
							}
							else
							{
								echo '

								
								<div id = "book-rating">
									Book Rating Details: NOT_AVAL
									</br>
								</div>	
								
															
							</div>
							
						</div>
					' ; 
							}			
			}		
		
	}
	else
	{
		echo "</br> Request failed internally" ;
		$ERR_CODE = 2 ;
		echo ".".$ERR_CODE ;
		// Query for gettings details of selected book is not working
	}
	
?>
