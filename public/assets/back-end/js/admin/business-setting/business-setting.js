'use strict';
$(document).on('ready',function (){
    initAutocomplete()
})

$("#update-error-message").hide();

$("#update-button-message").click(function(){
    $("#update-error-message").slideDown();
});


$('#free-delivery-responsibility').on('change', function () {
    let getAmountAdminArea = $('#free-delivery-over-amount-admin-area');
    if ($(this).val() === 'admin') {
        getAmountAdminArea.fadeIn();
    } else {
        getAmountAdminArea.fadeOut();
    }
});
$('#background-color').on('change', function(){
    let background_color = $('#background-color').val();
    $('#background-color-set').text(background_color);
});
$('#text-color').on('change', function(){
    let text_color = $('#text-color').val();
    $('#text-color-set').text(text_color);
});

$('#maintenance-mode-form').on('submit', function (e) {
    e.preventDefault();
    if($('#get-application-environment-mode').data('value') !== 'demo'){
        callDemo()
        setTimeout(() => {
            location.reload();
        }, 3000);
    }

    else{
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            beforeSend: function () {
                $('#loading').fadeIn();
            },
            success: function (data) {
                toastr.success(data.message);
            },
            complete: function () {
                $('#loading').fadeOut();
            },
        });
    }
});

$('#update-settings').on('submit', function (e) {
    let minimum_add_fund_amount = parseFloat($('#minimum_add_fund_amount').val());
    let maximum_add_fund_amount = parseFloat($('#maximum_add_fund_amount').val());
    if (maximum_add_fund_amount < minimum_add_fund_amount) {
        e.preventDefault();
        toastr.error($('#get-minimum-amount-message').data('error'));
    }
});

$(document).ready(function () {
    $('#dataTable').DataTable({
        language: {
            searchPlaceholder: 'Enter Keywords'
        }
    });
});

$(document).on('click', '.edit', function () {
    let route = $(this).attr("data-id");
    $.ajax({
        url: route,
        type: "GET",
        data: {"_token": "{{ csrf_token() }}"},
        dataType: "json",
        success: function (data) {
            $("#question-filed").val(data.question);
            $("#answer-field").val(data.answer);
            $("#ranking-field").val(data.ranking);
            $("#update-form-submit").attr("action", route);
        }
    });
});

$('#software-update-form').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData(document.getElementById('software-update-form'));
    let getSoftwareUpdate = $('#get-software-update-route');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: getSoftwareUpdate.data('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('.progress').removeClass('d-none');
            $('#product_form').find('.submit').text('submitting...');
        },
        xhr: function () {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress",
                (evt) => {
                    if (evt.lengthComputable) {
                        let percentage = (evt.loaded / evt.total) * 100
                        let percentageFormatted = percentage.toFixed(0)
                        $('.progress-bar').css('width', `${percentageFormatted}%`).text(`${percentageFormatted}%`);
                    }
                }, false);
            return xhr;
        },
        success: function (response) {
        },
        complete: function () {
            location.href = getSoftwareUpdate.data('redirect-route')+'/'+$('#update_key').val()
        },
        error: function (xhr, ajaxOption, thrownError) {
        }
    });
});

async function initAutocomplete() {
    let latitude = $("#get-default-latitude").data('latitude');
    let longitude = $("#get-default-longitude").data('longitude');
    let myLatLng = {
        lat: latitude,
        lng: longitude
    };
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new google.maps.Map(document.getElementById("location-map-canvas"), {
        center: {
            lat: latitude,
            lng: longitude
        },
        zoom: 13,
        mapId: "roadmap",
    });

    var marker = new AdvancedMarkerElement({
        position: myLatLng,
        map: map,
    });

    marker.setMap(map);
    var geocoder = geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
        var coordinates = JSON.parse(coordinates);
        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
        marker.position={lat:coordinates['lat'], lng:coordinates['lng']};
        map.panTo(latlng);

        document.getElementById('latitude').value = coordinates['lat'];
        document.getElementById('longitude').value = coordinates['lng'];
        $('#showLongitude').html(coordinates['lng']);
        $('#showLatitude').html(coordinates['lat']);
        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('shop-address').value = results[1].formatted_address;
                }
            }
        });
    });

    const input = document.getElementById("map-pac-input");
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    map.addListener("bounds_changed", () => {
        searchBox.setBounds(map.getBounds());
    });
    let markers = [];
    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }
        markers.forEach((marker) => {
            marker.setMap(null);
        });
        markers = [];
        const bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
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
};
