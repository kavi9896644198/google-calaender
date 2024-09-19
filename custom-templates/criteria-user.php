<?php
$scisco_query_var = get_query_var('qa_user');
$scisco_orderby = get_theme_mod('scisco_users_orderby', 'alphabetical_asc');
$user_search_query = array();
$user_id = get_current_user_id();
$current_userdata = get_userdata($user_id);
$criteria = get_usermeta( $user_id,'wiki_test_multicheckbox',true);
$grouped_results = array();


if (get_query_var('qa_orderby')) {
    $scisco_orderby = get_query_var('qa_orderby');
}

if ($scisco_query_var) {
    $user_search_query = array(
        'search'         => '*' . esc_attr($scisco_query_var) . '*',
        'search_columns' => array(
            'user_login',
            'user_nicename',
            'first_name',
            'last_name'
        )
    );
}

$criteria_filter = array(
    'relation' => 'AND', // Change relation to AND to satisfy all conditions
    'meta_query' => array(
        'relation' => 'AND', // Top-level relation remains OR
    )
);
// Check if 'type' filter is set and add it to the meta query
if(isset($_GET['type']) && $_GET['type'] !== ''){
    $type = $_GET['type'];
    $criteria_filter['meta_query'][] = array(
        'key'   => 'scisco_cmb2_type',
        'value' => $type,
        'compare' => 'LIKE',
    );
} 
// Check if 'looking' filter is set and add it to the meta query
if(isset($_GET['looking']) && $_GET['looking'] !== ''){
    $looking = $_GET['looking'];
    $criteria_filter['meta_query'][] = array(
        'key'     => 'wiki_test_multicheckbox', 
        'value'   => $looking,
        'compare' => 'LIKE',
    );
}
// Check if 'state' filter is set and add it to the meta query
if(isset($_GET['state']) && $_GET['state'] !== ''){
    $state = $_GET['state'];
    $criteria_filter['meta_query'][] = array(
        'key'     => 'scisco_cmb2_county_states', 
        'value'   => $state,
        'compare' => '=',
    );
}


switch ($scisco_orderby) {
    case 'registration_date_asc':
        $user_query_1 = array(
            'orderby' => 'user_registered',
            'order'   => 'ASC',
        );
        break;
    case 'registration_date_desc':
        $user_query_1 = array(
            'orderby' => 'user_registered',
            'order'   => 'DESC',
        );
        break;
    case 'alphabetical_asc':
        $user_query_1 = array(
            'orderby' => 'title',
            'order'   => 'ASC',
        );
        break;
    case 'alphabetical_desc':
        $user_query_1 = array(
            'orderby' => 'title',
            'order'   => 'DESC',
        );
        break;
    case 'reputation_asc':
        $user_query_1 = array(
            'orderby'  => 'meta_value_num',
            'meta_key' => 'ap_reputations',
            'order'    => 'ASC',
        );
        break;
    case 'reputation_desc':
        $user_query_1 = array(
            'orderby'  => 'meta_value_num',
            'meta_key' => 'ap_reputations',
            'order'    => 'DESC',
        );
        break;
}

// Merge the user search query and the meta query
$final_query = new WP_User_Query($user_search_query + $user_query_1 + $criteria_filter);
 // echo "<pre>";print_r($final_query->get_results());

// Get the total number of results
$total_query = count($final_query->get_results());
//echo $total_query;

?>
<div class="container-fluid">
<div class="scisco-users-wrapper verified-user">
<h3 class="users-title">People Looking For Your Services!</h3>
<?php foreach ($final_query->get_results() as $user) {
    
if($user->ID != $user_id):
    $user_meta = get_user_meta($user->ID, 'scisco_cmb2_type', true);
    $other_criteria = get_usermeta( $user->ID,'wiki_test_multicheckbox',true);
    if(isset($_GET['Availability']) && $_GET['Availability'] !== ''){        
        global $wpdb;
        $client_id = 20;
        $query = $wpdb->prepare("
            SELECT COUNT(*) AS booking_count FROM {$wpdb->prefix}book_slot
            WHERE client_id = %d
            AND status = 'booked'
        ", $user->ID);

        $query2 = $wpdb->prepare("
            SELECT COUNT(*) AS booking_count1 FROM {$wpdb->prefix}book_slot
            WHERE client_id = %d
        ", $user->ID);
        $booking_count = $wpdb->get_var($query);
        $total_count = $wpdb->get_var($query2);
        // print_r($booking_count);
        // print_r($total_count);
        if(($booking_count == $total_count && $booking_count !=0) || ($booking_count == $total_count && $total_count !=0)){
            // die('sss');
            continue;
        }
    }
     //print_r($user_meta);
    if (is_array($user_meta)) {
        foreach ($user_meta as $value) {
            if(!empty($other_criteria)){
                $other_criteria = $other_criteria;
            }else{
                $other_criteria = array();
            }
            if ($scisco_query_var || $_GET['type'] || $_GET['looking'] || $_GET['state']) {
                if ($value && in_array($value, $other_criteria)) {
                $grouped_results['matched'][] = $user->ID;
                }
            }else{
                $grouped_results['matched'][] = $user->ID;
            }
        }
    }else{
        $grouped_results['not_matched'][] = $user->ID;
    }
    endif; 
  }
?>
</div>


<?php
     //echo "<pre>";print_r($grouped_results);
    if(isset($grouped_results['matched']) && isset($grouped_results['not_matched'])){
        $grouped_results = array_merge($grouped_results['matched'], $grouped_results['not_matched']);
    }else if(isset($grouped_results['not_matched'])){
        $grouped_results = $grouped_results['not_matched'];
    }else{
       $grouped_results = $grouped_results['matched'];
    }
   // echo "<pre>";print_r($grouped_results);
    //echo "<pre>";print_r(array_unique($grouped_results));
    $qry = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}book_slot  WHERE current_user_id = '".$user_id."' AND (status = 'booked' || status = 'pending') GROUP by client_id");
    $bookedUsers = $wpdb->get_results($qry);
    $bookedClientId = [];
    foreach ($bookedUsers as $index => $bookeduser) {
         //echo $bookeduser->client_id;
        array_push($bookedClientId, $bookeduser->client_id);
    }

if($grouped_results){ 
   // echo count($grouped_results);
   foreach(array_unique($grouped_results) as $key => $user){
        $userdata = get_userdata($user);
   ?> 
   <div class="scisco-users-wrapper gap-c verified-user"><?php
    // echo "<h4>".$key."</h4>";
    // foreach($value as $user){ ?>
    
     <div class="scisco-question-wrapper">
      <div class="scisco-question-list">
      <div class="scisco-question-avatar <?php echo $user ?>"><?php //echo $user 
      $isBookedFlag = false;
      if(in_array($user, $bookedClientId)) {       
        $isBookedFlag = true;      
      }else{      
        $isBookedFlag = false ;    
      }
      //echo  $isBookedFlag;
      ?>
                <?php $scisco_page_slug = get_theme_mod('scisco_ap_about_slug', 'about'); ?>
                <?php if ( $da_avatar = get_the_author_meta( 'scisco_cmb2_user_avatar', $user ) ) : ?>
                   <a href="<?php echo esc_url(ap_user_link($user) . $scisco_page_slug); ?>/">
                        <?php //echo get_avatar( $user, ap_opt( 'avatar_size_list' ) ); ?>
                        <?php //echo get_avatar( $user); ?>
                        <?php if ( $isBookedFlag) : ?>
                            <div class="scisco-verified" >
                            <i class="fas fa-check" title="Booked Event"></i>
                            <?php echo get_avatar( $user); ?>
                            </div>
                        <?php else : ?>
                            <?php echo get_avatar( $user); ?>
                        <?php endif; ?>
                    </a>
                <?php else : 
                        $uploads = wp_upload_dir();
                        $upload_path = $uploads['baseurl']; 
                        if($user->scisco_cmb2_gender =='male'){
                            $profileImage = $upload_path."/2020/12/logo3.png" ;
                        }else {
                             $profileImage = $upload_path."/2020/12/logo6.png";
                        }
                    ?>
                   <a href="<?php echo esc_url(ap_user_link($user) . $scisco_page_slug); ?>/">  
                   <?php if ( $isBookedFlag) : ?>
                   <div class="scisco-verified" >
                   <i class="fas fa-check" title="Booked Event"></i>                    
                        <img class="avatar lazyloaded" data-src="<?php echo $profileImage ?>" src="<?php echo $profileImage ?>" width="45" height="45" alt="">
                   </div>                    
                    <?php else : ?>
                        <img class="avatar lazyloaded" data-src="<?php echo $profileImage ?>" src="<?php echo $profileImage ?>" width="45" height="45" alt="">
                    <?php endif; ?>
                    </a>
                <?php endif; ?>
               
            </div>
            <!--  -->
            <?php  $user_obj = get_userdata( $user ); ?>
            <?php  $encode_user = base64_encode($user); ?>
            <div class="scisco-question-title">
                <h6>
                    <a href="<?php echo esc_url(ap_user_link($user) . $scisco_page_slug); ?>/">
                        <?php 
                        // get the first name of the user as a string
                        $user_firstname = get_user_meta( $user, 'first_name', true );
                        // get the last name of the user as a string
                        $user_lastname = get_user_meta( $user, 'last_name', true );
                        if($user_firstname || $user_lastname) {
                            echo $user_firstname . ' ' . $user_lastname ;
                        }else{
                            echo esc_html($user_obj->user_nicename);   
                        } 
                        ?>
                    </a>
                    <?php $reputation_count = ap_get_user_reputation_meta( $user, true ); ?>                    
                    <span class="ap-user-reputation"><?php echo esc_html($reputation_count); ?></span>
                </h6>
                <span><?php echo esc_html($user_obj->description); ?></span>
               
                <?php $business_type = get_usermeta( $user,'scisco_cmb2_type',true);  ?>     
                <span class="scisco-cat-list">  
                    <?php 
                    if(is_array($business_type)){
                        foreach ($business_type as $key => $value) {
                            echo '<a href="">'.$value.'</a> ';
                        }
                    }
                    ?>
                </span>
           
                       
                    <?php if($user->scisco_cmb2_user_city): ?>
                        <p class="scisco-location">
                        <?=$user_obj->scisco_cmb2_user_city?>, <?=$user_obj->scisco_cmb2_user_state?>
                        </p>
                    <?php endif; ?>
            </div>
            <?php 
                    $scisco_cmb2_user_question_category = get_usermeta( $user,'wiki_test_multicheckbox',true);
                        if(!empty($scisco_cmb2_user_question_category)){ ?>
            <p class="looking-for"><i class="fa fa-users"></i> Looking for: 
              <?php
                           
                            foreach($scisco_cmb2_user_question_category as $question_category){
                                echo '<a href="">'.$question_category.'</a> ';
                            }
                     ?>
            </p>
        <?php } ?>
        </div>
        <!--  -->
        <div class="scisco-question-counts">
        <?php
            $user_answer = ap_total_posts_count( 'answer', false, $user);
            $user_answer_count = $user_answer->publish;
            $user_question = ap_total_posts_count( 'question', false, $user);
            $user_question_count = $user_question->publish;
            ?>
            <!-- <div class="ques_quote">
                <div class="ap-questions-count">
                    <span><?php echo esc_html($user_question_count); ?></span>
                    <?php esc_html_e( 'Que', 'scisco' ); ?>
                </div>
                <div class="ap-questions-count ap-questions-vcount">
                    <span><?php echo esc_html($user_answer_count); ?></span>
                    <?php esc_html_e( 'Ans', 'scisco' ); ?>
                </div>
            </div> -->
            <!-- <div class="appointment_button">
                <a target="_blank" href="<?php echo home_url() ?>/booking-form/?cs=<?= $encode_user ?>" class="btn btn-primary">Schedule a Metting!</a>
            </div> -->
               <?php 
                    global $wpdb;
                  $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}event_calender WHERE current_user_id = %d", $user);
                  $results = $wpdb->get_results($query, ARRAY_A);
                  if(empty($results)){
                     ?>
                     <div class="appointment_button">
                        <!-- <a href="javascript:void(0)" data-schedule="No" data-current_user_id="<?php echo $user; ?>" class="btn btn-primary available_meeting_time_custom">Schedule a Meeting!2</a> -->
                        <a href="javascript:void(0)" data-schedule="Yes" data-current_user_id="<?php echo $user; ?>" class="btn btn-primary schedule_Metting_class">Schedule a Meeting!</a>
                    </div>
                     <?php 
                  }else{
                    ?>
                     <!-- .<div class="appointment_button"> -->
                        <a href="javascript:void(0)" data-schedule="Yes" data-current_user_id="<?php echo $user; ?>" class="btn btn-primary schedule_Metting_class">Schedule a Meeting!</a>
                    <!-- </div> -->
                    <?php 
                  }
                ?>
           
            <!-- <div class="not_availability_set">
                <a href="/questions/profile/<?php echo $current_userdata->display_name; ?>/messages/?fepaction=newmessage&fep_to=<?php echo $userdata->display_name; ?>" target="_blank" class="btn btn-primary">Request For Set Availability!</a>
            </div> -->
        </div>
</div>
<div class="modal fade" id="schedule_Metting_popup" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Schedule a Metting!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="success_message_show"></div>
            <div class="modal-body">
                <div class="img-container">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="add-field" onclick="save_event()"><span class="spinner-border spinner-border-sm spinner_border_custom" style="display:none;"></span>Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="available_meeting_time_custom" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Schedule a Metting!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="success_message_show"></div>
            <div class="modal-body">
                <div class="img-container">
                    <p>This person has not set their available meeting times yet! Click this button to let them know you're intersted in connecting!</p>
                    <div class="not_availability_set">
                        <a href="javascript:void(0)" data-user_id="" class="btn btn-primary request_for_availability">Request For Set Availability!</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
               <!--  <button type="button" class="add-field" onclick="save_event()"><span class="spinner-border spinner-border-sm spinner_border_custom" style="display:none;"></span>Save</button> -->
            </div>
        </div>
    </div>
</div>
<div class="loader">
    <img class=" lazyloaded" src="/wp-content/uploads/2024/02/loader1.gif">
</div>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script>
    function schedule_metting() {
    var client_id = jQuery("#client_id").val();
    var current_user_id = jQuery("#current_user_id").val();
    var current_date_select = jQuery(".select_available_date").val();
    var event_id = jQuery("#event_id").val();
    var selectedSlot = jQuery('input[name="timeSlot"]:checked').val();
    var notes = jQuery('#myappointment_text').val();
    if (!current_date_select) {
       alert('Please Select date');
    } else if (!selectedSlot) {
      alert('Please select a time slot.');
    } else {
        jQuery('.spinner_border_custom').css('display','block');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "book_time_slot",current_user_id:current_user_id,client_id:client_id,current_date_select:current_date_select, event_id:event_id,selectedSlot:selectedSlot,notes:notes },
            success: function(response) {
                var responseData = JSON.parse(response);
                if(responseData.status == 200){
                    jQuery('.spinner_border_custom').css('display','none');
                    // jQuery('.success_message_show').text('The time slot request has been done! ');
                    var timeSlots = document.getElementsByName("timeSlot");
                    for (var i = 0; i < timeSlots.length; i++) {
                        timeSlots[i].disabled = true;
                    }
                    swal("Thanks your meeting  request has been  sent, You'll be notified if and when they accept!")
                    .then((value) => {
                      // swal(`The returned value is: ${value}`);
                      window.location.reload()
                    });
                }
                
                // 
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
                // alert(response.msg);
            }
        });
    }
}
</script>
<?php   
    // } ?>
</div>
    <?php
   } 
} else { 
    echo 'Record not found.';
}
   ?>

</div>