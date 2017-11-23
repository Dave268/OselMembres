$("#searchComposer").keyup(function(){

    var data = $(this).val();
    if(data !== '')
    {
        var path = Routing.generate('osel_score_search', {
            'text': data
        });

        $.ajax({
            url: path,
            type: "POST",
            dataType: "json",
            success: function (obj) {
                var container = $("#composerList");
                container.html('');
                $.each(obj, function(index,object){
                    console.log(object.id + " " + object.composer);
                    var gestion = Routing.generate('osel_score_gestion', {
                        'letter': data,
                        'idComposer': object.id
                    });
                    var modify = Routing.generate('osel_score_add_composer', {
                        'id': object.id
                    });
                    var check = null;
                    if(object.actif)
                    {
                        check = 'glyphicon-check';
                    }
                    else
                    {
                        check = 'glyphicon-unchecked';
                    }
                    var list = $("<li class=\"thread\"></li>");
                    list.html("<span class=\"time\">" + object.scores + " pcs</span><span class=\"title\"><a href=\"" + gestion + "\" style=\"color:#666666;\">" + object.composer + "</a></span><span class=\"icon\"> <a href=\"javascript:void(0)\" class=\"subscribe\"><i class=\"glyphicon " + check + "\"></i></a><a href=\"" + modify + "\" class=\"flag\"><i class=\"glyphicon glyphicon-pencil\"></i></a></span>");

                    list.appendTo(container);
                });
            },
            complete: function(res) {
            },
            error: function (err) {
                console.log('error');
                console.log(err);
            }
        });
    }




});