/* Hotel Booking — Public JavaScript */
(function ($) {
    'use strict';

    var booking = {
        step: 1,
        data: {
            check_in:  '',
            check_out: '',
            adults:    1,
            children:  0,
            nights:    0,
            room:      null,
            guest:     {},
        },

        init: function () {
            if (!$('#hb-booking-widget').length) return;
            this.bindSearch();
            this.bindNavigation();
            this.bindGuestForm();
            this.bindConfirm();
            this.initDateValidation();
        },

        // ── Step 1: Search ──────────────────────────────────────────────────
        bindSearch: function () {
            $('#hb-search-form').on('submit', function (e) {
                e.preventDefault();
                booking.searchRooms();
            });
        },

        searchRooms: function () {
            var $btn  = $('#hb-search-btn');
            var $err  = $('#hb-search-error');

            $btn.prop('disabled', true);
            $btn.find('.hb-btn-text').text(hbPublic.i18n.checking);
            $btn.find('.hb-btn-spinner').show();
            $err.text('');

            var formData = $('#hb-search-form').serialize();

            $.post(hbPublic.ajaxUrl, formData, function (res) {
                $btn.prop('disabled', false);
                $btn.find('.hb-btn-text').text($btn.find('.hb-btn-text').data('orig') || 'Check Availability');
                $btn.find('.hb-btn-spinner').hide();

                if (res.success) {
                    booking.data.check_in  = res.data.check_in;
                    booking.data.check_out = res.data.check_out;
                    booking.data.adults    = res.data.adults;
                    booking.data.children  = res.data.children;
                    booking.data.nights    = res.data.nights;
                    booking.renderRooms(res.data.rooms);
                    booking.goTo(2);
                } else {
                    $err.text(res.data.message);
                }
            }).fail(function () {
                $btn.prop('disabled', false);
                $btn.find('.hb-btn-spinner').hide();
                $err.text(hbPublic.i18n.error);
            });
        },

        renderRooms: function (rooms) {
            var $list = $('#hb-rooms-list');
            var d     = booking.data;
            var nights = d.nights;

            // Update summary bar
            var checkInFmt  = new Date(d.check_in  + 'T00:00').toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'});
            var checkOutFmt = new Date(d.check_out + 'T00:00').toLocaleDateString('en-GB', {day:'2-digit', month:'short', year:'numeric'});
            $('#hb-dates-summary').html(
                '<strong>' + checkInFmt + '</strong> → <strong>' + checkOutFmt + '</strong> · ' +
                nights + ' night' + (nights > 1 ? 's' : '') + ' · ' +
                d.adults + ' adult' + (d.adults > 1 ? 's' : '') +
                (d.children > 0 ? ' + ' + d.children + ' child' + (d.children > 1 ? 'ren' : '') : '')
            );

            if (!rooms.length) {
                $list.html('<p class="hb-no-rooms">' + hbPublic.i18n.no_rooms + '</p>');
                return;
            }

            var html = '';
            rooms.forEach(function (room) {
                var amenities = (room.amenities || []).slice(0, 5).map(function (a) {
                    return '<span class="hb-amenity-tag">' + a + '</span>';
                }).join('');

                var meta = [];
                if (room.adults)   meta.push('👥 ' + room.adults + ' adults');
                if (room.size)     meta.push('📐 ' + room.size + 'm²');
                if (room.bed_type) meta.push('🛏 ' + room.bed_type.charAt(0).toUpperCase() + room.bed_type.slice(1));
                if (room.view)     meta.push('🏞 ' + room.view);

                html += '<div class="hb-room-card" data-room-id="' + room.id + '">' +
                    '<div class="hb-room-img">' +
                        (room.thumbnail ? '<img src="' + room.thumbnail + '" alt="' + room.title + '" loading="lazy">' : '') +
                    '</div>' +
                    '<div class="hb-room-info">' +
                        '<h4>' + room.title + '</h4>' +
                        (room.excerpt ? '<p class="hb-room-excerpt">' + room.excerpt + '</p>' : '') +
                        '<div class="hb-room-meta">' + meta.join('') + '</div>' +
                        '<div class="hb-room-amenities">' + amenities + '</div>' +
                    '</div>' +
                    '<div class="hb-room-pricing">' +
                        '<div class="hb-room-price-per-night">' +
                            '<span class="hb-price-amount">' + hbPublic.currency + room.price_per_night.toFixed(0) + '</span>' +
                            '<span class="hb-price-label">/night</span>' +
                        '</div>' +
                        '<div class="hb-price-total">' + nights + ' nights = ' + hbPublic.currency + room.total_price.toFixed(2) + '</div>' +
                        '<button class="hb-select-room-btn" data-room-id="' + room.id + '">Select →</button>' +
                    '</div>' +
                '</div>';
            });

            $list.html(html);

            // Store room data
            $list.find('.hb-room-card').each(function () {
                var id = $(this).data('room-id');
                $(this).data('room-obj', rooms.find(function (r) { return r.id === id; }));
            });
        },

        // ── Step 2: Room selection ──────────────────────────────────────────
        bindNavigation: function () {
            $(document).on('click', '.hb-select-room-btn', function (e) {
                e.stopPropagation();
                var $card = $(this).closest('.hb-room-card');
                booking.data.room = $card.data('room-obj');
                booking.updateBookingBar();
                booking.goTo(3);
            });

            $(document).on('click', '.hb-room-card', function () {
                var $card = $(this);
                booking.data.room = $card.data('room-obj');
                $card.find('.hb-select-room-btn').trigger('click');
            });

            $('#hb-back-to-1').on('click', function () { booking.goTo(1); });
            $('#hb-back-to-2').on('click', function () { booking.goTo(2); });
            $('#hb-back-to-3').on('click', function () { booking.goTo(3); });
        },

        updateBookingBar: function () {
            var d    = booking.data;
            var room = d.room;
            if (!room) return;

            var ci = new Date(d.check_in  + 'T00:00').toLocaleDateString('en-GB', {day:'2-digit', month:'short'});
            var co = new Date(d.check_out + 'T00:00').toLocaleDateString('en-GB', {day:'2-digit', month:'short'});

            $('#hb-booking-bar').html(
                '<div class="hb-sum-item"><div class="hb-sum-label">Room</div><div class="hb-sum-value">' + room.title + '</div></div>' +
                '<div class="hb-sum-item"><div class="hb-sum-label">Check-in</div><div class="hb-sum-value">' + ci + '</div></div>' +
                '<div class="hb-sum-item"><div class="hb-sum-label">Check-out</div><div class="hb-sum-value">' + co + '</div></div>' +
                '<div class="hb-sum-item"><div class="hb-sum-label">Nights</div><div class="hb-sum-value">' + d.nights + '</div></div>' +
                '<div class="hb-sum-item"><div class="hb-sum-label">Total</div><div class="hb-sum-value">' + hbPublic.currency + room.total_price.toFixed(2) + '</div></div>'
            );
        },

        // ── Step 3: Guest details ───────────────────────────────────────────
        bindGuestForm: function () {
            $('#hb-guest-form').on('submit', function (e) {
                e.preventDefault();
                var $err = $('#hb-guest-error');
                $err.text('');

                var name  = $.trim($('#hb-guest-name').val());
                var email = $.trim($('#hb-guest-email').val());

                if (!name || !email) {
                    $err.text(hbPublic.i18n.fill_required);
                    return;
                }

                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $err.text(hbPublic.i18n.invalid_email);
                    return;
                }

                booking.data.guest = {
                    name:  name,
                    email: email,
                    phone: $.trim($('#hb-guest-phone').val()),
                    notes: $.trim($('#hb-notes').val()),
                };

                booking.renderConfirmation();
                booking.goTo(4);
            });
        },

        renderConfirmation: function () {
            var d    = booking.data;
            var room = d.room;
            var g    = d.guest;

            var ci = new Date(d.check_in  + 'T00:00').toLocaleDateString('en-GB', {weekday:'short', day:'2-digit', month:'short', year:'numeric'});
            var co = new Date(d.check_out + 'T00:00').toLocaleDateString('en-GB', {weekday:'short', day:'2-digit', month:'short', year:'numeric'});

            var rows = [
                ['Room',       room.title],
                ['Guest',      g.name],
                ['Email',      g.email],
                ['Phone',      g.phone || '—'],
                ['Check-in',   ci],
                ['Check-out',  co],
                ['Nights',     d.nights],
                ['Guests',     d.adults + ' adults' + (d.children ? ' + ' + d.children + ' children' : '')],
            ];

            var html = rows.map(function (r) {
                return '<div class="hb-confirm-row"><span>' + r[0] + '</span><strong>' + r[1] + '</strong></div>';
            }).join('');

            html += '<div class="hb-confirm-row hb-total-row"><span>Total</span><strong>' + hbPublic.currency + room.total_price.toFixed(2) + '</strong></div>';

            $('#hb-confirm-summary').html(html);
        },

        // ── Step 4: Confirm & Book ──────────────────────────────────────────
        bindConfirm: function () {
            $('#hb-book-btn').on('click', function () {
                var $err = $('#hb-confirm-error');
                $err.text('');

                if (!$('#hb-terms-check').is(':checked')) {
                    $err.text('Please accept the Terms & Conditions to proceed.');
                    return;
                }

                booking.submitBooking();
            });
        },

        submitBooking: function () {
            var $btn = $('#hb-book-btn');
            var d    = booking.data;
            var room = d.room;
            var g    = d.guest;

            $btn.prop('disabled', true);
            $btn.find('.hb-btn-text').text(hbPublic.i18n.booking);
            $btn.find('.hb-btn-spinner').show();

            var lang = $('html').attr('lang') || 'en';
            lang = lang.startsWith('de') ? 'de' : 'en';

            $.post(hbPublic.ajaxUrl, {
                action:       'hb_create_booking',
                nonce:        hbPublic.nonce,
                room_id:      room.id,
                check_in:     d.check_in,
                check_out:    d.check_out,
                adults:       d.adults,
                children:     d.children,
                guest_name:   g.name,
                guest_email:  g.email,
                guest_phone:  g.phone,
                notes:        g.notes,
                lang:         lang,
            }, function (res) {
                $btn.prop('disabled', false);
                $btn.find('.hb-btn-spinner').hide();

                if (res.success) {
                    $('#hb-success-ref').text(res.data.reference);
                    booking.goTo('success');
                    // Scroll to success
                    $('html,body').animate({ scrollTop: $('#hb-booking-widget').offset().top - 40 }, 400);
                } else {
                    $('#hb-confirm-error').text(res.data.message);
                    $btn.find('.hb-btn-text').text('✓ Confirm & Book');
                }
            }).fail(function () {
                $btn.prop('disabled', false);
                $btn.find('.hb-btn-text').text('✓ Confirm & Book');
                $btn.find('.hb-btn-spinner').hide();
                $('#hb-confirm-error').text(hbPublic.i18n.error);
            });
        },

        // ── Navigation ──────────────────────────────────────────────────────
        goTo: function (step) {
            // Hide all panels
            $('.hb-panel').hide();

            if (step === 'success') {
                $('#hb-success').fadeIn(350);
                booking.updateSteps(5);
                return;
            }

            $('#hb-step-' + step).fadeIn(350);
            booking.step = step;
            booking.updateSteps(step);
        },

        updateSteps: function (active) {
            $('.hb-step').each(function () {
                var s = parseInt($(this).data('step'));
                $(this).removeClass('hb-step-active hb-step-done');
                if (s === active) {
                    $(this).addClass('hb-step-active');
                } else if (s < active) {
                    $(this).addClass('hb-step-done');
                }
            });
        },

        // ── Date Validation ─────────────────────────────────────────────────
        initDateValidation: function () {
            var $in  = $('#hb-check-in');
            var $out = $('#hb-check-out');

            $in.on('change', function () {
                var val = $(this).val();
                if (val) {
                    $out.attr('min', val);
                    if ($out.val() && $out.val() <= val) {
                        $out.val('');
                    }
                }
            });
        }
    };

    $(function () {
        booking.init();

        // Save button text
        $('#hb-search-btn .hb-btn-text').data('orig', $('#hb-search-btn .hb-btn-text').text());
    });

})(jQuery);
