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

        $.get($objects.settings.data('contentUrl'), function(data){
            $canvas.html(data);
        });
    }


})(window, document, jQuery);
