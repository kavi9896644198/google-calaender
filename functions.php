<?php

function scisco_child_theme_styles() {

	wp_enqueue_style( 'child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'scisco-style' ),
		wp_get_theme()->get('Version')
	); 


	wp_enqueue_style( 'scisco-style', get_template_directory_uri() . '/style.css', array( 'scisco-bootstrap' ) );

	wp_enqueue_style( 'fullcalendar-style', get_stylesheet_directory_uri() . '/includes/css/fullcalendar.min.css', array( 'scisco-bootstrap' ) );

    
	
wp_enqueue_script(
		'sweet-alert','https://unpkg.com/sweetalert/dist/sweetalert.min.js','',time()
	);
wp_enqueue_script(
		'ajax-script','https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		array( 'jquery' ),time()
	);
	wp_enqueue_script(
		'main_js_script',get_stylesheet_directory_uri().'/includes/js/main.js',
		array( 'jquery' ),time()
	);

	wp_enqueue_script(
		'moment_js_script',get_stylesheet_directory_uri().'/includes/js/moment.min.js',
		array( 'jquery' ),time()
	);
    wp_enqueue_script(
		'fullcalendar_js_script',get_stylesheet_directory_uri().'/includes/js/fullcalendar.min.js',
		array( 'jquery' ),time()
	);
	wp_enqueue_script(
		'custom_validator_script','https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js',
		array( 'jquery' )
	);
	wp_enqueue_script(
		'custom_cstm_script',get_stylesheet_directory_uri().'/cstm.js?v2d',
		array( 'jquery' ),time()
	);
	
	wp_enqueue_script(
		'add-timeslot',get_stylesheet_directory_uri().'/custom-calenders-js/add-timeslot.js',
		array( 'jquery' ),time()
	);
	wp_enqueue_script(
		'edit-booking',get_stylesheet_directory_uri().'/custom-calenders-js/edit-bookings.js',
		array( 'jquery' ),time()
	);

}
add_action( 'wp_enqueue_scripts', 'scisco_child_theme_styles');


add_action( 'init', 'add_helpers' );
define('THEME_PATH_URI',dirname(__FILE__));

function add_helpers(){
	require_once(THEME_PATH_URI.'/helpers.php');
}


add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

add_filter('login_redirect', 'member_default_page');
function member_default_page() {
	return home_url();
}

add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
	wp_safe_redirect( home_url() );
	exit;
}

function ht1_change_avatar($args, $id_or_email) {
	$gender = get_user_meta($id_or_email, 'scisco_cmb2_gender', true);
	if($gender=='male'){
		$myavatar = 'https://cannaconnects.io/wp-content/uploads/2022/01/DD28985F-515C-478C-AAA8-D4468E19D381.png';
	}else{
		$myavatar = 'https://cannaconnects.io/wp-content/uploads/2020/12/logo3.png';
	}
	$args['url'] = $myavatar;
	return $args;
}
//add_filter('get_avatar_data', 'ht1_change_avatar', 100, 2);

// callback function
/*function show_login_message() {
	?>
	<div class="custom-model-main">
		<div class="custom-model-inner">        
			<div class="custom-model-wrap">
				<div class="pop-up-content-wrap">
					<strong style="color:red">Thanks for reading Cannaconnects</strong></br> Create your free account or log in to continue reading. 
				</div>
				<a class="btn btn-success btn-sidenav" href="<?php echo wp_login_url(); ?>" style="margin: 163px;">LOGIN</a>
			</div>  
		</div>  
		<div class="bg-overlay"></div>
	</div>
	<script>
		jQuery(document).ready(function($) {
			showpopup();
		});

	</script>
	<?php		
}*/

function subscription_gateway(){
	?>
	<div class="custom-model-main">
		<div class="custom-model-inner">        
			<div class="custom-model-wrap">
				<div class="pop-up-content-wrap">
					<strong style="color:red">You have reached your daily allotment limit!!!</strong></br> Kindly subscribe to our cheap and affordable pack now to read further. 
				</div>
				<a class="btn btn-success btn-sidenav" href="<?php echo site_url(); ?>/signing-in/" style="margin-left: 121px;">SUBSCRIBE</a>
			</div>  
		</div>  
		<div class="bg-overlay"></div>
	</div>
	<script>
		jQuery(document).ready(function($) {
			showpopup();
		});

	</script>
	<?php		
}

add_action('wp_login', 'add_custom_cookie',10, 2);

function add_custom_cookie($user_login, $user) {
	$get_data = get_user_meta($user->data->ID);
	if(!isset($_COOKIE['visit-counter'])){
		setcookie('visit-counter',1, time() + (60 * 86400),'/');
	}
	$user_info = get_userdata($user->data->ID);
	$email = $user_info->user_email;
	$first_name = get_user_meta($user->data->ID,'first_name',true);
	$last_name = get_user_meta($user->data->ID,'last_name',true);
	$scisco_cmb2_type = get_user_meta($user->data->ID,'scisco_cmb2_type',true);
	$wiki_test_multicheckbox = get_user_meta($user->data->ID,'wiki_test_multicheckbox',true);
	$county_states = get_user_meta($user->data->ID,'scisco_cmb2_county_states',true);
	$phone = get_user_meta($user_id, 'scisco_cmb2_user_phone', true);
	$city = get_user_meta($user_id, 'scisco_cmb2_user_city', true);
	$u_company_name = get_user_meta($user_id, 'u_company_name', true);
	$timeinquery = time();
    if(!empty($first_name) && !empty($last_name) && !empty($email) && !empty($county_states) && !empty($scisco_cmb2_type) && !empty($wiki_test_multicheckbox) && !empty($phone) && !empty($u_company_name) && !empty($city)){
		//wp_redirect(home_url('book-a-meeting'));
		wp_redirect('https://cannaconnects.io/book-a-meeting?v='.$timeinquery, 301 );
        exit();
    }else{
    	//wp_redirect(home_url('questions/profile/'.$user_login.'/edit-profile'));
		wp_redirect('https://cannaconnects.io/questions/profile/'.$user_login.'/edit-profile?v='.$timeinquery, 301 );
        exit();
    }   
}

add_action('wp_footer','restrict_user_access');

function restrict_user_access(){
	if(is_user_logged_in()){
		$current_user = wp_get_current_user();
		$email = $current_user->user_email;

		$roles = ( array ) $current_user->roles;
		$user_role = $roles[0];

		global $wpdb;
		$tablename = $wpdb->prefix.'subscribers_waiting_list';
		$sql = $wpdb->get_results("SELECT * FROM $tablename WHERE email='".$email."'");
		foreach($sql as $key){
			$subscription_stat = $key->status;
		}
	}
//echo "role: ";print_r($user_role);
//echo "email: ".$email;
	$restriction = get_field('restricted');
	if($restriction == "" ||$restriction == null){
		$restriction = "true";
	}
//echo "restriction :".$restriction;
	if(!(is_user_logged_in()) &&  is_anspress()){
		echo show_login_message();
	}
	if((is_user_logged_in()) && $restriction == "true" && $subscription_stat !== "Active" && $user_role !== "administrator"){
		//echo subscription_gateway();
	}

} 


function add_submenu_users_page() {
	if ( current_user_can( 'edit_users' ) ) {
		$parent = 'users.php';
	} else {
		$parent = 'profile.php';
	}
	add_submenu_page( $parent,'Waiting List', 'Waiting List', 'manage_options', 'waiting_list', "waiting_list_data", 3 );
}

add_action("admin_menu",'add_submenu_users_page');

function waiting_list_data(){
	get_template_part("templates/waiting");
}

add_action("wpcf7_submit", "update_table_waiting_list", 10, 2);

function update_table_waiting_list($form, $result) {
	if( !class_exists('WPCF7_Submission') )
		return;
	$submission = WPCF7_Submission::get_instance();
	if ($result["status"] == "mail_sent") {
		$id = $_POST['_wpcf7']; 
		$posted_data = $submission->get_posted_data();

		save_posted_data($posted_data,$id);
	}
};

function save_posted_data($posted_data,$id){
	if($id !=10380){
		return;
	} 
	global $wpdb;

	$wpdb->insert( 
		$wpdb->prefix.'subscribers_waiting_list',
		array(
			'email'=>$posted_data['your-email'],
			'name'=>$posted_data['your-name'],
			'plan'=>$posted_data['plan'][0]
		),
		array('%s','%s','%s')
	);

} 


add_action( 'wp_ajax_redeem_code', 'create_randomredeem_code' );
function create_randomredeem_code() { 
	$email=$_POST['user_email'];
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
	srand((double)microtime()*1000000); 
	$i = 0; 
	$pass = '' ; 

	while ($i <= 14) { 
		$num = rand() % 33; 
		$tmp = substr($chars, $num, 1); 
		$pass = $pass . $tmp; 
		$i++; 
	} 
	global $wpdb;
	$tablename = $wpdb->prefix.'subscribers_waiting_list';
	$sql = $wpdb->prepare("UPDATE $tablename SET redeem_code ='".$pass."' WHERE email='".$email."'");
	$result = $wpdb->query($sql);
	if($result == 0){
		echo"error";
	}else{
		redeem_code_mail($pass,$email);
	}

} 

function redeem_code_mail($password,$email){
	$en_email=base64_encode($email);
	$to = $email;
	$subject = "Redeem Code for Signing in Real Cannabis";
	$message = "
	<html>
	<head>
	<style>
	table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
	}

	td, th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even) {
		background-color: #dddddd;
	}
	</style>
	<title>Thanks for signing in:-</title>
	</head>
	<body>
	<p>Please dont's share your redeem code with anyone</p>
	<table solid>
	<tr>
	<th>Email</th>
	<th>Redeem Code</th>
	</tr>
	<tr>
	<td>".$email."</td>
	<td>".$password."</td>
	</tr>
	</table>
	</body>
	<a href=".site_url()."/signing-in/?login=".$en_email.">".site_url()."/signing-in/?login=".$en_email."</a>
	</html>
	";

// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
	$headers .= 'From: <optins@blazinmultimedia.com>' . "\r\n";

	mail($to,$subject,$message,$headers);
}

add_shortcode("redeem_code_form","display_redeem_form");

function display_redeem_form(){
	ob_start();
	if(isset($_GET['login'])){
		$email = base64_decode($_GET['login']);
	}	
	?>
	<div class="redeem-code">

		<form class="code-redeem">
			<label>Please Enter Redeem Code ,Sent on your registered Email id :- </label>
			<input type="text" name="promocode" id="promocode" maxlength="15" style="text-transform:uppercase" />
			<button type="submit" id="redeem_verification">Redeem</button>
		</form>
		<span style="color:red;font-size: medium;font-weight: 600;" id="error_redeem"></span>
	</div>
	<script>
		$(".code-redeem").submit(function(e){
			e.preventDefault();
			var data = {};
			var code = $("#promocode").val().toUpperCase();
			var email = "<?php echo $email; ?>";
			data.Code = code;
			data.Email = email; 
			jQuery.ajax({
				type : "post",
				url : ajaxurl,
				data : {action: "verify_redeem_code",
				fields:data},
				success: function(response) {
					console.log(response);
					$("#error_redeem").html(response);
				}
			});

		});
	</script>
	<?php
	return ob_get_clean();
}


add_action( 'wp_ajax_verify_redeem_code', 'verify_redeem_code' );
add_action( 'wp_ajax_nopriv_verify_redeem_code', 'verify_redeem_code' );
function verify_redeem_code(){
	$fetch = $_POST['fields'];
	$email = $fetch['Email'];
	$code = $fetch['Code'];
	$activate_date = date("Y/m/d");
	if($email== ""||$email== null){
		echo "Email not found!Please Signup again.";die();
	}
	elseif($code==""||$code== null){
		echo "Code not found!Please Signup again.";die();
	}else{
		global $wpdb;
		$tablename = $wpdb->prefix.'subscribers_waiting_list';
		$sql = $wpdb->prepare("UPDATE $tablename SET redeem_code ='',status = 'Active',date_activated ='".$activate_date."' WHERE email='".$email."' AND redeem_code='".$code."'");
		$result = $wpdb->query($sql);	
		if($result == 0){
			echo"There is some error.Your code is not valid please sign in again!";die();
		}else{
			echo "Subscription availed";die();
		}

	}


}

add_filter( 'ap_question_form_fields', 'customize_category_text');

function customize_category_text($form){
	if ( wp_count_terms( 'question_category' ) == 0 ) { // phpcs:ignore
			return $form;
		}
		
		$editing_id  = ap_sanitize_unslash( 'id', 'r' );
		$category_id = ap_sanitize_unslash( 'category', 'r' );

		$form['fields']['category'] = array(
			'label'    => __( 'What are you looking for?', 'anspress-question-answer' ),
			'desc'     => __( 'Select a topic that best fits your question.', 'anspress-question-answer' ),
			'type'     => 'select',
			'options'  => 'terms',
			'order'    => 2,
			'validate' => 'required,not_zero',
		);

		// Add value when editing post.
		if ( ! empty( $editing_id ) ) {
			$categories = get_the_terms( $editing_id, 'question_category' );

			if ( $categories ) {
				$form['fields']['category']['value'] = $categories[0]->term_id;
			}
		} elseif ( ! empty( $category_id ) ) {
			$form['fields']['category']['value'] = (int) $category_id;
		}

		return $form;
}


// display event calender
add_action( 'wp_ajax_display_events_function', 'display_events_function' );
add_action( 'wp_ajax_nopriv_display_events_function', 'display_events_function' );
function display_events_function(){
	global $wpdb;
	$current_user_id = get_current_user_id();
	$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}event_calender WHERE current_user_id = %d", $current_user_id);
	$results = $wpdb->get_results($query, ARRAY_A);
	echo json_encode($results);die;
	// if ($results) {
	//     foreach ($results as $row) {
	//         $event_id = $row['event_id'];
	//         $title = $row['title'];
	//     }
	// }


 //   $json_data = '{
	// 	  "status": true,
	// 	  "msg": "successfully!",
	// 	  "data": {
	// 	    "1": {
	// 	      "event_id": "2",
	// 	      "title": "test calender show",
	// 	      "start": "2024-02-02",
	// 	      "end": "2024-02-03",
	// 	      "color": "#a56025",
	// 	      "url": "https://www.shinerweb.com"
	// 	    },
	// 	    "2": {
	// 	      "event_id": "3",
	// 	      "title": "calender test",
	// 	      "start": "2024-02-03",
	// 	      "end": "2024-02-04",
	// 	      "color": "#a5603f",
	// 	      "url": "https://www.shinerweb.com"
	// 	    },
	// 	    "3": {
	// 	      "event_id": "4",
	// 	      "title": "dwds",
	// 	      "start": "2024-02-09",
	// 	      "end": "2024-02-10",
	// 	      "color": "#a56054",
	// 	      "url": "https://www.shinerweb.com"
	// 	    },
	// 	    "4": {
	// 	      "event_id": "5",
	// 	      "title": "sdsdasd",
	// 	      "start": "2024-02-08",
	// 	      "end": "2024-02-09",
	// 	      "color": "#a56069",
	// 	      "url": "https://www.shinerweb.com"
	// 	    }
	// 	  }
	// 	}';
	// 	echo $json_data;

		die;
}

//save event calender
add_action( 'wp_ajax_save_event_function', 'save_event_function' );
add_action( 'wp_ajax_nopriv_save_event_function', 'save_event_function' );
function save_event_function(){
	if(isset($_POST['formData'])){
		$current_user_id = $_POST['current_user_id'];
		$current_date_select = $_POST['current_date_select'];
		$formData = json_encode($_POST['formData']);
		global $wpdb;
		$data = array(
		    'current_user_id' => $current_user_id,
		    'event_name' => '',
		    'event_current_date' => $current_date_select,
		    'event_time_slot' => $formData,
		);
		$result = $wpdb->insert(
		    $wpdb->prefix . 'event_calender', // Table name
		    $data // Data to be inserted
		);
		if ($result !== false) {
		    $inserted_id = $wpdb->insert_id;
		   	global $wpdb;
			$current_user_id = $current_user_id; 
			$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}request_availability WHERE client_id = %d",$current_user_id);
			$result = $wpdb->get_results($query, ARRAY_A);
			if(!empty($result)){
				foreach ($result as $key => $value) {
					if($value['set_availability'] == 'no'){
						$data = array(
						    'set_availability' => 'yes',
						);
						$where = array(
						    'id' => $value['id'], // Assuming event_id is the primary key of your table
						);
						$result = $wpdb->update(
						    $wpdb->prefix . 'request_availability', // Table name
						    $data,
						    $where // WHERE condition
						);
						if ($result !== false) {
					        $user_data = get_userdata($value['user_id']);
					        $user_email = $user_data->user_email;
					        $user_name = $user_data->first_name;
					        $client_data = get_userdata($current_user_id);
					        $client_name = $client_data->first_name;
					        $client_email = $client_data->user_email;
					        $subject = 'Availability Times Added By '.$client_name.'';
					        $message = "Heyyyy $user_name,\n\nI wanted to let you know that I've added my availability times. Could you please check?\n\nThank you!";
					        $headers = 'From: ' .  $client_email . "\r\n"; // Set the sender email address here
					        
					        if (mail($client_email, $subject, $message, $headers)) { //'devuthopian@gmail.com'
					            echo json_encode(array('status'=>'200','success'=>'Email sent to client successfully.'));
					        } else {
					            echo ' Error sending email to client.';
					        }
						     echo json_encode(array('message'=>'success','html'=>'Data updated successfully.'));die;
						} else {
						     echo json_encode(array('message'=>'error','html'=>$wpdb->last_error));die;
						}
					}
				}
			}
			
		} else {
		    echo 'Error inserting data: ' . $wpdb->last_error;
		}
		echo '1';
	}
	die;
}


//show event popup
function show_popup_event_function(){
	if(isset($_POST['event_id'])){
		global $wpdb;
		$current_user_id = get_current_user_id();
		$event_id = $_POST['event_id']; 
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}event_calender WHERE current_user_id = %d AND event_id = %d", $current_user_id, $event_id);
		$result = $wpdb->get_row($query, ARRAY_A);
		$explode_time_slot = json_decode($result['event_time_slot']);
		$html = '<div><div class="modal-header"><h5 class="modal-title" id="modalLabel">Set Your Availability for Mettings</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="error_message_show"></div>
            <input type="hidden" name="current_user_id" id="current_user_id" value="' . get_current_user_id() . '" class="form-control" placeholder=""><input type="hidden" name="current_event_select" id="current_event_select" value="'.$result['event_id'].'" class="form-control" placeholder=""><div class="modal-body"><div class="img-container"><div class="row"><div class="col-sm-12"><div>Add Slot</div></div></div><div class="multi-field-wrapper"><div class="multi-fields">';
            			$key = '1';
                        foreach ($explode_time_slot as $key2 => $event){
					    $html .= '<div class="multi-field">';
					    $html .= '<div class="no-gutters row">';
					    // Start Time
					    $html .= '<div class="col-sm-5">';
					    $html .= '<div><b>Start Time:</b></div>';
					    $html .= '<select name="start_hour[' . $key . ']" class="time-select">';
					        $start_hour = 10; // Starting hour
							$end_hour = 18; 
					        for ($hour = $start_hour; $hour <= $end_hour; $hour++) {
							    $displayHour = ($hour <= 12) ? $hour : $hour - 12;
							    if ($displayHour === 0 || $displayHour === 12) {
							        $displayHour = 12;
							    }
							    $selected = ($displayHour == $event->startTime->hour) ? 'selected' : '';
							    $html .= '<option value="' . $displayHour . '" ' . $selected . '>' . sprintf("%02d", $displayHour) . '</option>';
							}
					    $html .= '</select>';
					    $html .= '<select name="start_minute[' . $key . ']" class="time-select">';
					       for ($minute = 00; $minute < 60; $minute += 15) {
					        $selected = ($minute == $event->startTime->minute) ? 'selected' : '';
					        $html .= '<option value="' .sprintf("%02d", $minute) . '" ' . $selected . '>' . sprintf("%02d", $minute) . '</option>';
					    }
					    $html .= '</select>';
					    $html .= '<select name="start_ampm[' . $key . ']" class="time-select">';
					    $html .= '<option value="AM" ' . (($event->startTime->ampm == 'AM') ? 'selected' : '') . '>AM</option>';
					    $html .= '<option value="PM" ' . (($event->startTime->ampm == 'PM') ? 'selected' : '') . '>PM</option>';
					    $html .= '</select>';
					    $html .= '</div>';
					    // End Time
					    $html .= '<div class="col-sm-5">';
					    $html .= '<div><b>End Time:</b></div>';
					    $html .= '<select '.$event->endTime->hour.' name="end_hour[' . $key . ']" class="time-select">';
					        for ($hour = 10; $hour <= 18; $hour++) { // Loop from 10 AM (10) to 6 PM (18)
							    $displayHour = ($hour <= 12) ? $hour : $hour - 12;
							    if ($displayHour === 0 || $displayHour === 12) {
							        $displayHour = 12;
							    }
							    
							    $selected = ($displayHour == $event->endTime->hour) ? 'selected' : ''; // Adjust as needed based on your requirements
							    $html .= '<option value="' . $displayHour . '" ' . $selected . '>' . sprintf("%02d", $displayHour) . '</option>';
							}
						$html .= '</select>';
					    $html .= '<select name="end_minute[' . $key . ']" class="time-select">';
					       for ($minute = 00; $minute < 60; $minute += 15) {
						        $selected = ($minute == $event->endTime->minute) ? 'selected' : '';
						        $html .= '<option value="' . sprintf("%02d", $minute) . '" ' . $selected . '>' . sprintf("%02d", $minute) . '</option>';
						    }
					    $html .= '</select>';
					    $html .= '<select name="end_ampm[' . $key . ']" class="time-select">';
					    $html .= '<option value="AM" ' . (($event->endTime->ampm == 'AM') ? 'selected' : '') . '>AM</option>';
					    $html .= '<option value="PM" ' . (($event->endTime->ampm == 'PM') ? 'selected' : '') . '>PM</option>';
					    $html .= '</select>';
					    $html .= '</div><div class="col-sm-2"><button type="button" class="remove-field">Remove</button></div>';
					    $html .= '</div>'; // .no-gutters .row
					    $html .= '</div>'; // .multi-field
					    $key++;
					}
					    $html .= '</div><div class="row">
					                 <div class="col-sm-12"> 
					                     <button type="button" class="add-field">Add Another Time Slot</button>
					                  </div>
					              </div>
					            </div>
					          </div>
					        <div class="modal-footer">
					        <button type="button" class="add-field" onclick="edit_event()"><span class="spinner-border spinner-border-sm spinner_border_custom" style="display:none;"></span>Save Availability Times</button>
					        </div>
					    </div>';
	  echo json_encode(array('message'=>'success','html'=>$html));
die;
	}

}
add_action( 'wp_ajax_show_popup_event_function', 'show_popup_event_function' );
add_action( 'wp_ajax_nopriv_show_popup_event_function', 'show_popup_event_function' );

// edit event 
add_action( 'wp_ajax_edit_event_function', 'edit_event_function' );
add_action( 'wp_ajax_nopriv_edit_event_function', 'edit_event_function' );
function edit_event_function(){
	if(isset($_POST['formData'])){
		$event_name = $_POST['event_name'];
		$current_event_id = $_POST['current_event_select'];
		$formData = json_encode($_POST['formData']);
		global $wpdb;
		$data = array(
		    'event_name' => $event_name,
		    'event_time_slot' => $formData,
		);
		$where = array(
		    'event_id' => $current_event_id, // Assuming event_id is the primary key of your table
		);
		$result = $wpdb->update(
		    $wpdb->prefix . 'event_calender', // Table name
		    $data,
		    $where // WHERE condition
		);
		if ($result !== false) {
		     echo json_encode(array('message'=>'success','html'=>'Data updated successfully.'));
		} else {
		     echo json_encode(array('message'=>'error','html'=>$wpdb->last_error));
		}
		echo '1';
	}
	die;
}

function show_time_slots_cb(){
	if(isset($_POST['event_id'])){
		global $wpdb;
		$event_id = $_POST['event_id']; 
		//echo $event_id;
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}event_calender WHERE event_id = %d",$event_id);
		$result = $wpdb->get_row($query, ARRAY_A);
		$timeSlots = json_decode($result['event_time_slot'],true);
		$html = '<div class="modal-content">';
        $html .= '<div class="modal-header">';
        $html .= '<h5 class="modal-title" id="modalLabel">Select Time Slots</h5>';
        $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
        $html .= '</div>';
        $html .= '<div class="modal-body">';
        $html .= '<label for="topic">Topic</label> ';
        $html .= '<input type="text" id="topic" name="topic" required>';
        $html .= '<input type="hidden" id="event_id" name="event_id" value="'.$result['event_id'].'">';
        $html .= '<input type="hidden" id="client_id" name="client_id" value="'.$result['current_user_id'].'">';
        $html .= '<input type="hidden" id="current_user_id" name="current_user_id" value="'.get_current_user_id().'">';
        $html .= '<input type="hidden" id="event_current_date" name="event_current_date" value="'.$result['event_current_date'].'">';
        $html .= '<div class="radio-button-group"> ';
        $html .= '<h6> Available Times</h6>';
        if (!empty($timeSlots)) {
          $current_user_id = get_current_user_id();
			$query1 = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}book_slot WHERE current_user_id = %d AND status = 'booked'", $current_user_id);
			$book_slot = $wpdb->get_results($query1, ARRAY_A);
			// echo "<pre>";print_r($book_slot);
        foreach ($timeSlots as $index => $timeSlot) {
            $startTime = $timeSlot['startTime']['hour'] . ':' . $timeSlot['startTime']['minute'] . ' ' . $timeSlot['startTime']['ampm'];
            $endTime = $timeSlot['endTime']['hour'] . ':' . $timeSlot['endTime']['minute'] . ' ' . $timeSlot['endTime']['ampm'];
            $booked_time = $startTime . ' - ' . $endTime;
            $isBooked = false;
	        // Check if any booked slot matches the current time slot
	        foreach ($book_slot as $slot) {
	            if ($slot['slot_time'] == $booked_time) {
	                $isBooked = true;
	                break; // No need to continue checking if already booked
	            }
	        }
	        $inputAttributes = '';
	        $addClass = '';
	        if ($isBooked) {
	            $inputAttributes = 'disabled="disabled"';
	            $addClass = "booked-time-slot";
	        }

            $html .= '<div class="radio-button-wrap '.$addClass.'">';
            $html .= '<input type="radio" name="timeSlot" value="' . $startTime . ' - ' . $endTime . '" '.$inputAttributes.'>'; 
            $html .= '<label >';
            $html .= ' ' . $startTime . ' - ' . $endTime;
            $html .= '</label>';
            $html .= '</div>';
            $html .= ' ';
        }
         $html .= '</div>';
    	}else{
        // Handle the case where $timeSlots is empty
        $html = '<p>No available time slots.</p>';
    	}
		$html .= '<div class="modal-footer"><button type="button" class="add-field" onclick="book_slot()"><span class="spinner-border spinner-border-sm spinner_border_custom" style="display:none;"></span>Book Slot</button></div>';
		$html .= '</div>';
		$html .= '</div>';
	  echo json_encode(array('message'=>'success','html'=>$html));
die;
	}

}
add_action( 'wp_ajax_show_time_slots', 'show_time_slots_cb' );
add_action( 'wp_ajax_nopriv_show_time_slots', 'show_time_slots_cb' );


add_action( 'wp_ajax_book_time_slot', 'book_time_slot' );
add_action( 'wp_ajax_nopriv_book_time_slot', 'book_time_slot' );
function book_time_slot(){
	if(isset($_POST['current_date_select'])){
		$topic = $_POST['current_date_select'];
		$current_user_id = $_POST['current_user_id'];
		$client_id = $_POST['client_id'];
		$current_date_select = $_POST['current_date_select'];
		$event_id = $_POST['event_id'];
		$selectedSlot = $_POST['selectedSlot'];
		$notes = $_POST['notes'];
		global $wpdb;
		$table = $wpdb->prefix . 'book_slot';
		$query = $wpdb->prepare("SELECT * FROM $table WHERE current_user_id = %d AND client_id = %d AND slot_time = %d" , $current_user_id, $client_id,$selectedSlot);
        $result = $wpdb->get_row($query, ARRAY_A);
        if(empty($result)){
        	$data = array(
			    'current_user_id' => $current_user_id,
			    'client_id' => $client_id,
			    'event_id' => $event_id,
			    'slot_date' => $current_date_select,
			    'slot_time' => $selectedSlot,
			    'notes' => $notes,
			);
			$result = $wpdb->insert(
			    $wpdb->prefix . 'book_slot', // Table name
			    $data // Data to be inserted
			);
        }else{
        	$data = array(
			    'notes' => $notes,
			);
			$where = array(
			    'book_id' => $result['book_id'], 
			);

			$result = $wpdb->update(
			    $wpdb->prefix . 'book_slot', 
			    $data,
			    $where // WHERE condition
			);
        }
		
		if ($result !== false) {
		    $inserted_id = $wpdb->insert_id;
		     // Send email to client
	        $client_data = get_userdata($client_id);
	        $current_user_data = get_userdata($current_user_id);
	        $current_user_email = $current_user_data->user_email;
	        $current_user_name = $current_user_data->first_name;
        	$client_email = $client_data->user_email; // Set the client's email address here
        	$client_name = $client_data->first_name; // Set the client's email address here

	        $subject = '[MEETING REQUEST] Real Canna Conference Meeting Request for You '.$client_name.'';
	        $message = "Heyyyy $client_name,\n\nYou have new meeting request pending for review from $current_user_name on $current_date_select at $selectedSlot.\n\nPlease check following url to respond: http://cannaconnects.io/my-dashboard/.\n\nThank you!";
	        $headers = 'From: ' . $current_user_email . "\r\n"; // Set the sender email address here
	        
	        if (mail($current_user_email, $subject, $message, $headers)) { //'devuthopian@gmail.com'
	            echo json_encode(array('status'=>'200','success'=>'Email sent to client successfully.'));
	        } else {
	            echo ' Error sending email to client.';
	        }
		} else {
		    echo 'Error inserting data: ' . $wpdb->last_error;
		}
	}
	die;
}

add_action( 'wp_ajax_update_booking_status', 'update_booking_status_cb' );
add_action( 'wp_ajax_nopriv_update_booking_status', 'update_booking_status_cb' );

function update_booking_status_cb(){

	if(isset($_POST['BookId'])){
		$book_id = $_POST['BookId'];
		$status = $_POST['status'];
		global $wpdb;
		$data = array(
		    'status' => $status,
		);
		$where = array(
		    'book_id' => $book_id, 
		);

		$result = $wpdb->update(
		    $wpdb->prefix . 'book_slot', 
		    $data,
		    $where // WHERE condition
		);
		if ($result !== false) {
			$table = $wpdb->prefix . 'book_slot';
			$query = $wpdb->prepare("SELECT * FROM $table WHERE book_id = %d", $book_id);
            $result = $wpdb->get_row($query, ARRAY_A);
			$client_data = get_userdata($result['client_id']);
	        $current_user_data = get_userdata($result['current_user_id']);
	        $current_user_email = $current_user_data->user_email;
	        $current_user_name = $current_user_data->display_name;
        	$client_email = $client_data->user_email; // Set the client's email address here
        	$client_name = $client_data->display_name; // Set the client's email address here
        	$current_date_select = $result['slot_date'];
        	$selectedSlot = $result['slot_time'];
        	$status = $result['status'];
	        $subject = 'Conference Request Status.';
	        if($status == "canceled"):
	        $message = "Dear $current_user_name,<br>Your request for appointment with $client_name on $current_date_select at $selectedSlot is $status by $client_name.<br>Please visit site to reschedule your appointement.<br> <a href='http://cannaconnects.io/book-a-meeting/''>Click to reschedule</a>.<br>Thank you!";
	       else:
	       	$message = "Dear $current_user_name,<br>Your  request for appointment with $client_name on $current_date_select at $selectedSlot is $status by $client_name.<br>Please visit site to update/sync. event on your goggle calendar<br>http://cannaconnects.io/my-dashboard/.<br>Thank you!";
	       endif;
	        $action_message = "Dear $client_name,<br>Your have update conference request of $current_user_name on $current_date_select at $selectedSlot to $status.<br>Please visit site to update/sync. event on your goggle calendar<br>http://cannaconnects.io/my-dashboard/.<br>Thank you!";
	        
	        $headers = 'From: ' . $client_email . "\r\n".
	                    'Content-Type: text/html; charset=UTF-8';
	        $headers_2 = 'From: ' . $current_user_email . "\r\n".
	                      'Content-Type: text/html; charset=UTF-8';

	        if (mail($client_email, $subject, $action_message, $headers_2)){
	        mail($current_user_email, $subject, $message, $headers);
		    echo json_encode(array('message'=>'success','html'=>'Data updated successfully.'));wp_die();
		    }
		} else {
		     echo json_encode(array('message'=>'error','html'=>$wpdb->last_error));wp_die();
		}
	}
	die;

}

add_action( 'wp_ajax_sync_now_google_calender_url', 'sync_now_google_calender_url' );
add_action( 'wp_ajax_nopriv_sync_now_google_calender_url', 'sync_now_google_calender_url' );

function sync_now_google_calender_url(){
	if(isset($_POST['bookId'])){
		$book_id = $_POST['bookId'];
		setcookie('sync_book_id', $book_id, time() + (86400 * 30), "/");
		if ($result !== false) {
		     echo json_encode(array('message'=>'success','html'=>'Data updated successfully.'));wp_die();
		} else {
		     echo json_encode(array('message'=>'error'));wp_die();
		}
	}
	die;
}

add_action( 'wp_ajax_sync_now_google_calender_email', 'sync_now_google_calender_email' );
add_action( 'wp_ajax_nopriv_sync_now_google_calender_email', 'sync_now_google_calender_email' );

function sync_now_google_calender_email(){
	if(isset($_POST['bookId'])){
		$book_id = $_POST['bookId'];
		$calander_url = $_POST['calander_url'];
		$html = '<div class="modal-content">';
        $html .= '<div class="modal-header">';
        $html .= '<h5 class="modal-title" id="modalLabel">Sync With Google Calender Email</h5>';
        $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
        $html .= '</div>';
        $html .= '<div class="sync_message_show" style="margin-left:24px;"></div>';
        $html .= '<div class="modal-body">';
        $html .= '<div class="modal-body">';
        $html .= '<div class="form-group">';
        $html .= '<label for="emailInput">Email:</label>';
        $html .= '<input type="email" class="form-control" name="sync_email" id="sync_email" placeholder="Enter your email">';
        $html .= '<input type="hidden" class="form-control" data-book_id='.$book_id.' value='.$calander_url.' name="calander_url" id="calander_url">';
        $html .= '</div>';
        $html .= '</div>';
		$html .= '<div class="modal-footer"><button type="button" class="add-field sync_email_button"><span class="spinner-border spinner-border-sm" style="display:none;"></span>Save</button></div>';
		$html .= '</div>';
		$html .= '</div>';
		if ($result !== false) {
		     echo json_encode(array('message'=>'success','html'=>$html));wp_die();
		} else {
		     echo json_encode(array('message'=>'error'));wp_die();
		}
	}
	die;
}

add_action( 'wp_ajax_cancel_appointment_google_calender', 'cancel_appointment_google_calender' );
add_action( 'wp_ajax_nopriv_cancel_appointment_google_calender', 'cancel_appointment_google_calender' );

function cancel_appointment_google_calender(){
	if(isset($_POST['BookId'])){
		$book_id = $_POST['BookId'];
		$status = $_POST['status'];
		setcookie('cancel_book_id', $book_id, time() + (86400 * 30), "/");
		setcookie('cancel_status', $status, time() + (86400 * 30), "/");
		if ($result !== false) {
		     echo json_encode(array('message'=>'success','html'=>'Data updated successfully.'));wp_die();
		} else {
		     echo json_encode(array('message'=>'error'));wp_die();
		}
	}
	die;
}


// 29-04-2024
add_action( 'edit_user_profile', 'user_profile_image_form' );

// Function to handle form submission
function handle_user_profile_image_submission() {
    if ( isset( $_POST['submit_user_profile_image'] ) && isset( $_FILES['user_profile_image'] ) ) {
        $user_id = isset( $_POST['scisco_user_id'] ) ? intval( $_POST['scisco_user_id'] ) : 0;
        // Check if user ID is valid
        if ( $user_id && get_userdata( $user_id ) ) {
            $image_file = $_FILES['user_profile_image'];
            // Check for errors
            if ( $image_file['error'] === 0 ) {
                // Process and save the image
                $upload_dir = wp_upload_dir();
                $image_name = sanitize_file_name( $image_file['name'] );
                $image_path = $upload_dir['path'] . '/' . $image_name;
                if ( move_uploaded_file( $image_file['tmp_name'], $image_path ) ) {
                    // Update user meta with the image URL
                    update_user_meta( $user_id, 'scisco_cmb2_user_avatar', $upload_dir['url'] . '/' . $image_name );
                   $attachment = array(
		                'post_mime_type' => $image_file['type'],
		                'post_title' => sanitize_file_name($image_file['name']),
		                'post_content' => '',
		                'post_status' => 'inherit'
		            );
		            $attach_id = wp_insert_attachment($attachment, $image_path);
            		require_once(ABSPATH . 'wp-admin/includes/image.php');
		            // Define attachment metadata
		            $attach_data = wp_generate_attachment_metadata($attach_id, $image_path);
		            wp_update_attachment_metadata($attach_id, $attach_data);
		            update_user_meta( $user_id, 'scisco_cmb2_user_avatar_id', $attach_id );
                }
            }
        }
    }
}
// Hook into user profile update
add_action( 'init', 'handle_user_profile_image_submission' );

add_action( 'wp_ajax_custom_description_add_User', 'custom_description_add_User' );
add_action( 'wp_ajax_nopriv_custom_description_add_User', 'custom_description_add_User' );
function custom_description_add_User(){
  if(!empty($_POST['data']['description'])){
    $user_id = trim($_POST['data']['scisco_user_id']);
    update_user_meta($user_id,'scisco_cmb2_resume',$_POST['data']['description']);
    echo json_encode(array('message'=>'success','html'=>'Description updated successfully.'));wp_die();
  }
}

add_action( 'wp_ajax_appointment_custom_notes', 'appointment_custom_notes' );
add_action( 'wp_ajax_nopriv_appointment_custom_notes', 'appointment_custom_notes' );
function appointment_custom_notes(){
  if(!empty($_POST['data']['description'])){
    $book_id = trim($_POST['data']['book_id']);
    global $wpdb;
		global $wpdb;
		$data = array(
		    'notes' => $_POST['data']['description'],
		);
		$where = array(
		    'book_id' => $book_id, 
		);

		$result = $wpdb->update(
		    $wpdb->prefix . 'book_slot', 
		    $data,
		    $where // WHERE condition
		);
		if ($result !== false) {
		    echo json_encode(array('message'=>'success','html'=>'Notes updated successfully.'));wp_die();
		}
    }
}


function schedule_Metting_function(){
	if($_POST['schedule'] === 'Yes'){
		if(isset($_POST['user_id'])){
			global $wpdb;
			$table = $wpdb->prefix.'event_calender';
			$query = $wpdb->prepare("SELECT * FROM $table WHERE current_user_id=%s;", $_POST['user_id'] );
			$results = $wpdb->get_results($query, ARRAY_A);
			if(!$results){
				global $wpdb;
				$currentYear = date("Y");
				$data = array(
					'current_user_id' => $_POST['user_id'],
					'event_name' => '',
					'event_current_date' => $currentYear.'-10-18',
					'event_time_slot' => '[{"startTime":{"hour":"10","minute":"0","ampm":"AM"},"endTime":{"hour":"5","minute":"0","ampm":"PM"}}]',
				);
				$result = $wpdb->insert(
					$wpdb->prefix . 'event_calender', // Table name
					$data // Data to be inserted
				);
				$data1 = array(
					'current_user_id' => $_POST['user_id'],
					'event_name' => '',
					'event_current_date' => $currentYear.'-10-19',
					'event_time_slot' => '[{"startTime":{"hour":"10","minute":"0","ampm":"AM"},"endTime":{"hour":"5","minute":"0","ampm":"PM"}}]',
				);
				$result1 = $wpdb->insert(
					$wpdb->prefix . 'event_calender', // Table name
					$data1 // Data to be inserted
				);
				$inserted_id = $wpdb->insert_id;
				$query = $wpdb->prepare("SELECT * FROM $table WHERE current_user_id=%s;", $_POST['user_id'] );
				$results = $wpdb->get_results($query, ARRAY_A);	
			}
		}
		if(isset($_POST['user_id'])){
			global $wpdb;
			$html = '<div class="modal-content">';
	        $html .= '<div class="modal-header">';
	        $html .= '<h5 class="modal-title" id="modalLabel">Schedule a Meeting!</h5>';
	        $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
	        $html .= '</div>';
	        $html .= '<div class="success_message_show"></div>';
	        $html .= '<div class="modal-body">';
	        $html .= '<div class="start_time_custom"><h6>Select a Day:</h6></div>';
	        $html .= '<select class="select_available_date" data-user_id='.$_POST['user_id'].'>';
	        $html .= '<option value="">Select Date</option>';
			foreach ($results as $key => $value) {
				$timestamp = strtotime($value['event_current_date']);
				$formatted_date = date('D M jS, Y', $timestamp);
			    $html .= '<option data-event_id="'.$value['event_id'].'" value="'.$value['event_current_date'].'">'.$formatted_date.'</option>';
			}
			$html .= '</select>';
			$html .= '<div class="available_times">';
			$html .= '</div>';
	        $html .= '</div>';
			$html .= '<div class="modal-footer"><button type="button" class="add-field" onclick="schedule_metting()"><span class="spinner-border spinner-border-sm spinner_border_custom" style="display:none;"></span>Request a Meeting!</button></div>';
			$html .= '</div>';
			$html .= '</div>';
		  echo json_encode(array('message'=>'success','html'=>$html));
		die;
		}
	}
	die;
}

add_action( 'wp_ajax_schedule_Metting', 'schedule_Metting_function' );
add_action( 'wp_ajax_nopriv_schedule_Metting', 'schedule_Metting_function' );


add_action( 'wp_ajax_schedule_Metting_1_function', 'schedule_Metting_1_function' );
add_action( 'wp_ajax_nopriv_schedule_Metting_1_function', 'schedule_Metting_1_function' );

function schedule_Metting_1_function(){
		if(isset($_POST['user_id'])){
			global $wpdb;
			$html = '<div class="modal-content">';
	        $html .= '<div class="modal-header">';
	        $html .= '<h5 class="modal-title" id="modalLabel">Schedule a Metting!</h5>';
	        $html .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>';
	        $html .= '</div>';
	        $html .= '<div class="success_message_show"></div>';
	        $html .= '<div class="modal-body">';
	        $html .= '<div class="img-container">';
	        $html .= '</div>';
	        $html .= "<p>This person has not set their available meeting times yet! Click this button to let them know you're intersted in connecting!</p>";
	        $html .= '<div class="not_availability_set">';
	        $html .= '<a href="javascript:void(0)" data-user_id="'.$_POST['user_id'].'" class="btn btn-primary request_for_availability">Request For Set Availability!</a>';
	        $html .= '</div>';
	        $html .= '</div>';
			$html .= '<div class="modal-footer"></div>';
			$html .= '</div>';
			$html .= '</div>';
		  echo json_encode(array('message'=>'success','html'=>$html));
     die;
	}
}

add_action( 'wp_ajax_request_for_availability', 'request_for_availability_fuction' );
add_action( 'wp_ajax_nopriv_request_for_availability', 'request_for_availability_fuction' );

function request_for_availability_fuction(){
	if(isset($_POST['user_id'])){
		    //Send email to client
	        $current_user_data = get_userdata(get_current_user_id());
	        $current_user_email = $current_user_data->user_email;
	        $client_data = get_userdata($_POST['user_id']);
			$client_email = $client_data->user_email; // Set the client's email address here
        	$client_name = $client_data->first_name;

	        $subject = 'Request For Set Availability!';
	        $message = "I'd like to schedule a meeting with you but cannot until you set your availability times!";
	        $headers = 'From: ' . $current_user_email . "\r\n"; // Set the sender email address here
	        global $wpdb;
			$data = array(
			    'user_id' => get_current_user_id(),
			    'client_id' => $_POST['user_id'],
			    'set_availability' => 'no'
			);
			$result = $wpdb->insert(
			    $wpdb->prefix . 'request_availability', // Table name
			    $data // Data to be inserted
			);
			if ($result !== false) {
			    if (mail($client_email, $subject, $message, $headers)) {
		            echo json_encode(array('status'=>'200','success'=>'Email sent
		             successfully.'));
		        } else {
		            echo ' Error sending email to client.';
		        }
			} else {
			    echo 'Error inserting data: ' . $wpdb->last_error;
			}
    die;
	}

}

function available_slot_time_show_function(){
	if(isset($_POST['event_id'])){
		global $wpdb;
		$event_id = $_POST['event_id']; 
		//echo $event_id;
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}event_calender WHERE event_id = %d",$event_id);
		$result = $wpdb->get_row($query, ARRAY_A);
		$timeSlots = json_decode($result['event_time_slot'],true);
		// echo "<pre>";print_r($timeSlots);die;
		$html .= '<input type="hidden" id="event_id" name="event_id" value="'.$result['event_id'].'">';
        $html .= '<input type="hidden" id="client_id" name="client_id" value="'.$result['current_user_id'].'">';
        $html .= '<input type="hidden" id="current_user_id" name="current_user_id" value="'.get_current_user_id().'">';
        $html .= '<div class="radio-button-group"> ';
        $html .= '<h6> Available Times</h6>';
        if (!empty($timeSlots)) {
            $current_user_id = get_current_user_id();
			$query1 = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}book_slot WHERE event_id = %d AND (status = 'booked' OR status = 'pending')", $event_id);
			$book_slots = $wpdb->get_results($query1, ARRAY_A);
			$time_slots = array();
			// Loop through each time range
			foreach ($timeSlots as $range) {
			    $startTime = $range['startTime'];
			    $endTime = $range['endTime'];

			    // Define start and end time
			    $start_minute = str_pad($startTime['minute'], 2, '0', STR_PAD_LEFT); // Add leading zero if minutes less than 10
			    $end_minute = str_pad($endTime['minute'], 2, '0', STR_PAD_LEFT); // Add leading zero if minutes less than 10

			    $start_time = strtotime($startTime['hour'].':' . $start_minute . ' ' . $startTime['ampm']);
			    $end_time = strtotime($endTime['hour'] . ':' . $end_minute . ' ' . $endTime['ampm']);

			    // Define slot duration in minutes
			    $slot_duration = 15 * 60; // 15 minutes in seconds

			    // Loop through the time range and generate slots
			    $current_time = $start_time;
			    while ($current_time < $end_time) {
			        $slot_start = date('h:ia', $current_time);
			        $slot_end = date('h:ia', $current_time + $slot_duration);
			        $time_slots[] = "$slot_start $slot_end";
			        $current_time += $slot_duration;
			    }
			}
			// foreach ($time_slots as $slot) {
			//     echo $slot . "<br>";
			// }
        // foreach ($timeSlots as $index => $timeSlot) {
        // 	$timeSlots = generateTimeSlots($timeSlot['startTime'], $timeSlot['endTime'], 15);
        	if(!empty($time_slots)){
        		foreach ($time_slots as $key => $timeSlot_2) {
				    $isBooked = false;
				    $isPending = false;
				    foreach ($book_slots as $slot) {
				        if ($slot['slot_time'] == $timeSlot_2) {
				            if ($slot['status'] == 'booked') {
				                $isBooked = true;
				            } elseif ($slot['status'] == 'pending') {
				                $isPending = true;
				            }
				            break; // No need to continue checking if already booked or pending
				        }
				    }
				    $inputAttributes = '';
				    $addClass = '';
				    
				    if ($isBooked) {
				        $inputAttributes = 'disabled="disabled"';
				        $addClass = "booked-time-slot";
				    } elseif ($isPending) {
				        // $inputAttributes = 'disabled="disabled"';
				        $addClass = "pending-time-slot";
				    }

		            $html .= '<div class="radio-button-wrap '.$addClass.'">';
		            $html .= '<input type="radio" name="timeSlot" value="' . $timeSlot_2 . '" '.$inputAttributes.'>'; 
		            $html .= '<label >';
		            $html .= ' ' . $timeSlot_2;
		            $html .= '</label>';
		            $html .= '</div>';
		            $html .= ' ';
		        }
		         $html .= '</div>';
		    	}else{
		        // Handle the case where $timeSlots is empty
		        $html = '<p>No available time slots.</p>';
		    	}
				$html .= '</div>';
				
        		// }
        		$html .= '<div class="notes">';
        		$html .= '<textarea id="myappointment_text" placeholder="Notes..." rows="4" cols="50"></textarea>';
				$html .= '</div>';
        	}
        echo json_encode(array('message'=>'success','html'=>$html));
		die;
	}

}
add_action( 'wp_ajax_available_slot_time_show', 'available_slot_time_show_function' );
add_action( 'wp_ajax_nopriv_schedule_Metting', 'available_slot_time_show_function' );
function generateTimeSlots($startTime, $endTime, $interval) {
    $timeSlots = [];
    // Convert start and end times to minutes
    $startMinutes = $startTime['hour'] * 60 + $startTime['minute'];
    // Set end time to 12:00 PM
    $endMinutes = 12 * 60;
    // Generate time slots
    $currentMinutes = $startMinutes;
    while ($currentMinutes < $endMinutes) {
        // Calculate slot end time
        $slotEndMinutes = min($currentMinutes + $interval, $endMinutes);
        
        // Convert slot start and end times back to hour and minute format
        $startHour = floor($currentMinutes / 60);
        $startMinute = $currentMinutes % 60;
        $endHour = floor($slotEndMinutes / 60);
        $endMinute = $slotEndMinutes % 60;

        // Determine AM/PM for start and end times
        $startAmpm = ($startHour >= 12) ? "PM" : "AM";
        if ($startHour > 12) {
            $startHour -= 12;
        }
        $endAmpm = ($endHour >= 12) ? "PM" : "AM";
        if ($endHour > 12) {
            $endHour -= 12;
        }
        if ($endHour == 0) {
            $endHour = 12;
        }
        if ($currentMinutes < $endMinutes) {
            $timeSlots[] = [
                'startTime' => [
                    'hour' => $startHour,
                    'minute' => $startMinute,
                    'ampm' => $startAmpm
                ],
                'endTime' => [
                    'hour' => $endHour,
                    'minute' => $endMinute,
                    'ampm' => $endAmpm
                ]
            ];
        }
        $currentMinutes += $interval;
    }
    return $timeSlots;
}







add_filter( 'fep_form_fields', 'customize_fep_form_fields', 10, 2 );
// // Custom function to modify the form fields
function customize_fep_form_fields( $fields, $where ) {
	if(isset($_REQUEST['fep_to']) && !empty($_REQUEST['fep_to'])){
		if ( isset( $fields['message_title'])){
            $fields['message_title']['value'] = 'Request For Set Availability';
        }
        if ( isset( $fields['message_content'] ) ) {
            $fields['message_content']['value'] = "I'd like to schedule a meeting with you but cannot until you set your availability times!";
        }
	}
    return $fields;
}


// register page
add_action( 'user_register', 'crf_user_register' );
function crf_user_register( $user_id ) {
	if ( ! empty( $_POST['user_access_code'] ) ) {
		update_user_meta( $user_id, 'user_access_code',  $_POST['user_access_code'] );
	}
	if ( ! empty( $_POST['first_name'] ) ) {
        update_user_meta( $user_id, 'first_name', trim( $_POST['first_name']  ));
    }

    if ( ! empty( $_POST['last_name'] ) ) {
        update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
    }

	// if ( ! empty( $_POST['scisco_cmb2_user_phone'] ) ) {
    //     update_user_meta( $user_id, 'scisco_cmb2_user_phone', trim( $_POST['scisco_cmb2_user_phone'] ) );
    // }
}

add_action( 'edit_user_profile', 'crf_custom_user_access_code_fields' );
function crf_custom_user_access_code_fields( $user ) {
	$user_access_code = get_user_meta( $user->ID, 'user_access_code', true );
	?>
    <table class="form-table">
        <tr>
            <th><label for="user_access_code">Access Code</label></th>
            <td><input type="text" class="input-text form-control" name="user_access_code" id="user_access_code" value="<?php echo esc_attr( $user_access_code ) ?>"/>
            </td>
        </tr>
	</table >
	<?php
}

// Saving Updated fields data
add_action( 'personal_options_update', 'save_extra_user_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_fields' );
function save_extra_user_fields( $user_id )
{
    update_user_meta( $user_id, 'user_access_code', sanitize_text_field( $_POST['user_access_code'] ) );
}

add_action('register_form', function () {
	//wp_learn_create_database_table();
	wp_learn_insert_record_into_table();
	$first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
	$user_access_code = ( ! empty( $_POST['user_access_code'] ) ) ? trim( $_POST['user_access_code'] ) : '';
	//$scisco_cmb2_user_phone = ( ! empty( $_POST['scisco_cmb2_user_phone'] ) ) ? trim( $_POST['scisco_cmb2_user_phone'] ) : '';
    ?>
        <div class="user-pass1-wrap">
		<p>
            <label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?></label><br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" />
        </p>

        <p>
            <label for="last_name"><?php _e( 'Last Name', 'mydomain' ) ?></label><br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" />
        </p>

		<!-- <p>
            <label for="pass1"><?php _e( 'Password' ); ?></label><br />
			<input type="password" name="pass1" id="pass1" class="input password-input" size="25" value="" autocomplete="off" aria-describedby="pass-strength-result" />
       </p> -->

	   <p>
        <label for="user_access_code"><?php esc_html_e('Access Code') ?></label><br />
        <input type="text" name="user_access_code" id="user_access_code" class="input" value="<?php echo esc_attr($user_access_code); ?>" size="25" />
    	</p>
		<!-- <p>
            <label for="scisco_cmb2_user_phone"><?php _e( 'Phone Number', 'mydomain' ) ?></label><br />
            <input type="text" name="scisco_cmb2_user_phone" id="scisco_cmb2_user_phone" class="input" value="<?php echo esc_attr( wp_unslash( $scisco_cmb2_user_phone ) ); ?>" size="25" />
        </p> -->
    <?php
});

add_action('login_enqueue_scripts', function () {
    if (is_on_registration_page() && !wp_script_is('user-profile')) {
        wp_enqueue_script('user-profile');
    }
});

function is_on_registration_page() {
    return $GLOBALS['pagenow'] == 'wp-login.php' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'register';
}

add_filter('registration_errors', function ($errors) {
	if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
		$errors->add( 'first_name_error', __( '<strong>Error</strong>: Please enter a first name.', 'mydomain' ) );
	}

	if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
		$errors->add( 'last_name_error', __( '<strong>Error</strong>: Please enter a last name.', 'mydomain' ) );
	}
    // if (empty($_POST['pass1'])) {
    //     $errors->add('password-required', '<strong>Error</strong>: Please enter a password.');
    // }
	if (empty($_POST['user_access_code'])) {
        $errors->add('user_access_code-required', '<strong>Error</strong>: Please enter a access code.');
    }
	else if($_POST['user_access_code']){
		global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}access_code WHERE access_code = '".$_POST['user_access_code']."' ");
		$access_code_data = $wpdb->get_results($query, ARRAY_A);	
		if($_POST['user_access_code'] != $access_code_data[0]['access_code']){
			$errors->add('user_access_code-required', '<strong>Error</strong>: Invalid access code.');
		}
	}
	// if ( empty( $_POST['scisco_cmb2_user_phone'] ) || ! empty( $_POST['scisco_cmb2_user_phone'] ) && trim( $_POST['scisco_cmb2_user_phone'] ) == '' ) {
	// 	$errors->add( 'phone_number_error', __( '<strong>Error</strong>: Please enter a phone number.', 'mydomain' ) );
	// }

    return $errors;
});


add_action('login_head', function(){
	?>
	<style>
	  #registerform label[for="user_login"], #registerform input#user_login {
		display:none;
	  }
	</style>
	<?php
  });

  add_action('login_form_register', 'ui_set_registration_username');

function ui_set_registration_username(){
  //if there is anything set for user email
  if( isset($_POST['user_email']) && ! empty( $_POST['user_email'] ) ){
    //replace login with user email
    $_POST['user_login'] = $_POST['user_email'];
  }
}

//Remove error for username, only show error for email only.
add_filter('registration_errors', 'ui_registration_errors', 10, 3);

function ui_registration_errors($wp_error, $sanitized_user_login, $user_email){
  if(isset($wp_error->errors['empty_username'])){
    unset($wp_error->errors['empty_username']);
  }

  return $wp_error;
}

//replace WP strings with our own custom strings
add_filter('gettext', 'ui_custom_string', 20, 3);
function ui_custom_string( $translated_text, $text, $domain ) {
  if($translated_text == 'Username or Email Address'){
    //you can add any string you want here, as a case
    return 'E-mail Address';
  }

  return $translated_text;
} 

function wp_learn_create_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'access_code';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,		
		access_code varchar(10) NOT NULL,
		`status` enum('1','0') NOT NULL DEFAULT '1',
		PRIMARY KEY  (id)
	  ) $charset_collate;";
	  
	  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	  dbDelta( $sql ); 
 
}


function wp_learn_insert_record_into_table(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'access_code';
	$wpdb->query("INSERT INTO ".$wpdb->prefix . "access_code (`id`, `access_code`, `status`)
            VALUES
            (521, 'RCE2by6', '1'),
(522, 'RCEtzpr', '1'),
(523, 'RCEsskg', '1'),
(524, 'RCE74rh', '1'),
(525, 'RCEzg58', '1'),
(526, 'RCEz17r', '1'),
(527, 'RCE3hgy', '1'),
(528, 'RCEjdbv', '1'),
(529, 'RCE71kg', '1'),
(530, 'RCEpwmb', '1'),
(531, 'RCEzs11', '1'),
(532, 'RCE1b3c', '1'),
(533, 'RCEkg68', '1'),
(534, 'RCEb2bg', '1'),
(535, 'RCE3rtj', '1'),
(536, 'RCE5zww', '1'),
(537, 'RCEtmtb', '1'),
(538, 'RCEp893', '1'),
(539, 'RCEm792', '1'),
(540, 'RCEz61c', '1'),
(41, 'RCE8yyc', '1'),
(42, 'RCEhbk8', '1'),
(43, 'RCEgdjk', '1'),
(44, 'RCEp159', '1'),
(45, 'RCEgr9y', '1'),
(46, 'RCEmgr6', '1'),
(47, 'RCEtvby', '1'),
(48, 'RCEy3yy', '1'),
(49, 'RCEyc34', '1'),
(50, 'RCE9b8r', '1'),
(51, 'RCE8yg2', '1'),
(52, 'RCErdwh', '1'),
(53, 'RCEkd56', '1'),
(54, 'RCEgyhs', '1'),
(55, 'RCEcyhr', '1'),
(56, 'RCE5tys', '1'),
(57, 'RCEvzb7', '1'),
(58, 'RCEk2rw', '1'),
(59, 'RCEchr1', '1'),
(60, 'RCE7t61', '1'),
(61, 'RCEy8dw', '1'),
(62, 'RCEkwtz', '1'),
(63, 'RCEj7bz', '1'),
(64, 'RCEvdw1', '1'),
(65, 'RCEhtwz', '1'),
(66, 'RCE34sz', '1'),
(67, 'RCEwhdk', '1'),
(68, 'RCEpyyt', '1'),
(69, 'RCEmw7v', '1'),
(70, 'RCEjyz2', '1'),
(71, 'RCEc3mp', '1'),
(72, 'RCEjd13', '1'),
(73, 'RCE366g', '1'),
(74, 'RCEw27b', '1'),
(75, 'RCE7sz1', '1'),
(76, 'RCEyt4b', '1'),
(77, 'RCEdd5h', '1'),
(78, 'RCEktmr', '1'),
(79, 'RCEj17h', '1'),
(80, 'RCEjh4c', '1'),
(81, 'RCE643y', '1'),
(82, 'RCEzr5k', '1'),
(83, 'RCE46g8', '1'),
(84, 'RCE2gbw', '1'),
(85, 'RCE8p44', '1'),
(86, 'RCEgygw', '1'),
(87, 'RCEdkjt', '1'),
(88, 'RCEsvrr', '1'),
(89, 'RCE2c9m', '1'),
(90, 'RCEcd77', '1'),
(91, 'RCEcj2j', '1'),
(92, 'RCEc248', '1'),
(93, 'RCEyjz6', '1'),
(94, 'RCEkhsp', '1'),
(95, 'RCEb8vj', '1'),
(96, 'RCEc8k9', '1'),
(97, 'RCEcy1c', '1'),
(98, 'RCEzry7', '1'),
(99, 'RCEhdpg', '1'),
(100, 'RCE9bwk', '1'),
(101, 'RCEtz4m', '1'),
(102, 'RCEkpbt', '1'),
(103, 'RCEycwb', '1'),
(104, 'RCEpz98', '1'),
(105, 'RCEb54s', '1'),
(106, 'RCEcdmm', '1'),
(107, 'RCE27b8', '1'),
(108, 'RCE46bk', '1'),
(109, 'RCE78rb', '1'),
(110, 'RCEgszb', '1'),
(111, 'RCEbzwb', '1'),
(112, 'RCEkjpj', '1'),
(113, 'RCEcj79', '1'),
(114, 'RCEvs98', '1'),
(115, 'RCE5zb5', '1'),
(116, 'RCE3jk6', '1'),
(117, 'RCE8jbr', '1'),
(118, 'RCE8ybh', '1'),
(119, 'RCE36v5', '1'),
(120, 'RCEcbg6', '1'),
(121, 'RCEm9tp', '1'),
(122, 'RCEzdkc', '1'),
(123, 'RCEvbkh', '1'),
(124, 'RCEv35s', '1'),
(125, 'RCEh1pg', '1'),
(126, 'RCEwg88', '1'),
(127, 'RCEgwd9', '1'),
(128, 'RCEwc2v', '1'),
(129, 'RCE2625', '1'),
(130, 'RCEwkzs', '1'),
(131, 'RCEkcr7', '1'),
(132, 'RCE27mg', '1'),
(133, 'RCE2djt', '1'),
(134, 'RCEwtj2', '1'),
(135, 'RCEv2km', '1'),
(136, 'RCE95v4', '1'),
(137, 'RCEk7hz', '1'),
(138, 'RCE189v', '1'),
(139, 'RCE8zr7', '1'),
(140, 'RCEbcv4', '1'),
(141, 'RCEkms2', '1'),
(142, 'RCE9yc2', '1'),
(143, 'RCE87tm', '1'),
(144, 'RCEpww3', '1'),
(145, 'RCE8y28', '1'),
(146, 'RCEhgyg', '1'),
(147, 'RCE3s41', '1'),
(148, 'RCEy16y', '1'),
(149, 'RCEthw7', '1'),
(150, 'RCE3393', '1'),
(151, 'RCE182j', '1'),
(152, 'RCE8kb8', '1'),
(153, 'RCEzhy1', '1'),
(154, 'RCE8d1b', '1'),
(155, 'RCEj8m1', '1'),
(156, 'RCE75z1', '1'),
(157, 'RCEs9sj', '1'),
(158, 'RCEcr56', '1'),
(159, 'RCEmmrj', '1'),
(160, 'RCEh2cm', '1'),
(161, 'RCE13d6', '1'),
(162, 'RCEjcbm', '1'),
(163, 'RCErp9h', '1'),
(164, 'RCE9r9j', '1'),
(165, 'RCEkgjm', '1'),
(166, 'RCEk321', '1'),
(167, 'RCEk1pd', '1'),
(168, 'RCE181y', '1'),
(169, 'RCEcr9w', '1'),
(170, 'RCEyz2c', '1'),
(171, 'RCEc7vp', '1'),
(172, 'RCEkw7t', '1'),
(173, 'RCE51k8', '1'),
(174, 'RCE94cy', '1'),
(175, 'RCE656m', '1'),
(176, 'RCEwkbv', '1'),
(177, 'RCEw9pk', '1'),
(178, 'RCEkgyp', '1'),
(179, 'RCEhhsb', '1'),
(180, 'RCEtmhv', '1'),
(181, 'RCEt7yw', '1'),
(182, 'RCEr1jj', '1'),
(183, 'RCEryck', '1'),
(184, 'RCEhr75', '1'),
(185, 'RCE8wpw', '1'),
(186, 'RCE614p', '1'),
(187, 'RCE4t6w', '1'),
(188, 'RCErss3', '1'),
(189, 'RCEygvc', '1'),
(190, 'RCEwvjm', '1'),
(191, 'RCEzpg5', '1'),
(192, 'RCE7jyr', '1'),
(193, 'RCEhpm7', '1'),
(194, 'RCEwr39', '1'),
(195, 'RCEp83r', '1'),
(196, 'RCE977s', '1'),
(197, 'RCEpj8p', '1'),
(198, 'RCEvrzy', '1'),
(199, 'RCEhzmd', '1'),
(200, 'RCE2kcc', '1'),
(201, 'RCEj54z', '1'),
(202, 'RCEyt4k', '1'),
(203, 'RCE4z5c', '1'),
(204, 'RCEgr7y', '1'),
(205, 'RCE6w85', '1'),
(206, 'RCEk4md', '1'),
(207, 'RCE6pk3', '1'),
(208, 'RCE3h38', '1'),
(209, 'RCEv6j7', '1'),
(210, 'RCE93wr', '1'),
(211, 'RCE5v9z', '1'),
(212, 'RCEpgrd', '1'),
(213, 'RCE6z38', '1'),
(214, 'RCEcwhw', '1'),
(215, 'RCE2c9w', '1'),
(216, 'RCEk1y2', '1'),
(217, 'RCEwdp8', '1'),
(218, 'RCE1krj', '1'),
(219, 'RCEkk9t', '1'),
(220, 'RCEhdvt', '1'),
(221, 'RCEdhpg', '1'),
(222, 'RCEyv47', '1'),
(223, 'RCEm1s2', '1'),
(224, 'RCE532b', '1'),
(225, 'RCE64h6', '1'),
(226, 'RCEymcp', '1'),
(227, 'RCEwvst', '1'),
(228, 'RCEj72v', '1'),
(229, 'RCE4djs', '1'),
(230, 'RCEzthk', '1'),
(231, 'RCE111c', '1'),
(232, 'RCEskg3', '1'),
(233, 'RCEz78v', '1'),
(234, 'RCEttst', '1'),
(235, 'RCEzwkc', '1'),
(236, 'RCEs1s2', '1'),
(237, 'RCEzpr7', '1'),
(238, 'RCEb7rw', '1'),
(239, 'RCEc4w5', '1'),
(240, 'RCEp57r', '1'),
(241, 'RCEt7v5', '1'),
(242, 'RCEwrvw', '1'),
(243, 'RCEp27r', '1'),
(244, 'RCEvwm4', '1'),
(245, 'RCEkppt', '1'),
(246, 'RCEk2hp', '1'),
(247, 'RCEh768', '1'),
(248, 'RCEdct9', '1'),
(249, 'RCE1jg4', '1'),
(250, 'RCE2bhv', '1'),
(251, 'RCEvr14', '1'),
(252, 'RCEmk62', '1'),
(253, 'RCE3jrm', '1'),
(254, 'RCE6zrh', '1'),
(255, 'RCEbwkp', '1'),
(256, 'RCEpzt1', '1'),
(257, 'RCEcsrb', '1'),
(258, 'RCE6mb9', '1'),
(259, 'RCEtcww', '1'),
(260, 'RCEg61d', '1'),
(261, 'RCEv5bp', '1'),
(262, 'RCEvdkb', '1'),
(263, 'RCEs3mm', '1'),
(264, 'RCE4vjs', '1'),
(265, 'RCEcrp9', '1'),
(266, 'RCE6cg8', '1'),
(267, 'RCEpwtk', '1'),
(268, 'RCEhpsg', '1'),
(269, 'RCEgt2r', '1'),
(270, 'RCE13hs', '1'),
(271, 'RCEmktp', '1'),
(272, 'RCEj8vh', '1'),
(273, 'RCEgws8', '1'),
(274, 'RCEg7p9', '1'),
(275, 'RCEsvck', '1'),
(276, 'RCE57ct', '1'),
(277, 'RCE7rgg', '1'),
(278, 'RCE98rb', '1'),
(279, 'RCE2sy6', '1'),
(280, 'RCEyg2r', '1'),
(281, 'RCEwgv4', '1'),
(282, 'RCEprv3', '1'),
(283, 'RCEs88h', '1'),
(284, 'RCEmz1j', '1'),
(285, 'RCEmj75', '1'),
(286, 'RCE7v8g', '1'),
(287, 'RCE3pj7', '1'),
(288, 'RCEkgjp', '1'),
(289, 'RCEd34j', '1'),
(290, 'RCEbzm3', '1'),
(291, 'RCE8cw9', '1'),
(292, 'RCEt3bm', '1'),
(293, 'RCEp4cr', '1'),
(294, 'RCEkp6m', '1'),
(295, 'RCEsbhh', '1'),
(296, 'RCEddhw', '1'),
(297, 'RCE2hvj', '1'),
(298, 'RCEdyb6', '1'),
(299, 'RCEtddh', '1'),
(300, 'RCE339r', '1'),
(301, 'RCEwp12', '1'),
(302, 'RCEjd4c', '1'),
(303, 'RCEdtp7', '1'),
(304, 'RCEp2jy', '1'),
(305, 'RCEpwh4', '1'),
(306, 'RCE62gr', '1'),
(307, 'RCEvpzj', '1'),
(308, 'RCE76r3', '1'),
(309, 'RCEkrbc', '1'),
(310, 'RCEb33y', '1'),
(311, 'RCEczr6', '1'),
(312, 'RCEw8rk', '1'),
(313, 'RCEzk1v', '1'),
(314, 'RCEppps', '1'),
(315, 'RCE59jm', '1'),
(316, 'RCEswd5', '1'),
(317, 'RCE465p', '1'),
(318, 'RCEb4sr', '1'),
(319, 'RCE3s5z', '1'),
(320, 'RCEgg2k', '1'),
(321, 'RCEv7z4', '1'),
(322, 'RCEy2hh', '1'),
(323, 'RCE6yvb', '1'),
(324, 'RCErtp1', '1'),
(325, 'RCEpdgj', '1'),
(326, 'RCEg16s', '1'),
(327, 'RCEmsy4', '1'),
(328, 'RCE7zb4', '1'),
(329, 'RCE4149', '1'),
(330, 'RCEcyck', '1'),
(331, 'RCEj496', '1'),
(332, 'RCEc7v4', '1'),
(333, 'RCEb2k7', '1'),
(334, 'RCE39my', '1'),
(335, 'RCE81mp', '1'),
(336, 'RCE9kyw', '1'),
(337, 'RCEvh57', '1'),
(338, 'RCEk7dz', '1'),
(339, 'RCE6wkh', '1'),
(340, 'RCEpj7r', '1'),
(341, 'RCE5zg9', '1'),
(342, 'RCE266g', '1'),
(343, 'RCEjh6y', '1'),
(344, 'RCEgg8m', '1'),
(345, 'RCE3yy6', '1'),
(346, 'RCEm5jd', '1'),
(347, 'RCEvcv4', '1'),
(348, 'RCE1v84', '1'),
(349, 'RCEy5c1', '1'),
(350, 'RCEz5p3', '1'),
(351, 'RCEj237', '1'),
(352, 'RCEwpb2', '1'),
(353, 'RCEjy8b', '1'),
(354, 'RCE2bv3', '1'),
(355, 'RCEpkdt', '1'),
(356, 'RCE14pk', '1'),
(357, 'RCEs76k', '1'),
(358, 'RCEzvt5', '1'),
(359, 'RCE9bb4', '1'),
(360, 'RCE27dj', '1'),
(361, 'RCE9zj6', '1'),
(362, 'RCEjd2g', '1'),
(363, 'RCEbt3d', '1'),
(364, 'RCE1myv', '1'),
(365, 'RCEpbz4', '1'),
(366, 'RCEdz94', '1'),
(367, 'RCE43pp', '1'),
(368, 'RCEc6wr', '1'),
(369, 'RCEsytd', '1'),
(370, 'RCE7z31', '1'),
(371, 'RCE2sth', '1'),
(372, 'RCE3khw', '1'),
(373, 'RCE3m5w', '1'),
(374, 'RCEbd6p', '1'),
(375, 'RCE61cg', '1'),
(376, 'RCEtzr2', '1'),
(377, 'RCEg2hm', '1'),
(378, 'RCE767m', '1'),
(379, 'RCEv733', '1'),
(380, 'RCE1t76', '1'),
(381, 'RCE1bzz', '1'),
(382, 'RCEtbjr', '1'),
(383, 'RCEv7p2', '1'),
(384, 'RCEs175', '1'),
(385, 'RCEk8t9', '1'),
(386, 'RCE447t', '1'),
(387, 'RCE69vg', '1'),
(388, 'RCEvyg8', '1'),
(389, 'RCEdht5', '1'),
(390, 'RCEk19c', '1'),
(391, 'RCE34kw', '1'),
(392, 'RCEwjk5', '1'),
(393, 'RCEyz8d', '1'),
(394, 'RCEtwb6', '1'),
(395, 'RCEsr23', '1'),
(396, 'RCE5v2t', '1'),
(397, 'RCEwz7m', '1'),
(398, 'RCE36ww', '1'),
(399, 'RCEw2zj', '1'),
(400, 'RCE847g', '1'),
(401, 'RCEwpm9', '1'),
(402, 'RCEv2pm', '1'),
(403, 'RCEr27t', '1'),
(404, 'RCEjrk4', '1'),
(405, 'RCErzt2', '1'),
(406, 'RCEgzmj', '1'),
(407, 'RCEbsh7', '1'),
(408, 'RCEy29y', '1'),
(409, 'RCEp6zh', '1'),
(410, 'RCE36hz', '1'),
(411, 'RCEkdwp', '1'),
(412, 'RCE7phh', '1'),
(413, 'RCEgrh1', '1'),
(414, 'RCEg3w3', '1'),
(415, 'RCE9jcb', '1'),
(416, 'RCEmdh2', '1'),
(417, 'RCEg2g2', '1'),
(418, 'RCE6tsk', '1'),
(419, 'RCE8zr1', '1'),
(420, 'RCEbwsk', '1'),
(421, 'RCEc3cg', '1'),
(422, 'RCEd1r6', '1'),
(423, 'RCE2r3v', '1'),
(424, 'RCEypc2', '1'),
(425, 'RCEmtc1', '1'),
(426, 'RCE43g6', '1'),
(427, 'RCEh1vy', '1'),
(428, 'RCEtdm8', '1'),
(429, 'RCEt7hb', '1'),
(430, 'RCEp19j', '1'),
(431, 'RCEgwgw', '1'),
(432, 'RCEzmb6', '1'),
(433, 'RCE55ms', '1'),
(434, 'RCE72d5', '1'),
(435, 'RCEjvdd', '1'),
(436, 'RCEv181', '1'),
(437, 'RCEmb1h', '1'),
(438, 'RCE6y58', '1'),
(439, 'RCEd1bb', '1'),
(440, 'RCEjszz', '1'),
(441, 'RCEhvmg', '1'),
(442, 'RCEpk56', '1'),
(443, 'RCEthgt', '1'),
(444, 'RCE8wd4', '1'),
(445, 'RCEbczd', '1'),
(446, 'RCEj7hc', '1'),
(447, 'RCEkrzs', '1'),
(448, 'RCEmgwr', '1'),
(449, 'RCE23kd', '1'),
(450, 'RCE8b4d', '1'),
(451, 'RCE7rgm', '1'),
(452, 'RCEj3s9', '1'),
(453, 'RCE2283', '1'),
(454, 'RCEm3zc', '1'),
(455, 'RCE8m33', '1'),
(456, 'RCEswjg', '1'),
(457, 'RCEdsw5', '1'),
(458, 'RCEdtgt', '1'),
(459, 'RCEkzv4', '1'),
(460, 'RCE1g1z', '1'),
(461, 'RCE5kt8', '1'),
(462, 'RCEtzb1', '1'),
(463, 'RCE5dh6', '1'),
(464, 'RCEzy5w', '1'),
(465, 'RCE83db', '1'),
(466, 'RCE7csz', '1'),
(467, 'RCEbw4w', '1'),
(468, 'RCEbhkg', '1'),
(469, 'RCErbwp', '1'),
(470, 'RCE799h', '1'),
(471, 'RCEc5pk', '1'),
(472, 'RCEvw94', '1'),
(473, 'RCE6jbs', '1'),
(474, 'RCE6r2m', '1'),
(475, 'RCErtbm', '1'),
(476, 'RCEgdzy', '1'),
(477, 'RCE6227', '1'),
(478, 'RCE22sg', '1'),
(479, 'RCEhhs8', '1'),
(480, 'RCEpc73', '1'),
(481, 'RCEgk63', '1'),
(482, 'RCEmksd', '1'),
(483, 'RCEk6p2', '1'),
(484, 'RCEydjp', '1'),
(485, 'RCE6d1p', '1'),
(486, 'RCE13t3', '1'),
(487, 'RCEzc2v', '1'),
(488, 'RCEh2zv', '1'),
(489, 'RCEh1wz', '1'),
(490, 'RCEdgvd', '1'),
(491, 'RCEhc74', '1'),
(492, 'RCEk513', '1'),
(493, 'RCEg2w1', '1'),
(494, 'RCEkddb', '1'),
(495, 'RCE6wg8', '1'),
(496, 'RCEd6yt', '1'),
(497, 'RCEsm9z', '1'),
(498, 'RCEhshm', '1'),
(499, 'RCEwgmj', '1'),
(500, 'RCE673s', '1'),
(501, 'RCEvbhr', '1'),
(502, 'RCE6rs4', '1'),
(503, 'RCEs1dr', '1')");
	// $wpdb->insert( $table_name, array("access_code"  => "RCEmv56","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEkt1b","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE7y4z","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE4ryt","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE3k7w","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE36dv","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE1dt1","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEc8m6","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE42v6","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEc36t","status"=>"1")); 
	
	// $wpdb->insert( $table_name, array("access_code"  => "RCE6sr9","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEbjwp","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEy4gm","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEymrh","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE5rv6","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEm5bk","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEw7b9","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCE2by6","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEtzpr","status"=>"1"));  
	// $wpdb->insert( $table_name, array("access_code"  => "RCEsskg","status"=>"1"));
}


//register_activation_hook( __FILE__, 'wp_learn_create_database_table' );
//register_activation_hook( __FILE__, 'wp_learn_insert_record_into_table' ); 