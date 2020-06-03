function getPosition(divError, successCallback, errorCallback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true
        });
        //{maximumAge: 50000, timeout: 20000}
    } else {
        divError.html("Geolocalizzazione non supportata del browser.");
    }
}

$(function () {
    $('#modalMap')
        .on('show.bs.modal', function (e) {
            const iframe = $(this).find('iframe');
            iframe.attr('src', null);
            let loc = $(e.relatedTarget).attr('data-location');
            iframe.attr('src', "https://maps.google.com/maps?q=" + loc + "&output=embed")
        })
        .on('hide.bs.modal', function (e) {
            $(this).find('iframe').attr('src', null);
        });

    let mapIssue = document.getElementById('mapIssue');
    if (mapIssue) {
        const map = new google.maps.Map(mapIssue, {
            zoom: 15,
            center: new google.maps.LatLng(40.8, 16.8)
        });
        let locations = $(mapIssue).data('locations');

        for (let i = 0; i < locations.length; i++) {
            let marker = new google.maps.Marker({
                position: new google.maps.LatLng(parseFloat(locations[i].latitude), parseFloat(locations[i].longitude)),
                map: map
            });
        }
    }
});