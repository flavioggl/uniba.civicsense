$(function () {

    $('.collapse').each(function () {
        const icon = $('[data-target="#' + $(this).attr('id') + '"').find('i.fas');
        if (!icon) return;
        $(this)
            .on('show.bs.collapse', function () {
                icon.removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
            })
            .on('hide.bs.collapse', function () {
                icon.addClass('fa-chevron-circle-down').removeClass('fa-chevron-circle-up');
            });
    });

    $('.checkall').change(function () {
        if ($(this).prop('checked')) {
            $('input[type="checkbox"]').prop('checked', true);
        } else {
            $('input[type="checkbox"]').prop('checked', false);
        }
    });

    $('#team-add-btn').click(function () {
        const btn = $(this);
        const index = btn.data('next-index');
        const form = $(this).parents('form');
        $.ajax({
            url: "/specialist/add",
            type: "post",
            data: {'index': index},
            beforeSend: function () {
                btn.attr('disabled', true);
            }
        }).done(function (html) {
            form.append(html);
            btn.data('next-index', index + 1);
        }).always(function () {
            btn.attr('disabled', false);
        });
    });
});

function btnCloseDelete(btn) {
    $(btn).parents('div.card-specialist').remove();
}