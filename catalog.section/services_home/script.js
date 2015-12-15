
$(document).ready(function(){
    $(document).on("click", ".__direction_service", function(e){
        if ($.ONAddService) {
            e.stopPropagation();
            e.preventDefault();
            $.ONAddService($(this).data('service-id'), $(this).data('service-name'), $(this).data('service-price'));

            var tabc = $(this).closest('#tabs');
            if (tabc) {
                tabc = tabc.parent().parent();
                if (tabc) {
                    tabc = tabc.find('.uslugi_list');
                    if (tabc) {
                        var offset = tabc.offset();
                        if (offset) {
                            $('html, body').animate({
                                scrollTop: tabc.offset().top
                            }, 500);
                        }
                    }
                }
            }
            return false;
        }
    });
});
