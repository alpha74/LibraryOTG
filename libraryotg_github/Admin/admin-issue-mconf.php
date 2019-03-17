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
	
	$year = 2997 ;

		// Getting POSTED data
		$regno = $_POST[ 'regno' ] ;
		//$year = $_POST[ 'year' ] ; Now it is generated from REGNO
		$bookid = $_POST[ 'bookid' ] ;
		$stampno = $_POST[ 'stampno' ] ;


	// Generating YEAR from REGNO
		if( $regno[ 0 ] == 'F' && $regno[ 1 ] == 'C' )
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
			$year = 2999 ; // Invalid input
	

		$allow_issue = 1 ;
		
		$ERR_CODE = 0 ;
		
		$task1 = 0 ;
		$task2 = 0 ;
		$task3 = 0 ;
		$task4 = 0 ;
		$surfername = 'NULL' ;
		
		// Date
		date_default_timezone_set( 'Asia/Kolkata' ) ;
		$curr_date = date( 'Y/m/d', time() ) ;
		echo $curr_date ;
		
		// Query 1 : To make new request in bookrequest table
		// No double checking : Do by checking count before and after
		
		$q1_ins = "INSERT INTO `bookrequest` (`srno`,`regno`, `year`, `bookid`, `date`, `issuestatus`) VALUES (' ','".$regno."', '".$year."', '".$bookid."', '".$curr_date."', '0') ;" ;
		
		if( $q1_run = mysqli_query( $conn, $q1_ins ) ) 
		{
			$q1_getsrno = "SELECT `srno` FROM `bookrequest` WHERE `bookrequest`.`regno` = '".$regno."' AND `bookrequest`.`issuestatus` = '0' ; " ;
			
			//echo "</br>".$q1_getsrno ;
		
			if( $q1_run2 = mysqli_query( $conn, $q1_getsrno ) )
			{
				$q1_exe = mysqli_fetch_assoc( $q1_run2 ) ;
				
				$srno = $q1_exe[ 'srno' ] ;
				//echo "</br> SRNO: ".$srno ;
				$task1 = 1 ;
			}
			else
			{
				$ERR_CODE = 2 ;
				echo ".".$ERR_CODE ;
				// Query for getting srno is not running
			}
		}
		else
		{
			$ERR_CODE = 1 ;
			echo ".".$ERR_CODE ;
			// Query for inserting into bookrequests is not working
		}
		
		// Query2 : To get surfername from studentuser_		
		
		$q2_getsurfer = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' " ;
		//echo "</br>".$q2_getsurfer ;		
		
		if( $q2_run = mysqli_query( $conn, $q2_getsurfer ) ) 
		{
			$q2_exe = mysqli_fetch_assoc( $q2_run ) ;
			$surfername = $q2_exe[ 'name' ] ;
					
			$value1 = $q2_exe[ 'bookrequests' ] ;
			
			//echo "</br> BR : ".$value1 ;
			//echo "</br> I : ".$q2_exe[ 'bookrequests' ] ;
			//echo "</br> I : ".$q2_exe[ 'issuedbooksno' ] ;
			
			if( $q2_exe[ 'issuedbooksno' ] > 1 || $value1 > 1 )
			{
				$ERR_CODE = 5 ;				
				$allow_issue = 0 ;
				echo ".".$ERR_CODE ;
				// Surfer is trying to request/ issue more books than maximum ;
			}
			else
			{
				$value2 = $value1 + 1 ;
				
				// Query for incrementing book requests
				
				$q2_bookreq = "UPDATE `studentuser".$year."` SET `bookrequests` = '".$value2."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ;" ; 
				//echo "</br>".$q2_bookreq ;
				
				if( $q2_run2 = mysqli_query( $conn, $q2_bookreq ) )
				{
					$task2 = 1 ;
				}
				else
				{
					$ERR_CODE = 4 ;
					echo ".".$ERR_CODE ;
					// Query for incrementing bookrequests is not running
				}
			}
		}
		else
		{
			$allow_issue = 0 ;
			$ERR_CODE = 3 ;
			echo ".".$ERR_CODE ;
			// Query for matching regno with studentuser_ is not working
		}
		
		// Query3 : For checking if quantity of bookid is > 0
		
		$q3_qt = "SELECT `totalcopies` FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."' " ;
		
		if( $q3_run = mysqli_query( $conn, $q3_qt ) )
		{
			$q3_exe = mysqli_fetch_assoc( $q3_run ) ;
			$copies = $q3_exe[ 'totalcopies' ] ;
			
			$task3 = 1 ;
			
			if( $copies < 1 )
			 $allow_issue = 2 ;
		}
		else
		{
			$ERR_CODE = 6 ;
			echo ".".$ERR_CODE ;
			// Query for reading totalcopies from bookdb is not working
		}
		
		
		// Query 4 : To authenticate the regno
		$q4_auth = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ;
		
		if( $q4_run = mysqli_query( $conn, $q4_auth ) )
		{
			$auth = mysqli_num_rows( $q4_run ) ;
			
			//echo "</br> C : ".$allow_issue ;
			
			if( $auth == 1 )
			{
				if( $allow_issue < 1 || $allow_issue > 1 )
					$allow_issue = 0 ;
				else
					$allow_issue = 1 ;
			}
			else
				$allow_issue = 0 ;
			
			$task4 = 1 ;
		}
		else
		{
			$ERR_CODE = 7 ;
			echo ".".$ERR_CODE ;
			// Query for authenticating correct regno is not working
		}
		
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-issue-mconf.css'>
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
								
								<form action = 'admin-issue.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'issue'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Go Back'>
								</form>								
								
								<form action = '.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'window' value = 'issue'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Suggest to Maintenance'>
								</form>								
								
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
							
							</br>
							<div id = 'box-left2'>
								
								<div id = 'box-left2-text'>
									Manual Issue Confirmation
									</br> <strong> Do not Refresh </strong>
								</div>
								</br></br>
								
								<div>
			" ;
									
		if( $task1 == 0 )
		 echo "<div id = 'error'> FAIL1 </div> " ;
		else
		 echo "<div id = 'success'> DONE1 </div> " ;
		
		if( $task2 == 0 )
		 echo "<div id = 'error'> FAIL2 </div> " ;
		else
		 echo "<div id = 'success'> DONE2 </div> " ;
	 
		if( $task3 == 0 )
		 echo "<div id = 'error'> FAIL3 </div> " ;
		else
		 echo "<div id = 'success'> DONE3 </div> " ;
	 
		if( $task4 == 0 )
		 echo "<div id = 'error'> FAIL4 </div> " ;
		else
		 echo "<div id = 'success'> DONE4 </div> " ;
	 
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
								Manual Issue Confirmation
							</div>
						</div>
				
					 " ;
				
				echo '
						<div id = "record">
													
							</br></br>					
							<strong> Student Details: </strong> </br></br>
							Reg. No: '.$regno.'
							</br>
					' ;
						
					if( $surfername == NULL && $allow_issue == 1 )
					{
						$surfername = "<div id = 'error'> MISMATCH Reg.No. and Admission Year</div>" ;
						$allow_issue = 0 ;
					}
					else
					{
						echo 'Name : <strong> '.$surfername.' </strong> ' ;
					}
					
					echo '
							</br>
							Year of Admission: '.$year.'
							</br> Booking ID : <strong> '.$srno.' </strong>
							<hr>
							</br>
							<strong> Book Details: </strong></br></br>
							Book ID: '.$bookid.' </br>
							Acc. No: '.$stampno.'
						' ;
					
					if( $allow_issue == 0 )
					{
						echo '<div id = "error"> Mismatch0 in Input </div> ' ;
					}					
					
					else if( $allow_issue == 2 )
					{
						echo '<div id = "error"> No books left: MismatchDB2 </div>' ;
					}
						
					else
					{
						echo '
							<div id = "book-record"> 
								<form id = "form-book-book" action = "admin-issue-confirm.php" method = "post">									
									<input class = "hidden" type = "text" name = "srno" value = '.$srno.'>
									<input class = "hidden" type = "text" name = "regno" value = '.$regno.'>
									<input class = "hidden" type = "text" name = "year" value = '.$year.'>
																
									<input class = "hidden" type = "text" name = "bookid" value = '.$bookid.'>
									<input class = "hidden" type = "text" name = "stampno"  value = '.$stampno.'>
									<input id = "form-book-book-submit" type = "submit" value = "Confirm">
								</form> 
							' ;
					}
					
					echo '
								
								<form id = "form-book-reject" action = "admin-issue-reject.php" method = "post">					
									<input class = "hidden" type = "text" name = "srno" value = "'.$srno.'">
									<input class = "hidden" type = "text" name = "regno" value = "'.$regno.'">
									<input class = "hidden" type = "text" name = "year" value = "'.$year.'">
									<input class = "hidden" type = "text" name = "bookid" value = "'.$bookid.'">
									
									<input id = "form-book-reject-submit" type = "submit" value = "Reject">
								</form>
								
							</div>
							
							</br>
						
						</div>
					' ; 
			
			
			
			echo "</div>" ;
			
			if( $ERR_CODE != 0 )
			 echo "</br> ERROR : ".$ERR_CODE ;

?>
