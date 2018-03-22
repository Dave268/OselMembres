$(document).ready( function() {
    $(".sendFile").click(function (e) {

        e.preventDefault();
        $('#modalFolder').modal("hide");
        $('#modalLoad').modal("show");

        var form = $('#formFile');
        var $href = form.attr('action');

            $.ajax({
                url: $href,
                data: new FormData(form),
                type: "POST",
                contentType:false,
                processData:false,
                cache:false,
                dataType: 'json',
                success: function (obj) {
                    console.log(obj.id);
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