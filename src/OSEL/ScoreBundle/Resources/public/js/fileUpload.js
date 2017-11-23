function itemAdd(id, item) {

    var url = Routing.generate('osel_score_delete_part', {
        'id': id
    });

    var idFile = $(item).attr("id");
    var columnthree = jQuery("<div class=\"col-md-2\"></div>");
    var deleteButton = $("<button></button>");
    deleteButton.html("Supprimer");
    deleteButton.attr("class", "btn btn-danger visible-xs-inline visible-sm-inline visible-md-inline visible-lg-inline");
    deleteButton.attr("data-href", url);
    deleteButton.attr("data-toggle", "modal");
    deleteButton.attr("type", "button");
    deleteButton.attr("data-target", "#confirm-delete");
    deleteButton.attr("id", "deletebutton" + id);
    columnthree.append(deleteButton);
    $(item).append(columnthree);


    /*$("#deletebutton" + id).on("click", function(e){

        e.preventDefault();
        var $href = $(this).attr('data-href');
        var target = $(this).attr('data-target').replace("file", "item");
        $("#" + target).remove();

        console.log($href);

        $.ajax({
            url: $href,
            method: 'post',
            dataType: 'json',
            success: function (obj) {
                $("#item" + obj.id).remove();
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
    */
}

function modifyName() {
    var container = $(this);
    var data = $(this).val();
    var $href = container.attr("data-href");

    if(data !== container.attr("data-init")){
        $('<div></div>').load($href + ' form', function () {
            //set form
            var $form = $(this).children('form');

            //set checkbox
            var $cb = $form.find("input[type=\"text\"]");

            //toggle
            $cb.val(data);


            // form action
            var $url = $href;


            //set data
            var $data = $form.serialize();

            $.ajax({
                url: $url,
                data: $data,
                method: 'post',
                xhr: function () {
                    $('#modalLoad').modal("show");
                },
                dataType: 'json',
                cache: false,
                success: function (obj) {
                },
                complete: function () {

                },
                error: function (err) {

                }
            });
        });
    }
}


$.fn.upload = function(remote, successFn, progressFn, formFn, numFilesFn, iconFn, deleteFN) {

    var def = new $.Deferred();
    if (numFilesFn) {
        $.ajax({
            url: remote,
            type: "POST",
            xhr: function() {
                myXhr = $.ajaxSettings.xhr();
                if(myXhr.upload && progressFn){
                    myXhr.upload.addEventListener("progress", function(prog) {
                        var value = ~~((prog.loaded / prog.total) * 100);

                        // if we passed a progress function
                        if (typeof progressFn === "function") {
                            progressFn(prog, value);

                            // if we passed a progress element
                        } else if (progressFn) {
                            $(progressFn).val(value);
                        }
                    }, false);
                }
                return myXhr;
            },
            data: formFn,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (obj) {
                $(progressFn).hide();
                $(iconFn).removeClass("hidden")
                console.log(obj.id);
                itemAdd(obj.id, deleteFN);


            },
            complete: function(res) {
                var json;
                try {
                    json = JSON.parse(res.responseText);
                } catch(e) {
                    json = res.responseText;
                }
                if (typeof successFn === "function") successFn(json);
                def.resolve(json);
            }
        });
    } else {
        def.reject();
    }

    return def.promise();
};