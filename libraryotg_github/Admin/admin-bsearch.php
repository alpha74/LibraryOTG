<?php
	require 'connect_libraryotg2.php' ;

	session_start() ;
	
	if( isset( $_SESSION[ 'username' ] ) )
	{
		echo '</br> SSA' ;
	}
	
	else
	{
		echo 'Please login to continue!' ;
		echo "</br></br> Tresspassing not allowed" ;
		die() ;
	}		
		// Acquiring session
		$username = $_SESSION[ 'username' ] ;
		
		// POSTED value
		if( $_POST != NULL )
		{
			$key = $_POST[ 'key' ] ;
			// Handling Space Input
			if( $key == ' ' )
			 $key = '_INVALID_' ;
		}
		else
		 $key = 'NULL' ;

		// To prevent SQL injection
		$key = stripcslashes( $key ) ;
		
		$key = mysqli_real_escape_string( $conn, $key ) ;
	 
		$count_num = 0 ;
		
		// Query : To check if the surfer is valid
		$qusercheck = "SELECT * FROM `adminuser` WHERE `adminuser`.`username` = '".$username."';" ;
		
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
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-bsearch.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<meta name = 'viewport' content = 'width=device, initial-scale = 1.0'>
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
									Logged in as:".$username."
								</div>
								
								</br>
								
								<div id = 'main-menu'>
									<form action = 'admin-bsearch.php' method = 'POST' title = 'Search for author or bookname: MAX: 30'>
										<input id = 'input-search1' type = 'text' name = 'key' placeholder = 'Enter keyword' maxlength : 30 required>
										<input id = 'button-tag-upper-search' type = 'submit' value = 'Go'>
									</form>								
									
									<form action = 'admin-bsearch.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'key' value = '".$key."'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Refresh'>
									</form>								
									
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
						<div id = "record">
							'.$query_execute[ 'bookid' ].'
							<div id = "book-record"><strong>'.$query_execute[ 'bookname' ].'</strong></div>
						
							<div id = "author-record">'.$query_execute[ 'bookauthor' ].'</div>
						
							</br></br>					
							<center> 
								<img id = "book-image" src = "http://192.168.77.4/libraryotg/'.$query_execute[ 'bookimage' ].'" alt = "Image unavailable">
							</center>
							
							</br>
						
							<div id = "book-left"> 
								<strong> Left: '.$query_execute[ 'totalcopies' ].' </strong>
								
								<form id = "form-view-book" action = "admin-book-view.php" method = "post">
									<input id = "form-view-book-hide" type = "text" name = "bookid" value = '.$query_execute[ 'bookid' ].'>
									<input id = "form-view-book-submit" type = "submit" value = "View Book">
								</form>	
							
							' ;
							
							
								echo '
							</div>
						</div>
					' ; 
							
			}
			
			echo "</div>" ;
			
		}
		else
		{
			echo ">Operation failed" ;
		}
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
