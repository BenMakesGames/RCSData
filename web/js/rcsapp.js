$(function() {

    $('.temperatureValue').each(function() {
        $(this).val($(this).parent().find('input[type="hidden"]').val());
    });

    $('form').on('change', '.temperatureValue,.temperatureUnit', function() {
        var $parent = $(this).parent();

        var temp = parseInt($parent.find('.temperatureValue').val());
        var unit = $parent.find('.temperatureUnit').val().toUpperCase();

        if(unit == 'F')
            temp = (temp - 32) / 1.8;
        else if(unit == 'K') // ha
            temp -= 273.15;

        $parent.find('input[type="hidden"]').val(temp);

        console.log(temp + ' C');
    });
});