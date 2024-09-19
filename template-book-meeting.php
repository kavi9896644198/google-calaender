<?php 
/*
*Template name: Book Meeting
*/

get_header();
?>
<?php 
//Header template
 get_template_part('custom','templates/canna-header');
?>
<main id="scisco-main-wrapper">
<?php if((is_user_logged_in())): ?>
 <?php	get_template_part( 'templates/usersearch', 'template'); ?>
 
 <?php get_template_part('custom','templates/criteria-user'); ?>
 <?php else: ?>
 	<div class="alert alert-danger">Please <a href="/wp-login.php">Login/Register</a> and Complete Your Profile to access this page!</div> 
 <?php endif; ?>	
</main>

<?php 
get_footer();