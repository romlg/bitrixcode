
$(document).ready(function(){
    $(".accordeon-body").removeClass("expanded");
    $(".accordeon-item:not(:has(.expanded))").removeClass("expanded");
    $('.accordeon-body[data-level!="1"][data-level!="2"]').hide();
    $(".accordeon-item.expanded:not(:has(li:visible))").removeClass("expanded");
    $(".accordeon-item:not(:has(.expanded))").removeClass("expanded");
    $('.all-prices').on('click', '.accordeon-header', function() {
        var thisEl = $(this);
        var datalevel = thisEl.data('level');

        if ($(this).data("url")) {
            try {
                history.pushState(null, null, $(this).data("url"));
            } catch(e) {
                if ($(this).data("id")) {
                    location.hash = '#collapse' + $(this).data("id");
                }
            }
        }

        if (datalevel==1) {
            var parentEl = thisEl.parent();
            if (parentEl.hasClass('expanded')) {
                parentEl.children('ul.accordeon-body').removeClass('expanded').slideUp(400);
                parentEl.removeClass('expanded').find('.accordeon-body[data-level="2"]').slideUp(400);
            } else {
                parentEl.children('ul.accordeon-body').addClass('expanded').slideDown(600);
                parentEl.addClass('expanded').find('.accordeon-body[data-level="2"]').slideDown(600);
            }
        } else {
            var parentEl = thisEl.parent();
            if (parentEl.hasClass('expanded')) {
                parentEl.removeClass('expanded').find('.accordeon-body').slideUp(400);
            } else {
                parentEl.addClass('expanded').find('.accordeon-body').addClass('expanded').slideDown(600);
            }
        }
    });

    var w_hash = location.hash;
    if (w_hash) {
        var item_to_show = $('.all-prices').find(w_hash);
        if (item_to_show.length>0) {

            var level = Number(item_to_show.data("level"));

            if (level == 2) {
                item_to_show.find('.accordeon-header:first').click();
            } else if (level > 2) {
                item_to_show.find('.accordeon-header:first').click();
                item_to_show.parents(".accordeon-body").each(function(){
                    if ($(this).data('level') < 2) {
                        $(this).addClass("expanded").find(".accordeon-body:first").slideDown(600);
                    } else {
                        $(this).show().addClass("expanded").children(".accordeon-body").show();
                    }
                });
            }
        }
    }
});