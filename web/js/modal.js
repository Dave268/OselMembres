$(document).ready( function() {	
	$(document).on({
		ajaxStart: function() { $('#modalLoad').modal("show");   },
		ajaxStop:  function() { $('#modalLoad').modal("hide")}    
	}); 
	
	
    if($('#modalError')){
		$('#modalError').modal("show");	
	}
    if($('#modalDone')){
		$('#modalDone').modal("show");	
	}
	
    
    $('.close').onclick = function() {
		$('.modal').modal("hide");
	}
    $('.close').onclick = function() {
		$('.modal').modal("hide");
	}
	window.onclick = function(event) {
    if (event.target == $(".modal")) {
        $(".modal").modal("hide");
    }
	}
    
    
    $('#confirm-delete').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

        $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
    });
    $('#confirm-send').on('show.bs.modal', function (e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

        $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
    });
});