<?php
/*
Plugin Name: E-Learning Main Plugin
Version: 1.0
Author: GM

Copyright 2018-2019 GM.
*/
show_admin_bar(false);


function students_home(){
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
	
	
    $("#join-class").submit(function(e){
		e.preventDefault();
		var formData = new FormData;
		$(".form-control", this).each(function(index, element) {
            formData.append($(this).attr("name"), $(this).val());
        });	
		$.ajax({
			url:$(this).attr("action")+".php",
			data:formData,
			type:"POST",
			processData:false,
			contentType:false,
			success: function(data){
				if(data.match('Error')){
					notif("error", data);
				}else{
					notif("success", "Access Granted!");
					setTimeout(function(){
						$(".modal").modal("toggle");
						window.location="<?php echo site_url() ?>/classes/?step=view_class&class_id="+data;
					},3000);
					
					
				}	
			}
		});
	});
});
</script>

<div class="row">
	<div class="col-md-6">
    </div>
    <div class="col-md-6">
    	<button class="btn btn-elegant pull-right" data-toggle="modal" data-target="#joinClassModal" type="button">Join Class</button>
    </div>
</div>





<!-- Central Modal Small -->
<div class="modal fade" id="joinClassModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <!-- Change class .modal-sm to change the size of the modal -->
    <div class="modal-dialog modal-md" role="document">


      <div class="modal-content">
      <form action="<?php echo plugin_dir_url(__FILE__) ?>process-classes" method="post" id="join-class">
        <div class="modal-header">
          <h4 class="modal-title w-100" id="myModalLabel">Join Class</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row justify-content-md-center">
          	<div class="col-md-10">
            	<input type="hidden" value="<?php echo wp_create_nonce("secureJoinClass") ?>" name="secure" class="form-control" />
            	<input type="text" name="class_code" placeholder="Enter Class Code" class="form-control" />
            </div>
          </div>
          
          <div class="row justify-content-md-center">
          	<div class="col-md-10 notif">
            	
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Join</button>
        </div>
      </form>
      </div>
    </div>
  </div>
  <!-- Central Modal Small -->
  
  
<div class="row">
	<div class="col-md-12">
    	<hr />
    </div>
</div>

<?php do_shortcode("[posts]"); ?>

<?php
ob_end_flush();
}
add_shortcode("stud-home", "students_home");


function teachers_home(){
ob_start();
global $wpdb;



do_shortcode("[posts]");

ob_end_flush();
}
add_shortcode("teachers-home", "teachers_home");


function admin_home(){
ob_start();
global $wpdb;


do_shortcode("[posts]");


ob_end_flush();
}
add_shortcode("admin-home", "admin_home");





function classes(){
ob_start();
global $wpdb;

if(isset($_GET['step'])&&$_GET['step']&&$_GET['step']=='view_class_students'){
	//check if class exists
	$class = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where id=%d", $_GET['class_id']));
	if($class){
	?>	
		<div class="row justify-content-md-center">
            <div class="col-md-5">
                <h3><?php echo ucfirst($class->class_name) ?> (<?php echo ucfirst($class->class_code) ?>)</h3>
            </div>
            <div class="col-md-5">
                <a href="<?php echo site_url() ?>/classes/?step=view_class&class_id=<?php echo $class->id ?>">
                <button class="btn btn-cyan pull-right" type="button">Back</button>
                </a>
            </div>
        </div>
        
        
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <hr />
            </div>
            
        </div>
        
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                Section: <?php echo $class->class_section ?>
            </div>
            <div class="col-md-5">
                Subject: <?php echo $class->class_subject ?>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                Room: <?php echo $class->class_room ?>
            </div>
            <div class="col-md-5">
                Access Code: <?php echo $class->class_code ?>
            </div>
        </div>
        
        
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <hr />
            </div>
        </div>
		
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <table class="table table-condensed table-striped table-hover datatable">
                	<thead class="thead-light">
                    	<th>Full Name</th>
                    	<th>Username</th>
                    	<th>Date Joined</th>
                    	<th>Status</th>
                    	<th>Option</th>
                    </thead>
                    <?php
					$class_participants = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_class_participant Where class_id=%d", $class->id));
					if($class_participants){
						foreach($class_participants as $participant){
							$user_info = get_user_by("ID", $participant->participant_id);
							echo '<tr>';	
								echo '<td>'.$user_info->first_name.' '.$user_info->last_name.'</td>';
								echo '<td>'.$user_info->user_login.'</td>';
								echo '<td>'.date("F d, Y h:i A", strtotime($participant->date_joined)).'</td>';
								echo '<td>'.$participant->status.'</td>';
								echo '<td></td>';
							echo '</tr>';
						}	
					}
					?>
                </table>
            </div>
        </div>
		
	<?php	
	}
	

}elseif(isset($_GET['step'])&&$_GET['step']&&$_GET['step']=='view_class'){
	//check if class exists
	$class = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where id=%d", $_GET['class_id']));
	if($class){
?>
        
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                <h3><?php echo ucfirst($class->class_name) ?> (<?php echo ucfirst($class->class_code) ?>)</h3>
            </div>
            <div class="col-md-5">
            	<?php
				if(!only_student()){
				?>
            	<a href="<?php echo site_url() ?>/classes/?step=view_class_students&class_id=<?php echo $class->id ?>">
                <button class="btn btn-secondary pull-right"  id="view-students" type="button">Students</button>
                </a>
                <a href="<?php echo site_url() ?>/posts/?step=add_post&class_id=<?php echo $class->id ?>">
                <button class="btn btn-cyan pull-right" type="button">Post</button>
                </a>
                <?php
				}
				?>
            </div>
        </div>
        
        
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <hr />
            </div>
            
        </div>
        
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                Section: <?php echo $class->class_section ?>
            </div>
            <div class="col-md-5">
                Subject: <?php echo $class->class_subject ?>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                Room: <?php echo $class->class_room ?>
            </div>
            <div class="col-md-5">
                Access Code: <?php echo $class->class_code ?>
            </div>
        </div>
        
        
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <hr />
            </div>
            
        </div>


	<script language="javascript">
	$(document).ready(function(e) {
		$(".add-comment").submit(function(e){
			e.preventDefault();	
			var formData = new FormData;
			$(".form-control", this).each(function(index, element) {
				formData.append($(this).attr("name"), $(this).val());
			});
			
			$.ajax({
				url:$(".add-comment").attr("action")+".php",
				data:formData,
				type:"POST",
				contentType:false,
				processData:false,
				success: function(data){
					
					var comment = JSON.parse(data);
					
					
					var prep_comment = "";
						prep_comment += '<div class="row justify-content-md-center">';
						prep_comment += '    <div class="col-md-10 post-comments">';
						prep_comment += '        <div class="comment_by">'+comment.added_by+' : </div> ';
						prep_comment += '        <div class="the_comment"> '+comment.body_text+'';
						prep_comment += '        <div class="buttons pull-right"><i class="remove fa fa-remove" id="'+comment.comment_id+'"></i></div></div>';
						prep_comment += '        <div class="comment_date_time">'+comment.date_added+'</div>';
						prep_comment += '    </div>';
						prep_comment += '</div>';
					
					
					$(".comments#"+comment.post_id+"").prepend(prep_comment);
					$(".add-comment#form-"+comment.post_id+"").trigger("reset");
				}
			});
		});
		
		
		$(document).on("click", '.remove', function(){
			var comment_id = $(this).attr("id");
			var formData = new FormData;
			formData.append("comment_id", comment_id);
			formData.append("secure", "<?php echo wp_create_nonce("secureRemoveComment") ?>");
			$(this).closest(".post-comments").hide();
			$.ajax({
				url:$("form.add-comment").attr("action")+".php",
				data:formData,
				type:"POST",
				contentType:false,
				processData:false,
				success: function(data){
					console.log(data);
				}
			});
		});
		
		
		
		
		
	});
	</script>
	
	
	
	
	
	
	<div class="row justify-content-md-center">
		<div class="col-md-8">
		<?php
		$posts = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_posts Where status=%s and post_to=%d ORDER by id DESC", 'Active', $_GET['class_id']));
		
		
		if($posts){
			foreach($posts as $post){
			$user_info = get_user_by("ID", $post->added_by);
			$user_full_name = $user_info->first_name." ".$user_info->last_name."(".$user_info->user_login.")";
			$class_detail = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where id=%d", $post->post_to));
			$class_name = $class_detail ? $class_detail->class_name : "All";
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="post_info">
						<div class="posted_by_and_posted_to">
							<?php echo $user_full_name ?> <i class="fa fa-play"></i> <?php echo ucfirst($post->post_type) ?> <i class="fa fa-play"></i> <?php echo $class_name ?>
							
							<?php if(!only_student()){ ?>
							<a href="<?php echo site_url() ?>/post/?step=delete_post&post_id=<?php echo $post->id ?>" class="pull-right"><i class="fa fa-remove"></i></a>
							<a href="<?php echo site_url() ?>/post/?step=edit_post&post_id=<?php echo $post->id ?>&trans_id=<?php echo time(); ?>" class="pull-right"><i class="fa fa-pencil"></i></a>
							<?php } ?>
                        </div>
						<div class="post_date_time">
							<?php echo date("F d, Y h:i A", strtotime($post->date_added)) ?>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="post-content">
						<?php echo stripslashes($post->body_text) ?>
					</div>
				</div>
			</div>
			<?php
			$attachments = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_attachments Where post_id=%d", $post->id));
			if($attachments){
			?>
			
			<div class="row">
				<div class="col-md-12">
					<div class="post-attachments">
						<span><i class="fa fa-paperclip"></i> Attachment:</span>
						<?php 
						
							foreach($attachments as $attach){
								$link_text = explode("_",$attach->file_loc);
								$link = "<a href='".plugin_dir_url(__FILE__).$attach->file_loc."'>".$link_text[2]."</a>";
								print_r($link);
							}
							 ?>
					</div>
				</div>
			</div>
			
			<?php
			}
			?>
			<div class="row justify-content-md-center">
				<div class="col-md-12 comments" id="<?php echo $post->id ?>">
				<?php
				$comments = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_comments Where post_id=%d and status='Active' ORDER by id DESC", $post->id));
				if($comments){
					foreach($comments as $comment){
						$comment_user_info = get_user_by("ID", $comment->added_by);
						$comment_user_full_name = $comment_user_info->first_name." ".$comment_user_info->last_name."(".$comment_user_info->user_login.")";
						?>
						<div class="row justify-content-md-center">
							<div class="col-md-10 post-comments">
								<div class="comment_by"><?php echo $comment_user_full_name ?> : </div> 
								<div class="the_comment"><?php echo $comment->body_text ?>
                                	<?php if($comment->added_by==get_current_user_id()){ ?>
									<div class="buttons pull-right"><i class="remove fa fa-remove" id="<?php echo $comment->id ?>"></i></div>
                                    <?php } ?>
								</div>
								<div class="comment_date_time"><?php echo date("M d, Y h:i A", strtotime($comment->date_added)) ?></div>
							</div>
						</div>
						<?php	
					}	
				}
				?>
				</div>
			</div>
			<hr/>
			<div class="row <?php echo $post->can_comment=="yes" ?  "d-block" : "d-none"?> ">
				<div class="col-md-12">
					<div class="row justify-content-md-center">
						<div class="col-md-10">
							<form action="<?php echo plugin_dir_url(__FILE__) ?>process-comments" method="post" class="add-comment" id="form-<?php echo $post->id ?>">
								<input type="hidden" name="secure" class="form-control" value="<?php echo wp_create_nonce("secureAddComment") ?>" />
								<input type="hidden" name="post_id" class="form-control" value="<?php echo $post->id ?>" />
								<input type="text" class="form-control" name="comment" required placeholder="Write a reply.." />
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<hr />
			<?php	
			}
		}else{
		?>
		<div class="row justify-content-md-center">
			<div class="col-md-12 notif error d-block">
				<h6>No post has been added yet!</h6>
			</div>
		</div>
		<?php
		}
		?>
		</div>    
	</div>



	<?php
	}else{
	?>
	<div class="row justify-content-md-center">
    	<div class="col-md-8 notif error d-block">
        	Class not found!
        </div>
    </div>
	<?php	
	}
	?>


<?php
}else{
?>
<script language="javascript">
$(document).ready(function(e) {
    $("#add-class").submit(function(e){
		e.preventDefault();	
		var formData = new FormData;
		$(".form-control").each(function(index, element) {
            formData.append($(this).attr("name"), $(this).val());
        });
		$.ajax({
			type:"POST",
			data:formData,
			url:$(this).attr("action")+".php",
			contentType:false,
			processData:false,
			success: function(data){
				
				
				
				
				$("#classes_table tbody").prepend(data);
				
				
				$("form").trigger("reset");
				$(".modal").modal("toggle");
			}
		});
	});
});
</script>
<div class="row justify-content-md-center">
	<div class="col-md-5">
    	<h3>Classes</h3>
    </div>
    <div class="col-md-5">
    	<?php if(only_admin()||only_teacher()){ ?>
    	<button class="btn btn-cyan pull-right"  data-toggle="modal" data-target="#basicExampleModal" type="button">Add Class</button>
        <?php } ?>
    </div>
</div>
<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<hr />
    </div>
    
</div>


<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<table class="table table-condensed table-striped table-hover" id="classes_table">
        	<thead class="thead-dark">
            <?php if(only_admin()){ ?>
            	<th>Name</th>
            	<th>Section</th>
            	<th>Subject</th>
            	<th>Room</th>
            	<th>Code</th>
            	<th>Created By</th>
                <th>Option</th>
            <?php }else{ ?>
            	<th>Name</th>
            	<th>Section</th>
            	<th>Subject</th>
            	<th>Room</th>
            	<th>Code</th>
                <th>Option</th>
            <?php } ?>
            </thead>
            <tbody>
            <?php
			if(only_admin()){
            	$classes = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_classes Where status=%s ORDER by id DESC", 'Active'));
			}elseif(only_teacher()){
				
				$classes = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_classes Where created_by=%d and status=%s ORDER by id DESC", get_current_user_id(), 'Active'));
			}else{
				$my_classes = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_class_participant Where participant_id=%d and status=%s", get_current_user_id(), 'Active'));
				
			}
			if($classes&&(only_admin()||only_teacher())){
				
				foreach($classes as $class){
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
					}elseif(only_teacher()){
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
				}	
			}else{
				if($my_classes){
					foreach($my_classes as $my_class){
						$class = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where id=%d and status=%s ORDER by id DESC", $my_class->class_id, 'Active'));
						if($class){
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
					}
					
				}else{
					echo '<tr>';	
						echo '<td colspan="6">You haven\'t joined any group yet.</td>';
					echo '</tr>';	
				}
			}
			?>
            
            </tbody>
        </table>
    </div>
</div>

<!-- Add Class Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form action="<?php echo plugin_dir_url(__FILE__) ?>process-classes" method="post" id="add-class">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Class</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<label for="class_name">Class Name: </label>
                <input type="text" name="class_name" class="form-control" placeholder="Class Name" autofocus />
            </div>
        </div>
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<label for="class_section">Section: </label>
                <input type="text" name="class_section" class="form-control" placeholder="Class Section" />
            </div>
        </div>
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<label for="class_subject">Class Subject: </label>
                <input type="text" name="class_subject" class="form-control" placeholder="Class Subject" />
            </div>
        </div>
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<label for="class_room">Class Room: </label>
                <input type="text" name="class_room" class="form-control" placeholder="Class Room" />
            </div>
        </div>
      </div>
      <div class="modal-footer">
      	<input type="hidden" class="form-control" value="<?php echo wp_create_nonce("secureAddClass") ?>" name="secure" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>


<?php
}
ob_end_flush();	
}
add_shortcode("classes", "classes");



function posts(){
ob_start();
global $wpdb;

	
?>





<?php


if(isset($_GET['step'])&&$_GET['step']&&$_GET['step']=='delete_post'){

?>
<script language="javascript">
$(document).ready(function(e) {
	function notif(state, msg){		
		$(".notif").removeClass("error");
		$(".notif").removeClass("success");
		$(".notif").addClass(state).html(msg).show();
	}
	
    $("#remove-post").submit(function(e){
		e.preventDefault();	
		var formData = new FormData;
		var process_to = $(this).attr("action") + ".php";
		$(".form-control").each(function(index, element) {
            formData.append($(this).attr("name"), $(this).val());
        });
		
		$.ajax({
			contentType:false,
			processData:false,
			type:"POST",
			url:process_to,
			data:formData,
			success: function(data){
				if(data.match('Error')){
					notif("error", data);
				}else{
					notif("success", data);
					$(".back-btn").removeClass("d-none");
					$(".remove-btn").addClass("d-none");
				}	
			}
		});
		
	});
});
</script>
<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<h4>Remove Post</h4>
        <hr/>
    </div>
</div>

<?php	

$post_detail = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_posts Where id=%d and status=%s", $_GET['post_id'], 'Active'));
if($post_detail){
	$posts = $post_detail;
	
}else{
?>
	<div class="row justify-content-md-center">
        <div class="col-md-9 notif error d-block">
            <h6>Post not Found!</h6>
        </div>
    </div>
    <br/>
<?php	
}


?>


<div class="row justify-content-md-center">
	<div class="col-md-10">
<?php





	if($posts){
		foreach($posts as $post){
		$user_info = get_user_by("ID", $post->added_by);
		$user_full_name = $user_info->first_name." ".$user_info->last_name."(".$user_info->user_login.")";
		?>
        <div class="row justify-content-md-center">
        	<div class="col-md-10 notif error d-block">
            Do you realy want to remove this post?
            </div>
        </div>
        <br/>
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<div class="post_info">
                	<div class="posted_by_and_posted_to">
						<?php echo $user_full_name ?> <i class="fa fa-play"></i> <?php echo ucfirst($post->post_type) ?> <i class="fa fa-play"></i> <?php echo $post->post_to ?>
                     </div>
                	<div class="post_date_time">
                    	<?php echo date("F d, Y h:i A", strtotime($post->date_added)) ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<div class="post-content">
                	<?php echo stripslashes($post->body_text) ?>
                </div>
            </div>
        </div>
        <?php
		$attachments = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_attachments Where post_id=%d", $post->id));
        if($attachments){
		?>
        
        <div class="row justify-content-md-center">
        	<div class="col-md-10">
            	<div class="post-attachments">
                	<span><i class="fa fa-paperclip"></i> Attachment:</span>
                	<?php 
					
						foreach($attachments as $attach){
							$link_text = explode("_",$attach->file_loc);
							$link = "<a href='".plugin_dir_url(__FILE__).$attach->file_loc."'>".$link_text[2]."</a>";
							print_r($link);
						}
						 ?>
                </div>
            </div>
        </div>
        
        
        
        <?php
		}
		?> 
        <div class="row justify-content-md-center remove-btn">
        	<div class="col-md-10">
            	<form action="<?php echo plugin_dir_url(__FILE__) ?>process-posts" method="post" id="remove-post">
                	<input type="hidden" name="secure" class="form-control" value="<?php echo wp_create_nonce("secureRemovePost"); ?>" />
                    <input type="hidden" name="post_id" class="form-control" value="<?php echo $post->id ?>" />
            		<button class="btn btn-danger btn-lg pull-right" type="submit"><i class="fa fa-remove"></i> Remove</button>
                	<a href="<?php echo site_url() ?>/posts"><button class="btn btn-default btn-lg pull-right" type="button"><i class="fa fa-reply"></i> Cancel</button></a>
                </form>
            </div>
        </div>
        <div class="row justify-content-md-center d-none back-btn">
        	<div class="col-md-10">
            	<a href="<?php echo site_url() ?>/posts"><button class="btn btn-default btn-lg pull-right" type="button"><i class="fa fa-reply"></i> Back</button></a>
                
            </div>
        </div>
        <hr />
        <?php	
		}
	}
?>
	</div>
</div>
<?php	
}elseif(isset($_GET['step'])&&$_GET['step']&&$_GET['step']=='edit_post'&&isset($_GET['trans_id'])&&$_GET['trans_id']){
$post_detail = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_posts Where id=%d", $_GET['post_id']));
if($post_detail){
	$post = $post_detail;	
}else{
?>
	<div class="row justify-content-md-center">
        <div class="col-md-9 notif error d-block">
            <h6>Post not Found!</h6>
        </div>
    </div>
    <br/>
<?php	
}
?>
<script language="javascript">
$(document).ready(function(e) {
	function notif(state, msg){		
		$(".notif").removeClass("error");
		$(".notif").removeClass("success");
		$(".notif").addClass(state).html(msg).show();
	}
	
	
	
	
	
    $("textarea[name=post_body]").Editor();
	$(".Editor-editor").html("<?php echo $post->body_text ?>");
	
	
	
	
	
	
	
	
	$(".remove-attachment").click(function(e){
		e.preventDefault();	
		
		var file_id = $(this).attr("href");
		var formData = new FormData;
		formData.append("file_id", file_id);
		formData.append("secure", "<?php echo wp_create_nonce("secureRemoveAttachment") ?>");
		formData.append("trans_id", "<?php echo $_GET['trans_id'] ?>");
		$(this).hide();
		$.ajax({
			url:$("#post_form").attr("action")+".php",
			data:formData,
			type:"POST",
			contentType:false,
			processData:false,
			success: function(data){
				
			}
		});
	});
	
	
	
	
	
	
	
	
	
	
	
	
	$("#post_form").submit(function(e){
		e.preventDefault();
		$("textarea[name=post_body]").val($(".Editor-editor").html());
		
		var formData = new FormData();
		var process_to = $(this).attr("action") + ".php";
		var file_ctr = $("input[type=file]", this)[0].files.length;
		var i;
		for(i=0; i<file_ctr;i++){
			var file = $("input[type=file]", this)[0].files[i];
			formData.append("attachment[]", file);
		}
		
		
		
		
		var other_data = $(this).serializeArray();
		$.each(other_data,function(key,input){
			formData.append(input.name,input.value);
		});
		$.ajax({url:process_to, data:formData, type:"POST", contentType:false, processData:false,
		success: function(data){
			if(data.match('Error')){
				notif("error", data);
			}else{
				notif("success", data);
				$("form").trigger("reset");
			}
		},
		xhr: function(){
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt){
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					
					$('.progress').show();
					$('.progress').css({
						width: percentComplete * 100 + '%'
					});
					$("button[type=submit]").attr("disabled", true);
					if (percentComplete === 1) {
						$('.progress').css({
							width: 0 + '%'
						});
						$("button[type=submit]").removeAttr("disabled");
					}
				}
			}, false);
			return xhr;	
		}
		
		});	
	});
	
});
</script>
<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<h4>Update Post</h4>
        <hr/>
    </div>
</div>

<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<div class="form-group">
        	<form action="<?php echo plugin_dir_url(__FILE__) ?>process-posts" id="post_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <label for="post_type">Type: </label>
                    <select class="form-control" name="post_type">
                        <option value="announcement" <?php echo $post->post_type=="announcement" ? "selected" : "" ?>>Announcement</option>
                        <option value="topic" <?php echo $post->post_type=="topic" ? "selected" : "" ?>>Topic</option>
                        <option value="assigment" <?php echo $post->post_type=="assigment" ? "selected" : "" ?>>Assigment</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="post_to">To: </label>
                    <select class="form-control" required name="post_to">
                        <?php
							
						
                        if(get_user_by("ID", get_current_user_id())->roles[0]=="administrator"){
							$selected =  $post->post_to=="all" ? "selected" : "";
                            echo '<option value="all" '.$selected.'>All</option>';
                            $classes = $wpdb->get_results("Select * From gm_tbl_classes");
							if($classes){
								foreach($classes as $class){
									echo '<option value="'.$class->id.'" '.$post->post_to==$class->id ? "selected" : "".'>'.ucfirst($class->name).'</option>';
								}
							}
                        }elseif(get_user_by("ID", get_current_user_id())->roles[0]=="teacher"){
                            $classes = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_classes Where created_by=%d",get_current_user_id()));
							if($classes){
								foreach($classes as $class){
									echo '<option value="'.$class->id.'" '.$post->post_to==$class->id ? "selected" : "".'>'.ucfirst($class->name).'</option>';
								}
							}
                        }
                        
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="post_type">Can Comment: </label>
                    <select class="form-control" name="can_comment">
                        <option value="yes" <?php echo $post->can_comment=="yes" ? "selected" : "" ?>>Yes</option>
                        <option value="no" <?php echo $post->can_comment=="no" ? "selected" : "" ?>>No</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="post_body">Body Text: </label>
                    <textarea class="form-control" name="post_body" placeholder="Enter Text Here.."></textarea>
                </div>
            </div>
            
            
            
             <?php
			$attachments = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_attachments Where post_id=%d", $post->id));
			if($attachments){
			?>
			
			<div class="row">
				<div class="col-md-12">
					<div class="post-attachments">
						<span><i class="fa fa-paperclip"></i> Attachment:</span>
						<?php 
						
							foreach($attachments as $attach){
								
								$link_text = explode("_",$attach->file_loc);
								$link = "
									<a href='".$attach->id."' class='remove-attachment'>".$link_text[2]." <i class='fa fa-remove'></i></a>
								";
								//check if already deleted in this transaction
								$check_if_deleted = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_attachment_temp Where file_id=%d and trans_id=%s", $attach->id, $_GET['trans_id']));
								if(!$check_if_deleted){
									print_r($link);
								}
							}
							 ?>
					</div>
				</div>
			</div>
			
			<?php
			}
			?>
            
            
            <?php
			if($post->file_loc){
			?>
			
			<div class="row">
				<div class="col-md-12">
					<div class="post-attachments">
						<span><i class="fa fa-paperclip"></i> Attachment:</span>
						<?php 
							$explode_link = explode(";", $post->file_loc);
							for($a=0;$a<count($explode_link);$a++){
								$link_text = explode("_",$explode_link[$a]);
								$link = "
									<a href='".$explode_link[$a]."' class='remove-attachment'>".$link_text[2]." <i class='fa fa-remove'></i></a>
								";
								print_r($link);
							}
							 ?>
					</div>
				</div>
			</div>
			
			<?php
			}
			?>
            
            
            
            <div class="row">
            	<div class="col-md-12">
                	<label for="attachment">Attachment: </label>
                	<div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                      </div>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input form-control" multiple name="attachment[]" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                      </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-md-center">
            	<div class="col-md-10 notif"></div>
            </div>
            
            <div class="row">
            	<div class="col-md-12">
                	<input type="hidden" name="secure" class="form-control" value="<?php echo wp_create_nonce("secureUpdatePost"); ?>" />
                    <input type="hidden" name="post_id" class="form-control" value="<?php echo $post->id ?>" />
                    <input type="hidden" name="trans_id" class="form-control" value="<?php echo $_GET['trans_id']; ?>" />
                	<button class="btn btn-primary pull-right" type="submit">Update</button>
                    <button class="btn btn-pink pull-right" type="reset">Reset</button>
                </div>
            </div>
            </form>
        </div>
        
    </div>
</div>

<?php



}elseif(isset($_GET['step'])&&$_GET['step']&&$_GET['step']=='add_post'){

?>
<script language="javascript">
$(document).ready(function(e) {
	function notif(state, msg){		
		$(".notif").removeClass("error");
		$(".notif").removeClass("success");
		$(".notif").addClass(state).html(msg).show();
	}
	
    $("textarea[name=post_body]").Editor();
	
	$("#post_form").submit(function(e){
		e.preventDefault();
		$("textarea[name=post_body]").val($(".Editor-editor").html());
		
		var formData = new FormData();
		var process_to = $(this).attr("action") + ".php";
		var file_ctr = $("input[type=file]", this)[0].files.length;
		var i;
		for(i=0; i<file_ctr;i++){
			var file = $("input[type=file]", this)[0].files[i];
			formData.append("attachment[]", file);
		}
		
		
		
		
		var other_data = $(this).serializeArray();
		$.each(other_data,function(key,input){
			formData.append(input.name,input.value);
		});
		$.ajax({url:process_to, data:formData, type:"POST", contentType:false, processData:false,
		success: function(data){
			if(data.match('Error')){
				notif("error", data);
			}else{
				notif("success", data);
				$("form").trigger("reset");
			}
		},
		xhr: function(){
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt){
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					
					$('.progress').show();
					$('.progress').css({
						width: percentComplete * 100 + '%'
					});
					$("button[type=submit]").attr("disabled", true);
					if (percentComplete === 1) {
						$('.progress').css({
							width: 0 + '%'
						});
						$("button[type=submit]").removeAttr("disabled");
					}
				}
			}, false);
			return xhr;	
		}
		
		});	
	});
	
});
</script>
<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<h4>Post</h4>
        <hr/>
    </div>
</div>

<div class="row justify-content-md-center">
	<div class="col-md-10">
    	<div class="form-group">
        	<form action="<?php echo plugin_dir_url(__FILE__) ?>process-posts" id="post_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <label for="post_type">Type: </label>
                    <select class="form-control" name="post_type">
                        <option value="announcement">Announcement</option>
                        <option value="topic">Topic</option>
                        <option value="assigment">Assigment</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="post_to">To: </label>
                    <select class="form-control" required name="post_to">
                        <?php
                        if(get_user_by("ID", get_current_user_id())->roles[0]=="administrator"){
                            echo '<option value="all">All</option>';
                            $classes = $wpdb->get_results("Select * From gm_tbl_classes");
							if($classes){
								foreach($classes as $class){
									if(isset($_GET['class_id'])&&$_GET['class_id']){
										$selected = $_GET['class_id']==$class->id ? "selected" : "";
										echo '<option value="'.$class->id.'" '.$selected.'>'.ucfirst($class->class_name).'</option>';
									}else{
										echo '<option value="'.$class->id.'">'.ucfirst($class->class_name).'</option>';
									}
								}	
							}
                        }elseif(get_user_by("ID", get_current_user_id())->roles[0]=="teacher"){
                            $classes = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_classes Where created_by=%d", get_current_user_id()));
							if($classes){
								foreach($classes as $class){
									if(isset($_GET['class_id'])&&$_GET['class_id']){
										$selected = $_GET['class_id']==$class->id ? "selected" : "";
										echo '<option value="'.$class->id.'" '.$selected.'>'.ucfirst($class->class_name).'</option>';
									}else{
										echo '<option value="'.$class->id.'">'.ucfirst($class->class_name).'</option>';
									}
								}	
							}
                        }
                        
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="post_type">Can Comment: </label>
                    <select class="form-control" name="can_comment">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="post_body">Body Text: </label>
                    
                    <textarea class="form-control" name="post_body" placeholder="Enter Text Here.."></textarea>
                </div>
            </div>
            <div class="row">
            	<div class="col-md-12">
                	<label for="attachment">Attachment: </label>
                	<div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                      </div>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input form-control" multiple name="attachment[]" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                      </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-md-center">
            	<div class="col-md-10 notif"></div>
            </div>
            <div class="row">
            	<div class="col-md-12">
                	<input type="hidden" name="secure" class="form-control" value="<?php echo wp_create_nonce("secureAddPost"); ?>" />
                    
                	<button class="btn btn-primary pull-right" type="submit">Post</button>
                    <button class="btn btn-pink pull-right" type="reset">Reset</button>
                </div>
            </div>
            </form>
        </div>
        
    </div>
</div>

<?php



}else{
?>
<!--<div class="row justify-content-md-center">
	<div class="col-md-7 notif">
    	<h4>Access Denied!</h4>
    </div>
</div>-->

<script language="javascript">
$(document).ready(function(e) {
    $(".add-comment").submit(function(e){
		e.preventDefault();	
		var formData = new FormData;
		$(".form-control", this).each(function(index, element) {
            formData.append($(this).attr("name"), $(this).val());
        });
		
		$.ajax({
			url:$(".add-comment").attr("action")+".php",
			data:formData,
			type:"POST",
			contentType:false,
			processData:false,
			success: function(data){
				
				var comment = JSON.parse(data);
				
				
				var prep_comment = "";
					prep_comment += '<div class="row justify-content-md-center">';
                    prep_comment += '    <div class="col-md-10 post-comments">';
                    prep_comment += '        <div class="comment_by">'+comment.added_by+' : </div> ';
                    prep_comment += '        <div class="the_comment"> '+comment.body_text+'';
					prep_comment += '        <div class="buttons pull-right"><i class="remove fa fa-remove" id="'+comment.comment_id+'"></i></div></div>';
                    prep_comment += '        <div class="comment_date_time">'+comment.date_added+'</div>';
                    prep_comment += '    </div>';
                    prep_comment += '</div>';
				
				
				$(".comments#"+comment.post_id+"").prepend(prep_comment);
				$(".add-comment#form-"+comment.post_id+"").trigger("reset");
			}
		});
	});
	
	
	$(document).on("click", '.remove', function(){
		var comment_id = $(this).attr("id");
		var formData = new FormData;
		formData.append("comment_id", comment_id);
		formData.append("secure", "<?php echo wp_create_nonce("secureRemoveComment") ?>");
		$(this).closest(".post-comments").hide();
		$.ajax({
			url:$("form.add-comment").attr("action")+".php",
			data:formData,
			type:"POST",
			contentType:false,
			processData:false,
			success: function(data){
				console.log(data);
			}
		});
	});
	
	
	
	
	
});
</script>

<?php if(only_admin()){ ?>
<div class="row justify-content-md-center">
	<div class="col-md-4">
    	<h4>Posts</h4>
    </div>
    <div class="col-md-4">
    	<a href="?step=add_post">
    	<button class="btn btn-primary pull-right" type="button">Post</button>
        </a>
    </div>
</div>

<div class="row justify-content-md-center">
	<div class="col-md-8"><hr /></div>
</div>

<?php } ?>

<div class="row justify-content-md-center">
	<div class="col-md-8">
    <?php
	$qry = "";
	if(only_student()){
		$my_classes = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_class_participant Where participant_id=%d", get_current_user_id()));
		if($my_classes){
			foreach($my_classes as $my_class){
				$qry .= ' id='.$my_class->class_id." or";	
			}
			$qry = rtrim($qry," or");
		}
		
		$posts = $wpdb->get_results("Select * From gm_tbl_posts Where status='Active' and (".$qry.") ORDER by id DESC");
	}elseif(only_teacher()){
		$posts = $wpdb->get_results("Select * From gm_tbl_posts Where status='Active' and added_by=".get_current_user_id()." ORDER by id DESC");
	}else{
		$posts = $wpdb->get_results("Select * From gm_tbl_posts Where status='Active' ORDER by id DESC");	
	}
	
	
	
	
	
	if($posts){
		foreach($posts as $post){
		$user_info = get_user_by("ID", $post->added_by);
		$user_full_name = $user_info->first_name." ".$user_info->last_name."(".$user_info->user_login.")";
		$class_detail = $wpdb->get_row($wpdb->prepare("Select * From gm_tbl_classes Where id=%d", $post->post_to));
		$class_name = $class_detail ? $class_detail->class_name : "All";
		?>
        <div class="row">
        	<div class="col-md-12">
            	<div class="post_info">
                	<div class="posted_by_and_posted_to">
						<?php echo $user_full_name ?> <i class="fa fa-play"></i> <?php echo ucfirst($post->post_type) ?> <i class="fa fa-play"></i> <?php echo $class_name ?>
                        
                        <?php if(!only_student()){ ?>
                        <a href="<?php echo site_url() ?>/posts/?step=delete_post&post_id=<?php echo $post->id ?>" class="pull-right"><i class="fa fa-remove"></i></a>
                        <a href="<?php echo site_url() ?>/posts/?step=edit_post&post_id=<?php echo $post->id ?>&trans_id=<?php echo time(); ?>" class="pull-right"><i class="fa fa-pencil"></i></a>
                    	<?php } ?>
                    </div>
                	<div class="post_date_time">
                    	<?php echo date("F d, Y h:i A", strtotime($post->date_added)) ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="row">
        	<div class="col-md-12">
            	<div class="post-content">
                	<?php echo stripslashes($post->body_text) ?>
                </div>
            </div>
        </div>
        <?php
		$attachments = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_attachments Where post_id=%d", $post->id));
        if($attachments){
		?>
        
        <div class="row">
        	<div class="col-md-12">
            	<div class="post-attachments">
                	<span><i class="fa fa-paperclip"></i> Attachment:</span>
                	<?php 
					
						foreach($attachments as $attach){
							$link_text = explode("_",$attach->file_loc);
							$link = "<a href='".plugin_dir_url(__FILE__).$attach->file_loc."'>".$link_text[2]."</a>";
							print_r($link);
						}
						 ?>
                </div>
            </div>
        </div>
        
        <?php
		}
		?>
        <div class="row justify-content-md-center">
        	<div class="col-md-12 comments" id="<?php echo $post->id ?>">
            <?php
			$comments = $wpdb->get_results($wpdb->prepare("Select * From gm_tbl_comments Where post_id=%d and status='Active' ORDER by id DESC", $post->id));
			if($comments){
				foreach($comments as $comment){
					$comment_user_info = get_user_by("ID", $comment->added_by);
					$comment_user_full_name = $comment_user_info->first_name." ".$comment_user_info->last_name."(".$comment_user_info->user_login.")";
					?>
                    <div class="row justify-content-md-center">
                        <div class="col-md-10 post-comments">
                            <div class="comment_by"><?php echo $comment_user_full_name ?> : </div> 
                            <div class="the_comment"><?php echo $comment->body_text ?>
                            	<?php if($comment->added_by==get_current_user_id()){ ?>
                            	<div class="buttons pull-right"><i class="remove fa fa-remove" id="<?php echo $comment->id ?>"></i></div>
                                <?php } ?>
                            </div>
                            <div class="comment_date_time"><?php echo date("M d, Y h:i A", strtotime($comment->date_added)) ?></div>
                        </div>
                    </div>
                    <?php	
				}	
			}
			?>
            </div>
        </div>
        <hr/>
        <div class="row <?php echo $post->can_comment=="yes" ?  "d-block" : "d-none"?> ">
        	<div class="col-md-12">
            	<div class="row justify-content-md-center">
                    <div class="col-md-10">
                        <form action="<?php echo plugin_dir_url(__FILE__) ?>process-comments" method="post" class="add-comment" id="form-<?php echo $post->id ?>">
                            <input type="hidden" name="secure" class="form-control" value="<?php echo wp_create_nonce("secureAddComment") ?>" />
                            <input type="hidden" name="post_id" class="form-control" value="<?php echo $post->id ?>" />
                            <input type="text" class="form-control" name="comment" required placeholder="Write a reply.." />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <hr />
        <?php	
		}
	}else{
	?>
    <div class="row justify-content-md-center">
        <div class="col-md-12 notif error d-block">
            <h6>No post has been added yet!</h6>
        </div>
    </div>
    <?php
	}
	?>
    </div>    
</div>


<?php	
}

?>

<?php

ob_end_flush();
}
add_shortcode("posts", "posts");



function e_learning_style() {
	wp_enqueue_style('e-learning-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
	
}
add_action('wp_enqueue_scripts', 'e_learning_style');
?>