<?php
require_once '../../../wp-load.php';
global $wpdb;
$error = 0;
$error_msg = "";
date_default_timezone_set("Asia/Manila");
if(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureRemoveComment")){
	
	//check if_file id exist
	$check_id = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_comments Where id=%d and status=%s", $_POST['comment_id'], 'Active'));
	if(!$check_id){
		$error += 1;
		$error_msg .="Error: Post doesn't exist!;";	
	}
	
	
	if($error==0){
		$remove_comment = $wpdb->query($wpdb->prepare("Update gm_tbl_comments Set status=%s Where id=%d", 'Removed', $_POST['comment_id']));	
	}else{
		echo create_error_msg($error_msg);	
	}
	
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureAddComment")){
	
	if($error==0){
		$data = array(
			'post_id'		=>$_POST['post_id'],
			'body_text'		=>$_POST['comment'],
			'added_by'		=>get_current_user_id()
		);
		$format = array(
			'%d',
			'%s',
			'%d'
		);
		$qry = $wpdb->insert('gm_tbl_comments', $data, $format);
		if($qry){
			$res = $wpdb->get_row("Select * From gm_tbl_comments Where id=".$wpdb->insert_id."");	
			
			$user_info = get_user_by("ID", $res->added_by);
			$user_full_name = $user_info->first_name." ".$user_info->last_name."(".$user_info->user_login.")";
			
			$encode = '"comment_id":"'.$res->id.'",';
			$encode .= '"post_id":"'.$res->post_id.'",';
			$encode .= '"body_text":"'.$res->body_text.'",';
			$encode .= '"added_by":"'.$user_full_name.'",';
			$encode .= '"date_added":"'.date("M d, Y h:i A", strtotime($res->date_added)).'"';
			
			echo "{".$encode."}";
		}else{
			echo "Error";	
		}	
	}else{
		echo create_error_msg($error_msg);		
	}
	
	
}else{
	echo create_error_msg("Error: Access Denied!");	
}