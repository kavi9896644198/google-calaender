<?php 
/*
*Template name: Booking Form
*/

get_header();
?>
<?php 
//Header template
 get_template_part('custom','templates/canna-header');
?>

<main id="scisco-main-wrapper">
<?php if((is_user_logged_in())): 
 if(isset($_GET['cs'])):
	$user_id = base64_decode($_GET['cs']);
	$user_displayname = get_user_by( "id", $user_id )->display_name;
  
  // Fetch events query  	
	global $wpdb;
	$table = $wpdb->prefix.'event_calender';
	$query = $wpdb->prepare("SELECT * FROM $table WHERE current_user_id=%s;", $user_id );
	$results = $wpdb->get_results($query, ARRAY_A);
	//echo"<pre>";print_r($results);
	$events =  json_encode($results);
    // print($events);die;
	?>

    <div class="container">
        <div class="row">
            <div class="col-sm-12"> 
                <form id="bookingForm">
                   <h5 align="center">Click on a date to book a slot.</h5>
                   <div class="book-slot text-right">
                        <span class="green-box"></span> Working Hours
                   </div>
                    <div id="book_time"></div>
                    <!-- <label for="description">Description<small>(Optional)</small></label> -->
                    <!-- <textarea id="description" name="description" rows="4" cols="50"></textarea>  -->
                    <input type="hidden" id="selectedDate" name="selectedDate" >
                    <input type="hidden" id="selectedTimeSlot" name="selectedTimeSlot">
                    <!-- Time slots will be dynamically added here -->

                    <!-- <button type="submit">Submit</button> -->
                </form>
                <div class="modal fade event_calender_modal" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader">
        <img class=" lazyloaded" src="/wp-content/uploads/2024/02/loader1.gif">
    </div>
<script>

        // Inject PHP data into JavaScript
$(document).ready(function() {
	var evenPush = [];
	// Inject PHP data into JavaScript
	var events = <?php echo $events; ?>;
	
	 //var responseData = JSON.parse(events);
        events.forEach(function(event) {
        var eventId = event.event_id;
        var eventName = event.event_name;
        var event_current_date = event.event_current_date;
        var eventColor = event.color;
        var eventUrl = event.url;
        evenPush.push({
            event_id: eventId,
            title: eventName,
            start: event_current_date,
            color: '#008000',
        });
    });
	// Initialize FullCalendar with events
	$('#book_time').fullCalendar({
		defaultView: 'month',
		selectable: true,
		selectHelper: true,
		editable: true,
		eventLimit: true,
		events: evenPush,
		dayClick: function(date, jsEvent, view) {
			var currentDate = date.format('YYYY-MM-DD'); // Get the current date
            jQuery('#current_date_select').val(currentDate);
            // jQuery('.new_content_append').css(currentDate);
            jQuery('.modal-content').html(' ');
            var newContent = jQuery('.new_content_append').clone(true);
            newContent.show();
            
            jQuery('.modal-content').html(newContent);
            jQuery('#event_entry_modal').modal('show');
		},
		eventRender: function(event, element, view) { 
            element.bind('click', function(){
                    jQuery('body').addClass('event_loader');
                    $.ajax({
                        type: "post",
                        url: ajaxurl,
                        data: { action: "show_time_slots", event_id: event.event_id},
                        success: function(response) {
                            jQuery('body').removeClass('event_loader');
                            var responseData = JSON.parse(response);
                            if(responseData.message == 'success'){
                                jQuery('.modal-content').html('');
                                jQuery('.modal-content').html(responseData.html);
                                $('#event_entry_modal').modal('show');
                            }
                        },
                        error: function(xhr, status) {
                            console.log('ajax error = ' + xhr.statusText);
                        }
                    });
                    // jQuery('#event_edit_modal').modal('show');
                });
        },
        eventAfterAllRender: function (view) {
                    markCellsWithEvents();
                }
});

    function markCellsWithEvents() {
                $('td.fc-day').each(function () {
                    var date = $(this).attr('data-date');
                    if ($('#book_time').fullCalendar('clientEvents', function (event) {
                        return moment(event.start).format('YYYY-MM-DD') == date;
                    }).length > 0) {
                        $(this).addClass('has-event');
                    }
                });
            }
	// Form submission handling
	$('#bookingForm').submit(function(event) {
	// Prevent the default form submission
	event.preventDefault();

	// Retrieve selected date and time slot
	var selectedDate = $('#selectedDate').val();
	var selectedTimeSlot = $('#selectedTimeSlot').val();

	// Perform further actions (e.g., AJAX submission)
	console.log('Selected Date:', selectedDate);
	console.log('Selected Time Slot:', selectedTimeSlot);
});
});

function book_slot() {
    var topic = jQuery("#topic").val();
    var client_id = jQuery("#client_id").val();
    var current_user_id = jQuery("#current_user_id").val();
    var current_date_select = jQuery("#event_current_date").val();
    var event_id = jQuery("#event_id").val();
    var selectedSlot = jQuery('input[name="timeSlot"]:checked').val();
    if (topic.trim() === '') {
       alert('Please enter a topic.');
    } else if (!selectedSlot) {
      alert('Please select a time slot.');
    } else {
        jQuery('.spinner_border_custom').css('display','block');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "book_time_slot", topic: topic,current_user_id:current_user_id,client_id:client_id,current_date_select:current_date_select, event_id:event_id,selectedSlot:selectedSlot },
            success: function(response) {
                var responseData = JSON.parse(response);
                if(responseData.status == 200){
                    jQuery('.spinner_border_custom').css('display','block');
                    jQuery('#event_entry_modal').modal('hide'); 
                    location.reload();
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
</main>

<?php else: ?>
<div class="error"> You are not allowed to access this page</div>
<?php endif; ?> 
<?php else: ?>
<div class="alert alert-danger">Please <a href="/wp-login.php">Login/Register</a> and Complete Your Profile to access this page!</div> 
<?php endif; ?> 
<?php 
get_footer();