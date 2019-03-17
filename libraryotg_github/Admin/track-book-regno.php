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

	$year = 2997 ;	
	
		// Getting POSTED data
		$regno = $_POST[ 'regno' ] ;
		//$year = $_POST[ 'year' ] ; Now it is generated from REGNO
		
	// Generating YEAR from REGNO
	// Departments : C-Comp. Science, E-Electronics, P-Physics
		if( $regno[ 0 ] == 'F' && ( $regno[ 1 ] == 'C' || $regno[ 1 ] == 'P' || $regno[ 1 ] == 'E' ) )
		{
			//echo "\n Faculty account : Year : 2016" ;
			$year = 2016 ;
		}
		else if ( $regno[ 0 ] == 'B' && $regno[ 1 ] == 'T' )
		{
			if( $regno[ 2 ] == '1' ) 
			{
				if( $regno[ 3 ] == 6 )
				{
					//echo "\n Year: 2016" ;
					$year = 2016 ;
				}
				else if( $regno[ 3 ] == 7 )
				{
					//echo "\n Year: 2017" ;
					$year = 2017 ;
				}
			
				else
				{
					//echo "\n Year: Unknown" ;
					$year = 2998 ;
				}
			}
		}
		else
			$year = 2999 ;	// Invalid input
	

		$ERR_CODE = 0 ;
		$zero_output = 0 ;
		
		$rec_name = "NULL" ;
		$rec_dep = "NULL" ;
		$rec_reqs = 0 ;
		$rec_iss = 0 ;
		$rec_contactno = 0 ;
		
		$task0 = 0 ;
		$task1 = 0 ;

		echo "</br>" ;
		
		// Query0
		
		$q0_getinfo = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ;
		
		if( $q0_run = mysqli_query( $conn, $q0_getinfo ) )
		{
			$q0_exe = mysqli_fetch_assoc( $q0_run ) ;
			
			$rec_name = $q0_exe[ 'name' ] ;
			$rec_dep = $q0_exe[ 'department' ] ;
			$rec_reqs = $q0_exe[ 'bookrequests' ] ;
			$rec_iss = $q0_exe[ 'issuedbooksno' ] ;
			$rec_contactno = $q0_exe[ 'contactno' ] ;
				
			$task0 = 1 ;
		}
		else
		{
			$ERR_CODE = 3 ;
			echo ".".$ERR_CODE ;
			// Query for getting info about Registration No. is not working
		}
		
		// Query1 : Get records of input Registration number
		
		$q1_getno = "SELECT * FROM `issuedbooks` WHERE `issuedbooks`.`regno` = '".$regno."' ; " ;
		
		$q1_run = mysqli_query( $conn, $q1_getno ) ;
		$zero_output =  mysqli_num_rows( $q1_run ) ;
		
		if( $zero_output > 0 )
		{		
			$q1_getrec = "SELECT * FROM `issuedbooks` WHERE `issuedbooks`.`regno` = '".$regno."' ; " ;
			
			if( $q1_run = mysqli_query( $conn, $q1_getrec ) )
			{
				/*$q1_exe = mysqli_fetch_assoc( $q1_run ) ;*/
				
				$task1 = 1 ;
			}
			else
			{
				$ERR_CODE = 1 ;
				echo ".".$ERR_CODE ;
				// Query for getting matched Registration No. records is not working
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
						<link rel = 'stylesheet' type = 'text/css' href = 'track-book-regno.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '800;url=http://192.168.77.4/libraryotg/destroy-session.php'>
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
									Track Registration No. Output
								</div>
								</br></br>
								
								<div>
									
								</div>
								
			" ;
	
			if( $task0 == 0 )
			 echo "<div id = 'error'> FAIL0 </div> " ;
			else
			 echo "<div id = 'success'> DONE0 </div> " ;
			
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
								Track Registration No. Output
							</div>
						</div>
				
					 " ;
				
				echo '
							<div id = "white-page">
								
									Reg. No: <div id = "book-record"><strong> '.$regno.' </strong> </div>
									</br>
									
									Name: <div id = "book-record"> <strong> '.$rec_name.' </strong></div>
									</br>
									Year of Admission: <div id = "book-record">'.$year.' </div>
									</br>
									Department: <div id = "book-record"> '.$rec_dep.' </div>
									</br>
									Contact No.: <div id = "book-record"> '.$rec_contactno.' </div>
								
									</br>
									</br>
								
									Book Requests: <div id = "book-record">'.$rec_reqs.' </div>
									</br>
								
							</div>
						' ;
					
				
				if( $zero_output > 0 )
				{
						
					if( $rec_name == NULL ) 
							echo "<div id = 'book-record'> Mismatch Input </div>" ;
						
					echo '
									
														
							<div id = "record-fields">
								<div id = "plate-title"> 
									Issued Books: '.$rec_iss.'
								</div>
								</br>
								<hr>
								</br>
								<table>
								 <tr>
									<td id = "record-data"> <strong> Book Image </strong> </td>
									<td id = "record-data"> <strong> Book ID </strong> </td>
									<td id = "record-data"> <strong> Accession No. </strong> </td>
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
									<td id = "record-data">
										<img id = "image-icon" src = "http://192.168.77.4/libraryotg/Book-Images/book'.$q1_exe[ 'bookid' ].'.jpg">
									</td>
									
									<td id = "record-data"> '.$q1_exe[ 'bookid' ].' </td>
									<td id = "record-data"> '.$q1_exe[ 'stampnumber' ].' </td>
									<td id = "record-data"> ' ;
										if( $q1_exe[ 'returned'] == 1 )
											echo '<div id = "success"> RETURNED </div> ' ;
										else
											echo '<div id = "error"> NOT RETURNED </div> ' ;
						echo '
						</td>
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
								</br>
								<div id = "plate-title"> Of :'.$regno.' </div>
								</br>
								<div> 								
								 Of Year of Admission: '.$year.'
								</div>
							</div>
						' ;
					
				}
					
					
					
				echo "</div>" ;	
				

?>
