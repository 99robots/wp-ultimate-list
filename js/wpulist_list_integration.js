var $ = jQuery;
$(document).ready(function() {
    $('.wpulist_atvmlmlistpg').click(function() {
        editservice($(this).attr('data-id'));
        activate($(this).attr('data-id'));
    });
    $('.wpulist_edtmlmlistpg').click(function() {
        editservice($(this).attr('data-id'));
    });
    $('.wpulist_deatvmlmlistpg').click(function() {
        deactivteservice($(this).attr('data-id'));
    });
    $('.wpulist_sttngppupno').click(function() {
        $('#TB_closeWindowButton').trigger('click');
    });
    $('#href_val_aw').click(function() {
        jQuery('#getAweber').submit();
    });
    $("select[name='ml_manager']").change(function() {

        if ($("select[name='ml_manager']").val() == '2') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#servier_api").show();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='2']").attr("apikey"));
            $("#href_val").attr("href", $('#mailchimp_dtl_arr_1').val());
            $("#href_val").html($('#mailchimp_dtl_arr_2').val());
            $("#href_val2").attr("href", $('#mailchimp_dtl_arr_4').val());
            $("#href_val2").html($('#mailchimp_dtl_arr_3').val());
        }
        else if ($("select[name='ml_manager']").val() == '4') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#servier_api").show();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='4']").attr("apikey"));
            $("#href_val").attr("href", $('#GetResponse_dtl_arr_1').val());
            $("#href_val").html($('#GetResponse_dtl_arr_2').val());
            $("#href_val2").attr("href", $('#GetResponse_dtl_arr_4').val());
            $("#href_val2").html($('#GetResponse_dtl_arr_3').val());

        }
        else if ($("select[name='ml_manager']").val() == '21') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#servier_api").show();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='21']").attr("apikey"));
            $("#href_val").attr("href", $('#HubSpot_dtl_arr_1').val());
            $("#href_val").html($('#HubSpot_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '11') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#servier_api").show();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='11']").attr("apikey"));
            $("#href_val").attr("href", $('#Vision6_dtl_arr_1').val());
            $("#href_val").html($('#Vision6_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '13') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#servier_api").show();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='13']").attr("apikey"));
            $("#href_val").attr("href", $('#SendInBlue_dtl_arr_1').val());
            $("#href_val").html($('#SendInBlue_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '14') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#servier_api").show();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='14']").attr("apikey"));
            $("#href_val").attr("href", $('#SendGrid_dtl_arr_1').val());
            $("#href_val").html($('#SendGrid_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '8') {
            $('#servier_username').show();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_api').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='8']").attr("apikey"));
            $("#href_val").attr("href", $('#MadMimi_dtl_arr_1').val());
            $("#href_val").html($('#MadMimi_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '6') {
            $('#servier_username').hide();
            $('#servier_token').show();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_api').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='6']").attr("apikey"));
            $("#href_val_cc").attr("href", $('#constantcontact_dtl_arr_1').val());
            $("#href_val_cc").html($('#constantcontact_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '9') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_vr_token').show();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_api').hide();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='9']").attr("apikey"));
            $("#href_val_vr").attr("href", $('#VerticalResponse_dtl_arr_1').val());
            $("#href_val_vr").html($('#VerticalResponse_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '10') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').show();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_api').show();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='10']").attr("apikey"));
            $("#href_val").attr("href", $('#FreshMail_dtl_arr_1').val());
            $("#href_val").html($('#FreshMail_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '12') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').show();
            $('#servier_dot_secret').hide();
            $('#servier_api').show();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='12']").attr("apikey"));
            $("#href_val").attr("href", $('#Customer_dtl_arr_1').val());
            $("#href_val").html($('#Customer_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '15') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').hide();
            $('#servier_dot_secret').show();
            $('#servier_bm_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#href_val_dm").attr("href", $('#DotMailer_dtl_arr_1').val());
            $("#href_val_dm").html($('#DotMailer_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '16') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_bm_secret').show();
            $('#servier_ic_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#href_val_dm").attr("href", $('#DotMailer_dtl_arr_1').val());
            $("#href_val_dm").html($('#DotMailer_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '17') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').show();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_cm_secret').show();
            $('#servier_ic_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='17']").attr("apikey"));
            $("#href_val").attr("href", $('#CampaignMonitor_dtl_arr_1').val());
            $("#href_val").html($('#CampaignMonitor_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '18') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').show();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').show();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='18']").attr("apikey"));
            $("#href_val").attr("href", $('#ActiveCampaign_dtl_arr_1').val());
            $("#href_val").html($('#ActiveCampaign_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '19') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').show();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#href_val_ic").attr("href", $('#icontact_dtl_arr_1').val());
            $("#href_val_ic").html($('#icontact_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '20') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').show();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').show();
            $('#servier_su_secret').hide();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#wpu_api_key").attr("value", $("select[name='ml_manager'] option[value='20']").attr("apikey"));
            $("#href_val").attr("href", $('#InfusionSoft_dtl_arr_1').val());
            $("#href_val").html($('#InfusionSoft_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '22') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').show();
            $('#servier_sf_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $("#href_val_su").attr("href", $('#SugarCrm_dtl_arr_1').val());
            $("#href_val_su").html($('#SugarCrm_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '23') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#aweber_access_key').hide();
            $('#aweber_access_secret').hide();
            $('#servier_sf_secret').show();
            $("#href_val_sf").attr("href", $('#SalesForces_dtl_arr_1').val());
            $("#href_val_sf").html($('#SalesForces_dtl_arr_2').val());
        }
        else if ($("select[name='ml_manager']").val() == '3') {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_api').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_su_secret').hide();
            $('#aweber_access_key').show();
            $('#aweber_access_secret').show();
            $("#href_val_aw").attr("href", $('#awber_dtl_arr_1').val());
            $("#href_val_aw").html($('#awber_dtl_arr_2').val());
        }
        else {
            $('#servier_username').hide();
            $('#servier_token').hide();
            $('#servier_vr_token').hide();
            $('#servier_fr_secret').hide();
            $('#servier_cio_secret').hide();
            $('#servier_dot_secret').hide();
            $('#servier_bm_secret').hide();
            $('#servier_cm_secret').hide();
            $('#servier_ic_secret').hide();
            $('#servier_ac_secret').hide();
            $('#servier_su_secret').hide();
            $('#servier_in_secret').hide();
            $('#servier_sf_secret').hide();
            $('#servier_api').show();

        }

    });


});

var $ = jQuery;
function deactivteservice(id)
{
    $(".deactivaeform  .deacitvateservice").val(id);
    $(".deactivatelink").trigger("click");
}
function editservice(id)
{
    $("select[name='ml_manager'] option[value='" + id + "']").attr("selected", true);
    $("select[name='ml_manager']").val(id);
    $("select[name='ml_manager']").trigger("change");
}