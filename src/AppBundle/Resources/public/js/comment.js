$(function () {
    $('#commentFormContainer')
        .on('submit', 'form', ajaxHandler)
    ;

    function ajaxHandler(event) {
        event.stopPropagation();
        event.preventDefault();

        var form = event.target;
        var $submitButton = $(form).find('input:submit');

        $submitButton.attr('disabled', 'disabled');
        $.ajax({
            url: form.action,
            type: 'POST',
            data: $(form).serialize()
        }).done(function (data) {
            $('#commentList').append(data);
            form.reset();
            $submitButton.removeAttr('disabled');
        }).catch(function (reason) {
            alert(reason);
            $submitButton.removeAttr('disabled');
        })
    }
});