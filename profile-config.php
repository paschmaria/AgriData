<?php
  include('functions.php');

  if (isset($_POST['update_profile'])) {
    update_user_profile();
  }

  function update_user_profile() {
    global $db, $errors, $email;

		// receive all input values from the form. 
		// call the e() function to escape form values
		$username		 =  $_SESSION['user']['username'];
		$firstname   =  e($_POST['firstname']);
    $lastname    =  e($_POST['lastname']);
    $gender  		 =  e($_POST['gender']);
    $month  		 =  e($_POST['dob_month']);
		$day  			 =  e($_POST['dob_day']);
		$year  			 =  e($_POST['dob_year']);
    $phone  		 =  e($_POST['phone']);
    $address  	 =  e($_POST['address']);
		$education   =  e($_POST['education']);
		$degree  	   =  e($_POST['degree']);
		$state  		 =  e($_POST['user_state']);
		$lga  			 =  e($_POST['user_lga']);
		$town  			 =  e($_POST['user_town']);
		$about       =  e($_POST['about_user']);

		// form validation: ensure that the form is correctly filled
		$n = $firstname;
		switch (empty($n)) {
			case $firstname:
			array_push($errors, "Firstname is required");
				break;
			
			case $lastname:
			array_push($errors, "Lastname is required");
				break;
			
			case $gender:
			array_push($errors, "Gender is required");
				break;
			
			case $month:
			array_push($errors, "Month of Birth is required");
				break;
			
			case $day:
			array_push($errors, "Day of Birth is required");
				break;
			
			case $year:
			array_push($errors, "Year of Birth is required");
				break;
			
			case $phone:
			array_push($errors, "Phone number is required");
				break;
			
			case $address:
			array_push($errors, "Address is required");
				break;
			
			case $education:
			array_push($errors, "Highest level of education is required");
				break;

			case $state:
			array_push($errors, "State is required");
				break;

			case $lga:
			array_push($errors, "Local Government Area is required");
				break;

			case $town:
			array_push($errors, "Town or Village is required");
				break;
					
			default:
				break;
		}

		if (!empty($year) && !empty($month) && !empty($day)) {
			$dob = $year."-".$month."-".$day;
    }
    
		// register user if there are no errors in the form
		if (count($errors) === 0) {

			$query = "UPDATE users SET firstname='$firstname', lastname='$lastname', phone='$phone', gender='$gender', address='$address', date_of_birth='$dob', bio='$about', education='$education', degree='$degree', state='$state', lga='$lga', town='$town' WHERE username='$username'";
			if (!mysqli_query($db, $query)) {
				var_dump(mysqli_error($db));
			}

			$new_query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
			$results = mysqli_query($db, $new_query);
			
			if (mysqli_num_rows($results) === 1) {
				$_SESSION['user'] = mysqli_fetch_assoc($results); // put logged in user in session
				// var_dump($_SESSION['user']);
			}

			$user = $_SESSION['user'];

			if ($user['user_type']==="agent") {
				$fullname = $user['firstname']. " " .$user['lastname'];
				$phone = $user['phone'];
				$email = $user['email'];
				
				$query = "UPDATE agents SET fullname='$fullname', phone='$phone' WHERE email='$email'";
				if (!mysqli_query($db, $query)) {
					var_dump(mysqli_error($db));
				}
			}
		}
  }