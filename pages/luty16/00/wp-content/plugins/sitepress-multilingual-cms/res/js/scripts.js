/*global jQuery, icl_ajx_url, icl_ajx_saved, icl_ajx_error, icl_ajxloaderimg_src */

jQuery(document).ready(function($){
    if(jQuery('#category-adder').html()){
        jQuery('#category-adder').prepend('<p>'+icl_cat_adder_msg+'</p>');
    }
    //jQuery('#noupdate_but input[type="button"]').click(iclSetDocumentToDate);
    jQuery('select[name="icl_translation_of"]').change(function(){jQuery('#icl_translate_options').fadeOut();});
    jQuery('#icl_dismiss_help').click(iclDismissHelp);
    jQuery('#icl_dismiss_upgrade_notice').click(iclDismissUpgradeNotice);
    jQuery(document).delegate('a.icl_toggle_show_translations', 'click', iclToggleShowTranslations)

    icl_tn_initial_value   = jQuery('#icl_post_note textarea').val();
    jQuery(document).delegate('#icl_post_add_notes h4 a', 'click', iclTnOpenNoteBox);

    jQuery(document).delegate('#icl_post_note textarea', 'keyup', iclTnClearButtonState);
    jQuery(document).delegate('#icl_tn_clear', 'click', function(){jQuery('#icl_post_note textarea').val('');jQuery(this).attr('disabled','disabled')});
    jQuery(document).delegate('#icl_tn_save', 'click', iclTnCloseNoteBox);

    jQuery('#icl_pt_hide').click(iclHidePTControls);
    jQuery('#icl_pt_show').click(iclShowPTControls);

    jQuery(document).delegate('#icl_pt_controls ul li :checkbox', 'change', function(){
        if(jQuery('#icl_pt_controls ul li :checkbox:checked').length){
            jQuery('#icl_pt_send').removeAttr('disabled');
        }else{
            jQuery('#icl_pt_send').attr('disabled', 'disabled');
        }
        iclPtCostEstimate();
    });
    jQuery(document).delegate('#icl_pt_send', 'click', iclPTSend);

    /* needed for tagcloud */
    oldajaxurl = false;

    jQuery(document).delegate("#icl_make_translatable_submit", 'click', icl_make_translatable);

    icl_admin_language_switcher();

    jQuery(document).ajaxSuccess(function(evt, request, settings) {

        if(typeof settings === 'undefined' || typeof settings.data === 'undefined' || typeof settings.data.search === 'undefined')  return;

        if(settings.data.search('action=add-tag') != -1 || settings.data.search('action=delete-tag') != -1 ){
            jQuery('#icl_subsubsub').load(location.href + ' #icl_subsubsub', function(resp){
                var p1 = resp.indexOf('<span id="icl_subsubsub">');
                var p2 = resp.indexOf('<\\/span>', p1);
                jQuery('#icl_subsubsub').html(resp.substr(p1+25, p2-p1-25).replace(/\\/g, ''));
            });
        }

        if(settings.data.search('action=add-tag') != -1 && (settings.data.search('source_lang%3D') != -1 || settings.data.search('icl_translation_of') != -1) ) {

            var taxonomy = '';
            var vars = settings.data.split("&");
            for (var i=0; i<vars.length; i++) {
                var pair = vars[i].split("=");
                if (pair[0] == 'taxonomy') {
                  taxonomy = pair[1];
                  break;
                }
            }

            jQuery('#icl_tax_'+taxonomy+'_lang .inside').html(icl_ajxloaderimg);
            jQuery.ajax({
                type:'GET',
                url : location.href.replace(/&trid=([0-9]+)/, ''),
                success: function(msg){
                    jQuery('#icl_tax_adding_notice').fadeOut();
                    jQuery('#icl_tax_'+taxonomy+'_lang .inside').html(jQuery(msg).find('#icl_tax_'+taxonomy+'_lang .inside').html());
                }
            })
        }
    });

    jQuery('a.icl_user_notice_hide').click(icl_hide_user_notice);

    jQuery('#icl_translate_independent').click(function(){
        jQuery(this).attr('disabled', 'disabled').after(icl_ajxloaderimg);
        jQuery.ajax({type: "POST",url: icl_ajx_url,
            data: "icl_ajx_action=reset_duplication&post_id="+jQuery('#post_ID').val() + '&_icl_nonce=' + jQuery('#_icl_nonce_rd').val(),
            success: function(msg){location.reload()}});
    });
    jQuery('#icl_set_duplicate').click(function(){
        if(confirm(jQuery(this).next().html())){
            jQuery(this).attr('disabled', 'disabled').after(icl_ajxloaderimg);
            jQuery.ajax({type: "POST",url: icl_ajx_url,
            data: "icl_ajx_action=set_duplication&post_id="+jQuery('#post_ID').val() + '&_icl_nonce=' + jQuery('#_icl_nonce_sd').val(),
            success: function(msg){location.reload()}});
        }

    });

    jQuery('#post input[name="icl_dupes[]"]').change(function(){
        if(jQuery('#post input[name="icl_dupes[]"]:checked').length > 0){
            jQuery('#icl_make_duplicates').show().removeAttr('disabled');
        }else{
            jQuery('#icl_make_duplicates').hide().attr('disabled', 'disabled');
        }
    })
    jQuery('#icl_make_duplicates').click(function(){
        var langs = new Array();
        jQuery('#post input[name="icl_dupes[]"]:checked').each(function(){langs.push(jQuery(this).val())});
        langs = langs.join(',');
        jQuery(this).attr('disabled', 'disabled').after(icl_ajxloaderimg);
        jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,data: "icl_ajx_action=make_duplicates&post_id=" + jQuery('#post_ID').val() + '&langs=' + langs + '&_icl_nonce=' + jQuery('#_icl_nonce_mdup').val(),
            success: function(msg){location.reload()}
        });
    })

    jQuery(document).delegate('#wpml_als_help_link', 'click', function(){
        jQuery('#wp-admin-bar-WPML_ALS').removeClass('hover');
        jQuery('#icl_als_help_popup').css('left', jQuery('#wp-admin-bar-WPML_ALS').position().left-10);
        jQuery('#icl_als_help_popup').show();
    });

    icl_popups.attach_listeners();

    if(jQuery('#icl_slug_translation').length){
        iclSaveForm_success_cb.push(function(form, response){
            if(form.attr('name') === 'icl_slug_translation'){
                if(response[1] === 1){
                    jQuery('.icl_slug_translation_choice').show();
                }else{
                    jQuery('.icl_slug_translation_choice').hide();
                }
            }else if(form.attr('name') === 'icl_custom_posts_sync_options'){
                jQuery('.icl_st_slug_tr_warn').hide();
            }
        });
    }

    jQuery('#icl_custom_posts_sync_options').submit(function(){
        iclHaltSave = false;
        jQuery('.icl_slug_translation_choice input[type=text]').removeClass('icl_error_input');
        jQuery('#icl_ajx_response_cp').html('').fadeOut();
        jQuery('.icl_slug_translation_choice input[type=text]').each(function(){

            if(jQuery(this).is(':visible') && jQuery.trim(jQuery(this).val()) === ''){
                jQuery(this).addClass('icl_error_input');
                iclHaltSave = true;
            }

        });

        if(iclHaltSave){
            jQuery('#icl_ajx_response_cp').html('Errors').fadeIn();
        }
    });

    if(jQuery('#icl_slug_translation').length){
        jQuery('#icl_slug_translation').submit(iclSaveForm);
        jQuery('.icl_slug_translation_choice input[type=checkbox]').change(function(){
            var table_row = jQuery(this).closest('tr');
            var cpt_slugs = jQuery(table_row).find('.js-cpt-slugs');

            if ( jQuery(this).prop('checked') ){
                cpt_slugs.show();
            }
            else {
                cpt_slugs.hide();
            }
        });
    }

    jQuery('.icl_sync_custom_posts').change(function(){
        var val = jQuery(this).val();
        var table_row = jQuery(this).closest('tr');
        var cpt_slugs = jQuery(table_row).find('.js-cpt-slugs');
        var icl_slug_translation = jQuery(table_row).find(':checkbox');
        if (val == 1) {
            icl_slug_translation.closest('.icl_slug_translation_choice').show();
            if( icl_slug_translation.prop('checked') && cpt_slugs) {
                cpt_slugs.show();
            }
        } else if(cpt_slugs) {
            icl_slug_translation.closest('.icl_slug_translation_choice').hide();
            cpt_slugs.hide();
        }

    });

    jQuery(document).delegate('.icl_error_input', 'focus', function() {
        jQuery(this).removeClass('icl_error_input');
    });

    $('.js-toggle-colors-edit').on('click', function(e) {
        e.preventDefault();

        var $target = $( $(this).attr('href') );
        var $caret = $(this).find('.js-arrow-toggle');
        console.log($caret);

        if ( $target.is(':visible') ) {
            $target.slideUp();
            $caret.removeClass('icon-caret-up').addClass('icon-caret-down');
        }
        else {
            $target.slideDown();
            $caret.removeClass('icon-caret-down').addClass('icon-caret-up');
        }

        return false;
    });

    $('#js-post-availability').on('change', function(e) {

        var $target = $( $(this).data('target') );

        if ( $(this).prop('checked') ) {
            $target.show();
        }
        else {
            $target.hide();
        }

    });

    $('.js-wpml-navigation-links a').on('click', function(e) { // prevent default scrolling for navigation links
        e.preventDefault();

        var $target = $( $(this).attr('href') );

        if ( $target.length !== 0 ) {
            var offset = 0;

            if ( $('#wpadminbar').length !== 0 ) {
                offset = $('#wpadminbar').height();
            }

            $('html, body').animate({
                scrollTop: $target.offset().top - offset
             }, 300, function() {
                var $header = $target.find('.wpml-section-header h3');
                $header.addClass('active');
                console.log($header);
                setTimeout(function(){
                    $header.removeClass('active');
                }, 700);
             });
        }

        return false;
    });

	var icl_untranslated_blog_posts = $("input[name=icl_untranslated_blog_posts]");
	var icl_untranslated_blog_posts_help = $('#icl_untranslated_blog_posts_help');

	var update_icl_untranslated_blog_posts = function () {
		//Get the value of currently selected radio option
		var value = icl_untranslated_blog_posts.filter(':checked').val();

		if (value == 0) {
			icl_untranslated_blog_posts_help.fadeOut('slow');
		} else {
			icl_untranslated_blog_posts_help.fadeIn('slow');
		}
	};

	update_icl_untranslated_blog_posts();
	icl_untranslated_blog_posts.bind('click', update_icl_untranslated_blog_posts);

});

var icl_tn_initial_value   = '';

window.onbeforeunload = function() {
    if(icl_tn_initial_value != jQuery('#icl_post_note textarea').val()){
        return jQuery('#icl_tn_cancel_confirm').val();
    }
};

function fadeInAjxResp(spot, msg, err){
    if(err != undefined){
        col = jQuery(spot).css('color');
        jQuery(spot).css('color','red');
    }
    jQuery(spot).html(msg);
    jQuery(spot).fadeIn();
    window.setTimeout(fadeOutAjxResp, 3000, spot);
    if(err != undefined){
        jQuery(spot).css('color',col);
    }
}

function fadeOutAjxResp(spot){
    jQuery(spot).fadeOut();
}

var icl_ajxloaderimg = '<img src="'+icl_ajxloaderimg_src+'" alt="loading" width="16" height="16" />';

var iclHaltSave = false; // use this for multiple 'submit events'
var iclSaveForm_success_cb = [];
function iclSaveForm() {

	if (iclHaltSave) {
		return false;
	}
	var form_name = jQuery(this).attr('name');
	jQuery('form[name="' + form_name + '"] .icl_form_errors').html('').hide();
	var ajx_resp = jQuery('form[name="' + form_name + '"] .icl_ajx_response').attr('id');
	fadeInAjxResp('#' + ajx_resp, icl_ajxloaderimg);
	var serialized_form_data = jQuery(this).serialize();
	var serialized_form_array_data = jQuery(this).serializeArray();
	jQuery.ajax({
		type: "POST",
		url: icl_ajx_url,
		data: "icl_ajx_action=" + jQuery(this).attr('name') + "&" + serialized_form_data,
		success: function (msg) {
			var spl = msg.split('|');
			if (parseInt(spl[0]) == 1) {
				fadeInAjxResp('#' + ajx_resp, icl_ajx_saved);
				for (var i = 0; i < iclSaveForm_success_cb.length; i++) {
					iclSaveForm_success_cb[i](jQuery('form[name="' + form_name + '"]'), spl);
				}
				if (form_name == 'icl_slug_translation' || form_name == 'icl_save_language_switcher_options') {
					location.reload();
				}
			} else {
				var icl_form_errors = jQuery('form[name="' + form_name + '"] .icl_form_errors');
				var error_html = (typeof spl[1] != 'undefined') ? spl[1] : spl[0];
				icl_form_errors.html(error_html);
				icl_form_errors.fadeIn();
				fadeInAjxResp('#' + ajx_resp, icl_ajx_error, true);
			}
		}
	});
	return false;
}


function iclSetDocumentToDate(){
    var thisbut = jQuery(this);
    if(!confirm(jQuery('#noupdate_but_wm').html())) return;
    thisbut.attr('disabled','disabled');
    thisbut.css({'background-image':"url('"+icl_ajxloaderimg_src+"')", 'background-position':'center right', 'background-repeat':'no-repeat'});
    jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,
            data: "icl_ajx_action=set_post_to_date&post_id="+jQuery('#post_ID').val(),
            success: function(msg){
                spl = msg.split('|');
                thisbut.removeAttr('disabled');
                thisbut.css({'background-image':'none'});
                thisbut.parent().remove();
                var st = jQuery('#icl_translations_status td.icl_translation_status_msg');
                st.each(function(){
                    jQuery(this).html(jQuery(this).html().replace(spl[0],spl[1]))
                })
                jQuery('#icl_minor_change_box').fadeIn();
            }
        });
}

function iclDismissHelp(){
    var thisa = jQuery(this);
    jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,
            data: "icl_ajx_action=dismiss_help&_icl_nonce=" + jQuery('#icl_dismiss_help_nonce').val(),
            success: function(msg){
                thisa.closest('#message').fadeOut();
            }
    });
    return false;
}

function iclDismissUpgradeNotice(){
    var thisa = jQuery(this);
    jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,
            data: "icl_ajx_action=dismiss_upgrade_notice&_icl_nonce=" + jQuery('#_icl_nonce_dun').val(),
            success: function(msg){
                thisa.parent().parent().fadeOut();
            }
    });
    return false;
}

function iclToggleShowTranslations(){
    jQuery('a.icl_toggle_show_translations').toggle();
    jQuery('#icl_translations_table').toggle();
    jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,
            data: "icl_ajx_action=toggle_show_translations&_icl_nonce=" + jQuery('#_icl_nonce_tst').val()
    });
    return false;
}

function iclTnOpenNoteBox(){
    jQuery('#icl_post_add_notes #icl_post_note').slideDown();
    jQuery('#icl_post_note textarea').focus();
    return false;
}
function iclTnClearButtonState(){
    if(jQuery.trim(jQuery(this).val())){
        jQuery('#icl_tn_clear').removeAttr('disabled');
    }else{
        jQuery('#icl_tn_clear').attr('disabled', 'disabled');
    }
}
function iclTnCloseNoteBox(){
    jQuery('#icl_post_add_notes #icl_post_note').slideUp('fast', function(){
        if(icl_tn_initial_value != jQuery('#icl_post_note textarea').val()){
            jQuery('#icl_tn_not_saved').fadeIn();
        }else{
            jQuery('#icl_tn_not_saved').fadeOut();
        }
    });
}

function iclShowPTControls(){
    var thisa = jQuery(this);
    jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,
            data: "icl_ajx_action=toggle_pt_controls&value=0&_icl_nonce=" + jQuery('#_icl_nonce_ptc').val(),
            success: function(msg){
                jQuery('#icl_pt_controls').slideDown();
                thisa.fadeOut(function(){jQuery('#icl_pt_hide').fadeIn();});
            }
    });
    return false;
}

function iclHidePTControls(){
    var thisa = jQuery(this);
    jQuery.ajax({
            type: "POST",
            url: icl_ajx_url,
            data: "icl_ajx_action=toggle_pt_controls&value=1&_icl_nonce=" + jQuery('#_icl_nonce_ptc').val(),
            success: function(msg){
                thisa.fadeOut(function(){
                    jQuery('#icl_pt_controls').slideUp(function(){
                        jQuery('#icl_pt_show').fadeIn()
                    });
                });
            }
    });
    return false;
}

function iclPtCostEstimate(){
    var estimate = 0;
    var words = parseInt(jQuery('#icl_pt_wc').val());
    jQuery('#icl_pt_controls ul li :checkbox:checked').each(
        function(){
            lang = jQuery(this).attr('id').replace(/^icl_pt_to_/,'');
            rate = jQuery('#icl_pt_rate_'+lang).val();
            estimate += words * rate;
        }
    )
    if(estimate < 1){
        precision = Math.floor(estimate).toString().length + 1;
    }else{
        precision = Math.floor(estimate).toString().length + 2;
    }

    jQuery('#icl_pt_cost_estimate').html(estimate.toPrecision(precision));
}

function iclPTSend(){
    jQuery('#icl_pt_error, #icl_pt_success').hide();
    jQuery('#icl_pt_send').attr('disabled', 'disabled');

    if(jQuery('#icl_pt_controls ul li :checkbox:checked').length==0) return false;

    target_languages = new Array();
    var translators = new Array();
    jQuery('#icl_pt_controls ul li :checkbox:checked').each(function(){
        var thisl = jQuery(this).val();
        target_languages.push(thisl);
        translators.push(jQuery('#icl_pt_controls [name="translator\['+thisl+'\]"]').val());
    });


    jQuery.ajax({
        type: "POST",
        url: icl_ajx_url,
        dataType: 'json',
        data: "icl_ajx_action=send_translation_request&post_ids=" + jQuery('#icl_pt_post_id').val()
            + '&icl_post_type['+ jQuery('#icl_pt_post_id').val() + ']=' + jQuery('#icl_pt_post_type').val()
            + '&target_languages='+target_languages.join('#')
            + '&translators='+translators.join('#')
            + '&service=icanlocalize'
            + '&tn_note_'+jQuery('#icl_pt_post_id').val()+'=' + jQuery('#icl_pt_tn_note').val()
            + '&_icl_nonce=' + jQuery('#_icl_nonce_pt_' + jQuery('#icl_pt_post_id').val()).val(),
        success: function(msg){
            for(i in msg){
                p = msg[i];
            }
            if(p.status > 0){
                location.href = location.href.replace(/#(.+)/,'')+'&icl_message=success';
            }else{
                jQuery('#icl_pt_error').fadeIn();
            }
        }
    });

}

function icl_pt_reload_translation_box(){
	var _icl_nonce_gts = jQuery('#_icl_nonce_gts');
	if(_icl_nonce_gts.length) {
		var icl_nonce = _icl_nonce_gts.val();
		jQuery.ajax({
			type: "POST",
			url: icl_ajx_url,
			dataType: 'json',
			data: "icl_ajx_action=get_translator_status&_icl_nonce=" + icl_nonce,
			success: function(){
				jQuery('#icl_pt_hide').hide();
				jQuery('#icl_pt_controls').html(icl_ajxloaderimg+'<br class="clear" />');
				jQuery.get(location.href, {rands:Math.random()}, function(data){
					jQuery('#icl_pt_controls').html(jQuery(data).find('#icl_pt_controls').html());
					icl_tb_init('a.icl_thickbox');
					icl_tb_set_size('a.icl_thickbox');
					jQuery('#icl_pt_hide').show();

				})
			}
		});
	}
}

function icl_copy_from_original(lang, trid){
	jQuery('#icl_cfo').after(icl_ajxloaderimg).attr('disabled', 'disabled');

	if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.get('content') ) && !ed.isHidden() && ed.hasVisual === true) { //has visual = set to normal non-html editing mode
		var content_type = 'rich';
	} else {
		var content_type = 'html';
	}

	if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.get('excerpt') ) && !ed.isHidden() && ed.hasVisual === true) {
		var excerpt_type = 'rich';
	} else {
		var excerpt_type = 'html';
	}
	// figure out all available editors and their types

	var custom_types = '';
	jQuery.ajax({
		            type:     "POST",
		            dataType: 'json',
		            url:      icl_ajx_url,
		            data:     "icl_ajx_action=copy_from_original&lang=" + lang + '&trid=' + trid + '&content_type=' + content_type + '&excerpt_type=' + excerpt_type + '&_icl_nonce=' + jQuery('#_icl_nonce_cfo_' + trid).val(),
		            success:  function (msg) {
			            if (msg.error) {
				            alert(msg.error);
			            } else {
				            try {
					            if (msg.content) {
						            if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.get('content') ) && !ed.isHidden()) {
							            ed.focus();
							            if (tinymce.isIE) {
								            ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
							            }
							            ed.execCommand('mceInsertContent', false, msg.content);
						            } else {
							            wpActiveEditor = 'content';
							            edInsertContent(edCanvas, msg.content);
						            }
					            }
					            if (typeof msg.title != "undefined") {
						            jQuery('#title-prompt-text').hide();
						            jQuery('#title').val(msg.title);
					            }
					            //handling of custom fields
					            //these have to be of array type with the indexes editor_type editor_name and value
					            //possible types are editor or text
					            //in case of text te prompt to be removed might have to be provided
					            for (var element in msg.customfields) {
						            if (msg.customfields[element].editor_type === 'editor') {
							            if (typeof tinyMCE != 'undefined' && ( ed = tinyMCE.get(msg.customfields[element].editor_name) ) && !ed.isHidden()) {
								            ed.focus();
								            if (tinymce.isIE) {
									            ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
								            }
								            ed.execCommand('mceInsertContent', false, msg.customfields[element].value);
							            } else {
								            wpActiveEditor = msg.customfields[element].editor_name;
								            edInsertContent(edCanvas, msg.customfields[element].value);
							            }
						            } else {
							            jQuery('#' + msg.customfields[element].editor_name).val(msg.customfields[element].value);
						            }
					            }
				            } catch (err) {
					            ;
				            }
			            }
			            jQuery('#icl_cfo').next().fadeOut();
		            }
	            });
	return false;
}

function icl_make_translatable(){
    var that = jQuery(this);
    jQuery(this).attr('disabled', 'disabled');
    jQuery('#icl_div_config .icl_form_success').hide();
    var translate = jQuery('#icl_make_translatable').attr('checked') ? 1 : 0;
    var custom_post = jQuery('#icl_make_translatable').val();
    var custom_taxs_on = new Array();
    var custom_taxs_off = new Array();
    jQuery(".icl_mcs_custom_taxs").each(function(){
        if(jQuery(this).attr('checked')){
            custom_taxs_on.push(jQuery(this).val());
        }else{
            custom_taxs_off.push(jQuery(this).val());
        }

    });

    var cfnames = new Array();
    var cfvals = new Array();
    jQuery('.icl_mcs_cfs:checked').each(function(){
        if(!jQuery(this).attr('disabled')){
            cfnames.push(jQuery(this).attr('name').replace(/^icl_mcs_cf_/,''));
            cfvals.push(jQuery(this).val())
        }
    })

    jQuery.post(location.href,
        {
                'post_id'       : jQuery('#post_ID').val(),
                'icl_action'    : 'icl_mcs_inline',
                'custom_post'   : custom_post,
                'translate'     : translate,
                'custom_taxs_on[]'   : custom_taxs_on,
                'custom_taxs_off[]'   : custom_taxs_off,
                'cfnames[]'   : cfnames,
                'cfvals[]'   : cfvals,
                '_icl_nonce' : jQuery('#_icl_nonce_imi').val()

        },

        function(data){
            that.removeAttr('disabled');
            if(translate){
                if(jQuery('#icl_div').length > 0){
                    icl_div_update = true;
                    jQuery('#icl_div').remove();
                }else{
                    icl_div_update = false;
                }

                var prependto = false;
                if(jQuery('#side-sortables').html()){
                    prependto = jQuery('#side-sortables');
                }else{
                    prependto = jQuery('#normal-sortables');
                }
                prependto.prepend(
                    '<div id="icl_div" class="postbox">' + jQuery(data).find('#icl_div').html() + '</div>'
                )

                jQuery('#icl_mcs_details').html(jQuery(data).find('#icl_mcs_details').html());

                if(!icl_div_update){
                    location.href='#icl_div';
                }
            }else{
                jQuery('#icl_div').hide();
                jQuery('#icl_mcs_details').html('');
            }
            jQuery('#icl_div_config .icl_form_success').fadeIn();
        }
    );


    return false;
}


function icl_admin_language_switcher(){
    jQuery('#icl-als-inside').width( jQuery('#icl-als-actions').width() - 4 );
    jQuery('#icl-als-toggle, #icl-als-inside').bind('mouseenter', function() {
        jQuery('#icl-als-inside').removeClass('slideUp').addClass('slideDown');
        setTimeout(function() {
            if ( jQuery('#icl-als-inside').hasClass('slideDown') ) {
                jQuery('#icl-als-inside').slideDown(100);
                jQuery('#icl-als-first').addClass('slide-down');
            }
        }, 200);
    }).bind('mouseleave', function() {
        jQuery('#icl-als-inside').removeClass('slideDown').addClass('slideUp');
        setTimeout(function() {
            if ( jQuery('#icl-als-inside').hasClass('slideUp') ) {
                jQuery('#icl-als-inside').slideUp(100, function() {
                    jQuery('#icl-als-first').removeClass('slide-down');
                });
            }
        }, 300);
    });

    jQuery('#show-settings-link, #contextual-help-link').bind('click', function(){
        jQuery('#icl-als-wrap').toggle();
    })
}

function icl_hide_user_notice(){
    var notice = jQuery(this).attr('href').replace(/^#/, '');
    var thisa = jQuery(this);

    jQuery.ajax({
        type: "POST",
        dataType: 'json',
        url: icl_ajx_url,
        data: "icl_ajx_action=save_user_preferences&user_preferences[notices]["+notice+"]=1&_icl_nonce="+jQuery('#_icl_nonce_sup').val(),
        success: function(msg){
            thisa.parent().parent().fadeOut();
        }
    });

    return false;
}

function icl_cf_translation_preferences_submit(cf, obj) {
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: 'action=wpml_ajax&icl_ajx_action=wpml_cf_translation_preferences&translate_action='+obj.parent().children('input:[name="wpml_cf_translation_preferences['+cf+']"]:checked').val()+'&'+obj.parent().children('input:[name="wpml_cf_translation_preferences_data_'+cf+'"]').val() + '&_icl_nonce = ' + jQuery('#_icl_nonce_cftpn').val(),
        cache: false,
        error: function(html){
            jQuery('#wpml_cf_translation_preferences_ajax_response_'+cf).html('Error occured');
        },
        beforeSend: function(html){
            jQuery('#wpml_cf_translation_preferences_ajax_response_'+cf).html(icl_ajxloaderimg);
        },
        success: function(html){
            jQuery('#wpml_cf_translation_preferences_ajax_response_'+cf).html(html);
        },
        dataType: 'html'
    });

}


    /* icl popups */
    var icl_popups = {

    attach_listeners: function(){
        jQuery('.icl_pop_info_but').click(function(){

            jQuery('.icl_pop_info').hide();
            var pop = jQuery(this).next();

            var _tdoffset = 0;
            var _p = pop.parent().parent();
            if(_p[0]['nodeName'] == 'TD'){
                _tdoffset = _p.width() - 30;
            }

            pop.show(function(){
                var animate = {};
                var fold = jQuery(window).width() + jQuery(window).scrollLeft();
                if(fold < pop.offset().left + pop.width()){
                    animate.left = '-=' + (pop.width() - _tdoffset);
                };

                if(parseInt(jQuery(window).height() + jQuery(window).scrollTop()) < parseInt(pop.offset().top) + pop.height()){
                    animate.top = '-=' + pop.height();
                }
                if(animate) pop.animate(animate);

            });
        });

        jQuery('.icl_pop_info_but_close').click(function(){
           jQuery(this).parent().fadeOut();
        });


    }

}



