// JavaScript Document
$(document).ready(function(e) {
	$(".gm-progress").fadeOut(1000);
	
	
	
	
	
	
	function validation(regex, string){
		var validate = string.match(regex);
		if(validate == null){
			return false;	
		}else{
			return true;	
		}
	}
	
	
	function notif(state, msg){		
		$(".notif").removeClass("error");
		$(".notif").removeClass("sucess");
		$(".notif").addClass(state).html(msg).show();
	}
	
	
	function locate_error(selector){
		$(selector).parent("div").addClass("has-error");	
	}
	
	

  // Or with jQuery

    $('.collapsible').collapsible();

	$(".button-collapse").click(function(){
		$("#slide-out").show().removeClass("bounceOutLeft").addClass("animated bounceInLeft");
		$(".slide-out-wrapper").show().css("z-index", 1000).removeClass("fadeOut").addClass("animated fadeIn");
	});
	
	$(".slide-out-wrapper").click(function(){
		$("#slide-out").removeClass("bounceInLeft").addClass("animated bounceOutLeft");
		$(".slide-out-wrapper").css("z-index", 0).removeClass("fadeIn").addClass("animated fadeOut");
	});


	$(".datatable").DataTable();
	
	$(".datepicker").datepicker({
		changeMonth: true,
      	changeYear: true
	});
	$(".release-datepicker").datepicker({
		changeMonth: true,
      	changeYear: true,
		minDate: 0
	});
	$(".collection-datepicker").datepicker({
		changeMonth: true,
      	changeYear: true,
		maxDate: 0
	});
	
	$(".collection-datepicker2").datepicker({
		changeMonth: true,
      	changeYear: true,
	});
	
	
	$("#contact-us-form").submit(function(e){
		e.preventDefault();
		var process_to = $(this).attr("action") + ".php";
		var formData = new FormData;
		$(".form-control", this).each(function(index, element) {
            formData.append($(this).attr("name"), $(this).val());
        });
		
		$.ajax({
			contentType:false,
			processData:false,
			url:process_to,
			type:"POST",
			data:formData,
			success: function(data){
				if(data.match('Error')){
					notif("error", data);
				}else{
					notif("success", data);
					$("form").trigger("reset");
				}
			}
			
		});
			
	});
	
	
	$("#upload_book_form").submit(function(e){
		e.preventDefault();
		var process_to = $(this).attr("action") + ".php";
		var file = $("input[type=file]", this)[0].files[0];
		var formData = new FormData();
		formData.append("full_doc", file);
		
		
		var other_data = $(this).serializeArray();
		$.each(other_data,function(key,input){
			formData.append(input.name,input.value);
		});
		$.ajax({url:process_to, data:formData, type:"POST", contentType:false, processData:false,
		success: function(a){
			if(a==""){
				$("#upload_book_form").trigger("reset");
				notif("success", "File has beed uploaded successfuly and added to the library.");
			}else{
				notif("error", a);	
			}
		},
		xhr: function(){
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt){
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					
				
					$("button[type=submit]").attr("disabled", true);
					if (percentComplete === 1) {
						$('.progress').css({
							width: 0 + '%'
						});
						$("button[type=submit]").removeAttr("disabled");
					}
				}
			}, false);
			return xhr;	
		}
		
		});
	});
	
	
	
	$("label").click(function(){
		$("input[name=" + $(this).attr("for")	+ "]").focus();
	});
	

	$('.modal').on('shown.bs.modal', function() {
	  $(this).find('[autofocus]').focus();
	});

	
	
	
	
	
	
	
	
	
	
	

});