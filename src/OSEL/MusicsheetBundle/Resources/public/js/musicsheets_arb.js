/**
 * Created by davidgoubau on 03/05/2017.
 */

$(document).ready(function() {
    $(".globalButton").click(function (e) {

        e.preventDefault();
        var $href = Routing.generate('osel_musicsheet_get_composers');
        console.log($href);

        $.get({
            url: $href,
            dataType: 'html',
            success: function (code_html, statut) {
                $("#output_list").html("");
                $("#hierarchie").html("");
                $(code_html).appendTo("#output_list");

                /*var hierarchie = $(jQuery('<span></span>')).html('Compositeurs');
                var lien = $(jQuery('<a></a>')).html(hierarchie);
                var puce = $(jQuery('<li  class="globalButton" href="' + $href + '"></li>')).html(lien);
                puce.appendTo("#hierarchie");*/

                console.log(code_html);
                console.log("Normalement ca a du fonctionner");
            },
            error: function (resultat, statut, erreur) {
                console.log("Erreur ajax");

            },
            complete: function (resultat, statut) {
                console.log("c'est fini");

            }
        });

    });

    $(".composerButton").click(function (e) {

        e.preventDefault();
        var $href = $(this).attr('href');
        var $hrefGlobal = Routing.generate('osel_musicsheet_get_composers');
        console.log($href);

        $.get({
            url: $href,
            dataType: 'html',
            success: function (code_html, statut) {
                $("#output_list").html("");
                $("#hierarchie").html("");


                var hierarchie = $(jQuery('<span></span>')).html('Compositeurs');
                var lien = $(jQuery('<a></a>')).html(hierarchie);
                var puce = $(jQuery('<li  class="globalButton" href="' + $hrefGlobal + '"></li>')).html(lien);
                puce.appendTo("#hierarchie");

                $(code_html).appendTo("#output_list");

                console.log(code_html);
                console.log("Normalement ca a du fonctionner");
            },
            error: function (resultat, statut, erreur) {
                console.log("Erreur ajax");

            },
            complete: function (resultat, statut) {
                console.log("c'est fini");

            }
        });

    });

    $(".musicsheetButton").click(function (e) {
        if (e.altKey) {
            var $href = Routing.generate('osel_musicsheet_modify_complete', {
                'id': $(this).attr('data')
            });

            $('<div></div>').load($href + ' form', function () {
                //set form
                var $form = $(this).children('form');

                //set checkbox
                var $cb = $form.find('input[type="checkbox"]');

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
                        var check = $('#musicsheet-complete-' + obj.id);

                        console.log('réussi');

                        if (obj.actif) {
                            check.attr('class', "state-icon glyphicon  glyphicon-check");
                        }
                        else {
                            check.attr('class', "state-icon glyphicon  glyphicon-unchecked");
                        }
                    },
                    complete: function () {
                        console.log("complete!");
                    },
                    error: function (err) {
                        console.log('error');
                        console.log(err);
                    }
                });

            });

            console.log("On active cette partition");
        }
        else {
            var $href = $(this).attr('href');
            var $musicsheetId = $(this).attr('data-composer');
            var $modify = Routing.generate('osel_musicsheet_modify', {
                'id': $(this).attr('data')
            });
            console.log($modify);

            $.get({
                url: $href,
                dataType: 'html',
                success: function (code_html, statut) {
                    $("#output_list").html("");

                    var hierarchie = $(jQuery('<span></span>')).html('Morceaux');
                    var lien = $(jQuery('<a></a>')).html(hierarchie);
                    var puce = $(jQuery('<li  class="composerButton" href="' + Routing.generate("osel_musicsheet_get_musicsheets" , { "id": $musicsheetId }) + '"></li>')).html(lien);
                    puce.appendTo("#hierarchie");

                    $(code_html).appendTo("#output_list");

                    console.log(code_html);
                    console.log("Normalement ca a du fonctionner");
                },
                error: function (resultat, statut, erreur) {
                    console.log("Erreur ajax");

                },
                complete: function (resultat, statut) {
                    console.log("c'est fini");

                }
            });

            $.get({
                url: $modify,
                dataType: 'html',
                success: function (code_html, statut) {
                    $("#output_form").html("");

                    $(code_html).appendTo("#output_form");

                    console.log(code_html);
                    console.log("Normalement on peut modifier la partition");
                },
                error: function (resultat, statut, erreur) {
                    console.log("Erreur ajax");
                    console.log(resultat);
                    console.log(statut);
                    console.log(erreur);

                },
                complete: function (resultat, statut) {
                    console.log("c'est fini");

                }
            });


        }
    });

    $(".partButton").click(function (e) {

        e.preventDefault();
        var $href = $(this).attr('href');

        window.location.href = $href;
        console.log($href);


    });
});