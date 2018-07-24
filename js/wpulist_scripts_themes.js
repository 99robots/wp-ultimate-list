
jQuery(document).ready(function() {

    back_url = jQuery('#background_url').val();
    theme_no = jQuery('#theme_count').val();

    if (back_url != '') {
        jQuery('.thme' + theme_no).css('background-image', "url(" + back_url + ")");
    }

    jQuery('.frmbutton').click(function() {

        jQuery.noConflict();
        jQuery('#et_re_loading2').show();
        frm_id = jQuery('#frm_id').val();
        ajax_url = jQuery('#ajax_url').val();
        jQuery('#btn_' + frm_id).attr('disabled', true);
        data = jQuery('#wpulist_frm_' + frm_id).serialize() + '&do=wpul_submit_frm&action=wpul_process_ajax';
        jQuery.post(ajax_url, data, function(response) {
            //alert(response);
            jQuery('#wpulist_frm_response').html(response).fadeIn().delay(10000).fadeOut();
            jQuery('#et_re_loading2').hide();
            jQuery('#btn_' + frm_id).attr('disabled', false);
        });

        return false;

    });


});	