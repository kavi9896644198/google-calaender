<?php 
/*
//THis one is not working in for edit profile.Please edit wp-content\plugins\scisco-features\profile-fields.php for any additions
*/

if ( is_user_logged_in() ) {

$prefix = 'scisco_cmb2';



	for($i=18;$i<=70;$i++){

		$age[$i]=$i;

	}

global $woocommerce;

$countries_obj   = new WC_Countries();

$countries   = $countries_obj->__get('countries');



 $default_country = $countries_obj->get_base_country();

 $default_county_states = $countries_obj->get_states( $default_country );

 

 $consumption_methods=['flower'=>'Flower','vapes'=>'Vapes','edibles'=>'Edibles','topicals'=>'Topicals','concentrates'=>'Concentrates'];

 $consumption_experiences=['rookie'=>'Rookie','experienced'=>'Experienced','expert'=>'Expert'];
 
 $company_type = ['cultivation'=>'Cultivation','retail'=>'Retail','holesale'=>'Holesale','distributor'=>'Distributor'];
 $company_stage = ['idea'=>'Idea','startup'=>'Startup','operating'=>'Operating','expansion-growth'=>'Expansion/Growth'];
 
 $has_skills = ['agriculture'=>'Agriculture','cultivation'=>'Cultivation','mentor'=>'Mentor'];
 $looking_for = ['partner'=>'Partner','team-member'=>'Team Member','co-founder'=>'Co-Founder'];
 $experience_level = ['entry-level'=>'Entry-level','intermediate'=>'Intermediate','mid-level'=>'Mid-level'];

 global $current_user;
 global $wp;
 $user=wp_get_current_user();
 $page_link = home_url( $wp->request );

 
$explode = explode('//',$page_link);
$url_exp = explode('/',$explode[1]);
$user_name = $url_exp[3];
$myuser = get_user_by( 'login', $user_name );
$myuserId =  $myuser->ID;
$user_meta = get_user_meta($myuserId);
//echo "<pre>";print_r($user_meta['first_name']);
$first_name = $user_meta['first_name'][0];
$last_name = $user_meta['last_name'][0];

?>



<div class="edit_profile_main">

	<form id="edit_profile_frm"  method="post">
			<div class="form-box">
				<div class="message-response-register"></div>
				<h5>Personal Information</h5>

						<p>
							<span><input type="text" name="first_name" required placeholder="First Name" value="<?=$user->first_name?>"></span>

							<span><input type="text" name="last_name" placeholder="Last Name" value="<?=$user->last_name?>"></span>
						</p>						
						<p>
							<span class="gender_age">
								<select name="<?=$prefix?>_gender" class="gender">
									<option value="">Gender *</option>
									<option value="male" <?=($user->scisco_cmb2_gender=='male')?'selected':''?>>Male</option>
									<option value="female" <?=($user->scisco_cmb2_gender=='female')?'selected':''?>>Female</option>
								</select>
								<select name="<?=$prefix?>_age" class="_age">
									<option value="">Age *</option>
									<?=value_select_options($age,$user->scisco_cmb2_age);?>
								</select>
							</span>
						</p>

					<p>

						<span <?=$user->ID?>>
							<input type="email" name="<?=$prefix?>_user_email" placeholder="Email Address" value="<?=$user->user_email?>">
						</span>
						<span>
							<input type="text" name="<?=$prefix?>_user_phone" placeholder="Phone" value="<?=$user->scisco_cmb2_user_phone?>">
						</span>
					</p>

					

					<p class="col_3">

						<span><input type="text" name="<?=$prefix?>_user_city" placeholder="City" value="<?=$user->scisco_cmb2_user_city?>">	</span>				

						<span>
							<select name="<?=$prefix?>_user_state" class="_age">
								<option value="">State *</option>
								<?=value_select_options($default_county_states,$user->scisco_cmb2_user_state);?>
							</select>
						</span>
						<span>
							<select name="<?=$prefix?>_user_country" class="_age">
								<option value="">Country *</option>
								<?=value_select_options($countries,$user->scisco_cmb2_user_country);?>
							</select>
						</span>							
					</p>

				</div>
				<div class="form-box">
					<h5>Employment Information</h5>	
					<p class="col_1">
						<span><input type="text" name="<?=$prefix?>_user_company_name" placeholder="Company Name" value="<?=$user->scisco_cmb2_user_company_name?>"></span>
					</p>

					<p>
						<span>
							<select name="<?=$prefix?>_user_type" class="_user_type">
								<option value="">Type</option>
								<?=key_select_options($company_type,$user->scisco_cmb2_user_type);?>
							</select>
						</span>						
						<span>
							<select name="<?=$prefix?>_user_stage" id="scisco_cmb2_user_stage" class="_user_stage">
								<option value="">Stage</option>
								<?=key_select_options($company_stage,$user->scisco_cmb2_user_stage);?>
							</select>
						</span>
					</p>

					

					<p class="col_1">
						<span>
							<select name="<?=$prefix?>_experience_level" class="_experience_level">
								<option value="">Experience Level</option>
								<?=key_select_options($experience_level,$user->scisco_cmb2_experience_level);?>
							</select>
						</span>
					</p>
					<p class="col_1">
						<span class="multiple-select">
							<select name="<?=$prefix?>_user_skills[]" multiple class="_user_skills">
								<option value="">Skills</option>
								<?=skills_select_options($has_skills,$user->scisco_cmb2_user_skills);?>
							</select>
						</span>
					</p>
				</div>
				<div class="form-box">
					<h5>Title or Taglines</h5>	
					<p class="col_1">
						<span>
							<input type="text" name="<?=$prefix?>_tag_line" placeholder="ie Entrepreneur, To be or not to be...." value="<?=$user->scisco_cmb2_tag_line?>">
							<i class="help-text">50 Characters available</i>
						</span>
					</p>
				<div class="form-box">
					<h5>Looking for…</h5>	

					<p class="col_1">
						<span class="multiple-select">
							<select name="<?=$prefix?>_user_question_category[]" multiple class="_user_question_category">
								<option value="">Skills</option>
								<?=skills_select_options($looking_for,$user->scisco_cmb2_user_question_category);?>
							</select>
						</span>
					</p>
					<p>
						<span>
							<select name="<?=$prefix?>_user_consumption_method" class="_user_consumption_method">

								<option value="">Fave Consumption Method</option>

								<?=key_select_options($consumption_methods,$user->scisco_cmb2_user_consumption_method);?>

							</select>
						</span>
						<span>
							<select name="<?=$prefix?>_consumption_experience_level" class="_consumption_experience_level">

								<option value="">Consumption Experience Level</option>

								<?=key_select_options($consumption_experiences,$user->scisco_cmb2_consumption_experience_level);?>

							</select>
						</span>
					</p>				
					<p class="col_1">
						<span>
							<select name="<?=$prefix?>_user_investors" class="_user_investors">
								<option value="">Do you need investors?</option>
								<option value="Yes" <?=($user->scisco_cmb2_user_investors=='Yes')?'selected':''?>>Yes</option>
								<option value="No" <?=($user->scisco_cmb2_user_investors=='No')?'selected':''?>>No</option>
							</select>
						</span>
					</p>					

				</div>
				<div class="form-btn">
					<button class="btn light-blue">SAVE</button>
				</div>

	</form>

</div>
<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<?php 
} else {
    wp_login_form();
}