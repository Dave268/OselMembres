
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
                        var url = Routing.generate('osel_score_delete_part', {
                            'id': obj.id
                        });

                        var columnthree = "<div class=\"col-md-2\"><button class=\"btn btn-danger btn-xs visible-xs-inline visible-sm-inline visible-md-inline visible-lg-inline\" type=\"button\" style=\"height:30px;padding-top:0px;padding-bottom:0px;font-size:10px;\"  data-href = \"" + url + "\">Supprimer </button></div>";
                        $(deleteFN).append(columnthree);
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