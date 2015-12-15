
$(document).ready(function(){
    $(document).on('click', 'button[data-clinic_id]', function(){
        if ($("#clinic_select").select2) {
            $("#clinic_select").select2("val", $(this).data("clinic_id")).change();
        }
    });
    $('#clinicsModal').on('hidden.bs.modal', function () {
        $("#doctor_select").select2("val", "").change();
    });
});
