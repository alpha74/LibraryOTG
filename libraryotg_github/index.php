<?php
	// Connecting to DB
	require 'connect_libraryotg1.php' ;
	
	// Unsetting all sessions
	session_start() ;
	session_destroy() ;
	
	// Client IP
	$client_ip = $_SERVER[ 'REMOTE_ADDR' ] ;
	echo $client_ip ;
	
	// Date
	date_default_timezone_set( 'Asia/Kolkata' ) ;
	$curr_date = date( 'Y-m-d', time() ) ; // Only date no time
	
	// Log page loads for security
	$log_track = 1 ;
	
	$task1 = 0 ;
	$task2 = 0 ;
	$task3 = 0 ;
	
	if( $log_track )
	{
		// Query1 : Log IP into logtable
		$q1_log = "INSERT INTO `pagerequestlog` (`srno`, `localip`) VALUES (NULL, '".$client_ip."') " ;
		
		if( $q1_run = mysqli_query( $conn, $q1_log ) )
		{
			$task1 = 1 ;
		}
		else
		{
			//echo $q1_log ;
			$ERR_CODE = 1 ;
			echo "</br> Operation failed ".$ERR_CODE ;
			die() ;
		}
	}
?>
<html>
	<head>
		<title>
			Library Home
		</title>
		<link rel="icon" href="Images/lotg-icon.png">
		<link rel = "stylesheet" type = "text/css" href = "lib_index.css">
		<link rel = "stylesheet" type = "text/css" href = "index_slider.css">			
		<meta name = "viewport" content = "width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no"/>
	</head>

	<body>
		
		<!--<a title = "Print page" onclick = "window.print()" target = "_blank">Print </a>-->
		
		<div id = "mobile-buttons">
			<button id = "mobile-button1" onclick = "ShowOB1()"> Login </button>
			<button id = "mobile-button2" onclick = "ShowOB2()"> About </button>
			<button id = "mobile-button3" onclick = "ShowOB3()"> Top Books </button>
		</div>
		
		<div id = "wrapper">
			<div id = "outerbox1">
				<center><strong>
				<p id = "header_text1">
					Library OTG v3.10.3
					</strong></p>
					
						<form id = "form1box" action = "verify-admin.php" method = "post">
							<input id = "form1" name = "username" type = "text" placeholder = "Username" title = "No spaces allowed" maxlength = 10 required>
							</br>
							<input id = "form1" name = "password" type = "password" placeholder = "Password" maxlength = 12 required>
							</br>					
							<input type = "submit" id = "action_button1" title = "For Admin login" value = "I am Admin">		
						</form>
		
					</form>
			
					<hr>
					</br>
					
					<form id = "form2box" action = "verify-surfer.php" method = "post">
						<input id = "form2" name = "regno" type = "text" placeholder = "Registration Number:BT" title = "Uppercase" maxlength = 10 required>
						</br>
						<input id = "form2" name = "password" type = "password" placeholder = "Password" maxlength = 12 required>
						</br>
						Year of admission
						<select id = "form2" title = "Your year of admission in college" name = "year" required> 
							<option name = "year" value = "2016" selected> 2016 </option>
							<option name = "year" value = "2017"> 2017 </option>
						</select>
						
						<input type = "submit" id = "action_button2" title = "Provide your Registration no. and password for Booking" value = "Surfer Login">		
					</form>
					<div id = "small-text1">
						<!--Not signed up?Request New Account-->
					</div>
					
					</br>
					
					<div>
					<button id = "action_button4" title = "For the developer team">
						Maintenance
					</button>
					<div>
				</center>
			</div>
		
			<div id = "outerbox2">
				<div id = "content-text1">
					<strong> 
						Greetings from Library OTG!
					</strong>
					</br>
					<hr>
				
					</br>
					
					<strong> Features: </strong>
					<ul> 
						<li> It is mobile-friendly </li> 
						<li> Issue book without ID card( Consult Library Incharge ) </li>
						<li> Explore books through filters and search </li>
						<li> Accessible on College WLAN </li>
					</ul>
					
					</br>
					<b>
						Check your Issue History now!
					</b>
					
					<hr>
					</br>
					Book collection and return is to be done physically
					</br>
					<strong> Get Your Login details from Library ASAP!  
					
					</br> See 
					<a href = "lib_rules.html"> Instructions </a></strong>
					and 
					<a href = "lib_ack.html"> Credits </a></strong>
					</br></br>
					<hr>
<?php
		// Query 3 : Get Visitors details : Using PageRequest and making it a percentage
		$q3_vis = "SELECT * FROM `loginlog` WHERE `time` LIKE '".$curr_date."%' " ;
		//echo $q3_vis ;
		
		$visitors = 11 ;
		
		if( $q3_run = mysqli_query( $conn, $q3_vis ) )
		{
			$task3 = 1 ;
			$visitors = mysqli_num_rows( $q3_run ) ;
		}
		
		// Calculating percentage
		$total_users = 210 ;
		$visit_percent = ( $visitors / $total_users ) * 100 ;
		
		echo "
			<div title = '".$visitors."'>".
			round( $visit_percent, 2 )."% visits today
			</div>" ;
?>
				</div>
				
			</div>
			
			<div id = "outerbox3">
				<!-- Slideshow container -->
				<div class="slideshow-container">
<?php

	// Number of Books to be displayed
	$num_books = 5 ;

	// QUERY : Getting list of top rating books
	$q2_top = "SELECT `bookimage` FROM `bookdb` ORDER BY `notimesissued` DESC LIMIT 0,".$num_books."" ;
	
	if( $q2_run = mysqli_query( $conn, $q2_top ) )
	{
		$task2 = 1 ;
		$counter = 1 ;
		
		while( $q2_exe = mysqli_fetch_assoc( $q2_run ) ) 
		{
			echo '
					<div class="mySlides fade">
						<div class="numbertext">LOTG: IIITN</div>
						<img id = "slide-image" src="'.$q2_exe[ 'bookimage' ].'">
						<div class="text">Trending #'.$counter.'</div>
					 </div>
				 ' ;
			$counter = $counter + 1 ;	 
		}
		
		echo '
				<!-- The dots/circles -->
					<div style="text-align:center">
			 ' ;
		
		$counter = 1 ;
		while( $counter <= $num_books )
		{
			echo '				
				  <span class="dot" onclick="currentSlide('.$counter.')"></span> 
				' ;
			$counter = $counter + 1 ;	
		}
		
		echo ' 
				</div>
			  ' ;
	}
	else
	{	
		$ERR_CODE = 2 ;
		echo $ERR_CODE ;
		echo '
				<!-- Full-width images with number and caption text -->
				  <div class="mySlides fade">
					<div class="numbertext">LOTG: IIITN</div>
					<img id = "slide-image" src="Book-Images/book1.jpg">
					<div class="text">Top trending Books</div>
				  </div>

				  <div class="mySlides fade">
					<div class="numbertext"> LOTG:IIITN </div>
					<img id = "slide-image" src="Book-Images/book2.jpg">
					<div class="text">Top trending Books</div>
				  </div>

				  <div class="mySlides fade">
					<div class="numbertext">LOTG:IIITN</div>
					<img id = "slide-image" src="Book-Images/book3.jpg">
					<div class="text">Top trending Books</div>
				  </div>
				  
				  <div class="mySlides fade">
					<div class="numbertext">LOTG:IIITN</div>
					<img id = "slide-image" src="Book-Images/book4.jpg">
					<div class="text">Top trending Books</div>
				  </div>
				  
				  <div class="mySlides fade">
					<div class="numbertext">LOTG:IIITN</div>
					<img id = "slide-image" src="Book-Images/book5.jpg">
					<div class="text">Top trending Books</div>
				  </div>
	
				</div>
				<br>

				<!-- The dots/circles -->
				<div style="text-align:center">
				  <span class="dot" onclick="currentSlide(1)"></span> 
				  <span class="dot" onclick="currentSlide(2)"></span> 
				  <span class="dot" onclick="currentSlide(3)"></span>
				  <span class="dot" onclick="currentSlide(4)"></span>
				  <span class="dot" onclick="currentSlide(5)"></span>
				</div>
			' ;
	}
?>
			</div>
		
		</div>
		
	</body>
	
</html>

	<script>
		var slideIndex = 0;
		showSlides();

		function showSlides() {
			var i;
			var slides = document.getElementsByClassName("mySlides");
			var dots = document.getElementsByClassName("dot");
			for (i = 0; i < slides.length; i++) {
			   slides[i].style.display = "none";  
			}
			slideIndex++;
			if (slideIndex > slides.length) {slideIndex = 1}    
			for (i = 0; i < dots.length; i++) {
				dots[i].className = dots[i].className.replace(" active", "");
			}
			slides[slideIndex-1].style.display = "block";  
			dots[slideIndex-1].className += " active";
			setTimeout(showSlides, 2000); // Change image every 2 seconds
		}
		
		
	</script>
	
	<script>
		function ShowOB1()
		{
			document.getElementById( "outerbox1" ).style.display = "inline-block" ;
			document.getElementById( "outerbox2" ).style.display = "none" ;
			document.getElementById( "outerbox3" ).style.display = "none" ;
			
			document.getElementById( "mobile-button1" ).style.opacity = "0.9" ;
			document.getElementById( "mobile-button2" ).style.opacity = "0.7" ;
			document.getElementById( "mobile-button3" ).style.opacity = "0.7" ;
			
			document.getElementById( "outerbox1" ).style.opacity = "0.9" ;
			document.getElementById( "outerbox2" ).style.opacity = "0.7" ;
			document.getElementById( "outerbox3" ).style.opacity = "0.7" ;
		}
		function ShowOB2()
		{
			document.getElementById( "outerbox1" ).style.display = "none" ;
			document.getElementById( "outerbox2" ).style.display = "inline-block" ;
			document.getElementById( "outerbox3" ).style.display = "none" ;
			
			document.getElementById( "mobile-button1" ).style.opacity = "0.7" ;
			document.getElementById( "mobile-button2" ).style.opacity = "0.9" ;
			document.getElementById( "mobile-button3" ).style.opacity = "0.7" ;
			
			document.getElementById( "outerbox1" ).style.opacity = "0.7" ;
			document.getElementById( "outerbox2" ).style.opacity = "0.9" ;
			document.getElementById( "outerbox3" ).style.opacity = "0.7" ;
		}
		function ShowOB3()
		{
			document.getElementById( "outerbox1" ).style.display = "none" ;
			document.getElementById( "outerbox2" ).style.display = "none" ;
			document.getElementById( "outerbox3" ).style.display = "inline-block" ;
			
			document.getElementById( "mobile-button1" ).style.opacity = "0.7" ;
			document.getElementById( "mobile-button2" ).style.opacity = "0.7" ;
			document.getElementById( "mobile-button3" ).style.opacity = "0.9" ;
			
			document.getElementById( "outerbox1" ).style.opacity = "0.7" ;
			document.getElementById( "outerbox2" ).style.opacity = "0.7" ;
			document.getElementById( "outerbox3" ).style.opacity = "0.9" ;
		}
	</script>
