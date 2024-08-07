"use strict";
$(document).ready(function () {
    $('.select-picker').select2();
    $(document).on("keydown", "input", function (e) {
        if (e.which === 13) {
            e.preventDefault();
        }
    });
});
async function initAutoComplete() {
    let latitude = $('#address-latitude').data('latitude')
    let longitude = $('#address-longitude').data('longitude')
    let myLatLng = {
        lat: latitude,
        lng: longitude,
    };
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
        center: myLatLng,
        zoom: 13,
        mapId: 'roadmap'
    });

    let marker = new AdvancedMarkerElement({
        map,
        position: myLatLng,
    });

    marker.setMap(map);
    var geocoder = geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        var coordinate = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
        var coordinates = JSON.parse(coordinate);
        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
        marker.position={lat:coordinates['lat'], lng:coordinates['lng']};
        map.panTo(latlng);

        document.getElementById('latitude').value = coordinates['lat'];
        document.getElementById('longitude').value = coordinates['lng'];

        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('address').value = results[1].formatted_address;
                }
            }
        });
    });

    const input = document.getElementById("pac-input");

    const searchBox = new google.maps.places.SearchBox(input);

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });
    let markers = [];
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }
        markers.forEach((marker) => {
            marker.setMap(null);
        });
        markers = [];

        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                return;
            }
            var mrkr = new AdvancedMarkerElement({
                map,
                title: place.name,
                position: place.geometry.location,
            });

            google.maps.event.addListener(mrkr, "click", function (event) {
                document.getElementById('latitude').value = this.position.lat();
                document.getElementById('longitude').value = this.position.lng();

            });

            markers.push(mrkr);

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}
function callBackFunction(){
    initAutoComplete();
}
$(document).on("keydown", "input", function (e) {
    if (e.which === 13) e.preventDefault();
});
