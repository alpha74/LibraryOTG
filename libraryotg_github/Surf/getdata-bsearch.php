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
		
		// POSTED value
		if( $_POST != NULL )
		 $key = $_POST[ 'key' ] ;
		else
		 $key = 'NULL' ;

		// To prevent SQL injection
		$key = stripcslashes( $key ) ;
		
		$key = mysqli_real_escape_string( $conn, $key ) ;
	 
		$count_num = 0 ;
		
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
		
		// Query2 : For getting number of books : $q2_getno
		$q2_getno = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookname` LIKE '%".$key."%' OR `bookdb`.`bookauthor` LIKE '%".$key."%' OR `bookid` LIKE '".$key."' ; " ;
		
		// Query1
		$query = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookname` LIKE '%".$key."%' OR `bookdb`.`bookauthor` LIKE '%".$key."%' OR `bookid` LIKE '".$key."' ; " ;	
				
		if( $q2_run = mysqli_query( $conn, $q2_getno ) )
		{
			$count_num = mysqli_num_rows( $q2_run ) ;
		}
		else
		{
			$ERR_CODE = 1 ;
			echo ".".$ERR_CODE ;
			// Query for counting number of books is not working
		}
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'getdata-bsearch.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
						<!-- Refresh -->
						<meta http-equiv = 'refresh' content = '240'>
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
									<form action = 'getdata-bsearch.php' method = 'POST' title = 'Search for author or bookname'>
										<input id = 'input-search1' type = 'text' name = 'key' placeholder = 'Enter keyword' required>
										<input id = 'button-tag-upper-search' type = 'submit' value = 'Go'>
									</form>								
									
									<form action = 'getdata-bsearch.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'key' value = '".$key."'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Refresh'>
									</form>								
			
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
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
									Search Key: <strong>".$key."(".$count_num.")
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
			
			echo '<div id = "wrap">' ;
			
			
			while( $query_execute = mysqli_fetch_assoc( $if_query_run ) )
			{
				echo '
						<div id = "record" title = "'.$query_execute[ 'bookid' ].'">
							<div id = "book-record"><strong>'.$query_execute[ 'bookname' ].'</strong></div>
						
							<div id = "author-record">'.$query_execute[ 'bookauthor' ].'</div>
						
							</br></br>					
							<center> 
								<img id = "book-image" src = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" alt = "Image unavailable">
							</center>
							
							</br>
						
							<div id = "book-left"> 
								<strong> Left: '.$query_execute[ 'totalcopies' ].' </strong>
								
								<form id = "form-view-book" action = "book-view.php" method = "post">
									<input id = "form-view-book-hide" type = "text" name = "bookid" value = '.$query_execute[ 'bookid' ].'>
									<input id = "form-view-book-submit" type = "submit" value = "View Book">
								</form>	

							' ;
							
							if( $query_execute[ 'totalcopies' ] <= 1 || $query_execute[ 'issuepermit' ] == 0 )
							{
								echo '
										<!--<button id = "button-inactive"> Book It </button>-->
										</div>
										</div>
										
									  ' ;
							}
							else
							{
								echo '
								<form id = "form-book-book" action = "book-book.php" method = "post">
									<input id = "form-book-book-hide" type = "text" name = "bookid" value = '.$query_execute[ 'bookid' ].'>
									<input id = "form-book-book-submit" type = "submit" value = "Book It">
								</form>
								
							</div>
						</div>
					' ; 
							}
			}
			
			echo "</div>" ;
			
		}
		else
		{
			echo ">Operation failed" ;
		}
	echo "	
			<a id = 'jump-top' href = '#box-left' onclick = 'scrollTop()'> Jump to Top </a>
			</body>
		  </html>" ;
	
?>

<script>

	function ShowMenu()
	{
		document.getElementById( "main-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "show-menu" ).style.display = "none" ;
		document.getElementById( "hide-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "wrap" ).style.marginTop = "-300px" ;
		
		document.getElementById( "filters-menu" ).style.display = "none" ;
		document.getElementById( "hide-filters" ).style.display = "none" ;
		document.getElementById( "show-filters" ).style.display = "inline-block" ;
	}
	function HideMenu()
	{
		document.getElementById( "main-menu" ).style.display = "none" ;
		
		document.getElementById( "hide-menu" ).style.display = "none" ;
		document.getElementById( "show-menu" ).style.display = "inline-block" ;	
		
		document.getElementById( "wrap" ).style.marginTop = "-430px" ;
	}
</script>
