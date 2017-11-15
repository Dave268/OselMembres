/**
 * Created by davidgoubau on 04/05/2017.
 */
$(document).ready(function() {

    $(".selectMembres").change(function()
    {
        var $href = $(this).children(":selected").attr('value');

        window.location.href = $href;
    });

    $(".user_activate").click(function (e) {

        e.preventDefault();
        var $href = $(this).attr('href');
        var $id = $(this).attr('obj');

        $('<div></div>').load($href + ' form', function () {
            //set form
            var $form = $(this).children('form');

            //set checkbox
            var $cb = $form.find('input[type="checkbox"]');
			
			var $precheck = $('#user_actif_' + $id + ' i');

			if($cb.prop("checked"))
			{
				//$precheck.attr('class', "state-icon glyphicon  glyphicon-unchecked");
				//console.log("On  décheck");
				var control = true;
			}
			else
			{
				//$precheck.attr('class', "state-icon glyphicon  glyphicon-check");
				//console.log("On  check");
				var control = false;
			}
				

            //toggle
            $cb.prop('checked', !$cb.prop('checked'));


            // form action
            var $url = $href;


            //set data
            var $data = $form.serialize();

            $.ajax({
                url: $url,
                data: $data,
                method: 'post',
                xhr: function() { $('#modalLoad').modal("show");},
                dataType: 'json',
                cache: false,
                success: function (obj) {
                    var check = $('#user_actif_' + obj.id + ' i');

                    console.log('réussi');

                    if (obj.enabled && obj.enabled != control) {
                        check.attr('class', "state-icon glyphicon  glyphicon-check");
                    }
					else if(!obj.enabled && obj.enabled != control)
                    {
                        check.attr('class', "state-icon glyphicon  glyphicon-unchecked");
                    }
					
                },
                complete: function () {
                    console.log("complete!");
                    $('#modalLoad').modal("hide");
                },
                error: function (err) {
                    console.log('error');
                    console.log(err);
                }
            });

        });
    });
});
