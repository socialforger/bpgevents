(function($){

    /**
     * JOIN / LEAVE EVENT (AJAX)
     */
    $(document).on('click', '.bpge-join-event, .bpge-leave-event', function(e){
        e.preventDefault();

        var button = $(this);
        var eventID = button.data('event');

        $.ajax({
            url: bpgevents_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'bpgevents_toggle_participation',
                event_id: eventID
            },
            beforeSend: function(){
                button.addClass('loading');
            },
            success: function(response){

                button.removeClass('loading');

                if (!response || !response.success) return;

                // Update button label
                if (response.data.joined) {
                    button.removeClass('bpge-join-event')
                          .addClass('bpge-leave-event')
                          .text(bpgevents_ajax.leave_label);
                } else {
                    button.removeClass('bpge-leave-event')
                          .addClass('bpge-join-event')
                          .text(bpgevents_ajax.join_label);
                }

                // Update participants count
                if (response.data.participants !== undefined) {
                    $('.bpge-participants-count').text(
                        bpgevents_ajax.participants_label.replace('%d', response.data.participants)
                    );
                }
            }
        });
    });


    /**
     * LEAFLET MAP INITIALIZATION
     */
    window.BPGE_init_map = function(containerID, lat, lng, zoom, markerIconURL, shadowURL) {

        if (!document.getElementById(containerID)) return;

        var map = L.map(containerID).setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var icon = L.icon({
            iconUrl: markerIconURL,
            shadowUrl: shadowURL,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            shadowSize: [41, 41]
        });

        L.marker([lat, lng], { icon: icon }).addTo(map);

        return map;
    };

})(jQuery);
