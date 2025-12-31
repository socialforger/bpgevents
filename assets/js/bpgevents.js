(function($){

    /**
     * Init single event map (if needed)
     */
    window.BPGE_init_map = function(mapId, lat, lng, zoom, markerIcon, markerShadow) {

        if (typeof L === 'undefined') {
            return;
        }

        var map = L.map(mapId).setView([lat, lng], zoom || 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var markerOptions = {};

        if (markerIcon) {
            markerOptions.icon = L.icon({
                iconUrl: markerIcon,
                shadowUrl: markerShadow || '',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        }

        L.marker([lat, lng], markerOptions).addTo(map);

        return map;
    };

    /**
     * Init cluster map for multiple markers
     */
    window.BPGE_init_cluster_map = function(mapId, markers, tileLayerUrl) {

        if (typeof L === 'undefined' || typeof L.markerClusterGroup === 'undefined') {
            return;
        }

        var map = L.map(mapId).setView([0, 0], 2);

        L.tileLayer(tileLayerUrl || 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var clusterGroup = L.markerClusterGroup();

        markers.forEach(function(item){
            var marker = L.marker([item.lat, item.lng]);
            marker.bindPopup('<a href="' + item.url + '">' + item.title + '</a>');
            clusterGroup.addLayer(marker);
        });

        map.addLayer(clusterGroup);

        if (markers.length > 0) {
            map.fitBounds(clusterGroup.getBounds());
        }

        return map;
    };

    /**
     * Placeholder for future AJAX join/leave handlers, etc.
     */
    $(document).on('click', '.bpge-join-event, .bpge-leave-event', function(e){
        e.preventDefault();
        // Qui potrai inserire la logica di partecipazione via AJAX
    });

})(jQuery);
