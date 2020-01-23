var dropzoneAction = "";

$(document).ready(function() {

    var $dropzone = $(".dropzone");
    var $folder = $(".folder");
    var $densities = $(".densities");
    var $removesufix = $("#removesufix");

    var folderSelected = $('input[type=radio]:checked').val();

    dropzoneAction = uploadlink;

    var checkUrl = function(fromChange) {
        var options = "?folder=" + folderSelected;
        var densitiesLength = $(".densities:checked").length;

        $densities.each(function() {
            if ($(this).is(":checked")) {
                options += "&densities[]=" + $(this).val();
            }
        });

        if ($removesufix.is(":checked") && densitiesLength == 1) {
            options += "&removesufix=1";
        }

        if (densitiesLength == 1) {
            $removesufix.removeAttr("disabled");
        } else {
            $removesufix.attr("disabled", "disabled");
            $removesufix.prop("checked", false);
        }

        dropzoneAction = uploadlink + options;

        if (fromChange) {
            window.history.replaceState("default", "Title", options);
        }
    }

    checkUrl(false);

    $('input[type=radio]').change(function() {
        folderSelected = this.value;
        checkUrl(true);
    });

    $(".folder, #removesufix, .densities").change(function() {
        checkUrl(true);
    });

});

Dropzone.options.uploadWidget = {
    init: function() {
        this.on("processing", function(file) {
            console.log('processing - file: ') + file;

            this.options.url = dropzoneAction;
        });
    },
    queuecomplete: function (file) {
        console.log('queuecomplete - file: ') + file;

        setTimeout(function() {
            window.open(ziplink, "_self");
        }, 200);
    }
};

