<?php
	require 'connect_libraryotg2.php' ;

	// Surfer Book record : Different format from Admin

	session_start() ;
	
	if( isset( $_SESSION[ 'regno' ] ) )
	{
		echo '</br> SA' ;
	}
	else
	{
		echo '</br> Login to continue! ' ;
		echo '</br> Tresspassing not allowed!' ;
		die() ;
	}
	
	// Acquiring session
	$regno = $_SESSION[ 'regno' ] ;
	$year = $_SESSION[ 'year' ] ;
	

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
						<link rel = 'stylesheet' type = 'text/css' href = 'surfer-records.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>

						<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '300;url=http://192.168.77.4/libraryotg/destroy-session.php'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$regno."
								</div>
								
								</br>
								
								<form action = 'getdata.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Surf Home'>
								</form>							
								
								<form action = 'surfer-suggest.php' method = 'POST'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Suggest Edit'>
								</form>				

								<form action = 'surfer-change-pass.php' method = 'POST'>
										<input id = 'button-tag-upper' type = 'submit' value = 'Change Password'> 
								</form>				
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									
								</div>
								</br></br>
								
								<div>
									
								</div>
								
			" ;
	
			if( $task0 == 0 )
			 echo "<div id = 'error'> FAIL0 </div> " ;
			else
			{
				//echo "<div id = 'success'> DONE0 </div> " ;
				echo "" ;
			}
			
			if( $task1 == 0 )
			 echo "<div id = 'error'> FAIL1 </div> " ;
			else
			{
				//echo "<div id = 'success'> DONE1 </div> " ;
				echo "" ;
			}			

			echo "
							</div>
							
						 
						</div>
						
					</body> 
				
				</html>
			 " ;			
			
			echo '<div id = "wrap">' ;
				//echo "  " ; Title bar was here
				
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
								<table id = "heading-table">
								 <tr id = "record-row">
									<td id = "record-data"> <strong> Cover Image </strong> </td>
									<!--<td id = "record-data"> <strong> Book ID </strong> </td>-->
									<!--<td id = "record-data"> <strong> Accession No. </strong> </td>-->
									<td id = "record-data"> <strong> Issue Date </strong> </td>
									<td id = "record-data"> <strong> Status </strong> </td>
								 </tr>
								</table>
							
							</div>								
								
						 ' ;
					
					
					echo ' <div id = "record-data-wrap"> <table> ' ;
					while( $q1_exe = mysqli_fetch_assoc( $q1_run ) )
					{
						echo '
								<tr id = "record-row">
									<td id = "record-data">
										<img id = "image-icon" src = "http://192.168.77.4/libraryotg/Book-Images/book'.$q1_exe[ 'bookid' ].'.jpg">
									</td>
									
									<!--<td id = "record-data"> '.$q1_exe[ 'bookid' ].' </td> -->
									<!--<td id = "record-data"> '.$q1_exe[ 'stampnumber' ].' </td>-->
									<td id = "record-data"> '.$q1_exe[ 'dateissue' ].' </td>
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
								
							</div>
						' ;
					
				}
					
					
					
				echo "</div>" ;	
				

?>
