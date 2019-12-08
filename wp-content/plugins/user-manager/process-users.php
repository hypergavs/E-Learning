<?php
require_once '../../../wp-load.php';
$error=0;
$error_msg = "";
if(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "addNewUser")){
	$first_name = $_POST['first_name'];
	$middle_name = $_POST['middle_name'];
	$last_name = $_POST['last_name'];
	$suffix = $_POST['suffix'];
	$gender = $_POST['gender'];
	$civil_status = $_POST['civil_status'];
	$b_day = $_POST['b_day'];
	$contact = $_POST['contact'];
	
	$username = $_POST['username'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$email1 = $_POST['email1'];
	$email2 = $_POST['email2'];
	$role = $_POST['user-role'];
	
	if($password1!=$password2){
		$error += 1;
		$error_msg .= "Error: Password did not match!;";
	}
	
	if($email1!=$email2){
		$error += 1;
		$error_msg .= "Error: Email address did not match!;";
	}
	
	if(username_exists($username)){
		$error += 1;
		$error_msg .= "Error: Username is already in use!;";
	}
	
	if(email_exists($email1)){
		$error += 1;
		$error_msg .= "Error: Email is already in use!;";	
	}
	
	
	if($error==0){
		$new_user_id = wp_insert_user(array(
		'user_login'		=> $username,
		'user_pass'	 		=> $password1,
		'user_email'		=> $email1,
		'first_name'		=> $first_name,
		'last_name'			=> $last_name,
		'user_registered'	=> date('Y-m-d H:i:s'),
		'role'				=> $role
			)
		);	
		
		if($new_user_id){
			add_user_meta( $new_user_id, "gender", $gender, false );
			add_user_meta( $new_user_id, "civil_status", $civil_status, false );
			add_user_meta( $new_user_id, "contact", $contact, false );
			add_user_meta( $new_user_id, "middle_name", $middle_name, false );
			add_user_meta( $new_user_id, "suffix", $suffix, false );
			add_user_meta( $new_user_id, "bday", $b_day, false );
			echo 'User successfuly added!';
		}else{
			echo 'Error: Something went wrong while trying to add user. Please try again';	
		}			
	}else{
		echo create_error_msg($error_msg);	
	}
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureUserSuspend")){
	if(!isset($_POST['user_id'])||!$_POST['user_id']){
		$error += 1;
		$error_msg .= 'Error: User is not defined!;';
	}
	
	$account_info = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where ID=%d or user_login=%s", $_POST['user_id'], $_POST['user_id']
	));
	
	if(!$account_info){
		$error += 1;
		$error_msg .= 'Error: No such account!;';	
	}
	
	
	if($error==0){
		$update_user = $wpdb->query($wpdb->prepare(
		"Update gm_users Set user_status=%d Where id=%d", 1, $account_info->ID
		));
		if($update_user){
			echo "Account Suspended!";
		}else{
			echo create_error_msg("Error: Something went wrong while trying to suspend user, Please try again.");	
		}
	}else{
		echo create_error_msg($error_msg);
	}
	
	
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureUserActivate")){
	if(!isset($_POST['user_id'])||!$_POST['user_id']){
		$error += 1;
		$error_msg .= 'Error: User is not defined!;';
	}
	
	$account_info = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where ID=%d or user_login=%s", $_POST['user_id'], $_POST['user_id']
	));
	
	if(!$account_info){
		$error += 1;
		$error_msg .= 'Error: No such account!;';	
	}
	
	
	if($error==0){
		$update_user = $wpdb->query($wpdb->prepare(
		"Update gm_users Set user_status=%d Where id=%d", 0, $account_info->ID
		));
		if($update_user){
			echo "Account Activated!";
		}else{
			echo create_error_msg("Error: Something went wrong while trying to activate user, Please try again.");	
		}
	}else{
		echo create_error_msg($error_msg);
	}
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureUpdateUser")){
	global $wpdb;
	$user_id = $_POST['user_id'];
	$first_name = $_POST['first_name'];
	$middle_name = $_POST['middle_name'];
	$last_name = $_POST['last_name'];
	$suffix = $_POST['suffix'];
	$gender = $_POST['gender'];
	$civil_status = $_POST['civil_status'];
	$b_day = $_POST['b_day'];
	$contact = $_POST['contact'];
	
	$username = $_POST['username'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$email1 = $_POST['email1'];
	$email2 = $_POST['email2'];
	$role = $_POST['user-role'];
	
	$is_user_exist = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where id=%d",
	$user_id
	));
	
	if(!$is_user_exist){
		$error += 1;
		$error_msg .= "Error: User doesn't exist!;";
	}
	
	if($password1!=$password2&&$password1&&$password2){
		$error += 1;
		$error_msg .= "Error: Password did not match!;";
	}
	
	if($email1!=$email2){
		$error += 1;
		$error_msg .= "Error: Email address did not match!;";
	}
	//check user login if exist 
	$check_username = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where user_login=%s and ID!=%d", $username, $is_user_exist->ID 
	));
	if($check_username){
		$error += 1;
		$error_msg .= "Error: Username is already in use!;";
	}
	$check_user_email = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where user_email=%s and ID!=%d", $email1, $is_user_exist->ID 
	));
	if($check_user_email){
		$error += 1;
		$error_msg .= "Error: Email is already in use!;";	
	}
	
	
	if($error==0){
		if($password1&&$password2){
			$update_user = wp_update_user( array( 
						  'ID'				 => $user_id,
						  'user_login'		 => $username,
						  'user_pass'	 	 => $password1,
						  'user_email'		 => $email1,
						  'first_name'		 => $first_name,
						  'last_name'		 => $last_name,
						  'role'			 => $role ) );
			update_user_meta( $user_id, "gender", $gender, false );
			update_user_meta( $user_id, "civil_status", $civil_status, false );
			update_user_meta( $user_id, "contact", $contact, false );
			update_user_meta( $user_id, "middle_name", $middle_name, false );
			update_user_meta( $user_id, "suffix", $suffix, false );
			update_user_meta( $user_id, "bday", $b_day, false );
			
			if($update_user){
				echo 'User Update successful.';
			}else{
				echo 'Error: Error happened while trying to update user account. Please try again.';
			}
		}else{
			$update_user = wp_update_user( array( 
						  'ID'				 => $user_id,
						  'user_login'		 => $username,
						  'user_email'		 => $email1,
						  'first_name'		 => $first_name,
						  'last_name'		 => $last_name,
						  'role'			 => $role ) );
			update_user_meta( $user_id, "gender", $gender, false );
			update_user_meta( $user_id, "civil_status", $civil_status, false );
			update_user_meta( $user_id, "contact", $contact, false );
			update_user_meta( $user_id, "middle_name", $middle_name, false );
			update_user_meta( $user_id, "suffix", $suffix, false );
			update_user_meta( $user_id, "bday", $b_day, false );
			if($update_user){
				echo 'User update successful.';
			}else{
				echo 'Error: Error happened while trying to update user account. Please try again.';
			}
		}
			
	}else{
		echo create_error_msg($error_msg);	
	}
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "GMLogin-Nonce")){
	// this returns the user ID and other info from the user name
	$user = get_user_by('login',$_POST['GMUsername']);
	//$user_role = key($user->jd_capabilities);
	$pass = $_POST['password1'];
	
	
	if(!isset($_POST['password1']) || $_POST['password1'] == '') {
		// if no password was entered
		$e_msg .= 'Error: Please enter a password.<br/><br/>';
		$error += 1;
	}
			// check the user's login with their password
	if(!$user || !wp_check_password( $pass, $user->user_pass, $user->ID)){
		$e_msg .= "Error: It's Either your Username or Password is Incorrect!<br/><br/>";
		$error += 1;	
	}
	
	$suspended = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where user_status=%d and id=%d", 1, $user->id
	));

	if($suspended->user_status==1){
		$e_msg .= "Error: User account is suspended.<br/><br/>";
		$error += 1;
	}
	
	if($error==0) {	
		  wp_setcookie($_POST['GMUsername'], $_POST['password1'], true);
		  wp_set_current_user($user->ID, $_POST['GMUsername']);	
		  do_action('wp_login', $_POST['GMUsername']);
		  
		  echo home_url()."/main/";
		  
	  }else{
		echo create_error_msg($e_msg);	  
	}
}else{
	echo create_error_msg("Error: Acess Denied!");
}