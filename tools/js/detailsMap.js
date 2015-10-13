/*var delay = 100;
var infowindow = new google.maps.InfoWindow();
var myCenter = new google.maps.LatLng(20.593684, 78.96288000000004);
var mapOptions = {
    zoom: 4,
    center: myCenter,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};
var geo = new google.maps.Geocoder();
var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);
var bounds = new google.maps.LatLngBounds();


function getAddress(search, next) {
    geo.geocode({address: search}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            var p = results[0].geometry.location;
            var lat = p.lat();
            var lng = p.lng();
            createMarker(search, lat, lng);
        }
        else {
            if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                nextAddress--;
                delay++;
            }
        }
        next();
    });
}

function createMarker(add, lat, lng) {
    var contentString = add;
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        map: map,
        zIndex: Math.round(myCenter.lat() * -100000) << 5
    });

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString);
        infowindow.open(map, marker);
    });

    bounds.extend(marker.position);

}

var addresses = [
    "Chennai", "New Delhi", "Kolkata ", "Mumbai", "Hyderabad", "Bangalore"];var nextAddress = 0;

function theNext() {
    if (nextAddress < addresses.length) {
        setTimeout('getAddress("' + addresses[nextAddress] + '",theNext)', delay);
        nextAddress++;
    } else {
        map.fitBounds(bounds);
    }
}
theNext();*/