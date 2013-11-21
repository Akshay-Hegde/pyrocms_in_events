(function($) {
    var calendar = '#in-events-calendar',
        show_map = '[name="show_map"]',
        map_container = 'label[for="map"]';

    function check_map_container() {
        var $map_container = $(map_container).parent().hide(),
            value = parseInt($(this).val());

        if (value === 1) {
            $map_container.show();

            if (map) {
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center);
            }

        }
    }

    $(document).on('change', show_map, check_map_container);

    $(document).on('keyup', '[name="place"]', function() {
        $('#map_input').val($(this).val()).trigger('keyup');
    });

    $(function() {
        check_map_container.call($(show_map + ':checked'));

        if ($(calendar).length) {
            $(calendar).fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                events: SITE_URL + 'admin/in_events/load'
            });
        }

        $('#datepicker_start_date, #datepicker_end_date').datepicker({
            minDate: 0,
            dateFormat: 'yy-mm-dd'
        });
    });
})(window.jQuery);