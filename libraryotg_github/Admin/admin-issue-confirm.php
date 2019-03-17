
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
		$srno = $_POST[ 'srno' ] ;
		$regno = $_POST[ 'regno' ] ;
		$year = $_POST[ 'year' ] ;
		$bookid = $_POST[ 'bookid' ] ;
		$stampno = $_POST[ 'stampno' ] ;

		// Date
		date_default_timezone_set( 'Asia/Kolkata' ) ;
		$curr_date = date( 'Y/m/d h:i:s a', time() ) ;
		echo $curr_date."</br>" ;
		
		//echo "</br>".$username ;
		//echo "</br>".$srno ;
		//echo "</br>".$regno ;
		//echo "</br>".$year ;
		//echo "</br>".$bookid ;
		//echo "</br>".$stampno ;
	
	// Main tasks
	
	$task1 = 0 ;
	$task2 = 0 ;
	$task3 = 0 ;
	$task4 = 0 ;
	$ERR_CODE = 0 ;
	
	$q_getc = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."' ;" ;
	
	if( $q_getc_run = mysqli_query( $conn, $q_getc ) )
	{
		$q_getc_exe = mysqli_fetch_assoc( $q_getc_run ) ;
		$total = $q_getc_exe[ 'totalcopies' ] ;
		
		if( $total > 0 )
		{				
			// Query1 : Change bookrequests status to 1 
			$q1_ack_req = "UPDATE `bookrequest` SET `issuestatus` = '1' WHERE `bookrequest`.`srno` = '".$srno."' ; " ;
			$q1_test = "SELECT * FROM `bookrequest` WHERE `bookrequest`.`srno` = '".$srno."' ; " ;
			
			if( $q1_run = mysqli_query( $conn, $q1_ack_req ) )
			{
				//echo ">Query Ran" ;
				
				$q1_run_test = mysqli_query( $conn, $q1_test ) ;
				$q1_test_exe =  mysqli_fetch_assoc( $q1_run_test ) ; 
				$task1 = $q1_test_exe[ 'issuestatus' ] ;
			}
			else
			{
				$ERR_CODE = 3 ;
				echo ".".$ERR_CODE ;
				// Query for updating issue status is not running
			}
			//echo $task1 ;
			
			// Query2 : Decrement book count from bookdb
			
			$bookcount1 = 0 ;
			$bookcount2 = 0 ;
			
			$q2_getc = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."' ;" ;
			
			if( $q2_getc_run = mysqli_query( $conn, $q2_getc ) )
			{
				$q2_getc_exe = mysqli_fetch_assoc( $q2_getc_run ) ;
				$bookcount1 = $q2_getc_exe[ 'totalcopies' ] ;
				
				$bookcount2 = $bookcount1 - 1 ;
				
				$times1 = $q2_getc_exe[ 'notimesissued' ] ;
				$times2 = $times1 + 1 ;
				
				$q2_dec_book = "UPDATE `bookdb` SET `totalcopies` = '".$bookcount2."', `notimesissued` = '".$times2."' WHERE `bookdb`.`bookid` = '".$bookid."' ;" ;
					
				if( $q2_dec_run = mysqli_query( $conn, $q2_dec_book ) )
				{
					//echo ">Query Ran" ;
					$q2_test = "SELECT * FROM `bookdb` WHERE `bookdb`.`bookid` = '".$bookid."' ; " ;
					
					$q2_run_test = mysqli_query( $conn, $q2_test ) ;
					$q2_test_exe =  mysqli_fetch_assoc( $q2_run_test ) ; 
					
					if( $q2_test_exe[ 'totalcopies' ] == $bookcount2 )
					{
						$task2 = 1 ;
					}
					else
					{
						$ERR_CODE = 13 ;
						echo ".".$ERR_CODE ;
					}
				}
				else
				{
					$ERR_CODE = 5 ;
					echo ".".$ERR_CODE ;
					// Query for updating/ decrementing book numbers is not working
				}
			}
			else
			{
				$ERR_CODE = 4 ;
				echo ".".$ERR_CODE ;
				// Query for getting book numbers is no working
			}
			//echo $task2 ;
			
			// Query3 : Update fields in studentuser_ : Decre reqs and Incre issued
			
			$q3_stud = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ;" ;
			
			$value1 = 0 ;
			$value2 = 0 ;
			
			if( $q3_run = mysqli_query( $conn, $q3_stud ) )
			{
				//echo "</br> Query Ran " ;
				
				$q3_exe = mysqli_fetch_assoc( $q3_run ) ;
				
				$reqs = $q3_exe[ 'bookrequests' ] ;
				$reqs = $reqs - 1 ;
				
				$issued = $q3_exe[ 'issuedbooksno' ] ;
				$issued = $issued + 1 ;
				
				$q3_studupdate = "UPDATE `studentuser".$year."` SET `bookrequests` = '".$reqs."', `issuedbooksno` = '".$issued."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ; " ;
				
				if( $q3_run = mysqli_query( $conn, $q3_studupdate ) )
				{
					//echo "</br> Query2 Ran " ;
					
					$q3_stud = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ;" ;
					if( $q3_run = mysqli_query( $conn, $q3_stud ) )
					{
						//echo "</br> Query Ran " ;
						
						$q3_exe = mysqli_fetch_assoc( $q3_run ) ;
						
						$reqs = $q3_exe[ 'bookrequests' ] ;
						$issued = $q3_exe[ 'issuedbooksno' ] ;
				
						if( $reqs > 1 || $reqs < 0 || $issued > 2 || $issued < 0 )
						{
							$ERR_CODE = 9 ;
							echo ".".$ERR_CODE ;
							// Surfer has somehow crossed max request and issue limits
							// Or the values are not within limits
						}
						else
						{
							$task3 = 1 ;
						}
					}
					else
					{
						$ERR_CODE = 8 ;
						echo ".".$ERR_CODE ;
						// Query for double checking discrepancies in studentuser max requests and issues is nnot working
					}
				}
				else
				{
					$ERR_CODE = 7 ;
					echo ".".$ERR_CODE ;
					// Query for updating request and issuedbooks is not working
				}
			}
			else
			{
				$ERR_CODE = 6 ;
				echo ".".$ERR_CODE ;
				// Main query for getting book request and issue is not working
			}
			
			// Query 4 : Doing entry in issued books table
			
			$q4_getc = "SELECT * FROM `issuedbooks` ; " ;
				
			if( $q4_run = mysqli_query( $conn, $q4_getc ) )
			{
				//echo "</br> Query Ran " ;
				
				$count1 =  mysqli_num_rows( $q4_run ) ;
								
				$q4_issue = "INSERT INTO `issuedbooks` (`isrno`, `stampnumber`, `bookid`, `regno`, `year`, `bookingid`, `dateissue`, `returndate`, `returned`) VALUES (NULL, '".$stampno."', '".$bookid."', '".$regno."', '".$year."', '".$srno."', '".$curr_date."', '', '0') ; " ;
				
				if( $q4_run = mysqli_query( $conn, $q4_issue ) )
				{
					$q4_getc = "SELECT * FROM `issuedbooks` ; " ;
					
					if( $q4_run = mysqli_query( $conn, $q4_getc ) )
					{
						//echo "</br> Query3 Ran " ;
						$count2 =  mysqli_num_rows( $q4_run ) ;
						
						if( $count2 > $count1 )
						{
							$task4 = 1 ;
						}
						else
						{
							$ERR_CODE = 12 ;
							echo ".".$ERR_CODE ;
							// Count is not increasing after new insertion
						}
					}
				}
				else
				{
					$ERR_CODE = 11 ;
					echo ".".$ERR_CODE ;
					// Query for inserting new entry is not working
				}
				
			}
			else
			{
				$ERR_CODE = 10 ;
				echo ".".$ERR_CODE ;
				// Query for getting count before insertion is not working
			}
			
			
		}
		else
		{
				$ERR_CODE = 2 ;
				echo ".".$ERR_CODE ;
				// All books of that Book ID are already issued.
				// If a book of that ID is present in library, there is a mismatch is the database
		}
	
	}
	else
	{
		$ERR_CODE = 1 ;
		echo ".".$ERR_CODE ;
		// Query for getting total copies left is not getting executed
	}
	
		echo "
				<html>
					<head> 
						<link rel = 'stylesheet' type = 'text/css' href = 'admin-issue-confirm.css'>
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
								<div>
									
									<!-- Home -->
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
										Issue Status
										
										" ;
		if( $task1 == 0 )
		 echo "</br><div id = 'error'> FAIL1 </div>" ;
		else
		 echo "</br><div id = 'success'> DONE1 </div>" ;
		
		if( $task2 == 0 )
		 echo "</br><div id = 'error'> FAIL2 </div>" ;
		else
		 echo "</br><div id = 'success'> DONE2 </div>" ;
		
		if( $task3 == 0 )
		 echo "</br><div id = 'error'> FAIL3 </div>" ;
		else
		 echo "</br><div id = 'success'> DONE3 </div>" ;
	 
		if( $task4 == 0 )
		 echo "</br><div id = 'error'> FAIL4 </div>" ;
		else
		 echo "</br><div id = 'success'> DONE4 </div>" ;
										
		echo "							
									</div>
									</br></br>
									
									</div>
							</div>
						</div>
					</body> 
				</html>
			 " ;
			
		$query = "SELECT * FROM `bookdb`" ;
		
		if( $if_query_run = mysqli_query( $conn, $query ) ) 
		{
			//echo ">Query Ran" ;		
			
			while( $query_execute = mysqli_fetch_assoc( $if_query_run ) )
			{
				if( $query_execute[ 'bookid' ] == $bookid )
				{				
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
										
										<strong> Tags: </strong>'.$query_execute[ 'booktag' ].'
																		
									</div>							
												
									</br>
									<hr>
									</br>
									<strong> Issue Details : </strong>
									</br></br>
									
									<div id = "book-describe">
										Current Details
									</div>
									
									</br>	
									<hr>
									<strong> Other Details : </strong>
									</br></br>
									<div id = "book-describe">
										Other
									</div>
								</div>	
									
									' ;						
				}
				
			}
				
			
		}
		
		else
		{
			echo "</br> Request failed internally" ;
			
			echo "	
					</br></br>
					<ul>
						<li> Book Issue operation is completed </li>
						<li> Table not reachable </li>
					</ul>			
				 " ;
		}
?>