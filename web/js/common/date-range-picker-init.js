var initDateRangePicker = function(element){
    element.daterangepicker({
        startDate: moment().subtract(7, 'days'),
        endDate: moment(),
        autoUpdateInput: false,
        ranges: {
            'Aujourd\'hui': [moment(), moment()],
            'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Cette semaine': [moment().startOf('week').add(1, 'day'), moment().endOf('week').add(1, 'day')],
            'Les 7 derniers jours': [moment().subtract(6, 'days'), moment()],
            'Les 30 derniers jours': [moment().subtract(29, 'days'), moment()],
            'Ce mois': [moment().startOf('month'), moment().endOf('month')],
            'Le mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Ce mois N-1': [moment().subtract(365, 'days').startOf('month'), moment().subtract(365, 'days').endOf('month')],
            'Cette année': [moment().startOf('year'), moment().endOf('year')],
            'L\'année dernière': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
        },
        locale: {
            format: "DD/MM/YYYY",
            separator: " - ",
            applyLabel: "Appliquer",
            cancelLabel: "Annuler",
            fromLabel: "De",
            toLabel: "À",
            customRangeLabel: "Personnalisé",
            weekLabel: "S",
            daysOfWeek: [
                "Di",
                "Lu",
                "Ma",
                "Me",
                "Je",
                "Ve",
                "Sa"
            ],
            monthNames: [
                "Janvier",
                "Février",
                "Mars",
                "Avril",
                "Mai",
                "Juin",
                "Juillet",
                "Août",
                "Septembre",
                "Octobre",
                "Novembre",
                "Décembre"
            ],
            firstDay: 1
        }
    }, function(start, end, label){
        element.val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        element.trigger('change');
    });
}

$(document).ready(function(){
    $('.date-range-picker').each(function(){
        initDateRangePicker($(this));
    });
});