$(document).ready(function(){
    $(".datetimepicker, .datepicker").each(function(){
        if($(this).data('min-date')){
            var date = $(this).data('min-date').split('/');
            var minDate = new Date();
            minDate.setDate(date[0]); minDate.setMonth(date[1] -1); minDate.setYear(date[2]);
        }
        $(this).datetimepicker({
            format: ($(this).hasClass('datetimepicker')) ? "DD/MM/YYYY - HH:mm" : "DD/MM/YYYY",
            locale: 'fr',
            sideBySide: true,
            useCurrent: false,
            minDate: ($(this).data('min-date') && !$(this).val()) ? minDate : false
        });
    });
});
