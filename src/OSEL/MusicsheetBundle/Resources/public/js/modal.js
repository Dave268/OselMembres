
$(document).ready(function() {

// / Get the modal
    var modalComposer = document.getElementById('formComposer');
    var modalType = document.getElementById('formType');
    var modalInstrument = document.getElementById('formInstrument');

// Get the image and insert it inside the modal - use its "alt" text as a caption
    var formTypeButton = document.getElementById('formTypeLaunch');
    var formInstrumentButton = document.getElementById('formInstrumentLaunch');

    $('#formComposerLaunch').click(function () {
        modalComposer.style.display = "block";
        modalFormComposer.src = this.src;
    });
    $('#formTypeLaunch').click(function () {
        modalType.style.display = "block";
        modalFormComposer.src = this.src;
    });
    $('#formInstrumentLaunch').click(function () {
        modalInstrument.style.display = "block";
    });

// Get the <span> element that closes the modal
//var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
    $('.close').click(function () {
        modalComposer.style.display = "none";
        modalType.style.display = "none";
        modalInstrument.style.display = "none";
    });


//petit script qui lie le bouton file avec l'input file
    $("#formFileLaunch").click(function () {
        $("#musicsheet_uploadedFiles").click();
    });

    $("input[id=musicsheet_uploadedFiles]").change(function () {
        console.log('Check 1');
        document.getElementById("filesToUploadBlock").style.display = "block";

        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            var fileList = jQuery('#output_files');

            var newWidget = fileList.attr('data-prototype');
            var $data = $('#countParts').attr('data');

            newWidget = newWidget.replace(/__name__/g, parseInt($data) + i );

            var newPartsForm = jQuery('<div class="form-group"></div>').html(newWidget);
            var newPartsTitle = jQuery('<span>' + $(this).get(0).files[i].name + '</span>');
            var container = jQuery('<li class="list-group-item"></li>');


            newPartsTitle.appendTo(container);
            newPartsForm.appendTo(container);
            container.appendTo(fileList);
        }
    });


});