<?php

add_shortcode('edit_user_profile','fn_edit_user_profile');

function fn_edit_user_profile(){
	require_once(THEME_PATH_URI.'/templates/edit_user_profile.php');
}

function value_select_options($searr,$selectValue=false){
	$htmlcollect='';
	
	if(count($searr)>0){
	foreach($searr as $a){
				$select=($a==$selectValue)?'selected':'';
		 $htmlcollect.='<option value="'.$a.'" '.$select.' >'.$a.'</option>';
	}
	
	}
	
	return $htmlcollect;
}

function key_select_options($ar,$selectValue=false){
	 foreach($ar as $k=>$a){
		$select=($k==$selectValue)?'selected':'';
		echo '<option value="'.$k.'" '.$select.'>'.$a.'</option>';
	}
}

function skills_select_options($ar,$selectValue=false){
	 foreach($ar as $k=>$a){
	 	if(!empty($selectValue)){
	 		$explodeValue = explode(",",$selectValue);	 		
			if (in_array($k, $explodeValue)){
				$select = 'selected';
			}
			else {
				$select = '';
			}
			echo '<option value="'.$k.'" '.$select.'>'.$a.'</option>';
	 	}else {	 		
		 	foreach($ar as $k=>$a){
			$select=($k==$selectValue)?'selected':'';
			echo '<option value="'.$k.'" '.$select.'>'.$a.'</option>';
		 	}
	 	}
	}
}


function update_user_register_data() {
	$user_id = get_current_user_id(); 
	$data=$_POST;
	//echo "<pre>";print_r($data);die;
	
	$userData=[
		'ID' => $user_id,
		'first_name' => $data['first_name'],
		'last_name' => $data['last_name'],
		'user_email' => $data['scisco_cmb2_user_email'],
	];
	//$company_skills = 
	$scisco_cmb2_user_skills = $data['scisco_cmb2_user_skills'];
	$scisco_cmb2_user_question_category = $data['scisco_cmb2_user_question_category'];
	$company_skills =  implode(",",$scisco_cmb2_user_skills);	
	$looking_for =  implode(",",$scisco_cmb2_user_question_category);	

update_user_meta( $user_id, 'scisco_cmb2_gender',$data['scisco_cmb2_gender']);
update_user_meta( $user_id, 'scisco_cmb2_age',$data['scisco_cmb2_age']);
update_user_meta( $user_id, 'scisco_cmb2_user_phone',$data['scisco_cmb2_user_phone']);
update_user_meta( $user_id, 'scisco_cmb2_user_city',$data['scisco_cmb2_user_city']);
update_user_meta( $user_id, 'scisco_cmb2_user_state',$data['scisco_cmb2_user_state']);
update_user_meta( $user_id, 'scisco_cmb2_user_country',$data['scisco_cmb2_user_country']);
update_user_meta( $user_id, 'scisco_cmb2_user_company_name',$data['scisco_cmb2_user_company_name']);
update_user_meta( $user_id, 'scisco_cmb2_user_type',$data['scisco_cmb2_user_type']);
update_user_meta( $user_id, 'scisco_cmb2_user_stage',$data['scisco_cmb2_user_stage']);
update_user_meta( $user_id, 'scisco_cmb2_experience_level',$data['scisco_cmb2_experience_level']);
update_user_meta( $user_id, 'scisco_cmb2_user_skills',$company_skills );
update_user_meta( $user_id, 'scisco_cmb2_tag_line',$data['scisco_cmb2_tag_line']);
update_user_meta( $user_id, 'scisco_cmb2_user_question_category',$looking_for);
update_user_meta( $user_id, 'scisco_cmb2_user_consumption_method',$data['scisco_cmb2_user_consumption_method']);
update_user_meta( $user_id, 'scisco_cmb2_consumption_experience_level',$data['scisco_cmb2_consumption_experience_level']);
update_user_meta( $user_id, 'scisco_cmb2_user_investors',$data['scisco_cmb2_user_investors']);
//update_user_meta( $user_id, 'scisco_cmb2_user_services',$data['scisco_cmb2_user_services']);
	
	$user_data = wp_update_user($userData);
	
	if ( is_wp_error( $user_data ) ) {
		// There was an error; possibly this user doesn't exist.
		 wp_send_json(['status'=>'fail','mesg'=>'There was an error!']);
	} else {
		// Success!
		 wp_send_json(['status'=>'success','mesg'=>'Profile updated successfully!']);
	}
   

    die(); 
}

add_action('wp_ajax_update_user_register_data', 'update_user_register_data');
add_action('wp_ajax_nopriv_update_user_register_data', 'update_user_register_data');



/**
 * Show custom user profile fields
 * 
 * @param  object $profileuser A WP_User object
 * @return void
 */
function custom_user_profile_fields( $profileuser ) {
	
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
 
 $age=esc_attr( get_the_author_meta($prefix.'_age', $profileuser->ID ) );
?>
    <table class="form-table">
	<h3>User Info</h3>
       
		<p>
			<span class="gender_age">
				<select name="<?=$prefix?>_gender" class="gender">
					<option value="">Gender *</option>
					<option value="male">Male</option>
					<option value="female">Female</option>
				</select>
				<select name="<?=$prefix?>_age" class="_age">
					<option value="">Age *</option>
					<?=value_select_options($age,$age);?>
				</select>
			</span>
		</p>
		<p>

						<span <?=$user->ID?>>
							<input type="email" name="<?=$prefix?>_user_email" placeholder="Email Address" value="<?=$user->user_email?>">
						</span>
						<span>
							<input type="text" name="<?=$prefix?>_user_phone" placeholder="Phone">
						</span>
					</p>

					

					<p class="col_3">

						<span><input type="email" name="<?=$prefix?>_user_city" placeholder="City">	</span>				

						<span>
							<select name="<?=$prefix?>_user_state" class="_age">
								<option value="">State *</option>
								<?=value_select_options($default_county_states);?>
							</select>
						</span>
						<span>
							<select name="<?=$prefix?>_user_country" class="_age">
								<option value="">Country *</option>
								<?=value_select_options($countries);?>
							</select>
						</span>							
					</p>

				</div>
				<div class="form-box">
					<h5>Employment Information</h5>	
					<p class="col_1">
						<span><input type="text" name="<?=$prefix?>_user_company_name" placeholder="Company Name"></span>
					</p>

					<p>
						<span>
							<select name="<?=$prefix?>_user_type" class="_user_type">
								<option value="">Type</option>
							</select>
						</span>						
						<span>
							<select name="<?=$prefix?>_user_stage" class="_user_stage">
								<option value="">Stage</option>
							</select>
						</span>
					</p>

					

					<p>
						<span>
							<select name="<?=$prefix?>_experience_level" class="_experience_level">
								<option value="">Experience Level</option>
							</select>
						</span>
						<span>	
							<select name="<?=$prefix?>_user_skills" class="_user_skills">
								<option value="">Skills</option>
							</select>
						</span>
					</p>
				</div>
				<div class="form-box">
					<h5>Title or Tagline</h5>	
					<p class="col_1">
						<span>
							<input type="text" name="<?=$prefix?>_tag_line" placeholder="ie Entrepreneur, To be or not to be....">
							<i class="help-text">50 Characters available</i>
						</span>
					</p>
				<div class="form-box">
					<h5>Looking for…</h5>	

					<p class="col_1">
						<span>
							<select name="<?=$prefix?>_" class="_user_question_category">
								<option value="">Skills</option>
							</select>
						</span>
					</p>

					<p>
						<span>
							<select name="<?=$prefix?>_user_consumption_method" class="_user_consumption_method">

								<option value="">Fave Consumption Method</option>

								<?=key_select_options($consumption_methods);?>

							</select>
						</span>
						<span>
							<select name="<?=$prefix?>_consumption_experience_level" class="_consumption_experience_level">

								<option value="">Consumption Experience Level</option>

								<?=key_select_options($consumption_experiences);?>

							</select>
						</span>
					</p>

				

					<p class="col_1">
						<span>
							<select name="<?=$prefix?>_user_investors" class="_user_investors">

								<option value="">Do you need investors?</option>

								<option value="Yes">Yes</option>

								<option value="No">No</option>

							</select>

						</span>

					</p>

					

					<p class="col_1">
						<span>
							<select name="<?=$prefix?>_user_investors" class="_user_investors">

								<option value="">What service providers do you need?</option>

								<option value="Yes">Yes</option>

								<option value="No">No</option>

							</select>

						</span>

					</p>
    </table>
<?php
}
add_action( 'show_user_profile', 'custom_user_profile_fields', 10, 1 );
add_action( 'edit_user_profile', 'custom_user_profile_fields', 10, 1 );

