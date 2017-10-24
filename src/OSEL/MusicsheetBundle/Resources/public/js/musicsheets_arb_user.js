/**
 * Created by davidgoubau on 03/05/2017.
 */

$(document).ready(function() {
    $(".globalButton").click(function (e) {

        e.preventDefault();
        var $href = Routing.generate('osel_musicsheet_get_composers_user');
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
        var $hrefGlobal = Routing.generate('osel_musicsheet_get_composers_user');
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
            var $href = $(this).attr('href');
            var $musicsheetId = $(this).attr('data-composer');

            $.get({
                url: $href,
                dataType: 'html',
                success: function (code_html, statut) {
                    $("#output_list").html("");

                    var hierarchie = $(jQuery('<span></span>')).html('Morceaux');
                    var lien = $(jQuery('<a></a>')).html(hierarchie);
                    var puce = $(jQuery('<li  class="composerButton" href="' + Routing.generate("osel_musicsheet_get_musicsheets_user" , { "id": $musicsheetId }) + '"></li>')).html(lien);
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

    $(".partButton").click(function (e) {

        e.preventDefault();
        var $href = $(this).attr('href');

        window.location.href = $href;
        console.log($href);


    });
});