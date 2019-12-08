<?php
/*
Plugin Name: User Manager
Version: 1.0
Author: GM

Copyright 2018-2019 GM.
*/
show_admin_bar(false);





function user_login(){
ob_start();
global $wpdb;
?>
<script language="javascript">
$(document).ready(function(e) {
    function notif(state, msg){		
		$(".notif").removeClass("error");
		$(".notif").removeClass("success");
		$(".notif").addClass(state).html(msg).show();
	}
	$("#login-form").submit(function(e){
		e.preventDefault();
		var formData = new FormData();
		$(".form-control", this).each(function(index, element) {
			formData.append($(this).attr("name"), $(this).val());
		});
		$.ajax({
			contentType:false,
			processData:false,
			url:$(this).attr("action"),
			type:"POST",
			data:formData,
			beforeSend: function(){
				$(".gm-progress").fadeIn(200);
			},
			success: function(data){
				if(data.substring(0,4)=="http"){
					notif("success", "Successfuly Logged In. Please Wait..");
					setTimeout(function(){
						window.location=data;
					},3000);
				}else{
					notif("error", data);	
				}
			},
			complete: function(){
				$(".gm-progress").fadeOut(1000);
			}
		});
	});
});
</script>




<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      
      <form action="<?php echo plugin_dir_url( __FILE__ ) ?>process-users.php" method="post" id="login-form">
      <div class="modal-body">
        
        
        <div class="row">
            <div class="col-md-12">
                <div class="row justify-content-md-center">
                    
                    <div class="col-md-10 login-wrapper">
               
                      
                        <div class="row justify-content-md-center">
                            <div class="md-form col-md-12">
                                <input type="text" name="GMUsername" class="form-control" id="GMUsername" autofocus />
                                <label for="GMUsername"><i class="fa fa-user"></i> Username: </label>
                            </div>
                        </div>
                        
                        <div class="row justify-content-md-center">
                            <div class="md-form col-md-12">
                                <input type="password" name="password1" class="form-control" />
                                <label for="GMPassword"><i class="fa fa-lock"></i> Password: </label>
                            </div>
                        </div>
                        
                        <input type="hidden" name="secure" value="<?php echo wp_create_nonce('GMLogin-Nonce'); ?>" class="form-control"/>
                        <div class="row justify-content-md-center">
                            <div class="col-md-12">
                                <div class="notif"></div>
                            </div>
                        </div>
                       
                </div>
            </div>
        </div>
        
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </div>
    </form>
  </div>
</div>



	
<?php

ob_end_flush();
}
add_shortcode("user-login", "user_login");







function users(){
ob_start();
global $wpdb;
?>

<div class="row">
	<div class="col-md-12"><h3><i class="fa fa-users"></i> Users</h3></div>
</div>
<hr />
<?php
if(only_admin()){
if(isset($_GET['act'])&&$_GET['act']&&$_GET['act']=='edit'){
	if(isset($_GET['user'])&&$_GET['user']){
	$account_info = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where (ID=%d and ID!=%d) or user_login=%s", $_GET['user'],1, $_GET['user']
	));
		if($account_info){
			$user_info = get_user_by("ID", $account_info->ID);
			$rol = "";
			foreach($user_info->roles as $role){
				$rol .= $role.",";	
			}
			if($res->user_status==1){
				$color = "#FFB3B5";
			}else{
				$color = "";
			}
			$gender = get_user_meta($account_info->ID, "gender", true);
			$civil_status = get_user_meta($account_info->ID, "civil_status", true);
			$contact = get_user_meta($account_info->ID, "contact", true);
			$middle_name = get_user_meta($account_info->ID, "middle_name", true);
			$suffix = get_user_meta($account_info->ID, "suffix", true);
			$b_day = get_user_meta($account_info->ID, "bday", true);
			$now = date_create("NOW");
			$age = date_diff($now, date_create($b_day));
			?>
            <script language="javascript">
            $(document).ready(function(e) {
                function notif(state, msg){		
					$(".notif").removeClass("error");
					$(".notif").removeClass("success");
					$(".notif").addClass(state).html(msg).show();
				}
				$("#update-user-form").submit(function(e){
					e.preventDefault();
					var formData = new FormData();
					$(".form-control", this).each(function(index, element) {
						formData.append($(this).attr("name"), $(this).val());
					});
					$.ajax({
						contentType:false,
						processData:false,
						url:$(this).attr("action"),
						type:"POST",
						data:formData,
						beforeSend: function(){
							$(".gm-progress").fadeIn(200);
						},
						success: function(data){
							if(data.match('Error')){
								notif("error", data);
							}else{
								notif("success", data);
							}
						},
						complete: function(){
							$(".gm-progress").fadeOut(1000);
						}
					});
				});
            });
            </script>
			<div class="row">
				<div class="col-md-12"><h5><i class="fa fa-pencil"></i> Edit User</h5></div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12"><strong>Basic Info.</strong></div>
					</div>
					<form action="<?php echo plugin_dir_url(__FILE__) ?>process-users.php" method="post" id="update-user-form" />
					<div class="row">
						<div class="col-md-4">
							<label>First Name:</label>
							<input type="text" placeholder="First Name" value="<?php echo $user_info->first_name ?>" name="first_name" class="form-control" required autofocus />
						</div>
						<div class="col-md-3">
							<label>Middle Name:</label>
							<input type="text" placeholder="Middle Name" value="<?php echo $middle_name ?>" name="middle_name" class="form-control" required />
						</div>
						<div class="col-md-3">
							<label>Last Name:</label>
							<input type="text" placeholder="Last Name" value="<?php echo $user_info->last_name ?>" name="last_name" class="form-control" required />
						</div>
						<div class="col-md-2">
							<label>Suffix(Optional):</label>
							<input type="text" placeholder="Suffix" value="<?php echo $suffix; ?>" name="suffix" class="form-control" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label>Gender:</label>
							<select name="gender" class="form-control">
								<option value="">Select Gender</option>
								<option value="Male" <?php echo $gender=="Male" ? "selected" : "" ?>>Male</option>
								<option value="Female" <?php echo $gender=="Female" ? "selected" : "" ?>>Female</option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Civil Status:</label>
							<select name="civil_status" class="form-control">
								<option value="">Select Status</option>
								<option value="Single" <?php echo $civil_status=="Single" ? "selected" : "" ?>>Single</option>
								<option value="Married" <?php echo $civil_status=="Married" ? "selected" : "" ?>>Married</option>
								<option value="Widow" <?php echo $civil_status=="Widow" ? "selected" : "" ?>>Widow</option>
								<option value="Separated" <?php echo $civil_status=="Separated" ? "selected" : "" ?>>Separated</option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Birth Date:</label>
							<input type="text" placeholder="Birth Date" value="<?php echo date("m/d/Y", strtotime($b_day)) ?>" name="b_day" class="form-control datepicker" />
						</div>
						<div class="col-md-4">
							<label>Contact: </label>
							<input type="text" placeholder="Contact" value="<?php echo $contact ?>" name="contact" class="form-control" />
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-md-12"><strong>Account Info.</strong></div>
					</div>
                    <div class="row">
                    	<div class="col-md-8 offset-md-4"><span class="note">If you wish not to change password, Just leave it blank.</span></div>
                    </div>
					<div class="row">
						<div class="col-md-4">
							<label>Username: <span class="note">(Usernames cannot be changed.)</span></label>
							<input type="text" name="username" value="<?php echo $user_info->user_login ?>" readonly placeholder="Username" class="form-control" required />
						</div>
						<div class="col-md-4">
							<label>Password: </label>
							<input type="password" name="password1" placeholder="Password" class="form-control" />
						</div>
						<div class="col-md-4">
							<label>Verify Password: </label>
							<input type="password" name="password2" placeholder="Re-type Password" class="form-control" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<label>Role: </label>
							<select class="form-control" name="user-role" required>
								<option value="">Select Role</option>
								<?php
								global $wp_roles;
								
								foreach($wp_roles->roles as $key=>$value){
								$selected = rtrim($rol,",")==$key ? "selected" : '';
								echo "<option value='".$key."' ".$selected.">";
										echo ucfirst($key);
								echo '</option>';	
								}
							?>
							</select>
						</div>
						<div class="col-md-4">
							<label>Email: </label>
							<input type="text" name="email1" value="<?php echo $user_info->user_email; ?>" placeholder="Email Address" class="form-control" />
						</div>
						<div class="col-md-4">
							<label>Verify Email: </label>
							<input type="text" name="email2" value="<?php echo $user_info->user_email; ?>" placeholder="Re-type Email Address" class="form-control" />
							
                            <input type="hidden" name="user_id" value="<?php echo $account_info->ID ?>" class="form-control" />
                            <input type="hidden" name="secure" value="<?php echo wp_create_nonce("secureUpdateUser") ?>" class="form-control" />
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-md-11 notif"></div>
					</div>
					<div class="row">
						<div class="col-md-6 offset-md-6">
							<div class="row">
								<div class="col-md-6">
                                	<a href="<?php echo '?user='.$account_info->ID.'&act=view'; ?>">
									<button type="button" class="btn btn-secondary btn-block"><i class="fa fa-reply"></i> Back</button>
                                    </a>
								</div>
								<div class="col-md-6">
									<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Update User</button>
								</div>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
			<?php
		}else{
			echo 'No such account!';
		}
	}else{
		echo 'User is not defined!';
	}
}elseif(isset($_GET['act'])&&$_GET['act']&&$_GET['act']=='view'){
	if(isset($_GET['user'])&&$_GET['user']){
	$account_info = $wpdb->get_row($wpdb->prepare(
	"Select * From gm_users Where (ID=%d and ID!=%d) or user_login=%s", $_GET['user'],1, $_GET['user']
	));
		if($account_info){
			$user_info = get_user_by("ID", $account_info->ID);
			$rol = "";
			foreach($user_info->roles as $role){
				$rol .= $role.",";	
			}
			if($res->user_status==1){
				$color = "#FFB3B5";
			}else{
				$color = "";
			}
			$gender = get_user_meta($account_info->ID, "gender", true);
			$civil_status = get_user_meta($account_info->ID, "civil_status", true);
			$contact = get_user_meta($account_info->ID, "contact", true);
			$middle_name = get_user_meta($account_info->ID, "middle_name", true);
			$suffix = get_user_meta($account_info->ID, "suffix", true);
			$b_day = get_user_meta($account_info->ID, "bday", true);
			$now = date_create("NOW");
			$age = date_diff($now, date_create($b_day));
			?>
            <script language="javascript">
			$(document).ready(function(e) {
				function notif(state, msg){		
					$(".notif").removeClass("error");
					$(".notif").removeClass("success");
					$(".notif").addClass(state).html(msg).show();
				}
				var process_to = "<?php echo plugin_dir_url(__FILE__) ?>process-users.php";
				
				$("#suspend").click(function(){
					var formData = new FormData;
					formData.append("secure", "<?php echo wp_create_nonce("secureUserSuspend") ?>");
					formData.append("user_id", "<?php echo $account_info->ID; ?>");
					$.ajax({
						contentType:false,
						processData:false,
						url:process_to,
						type:"POST",
						data:formData,
						beforeSend: function(){
							$(".gm-progress").fadeIn(200);
						},
						success: function(data){
							if(data.match('Error')){
								notif("error", data);
							}else{
								notif("success", data);
								$("#suspend").prop("disabled", true);
								$("#status").html("Suspended");
								//$("form").trigger("reset");
							}
						},
						complete: function(){
							$(".gm-progress").fadeOut(1000);
						}
					});
				});
				$("#activate").click(function(){
					var formData = new FormData;
					formData.append("secure", "<?php echo wp_create_nonce("secureUserActivate") ?>");
					formData.append("user_id", "<?php echo $account_info->ID; ?>");
					$.ajax({
						contentType:false,
						processData:false,
						url:process_to,
						type:"POST",
						data:formData,
						beforeSend: function(){
							$(".gm-progress").fadeIn(200);
						},
						success: function(data){
							if(data.match('Error')){
								notif("error", data);
							}else{
								notif("success", data);
								$("#activate").prop("disabled", true);
								$("#status").html("Active");
								//$("form").trigger("reset");
							}
						},
						complete: function(){
							$(".gm-progress").fadeOut(1000);
						}
					});
				});
			});
			</script>
			<div class="row">
				<div class="col-md-12"><h5><i class="fa fa-user-circle"></i> User Info.</h5></div>
			</div>
			<div class="row" id="user-info">
				<div class="col-md-12">
					
					<div class="row">
						<div class="col-md-3">Username: <?php echo $user_info->user_login; ?></div>
						<div class="col-md-5">Account Name: <?php echo ucfirst($user_info->last_name).', '.ucfirst($user_info->first_name).' '.ucfirst($suffix).' '.ucfirst($middle_name); ?></div>
						<div class="col-md-4">Date Added: <?php echo date("F d, Y h:i A", strtotime($user_info->user_registered)); ?></div>
                    </div>
					<div class="row">
						<div class="col-md-3">Gender: <?php echo $gender; ?></div>
						<div class="col-md-3">Civil Status: <?php echo $civil_status; ?></div>
						<div class="col-md-3">Birthdate: <?php echo date("M d, Y", strtotime($b_day)); ?></div>
						<div class="col-md-3">Age: <?php print_r( $age->y ); ?> Years Old</div>
					</div>
					<div class="row">
						<div class="col-md-4">Contact: <?php echo $contact; ?></div>
						<div class="col-md-4">Role: <?php echo rtrim($rol,","); ?></div>
                        <div class="col-md-4">Status: <span id="status"><?php echo $account_info->user_status==1 ? "Suspended" : "Active" ?></span></div>
					</div>
                    <div class="row">
						<div class="col-md-12">Email Address: <?php echo $user_info->user_email; ?></div>
					</div>
					
					
				</div>
			</div>
            <br/>
            <div class="row">
                <div class="col-md-11  notif success"></div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-6 user-info-buttons">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="<?php echo home_url()."/users/" ?>">
                            <button type="button" class="btn btn-secondary btn-lg btn-block pull-right"><i class="fa fa-reply"></i> Back</button>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <?php
							echo $account_info->user_status==0 ?
							'<button type="button" class="btn btn-danger btn-lg btn-block" id="suspend"><i class="fa fa-pause"></i> Suspend</button>' :
							'<button type="button" class="btn btn-green btn-lg btn-block" id="activate"><i class="fa fa-pause"></i> Activate</button>'
							?>
                        </div>
                        <div class="col-md-4">
                        	<a href="?user=<?php echo $user_info->ID ?>&act=edit">
                            <button type="button" class="btn btn-yellow btn-lg btn-block" id="edit-user"><i class="fa fa-pencil"></i> Edit</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            		
                	
			<br/><hr />
			<?php
		}else{
			echo 'No such account!';	
		}
	}else{
		echo 'User is not defined!';	
	}
}else{
?>
<div class="row">
    <div class="col-md-12">
    <table class="table table-condensed datatable">
        <thead class="thead-light">
            <th>#</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Birthday</th>
            <th>Civil Status</th>
            <th>Username</th>
            <th>Email Address</th>
            <th>Role</th>
        </thead>
        <?php
        global $wpdb;
        $results = $wpdb->get_results("Select * From gm_users Where ID!=1 ORDER by id DESC");
        foreach($results as $res){
            $rol = "";
            $info = get_user_by("ID", $res->ID);
            foreach($info->roles as $role){
                $rol .= $role.",";	
            }
            if($res->user_status==1){
                $color = "#FFB3B5";
            }else{
                $color = "";
            }
			$gender = get_user_meta($res->ID, "gender", true);
			$civil_status = get_user_meta($res->ID, "civil_status", true);
			$contact = get_user_meta($res->ID, "contact", true);
			$middle_name = get_user_meta($res->ID, "middle_name", true);
			$suffix = get_user_meta($res->ID, "suffix", true);
			$b_day = get_user_meta($res->ID, "bday", true);
            echo '
            <tr bgcolor="'.$color.'">
                <td>'.$info->ID.'</td>
                <td>
					<a href="?user='.$info->id.'&act=view">
					'.ucfirst($info->last_name).', '.ucfirst($info->first_name).' '.ucfirst($suffix).' '.ucfirst($middle_name).'
					</a>
				</td>
				<td>'.$gender.'</td>
				<td>'.date("F d, Y", strtotime($b_day)).'</td>
				<td>'.$civil_status.'</td>
                <td>'.$res->user_login.'</td>
                <td>'.$res->user_email.'</td>
                <td>'.rtrim($rol,",").'</td>
            </tr>
            ';
        }
        ?>
    </table>
    </div>
</div>
<?php
}
}else{
?>
<div class="row justify-content-md-center">
	<div class="col-md-8 notif error" style="display:block;"><i class="fa fa-exclamation-triangle"></i> Oooopppss.. You are not allowed here.</div>
</div>
<?php	
}
ob_end_flush();
}
add_shortcode("users", "users");


function add_system_user(){
ob_start();
if(only_admin()){
?>
<script language="javascript">
$(document).ready(function(e) {
	function notif(state, msg){		
		$(".notif").removeClass("error");
		$(".notif").removeClass("success");
		$(".notif").addClass(state).html(msg).show();
	}
    $("#add-user-form").submit(function(e){
		e.preventDefault();
		var formData = new FormData();
		$(".form-control", this).each(function(index, element) {
            formData.append($(this).attr("name"), $(this).val());
        });
		$.ajax({
			contentType:false,
			processData:false,
			url:$(this).attr("action"),
			type:"POST",
			data:formData,
			beforeSend: function(){
				$(".gm-progress").fadeIn(200);
			},
			success: function(data){
				if(data.match('Error')){
					notif("error", data);
				}else{
					notif("success", data);
					$("form").trigger("reset");
				}
			},
			complete: function(){
				$(".gm-progress").fadeOut(1000);
			}
		});
	});
});
</script>
<div class="row">
	<div class="col-md-12"><h3><i class="fa fa-user-plus"></i> Add User</h3></div>
</div>
<hr />
<div class="row">
	<div class="col-md-12">
    	<div class="row">
        	<div class="col-md-12"><strong>Basic Info.</strong></div>
        </div>
        <form action="<?php echo plugin_dir_url(__FILE__) ?>process-users.php" method="post" id="add-user-form" />
        <div class="row">
            <div class="col-md-4">
                <label>First Name:</label>
                <input type="text" placeholder="First Name" name="first_name" class="form-control" required autofocus />
            </div>
            <div class="col-md-3">
                <label>Middle Name:</label>
                <input type="text" placeholder="Middle Name" name="middle_name" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label>Last Name:</label>
                <input type="text" placeholder="Last Name" name="last_name" class="form-control" required />
            </div>
            <div class="col-md-2">
                <label>Suffix(Optional):</label>
                <input type="text" placeholder="Suffix" name="suffix" class="form-control" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <label>Gender:</label>
                <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Civil Status:</label>
                <select name="civil_status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Widow">Widow</option>
                    <option value="Separated">Separated</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Birth Date:</label>
                <input type="text" placeholder="Birth Date" name="b_day" class="form-control datepicker" />
            </div>
            <div class="col-md-4">
                <label>Contact: </label>
                <input type="text" placeholder="Contact" name="contact" class="form-control" />
            </div>
        </div>
        <br/>
        <div class="row">
        	<div class="col-md-12"><strong>Account Info.</strong></div>
        </div>
        <div class="row">
        	<div class="col-md-4">
            	<label>Username: </label>
                <input type="text" name="username" placeholder="Username" class="form-control" required />
            </div>
        	<div class="col-md-4">
            	<label>Password: </label>
                <input type="password" name="password1" placeholder="Password" class="form-control" required />
            </div>
        	<div class="col-md-4">
            	<label>Verify Password: </label>
                <input type="password" name="password2" placeholder="Re-type Password" class="form-control" required />
            </div>
        </div>
        <div class="row">
        	<div class="col-md-4">
            	<label>Role: </label>
                 
                <select class="form-control" name="user-role" required>
               		<option value="">Select Role</option>
                    <?php
				 	global $wp_roles;
					remove_role( 'editor' );
					foreach($wp_roles->roles as $key=>$value){
					echo '<option value="'.$key.'">';
							echo ucfirst($key);
					echo '</option>';	
					}
				?>
                </select>
            </div>
        	<div class="col-md-4">
            	<label>Email: </label>
            	<input type="text" name="email1" placeholder="Email Address" class="form-control" />
            </div>
            <div class="col-md-4">
            	<label>Verify Email: </label>
            	<input type="text" name="email2" placeholder="Re-type Email Address" class="form-control" />
                <input type="hidden" name="secure" value="<?php echo wp_create_nonce("addNewUser") ?>" class="form-control" />
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-11 notif"></div>
        </div>
        <div class="row">
        	<div class="col-md-6 offset-md-6">
            	<div class="row">
                	<div class="col-md-6">
                    	<a href="<?php echo home_url() ?>">
                    	<button type="button" class="btn btn-secondary btn-block"><i class="fa fa-reply"></i> Back</button>
                        </a>
                    </div>
                    <div class="col-md-6">
                    	<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Add User</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<?php
}else{
?>
<div class="row justify-content-md-center">
	<div class="col-md-8 notif error" style="display:block;"><i class="fa fa-exclamation-triangle"></i> Oooopppss.. You are not allowed here.</div>
</div>
<?php	
}
ob_end_flush();
}
add_shortcode("add-user", "add_system_user");



function user_manage_style() {
	wp_enqueue_style('user-manager-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
	
}
add_action('wp_enqueue_scripts', 'user_manage_style');
?>