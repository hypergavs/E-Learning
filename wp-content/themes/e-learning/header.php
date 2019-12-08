<html <?php language_attributes(); ?>>
	<head>
    	<?php wp_head(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?php echo get_template_directory_uri() ?>/images/favicon.png">
        <title><?php bloginfo('title'); ?></title>
    </head>
<body <?php body_class(); ?>>
<?php
date_default_timezone_set("Asia/Manila");
?>
<div class="gm-progress"></div>

<nav class="mb-1 navbar navbar-expand-lg navbar-dark default-color">
      <a class="navbar-brand" href="#"><?php echo bloginfo('title') ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-3" aria-controls="navbarSupportedContent-3" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent-3">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="<?php echo site_url() ?>">Home
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="<?php echo site_url() ?>/classes">Classes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="<?php echo site_url() ?>/posts">Posts</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Users
            </a>
            <div class="dropdown-menu dropdown-default" aria-labelledby="navbarDropdownMenuLink-3">
              <a class="dropdown-item waves-effect waves-light" href="<?php echo site_url() ?>/add-user">Add User</a>
              <a class="dropdown-item waves-effect waves-light" href="<?php echo site_url() ?>/users">View Users</a>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto nav-flex-icons">
       	
        

          <?php
          if(!is_user_logged_in()){
		  ?>
          <li class="nav-item">
              <a href="<?php echo site_url() ?>/login" id="navbar-static-login" class="nav-link waves-effect waves-light" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in mr-1"></i><span class="clearfix d-none d-sm-inline-block">Log In</span></a>
          </li>
          <?php
		  }else{
		  ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item waves-effect waves-light" href="<?php echo wp_logout_url("index.php"); ?>">Logout</a>
            </div>
          </li>
          <?php
		  }
		  ?>
        </ul>
      </div>
    </nav>
    
    
    
<?php
	if(!is_user_logged_in()){
		do_shortcode("[user-login]");
	}
	
?>
		  