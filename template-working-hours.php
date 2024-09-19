<?php 
/*Template name: Working Schedule*/

get_header();

//Header template
 get_template_part('custom','templates/canna-header');
?>

<!-- CSS for full calender -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" /> -->
<!-- JS for jQuery -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- JS for full calender -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script> -->
<!-- bootstrap css and js -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
<main id="scisco-main-wrapper">
<?php if((is_user_logged_in())): ?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h5 align="center">Click on a date below to set your available times to meet with others</h5>
             <div class="book-slot1 text-right">
                <span class="blue-box"></span> Available Meeting Times
           </div>
            <div class="conference_custom_date" style="margin-top: 100px;text-align: center;">
                <?php 
                $currentYear = date("Y");
                global $wpdb;
                $current_user_id = get_current_user_id();
                $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}event_calender WHERE current_user_id = %d", $current_user_id);
                $results = $wpdb->get_results($query, ARRAY_A);
                $hasDate18 = false;
                $hasDate19 = false;
                $eventId18 = '';
                $eventId19 = '';
                foreach ($results as $value) {
                    if (trim($value['event_current_date']) == $currentYear.'-10-18') {
                        $eventId18 = $value['event_id'];
                        $hasDate18 = true;
                    } elseif (trim($value['event_current_date']) == $currentYear.'-10-19') {
                        $eventId19 = $value['event_id'];
                        $hasDate19 = true;
                    }
                }
                // Generate buttons based on the existence of each date
                if ($hasDate18) { ?>
                    <input type="button" class="custom_date_update" data-event_id="<?php echo $eventId18; ?>" value="Oct 18th, <?php echo $currentYear; ?>">
                <?php } else { ?>
                    <input type="button" data-custom_date="<?php echo $currentYear; ?>-10-18" class="custom_date_add" value="Oct 18th, <?php echo $currentYear; ?>">
                <?php }

                if ($hasDate19) { ?>
                    <input type="button" class="custom_date_update" data-event_id="<?php echo $eventId19; ?>" value="Oct 19th, <?php echo $currentYear; ?>">
                <?php } else { ?>
                    <input type="button" data-custom_date="<?php echo $currentYear; ?>-10-19" class="custom_date_add" value="Oct 19th, <?php echo $currentYear; ?>">
                <?php } ?>
            </div>
            <div class="show_conference_message">
                <?php if ($eventId18 || $eventId19): ?>
                <div>You've successfully set available times for the following dates:</div>
                <?php if ($eventId18): ?>
                    <div>18-10-<?php echo $currentYear; ?></div>
                <?php endif; ?>
                <?php if ($eventId19): ?>
                    <div>19-10-<?php echo $currentYear; ?></div>
                <?php endif; ?>
            <?php endif; ?>
            <style>
                .show_conference_message {
                    margin-top: 30px;
                    font-size: 20px;
                }           
            </style>
            </div>
            <!-- <div id="calendar"></div> -->
        </div>
    </div>
</div>
<!-- edit popup -->
<div class="modal fade event_calender_modal" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<!-- End popup dialog box -->
<br>
<!-- <center>Developed by <a href="https://shinerweb.com/">Shinerweb</a></center> -->
</body>
<div style="display: none;">
    <div class="new_content_append" >
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Set Your Availability for Mettings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="error_message_show"></div>
        <input type="hidden" name="current_user_id" id="current_user_id" value="<?php echo get_current_user_id(); ?>" class="form-control" placeholder="">
        <input type="hidden" name="current_date_select" id="current_date_select" value="" class="form-control" placeholder="">
        <div class="modal-body">
            <div class="img-container">
                <!-- <div class="row">
                    <div class="col-sm-12">  
                        <div class="form-group">
                            <label for="event_name">Event name</label>
                            <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter your event name">
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-sm-12"> 
                        <div>Add Slot</div>
                    </div>
                </div>
                <div class="multi-field-wrapper">
                    <div class="multi-fields">
                        <div class="multi-field">
                            <div class="no-gutters row">
                                <div class="col-sm-5">
                                    <div><b>Start Time:</b></div>
                                    <select name="start_hour[1]" class="time-select">
                                       <?php
                                        $start_options = array("10", "11", "12", "1", "2", "3", "4", "5", "6");
                                        foreach ($start_options as $hour) {
                                            $displayHour = ($hour <= 12) ? $hour : $hour - 12;
                                            if ($displayHour === 0 || $displayHour === 12) {
                                                $displayHour = 12;
                                            }
                                            $amPm = ($hour < 12) ? "AM" : "PM";
                                            $selected = ($hour == 10) ? 'selected' : ''; // This line sets 'selected' for 10 AM, you can adjust it as needed
                                            echo '<option value="' . sprintf("%02d", $displayHour) . '" ' . $selected . '>'.sprintf("%02d", $displayHour).'</option>';
                                        }
                                        ?>
                                    </select>
                                    <select name="start_minute[1]" class="time-select">
                                        <?php
                                        for ($minute = 00; $minute < 60; $minute += 15) {
                                            echo '<option value="' . sprintf("%02d", $minute) . '">' . sprintf("%02d", $minute) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <select name="start_ampm[1]" class="time-select">
                                        <option value="AM" selected="selected">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <div><b>End Time:</b></div>
                                    <select name="end_hour[1]" class="time-select">
                                        <?php
                                        $end_options = array("10", "11", "12", "1", "2", "3", "4", "5", "6");
                                        foreach ($end_options as $hour) {
                                            $displayHour = ($hour <= 12) ? $hour : $hour - 12;
                                            if ($displayHour === 0 || $displayHour === 12) {
                                                $displayHour = 12;
                                            }
                                            $amPm = ($hour < 12) ? "AM" : "PM";
                                            $selected = ($hour == 5) ? 'selected' : ''; // This line sets 'selected' for 5 PM, you can adjust it as needed
                                            echo '<option value="' . sprintf("%02d", $displayHour) . '" ' . $selected . '>'.sprintf("%02d", $displayHour).'</option>';
                                        }
                                        ?>

                                    </select>
                                    <select name="end_minute[1]" class="time-select">
                                        <?php
                                        for ($minute = 00; $minute < 60; $minute += 15) {
                                            // $selected = ($minute == 15) ? 'selected' : '';
                                            echo '<option value="' . $minute . '">' . sprintf("%02d", $minute) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <select name="end_ampm[1]" class="time-select">
                                        <option value="AM">AM</option>
                                        <option value="PM" selected="selected">PM</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" class="remove-field">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="add-field">Add Another Time Slot</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="add-field" onclick="save_event()"><span class="spinner-border spinner-border-sm spinner_border_custom" style="display:none;"></span>Save Availability Times</button>
        </div>
    </div>
</div>
<div class="loader">
    <img class=" lazyloaded" src="/wp-content/uploads/2024/02/loader1.gif">
</div>

<?php else: ?>
  <div class="alert alert-danger"><?php esc_html_e( 'Please Login/Register and Complete Your Profile to access this page for slot booking!', 'scisco' ); ?></div> 
 <?php endif; ?> 
 </main> 
<?php 
get_footer();

