<?php
	require 'connect_libraryotg2.php' ;

	// Track Book Issue on date

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
	
		// Getting POSTED data
		$date = $_POST[ 'date' ] ;
		
		$ERR_CODE = 0 ;
		$zero_output = 0 ;
		
		$task1 = 0 ;

		echo "</br>" ;
		
		// Query1 : Get records of input date
		
		$q1_getno = "SELECT * FROM `issuedbooks` WHERE `issuedbooks`.`returndate` LIKE '".$date."%' ; " ;
		
		$q1_run = mysqli_query( $conn, $q1_getno ) ;
		$zero_output =  mysqli_num_rows( $q1_run ) ;
		
		if( $zero_output > 0 )
		{		
			$q1_getrec = "SELECT * FROM `issuedbooks` WHERE `issuedbooks`.`returndate` LIKE '".$date."%' ; " ;
			
			if( $q1_run = mysqli_query( $conn, $q1_getrec ) )
			{
				/*$q1_exe = mysqli_fetch_assoc( $q1_run ) ;*/
				
				$task1 = 1 ;
			}
			else
			{
				$ERR_CODE = 1 ;
				echo ".".$ERR_CODE ;
				// Query for getting matched date records is not working
			}
		}
		else
		{
			$ERR_CODE = 2 ;
			echo ".".$ERR_CODE ;
			// Zero outputs
		}
	
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'track-book-return.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
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
								
								<form action = 'admin-home.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'home'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Go Back'>
								</form>								
								
								<form action = 'maint-suggest.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'home'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Suggest to Maintenance'>
								</form>								
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									Track Return Date Output
								</div>
								</br></br>
								
								<div>
									
								</div>
								
			" ;
	
			if( $task1 == 0 )
			 echo "<div id = 'error'> FAIL1 </div> " ;
			else
			 echo "<div id = 'success'> DONE1 </div> " ;
			
			echo "
							</div>
							
						 
						</div>
						
					</body> 
				
				</html>
			 " ;			
			
			echo '<div id = "wrap">' ;
			
				echo " 
						<div id = 'title-bar'>
							<div id = 'title-bar-text'>
								Track Return Date Output
							</div>
						</div>
				
					 " ;
				
				if( $zero_output > 0 )
				{
					echo '
														
							<div id = "record-fields">
								<div id = "plate-title"> On: '.$date.' --Count: '.$zero_output.' </div>
								</br>
								<hr>
								</br>
								<table>
								 <tr>
									<td id = "record-data"> <strong> Book ID </strong> </td>
									<td id = "record-data"> <strong> Accession No. </strong> </td>
									<td id = "record-data"> <strong> Registration No.</strong></td>
									<td id = "record-data"> <strong> Returned Status </strong> </td>
								 </tr>
								</table>
							
							</div>								
								
						 ' ;
					
					
					echo ' <div id = "record-data-wrap"> <table> ' ;
					while( $q1_exe = mysqli_fetch_assoc( $q1_run ) )
					{
						echo '
								<tr>
									<td id = "record-data"> '.$q1_exe[ 'bookid' ].' </td>
									<td id = "record-data"> '.$q1_exe[ 'stampnumber' ].' </td>
								
									<td id = "record-data">
										<form id = "form-view-book" action = "track-book-regno.php" method = "post" title = "View this surfer">
										<input class = "hidden" type = "text" name = "regno" value = "'.$q1_exe[ 'regno'].'">
										<input class = "hidden" type = "text" name = "year" value = "'.$q1_exe[ 'year'].'">
										<input id = "form-view-book-glass" type = "submit" value = "'.$q1_exe[ 'regno' ].'">
										</form>
									</td>
									
									<td id = "record-data"> ' ;
										if( $q1_exe[ 'returned'] == 1 )
											echo '<div id = "success"> RETURNED </div> ' ;
										else
											echo '<div id = "error"> NOT RETURNED </div> ' ;
						echo '</td>
								<tr>
							' ;
					}
					echo '</table> </div>' ;
				}
				else
				{
					echo '
							<div id = "record1">
								
								<div id = "book-record"><strong> 0 records found.</strong></div>
							
								<div id = "plate-title"> on date :'.$date.' </div>
								</br>
							
								<div id = "about"> 								
								
								</div>
							</div>
						' ;
					
				}
					
					
					
				echo "</div>" ;	
				
?>
