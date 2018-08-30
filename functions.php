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

	array_push($project_name, "register_farmer");
	array_push($project_name, "market_prices");
	array_push($project_name, "farmers_survey");

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
		if (empty($password_2)) { 
			array_push($errors, "Please confirm password"); 
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
		if (count($errors) === 0) {
			$password = md5($password_1); //encrypt the password before saving in the database
			$verify_code = md5(uniqid(rand(0,1000))); // Generate random 32 character hash and assign it to a local variable.
			$signed_up_time = date("Y-m-d H:i:s", time());

			$query = "INSERT INTO users (username, email, firstname, lastname, profile_pic, phone, gender, user_type, password, password_reset_code, project_name, project_id, verify_code, verified, address, date_of_birth, bio, education, degree, state, lga, town, signed_up) VALUES ('$username', '$email', '', '', '', '', '', 'agent', '$password', '', '', '', '$verify_code', 0, '', '1000-01-01', '', '', '', '', '', '', '$signed_up_time')";
			if (!mysqli_query($db, $query)) {
				var_dump(mysqli_error($db));
			} else {
				// get id of the created user
				$logged_in_user_id = mysqli_insert_id($db);
				$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
				$_SESSION['success']  = "You are now logged in";
				verification_email_content($email, $verify_code);

				header('location: ./verify-email.php');
			}

			exit;
		}
	}

	function invite_email_content($email, $id, $name, $user) {
		$name_arr			= explode("-", $name);
		$project_name = ucfirst(implode(" ", $name_arr));
		$subject			= "You are invited to collaborate on the " . $project_name . " project!";
		$message			= "
			<h1>Hey there!</h1>
			<br />
			<p>You've been invited by ". ucfirst($user) ." to join the ". $project_name ." project.</p>
			<br />
			<p>Click on the button below to join.</p>

			<a href='https://agridata.plurimustech.ng/forms?name=". $name ."&id=". $id ."'>
					<button type='button' style='padding: 0.75rem; background-color: #817729; -webkit-appearance: button; font-size: 0.8215rem; text-align: center; border-radius: 3px; border: 1px solid #786e21; color: #fff;'>Join Project</button>
				</a>
				
			<br />
			<p> or copy the following link to your browser:</p>
			<br />
			https://agridata.plurimustech.ng/forms?name=". $name ."&id=". $id ."
			<br />

			<p>Thanks</p>
			<p>Your friends at AgriData</p>
		";

		send_email($subject, $email, $message);
	}

	function verification_email_content($email, $code) {
		$subject = "Welcome to AgriData! Activate Your Account";
		$message = "
			<div style='background-color: #f5f7fb; color: #495057;'>
				<h1>You're almost there!</h1>
				<br />
				<p>Your account has been created, please click the button below to activate your account:</p>
				<br />
				------------------------------------------------
				<br />
				<a href='https://agridata.plurimustech.ng/login?hvc=".$code."'>
					<button type='button' style='padding: 0.75rem; background-color: #817729; -webkit-appearance: button; font-size: 0.8215rem; text-align: center; border-radius: 3px; border: 1px solid #786e21; color: #fff;'>Activate Account</button>
				</a>
				
				<br />
				<p> or copy the link to your browser:</p>
				<br />
				https://agridata.plurimustech.ng/login?hvc=".$code."
				<br />
				------------------------------------------------

				<p>Thanks</p>
				<p>Your friends at AgriData</p>
			</div>
		";

		send_email($subject, $email, $message);
	}

	function password_reset_email_content($email, $code) {
		global $db;

		$subject = "Please reset your password";
		$message = "
			<p>We are so sorry you lost your AgriData password!</p>

			<p>But don't worry, you'll be back in as soon as possible! Click on the link below to reset your password:</p>

			https://agridata.plurimustech.ng/password-reset?pass_rc=".$code."

			<br />
			<p>This link will expire within 3 hours if you don't use it. To get a new password reset link, visit https://agridata.plurimustech.ng/forgot-password</p>
			
			<p>Thanks</p>
			<p>Your friends at AgriData</p>
		";

		send_email($subject, $email, $message);

		$query = "UPDATE users SET password_reset_code='$code' WHERE email='$email' LIMIT 1";
		if (!mysqli_query($db, $query)) {
			mysqli_error($db);
		}
	}

	// Send email to our user
	function send_email($subject, $email, $message) {
		require("./sendgrid/sendgrid-php.php");
		$from = new SendGrid\Email("AgriData Team", "noreply@plurimustech.ng");
		// $subject = $subject;
		$to = new SendGrid\Email("", $email);
		$content = new SendGrid\Content("text/html", $message);
		$mail = new SendGrid\Mail($from, $subject, $to, $content);
		$apiKey = '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';
		$sg = new \SendGrid($apiKey);
		$response = $sg->client->mail()->send()->post($mail);
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
		'<div class="alert alert-'. $type .' text-center" role="alert">'
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

	// call the register() function if register button is clicked
	if (isset($_POST['register'])) {
		register();
	}

	// log user out when logout link is clicked
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['user']);
		header("location: login.php");
	}

	// call the resend_email() function if resend btn is clicked
	if (isset($_POST['resend'])) {
		resend_email();
	}
	
	// call the reset password function if the button is clicked
	if (isset($_POST['send_email'])) {
		reset_password();
	}

	// call the login() function if login btn is clicked
	if (isset($_POST['login'])) {
		login();
	}

	// call the register_farmer() function if the Forms btn is clicked
	if (isset($_POST['register_farmer'])) {
		register_farmer();
	}

	// resend email
	function resend_email() {
		global $db;
		
		$verify_code = md5(rand(0,1000));
		$email = $_SESSION['user']['email'];
		$query = "UPDATE users SET verify_code='$verify_code' WHERE email='$email'";
		if (!mysqli_query($db, $query)) {
			var_dump(mysqli_error($db));
		} else {
			verification_email_content($email, $verify_code);
		}
	}

	function reset_password() {
		global $db, $errors, $email;

		// receive input from the form. 
		// call the e() function to escape email value
		$email = e(e_valid($_POST['email']));
		$verify_code = md5(uniqid(rand(1000,3000)));

		// form validation: ensure that the form is correctly filled
		if (empty($email)) { 
			array_push($errors, "Email is required"); 
		}

		$query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results)===1) {
			password_reset_email_content($email, $verify_code);
			alert_message("Check your email for a link to reset your password. If it doesn’t appear within a few minutes, check your spam folder.", "success");

			$query = "CREATE EVENT expireevent ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 3 HOURS DO UPDATE users SET password_reset_code='' WHERE email='$email' LIMIT 1";

			if (!mysqli_query($db, $query)) {
				mysqli_error($db);
			}
		} else {
			array_push($errors, "User with email address does not exist!");
		}
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
		if (count($errors) === 0) {
			$password = md5($password);

			$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) === 1) { // user found
				// check if user is administrator or agent
				$logged_in_user = mysqli_fetch_assoc($results);
				if ($logged_in_user['verified']!=="1") {
					alert_message("Your account has not been activated! Click on the link sent to your email.", "danger");
				} else {
					if ($logged_in_user['user_type'] === 'administrator') {

						$_SESSION['user'] = $logged_in_user;
						$_SESSION['success']  = "You are now logged in as Administrator";
						assign_project($_SESSION['user']);
						update_projects($_SESSION['user']);
						redirect_url();
					}else{
						$_SESSION['user'] = $logged_in_user;
						$_SESSION['success']  = "You are now logged in as a Collaborator";
						update_projects($_SESSION['user']);
						redirect_url();
					}
				}				
			} else {
				array_push($errors, "Wrong username/password combination");
			}
		}

	}

	// set project id of the project the user is on
	function assign_project($user) {
		global $db;
        
		$username = $user['username'];
		$project_name = $user['project_name'];
		$project_arr = explode(', ', $project_name);
		$project_ids = array();
		
		foreach ($project_arr as $key => $project) {
			$query = "SELECT project_id FROM projects WHERE project_name='$project' LIMIT 1";
			$results = mysqli_query($db, $query);
			while ($projects = mysqli_fetch_assoc($results)) {
				array_push($project_ids, $projects['project_id']);
				// remember to confirm this...
			}
		}
		// var_dump($project_ids);
		
		$project_id = implode(', ', $project_ids);
		$new_query = "UPDATE users SET project_id='$project_id' WHERE username='$username' LIMIT 1";
		if (!mysqli_query($db, $new_query)) {
			var_dump(mysqli_error($db));
		}

		$new_query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
		$results = mysqli_query($db, $new_query);
		
		if (mysqli_num_rows($results) === 1) {
			$_SESSION['user'] = mysqli_fetch_assoc($results); // put logged in user in session
		}

		$user = $_SESSION['user'];
	}

	function update_projects($user) {
		global $db, $project_name;
		
		$project_name_arr = explode(', ', $user['project_name']);
		// Insert details of each project into the database
		foreach ($project_name as $key=>$project) {
			$project_key	 = md5($key);
			$project_id 	 = md5(base_convert(cos($key),10,5));
			$project_owner = $user['user_type']==='administrator'&&in_array($project, $project_name_arr, true) ? $user['username'] : "";
			$responses 		 = 0;
			$status 			 = "Published";
			$collaborators = "";
			$present_user	 = $user['username'];
			
			$query = "INSERT INTO projects (project_key, project_name, project_id, no_of_responses, status, collaborators, project_owner, present_user) VALUES ('$project_key', '$project', '$project_id', '$responses', '$status', '$collaborators', '$project_owner', '$present_user') ON DUPLICATE KEY UPDATE project_name = VALUES(project_name), project_id = VALUES(project_id), project_owner = VALUES(project_owner), present_user = VALUES(present_user)";
			if (!mysqli_query($db, $query)) {
				var_dump(mysqli_error($db));
			}
		}
	}

	// redirect to previous locaton on login
	function redirect_url()	{
		if (isset($_GET['nexturl'])&&isset($_GET['id'])) {
			// var_dump($_GET['nexturl'].'&id='.$_GET['id']);
			$new_url = e($_GET['nexturl'].'&id='.$_GET['id']);
			header("Location: {$new_url}");
		} else {
			header("Location: ./forms.php");
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

	function check_and_save_file($fs,$fn,$ft,$f_n) {
		global $errors;
		// Check size
		if($fs > 2097152){
			array_push($errors, "File size must be less than 2 MB");
		}

		$dir_name				= "./assets/images/".$f_n;
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

	function getFileProp($files,$folder_name) {
		$fileNameArr = array();
		if (isset($files)) {
			foreach ($files["tmp_name"] as $key => $tmp_name) {
				$file_name = $key.$files["name"][$key];
				$file_size = $files["size"][$key];
				$file_tmp  = $files['tmp_name'][$key];
				$file_type = $files['type'][$key];

				array_push($fileNameArr, check_and_save_file($file_size,$file_name,$file_tmp,$folder_name));
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
		
		$user					  = $_SESSION['user']['username'];
		$land_area		  = $land_size." ".$land_unit;
		$produce_volume = $produce_size." ".$produce_unit;
		$new_farmer_pic = check_and_save_file($farmer_pic_size,$farmer_pic_name,$farmer_pic_tmp,"farmers_pictures");
		$new_farm_pic	  = implode(",", getFileProp($farm_pic,"farmers_pictures"));
		
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
	