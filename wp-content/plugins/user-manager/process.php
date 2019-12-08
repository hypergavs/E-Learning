<?php
require_once '../../../wp-load.php';
$error = 0;
$e_msg = "";
if($_POST['GMUsername'] && wp_verify_nonce($_POST['secure'], 'GMLogin-Nonce')){

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
			  if($user->roles[0]=="salesman"){
				  echo home_url()."/point-of-sale";
			  }elseif($user->roles[0]=="cashier"){
				  echo home_url()."/cashier";
			  }else{
				  echo home_url();
			  }
		  }else{
			echo create_error_msg($e_msg);	  
		}
}else{
   echo "Error: Username is Empty";
}
	
?>