$("#searchComposer").keyup(function(){

    var data = $(this).val();


        if(data == '')
        {
            var path = Routing.generate('osel_score_search', {
                'text': '%%%',
                'letter': $(this).attr("data-letter")
            });
        }
        else
        {
            var path = Routing.generate('osel_score_search', {
                'text': data,
                'letter': $(this).attr("data-letter")
            });
        }

        $.ajax({
            url: path,
            type: "POST",
            dataType: "json",
            success: function (obj) {
                var container = $("#composerList");
                container.html('');
                $.each(obj, function(index,object){
                    //console.log(object.id + " " + object.composer);
                    if(object.type == "composer")
                    {
                        var gestion = Routing.generate('osel_score_gestion', {
                            'letter': data.charAt(0),
                            'idComposer': object.id
                        });
                        var modify = Routing.generate('osel_score_add_composer', {
                            'id': object.id
                        });
                        var view = Routing.generate('osel_score_view_composer', {
                            'id': object.id
                        });
                    }
                    else if(object.type == "score")
                    {
                        var gestion = Routing.generate('osel_score_gestion', {
                            'letter': data.charAt(0),
                            'idComposer': object.id,
                            'idScore': object.id
                        });
                        var modify = Routing.generate('osel_score_create_score', {
                            'id': object.id
                        });
                        var remove = Routing.generate('osel_score_delete_score', {
                            'id': object.id
                        });
                        var activate = Routing.generate('osel_score_activate_score', {
                            'id': object.id
                        });
                    }

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
                    if(object.type == "composer")
                    {
                        list.html("<span class=\"time\">" + object.scores + " pcs</span><span class=\"title\"><a href=\"" + gestion + "\" style=\"color:#666666;\">" + object.composer + "</a></span><span class=\"icon\"> <a href=\"" + view + "\" class=\"flag\"><i class=\"glyphicon glyphicon-eye-open\"></i></a><a href=\"javascript:void(0)\" class=\"subscribe\"><i class=\"glyphicon " + check + "\"></i></a><a href=\"" + modify + "\" class=\"flag\"><i class=\"glyphicon glyphicon-pencil\"></i></a></span>");
                    }
                    if(object.type == "score")
                    {
                        list.html("<span class=\"time\">" + object.scores + " pcs</span><span class=\"title\"><a href=\"" + gestion + "\" style=\"color:#666666;\">" + object.composer + "</a></span><span class=\"icon\"> <a href=\"" + activate + "\" class=\"subscribe activate\"><i class=\"glyphicon " + check + "\"></i></a><a href=\"" + modify + "\" class=\"flag\"><i class=\"glyphicon glyphicon-pencil\"></i></a><a data-href=\"" + remove + "\" data-toggle=\"modal\" data-target=\"#confirm-delete\" class=\"flag\"><i class=\"glyphicon glyphicon-trash\"></i></a></span>");
                    }

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





});