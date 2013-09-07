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

function generateReportPopupHtml(reportIds, reportUrl)
{
    var reportsText = (reportIds.length == 1 ? 'report' : 'reports');

    var html =
        '<p>This point represents data from ' + reportIds.length + ' ' + reportsText + '.</p>' +
        '<form action="' + reportUrl + '" method="GET">'
    ;

    $.each(reportIds, function(i, reportId) {
        html += '<input type="hidden" name="report_id[]" value="' + reportId + '" />';
    });

    html +=
        '<p><input type="submit" class="btn" value="View ' + reportsText.capitalize() + '" /></p>' +
        '</form>'
    ;

    return html;
}

function doReportsPopup(e, reportIds, reportUrl)
{
    var offset = $(e).offset();

    var $popup = $('#report-details')
        .css({
            display: 'block',
            left: offset.left + 'px',
            top: offset.top + 'px'
        })
    ;

    $popup.find('.popover-content')
        .html(generateReportPopupHtml(reportIds, reportUrl))
    ;
}
