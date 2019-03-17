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
		// Getting POSTED values
		$stampno = $_POST[ 'stampno' ] ;
		$regno = $_POST[ 'regno' ] ;
		$year = $_POST[ 'year' ] ;
	
		$allow_collect = 1 ;
	
		// Date
		date_default_timezone_set( 'Asia/Kolkata' ) ;
		$curr_date = date( 'Y/m/d h:i:s a', time() ) ;
		echo $curr_date."</br>" ;
		
		$ERR_CODE = 0 ;
		$copies = -99 ;
		
		$bookid = 0 ;
		$curr_isrno = 0 ;
		
		$task0 = 0 ;
		$task1 = 0 ;		
		$task2 = 0 ;
		$task3 = 0 ;
		
		// Query 0 : Get bookid from stampno
		
		$q0_getid = "SELECT * FROM `issuedbooks` WHERE `stampnumber` = '".$stampno."' AND `regno` = '".$regno."' AND `returned` = 0" ;		
		
		if( $q0_run = mysqli_query( $conn, $q0_getid ) )
		{
			$q0_exe = mysqli_fetch_assoc( $q0_run ) ;
			$bookid = $q0_exe[ 'bookid' ] ;
			$curr_isrno = $q0_exe[ 'isrno' ] ;
			$task0 = 1 ;
		}
		else
		{
			$ERR_CODE = 5 ;
			echo ".".$ERR_CODE ;
			// Query for getting bookid by matching stampno in issuedbooks is not working
		}
		
		// Query 1 : Add returndate and returned = 1 to issuedbooks table 
		
		$q1_issdone = "UPDATE `issuedbooks` SET `returndate` = '".$curr_date."', `returned` = '1' WHERE `isrno` = '".$curr_isrno."' ;" ;
		
		if( $q1_run = mysqli_query( $conn, $q1_issdone ) )
		{
			$task1 = 1 ;
		}
		else
		{
			$ERR_CODE = 1 ;
			echo ".".$ERR_CODE ;
			// Query for returning book in issuedbooks is not working
		}
		
		// Query2 : Decrement no. of issued books in studentuser_
		
		$q2_getrec = "SELECT `issuedbooksno` FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ;
		
		if( $q2_run = mysqli_query( $conn, $q2_getrec ) )
		{
			$q2_exe = mysqli_fetch_assoc( $q2_run ) ;
			$value1 = $q2_exe[ 'issuedbooksno' ] ;
			
			$value1 = $value1 - 1 ;
			
			$q2_dec = "UPDATE `studentuser".$year."` SET `issuedbooksno` = '".$value1."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ;

			if( $q2_run2 = mysqli_query( $conn, $q2_dec ) ) 
			{
				$task2 = 1 ;
			}
			else
			{
				$ERR_CODE = 3 ;
				echo ".".$ERR_CODE ;
				// Query for decrementing issued books in studentuser_ is not working
			}
		}
		else
		{
			$ERR_CODE = 2 ;
			echo ".".$ERR_CODE ;
			// Query for getting number of issued books from studentuser_ is not working
		}
		
		// Query 3 : Increment number of books in bookdb
		
		$q3_getcop = "SELECT `totalcopies` FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."' ; " ;
		
		if( $q3_run = mysqli_query( $conn, $q3_getcop ) )
		{
			$q3_exe = mysqli_fetch_assoc( $q3_run ) ;
			
			$value1 = $q3_exe[ 'totalcopies' ] ;
			$value1 = $value1 + 1 ;
			
			$copies = $value1 ;
			
			$q3_inc = "UPDATE `bookdb` SET `totalcopies` = '".$value1."' WHERE `bookdb`.`bookid` = '".$bookid."' ; " ; 
			
			if( $q3_run2 = mysqli_query( $conn, $q3_inc ) )
			{
				$task3 = 1 ; 
			}
			else
			{
				$ERR_CODE = 5 ;
				echo ".".$ERR_CODE ;
				// Query for incrementing totalcopies is not working
			}
		}
		else
		{
			$ERR_CODE = 4 ;
			echo ".".$ERR_CODE ;
			// Query for getting totalcopies in bookdb is not working
		}
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-collect2-confirm.css'>
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
									 Collect Summary Window
								</div>
								</br></br>
								
								<div>
			" ;

			if( $task0 == 0 )
			 echo "<div id = 'error'> FAIL0 </div> " ;
			else
			 echo "<div id = 'success'> DONE0 </div>" ;
			
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
								Collect Summary Window
							</div>
						</div>
				
					 " ;
				
				echo '
						<div id = "record">							
							<div id = "book-record"> 
							
								<strong> Updated Book Details </strong> 
									
								<hr>
								</br>
								Book ID : '.$bookid.'
								</br>
								Left: '.$copies.'
								</br>
								Acc. No: '.$stampno.'
								
								<br></br>
								<hr>

								<strong> Submitted By: </strong>
								</br></br>
								Registration No: '.$regno.'
								
								
								</br></br>
								<strong> Date Return: '.$curr_date.'
								
								</br></br>
								
								<div id = "book-record"> 
											
								<form action = "admin-collect1.php" method = "POST">
									<input class = "hidden" type = "text" name = "window" value = "collect">	
									<input id = "form-book-book-submit" type = "submit" value = "Done">
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
