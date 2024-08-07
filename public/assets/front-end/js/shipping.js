"use strict";

$(document).ready(function() {
    let activeId = $('.select_shipping_address.active').attr('id');
    if(activeId){
        let shipping_value = $('.selected_' + activeId).val();
        shipping_method_select(shipping_value)
    }

    let billingsActiveId = $('.select_billing_address.active').attr('id');
    if(billingsActiveId){
        let billing_value = $('.selected_' + billingsActiveId).val();
        billing_method_select(billing_value)
    }

    try {
        initializePhoneInput(".phone-input-with-country-picker-3", ".country-picker-phone-number-3");
    } catch (error) {}

    try {
        initializePhoneInput(".phone-input-with-country-picker-2", ".country-picker-phone-number-2");
    } catch (error) {}
})

let messageUpdateThisAddress = $('#message-update-this-address').data('text');
const addressItems = document.querySelectorAll('.select_shipping_address');
addressItems.forEach(item => {
    item.addEventListener('click', function () {
        const selectedAddressId = item.id;
        let shipping_value = $('.selected_' + selectedAddressId).val();
        $('.select_shipping_address').removeClass('active');
        $('#'+selectedAddressId).addClass('active')
        shipping_method_select(shipping_value)
    });
});

function shipping_method_select(get_value){
    let shipping_method_id = $('.select_shipping_address.active input[name="shipping_method_id"]').val()
    let shipping_value= JSON.parse(get_value);
    $('#name').val(shipping_value.contact_person_name);
    $('#phone').val(shipping_value.phone);
    $('#address').val(shipping_value.address);
    $('#city').val(shipping_value.city);
    $('#zip').val(shipping_value.zip);
    $('#country').val(shipping_value.country);
    $('#address_type').val(shipping_value.address_type);
    let update_address = `<input type="hidden" name="shipping_method_id" id="shipping_method_id" value="${shipping_method_id}">
            <input type="checkbox" name="update_address" id="update_address">`+ messageUpdateThisAddress;
    $('#save_address_label').html(update_address);
}

const addressItemsBilling = document.querySelectorAll('.select_billing_address');
addressItemsBilling.forEach(item => {
    item.addEventListener('click', function () {
        const selectedBillingAddressId = item.id;
        let billing_value = $('.selected_' + selectedBillingAddressId).val();
        $('.select_billing_address').removeClass('active');
        $('#'+selectedBillingAddressId).addClass('active')
        billing_method_select(billing_value);
        console.log(billing_value)
    });
});

function billing_method_select(get_billing_value){

    let billing_value= JSON.parse(get_billing_value);
    let billing_method_id = $('.select_billing_address.active input[name="billing_method_id"]').val()
    $('#billing_contact_person_name').val(billing_value.contact_person_name);
    $('#billing_phone').val(billing_value.phone);
    $('#billing_address').val(billing_value.address);
    $('#billing_city').val(billing_value.city);
    $('#billing_zip').val(billing_value.zip);
    $('#select_billing_zip').text(billing_value);
    $('#billing_country').val(billing_value.country);
    $('#billing_address_type').val(billing_value.address_type);
    let update_address_billing = `
                <input type="hidden" name="billing_method_id" id="billing_method_id" value="${billing_method_id}">
                <input type="checkbox" name="update_billing_address" id="update_billing_address">`+messageUpdateThisAddress;
    $('#save-billing-address-label').html(update_address_billing);
}

$('.add-another-address').on('click', function (){
    $('#sh-0').prop('checked', true);
    $("#collapseThree").collapse();
})

let defaultLatitudeAddressValue = $('#default-latitude-address').data('value');
let defaultLongitudeAddressValue = $('#default-longitude-address').data('value');
async function initAutocomplete() {
    var myLatLng = {
        lat: defaultLatitudeAddressValue,
        lng: defaultLongitudeAddressValue
    };
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
        center: {
            lat: defaultLatitudeAddressValue,
            lng: defaultLongitudeAddressValue
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
        coordinates = JSON.parse(coordinates);
        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
        marker.position={lat:coordinates['lat'], lng:coordinates['lng']};
        map.panTo(latlng);

        document.getElementById('latitude').value = coordinates['lat'];
        document.getElementById('longitude').value = coordinates['lng'];

        geocoder.geocode({'latLng': latlng}, function (results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('address').value = results[1].formatted_address;

                    let systemCountryRestrictStatus = $('#system-country-restrict-status').data('value');
                    if (systemCountryRestrictStatus) {
                        const countryObject = findCountryObject(results[1].address_components);
                        deliveryRestrictedCountriesCheck(countryObject.long_name, '.location-map-canvas-area', '#address')
                    }
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
}

$(document).on("keydown", "input", function (e) {
    if (e.which == 13) e.preventDefault();
})

async function initAutocompleteBilling() {
    var myLatLng = {
        lat: defaultLatitudeAddressValue,
        lng: defaultLongitudeAddressValue
    };
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const map = new google.maps.Map(document.getElementById("location_map_canvas_billing"), {
        center: {
            lat: defaultLatitudeAddressValue,
            lng: defaultLongitudeAddressValue
        },
        zoom: 13,
        mapId: "roadmap",
    });

    var marker = new AdvancedMarkerElement({
        map,
        position: myLatLng,
    });

    marker.setMap(map);
    var geocoder = geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
        coordinates = JSON.parse(coordinates);
        var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
        marker.position={lat:coordinates['lat'], lng:coordinates['lng']};
        map.panTo(latlng);

        document.getElementById('billing_latitude').value = coordinates['lat'];
        document.getElementById('billing_longitude').value = coordinates['lng'];

        geocoder.geocode({'latLng': latlng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('billing_address').value = results[1].formatted_address;

                    let systemCountryRestrictStatus = $('#system-country-restrict-status').data('value');
                    if (systemCountryRestrictStatus) {
                        const countryObject = findCountryObject(results[1].address_components);
                        deliveryRestrictedCountriesCheck(countryObject.long_name, '.location-map-billing-canvas-area', '#billing_address')
                    }
                }
            }
        });
    });


    const input = document.getElementById("pac-input-billing");
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
                document.getElementById('billing_latitude').value = this.position.lat();
                document.getElementById('billing_longitude').value = this.position.lng();

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

$(document).on("keydown", "input", function (e) {
    if (e.which == 13) e.preventDefault();
});

function checkoutFromShipping() {
    let physical_product = $('#physical_product').val();
    let billing_address_same_shipping;

    if(physical_product === 'yes') {
        let sameAsShippingCheckbox = $('#same_as_shipping_address');
        billing_address_same_shipping = sameAsShippingCheckbox ? sameAsShippingCheckbox.is(":checked") : false;

        let allAreFilled = true;
        document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
            if (!allAreFilled) return;
            if (!i.value) allAreFilled = false;
            if (i.type === "radio") {
                let radioValueCheck = false;
                document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                    if (r.checked) radioValueCheck = true;
                });
                allAreFilled = radioValueCheck;
            }
        });

        let allAreFilled_shipping = true;

        let billingAddressForm = $('#billing-address-form');
        if (billing_address_same_shipping != true && billingAddressForm.length > 0) {

            document.getElementById("billing-address-form").querySelectorAll("[required]").forEach(function (i) {
                if (!allAreFilled_shipping) return;
                if (!i.value) allAreFilled_shipping = false;
                if (i.type === "radio") {
                    let radioValueCheck = false;
                    document.getElementById("billing-address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                        if (r.checked) radioValueCheck = true;
                    });
                    allAreFilled_shipping = radioValueCheck;
                }
            });
        }
    }else {
        billing_address_same_shipping = false;
    }

    let isCheckCreateAccount = $('#is_check_create_account');
    let customerPassword = $('#customer_password');
    let customerConfirmPassword = $('#customer_confirm_password');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.post({
        url: $('#route-customer-choose-shipping-address-other').data('url'),
        data: {
            physical_product: physical_product,
            shipping: physical_product === 'yes' ? $('#address-form').serialize() : null,
            billing: $('#billing-address-form').serialize(),
            billing_addresss_same_shipping: billing_address_same_shipping,
            is_check_create_account: isCheckCreateAccount && isCheckCreateAccount.prop("checked") ? 1 : 0,
            customer_password: customerPassword ? customerPassword.val() : null,
            customer_confirm_password: customerConfirmPassword ? customerConfirmPassword.val() : null,
        },

        beforeSend: function () {
            $('#loading').show();
        },
        success: function (data) {
            // console.log(errors)
            // console.log(data.errors)
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                location.href = $('#route-checkout-payment').data('url');
            }
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (data) {
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                let error_msg = data.responseJSON.errors;
                console.log(data.responseJSON)
                toastr.error(error_msg, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    });
}

function mapsShopping() {
    try {
        initAutocomplete();
    } catch (error) {
    }
    try {
        initAutocompleteBilling();
    } catch (error) {
    }
}
