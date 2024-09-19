jQuery(document).ready(function ($) {
	jQuery('#booked_appointements,#pending_appointements').on('change', function () {
         var status = $(this).val();
		 var BookId = $(this).find('option:selected').data('book_id');

        jQuery("body").addClass("event_loader");
        setTimeout(function() {
		
		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: 'json',
			data: { action: 'update_booking_status', status: status, BookId: BookId },
			success: function (response) {

				console.log("sdf", response);

				jQuery("body").removeClass('event_loader');

				location.reload();
			},

			error: function (error) {
				console.error("Ajax request failed:", error);

			}
		}); 
		 }, 2500);
	});

	jQuery('#booked_appointements_google').on('change', function () {
         var status = jQuery(this).val();
         var calander_url = jQuery(this).data('calander_url');
		 var BookId = jQuery(this).find('option:selected').data('book_id');
        jQuery("body").addClass("event_loader");
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: 'json',
			data: { action: 'cancel_appointment_google_calender', status: status, BookId: BookId },
			success: function (response) {
				jQuery("body").removeClass('event_loader');
				window.location.href = calander_url;
			},
			error: function (error) {
				console.error("Ajax request failed:", error);
			}
		}); 
	});

	jQuery('.custom_sync_class').click(function(){
		 var bookId = jQuery(this).data('book_id');
		 var calander_url = jQuery(this).data('calander_url');
		 jQuery("body").addClass("event_loader");
		 $.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: 'json',
			data: { action: 'sync_now_google_calender_url', bookId: bookId},
			success: function (response) {
				jQuery("body").removeClass('event_loader');
				window.location.href = calander_url;
			},
			error: function (error) {
				console.error("Ajax request failed:", error);

			}
		});

	});

});
function isGmailLoggedIn() {
    // Check if Google cookies exist
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        // Check for specific Google cookies
        if (cookie.startsWith('GMAIL_')) {
            return true; // Gmail cookies found
        }
    }
    return false; // No Gmail cookies found
}

// Example usage
if (isGmailLoggedIn()) {
    console.log("User is logged into a Gmail account.");
} else {
    console.log("User is not logged into a Gmail account.");
}
