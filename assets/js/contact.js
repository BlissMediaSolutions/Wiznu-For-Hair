(function($){
	$(document).ready(function(){

    if($("#contact-form").length>0) {
			$("#contact-form").validate({
				submitHandler: function(form) {
					$('.submit-button').button("loading");
					$.ajax({
						type: "POST",
						url: "assets/php/contact.php",
						data: {
							"name": $("#contact-form #name").val(),
							"email": $("#contact-form #email").val(),
							"phone": $("#contact-form #phone").val(),
							//"subject": $("#contact-form #subject").val(),
							"message": $("#contact-form #message").val(),
							"g-recaptcha-response": $("#g-recaptcha-response").val()
						},
						dataType: "json",
						success: function (data) {
							if (data.sent == "yes") {
								$("#MessageSent").removeClass("d-none");
								$("#MessageNotSent").addClass("d-none");
								$(".submit-button").removeClass("btn-default").addClass("btn-success").prop('value', 'Message Sent');
								$("#contact-form-with-recaptcha .form-control").each(function() {
									$(this).prop('value', '').parent().removeClass("has-success").removeClass("has-error");
								});
							} else {
								$("#MessageNotSent").removeClass("d-none");
								$("#MessageSent").addClass("d-none");
							}
						}
					});
				},
				errorPlacement: function(error, element) {
					error.insertBefore( element );
				},
				onkeyup: false,
				onclick: false,
				rules: {
					name: {
						required: true,
						minlength: 2
					},
					email: {
						required: true,
						email: true
					},
					phone: {
						required: true,
						maxlength: 12
					},
					/*subject: {
						required: true
					}, */
					message: {
						required: true,
						minlength: 10
					}
				},
				messages: {
					name: {
						required: "Please enter your name",
						minlength: "Your name must be longer than 2 characters"
					},
					email: {
						required: "Please enter your email address",
						email: "Please enter a valid email address e.g. name@domain.com"
					},
					phone: {
						required: "Please enter your phone number",
						maxlength: "Please enter a valid phone number"
					},
					/* subject: {
						required: "Please enter a subject"
					}, */
					message: {
						required: "Please enter a message",
						minlength: "Your message must be longer than 10 characters"
					}
				},
				errorElement: "span",
				highlight: function (element) {
					$(element).parent().removeClass("has-success").addClass("has-error");
					$(element).siblings("label").addClass("hide");
				},
				success: function (element) {
					$(element).parent().removeClass("has-error").addClass("has-success");
					$(element).siblings("label").removeClass("hide");
				}
			});
		};
  });
}) (this.jQuery);