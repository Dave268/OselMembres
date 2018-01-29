
$(document).ready( function() {
    $(".activate").click(function (e) {

        e.preventDefault();
        $('#modalLoad').modal("show");
        var $object = $(this);

        var $icon = $(this).find('i');
        console.log($icon);
        var $href = $object.attr('href');

        $('<div></div>').load($href + ' form', function () {
            //set form
            var $form = $(this).children('form');


            //set checkbox
            var $cb = $form.find('input[type="checkbox"]');

            var control = false;
            if ($cb.prop("checked")) {
                $icon.attr('class', "glyphicon glyphicon-unchecked");
                console.log("On  décheck");
                control = true;
            }
            else {
                $icon.attr('class', "glyphicon glyphicon-check");
                console.log("On  check");
                control = false;
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
                type: "POST",
                dataType: 'json',
                success: function (obj) {
                    console.log('réussi' + obj.id);
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