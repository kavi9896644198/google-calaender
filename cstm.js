jQuery(document).ready(function($){
	jQuery( "#edit_profile_frm" ).validate({
	  rules: {
		first_name: {
		  required: true
		},
		scisco_cmb2_gender: {
		  required: true
		},
		scisco_cmb2_age: {
		  required: true
		},
		scisco_cmb2_user_email: {
		  required: true
		},
		scisco_cmb2_user_state: {
		  required: true
		},
		scisco_cmb2_user_country: {
		  required: true
		},
		scisco_cmb2_company_type: {
		  required: true
		},
		scisco_cmb2_company_stage: {
		  required: true
		},
		scisco_cmb2_experience_level: {
		  required: true
		},
		scisco_cmb2_tag_line: {
		  maxlength: 50
		},
		scisco_cmb2_user_consumption_method: {
		  required: true
		},
		scisco_cmb2_consumption_experience_level: {
		  required: true
		},
		scisco_cmb2_user_investors: {
		  required: true
		},
	  }

	});
     $('#submit-btn-description').on('click', function(){
        var description = $('#user-description').val();
        var scisco_user_id = $('#scisco_user_id').val();
        if(description !=''){
        	var data = {};
            data.description = description;
			data.scisco_user_id = scisco_user_id;
        	jQuery.ajax({
				type : "post",
				url : ajaxurl,
				data : {action: "custom_description_add_User",
				data},
				success: function(response) {
					var result = JSON.parse(response);
					if(result.message == 'success'){
						$('#description-result').html(result.html);
					}
				}
			});
        }else{
        	alert('Please add description');
        }
        
    });

});
var $=jQuery;
var ajaxurl;

function d_loader(PereentDiv=false){
     if(PereentDiv){
        loader=PereentDiv;
     }else{
        loader='body'; 
     }
     jQuery(loader).after().append('<div class="ssloading">Loading&#8230;</div>');
}

function r_loader(PereentDiv=false){
     if(PereentDiv){
        loader=PereentDiv;
     }else{
        loader='body'; 
     }
     jQuery(loader).find('.ssloading').remove();
}

 
 
 $(function(){
    $("#edit_profile_frm").validate();    
    $("#edit_profile_frm").on('submit', function(e) {
        var isvalid = $("#edit_profile_frm").valid();
        if (isvalid=== true) {
            e.preventDefault();
            
    var myForm = document.getElementById('edit_profile_frm');
	 var formData = new FormData(myForm);
	 formData.append('action', 'update_user_register_data'); 
     
	
	
$.ajax({
	 type : "POST",
	 url :ajaxurl,
	 contentType: false,
	 processData: false,
	 dataType: 'json',
	 data : formData,
	  beforeSend: function(){
		 //$('.loading-icons.result-response').css('display','flex');
		 d_loader();
	},
	 success: function(response) {
		 	var string1 = JSON.stringify(response);
			var parsed = JSON.parse(string1);
			//console.log(parsed.mesg);
			//console.log(parsed.status);
			if(parsed.status =='success'){
				$('.message-response-register').html('<span class="green">'+parsed.mesg+'</span>');
				r_loader();			 
				var body = $("html, body");
				body.stop().animate({scrollTop:0}, 500,);
			}
			else {
				$('.message-response-register').html('<span class="alert alert-danger">'+parsed.mesg+'</span>');
				r_loader();
				var body = $("html, body");
				body.stop().animate({scrollTop:0}, 500,);
				indow.setTimeout(function(){location.reload()},2000)
			}
		}
}); 

  }
    });
});

  $("#scisco_cmb2_user_stage").on("click", function(){
            $("#scisco_cmb2_user_stage option:selected").each(function(){
                //alert($(this).val());
            });
        });


function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

