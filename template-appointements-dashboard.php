<?php 
/*Template name: Appointments Dashboard*/

get_header();

//Header template
 get_template_part('custom','templates/canna-header');
 ?>
<main id="scisco-main-wrapper">
<?php if((is_user_logged_in())):  

define('CLIENT_REDIRECT_URL', 'https://cannaconnects.io/my-dashboard/'); 
define('CLIENT_SECRET', ''); 
define('CLIENT_ID', '');

$login_url = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/calendar') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
require_once get_stylesheet_directory().'/google-calendar-api.php';
// print_r($_COOKIE['sync_book_id']);die;
if(isset($_GET['code'])) {
    try {
        $capi = new GoogleCalendarApi();
        // Get the access token 
        $data = $capi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
        // Save the access token as a session variable
        $_SESSION['access_token'] = $data['access_token'];
        $access_token = $data['access_token'];
        die('Please provide CLIENT_SECRET and CLIENT_ID  API details, then proceed.');
        // $GetEventsList = $capi->GetEventsList($access_token,'');
            $GetCalendarsList = $capi->GetCalendarsList($access_token);
            // echo "<pre>";print_r($GetCalendarsList);die;
            $calendar_id = $GetCalendarsList[0]['id'];
             if(isset($_COOKIE['sync_book_id'])){
               $book_id = trim($_COOKIE['sync_book_id']);
               $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}book_slot WHERE book_id = %d", $book_id);
               $book_slot = $wpdb->get_row($query, ARRAY_A);
                $get_time_zone = $capi->GetUserCalendarTimezone($access_token);
                $CreateCalendarEvent = $capi->CreateCalendarEvent($book_slot,$calendar_id,$access_token,$get_time_zone);
                 $book_data = array(
                    'google_event_id' => $CreateCalendarEvent['id'],
                );     
                  $where = array(
                      'book_id' => $book_id, 
                  );
                  $result = $wpdb->update(
                    $wpdb->prefix . 'book_slot', 
                    $book_data,
                    $where // WHERE condition
                );
                setcookie('sync_book_id', '', time() + (86400 * 30), "/");
              }

               if(isset($_COOKIE['cancel_book_id'])){
                 $book_id = trim($_COOKIE['cancel_book_id']);
                 $status = trim($_COOKIE['cancel_status']);
                 $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}book_slot WHERE book_id = %d", $book_id);
                 $book_slot = $wpdb->get_row($query, ARRAY_A);
                $DeleteCalendarEvent = $capi->DeleteCalendarEvent($book_slot['google_event_id'],$calendar_id,$access_token);
                $book_data = array(
                    'google_event_id' => '',
                    'status' => $status,
                );
                $where = array(
                      'book_id' => $book_id, 
                  );
                $result = $wpdb->update(
                    $wpdb->prefix . 'book_slot', 
                    $book_data,
                    $where // WHERE condition
                );
                setcookie('cancel_book_id', '', time() + (86400 * 30), "/");
                setcookie('cancel_status', '', time() + (86400 * 30), "/");
              }
            wp_redirect(home_url('/my-dashboard/'));
          exit();
    }
    catch(Exception $e) {
        echo $e->getMessage();
        exit();
    }
}
?>

<?php 
  global $wpdb;
    $logged_user_id = get_current_user_id();
    $booked = [];
    $canceled = [];
    $pending = [];
    $table = $wpdb->prefix."book_slot";
    $query = $wpdb->prepare("SELECT * FROM $table WHERE client_id = %d", $logged_user_id);
    $result = $wpdb->get_results($query, ARRAY_A);
    foreach ($result as $entry) {
    switch ($entry['status']) {
        case 'booked':
            $booked[] = $entry;
            break;
        case 'canceled':
            $canceled[] = $entry;
            break;
        case 'pending':
            $pending[] = $entry;
            break;
    }
}

    ?>    
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <ul class="nav nav-tabs tab-ex" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#home">My Appointments</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu1">Pending</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu2">Cancelled</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu3">My Requests</a>
            </li>
          </ul>
          <div class="tab-content tab-con">
            <div id="home" class="container tab-pane active">

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Client Name</th>
                    <th>Date_Time</th>
                    <th>Edit Status</th>
                    <th>Notes</th>
                    <th>Sync. with Google Calender</th>
                  </tr>
                </thead>  
                <tbody>
                  <?php  if(!empty($booked)): ?>
                    <?php foreach($booked as $key=>$val){ 
                      $u_data = get_userdata($val['current_user_id']);
                      $date = new DateTime($val['slot_date']);
                      ?>
                      <tr>
                        <td><a href="<?php echo esc_url(ap_user_link($val['current_user_id']) . $scisco_page_slug) ?>"><?php echo $u_data->display_name; ?></a></td>
                        <td><?php echo $date->format('m-d-Y')." || ".$val['slot_time']; ?></td>
                        <td>
                          <?php 
                            if(!empty($val['google_event_id'])){?>
                               <select name="booked_appointements_google" data-calander_url="<?php echo $login_url; ?>" data-book_id="<?php echo $val['book_id'];?>" id="booked_appointements_google" class="tab-sel">
                                <option value="">Select</option>
                                <option value="canceled" data-book_id ="<?php echo $val['book_id']?>">Cancel</option>
                              </select>
                           <?php }else{?>
                               <select name="booked_appointements" data-book_id="<?php echo $val['book_id'];?>" id="booked_appointements" class="tab-sel">
                                <option value="">Select</option>
                                <option value="canceled" data-book_id ="<?php echo $val['book_id']?>">Cancel</option>
                              </select>
                             <?php }
                            ?>
                      </td>
                      <td><?php echo $val['notes']; ?></td>
                      <td>
                        <?php 
                        if(!empty($val['google_event_id'])){?>
                          <a class="btn btn-success btn-sidenav custom_sync_class disabled" data-book_id="<?php echo $val['book_id']?>" data-calander_url="<?php echo $login_url; ?>" href="javascript:void(0)">Sync Now</a>
                          <span>Already synced with Google Calendar</span>
                       <?php }else{?>
                          <a class="btn btn-success btn-sidenav custom_sync_class" data-book_id="<?php echo $val['book_id']?>" data-calander_url="<?php echo $login_url; ?>" href="javascript:void(0)">Sync Now</a>
                         <?php }
                        ?>
                      </td>
                    </tr>
                    <?php 
                  }
                  ?>
                  <?php else: ?>
                    <tr><td colspan="3">No Appointements Yet</td></tr>
                  <?php endif; ?>
                </tbody>

              </table>
            </div>
            <div id="menu1" class="container tab-pane fade"><br>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Client Name</th>
                    <th>Date_Time</th>
                    <th>Edit Status</th>
                    <th>Notes</th>
                    <!-- <th>Sync. with Google Calender</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php  if(!empty($pending)): ?>
                    <?php foreach($pending as $key=>$val){ 
                      $u_data = get_userdata($val['current_user_id']);
                      $date = new DateTime($val['slot_date']);
                      ?>
                      <tr>
                        <td><a href="<?php echo esc_url(ap_user_link($val['current_user_id']) . $scisco_page_slug) ?>"><?php echo $u_data->display_name; ?></a></td>
                        <td><?php echo $date->format('m-d-Y')." || ".$val['slot_time']; ?></td>
                        <td><select name="pending_appointements" id="pending_appointements" class="tab-sel">
                          <option value="" >Select</option>
                          <option data-book_id ="<?php echo $val['book_id']?>" value="booked">Accept Appointement</option>
                          <option data-book_id ="<?php echo $val['book_id']?>" value="canceled">Cancel</option>
                        </select>
                         <!-- <td><a class="btn btn-success btn-sidenav" href="<?php echo $login_url; ?>">Sync Now</a></td> -->
                      </td>
                      <td><?php echo $val['notes']; ?></td>
                    </tr>
                    <?php 
                  }
                  ?>
                  <?php else: ?>
                    <tr><td colspan="3">No Pending Appointements Yet</td></tr>
                  <?php endif; ?>

                </tbody>
              </table>
            </div>
            <div id="menu2" class="container tab-pane fade"><br>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Client Name</th>
                    <th>Date_Time</th>
                    <th>Notes</th>
                  </tr>
                </thead>
                <tbody>
                  <?php  if(!empty($canceled)): ?>
                    <?php foreach($canceled as $key=>$val){ 
                      $u_data = get_userdata($val['client_id']);
                      $date = new DateTime($val['slot_date']);
                      ?>
                      <tr>
                        <td><a href="<?php echo esc_url(ap_user_link($val['current_user_id']) . $scisco_page_slug) ?>">><?php echo $u_data->display_name; ?></a></td>
                        <td><?php echo $date->format('m-d-Y')." || ".$val['slot_time']; ?></td>
                        <td><?php echo $val['notes']; ?></td>
                      </tr>
                      <?php 
                    }
                    ?>
                    <?php else: ?>
                      <tr><td colspan="3">No Cancelled Appointements Yet</td></tr>
                    <?php endif; ?>
                  </tbody>

                </tbody>
              </table> </div>
              <div id="menu3" class="container tab-pane fade"><br>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Client Name</th>
                    <th>Date & Time</th>
                    <!-- <th>Notes</th> -->
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php  
                  $logged_user_id = get_current_user_id();
                  $query = $wpdb->prepare("SELECT * FROM $table WHERE current_user_id = %d", $logged_user_id);
                  $result = $wpdb->get_results($query, ARRAY_A);
                  // echo "<pre>";print_r($result);
                  if(!empty($result)): ?>
                    <?php foreach($result as $key=>$val){ 
                      $u_data = get_userdata($val['client_id']);
                      ?>
                      <tr>
                        <td><a href="<?php echo esc_url(ap_user_link($val['current_user_id']) . $scisco_page_slug) ?>"><?php echo $u_data->display_name; ?></a></td>
                        <td><?php echo $val['slot_date']." || ".$val['slot_time']; ?></td>
                        <!-- <td><?php echo $val['topic'] ?></td> -->
                        <td><?php echo $val['status'] ?></td>
                      </tr>
                      <?php 
                    }
                    ?>
                    <?php else: ?>
                      <tr><td colspan="3">No Cancelled Appointements Yet</td></tr>
                    <?php endif; ?>
                  </tbody>

                </tbody>
              </table> </div>
            </div>
          </div>
        </div>
      </div>
      <div class="loader">
        <img class="lazyloaded" src="/wp-content/uploads/2024/02/loader1.gif">
      </div>
      <?php else: ?>
  <div class="alert alert-danger">Please <a href="/wp-login.php">Login/Register</a> and Complete Your Profile to access this page!</div> 
 <?php endif; ?> 
 </main> 
<?php 
get_footer();
