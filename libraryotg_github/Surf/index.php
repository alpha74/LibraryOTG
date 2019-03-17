<?php
	require 'connect_libraryotg2.php' ;

	session_start() ;
	
	if( isset( $_SESSION[ 'regno' ] ) )
	{
		echo "</br> SA" ;
		// Setting validity of session
	}
	
	else
	{
		echo "</br> Please login to continue!" ;
		echo"</br> Tresspassers will be terminated!" ;		
		die() ;
	}
		// Getting from session
		
		$regno = $_SESSION[ 'regno' ] ;
		$year = $_SESSION[ 'year' ] ;

		// POSTED value
		if( $_POST != NULL )
		 $query_filter = $_POST[ 'filter' ] ;
		else
		 $query_filter = 'misc' ;
		
		// To prevent SQL injection
		$query_filter = stripcslashes( $query_filter ) ;
		
		$query_filter = mysqli_real_escape_string( $conn, $query_filter ) ;
		
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
		
		$count_num = 0 ;
		
		$all = "all" ;
		if( $query_filter == $all )
		{
			$query = "SELECT * FROM `bookdb`" ;
			$q2_getno = "SELECT COUNT(*) FROM `bookdb`" ;
			$filter_value = "All" ;
		}
		else
		{
		
			switch( $query_filter )
			{								
				case "maths": 	$filter_value = "Maths" ;
								
								break ;

				case "c/c++": 	$filter_value = "C/C++" ;
								break ;
				
				case "physics":	$filter_value = "Physics" ;
								break ;
								
				case "microprocessor": $filter_value = "Microprocessor" ;	
								break ;
								
				case "mech/graphics": $filter_value = "Mech/Graphics" ;	
								break ;
								
				case "electrical": $filter_value = "Electrical" ;	
								break ;
							
				case "electronics": $filter_value = "Electronics" ;	
								break ;
								
				case "humanities": $filter_value = "Humanities" ;	
								break ;
								
				case "webdev": $filter_value = "WebDev" ;	
								break ;
				
				case "misc" : $filter_value = "Misc" ;
							  break ;
				default: $filter_value = "Misc" ;
			}
			
			$query = "SELECT * FROM `bookdb` WHERE `bookdb`.`booktag` = '".$filter_value."'" ;
			$q2_getno = "SELECT * FROM `bookdb` WHERE `bookdb`.`booktag` = '".$filter_value."'" ;
		}
		
		// Query2 : For getting number of books : $q2_getno
		
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
						<link rel = 'stylesheet' type = 'text/css' href = 'getdata.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
						<!-- Refresh -->
						<meta http-equiv = 'refresh' content = '120'>
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
									<form action = 'getdata-bsearch.php' method = 'POST' title = 'Search for author or book name'>
										<input id = 'input-search1' type = 'text' name = 'key' placeholder = 'Enter keyword' required>
										<input id = 'button-tag-upper-search' type = 'submit' value = 'Go'>
									</form>								
									
									<form action = 'surfer-change-pass.php' method = 'POST'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Change Password'> 
									</form>
									
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = '".$query_filter."'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Refresh'>
									</form>								
			
									<form action = 'surfer-suggest.php' method = 'POST'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Suggest' title = 'Suggest for better'>
									</form>
			
									<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
										<button id = 'button-tag-upper'> Sign Out </button>
									</a>
								</div>
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									Filter: <strong>".$filter_value."( ".$count_num." )
									<button id = 'show-filters' onclick = 'ShowFilters()'> Filters </button>
									<button id = 'hide-filters' onclick = 'HideFilters()'> Hide </button>
								</div>
								</br>
								
								<div id = 'filters-menu'>
									<!-- All -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'all'>
										<input id = 'button-tag' type = 'submit' value = 'All'>
									</form>								
									
									<!-- Maths -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'maths'>
										<input id = 'button-tag' type = 'submit' value = 'Maths'>
									</form>								
									
									<!-- C/C++ -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'c/c++'>
										<input id = 'button-tag' type = 'submit' value = 'C/C++'>
									</form>								
									
									<!-- Physics -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'physics'>
										<input id = 'button-tag' type = 'submit' value = 'Physics'>
									</form>								
									
									<!-- Microprocessor -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'microprocessor'>
										<input id = 'button-tag' type = 'submit' value = 'Comp.Org/Microprocessor'>
									</form>								
									
									<!-- Mech/Graphics -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'mech/graphics'>
										<input id = 'button-tag' type = 'submit' value = 'Mechanics/Graphics'>
									</form>								
									
									<!-- Electrical -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'electrical'>
										<input id = 'button-tag' type = 'submit' value = 'Electrical'>
									</form>								
									
									<!-- Electronics -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'electronics'>
										<input id = 'button-tag' type = 'submit' value = 'Electronics'>
									</form>								
									
									
									<!-- Humanities -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'humanities'>
										<input id = 'button-tag' type = 'submit' value = 'Humanities/EVS'>
									</form>								
									
									<!-- WebDev -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'webdev'>
										<input id = 'button-tag' type = 'submit' value = 'Web Dev'>
									</form>								
									
									<!-- Misc -->
									<form action = 'getdata.php' method = 'POST'>
										<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
										<input id = 'button-tag' type = 'submit' value = 'Misc'>
									</form>								
																
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
									<input id = "form-book-book-hide" type = "text" name = "year" value = '.$year.'>
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
			echo ">Query failed" ;
		}
	echo "	
			<a id = 'jump-top' href = '#box-left' onclick = 'scrollTop()'> Jump to Top </a>
			</body>
		  </html>" ;
?>

<script type = "text/javascript">

	function ShowMenu()
	{
		document.getElementById( "main-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "show-menu" ).style.display = "none" ;
		document.getElementById( "hide-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "wrap" ).style.marginTop = "-220px" ;
		
		document.getElementById( "filters-menu" ).style.display = "none" ;
		document.getElementById( "hide-filters" ).style.display = "none" ;
		document.getElementById( "show-filters" ).style.display = "inline-block" ;
	}
	function HideMenu()
	{
		document.getElementById( "main-menu" ).style.display = "none" ;
		
		document.getElementById( "hide-menu" ).style.display = "none" ;
		document.getElementById( "show-menu" ).style.display = "inline-block" ;	
		
		document.getElementById( "wrap" ).style.marginTop = "-420px" ;
	}
	
	function ShowFilters()
	{
		document.getElementById( "filters-menu" ).style.display = "inline-block" ;
		
		document.getElementById( "show-filters" ).style.display = "none" ;
		document.getElementById( "hide-filters" ).style.display = "inline-block" ;
		
		document.getElementById( "wrap" ).style.marginTop = "-90px" ;
		
		document.getElementById( "main-menu" ).style.display = "none" ;
		document.getElementById( "hide-menu" ).style.display = "none" ;
		document.getElementById( "show-menu" ).style.display = "inline-block" ;	
	}
	function HideFilters()
	{
		document.getElementById( "filters-menu" ).style.display = "none" ;
		
		document.getElementById( "hide-filters" ).style.display = "none" ;
		document.getElementById( "show-filters" ).style.display = "inline-block" ;
		
		document.getElementById( "wrap" ).style.marginTop = "-420px" ;
	}

</script>
