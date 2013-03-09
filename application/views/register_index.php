<?php $this->load->view("template_header"); ?>

<?php 
	echo form_open('register/register', array(
		'class' => 'form-register',
		'id' => 'registration_form'
	));
?>

<h2>Create Account</h2>

<?php
	//echo form_input('email_address',set_value('email_address'),'id="email_address"');
	echo form_input(array(
					'id' => 'email_address',
					'name' => 'email_address',
					'type' => 'text',
					'class' => 'input-block-level input-large',
					'placeholder' => 'email@example.com',
					'value' => set_value('email_address')
				));

	//echo form_password('password_1','','id="password_1"');
	echo form_password(array(
					'id' => 'password_1',
					'name' => 'password_1',
					'class' => 'input-block-level input-large',
					'placeholder' => 'Password'
				));

	//echo form_password('password_2','','id="password_2"');
	echo form_password(array(
					'id' => 'password_2',
					'name' => 'password_2',
					'class' => 'input-block-level input-large',
					'placeholder' => 'Confirm Password'
				));

	/* $data = array(
		'id' => 'btn',
		'class' => 'btn',
		'name' => 'accounts',
		);

	 echo form_submit($data,'Create Senior Project Account');
	*/
	echo form_submit(array(
		'id' => 'btn',
		'name' => 'accounts',
		'type' => 'Submit',
		'class' => 'btn btn-large btn-primary',
		'value' => 'Create Senior Project Account'
	));

echo form_close() ?>

<div class="errors">
<?php echo validation_errors(); ?>
<?php

	if(isset($already_registered))
	{
		 echo "This email address is already registered"; 	
	}
?>
</div>

<script type="text/javascript">

	$( document ).ready(function() {

	  	$("#btn").on("click", function(e) {
	  		var 
	  			fields = {
	  				email: $.trim($("#email_address").val()),
	  				password1: $.trim($('#password_1').val()),
	  				password2: $.trim($('#password_2').val())
	  			};
			if( !fields.email.length ){
				e.preventDefault();
				$(".errors").html("Email address required");
			}else if( !fields.password1.length && !fields.password2.length ){
				e.preventDefault();
				$(".errors").html("Password fields required");
			}else if( fields.password1 !== fields.password2){
				e.preventDefault();
				$(".errors").html("Passwords did not match.");
			}
	  	});
	});

 </script> 

 <?php $this->load->view("template_footer"); ?>
