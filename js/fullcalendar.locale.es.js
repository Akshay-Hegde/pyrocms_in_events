(function($) {
    $.fullCalendar.monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $.fullCalendar.monthNamesShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    $.fullCalendar.dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    $.fullCalendar.dayNamesShort = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sab', 'Dom'];
    $.fullCalendar.buttonText = {
        prev: '&nbsp;&#9668;&nbsp;',
        next: '&nbsp;&#9658;&nbsp;',
        prevYear: '&nbsp;&lt;&lt;&nbsp;',
        nextYear: '&nbsp;&gt;&gt;&nbsp;',
        today: 'hoy',
        month: 'mes',
    };
    $.fullCalendar.titleFormat = {
        month: 'MMMM yyyy',
        week: "d [ yyyy]{ '&#8212;'[ MMM] d MMM yyyy}",
        day: 'dddd, d MMM, yyyy'
    };
    $.fullCalendar.columnFormat = {
        month: 'ddd',
        week: 'ddd d/M',
        day: 'dddd d/M'
    };
    $.fullCalendar.allDayText = 'Todo el día';
    $.fullCalendar.axisFormat = 'H:mm';
    $.fullCalendar.timeFormat = {
        '': 'H(:mm)',
        agenda: 'H:mm{ - H:mm}'
    };
})(window.jQuery);