<?php 
/*
Template Name: Login Page
*/
show_admin_bar(false);
get_header(); ?>


<div class="container-fluid" id="login-page">
	<div class="row">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                <?php
				if(is_user_logged_in()){
				?>
                
                
                	
				
				<?php
				}else{
					do_shortcode("[user-login]");	
				}
				?>
                </div>
            </div>
        </div>
    </div>
</div>







<?php get_footer(); ?>











