<?php
require_once '../../../wp-load.php';
global $wpdb;
$error = 0;
$error_msg = "";
date_default_timezone_set("Asia/Manila");

if(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureRemovePost")){
	
	//check if_file id exist
	$check_id = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_posts Where id=%d and status=%s", $_POST['post_id'], 'Active'));
	if(!$check_id){
		$error += 1;
		$error_msg .="Error: Post doesn't exist!;";	
	}
	
	
	
	if($error==0){
	
	$qry = $wpdb->query($wpdb->prepare("Update gm_tbl_posts Set status=%s Where id=%d", "Removed", $check_id->id));
		if($qry){
			echo "Post Removed!";
		}else{
			echo create_error_msg("Error: Something went wrong while trying to remove the post. please try again.");	
		}
		
	}else{
		echo create_error_msg($error_msg);	
	}
	
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureUpdatePost")){

	//check if_file id exist
	$check_id = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_posts Where id=%d", $_POST['post_id']));
	if(!$check_id){
		$error += 1;
		$error_msg .="Error: Post doesn't exist!;";	
	}
	
	
	if($_FILES['attachment']['name'][0]){
		$file_count = count(reArrayFiles($_FILES['attachment']));
		$files = reArrayFiles($_FILES['attachment']);
		$file_loc = "";
		for($a=0;$a<$file_count;$a++){
			$upload_max_size = ini_get('upload_max_filesize');
			$file_size = $files[$a]["size"] / 1000000;
			
			$upload_max_size = ini_get('upload_max_filesize');
			$file_size = $files[$a]["size"] / 1000000;
			if (number_format($file_size,5) > $upload_max_size){
				$error += 1;
				$err_message .= 'Error: File is too large!, Max upload size is '. number_format(($upload_max_size / 1000000), 2) .'MB';
			}
			
			$allowed_file_type = array("jpg","png","pdf","docx","doc","ppt","pptx","xls","xlsx","txt","rar","zip");
			
			$target_dir = "uploads/";
			$target_file = $target_dir . get_current_user_id() ."_". time()."_".basename($files[$a]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
			if(!array_search($imageFileType, $allowed_file_type)){
				$error+=1;
				$error_msg .="Error: Invalid file type!, The system only allow JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLS, XLSX, TXT, RAR and ZIP File. Please check your file.;";	
			}
			
			if($error==0){
				if (!move_uploaded_file($files[$a]["tmp_name"], $target_file)) {
					$error += 1;
					$err_message .= 'Error: There was an error uploading your file;';
				}else{
					$file_loc .= $target_file.";";
				}
			}
			
			
		}
		
	}else{
		$file_loc = "";	
	}
	
	if(!$_POST['post_body']){
		$error += 1;
		$err_message .= 'Error: Body text is required!;';
	}
	
	if($error==0){
			$data = array(
						'post_type'	=>$_POST['post_type'],
						'post_to'	=>$_POST['post_to'],
						'body_text'	=>$_POST['post_body'],
						'can_comment'=>$_POST['can_comment'],
						'added_by'	=>get_current_user_id()
					);
			$where = array(
						'id'		=>$_POST['post_id']
				);
			$format = array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
					);
			$where_format = array(
						'%d'
					);
					
			//update attachements
			$attachment_temp = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_attachment_temp Where trans_id=%s", $_POST['trans_id']));
			if($attachment_temp){
				foreach($attachment_temp as $attach){
					$wpdb->query($wpdb->prepare("Delete From gm_tbl_attachments Where id=%d", $attach->file_id));
					$wpdb->query($wpdb->prepare("Delete From gm_tbl_attachment_temp Where id=%d", $attach->id));
				}	
			}
					
					
			$update_post = $wpdb->update('gm_tbl_posts',$data, $where, $format, $where_format);
			
			
			$explode_link = explode(";",rtrim($file_loc,';'));
			
			$inser_post_id = $wpdb->insert_id;
			if($file_loc!=""){
				for($a=0;$a<count($explode_link);$a++){
					$attachment_data = array(
							'post_id'	=> $_POST['post_id'],
							'file_loc'	=> $explode_link[$a],
							'added_by'	=> get_current_user_id()
						);
					$attachment_format = array(
						'%d','%s','%d'
					);
					
					$insert_attachments = $wpdb->insert('gm_tbl_attachments',$attachment_data, $attachment_format);
				}
			}
			
			
			
			
			if($update_post||$insert_attachments){
				echo "Post has been updated!";
			}elseif($update_post===0){
				echo "Post has been updated!";
			}else{
				echo "Error: Something went wrong while trying to post the form. Please try again.";	
			}
		}else{
			echo create_error_msg($err_message);	
		}
	
	
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureRemoveAttachment")){
	
	
	//check if_file id exist
	$check_id = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_attachments Where id=%d", $_POST['file_id']));
	if(!$check_id){
		$error += 1;
		$error_msg .="Error: File doesn't exist!;";	
	}
	
	if($error==0){
		$wpdb->query($wpdb->prepare("Insert Into gm_tbl_attachment_temp (trans_id,file_id,removed_by) VALUES (%s,%d,%d)", $_POST['trans_id'], $check_id->id, get_current_user_id()));	
	}else{
		echo create_error_msg($error_msg);	
	}
	
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureAddPost")){
	
	
	//last post
	$user_last_post = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_posts Where added_by=%d ORDER by id DESC", get_current_user_id()));
	
	$date_time_now = date("Y-m-d H:i:s");
	$last_post_time = $user_last_post->date_added;
	
	
	$date1 = date_create($date_time_now);
	$date2 = date_create($last_post_time);
	
	$diff=date_diff($date1,$date2);
	
	
	
	if($diff->i>=5||!$user_last_post){
		
	
	
	if($_FILES['attachment']['name'][0]){
		$file_count = count(reArrayFiles($_FILES['attachment']));
		$files = reArrayFiles($_FILES['attachment']);
		$file_loc = "";
		for($a=0;$a<$file_count;$a++){
			$upload_max_size = ini_get('upload_max_filesize');
			$file_size = $files[$a]["size"] / 1000000;
			
			$upload_max_size = ini_get('upload_max_filesize');
			$file_size = $files[$a]["size"] / 1000000;
			if (number_format($file_size,5) > $upload_max_size){
				$error += 1;
				$err_message .= 'Error: File is too large!, Max upload size is '. number_format(($upload_max_size / 1000000), 2) .'MB';
			}
			
			$allowed_file_type = array("jpg","png","pdf","docx","doc","ppt","pptx","xls","xlsx","txt","rar","zip");
			
			$target_dir = "uploads/";
			$target_file = $target_dir . get_current_user_id() ."_". time()."_".basename($files[$a]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
			if(!array_search($imageFileType, $allowed_file_type)){
				$error+=1;
				$error_msg .="Error: Invalid file type!, The system only allow JPEG, PNG, PDF, DOCX, DOC, PPT, PPTX, XLS, XLSX, TXT, RAR and ZIP File. Please check your file.;";	
			}
			
			if($error==0){
				if (!move_uploaded_file($files[$a]["tmp_name"], $target_file)) {
					$error += 1;
					$err_message .= 'Error: There was an error uploading your file;';
				}else{
					$file_loc .= $target_file.";";
				}
			}
			
			
		}
		
	}else{
		$file_loc = "";	
	}
	
	if(!$_POST['post_body']){
		$error += 1;
		$err_message .= 'Error: Body text is required!;';
	}
	
	if($error==0){
			$data = array(
						'post_type'	=>$_POST['post_type'],
						'post_to'	=>$_POST['post_to'],
						'body_text'	=>$_POST['post_body'],
						'can_comment'=>$_POST['can_comment'],
						'added_by'	=>get_current_user_id()
					);
			$format = array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
					);
					
			$insert_post = $wpdb->insert('gm_tbl_posts',$data, $format);
			
			
			$explode_link = explode(";",rtrim($file_loc,';'));
			
			$inser_post_id = $wpdb->insert_id;
			
			if($file_loc!=""){
				for($a=0;$a<count($explode_link);$a++){
					$attachment_data = array(
							'post_id'	=> $inser_post_id,
							'file_loc'	=> $explode_link[$a],
							'added_by'	=> get_current_user_id()
						);
					$attachment_format = array(
						'%d','%s','%d'
					);
					
					$insert_attachments = $wpdb->insert('gm_tbl_attachments',$attachment_data, $attachment_format);
				}
			}
			
			
			
			if($insert_post){
				echo "Post has been submitted!";
			}else{
				echo "Error: Something went wrong while trying to post the form. Please try again.";	
			}
		}else{
			echo create_error_msg($err_message);	
		}
		
		
	}else{
		echo create_error_msg("Error: Can't post simultaneously, posting should have 5 minutes interval between each post.");	
	}
	
}else{
	echo create_error_msg("Error: Access Denied!");	
}



?>