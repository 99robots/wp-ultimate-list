var $ = jQuery;
$(document).ready(function() {
    $('#wpulist_delete_subscriber_yes').click(function() {
        $('#deleteForm').submit();
    });
    $('#wpulist_delete_subscriber_no').click(function() {
        $('#TB_closeWindowButton').trigger('click');
    });
    $('#subscriber_page_refresh').change(function() {
        MM_jumpMenu('parent', this, 1);
    });
    $('#exportButton').click(function() {
        $('#exportForm').submit();
    });
    $('.dashboard_charts').each(function() {
        var id = $(this).attr('data-id');
        var dataChartVar = [];
        $('.dashboard_charts_details_' + id).each(function() {
            chartValue = $(this).attr('data-chart').split("/");
            dataChartVar.push({date: chartValue[0], value: chartValue[1], id: chartValue[2]});
        });
        console.log(dataChartVar);
        create_chart('conv_forms_' + id, dataChartVar);

    });


});

jQuery(function() {
    jQuery("#accordion").accordion();
});

function create_chart(chartId, chartData) {
    new Morris.Area({
        // ID of the element in which to draw the chart.
        element: chartId,
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: chartData,
        // The name of the data record attribute that contains x-values.
        xkey: ['date'],
        // A list of names of data record attributes that contain y-values.
        ykeys: ['value'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['Conversions']
    });
}

function MM_jumpMenu(targ, selObj, restore) { //v3.0
    eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
    if (restore)
        selObj.selectedIndex = 0;
}