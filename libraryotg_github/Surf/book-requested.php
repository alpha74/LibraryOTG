
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
	
	// Getting POSTED values
	if( $_POST == NULL ) 
	{
		echo "</br> Cannot open page!" ;
		die() ;
	}
	$bookid = $_POST[ 'bookid' ] ;
	$bookname = $_POST[ 'bookname' ] ;
	
	// Prevent MySQL injection
	$bookid = stripcslashes( $bookid ) ;
	$bookname = stripcslashes( $bookname ) ;
		
	$bookid = mysqli_real_escape_string( $conn, $bookid ) ;	
	$bookname = mysqli_real_escape_string( $conn, $bookname ) ;	
	
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
	
	echo "
			<html>
				<head> 
					<link rel = 'stylesheet' type = 'text/css' href = 'book-view.css'>
					<meta name = 'viewport' content = 'width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no'/>
					<meta http-equiv = 'refresh' content = '120;url=http://192.168.77.4/libraryotg/destroy-session.php'>
				</head>
			
				<body>
					
					<div id = 'box-left'>
						
						<div id = 'box-left1'>
							
							<strong> Library OTG </strong>
							
							<div id = 'small-text1'>
								Logged in as:".$regno."
							</div>
							
							</br>
							<div>
								<form action = 'getdata.php' method = 'POST'>
									<input class = 'hidden' type = 'text' name = 'filter' value = 'misc'>
									<input id = 'button-tag-upper' type = 'submit' value = 'Surf Again'>
								</form>
								<a href = 'http://192.168.77.4/libraryotg/destroy-session.php'>
									<button id = 'button-tag-upper'> Sign Out </button>
								</a>
								
							</div>
						  
							
						</div>
						
						</br>
						<div id = 'box-left2'>
							
							<div id = 'box-left2-text'>
								
							</div>
							</br>
							
							<div>
								
							</div>
							
						</div>
					</div>
					
				</body> 
			
			</html>
		 " ;
	
	
	$year2016 = 2016 ;
	$year2017 = 2017 ;
	
	if( $year == $year2016 || $year == $year2017 )
	{
		$query_getrequests = "SELECT * FROM `studentuser".$year."` WHERE `studentuser".$year."`.`regno` = '".$regno."' ;" ;
		//echo "</br>".$query_getrequests ;
		
		if( $if_query_run = mysqli_query( $conn, $query_getrequests ) )
		{		
			$query_exe_getrequests = mysqli_fetch_assoc( $if_query_run ) ;
				
			$book_limit = 2 ;
			$book_requests = $query_exe_getrequests[ 'bookrequests' ] ;
			$book_issued = $query_exe_getrequests[ 'issuedbooksno' ] ;
			
			$random_num = rand( 1, 13 ) ;
			
			if( $book_requests < $book_limit && $book_issued < $book_limit && ( $book_requests + $book_issued ) < $book_limit )
			{
				$book_requests = $query_exe_getrequests[ 'bookrequests' ] + 1 ;
					
				// Date
				date_default_timezone_set( 'Asia/Kolkata' ) ;
				$curr_date = date( 'Y/m/d a', time() ) ;
				//echo $curr_date ;	
				
				$query_reqbook = "INSERT INTO `bookrequest` (`bookid`, `regno`, `year`, `date`, `issuestatus`) VALUES ('".$bookid."', '".$regno."','".$year."' ,'".$curr_date."', '0');" ;
				//echo "</br>".$query_reqbook ;			
			
					if( $if_query_run = mysqli_query( $conn, $query_reqbook ) ) 
					{
 						$query_update_req = "UPDATE `studentuser".$year."` SET `bookrequests` = '".$book_requests."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ;" ; 
						
						//echo "> Sub-Query Ran" ;		
								
						echo '
								<div id = "record">						
									<div id = "book-left-col">
										BOOK ID: 
										<div id = "book-record"><strong>'.$bookid.'</strong></div>
														
										</br></br>
										<hr>
										</br>
																		
										<strong> Book request successful! </strong>
														
										</br>
										<hr>
																																		
									</div>	
											
									<div id = "book-right-col">
																		
										</br>		
										<img id = "book-image" src = "http://192.168.77.4/libraryotg/Images/havefun'.$random_num.'.jpg" alt = "Image unavailable">
												
										</br></br>						
																			
										</div>
											
									</div>
							' ; 
									
						$query_update_req = "UPDATE `studentuser".$year."` SET `bookrequests` = '".$book_requests."' WHERE `studentuser".$year."`.`regno` = '".$regno."' ;" ; 
						//echo $query_update_req ;
						
						if( $if_query_run = mysqli_query( $conn, $query_update_req ) )
						{
							echo "</br> Success </br> " ;
						}
						else
						{
							echo "</br> ERR_UPDATE_REQ failure" ;
						}
					}
					else
					{
						echo "	
						<div id = 'record2'>
							</br> Request failed internally
							</br></br>
							<strong> This may be due to :</strong>
						
							<ul>
								<li> ERR_SUB_Q failure </li>
							</ul>
							
						</div>
					  " ;
					}
						
			}
			else
			{		
				echo "	
						<div id = 'record2'>
							</br> Request failed internally
							</br></br>
							<strong> This may be due to :</strong>
						
							<ul>
								<li> You have requested more books than maximum allowed </li>
								<li> You have already issued maximum books allowed </li>
							</ul>
							
						</div>
					  " ;
			}
		}
		else
		{		
			echo "	
					<div id = 'record2'>
						</br> Request failed internally
						</br></br>
						<strong> This may be due to :</strong>
					
						<ul>
							<li> ERR_SUPER_Q failure </li>
						</ul>
			
					</div>
				  " ;
		}
	
	}
	else
	{		
		echo "	
				<div id = 'record2'>
					</br> Request failed internally
					</br></br>
					<strong> This may be due to :</strong>
				
					<ul>
						<li> Your account details are incorrect </li>
					</ul>
									
				</div>
			  " ;
	}
	
?>
