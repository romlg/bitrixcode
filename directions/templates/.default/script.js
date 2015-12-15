
$(document).ready(function(){
    $(document).on("click", '#tabs [data-toggle="tab"]', function(){
        var curr_tab = $(this).attr('href').replace('#', '');
        $('#left_tab_menu a').each(function(){
            var base_href;
            if (base_href = $(this).parent().attr('data-base-href')) {
                var old_href = $(this).attr('href');
                $(this).attr('href', base_href + (base_href.indexOf("?")>=0 ? '&' : '?') + "active_tab=" + curr_tab);
                if ($(this).attr('onclick')) {
                    $(this).attr('onclick', $(this).attr('onclick').replace(old_href, $(this).attr('href')));
                }
            }
        });
        if ($(this).data("url-replace")) {
            var new_href = "?active_tab="+curr_tab;
            try {
                history.pushState(null, null, new_href);
                return;
            } catch(e) {}
            location.hash = '#' + new_href;
        }
    });

    $(document).on("click", '[data-scroll-services]', function(e){
        
		if ($('#clinic_form').is(':hidden')) {
			return;
		}
		e.stopPropagation();
        e.preventDefault();

        var formtype = $(this).data('scroll-services');

        if (formtype=="doctor") {
            var doctor_id = $(this).data('scroll-doctor');
            var clinic_id = $(this).data('scroll-clinic');

            if (doctor_id) {
                $('#clinic_form').append("<input type=hidden name=clinic value=''>");
                $('#clinic_form').append("<input type=hidden name=doctor value='" + doctor_id + "'>");
            } else if (clinic_id) {
                $('#clinic_form').append("<input type=hidden name=clinic value='" + clinic_id + "'>");
                $('#clinic_form').append("<input type=hidden name=doctor value=''>");
            }

            if ($('#doctor_first').length) {
                $('#clinic_form').find('#doctor_first').click();
                $('#clinic_form input[name=no_submit]').click();
            } else {
                $('#selected_formtype').val(formtype).change();
            }
        } else if (formtype=="service") {
            if ($('#service_first').length) {
                $('#clinic_form').find('#service_first').click();
                $('#clinic_form input[name=no_submit]').click();
            } else {
                $('#selected_formtype').val(formtype).change();
            }
        } else if (formtype=="home") {
            if ($('#home_first').length) {
                $('#clinic_form').find('#home_first').click();
                $('#clinic_form input[name=no_submit]').click();
            } else {
                $('#selected_formtype').val(formtype).change();
            }
        }

        var tabc = $('#clinic_form').parent();
        if (tabc.length) {
            $('html, body').animate({
                scrollTop: tabc.offset().top
            }, 500);
        }
        return false;
    });
});
