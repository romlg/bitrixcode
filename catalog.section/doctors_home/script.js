
$(document).ready(function(){
    $("button[data-doctor_id]").click(function(){
        if ($("#doctor_select").select2) {
            $("#doctor_select").select2("val", $(this).data("doctor_id")).change();
        }
    });
});
