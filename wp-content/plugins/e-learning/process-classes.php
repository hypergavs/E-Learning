<?php
require_once '../../../wp-load.php';
global $wpdb;
$error = 0;
$error_msg = "";
date_default_timezone_set("Asia/Manila");


if(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureJoinClass")){
	//authenticate class code
	$check_code = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where class_code=%s", $_POST['class_code']));
	if($check_code->class_code!=$_POST['class_code']){
		$error+= 1;
		$error_msg.='Error: Class not found!;';
	}
	
	//check if already join the group
	$check_if_joined = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_class_participant Where class_id=%d and participant_id=%d", $check_code->id, get_current_user_id()));
	if($check_if_joined){
		$error+= 1;
		$error_msg.='Error: You have already joined the class.;';	
	}
	
	
	if($error==0){
		$data = array(
			'participant_id'	=>get_current_user_id(),
			'class_id'			=>$check_code->id
		);
		$format = array('%d', '%d');
		
		$insert = $wpdb->insert('gm_tbl_class_participant', $data, $format);
		
		if($insert){
			echo $check_code->id;	
		}
	}else{
		echo create_error_msg($error_msg);	
	}
}elseif(isset($_POST['secure'])&&$_POST['secure']&&wp_verify_nonce($_POST['secure'], "secureAddClass")){
	
	$gen_class_code = false;
	$class_code = "";
	do{
		$code = substr(md5(time()),2,8);
		
		$check_code = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where class_code=%s", $code));
		if(!$check_code){
			$gen_class_code=true;
			$class_code=$code;
		}
	}while($gen_class_code==false);
	
	
	if($error==0){
		$data = array(
			'class_name'		=>	$_POST['class_name'],
			'class_section'		=>	$_POST['class_section'],
			'class_subject'		=>	$_POST['class_subject'],
			'class_room'		=>	$_POST['class_room'],
			'created_by'		=>	get_current_user_id(),
			'class_code'		=>	$code
		);
		$format = array('%s','%s','%s','%s','%d', '%s');
		$insert = $wpdb->insert('gm_tbl_classes', $data, $format);
		if($insert){
			$class = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where id=%d", $wpdb->insert_id));
			if(only_admin()){
				  echo '<tr>';	
					  echo '<td>'.$class->class_name.'</td>';
					  echo '<td>'.$class->class_section.'</td>';
					  echo '<td>'.$class->class_subject.'</td>';
					  echo '<td>'.$class->class_room.'</td>';
					  echo '<td>'.$class->class_code.'</td>';
					  echo '<td>'.get_user_by("ID", $class->created_by)->user_login.'</td>';
					  echo '<td>
								<a href="?step=view_class&class_id='.$class->id.'"><button class="btn btn-sm btn-block btn-primary">Visit</button></a>
							</td>';
				  echo '</tr>';
			  }else{
				  echo '<tr>';	
					  echo '<td>'.$class->class_name.'</td>';
					  echo '<td>'.$class->class_section.'</td>';
					  echo '<td>'.$class->class_subject.'</td>';
					  echo '<td>'.$class->class_room.'</td>';
					  echo '<td>'.$class->class_code.'</td>';
					  echo '<td>
								<a href="?step=view_class&class_id='.$class->id.'"><button class="btn btn-sm btn-block btn-primary">Visit</button></a>
							</td>';
				  echo '</tr>';
			  }
		}else{
			echo create_error_msg("Error: Problem saving record to the database.");	
		}
	}else{
		echo create_error_msg($error_msg);	
	}
	
}else{
	echo create_error_msg("Error: Access Denied!");	
}