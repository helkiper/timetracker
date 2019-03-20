$(function () {
    const CONTENT_UPDATED_EVENT = 'content_updated';
    const $_TIMER_BUTTON = $('#timerButton');
    var timerActive = false;  //TODO replace to current task
    var clickAndSubmitAvailable = true;

    $('#content')
        .on('click', 'a', AJAXEventHandler)
        .on('submit', 'form', AJAXEventHandler)
        .on(CONTENT_UPDATED_EVENT, contentUpdateHandler)
    ;

    function AJAXEventHandler(event) {
        event.stopPropagation();
        event.preventDefault();

        if (clickAndSubmitAvailable) {
            var target = $(event.target);
            var options = {dataType: 'html'};

            clickAndSubmitAvailable = false;

            if (target.is('a')) {
                options.url = target.attr('href');
            } else if (target.is('form')) {
                options.url = target.attr('action');
                options.type = target.attr('method');
                options.data = target.serialize();
            }

            $.ajax(options).done(function (data) {
                $('#content')
                    .html(data)
                    .trigger(CONTENT_UPDATED_EVENT)
                ;
                clickAndSubmitAvailable = true;
            }).catch(function (reason) {
                alert(reason);
                clickAndSubmitAvailable = true;
            });
        }
    }

    function contentUpdateHandler() {
        if (!timerActive) {
            if ($('#content').is(':has(#show-task-page-indicator)')) {
                $_TIMER_BUTTON.show();
            } else {
                $_TIMER_BUTTON.hide();
            }
        }
    }
});