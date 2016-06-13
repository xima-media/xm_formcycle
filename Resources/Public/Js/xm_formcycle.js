/**
 * XmFormcycle
 * Inhalte nachladen
 */
(function(window, document, $, undefined){

    var $objects = {wrapper: $('.tx-xm-formcycle')};
    $objects.settings = $('#settings', $objects.wrapper);

    $.get($objects.settings.data('contentUrl'), function(data){
        $('#' + $objects.settings.data('contentCanvas')).html(data);
    });

})(window, document, jQuery);
