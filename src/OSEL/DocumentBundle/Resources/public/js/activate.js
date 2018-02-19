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
                $icon.attr('class', "fa fa-lock");
                console.log("On  décheck");
                control = true;
            }
            else {
                $icon.attr('class', "fa fa-unlock");
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
                    var temp = $("#" + obj.data + obj.id);
                    console.log(obj.data + obj.id);
                    if(obj.enabled)
                    {
                        //temp.addClass("newDir");
                        temp.click(function (e) {
                            e.preventDefault();
                            getForm($(this));
                        });
                    }
                    else
                    {
                        //temp.removeClass("newDir");
                        temp.unbind( "click" );
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