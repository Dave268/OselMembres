
        $.fn.upload = function(remote, successFn, progressFn, formFn, numFilesFn, iconFn) {

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
                        $(iconFn).removeClass("hidden");
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