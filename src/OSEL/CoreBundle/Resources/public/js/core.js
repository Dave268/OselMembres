/**
 * Created by davidgoubau on 03/05/2017.
 */
$(document).ready( function() {
    $('#messages').delay(2000).fadeOut();
	
	$body = $("body");
	$(document).on({
		ajaxStart: function() { $body.addClass("loading");    },
		ajaxStop: function() { $body.removeClass("loading"); }    
	});
	
	var modal = document.getElementById('successModal');
	var span = document.getElementsByClassName("closeModal")[0];
	if(modal != null)
	{
		modal.style.display="block";
		span.onclick = function() {
		modal.style.display = "none";
		}	
	}
	
	window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display="none";
    }
	}
});
