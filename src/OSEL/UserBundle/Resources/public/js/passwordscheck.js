$(document).ready(function()
{
	$('#form_Envoyer').attr('disabled', 'disabled');

	$('#form_pwd1').keyup(function()
	{
		$('#result').html(checkStrength($('#form_pwd1').val()))
	});

	$('#form_pwd2').keyup(function()
	{
		$('#result').html(checkIfEqual($('#form_pwd1').val(), $('#form_pwd2').val()));
	});
	
	function checkIfEqual(passwordOne, passwordTwo)
	{
		if(passwordOne == passwordTwo && $('#form_pwd1').val().length > 5)
		{
			$('#form_pwd2').css({
				'border-color': 'green',
				'box-shadow':'0px 0px 6px green'
				});
			$('#form_Envoyer').removeAttr('disabled');
		}
		else
		{
			$('#form_pwd2').css({
				'border-color': 'red',
				'box-shadow':'0px 0px 6px red'
				});

				$('#form_Envoyer').attr('disabled','disabled');

		}
	}
		
	function checkStrength(password)
	{
		var strength = 0
		
		if (password.length < 6) { 
			$('#result').removeClass();
			$('#result').addClass('short');
			$('#form_pwd1').css({
				'border-color': 'red',
				'box-shadow':'0px 0px 6px red'
				});
			return 'Too short' ;
		}
		else{
			$('#form_pwd1').css({
				'border-color': 'orange',
				'box-shadow':'0px 0px 6px orange'
				});
		}
		
		if (password.length > 7)
		{
			strength += 1;
			
		}
		
		//If password contains both lower and uppercase characters, increase strength value.
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;
		
		//If it has numbers and characters, increase strength value
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;
		
		//If it has one special character, increase strength value.
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;
		
		//if it has two special characters, increase strength value.
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
		
		//if very long
		if (password.length > 10)
		{
			strength += 1;	
		}
		
		
		//Calculated strength value, we can return messages
		console.log(strength);
		
		
		
		//If value is less than 2
		
		if (strength < 2 )
		{
			$('#result').removeClass();
			$('#result').addClass('weak');
		
			return 'Weak'			
		}
		else if (strength == 2 )
		{
			$('#result').removeClass();
			$('#result').addClass('good');
		
			return 'Good';		
		}
		else if (strength >= 3 && strength < 6)
		{
			$('#result').removeClass()
			$('#result').addClass('strong')
			$('#form_pwd1').css({
				'border-color': 'green',
				'box-shadow':'0px 0px 6px green'
				});
			return 'Strong'
		}
		else if (strength > 5 )
		{
			$('#result').removeClass()
			$('#result').addClass('verystrong')
			$('#form_pwd1').css({
				'border-color': 'green',
				'box-shadow':'0px 0px 6px green'
				});
			return 'Very Strong'
		}
	}
});