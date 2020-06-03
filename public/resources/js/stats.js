$(function () {
    $('.chart').each(function (e) {
        const data = $(this).data('json');
        new Chart($(this), {
            type: 'doughnut',
            data: data,
            options: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 20
                    }
                }
            }
        });
    });
});