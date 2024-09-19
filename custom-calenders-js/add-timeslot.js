 jQuery(document).ready(function () {
        display_events();
        jQuery('body').on('click', '.add-field', function (e) {
        var wrapper = jQuery(this).closest('.multi-field-wrapper').find('.multi-fields');
        jQuery('.multi-field:first-child', wrapper).clone(true).appendTo(wrapper).find('input').val('').focus();

        wrapper.find('.multi-field').each(function (i) {
            var currentIndex = i + 1;
            jQuery(this).find('select').each(function () {
                var currentName = jQuery(this).attr('name');
                var newName = currentName.replace(/\[\d+\]/g, '[' + currentIndex + ']');
                jQuery(this).attr('name', newName);
            });
        });
    });

    jQuery('body').on('click', '.remove-field', function (){
        var wrapper = jQuery(this).closest('.multi-field-wrapper').find('.multi-fields');
        if (wrapper.find('.multi-field').length > 1)
            jQuery(this).closest('.multi-field').remove();
    });
});


 //end document.ready block
function display_events() {
    var events = new Array();
jQuery.ajax({
    type : "post",
    url : ajaxurl,
    data : {action: "display_events_function"},
    success: function (response) {
        var responseData = JSON.parse(response);
        responseData.forEach(function(event) {
        var eventId = event.event_id;
        var eventName = event.event_name;
        var event_current_date = event.event_current_date;
        var eventColor = event.color;
        var eventUrl = event.url;
        // Parse event time slots
        var eventTimeSlots = JSON.parse(event.event_time_slot);
        
        // Prepare custom data string
        var customData = "";
        eventTimeSlots.forEach(function(timeSlot, index){
            customData += "Start: " + timeSlot.startTime.hour + ":" + timeSlot.startTime.minute + "-" +
                          "End: " + timeSlot.endTime.hour + ":" + timeSlot.endTime.minute;
            if (index < eventTimeSlots.length - 1) {
                customData += ", ";
            }
        });
        // console.log('testjjj',customData);
        events.push({
            event_id: eventId,
            title: 'eventName',
            start: event_current_date,
            color: '',
            url: '',
            customData: customData 
        });
    });
    jQuery('body').on('click','.schedule_Metting_class',function(e){
        e.preventDefault();
        jQuery('body').addClass('event_loader');
        var user_id = jQuery(this).data('current_user_id');
        var schedule = jQuery(this).data('schedule');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "schedule_Metting", user_id:user_id,schedule:schedule},
            success: function(response) {
                jQuery('body').removeClass('event_loader');
                var responseData = JSON.parse(response);
                if(responseData.message == 'success'){
                    jQuery('.modal-content').html('');
                    jQuery('.modal-content').html(responseData.html);
                    jQuery('#schedule_Metting_popup').modal('show');
                }
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
            }
        });
    });

    jQuery('body').on('click','.available_meeting_time_custom',function(e){
        e.preventDefault();
        jQuery('body').addClass('event_loader');
        var user_id = jQuery(this).data('current_user_id');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "schedule_Metting_1_function", user_id:user_id},
            success: function(response) {
                jQuery('body').removeClass('event_loader');
                var responseData = JSON.parse(response);
                if(responseData.message == 'success'){
                    jQuery('.modal-content').html('');
                    jQuery('.modal-content').html(responseData.html);
                    // jQuery('.request_for_availability').data('user_id',responseData.client_user_id);
                    jQuery('#available_meeting_time_custom').modal('show');
                }
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
            }
        });
    });
    jQuery('body').on('click','.request_for_availability',function(){
        jQuery('body').addClass('event_loader');
        var user_id = jQuery(this).data('user_id');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "request_for_availability", user_id:user_id},
            success: function(response) {
                jQuery('body').removeClass('event_loader');
                var responseData = JSON.parse(response);
                if(responseData.status == '200'){
                    swal("Your message has been sent and you will notified when  they set  their available meeting times!")
                    .then((value) => {
                      // swal(`The returned value is: ${value}`);
                    });
                }
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
            }
        });
    });

    jQuery('body').on('change','.select_available_date',function(){
        var selected_date = jQuery(this).val();
        var event_id = jQuery(this).find('option:selected').data('event_id');
        jQuery('body').addClass('event_loader');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "available_slot_time_show", event_id:event_id},
            success: function(response) {
                jQuery('body').removeClass('event_loader');
                var responseData = JSON.parse(response);
                console.log(responseData);
                if(responseData.message == 'success'){
                    jQuery('.available_times').html('');
                    jQuery('.available_times').html(responseData.html);
                    // jQuery('#schedule_Metting_popup').modal('show');
                }
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
            }
        });
    });
    jQuery('.custom_date_add').click(function(){
        var currentDate = jQuery(this).data('custom_date');
        jQuery('#current_date_select').val(currentDate);
        jQuery('.modal-content').html(' ');
        var newContent = jQuery('.new_content_append').clone(true);
            newContent.show();
            
        jQuery('.modal-content').html(newContent);
        jQuery('#event_entry_modal').modal('show');
    });
    jQuery('.custom_date_update').click(function(){
        jQuery('body').addClass('event_loader');
        var event_id = jQuery(this).data('event_id');
        $.ajax({
                type: "post",
                url: ajaxurl,
                data: { action: "show_popup_event_function", event_id: event_id},
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
    });
    // var calendar = jQuery('#calendar').fullCalendar({
    //     defaultView: 'month',
    //     timeZone: 'local',
    //     editable: true,
    //     selectable: true,
    //     selectHelper: true,
    //     dayClick: function(date, jsEvent, view) {
    //         var currentDate = date.format('YYYY-MM-DD'); // Get the current date
    //         jQuery('#current_date_select').val(currentDate);
    //         // jQuery('.new_content_append').css(currentDate);
    //         jQuery('.modal-content').html(' ');
    //         var newContent = jQuery('.new_content_append').clone(true);
    //         newContent.show();
            
    //         jQuery('.modal-content').html(newContent);
    //         jQuery('#event_entry_modal').modal('show');
    //     },
    //     events: events,
    //     eventRender: function(event, element, view) { 
    //         element.bind('click', function(){
    //                 jQuery('body').addClass('event_loader');
    //                 // jQuery('#current_event_id').val(event.event_id);
    //                 console.log('test2');
    //                 $.ajax({
    //                     type: "post",
    //                     url: ajaxurl,
    //                     data: { action: "show_popup_event_function", event_id: event.event_id},
    //                     success: function(response) {
    //                         jQuery('body').removeClass('event_loader');
    //                         var responseData = JSON.parse(response);
    //                         if(responseData.message == 'success'){
    //                             jQuery('.modal-content').html('');
    //                             jQuery('.modal-content').html(responseData.html);
    //                             $('#event_entry_modal').modal('show');
    //                         }
    //                     },
    //                     error: function(xhr, status) {
    //                         console.log('ajax error = ' + xhr.statusText);
    //                     }
    //                 });
    //                 // jQuery('#event_edit_modal').modal('show');
    //             });
    //     },
    //     eventAfterAllRender: function (view) {
    //                 markCellsWithEvents();
    //             }
    //     }); //end fullCalendar block    
      },//end success block
      error: function (xhr, status) {
      alert(response.msg);
      }
    });//end ajax block 
}

function markCellsWithEvents() {
    $('td.fc-day').each(function () {
        var date = $(this).attr('data-date');
        if ($('#calendar').fullCalendar('clientEvents', function (event) {
            return moment(event.start).format('YYYY-MM-DD') == date;
        }).length > 0) {
            $(this).addClass('has-event');
        }
    });
}

function save_event(){
    var event_name = jQuery("#event_name").val();
    var current_user_id = jQuery("#current_user_id").val();
    var current_date_select = jQuery("#current_date_select").val();
    var formData = [];
    var isValid = true; // Flag to track overall validation
    $(".multi-field").each(function(index) {
        var startTimeHour = parseInt($(this).find("select[name='start_hour[" + (index + 1) + "]']").val());
        var startTimeMinute = parseInt($(this).find("select[name='start_minute[" + (index + 1) + "]']").val());
        var endTimeHour = parseInt($(this).find("select[name='end_hour[" + (index + 1) + "]']").val());
        var endTimeMinute = parseInt($(this).find("select[name='end_minute[" + (index + 1) + "]']").val());
        var start_ampm = $(this).find("select[name='start_ampm[" + (index + 1) + "]']").val();
        var end_ampm = $(this).find("select[name='end_ampm[" + (index + 1) + "]']").val();
        // Convert AM/PM to 24-hour format for comparison

        var startTimeMinute = startTimeMinute < 10 ? '0' + startTimeMinute : startTimeMinute.toString();
        var endTimeMinute = endTimeMinute < 10 ? '0' + endTimeMinute : endTimeMinute.toString();
        if(startTimeHour == endTimeHour && startTimeMinute == endTimeMinute && start_ampm == end_ampm){
             isValid = false;
            jQuery('.error_message_show').text("End time must be greater than start time for time slot " + (index + 1) + ".");
            setTimeout(function() {
                jQuery('.error_message_show').text('');
            }, 2000);
            return false;
        }
      // Assuming startTimeHour and endTimeHour are in 12-hour format, and start_ampm and end_ampm represent whether it's AM or PM.
     if(!isNaN(startTimeHour) && !isNaN(startTimeMinute) && !isNaN(endTimeHour) && !isNaN(endTimeMinute)) {
            if (start_ampm == "PM" && startTimeHour != 12) {
                startTimeHour += 12;
            } else if (start_ampm == "AM" && startTimeHour == 12) {
                startTimeHour = 0;
            }

            if (end_ampm == "PM" && endTimeHour != 12) {
                endTimeHour += 12;
            } else if (end_ampm == "AM" && endTimeHour == 12) {
                endTimeHour = 0;
            }
            // Check if the selected time range is between 10:00 AM and 6:00 PM
            if ((startTimeHour >= 10 && startTimeHour < 18) || (startTimeHour == 18 && startTimeMinute == 0 && endTimeHour == 18 && endTimeMinute == 0)){
                if ((endTimeHour > 10 && endTimeHour <= 18) || (endTimeHour == 10 && endTimeMinute >= 0 && startTimeHour == 10 && startTimeMinute >= 0)){
                    console.log("Selected time range is between 10:00 AM and 6:00 PM");
                    isValid = true;
                }else{
                    isValid = false;
                    jQuery('.error_message_show').text("End time is not between 10:00 AM and 6:00 PM." + (index + 1));
                    setTimeout(function() {
                        jQuery('.error_message_show').text('');
                    }, 2000);
                    return false;
                }
            }else{
                isValid = false;
                jQuery('.error_message_show').text("Start time is not between 10:00 AM and 6:00 PM." + (index + 1));
                setTimeout(function() {
                    jQuery('.error_message_show').text('');
                }, 2000);
                return false;
            }
    }
        var startTimeHour = parseInt($(this).find("select[name='start_hour[" + (index + 1) + "]']").val());
        var startTimeMinute = parseInt($(this).find("select[name='start_minute[" + (index + 1) + "]']").val());
        var endTimeHour = parseInt($(this).find("select[name='end_hour[" + (index + 1) + "]']").val());
        var endTimeMinute = parseInt($(this).find("select[name='end_minute[" + (index + 1) + "]']").val());
        var start_ampm = $(this).find("select[name='start_ampm[" + (index + 1) + "]']").val();
        var end_ampm = $(this).find("select[name='end_ampm[" + (index + 1) + "]']").val();
        if (!isNaN(startTimeHour) && !isNaN(startTimeMinute) && !isNaN(endTimeHour) && !isNaN(endTimeMinute)) {
            var startTime = {
                hour: startTimeHour,
                minute: startTimeMinute,
                ampm: $(this).find("select[name='start_ampm[" + (index + 1) + "]']").val()
            };
            var endTime = {
                hour: endTimeHour,
                minute: endTimeMinute,
                ampm: $(this).find("select[name='end_ampm[" + (index + 1) + "]']").val()
            };
            formData.push({
                startTime: startTime,
                endTime: endTime
            });
        }       
    });
    console.log(formData);
    if (!isValid) {
        return false; // Exit the function if any validation failed
    }
    var result = checkForDuplicatesAndOverlap(formData);
    if (result) {
        jQuery('.error_message_show').text(result);
        setTimeout(function() {
          jQuery('.error_message_show').text('');
        }, 2000);
    } else {
        jQuery('.spinner_border_custom').css('display','block');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "save_event_function",current_user_id:current_user_id,current_date_select:current_date_select, formData },
            success: function(response) {
                jQuery('.spinner_border_custom').css('display','none');
                $('#event_entry_modal').modal('hide');
                // location.reload();
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
                // alert(response.msg);
            }
        });
    }
    // Proceed with the AJAX request if all validations passed
    return false;
}

jQuery(document).ready(function() {

//     jQuery('body').on("change", '.time-select', function() {
//         const currentValue = jQuery(this).val();
//         const attributeName = jQuery(this).attr('name');
//         const match = attributeName.match(/^([^\[]+)\[(\d+)\]$/);
//         if (match) {
//             const startHour = match[1]; // Extracted attribute name ("start_hour")
//             const index = match[2]; // Extracted index (1)
//             if (startHour == 'start_hour' || startHour == 'start_minute' || startHour == 'start_ampm') {
//                 // Calculate end time based on start time
//                 const startHourValue = parseInt(jQuery('select[name="start_hour[' + index + ']"]').val());
//                 const startMinuteValue = parseInt(jQuery('select[name="start_minute[' + index + ']"]').val());
//                 var startAmPmValue = jQuery('select[name="start_ampm[' + index + ']"]').val();
//                 var endHourValue = startHourValue;
//                 var endMinuteValue = startMinuteValue + 15; // Increment by 15 minutes
//                 var endAmPmValue = startAmPmValue;
//                 // Adjust end minute and hour if it exceeds 59 or 12 respectively
//                 if (endMinuteValue >= 60) {
//                     endHourValue += 1;
//                     endMinuteValue -= 60;
//                 }
//                 if (endHourValue > 12) {
//                     endHourValue -= 12;
//                     console.log('endHourValue')
//                     // endAmPmValue = (endAmPmValue === 'AM') ? 'PM' : 'AM';
//                 }
//                 if(startHourValue >= 12 || startHourValue < 10){
//                     endAmPmValue = 'PM';
//                     var startAmPmValue = 'PM';
//                 }else if(startHourValue === 11 && startMinuteValue >= 45){
//                    var endAmPmValue = 'PM';
//                 }else{
//                     var endAmPmValue = 'AM';
//                     var startAmPmValue = 'AM';
//                 }
//                 endMinuteValue = (endMinuteValue < 10 ? '0' : '') + endMinuteValue;
//                 console.log(endMinuteValue);
//                 // Update the end time select elements
//                 jQuery('select[name="end_hour[' + index + ']"]').val(endHourValue);
//                 jQuery('select[name="end_minute[' + index + ']"]').val(endMinuteValue);
//                 jQuery('select[name="end_ampm[' + index + ']"]').val(endAmPmValue);
//                 jQuery('select[name="start_ampm[' + index + ']"]').val(startAmPmValue);
//                 jQuery('select[name="end_hour[' + index + ']"]').prop('disabled', true);
//                 jQuery('select[name="end_minute[' + index + ']"]').prop('disabled', true);
//                 jQuery('select[name="end_ampm[' + index + ']"]').prop('disabled', true);
//                 jQuery('select[name="start_ampm[' + index + ']"]').prop('disabled', true);
//             }
//         } else {
//             console.log("Invalid attribute name format");
//         }
//     });
});

function checkForDuplicatesAndOverlap(formData) {
    for (var i = 0; i < formData.length - 1; i++) {
        for (var j = i + 1; j < formData.length; j++) {
            // Check for duplicate time slots
            if (formData[i].startTime.hour === formData[j].startTime.hour &&
                formData[i].startTime.minute === formData[j].startTime.minute &&
                formData[i].startTime.ampm === formData[j].startTime.ampm &&
                formData[i].endTime.hour === formData[j].endTime.hour &&
                formData[i].endTime.minute === formData[j].endTime.minute &&
                formData[i].endTime.ampm === formData[j].endTime.ampm) {
                return 'Duplicate time slots found. Please choose another.';
            }

            if ((formData[i].startTime.hour < formData[j].endTime.hour ||
                    (formData[i].startTime.hour === formData[j].endTime.hour &&
                        formData[i].startTime.minute < formData[j].endTime.minute)) &&
                (formData[i].endTime.hour > formData[j].startTime.hour ||
                    (formData[i].endTime.hour === formData[j].startTime.hour &&
                        formData[i].endTime.minute > formData[j].startTime.minute))) {
                return 'Overlapping time slots found. Please adjust the time range.';
            }
    
        }
    }
    return false; // No duplicates or overlaps found
}



function edit_event(){
    var event_name = jQuery("#event_name").val();
    var current_user_id = jQuery("#current_user_id").val();
    var current_event_select = jQuery("#current_event_select").val();
    if (event_name == "") {
         jQuery('.error_message_show').text('Please enter the event name.');
        setTimeout(function() {
          jQuery('.error_message_show').text('');
        }, 2000);
        return false;
    }
    var formData = [];
    var isValid = true; // Flag to track overall validation
    $(".multi-field").each(function(index) {
        var startTimeHour = parseInt($(this).find("select[name='start_hour[" + (index + 1) + "]']").val());
        var startTimeMinute = parseInt($(this).find("select[name='start_minute[" + (index + 1) + "]']").val());
        var endTimeHour = parseInt($(this).find("select[name='end_hour[" + (index + 1) + "]']").val());
        var endTimeMinute = parseInt($(this).find("select[name='end_minute[" + (index + 1) + "]']").val());
        var start_ampm = $(this).find("select[name='start_ampm[" + (index + 1) + "]']").val();
        var end_ampm = $(this).find("select[name='end_ampm[" + (index + 1) + "]']").val();
        if(startTimeHour == endTimeHour && start_ampm == end_ampm && startTimeMinute == endTimeMinute){
             isValid = false;
            jQuery('.error_message_show').text("End time must be greater than start time for time slot " + (index + 1) + ".");
            setTimeout(function() {
                jQuery('.error_message_show').text('');
            }, 2000);
            return false;
        }

        if(!isNaN(startTimeHour) && !isNaN(startTimeMinute) && !isNaN(endTimeHour) && !isNaN(endTimeMinute)) {
            if (start_ampm == "PM" && startTimeHour != 12) {
                startTimeHour += 12;
            } else if (start_ampm == "AM" && startTimeHour == 12) {
                startTimeHour = 0;
            }

            if (end_ampm == "PM" && endTimeHour != 12) {
                endTimeHour += 12;
            } else if (end_ampm == "AM" && endTimeHour == 12) {
                endTimeHour = 0;
            }
            // Check if the selected time range is between 10:00 AM and 6:00 PM
            if ((startTimeHour >= 10 && startTimeHour < 18) || (startTimeHour == 18 && startTimeMinute == 0 && endTimeHour == 18 && endTimeMinute == 0)){
                if ((endTimeHour > 10 && endTimeHour <= 18) || (endTimeHour == 10 && endTimeMinute >= 0 && startTimeHour == 10 && startTimeMinute >= 0)){
                    console.log("Selected time range is between 10:00 AM and 6:00 PM");
                    isValid = true;
                }else{
                    isValid = false;
                    jQuery('.error_message_show').text("End time is not between 10:00 AM and 6:00 PM." + (index + 1));
                    setTimeout(function() {
                        jQuery('.error_message_show').text('');
                    }, 2000);
                    return false;
                }
            }else{
                isValid = false;
                jQuery('.error_message_show').text("Start time is not between 10:00 AM and 6:00 PM." + (index + 1));
                setTimeout(function() {
                    jQuery('.error_message_show').text('');
                }, 2000);
                return false;
            }
        }
        var startTimeHour = parseInt($(this).find("select[name='start_hour[" + (index + 1) + "]']").val());
        var startTimeMinute = parseInt($(this).find("select[name='start_minute[" + (index + 1) + "]']").val());
        var endTimeHour = parseInt($(this).find("select[name='end_hour[" + (index + 1) + "]']").val());
        var endTimeMinute = parseInt($(this).find("select[name='end_minute[" + (index + 1) + "]']").val());
        var start_ampm = $(this).find("select[name='start_ampm[" + (index + 1) + "]']").val();
        var end_ampm = $(this).find("select[name='end_ampm[" + (index + 1) + "]']").val();
        var startTimeMinute = startTimeMinute < 10 ? '0' + startTimeMinute : startTimeMinute.toString();
        var endTimeMinute = endTimeMinute < 10 ? '0' + endTimeMinute : endTimeMinute.toString();
        if (!isNaN(startTimeHour) || !isNaN(startTimeMinute) || !isNaN(endTimeHour) || !isNaN(endTimeMinute)) {
            var startTime = {
                hour: startTimeHour,
                minute: startTimeMinute,
                ampm: $(this).find("select[name='start_ampm[" + (index + 1) + "]']").val()
            };
            var endTime = {
                hour: endTimeHour,
                minute: endTimeMinute,
                ampm: $(this).find("select[name='end_ampm[" + (index + 1) + "]']").val()
            };
            formData.push({
                startTime: startTime,
                endTime: endTime
            });
        }
    });
    console.log(formData)
    if (!isValid) {
        return false; // Exit the function if any validation failed
    }
    var result = checkForDuplicatesAndOverlap(formData);
    if (result) {
        jQuery('.error_message_show').text(result);
        setTimeout(function() {
          jQuery('.error_message_show').text('');
        }, 2000);
    } else {
        jQuery('.spinner_border_custom').css('display','block');
        $.ajax({
            type: "post",
            url: ajaxurl,
            data: { action: "edit_event_function", event_name: event_name,current_event_select:current_event_select, formData },
            success: function(response) {
                jQuery('.spinner_border_custom').css('display','none');
                $('#event_entry_modal').modal('hide');
                location.reload();
            },
            error: function(xhr, status) {
                console.log('ajax error = ' + xhr.statusText);
                // alert(response.msg);
            }
        });
    }
    return false;
}
