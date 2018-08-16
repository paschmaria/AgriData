<?php 
	session_start();
    
  header("Access-Control-Allow-Origin: *");
	// connect to database
	$db = mysqli_connect('localhost', 'root', 'SperaenDeo1', 'agridata');
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// variable declaration
	$username 		= "";
	$email    		= "";
	$errors   		= array(); 
	$alerts    		= array();
	$project_name = array();

	array_push($project_name, "Register Farmer");
	array_push($project_name, "Market Prices");
	array_push($project_name, "Farmer's Survey");

	// call the register() function if register button is clicked
	if (isset($_POST['register'])) {
		register();
	}

	// REGISTER USER
	function register(){
		global $db, $errors, $username, $email;

		// receive all input values from the form. 
		// call the e() function to escape form values
		$username    =  e($_POST['username']);
		$email       =  e(e_valid($_POST['email']));
		$password_1  =  e($_POST['password_1']);
		$password_2  =  e($_POST['password_2']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { 
			array_push($errors, "Username is required"); 
		}
		if (empty($email)) { 
			array_push($errors, "Email is required"); 
		}
		if (empty($password_1)) { 
			array_push($errors, "Password is required"); 
		}
		if ($password_1 !== $password_2) {
			array_push($errors, "The two passwords do not match");
		}

		// first check the database to make sure 
		// a user does not already exist with the same username and/or email
		$user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
		$result = mysqli_query($db, $user_check_query);
		$user = mysqli_fetch_assoc($result);
		
		if ($user) { // if user exists
			if ($user['username'] === $username && $user['email'] === $email) {
				array_push($errors, "You are already registered!");
			} else if ($user['email'] === $email && $user['username'] !== $username) {
				array_push($errors, 'A user with this email exists!');
			} else if ($user['email'] !== $email && $user['username'] === $username) {
				array_push($errors, 'Username exists, please use a different username!');
			}
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password_1); //encrypt the password before saving in the database
			$verify_code = md5(rand(0,1000)); // Generate random 32 character hash and assign it to a local variable.

			$query = "INSERT INTO users (username, email, firstname, lastname, phone, gender, user_type, password, verify_code, verified, address, date_of_birth, bio, education, degree, state, lga) 
							VALUES ('$username', '$email', '', '', '', '', 'agent', '$password', '$verify_code', 0, '', '1000-01-01', '', '', '', '', '')";
			if (!mysqli_query($db, $query)) {
				var_dump(mysqli_error($db));
			} else {
				// get id of the created user
				$logged_in_user_id = mysqli_insert_id($db);
				$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
				$_SESSION['success']  = "You are now logged in";
				send_email($email, $verify_code);

				header('location: ./verify-email.php');
			}

			exit;
		}
	}
	// Send email to our user
	function send_email($email, $verify_code) {
		require("./sendgrid/sendgrid-php.php");
		$from = new SendGrid\Email("AgriData Team", "noreply@plurimustech.ng");
		$subject = "Welcome to AgriData! Activate Your Account";
		$to = new SendGrid\Email("", $email);
		$content = new SendGrid\Content("text/html", "
			<div style='background-color: #f5f7fb; color: #495057;'>
				<h1>You're almost there!</h1>
				<br />
				<p>Your account has been created, please click the button below to activate your account:</p>
				<br />
				------------------------------------------------
				<br />
				<a href='https://agridata.plurimustech.ng/login.php?hvc=\"'.$verify_code.'\"'>
					<button type='button' style='padding: 0.75rem; background-color: #817729; -webkit-appearance: button; font-size: 0.8215rem; text-align: center; border-radius: 3px; border: 1px solid #786e21; color: #fff;'>Activate Account</button>
				</a>
				
				<br />
				<p> or copy the link to your browser:</p>
				<br />
				https://agridata.plurimustech.ng/login.php?hvc=".$verify_code."
				<br />
				------------------------------------------------
			</div>
		");
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
		var_dump($response->statusCode());
		var_dump($response->body());

	}

	// return user array from their id
	function getUserById($id){
		global $db;

		$query = "SELECT * FROM users WHERE id=$id";
		$result = mysqli_query($db, $query);

		$user = mysqli_fetch_assoc($result);
		return $user;
	}

	// check if email address is valid, i.e. if it matches the format xx@xxx.xxxx
	function e_valid($email) {
		global $errors;

		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)){
			array_push($errors, "Email is invalid");
		}else{
			return $email;
		}
	}

	// escape string
	function e($val){
		global $db;

		if (isset($val)) {
			return mysqli_real_escape_string($db, trim($val));
		}
	}

	// display error messages
	function display_error() {
		global $errors;

		if (count($errors) > 0){
			echo '<p class="text-danger mb-0">';
				foreach ($errors as $error){
					echo $error .'<br>';
				}
			echo '</p>';
		}
	}

	// display alert messages
	function alert_message($message, $type) {
		global $alerts;

		array_push($alerts,
		'<div class="alert alert-'. $type .'" role="alert">'
			. $message .
		'</div>');
	}

	function alert() {
		global $alerts;

		if (count($alerts > 0)) {
			foreach ($alerts as $alert) {
				echo $alert;
			}
		}
	}

	// log user out when logout link is clicked
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['user']);
		header("location: login.php");
	}

	// call the send_email() function if resend btn is clicked
	if (isset($_POST['resend'])) {
		global $db;

		$verify_code = md5(rand(0,1000));
		$email = $_SESSION['user']['email'];
		$query = "UPDATE users SET verify_code='$verify_code' WHERE email='$email'";
		if (!mysqli_query($db, $query)) {
			var_dump(mysqli_error($db));
		} else {
			send_email($email, $verify_code);
		}
    
  }

	// call the login() function if login btn is clicked
	if (isset($_POST['login'])) {
		login();
	}

	// call the register_farmer() function if the Forms btn is clicked
	if (isset($_POST['register_farmer'])) {
		register_farmer();
	}

	// LOGIN USER
	function login(){
		global $db, $username, $errors;

		// grab form values
		$username = e($_POST['username']);
		$password = e($_POST['password']);

		// make sure form is filled properly
		if (empty($username)) {
			array_push($errors, "Username is required");
		} elseif (empty($password)) {
			array_push($errors, "Password is required");
		}

		// attempt login if no errors on form
		if (count($errors) == 0) {
			$password = md5($password);

			$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) { // user found
				// check if user is admin or user
				$logged_in_user = mysqli_fetch_assoc($results);
				if ($logged_in_user['user_type'] == 'admin') {

					$_SESSION['user'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in as Admin";
					header('location: ./forms.php');		  
				}else{
					$_SESSION['user'] = $logged_in_user;
					$_SESSION['success']  = "You are now logged in";
					header('location: ./forms.php');
				}
			}else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}

	// Variable declaration
	$month 				= "";
	$day 					= "";
	$year 				= "";
	$land_size 		= "";
	$land_unit 		= "";
	$produce_size = "";
	$produce_unit = "";

	function check_and_save_file($fs,$fn,$ft) {
		global $errors;
		// Check size
		if($fs > 2097152){
			array_push($errors, "File size must be less than 2 MB");
		}

		$dir_name				= "./assets/images/farmers_pictures";
		$picFileType 		= strtolower(pathinfo("$dir_name/".$fn, PATHINFO_EXTENSION));
		$extensions_arr = array("jpg","jpeg","png","svg");
		// Check extension
		if( !in_array($picFileType,$extensions_arr) ){
			array_push($errors, "Picture file format not supported, only jpeg, jpg, png, and svg allowed!");
		}
		
		$temp  = explode(".", $fn);
		$newFN = uniqid() . '.' . end($temp);

		if (count($errors) === 0) {
			if(is_dir($dir_name) === false){
				mkdir("$dir_name", 0700);		// Create directory if it does not exist
			}
			move_uploaded_file($ft, $dir_name."/".$newFN);
		}
		return $newFN;
	}

	function getFileProp($files) {
		$fileNameArr = array();
		if (isset($files)) {
			foreach ($files["tmp_name"] as $key => $tmp_name) {
				$file_name = $key.$files["name"][$key];
				$file_size = $files["size"][$key];
				$file_tmp  = $files['tmp_name'][$key];
				$file_type = $files['type'][$key];

				array_push($fileNameArr, check_and_save_file($file_size, $file_name, $file_tmp));
			}
		}
		return $fileNameArr;
	}

	// Forms
	function register_farmer(){
		global $db, $errors, $month, $day, $year, $land_size, $land_unit, $produce_size, $produce_volume;

		// receive all input values from the form.
		// call the e() function to escape form values
		$firstname    =  e($_POST['farmer_firstname']);
		$lastname    	=  e($_POST['farmer_lastname']);
		$phone1    		=  e($_POST['farmer_phone1']);
		$phone2    		=  e($_POST['farmer_phone2']);
		$email       	=  e($_POST['farmer_email']);
		$month  			=  e($_POST['farmer_dob_month']);
		$day  				=  e($_POST['farmer_dob_day']);
		$year  				=  e($_POST['farmer_dob_year']);
		$gender  			=  e($_POST['farmer_gender']);
		$education  	=  e($_POST['farmer_education']);
		$family_size  =  e($_POST['farmer_family_size']);
		$income				=  e($_POST['farmer_income']);
		$state  			=  e($_POST['farmer_state']);
		$lga  				=  e($_POST['farmer_lga']);
		$town  				=  e($_POST['farmer_town']);
		$latitude  		=  e($_POST['farmer_latitude']);
		$longitude  	=  e($_POST['farmer_longitude']);
		$land_size  	=  e($_POST['farmer_land_size']);
		$land_unit  	=  e($_POST['farmer_land_unit']);
		$crops  			=  e($_POST['farmer_crops']);
		$produce_size	=  e($_POST['farmer_produce_size']);
		$produce_unit =  e($_POST['farmer_produce_unit']);
		$farm_labour  =  e($_POST['farmer_farm_labour']);

		$farmer_pic 	=  $_FILES['farmer_pic'];
		$farm_pic 		=  $_FILES['farm_pictures'];

		$farmer_pic_name = $farmer_pic['name'];
		$farmer_pic_size = $farmer_pic['size'];
		$farmer_pic_tmp  = $farmer_pic['tmp_name'];
		
		$user					=  $_SESSION['user']['username'];
		$land_area		=  $land_size." ".$land_unit;
		$produce_volume= $produce_size." ".$produce_unit;
		$new_farmer_pic= check_and_save_file($farmer_pic_size,$farmer_pic_name,$farmer_pic_tmp);
		$new_farm_pic	= (implode(",", getFileProp($farm_pic)));
		
		// form validation: ensure that the form is correctly filled
		$n = $firstname;
		switch (empty($n)) {
			case $firstname:
			array_push($errors, "Firstname is required");
				break;
			
			case $lastname:
			array_push($errors, "Lastname is required");
				break;
			
			case $farmer_pic:
			array_push($errors, "Farmer's picture is required");
				break;
			
			case $phone1:
			array_push($errors, "Primary Phone number is required");
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
			
			case $gender:
			array_push($errors, "Gender is required");
				break;
			
			case $education:
			array_push($errors, "Education is required");
				break;

			case $family_size:
			array_push($errors, "Family Size is required");
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

			case $latitude:
			array_push($errors, "Latitude is required");
				break;

			case $longitude:
			array_push($errors, "Longitude is required");
				break;

			case $land_size:
			array_push($errors, "Land Size is required");
				break;

			case $land_unit:
			array_push($errors, "Land Unit is required");
				break;

			// case $farm_pic:
			// array_push($errors, "Farmer's farm picture is required");
			// 	break;

			case $crops:
			array_push($errors, "Farmer's crop is required");
				break;

			case $produce_size:
			array_push($errors, "Produce Size is required");
				break;

			case $produce_unit:
			array_push($errors, "Produce Unit is required");
				break;

			case $farm_labour:
			array_push($errors, "Farm Labour type is required");
				break;
					
			default:
				break;
		}

		if (!empty($year) && !empty($month) && !empty($day)) {
			$dob = $year."-".$month."-".$day;
		}

		// first check the database to make sure farmer's details have not already been submitted
		$farmer_check_query = "SELECT * FROM farmers WHERE phone_primary='$phone1' LIMIT 1";
		$result = mysqli_query($db, $farmer_check_query);
		$farmer = mysqli_fetch_assoc($result);
		if ($farmer) { // if farmer exists
			if ($farmer['firstname'] === $firstname && $farmer['lastname'] === $lastname && $farmer['phone_primary'] === $phone1) {
				array_push($errors, "Farmer has been registered!");
			} else if ($farmer['firstname'] !== $firstname && $farmer['lastname'] !== $lastname && $farmer['phone_primary'] === $phone1) {
				array_push($errors, 'A farmer with this primary phone number exists!');
			}
		}

		// var_dump($errors);
		// Forms if there are no errors in the form
		if (count($errors) === 0) {
			$query = "INSERT INTO farmers (firstname, lastname, farmer_pic, phone_primary, phone_secondary, email, date_of_birth, gender, education, family_size, income, state, lga, town, latitude, longitude, land_area, farm_pic, crops, produce_volume, farm_labour, user) VALUES ('$firstname', '$lastname', '$new_farmer_pic', '$phone1', '$phone2', '$email', '$dob', '$gender', '$education', '$family_size', '$income', '$state', '$lga', '$town', '$latitude', '$longitude', '$land_area', '$new_farm_pic', '$crops', '$produce_volume', '$farm_labour', '$user')";
			// mysqli_query($db, $query);
			if (!mysqli_query($db, $query)) {
				var_dump(mysqli_error($db));
			}
			mysqli_close($db);
		}
	}
	