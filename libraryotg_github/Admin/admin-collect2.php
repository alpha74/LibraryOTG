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
	
		// Getting POSTED data
		$stampno = $_POST[ 'stampno' ] ;
		$regno = $_POST[ 'regno' ] ;
	
		$allow_collect = 0 ;
	
		// Date
		date_default_timezone_set( 'Asia/Kolkata' ) ;
		$curr_date = date( 'Y/m/d h:i:s a', time() ) ;
		echo $curr_date."</br>" ;
				
		$ERR_CODE = 0 ;		

		$flag_found_match = 0 ;
		
		$tempregno = "NULL" ;
		$name = "NULL" ;
		$isrno = 0 ;
		$year = 0 ;
		$bookid = 0 ;		
		$dateissue = 0 ;
		
		$days = 0 ;
		$fine = 0 ;
		
		$task1 = 0 ;		
		$task2 = 0 ;
		$task3 = 0 ;
		
		// Query 1 : Match stampno in issuedbooks and display info
		
		$q1_getiss = "SELECT * FROM `issuedbooks` WHERE `issuedbooks`.`stampnumber` = ".$stampno." ; " ;
		
		if( $q1_run = mysqli_query( $conn, $q1_getiss ) )
		{
			while( $q1_exe = mysqli_fetch_assoc( $q1_run ) )
			{
				$tempregno = $q1_exe[ 'regno' ] ;
				
				if( $tempregno == $regno )
				{
					$flag_found_match = 1 ;
					
					$isrno = $q1_exe[ 'isrno' ] ;
					$year = $q1_exe[ 'year' ] ;
					$bookid = $q1_exe[ 'bookid' ] ;
					$dateissue = $q1_exe[ 'dateissue' ] ;
				
					$task1 = 1 ;
				}
		
			}
			if( $flag_found_match == 1 ) // All details match and we can go for collection
			{
				$allow_collect = 1 ;
			
				// Query2 : Getting name from regno
				
				$q2_getname = "SELECT `name` FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' "; 
				
				if( $q2_run = mysqli_query( $conn, $q2_getname ) ) 
				{
					$q2_exe = mysqli_fetch_assoc( $q2_run ) ;
					
					$name = $q2_exe[ 'name' ] ;
					$task2 = 1 ;
				}
				else
				{
					$ERR_CODE = 3 ;
					echo ".".$ERR_CODE ;
					// Query to matched name is not working
				}
			}
			else
			{
				$ERR_CODE = 2 ;
				echo ".".$ERR_CODE ;
				// Input and record's regno do not match
			}
		}
		else
		{
			$ERR_CODE = 1 ;
			echo ".".$ERR_CODE ;
			// Query for matching stamp number is not working
		}		
		
		// Query 3 : Getting difference in date
		$q3_date = "SELECT DATEDIFF( '".$curr_date."','".$dateissue."' ) FROM `issuedbooks` ;" ;
	
		if( $task2 == 1 )
		{
			if( $q3_run = mysqli_query( $conn, $q3_date ) )
			{
				$diff_result = mysqli_fetch_row( $q3_run ) ;
				$days = $diff_result[ 0 ] ;
				
				$interval = 8 ;
				
				$task3 = 1 ;
				
				// Calculating fine amount
				$fine = ( floor( $days / $interval ) ) * 10 ;
			}
			else
			{
				$ERR_CODE = 4 ;
				echo ".".$ERR_CODE ;
				//  Query for getting number of days is not working or Query2 is not running
			}
		}
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-collect2.css'>
						<link rel = 'icon' href = 'home_page_images/web_icon2.png'>
						<!-- Timeout -->
						<meta http-equiv = 'refresh' content = '600'>
					</head>
				
					<body>
						
						<div id = 'box-left'>
							
							<div id = 'box-left1'>
								
								<strong> Library OTG </strong>
								
								<div id = 'small-text1'>
									Logged in as:".$username."
								</div>
								
								</br>
								
								<form action = 'admin-collect1.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'collect'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Go Back'>
								</form>								
								
								<form action = 'maint-suggest.php' method = 'POST'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Suggest to Maintenance'>
								</form>							
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									 Collect Confirm Window
								</div>
								</br></br>
								
								<div>
			" ;

			if( $task1 == 0 )
			 echo "<div id = 'error'> FAIL1 </div> " ;
			else
			 echo "<div id = 'success'> DONE1 </div>" ;
		
			if( $task2 == 0 )
			 echo "<div id = 'error'> FAIL2 </div> " ;
			else
			 echo "<div id = 'success'> DONE2 </div>" ;
		 
			
			if( $task3 == 0 )
			 echo "<div id = 'error'> FAIL3 </div> " ;
			else
			 echo "<div id = 'success'> DONE3 </div>" ;
		
			echo "						
								</div>
							</div>
						</div>
						
					</body> 
				
				</html>
			 " ;
		
		
			
			echo '<div id = "wrap">' ;
			
			
			
				echo " 
						<div id = 'title-bar'>
							<div id = 'title-bar-text'>
								Collect Confirm Window
							</div>
						</div>
				
					 " ;
				
				echo '
						<div id = "record">							
							<div id = "book-record"> 
							
								<strong> Issue Record </strong> 
									
								<hr>
								</br>
								<u>Surfer Details: </u>
								</br>
								Name :<strong> '.$name.' </strong>
								</br>
								Reg. No: '.$tempregno.'
								
								<br></br>
								<hr>
								</br>
								<u>Book Details: </u>
								</br>
								Book ID : '.$bookid.' 
								</br>
								Accession No: '.$stampno.'
								
								</br></br>
								<strong> Date Issue: '.$dateissue.' </strong>
								</br>
								Days Issued: '.$days.' 
								<div id = "error"> Fine Amount : <strong> Rs.'.$fine.'/-</strong></div>
								
								
								</br>
								
								<div id = "book-record"> 
					' ;
					
				if( $allow_collect == 1 )
				{
					echo '
								<form id = "form-book-book" action = "admin-collect2-confirm.php" method = "post">									
									<input class = "hidden" type = "text" name = "regno" value = '.$regno.'>
									<input class = "hidden" type = "text" name = "year" value = '.$year.'>
																
									<input class = "hidden" type = "text" name = "bookid" value = '.$bookid.'>
									<input class = "hidden" type = "text" name = "stampno"  value = '.$stampno.'>
									<input id = "form-book-book-submit" type = "submit" value = "Collect">
								</form> 
						' ;
				}
				else
				{
					echo '
							<div id = "error"> <strong> Mismatching information( Acc.No. and Reg.No) </strong></div>
						 ' ;
				}
				
				echo '
								<form action = "admin-collect1.php" method = "POST">
									<input class = "hidden" type = "text" name = "window" value = "collect">	
									<input id = "form-book-reject-submit" type = "submit" value = "Cancel">
								</form>																				
							</div>
							
							</br>
						
						</div>
					 
									
							</div>
							
							</br>
						
						</div>
					' ; 
			
			
			
			echo "</div>" ;
	
?>
