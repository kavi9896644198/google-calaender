<?php
/**
 * User profile template.
 * User profile index template.
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 * @author     Rahul Aryan <support@anspress.io>
 *
 * @link       https://anspress.io
 * @since      4.0.0
 * @package    AnsPress
 * @subpackage Templates
 */

$user_id     = ap_current_user_id();
$looking_for = get_user_meta($user_id,"scisco_cmb2_user_question_category",true );
$about = get_user_meta($user_id,"scisco_cmb2_resume",true );
$test = get_user_meta($user_id);
$current_tab = ap_sanitize_unslash( 'tab', 'r', 'about' );
$user_info = get_userdata($user_id);
$username = $user_info->user_login;
$email = $user_info->user_email;
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$scisco_cmb2_type = get_user_meta($user_id, 'scisco_cmb2_type', true);
$wiki_test_multicheckbox = get_user_meta($user_id, 'wiki_test_multicheckbox', true);
$county = get_user_meta($user_id, 'scisco_cmb2_countries', true);
$county_states = get_user_meta($user_id, 'scisco_cmb2_county_states', true);
$phone = get_user_meta($user_id, 'scisco_cmb2_user_phone', true);
$city = get_user_meta($user_id, 'scisco_cmb2_user_city', true);
$u_company_name = get_user_meta($user_id, 'u_company_name', true);
$current_user_id = get_current_user_id();
if (empty($first_name) && empty($last_name) && empty($email) && empty($phone) && empty($u_company_name) && empty($city) && empty($county_states) && empty($county) && empty($scisco_cmb2_type) && empty($wiki_test_multicheckbox)) :
?>
<div class="profile_text alert alert-danger">
    Please complete your profile to proceed &nbsp;
    <button class="btn btn-primary"><a href="/questions/profile/<?php echo $username; ?>/edit-profile/">Edit Profile</a></button>
</div>
<?php
endif;
?>


<div class="row edit-row-form">

	<div class="col-12 col-lg-12 mb-5">

		<?php if ( '0' == $user_id && ! is_user_logged_in() ) : ?>

			<div class="alert alert-warning"><?php esc_html_e( 'Please login to view your profile', 'scisco' ); ?></div>

		<?php else : ?>

			<?php self::sub_page_template(); ?>

		<?php endif; ?>

	</div>
	<div id="ap-user-nav" class="col-12 col-lg-12 hide-edit">
		<!-- <div class="scisco-question-meta">
			<span class="ap-display-meta-item views">
				<p class="looking-for"><i class="fa fa-users"></i> Looking for: 
					<?php
					$looking_for_arr = explode(",",$looking_for);
					foreach($looking_for_arr as $question_category){
						echo '<a href="#">'.$question_category.'</a> ';
					}
					?>
				</p>
			</span>
		</div> -->
		<!-- <div class="scisco-user-description">
			<h4>About Me</h4>
			<p>
				<?php 
				if($about):
					echo $about;
				else:
					echo "No description";
			endif;
			?>
		</p>
	</div> -->
	<div class="scisco-user-description">
		<h4>About Me</h4>
		<?php 
			// 	if($about):
			// 		echo $about;
			// 	else:
			// 		echo "No description";
			// endif;
			?>
		<?php		
		if($current_user_id == $user_id) { ?>
		<form id="scisco-user-form">
            <textarea id="user-description" name="scisco_user_description" placeholder="Enter your description"><?php if($about): echo $about;   endif;	?></textarea>
			<input type="hidden" name="scisco_user_id" id="scisco_user_id" value="<?php echo $user_id; ?>">
           
			<button type="button" id="submit-btn-description">Submit</button>
        </form> 
		<?php } else { 
			if($about): echo $about;   endif;	
		 } ?>
		<div id="description-result"></div>
	</div>

		<div id="scisco-user-menu-wrapper" style="display:none">
			<a id="scisco-user-menu-toggler" href="#" class="btn btn-primary float-right">
				<?php esc_html_e( 'Menu', 'scisco'); ?><i class="fas fa-chevron-down"></i>
			</a>
			<div class="clearfix"></div>
			<?php self::user_menu(); ?>
		</div>
		<?php dynamic_sidebar( 'ap-author' ); ?>
	</div>
	<div class="col-12 form-heading" style="display: none;" style="background:url();">
		<div class="form-heading-content">
			<h3>Complete Your <br>Profile Form</h3>
			<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</p>
		</div>
	</div>
</div>
