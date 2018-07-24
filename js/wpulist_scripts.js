var $ = jQuery;
// Delete mailing
function wpmulti_delete_email_list(id, alertmsg) {
    if (confirm(alertmsg)) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: ajaxurl,
            data: 'do=delete_mailing_list&mail_id=' + id + '&action=wpul_process_ajax_stng',
            success: function(result) {
                if (result['ui'] != "") {
                    $("#mail_list_" + result['ui']).remove();
                }
            }
        });
    }
    else {
        return false;
    }
}


$(document).ready(function($) {


// Save mailing list
    $("#frm_wpulist_mailing_list").submit(function() {

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: ajaxurl,
            data: 'do=update_mailing_list&mailing_list_name=' + $("#wpulist_mailing_list_name").val() + '&action=wpul_process_ajax_stng',
            success: function(result) {
                $('#et_re_loading').hide();
                if (result['ui'] != "") {
                    $(".widefat").prepend(result['ui']);
                }
            }

        });
        //alert("hi hi 33");
        return false;
    });



    $("#wpulist_ml_settings").submit(function() {
        $('#et_re_loading2').show();
        $('#et_re_sv_search').attr('disabled', true);
        data = $("#wpulist_ml_settings").serialize() + '&do=update_integration_settings&action=wpul_integration_settings'

        $.post(ajaxurl, data, function(response) {

            $('#wpulist_show_msg').html(response).fadeIn().delay(100000).fadeOut();
            $('#et_re_loading2').hide();
            $('#et_re_sv_search').attr('disabled', false);
        });

        return false;
    });

});