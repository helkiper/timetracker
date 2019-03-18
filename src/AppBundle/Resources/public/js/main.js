$(function () {
    $('#content')
        .on('click', 'a', AJAXEventHandler)
        .on('submit', 'form', AJAXEventHandler);

    function AJAXEventHandler(event) {
        event.stopPropagation();
        event.preventDefault();

        var target = $(event.target);
        var options = {dataType: 'html'};

        if (target.is('a')) {
            options.url = target.attr('href');
        } else if (target.is('form')) {
            options.url = target.attr('action');
            options.type = target.attr('method');
            options.data = target.serialize();
        }

        $.ajax(options).done(function (data) {
            $('#content').html(data);
        }).catch(function (reason) {
            alert(reason);
        });
    }
});