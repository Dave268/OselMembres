$(document).ready(function() {
    $(".event_activate").click(function (e) {

        e.preventDefault();
        $('#modalLoad').modal("show");
        var $href = $(this).attr('href');
        console.log($href);
        var $id = $(this).attr('obj');

        $('<div></div>').load($href + ' form', function () {
            //set form
            var $form = $(this).children('form');

            //set checkbox
            var $cb = $form.find('input[type="checkbox"]');

            var $precheck = $('#inscription_' + $id + ' i');
            if ($cb.prop("checked")) {
                $precheck.attr('class', "state-icon glyphicon  glyphicon-unchecked");
                console.log("On  décheck");
                var control = true;
            }
            else {
                $precheck.attr('class', "state-icon glyphicon  glyphicon-check");
                console.log("On  check");
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
                dataType: 'json',
                cache: false,
                success: function (obj) {
                    var check = $('#event_' + obj.id + ' i');
                    $('#payeTotal').html(obj.paye);

                    console.log('réussi');

                    if (obj.actif && obj.actif != control) {
                        $('.event_activate i').attr('class', "state-icon glyphicon glyphicon-unchecked");
                        check.attr('class', "state-icon glyphicon  glyphicon-check");
                    }
                    else if (!obj.actif && obj.actif != control) {
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