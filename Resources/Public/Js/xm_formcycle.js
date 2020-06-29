/**
 * XmFormcycle
 * Inhalte nachladen
 */
(function(window, document, $, undefined){

    var $objects = {wrapper: $('.tx-xm-formcycle')};
    $objects.settings = $('#settings', $objects.wrapper);

    var $canvas = $('#' + $objects.settings.data('contentCanvas'));

    if ($objects.settings
        && $objects.settings.data('contentUrl') != ''
        && $canvas.length > 0) {

        $.ajax({
            url: $objects.settings.data('contentUrl'),
            type: "GET",
            dataType: "html",
            xhrFields: {
                withCredentials: true
            },
        }).done(function (data) {
            $canvas.html(data);
        });
    }


})(window, document, jQuery);
