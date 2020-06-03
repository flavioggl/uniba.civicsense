function setGpsValuesToInputs(inputLat, inputLong) {
    if (inputLat.length && inputLong.length) {
        const alert = $('#alertPosition');
        const alertText = $('#alertPosition > span');
        getPosition(alertText
            , function (position) {
                inputLat.val(position.coords.latitude);
                inputLong.val(position.coords.longitude);
                setBtnMapPosition(inputLat, inputLong);
                alert.alert('close');
            }
            , function () {
                alertText.html("Impossibile ottenere la posizione.");
                if (!alert.hasClass('show')) {
                    alert.addClass('show');
                }
            });
    }
}

function setBtnMapPosition(inputLat, inputLong) {
    $('[data-target="#modalMap"]').attr('data-location', inputLat.val() + "," + inputLong.val());
}

$(function () {
    const inputLat = $('input[name="latitude"]');
    const inputLong = $('input[name="longitude"]');
    setGpsValuesToInputs(inputLat, inputLong);

    $('#btnGPS').click(function () {
        setGpsValuesToInputs(inputLat, inputLong);
    });
    inputLat.on('change', function () {
        setBtnMapPosition(inputLat, inputLong);
    });
    inputLong.on('change', function () {
        setBtnMapPosition(inputLat, inputLong);
    });
});