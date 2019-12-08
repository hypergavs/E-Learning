<?php 
/*
Template Name: Contact Us
*/
show_admin_bar(false);
get_header(); ?>


<!--Main Navigation-->
<header id="top-header">
    <div class="container">
        <div class="row">
        	<div class="col-md-6 col-xs-0">
            <?php
			bloginfo('title');
			?>
            </div>
            <div class="col-md-6 justify-content-md-end">
            	<ul id="menu">
                	<?php
					if(!is_user_logged_in()){
					?>
                    <li><a href="contact-us"><button class="btn btn-outline-white btn-block btn-sm"><i class="fa fa-envelope"></i> Contact Us </button></a></li>
                    <!--<li><button class="btn btn-outline-white btn-block btn-sm" type="button" id="menu1" data-toggle="dropdown">Tutorials <i class="fa fa-caret-down"></i></button>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSS</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">JavaScript</a></li>
                  <li role="presentation" class="divider"></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">About Us</a></li>
                </ul>-->
                   <?php
					}else{
					?>
                    <li><button class="btn btn-outline-white btn-block btn-sm" type="button" id="menu1" data-toggle="dropdown"><i class="fa fa-book"></i> Journals <i class="fa fa-caret-down"></i></button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="view-journals">View Journals</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="upload-journal">Upload</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="my-uploads">My Uploads</a></li>
                      </ul>
                     <li><button class="btn btn-outline-white btn-block btn-sm" type="button" id="menu1" data-toggle="dropdown"><i class="fa fa-user"></i> Users <i class="fa fa-caret-down"></i></button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="users">View Users</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="add-user">Add User</a></li>
                      </ul>
                    <li><a href="messages"><button class="btn btn-outline-white btn-block btn-sm"><i class="fa fa-bell"></i> Messages</button></a></li>
                    <li><a href="<?php echo wp_logout_url("index.php"); ?>"><button class="btn btn-outline-white btn-block btn-sm"> Logout </button></a></li>
                    <?php
					}
				   
				   ?>
              </div>
</li>
                </ul>
            </div>
        </div>
    </div>
</header>
<!--Main Navigation-->


<div class="container-fluid" id="title-and-motto-dashboard">
	<div class="container">
    	<h2>
    	<?php
        bloginfo('title');
		
		?>
        </h2>
    </div>
</div>



<div class="container-fluid" id="page">
	<div class="row">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                
				<?php if(have_posts()){ ?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                        <?php //get_template_part( 'content', 'page' ); ?>
            
                        <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if ( comments_open() || '0' != get_comments_number() )
                                comments_template();					
                        
                        ?>
            
                    <?php endwhile; ?>
                <?php }else{ echo "Content not found!"; }?>
               
                </div>
            </div>
        </div>
    </div>
</div>







<?php get_footer(); ?>











