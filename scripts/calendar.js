$(document).ready(function () {
    $.ajax({
        url: 'scripts/get-upcoming-data', dataType: 'json', success: function (data) {
            var events = [];

            $.each(data, function (index, value) {
                events.push({
                    name: value.name, date: value.date, id: value.id
                });
            });

            generateCalendar(events);
        }, error: function (xhr, status, error) {
            console.log('Error: ' + error);
        }
    });

    function generateCalendar(events) {

        var currentMonth = new Date().getMonth();
        var currentYear = new Date().getFullYear();
        setMonth(currentMonth, currentYear);

        $('#month-back').on('click', function () {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            setMonth(currentMonth, currentYear);
        });

        $('#month-forward').on('click', function () {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            setMonth(currentMonth, currentYear);
        });

        function setMonth(month, year) {
            $('#month').html('<h3>' + monthName(month) + ' ' + year + '</h3>');
            $('#calendar').empty();
            var firstDay = new Date(year, month, 1).getDay();
            var daysInMonth = new Date(year, month + 1, 0).getDate();
            var daysInPreviousMonth = new Date(year, month, 0).getDate();

            if (firstDay === 0) {
                firstDay = 6;
            } else {
                firstDay--;
            }

            for (var i = firstDay; i > 0; i--) {
                var dayElement = $('<div class="day previous-month">' + (daysInPreviousMonth - i + 1) + '</div>');
                $('#calendar').append(dayElement);
            }

            for (var i = 1; i <= daysInMonth; i++) {
                var dayElement = $('<div class="day">' + i + '</div>');
                var dateString = year + '-' + padNumber(month + 1) + '-' + padNumber(i);
                for (var j = 0; j < events.length; j++) {
                    if (events[j].date == dateString) {
                        dayElement.addClass('event');
                        dayElement.append('<a href="series?s=' + events[j].id + '"><div class="event-name">' + events[j].name + '</div></a>');
                    }
                }
                if (isToday(year, month, i)) {
                    dayElement.addClass('today');
                }
                $('#calendar').append(dayElement);
            }

            var remainingDays = 42 - (firstDay + daysInMonth);
            for (var i = 1; i <= remainingDays; i++) {
                var dayElement = $('<div class="day next-month">' + i + '</div>');
                $('#calendar').append(dayElement);
            }
        }


        function monthName(month) {
            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            return months[month];
        }

        function padNumber(number) {
            if (number < 10) {
                return '0' + number;
            } else {
                return number;
            }
        }

        function isToday(year, month, day) {
            var today = new Date();
            return today.getFullYear() == year && today.getMonth() == month && today.getDate() == day;
        }

    }
});