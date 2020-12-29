$(function() {
    $('.user-role-selector').change(function() {
        var roleSelector = $(this);
        var data = {
            id: roleSelector.data('id'),
            role: roleSelector.val()
        };
        $.ajax({
            async: true,
            type: "POST",
            url: $('#user-container').data('url'),
            data: data,
            cache: false,
            global: false,
            success: function () {
                toastr.success('Роль успешно изменена');
            },
            error: toastrError
        });
    });
});