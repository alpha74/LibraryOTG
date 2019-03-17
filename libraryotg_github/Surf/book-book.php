
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
	{
		echo "</br> Cannot open page!" ;
		die() ;
	}
	
	// Prevent MySQL injection
	$bookid = stripcslashes( $bookid ) ;
		
	$bookid = mysqli_real_escape_string( $conn, $bookid ) ;
	
	$bookings = 0 ;
	$task1 = 0 ;
	$task2 = 0 ;
	$task3 = 0 ;
	$task4 = 0 ;
	$reviews_count = 0 ;
	
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
	
	// Query2 : For getting details of selected book : Used down
	$query = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."'  ; " ;
 
	if( $if_query_run = mysqli_query( $conn, $query ) ) 
	{
		$task2 = 1 ;
	}
	else
	{
		$ERR_CODE = 2 ;
		echo ".".$ERR_CODE ;
		// Query for getting book details is not working
	}
 
	// Query3 : Count number of reviews of this book
	$q3_revc = "SELECT * FROM `book-review` WHERE `book-review`.`bookid` = ".$bookid." ; " ; 
 
	if( $q3_run = mysqli_query( $conn, $q3_revc ) )
	{
		$reviews_count = mysqli_num_rows( $q3_run ) ;
		$task3 = 1 ;
	}
	else
	{
		$ERR_CODE = 3 ;
		echo ".".$ERR_CODE ;
		// Query for counting number of reviews is not working
	}
 
	if( $reviews_count > 0 )
	{
		$limit = 2 ; // Limit of displayed reviews
		// Query 4 : Loading last 2 reviews
		$q4_revs = "SELECT * FROM `book-review` WHERE `book-review`.`bookid` = '".$bookid."' ORDER BY `date` DESC LIMIT ".$limit." " ;
		//echo "</br>".$q4_revs ;
		//echo "</br>".$bookid ;
		
		if( $q4_run = mysqli_query( $conn, $q4_revs ) ) 
		{
			$task4 = 1 ;
		}
		else
		{
			$ERR_CODE = 5 ;
			echo ".".$ERR_CODE ;
			// Query for getting reviews is not working
		}
	}
	else
	{
		$ERR_CODE = 4 ;
		echo ".".$ERR_CODE ;
		// No output
	}
 
	echo "
			<html>
				<head> 
					<link rel = 'stylesheet' type = 'text/css' href = 'book-book.css'>
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
								Book Reviews:
								
								<button id = 'show-reviewbox' onclick = 'ShowReviewbox()'> See </button>
								<button id = 'hide-reviewbox' onclick = 'HideReviewbox()'> Hide </button>
								
								</br>
								<hr>
		
		" ;
		
		if( $task4 == 0 )
		{
			echo "<div id = 'normal-text'> No reviews yet </div>" ;
		}
		else
		{
			while( $q4_exe = mysqli_fetch_assoc( $q4_run ) )
			{
				echo " 
						<div id = 'normal-text'>
						    <strong>".$q4_exe[ 'generator'].": ". $q4_exe[ 'gen_name' ] ." </strong> 
							</br>
							".$q4_exe[ 'content' ]." 
						</div>
						<hr>
					 " ;
			}
		}
		
		echo "
							</div>
							</br>
							
							<div>
								
							</div>
							
						</div>
						
					 
					</div>
					
				</body> 
			
			</html>
		 " ;
	
	if( $if_query_run = mysqli_query( $conn, $query ) ) 
	{
		//echo ">Query Ran" ;		
			$query_execute = mysqli_fetch_assoc( $if_query_run ) ;
		
			if( $query_execute[ 'bookid' ] == $bookid )
			{
				$bookname = $query_execute[ 'bookname' ] ;
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
								</br>
								
								<strong> Do you really want this book? </strong>
								
								</br></br>						
								
								<div id = "book-confirm">
									<form id = "form-book-confirm" action = "book-requested.php" method = "post">
										<input id = "form-book-confirm-hide" type = "text" name = "bookid" value = '.$bookid.'>
										<input id = "form-book-confirm-hide" type = "text" name = "bookname" value = '.$bookname.'>			
										<input id = "form-book-confirm-submit" type = "submit" value = "Yes">
									</form>
								</div>
								
								<div id = "book-confirm">	
									<form id = "form-book-confirm" action = "getdata.php" method = "post">	
										<input class = "hidden" type = "text" name = "filter" value = "all">
										<input id = "action_button1" type = "submit" value = "No">
									</form>									
								</div>
										
							</div>	
							
							<div id = "book-right-col">
															
								</br>		
								<img id = "book-image" src = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" alt = "Image unavailable">
								
								</br></br>
																
								<div id = "book-rating">
									Book Rating Details: 0
									</br>
								</div>	
								
															
							</div>
							
						</div>
					' ; 
						
			}	
	}
	else
	{
		echo "</br> Request failed internally" ;
		$ERR_CODE = 6 ;
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
		document.getElementById( "normal-text" ).style.display = "none" ;
		
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
		document.getElementById( "normal-text" ).style.display = "inline-block" ;
		
		document.getElementById( "show-reviewbox" ).style.display = "none" ;
		document.getElementById( "hide-reviewbox" ).style.display = "inline-block" ;
		
		document.getElementById( "hide-menu" ).style.display = "none" ;
		document.getElementById( "show-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "book-left-col" ).style.marginTop = "350px" ;
	}
	function HideReviewbox()
	{
		document.getElementById( "normal-text").style.display = "none" ;
		
		document.getElementById( "show-reviewbox" ).style.display = "inline-block" ;
		document.getElementById( "hide-reviewbox" ).style.display = "none" ;	

		document.getElementById( "book-left-col" ).style.marginTop = "230px" ;
	}

</script>
