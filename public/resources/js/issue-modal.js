$(function () {
    $('.modalIssue')
        .on('show.bs.modal', function (e) {
            let id = $(e.relatedTarget).attr('data-issue');
            $(this).find('input[name="id"]').val(id);
        })
        .on('hide.bs.modal', function (e) {
            $(this).find('input[name="id"]').val('');
        });
});