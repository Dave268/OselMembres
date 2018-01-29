/**
 * Created by davidgoubau on 04/05/2017.
 */
function elementClicked() {
    var divHtml = $(this).html();
    var editableText = $("<input />");
    editableText.val(divHtml);
    editableText.attr('data-id', $(this).attr('data-id'));
    editableText.attr('data-val', divHtml);
    editableText.width('30px');
    $(this).replaceWith(editableText);
    editableText.focus();
    editableText.blur(editableTextBlurred);
}

function editableTextBlurred() {
    $('#modalLoad').modal("show");
    if($(this).val() == '')
    {
        var html = '0';
    }
    else
    {
        var html = $(this).val();
    }

    var viewableText = $("<span>");
    viewableText.html(html);

    var $href = Routing.generate('osel_event_inscription_prix', {
        'id': $(this).attr('data-id')
    });
    console.log($href);

    if($(this).val() != $(this).attr('data-val'))
    {
        $('<div></div>').load($href + ' form', function () {
            //set form
            var $form = $(this).children('form');

            //set checkbox
            var $cb = $form.find("input[type=\"text\"]");

            //toggle
            $cb.val(html);


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
                    viewableText.attr('data-id', obj.id);
                    $('#prixTotal').html(obj.prix);
                    $('#payeTotal').html(obj.paye);
                    console.log('réussi');
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
    }
    else
    {
        viewableText.attr('data-id', $(this).attr('data-id'));
    }


    $(this).replaceWith(viewableText);
    // setup the click event for this new div
    viewableText.click(elementClicked);
}


$(document).ready(function() {

        //on change un text en textarea pour pouvoir le modifier
        $('.textToModify').click(elementClicked);




    $(".inscription_activate").click(function (e) {

        e.preventDefault();
        $('#modalLoad').modal("show");
        var $href = $(this).attr('href');
        var $id = $(this).attr('obj');

        $('<div></div>').load($href + ' form', function () {
            //set form
            var $form = $(this).children('form');

            //set checkbox
            var $cb = $form.find('input[type="checkbox"]');

            var $precheck = $('#inscription_' + $id + ' i');
            if($cb.prop("checked"))
            {
                $precheck.attr('class', "state-icon glyphicon  glyphicon-unchecked");
                console.log("On  décheck");
                var control = true;
            }
            else
            {
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
                    var check = $('#inscription_' + obj.id + ' i');
                    $('#payeTotal').html(obj.paye);
                    console.log(obj.paye);

                    console.log('réussi');

                    if (obj.actif && obj.actif != control) {
                        check.attr('class', "state-icon glyphicon  glyphicon-check");
                    }
                    else if(!obj.actif && obj.actif != control)
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
