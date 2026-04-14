/* Hotel Booking — Admin JavaScript */
(function ($) {
    'use strict';

    // ─── Status Change (inline select) ───────────────────────────────────────
    $(document).on('change', '.hb-status-select', function () {
        var $select = $(this);
        var id      = $select.data('id');
        var status  = $select.val();
        var label   = $select.find('option:selected').text();

        if (status === 'cancelled' && !confirm(hbAdmin.i18n.confirm_cancel)) {
            $select.val($select.data('prev'));
            return;
        }

        $select.data('prev', status);

        $.post(hbAdmin.ajaxUrl, {
            action: 'hb_admin_update_status',
            nonce:  hbAdmin.nonce,
            id:     id,
            status: status
        }, function (res) {
            if (res.success) {
                var $row = $select.closest('tr');
                $row.find('.hb-status').remove();
                showNotice(res.data.message, 'success');
            } else {
                showNotice(res.data.message, 'error');
            }
        });
    });

    // Save previous value for rollback
    $('.hb-status-select').each(function () {
        $(this).data('prev', $(this).val());
    });

    // ─── Delete Reservation ──────────────────────────────────────────────────
    $(document).on('click', '.hb-btn-delete', function () {
        var id = $(this).data('id');
        if (!confirm(hbAdmin.i18n.confirm_delete)) return;

        var $btn = $(this);
        $btn.prop('disabled', true).text('...');

        $.post(hbAdmin.ajaxUrl, {
            action: 'hb_admin_delete',
            nonce:  hbAdmin.nonce,
            id:     id
        }, function (res) {
            if (res.success) {
                $btn.closest('tr').fadeOut(300, function () { $(this).remove(); });
            } else {
                showNotice(res.data.message, 'error');
                $btn.prop('disabled', false).text('🗑️');
            }
        });
    });

    // ─── Reservation Form (Add/Edit) ─────────────────────────────────────────
    $('#hb-reservation-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $btn  = $('#hb-save-btn');
        var $msg  = $('.hb-save-feedback');

        $btn.text(hbAdmin.i18n.saving).prop('disabled', true);
        $msg.text('').removeClass('hb-notice-success hb-notice-error');

        $.post(hbAdmin.ajaxUrl, $form.serialize(), function (res) {
            $btn.text($btn.data('original') || $btn.val()).prop('disabled', false);
            if (res.success) {
                $msg.text(res.data.message).css('color', '#16a34a');
                if (res.data.id && !$('#hb-reservation-form [name="id"]').val()) {
                    $('[name="id"]').val(res.data.id);
                    history.replaceState(null, '', '?page=hotel-booking-reservations&action=edit&id=' + res.data.id);
                }
            } else {
                $msg.text(res.data.message).css('color', '#dc2626');
            }
        });
    });

    // ─── Auto-calculate total price ──────────────────────────────────────────
    function recalcPrice() {
        var checkIn  = $('#hb-check-in').val();
        var checkOut = $('#hb-check-out').val();
        var $room    = $('#hb-room-select option:selected');
        var price    = parseFloat($room.data('price')) || 0;

        if (checkIn && checkOut && price > 0) {
            var d1 = new Date(checkIn);
            var d2 = new Date(checkOut);
            var nights = Math.max(0, Math.round((d2 - d1) / 86400000));
            if (nights > 0) {
                $('#hb-total-price').val((nights * price).toFixed(2));
            }
        }
    }

    $('#hb-check-in, #hb-check-out, #hb-room-select').on('change', recalcPrice);

    // Validate check-out > check-in
    $('#hb-check-in').on('change', function () {
        var min = $(this).val();
        if (min) $('#hb-check-out').attr('min', min);
    });

    // ─── Availability Calendar ───────────────────────────────────────────────
    var calendar = {
        month: new Date().getMonth() + 1,
        year:  new Date().getFullYear(),
        roomId: 0,

        init: function () {
            if (!$('#hb-calendar-wrap').length) return;
            this.load();

            $('#hb-cal-prev').on('click', function () {
                calendar.month--;
                if (calendar.month < 1) { calendar.month = 12; calendar.year--; }
                calendar.load();
            });
            $('#hb-cal-next').on('click', function () {
                calendar.month++;
                if (calendar.month > 12) { calendar.month = 1; calendar.year++; }
                calendar.load();
            });
            $('#hb-cal-room-filter').on('change', function () {
                calendar.roomId = $(this).val();
                calendar.load();
            });
        },

        load: function () {
            var $wrap = $('#hb-calendar-wrap');
            $wrap.html('<div class="hb-calendar-loading">Loading...</div>');

            $.post(hbAdmin.ajaxUrl, {
                action:  'hb_admin_get_availability',
                nonce:   hbAdmin.nonce,
                month:   calendar.month,
                year:    calendar.year,
                room_id: calendar.roomId
            }, function (res) {
                if (!res.success) return;
                calendar.render(res.data);
            });
        },

        render: function (data) {
            var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            var days   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            var month  = data.month;
            var year   = data.year;

            $('#hb-cal-title').text(months[month - 1] + ' ' + year);

            var firstDay = new Date(year, month - 1, 1).getDay();
            var daysInMonth = new Date(year, month, 0).getDate();
            var today = new Date();
            var todayStr = today.getFullYear() + '-' + String(today.getMonth()+1).padStart(2,'0') + '-' + String(today.getDate()).padStart(2,'0');

            // Build map: date → reservations
            var evMap = {};
            (data.reservations || []).forEach(function (r) {
                var d = new Date(r.check_in);
                var end = new Date(r.check_out);
                while (d < end) {
                    var k = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
                    evMap[k] = evMap[k] || [];
                    evMap[k].push(r);
                    d.setDate(d.getDate() + 1);
                }
            });

            var html = '<table><thead><tr>';
            days.forEach(function (d) { html += '<th>' + d + '</th>'; });
            html += '</tr></thead><tbody><tr>';

            // Blank cells
            for (var i = 0; i < firstDay; i++) html += '<td class="hb-cal-other-month"></td>';

            for (var day = 1; day <= daysInMonth; day++) {
                var dateStr = year + '-' + String(month).padStart(2,'0') + '-' + String(day).padStart(2,'0');
                var isToday = dateStr === todayStr;
                var events  = evMap[dateStr] || [];
                var cellClass = isToday ? ' hb-cal-today' : '';

                html += '<td class="hb-cal-cell' + cellClass + '">';
                html += '<div class="hb-cal-day-num">' + day + '</div>';
                events.forEach(function (r) {
                    html += '<div class="hb-cal-event status-' + r.status + '" title="' + r.guest_name + ' — ' + r.reference + '">' +
                            r.guest_name.split(' ')[0] + '</div>';
                });
                html += '</td>';

                if ((firstDay + day) % 7 === 0) html += '</tr><tr>';
            }

            html += '</tr></tbody></table>';
            $('#hb-calendar-wrap').html(html);
        }
    };

    calendar.init();

    // ─── Helper: Show inline notice ──────────────────────────────────────────
    function showNotice(msg, type) {
        var cls = 'hb-notice hb-notice-' + type;
        var $notice = $('<div class="' + cls + '">' + msg + '</div>');
        $('.hb-admin-header').after($notice);
        setTimeout(function () { $notice.fadeOut(500, function () { $(this).remove(); }); }, 3000);
    }

})(jQuery);
