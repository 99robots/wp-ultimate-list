 var $ = jQuery;
        $('#tpl_id').change(function() {
            $('#shortcode_copy').val($(this).val());
        });

        $(document).ready(function() {
            var mediaUploader;
            $('#uploadImageWpulist').click(function(e) {



                e.preventDefault();
    // If the uploader object has already been created, reopen the dialog
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
    // Extend the wp.media object
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    }, multiple: false});

    // When a file is selected, grab the URL and set it as the text field's value
                mediaUploader.on('select', function() {
                    attachment = mediaUploader.state().get('selection').first().toJSON();
                    dimen = $('#dimensions').val().split('x');
                    if (attachment.sizes.full.width <= parseInt(dimen[0]) && attachment.sizes.full.height <= parseInt(dimen[1])) {
                        $('#wpulist_upload_image').val(attachment.url);
                        //$('#publish').prop('disabled', false);
                    } else {
                        $('#wpulist_upload_image_alert').css('display', 'block');
                        //  $('#publish').attr('disabled', true);
                        setTimeout("$('#wpulist_upload_image_alert').css('display','none')", 5000);
                    }

                });
    // Open the uploader dialog
                mediaUploader.open();
            });

        });
		
		$(document).ready(function() {
				
                    $('.theme_image').each(function() {
                        plugin_dir = $("#plugin_url", top.document).val();
                        $(this).attr('src', plugin_dir + "themes/" + $(this).attr('data-image'));

                    });


                    $("#closelink").click(function() {
						parent.tb_remove();						   	
                        $("#tpl_id", top.document).val($('input[name=chk_tpl_id]:radio:checked').val());
                        $('#shortcode_text', top.document).html('[wpulist_forms frm_id=' + $('#post_id_shortcode', top.document).val() + 'tpl_id=' + $('input[name=chk_tpl_id]:radio:checked').val() + ']');
                        $('.chk_tpl_id').each(function() {

                            if ($(this).is(":checked")) {
                                $('#theme_name', top.document).html($(this).attr('data-name'));
                                plugin_dir = $("#plugin_url", top.document).val();
                                $('#theme_screenshot', top.document).attr('src', plugin_dir + "themes/" + $(this).attr('data-screenshot'));
                                $('#theme_screenshot', top.document).css('display', 'block');

                                if ($(this).attr('data-dimensions') != '') {
                                    dimen = $(this).attr('data-dimensions').split('x');
                                    $('.upload_image_sec', top.document).css('display', 'block');
                                    $('#dimensions', top.document).val($(this).attr('data-dimensions'));
                                    $('#wpulist_upload_image_alert_msg', top.document).html('Image Size is not under ' + dimen[0] + 'X' + dimen[1]);
                                    $('#wpulist_upload_image_dimen_msg', top.document).html('Recommended image size:  ' + dimen[0] + 'X' + dimen[1]);
                                } else {
                                    $('.upload_image_sec', top.document).css('display', 'none');

                                }
                            }

                        });

                    });

                });
