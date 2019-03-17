
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
	if( $_POST != NULL )
	 $bookid = $_POST[ 'bookid' ] ;
	else
	 $bookid = 1 ;
	
	// To prevent SQL injection
	$bookid = stripcslashes( $bookid ) ;
		
	$bookid = mysqli_real_escape_string( $conn, $bookid ) ;
	
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
	
	echo "
			<html>
				<head> 
					<link rel = 'stylesheet' type = 'text/css' href = 'book-view.css'>
					<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
					<meta http-equiv = 'refresh' content = '300;url=http://192.168.77.4/libraryotg/destroy-session.php'>
				</head>
			
				<body>
					
					<div id = 'box-left'>
						
						<div id = 'box-left1'>
							
							<strong> Library OTG </strong>
							
							<button id = 'show-menu' onclick = 'ShowMenu()'> Main Menu </button>
							<button id = 'hide-menu' onclick = 'HideMenu()'> Hide </button>
							
							<div id = 'small-text1'>
								Logged in as:".$regno."
							</div>
														
							</br>
							<div id = 'main-menu'>
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
								Review book  
								<button id = 'show-reviewbox' onclick = 'ShowReviewbox()'> Write </button>
								<button id = 'hide-reviewbox' onclick = 'HideReviewbox()'> Hide </button>
							</div>
							</br>
							
							<form id = 'reviewbox' action = 'book-review-submit.php' method = 'POST'>
									<textarea id = 'textarea1' maxlength = '130' name = 'content' required> I like It! </textarea>
									<input class = 'hidden' type = 'text' name = 'bookid' value = ".$bookid.">
									<input id = 'button-tag' type = 'submit' value = 'Submit Review'>
							</form>
						
						</div>
						
					 
					</div>
					
				</body> 
			
			</html>
		 " ;
	
	$bookings = 0 ;
	
	// Query1 : For getting number of bookings done on that book
	
	$q1_getrn = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`bookid` = '".$bookid."' AND `bookrequest`.`issuestatus` = '0' ; " ;

	if( $q1_run = mysqli_query( $conn, $q1_getrn ) ) 
	{
		$bookings = mysqli_num_rows( $q1_run ) ;
	}
	else
	{
		$ERR_CODE = 1 ;
		echo ".".$ERR_CODE ;
		// Query for getting number of bookings is not working
	}
	
	// Query2 : For getting details of selected book
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
									Cell: <b> 
					' ;

				if( $query_execute[ 'rackcell' ] == '0' )
					echo '<style: color: red;> NOT AVAL</style>' ;
				
				else if( $query_execute[ 'rackcell' ] == '1' )
					echo 'MAGAZINE TABLE' ;
				
				else
					echo $query_execute[ 'rackcell' ] ;
				
				echo '	
									</b>
									</br></br>
									'.$query_execute[ 'bookdescribe' ].'
									
									</br>
								</div>
										
							</div>	
							
							<div id = "book-right-col">
															
								</br>		
								<a href = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" target = "_blank" title = "View Book Image">
									<img id = "book-image" src = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" alt = "Image Unavailable">
								</a>
								
								</br></br>
								
								' ;
							if( $query_execute[ 'totalcopies' ] <= 1 || $query_execute[ 'issuepermit' ] == 0 )
							{
								echo '
									<div id = "book-rating">
									Book Rating: <b>' ;
									
									// Calculating book rating	
									$num_issued = $query_execute[ 'notimesissued' ] ;	
								
									// Query 3 : Getting total of books issued 
									$q3_tot = "SELECT SUM(`notimesissued`) FROM `bookdb` WHERE 1" ;
								
									if( $q3_run = mysqli_query( $conn, $q3_tot ) )
									{
										$sum_result = mysqli_fetch_row( $q3_run ) ;
										$issued_total = $sum_result[ 0 ] ;
									
										if( $issued_total < 1 ) 
										{
											$rating = 0 ;
										}
										else
										{
											$rating = ( $num_issued / $issued_total ) * 100 ;
											$rating = ( int ) $rating ;
										}
									
										echo $rating ;
									}
									else
									{
										$ERR_CODE = 3 ;
										echo 'NOT AVAL' ;									
									}
										
									echo '% </b>
										</br>
									</div>

									</div></div>
									 ' ;
							}
							else
							{
								echo '
								<form id = "form-book-book" action = "book-book.php" method = "post">
									<input id = "form-book-book-hide" type = "text" name = "bookid" value = '.$query_execute[ 'bookid' ].'>
									<input id = "form-book-book-submit" type = "submit" value = "Book It">
								</form>
								
								<div id = "book-rating">
									Book Rating: <b>' ;
									
								// Calculating book rating	
								$num_issued = $query_execute[ 'notimesissued' ] ;	
								
								// Query 3 : Getting total of books issued 
								$q3_tot = "SELECT SUM(`notimesissued`) FROM `bookdb` WHERE 1" ;
								
								if( $q3_run = mysqli_query( $conn, $q3_tot ) )
								{
									$sum_result = mysqli_fetch_row( $q3_run ) ;
									$issued_total = $sum_result[ 0 ] ;
									
									if( $issued_total < 1 ) 
									{
										$rating = 0 ;
									}
									else
									{
										$rating = ( $num_issued / $issued_total ) * 100 ;
										$rating = ( int ) $rating ;
									}
									
									echo $rating ;
								}
								else
								{
									$ERR_CODE = 3 ;
									echo 'NOT AVAL' ;									
								}
										
								echo '% </b>
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

<script>

	function ShowMenu()
	{
		document.getElementById( "main-menu").style.display = "inline-block" ;
		
		document.getElementById( "show-menu" ).style.display = "none" ;
		document.getElementById( "hide-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "hide-reviewbox" ).style.display = "none" ;
		document.getElementById( "show-reviewbox" ).style.display = "inline-block" ;
		document.getElementById( "reviewbox" ).style.display = "none" ;
		
		document.getElementById( "book-left-col" ).style.marginTop = "300px" ;
	}
	function HideMenu()
	{
		document.getElementById( "main-menu").style.display = "none" ;
		
		document.getElementById( "show-menu" ).style.display = "inline-block" ;
		document.getElementById( "hide-menu" ).style.display = "none" ;		
		
		document.getElementById( "book-left-col" ).style.marginTop = "230px" ;
	}
	
	function ShowReviewbox()
	{
		document.getElementById( "main-menu" ).style.display = "none" ;
		document.getElementById( "reviewbox" ).style.display = "inline-block" ;
		
		document.getElementById( "show-reviewbox" ).style.display = "none" ;
		document.getElementById( "hide-reviewbox" ).style.display = "inline-block" ;
		
		document.getElementById( "hide-menu" ).style.display = "none" ;
		document.getElementById( "show-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "book-left-col" ).style.marginTop = "350px" ;
	}
	function HideReviewbox()
	{
		document.getElementById( "reviewbox").style.display = "none" ;
		
		document.getElementById( "show-reviewbox" ).style.display = "inline-block" ;
		document.getElementById( "hide-reviewbox" ).style.display = "none" ;	

		document.getElementById( "book-left-col" ).style.marginTop = "230px" ;
	}

</script>
