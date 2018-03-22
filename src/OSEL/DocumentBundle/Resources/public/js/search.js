$("#search").keyup(function(){

    var data = $(this).val();

    if(data == '')
    {
        var path = Routing.generate('osel_documents_search', {
            'text': '%%%',
            'id'  : $(this).attr('data-id')
        });
    }
    else
    {
        var path = Routing.generate('osel_documents_search', {
            'text': data,
            'id'  : $(this).attr('data-id')
        });
    }


        $.ajax({
            url: path,
            type: "POST",
            dataType: "json",
            success: function (obj) {
                var container = $("#fileList");
                container.html('');
                $.each(obj, function(index,object){


                    var fileicon = object.icon;

                    if(object.isdir === "folder")
                    {
                        var download = Routing.generate('osel_documents_download_dir', {'id': object.id});
                        var modify = Routing.generate('osel_documents_add_dir', {'id': object.id});
                        var remove = Routing.generate('osel_documents_delete_dir', {'id': object.id});
                        var activate = Routing.generate('osel_documents_activate_dir', {'id': object.id})
                        var indexRoute = Routing.generate('osel_documents_index', {
                            'idDir': object.id
                        });
                    }
                    else
                    {
                        var download = Routing.generate('osel_documents_download_file', {'id': object.id});
                        var modify = Routing.generate('osel_documents_modify_file', {'id': object.id});
                        var remove = Routing.generate('osel_documents_delete_file', {'id': object.id});
                        var activate = Routing.generate('osel_documents_activate_file', {'id': object.id});
                        var indexRoute = Routing.generate('osel_documents_view_file', {
                            'id': object.id
                        });
                    }

                    if(object.enabled)
                    {
                        var icon = 'fa fa-unlock';
                    }
                    else
                    {
                        var icon = 'fa fa-lock';
                    }

                    var list = $("<li class=\"thread\"></li>");
                    list.html("<span class=\"time\" style=\"padding-right:0px;padding-left:0px;\">" +
                        "<i class=\"" + fileicon + "\" style=\"font-size:20px;color:rgb(84,132,254);\"></i></span><span class=\"title\"><a href=\"" + indexRoute +  "\" style=\"color:#666666;\">" + object.originalName + "</a></span>\n" +
                        "<span class=\"icon\">\n" +
                        "<a href=\"" + download + "\" class=\"flag\"><i class=\"fa fa-download\"></i></a>\n" +
                        "<a href=\"" + modify + "\" class=\"flag\"><i class=\"glyphicon glyphicon-pencil\"></i></a>\n" +
                        "<a data-href=\"" + remove + "\" data-toggle=\"modal\" data-target=\"#confirm-delete\" class=\"flag\"><i class=\"glyphicon glyphicon-trash\"></i></a>\n" +
                        "<a href=\"" + activate + "\" class=\"flag activate\"><i class=\"" + icon + "\"></i></a></span>\n" +
                        "<span style=\"float:right;color: rgba(0,0,0,0.4);margin-right: 50px; font-size: 1.1em;\"><em> - " + object.dateAdd + " - </em></span>\n" +
                        "<span style=\"float:right;color: rgba(0,0,0,0.4);margin-right: 50px; font-size: 1.1em;\"><em> - " + object.role + " - </em></span>");

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