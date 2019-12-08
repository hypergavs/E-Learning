<?php 
get_header(); ?>


<?php
if(only_student()){
?>	
<div class="container-fluid" id="page">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <?php echo do_shortcode("[stud-home]") ?>
        </div>
    </div>
</div>
<?php
}
?>

<?php
if(only_teacher()){
?>	
<div class="container-fluid" id="page">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <?php echo do_shortcode("[teachers-home]") ?>
        </div>
    </div>
</div>
<?php
}
?>

<?php
if(only_admin()){
?>	
<div class="container-fluid" id="page">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <?php echo do_shortcode("[admin-home]") ?>
        </div>
    </div>
</div>
<?php
}
?>





<?php get_footer(); ?>
