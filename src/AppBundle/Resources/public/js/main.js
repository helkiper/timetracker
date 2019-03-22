$(function () {
    const SELECTORS = {
        timer: '#timer',
        timerContainer: '#timerContainer',
    };
    const TIMER_STATE_ROUTE = 'timer_state';
    var clickAndSubmitAvailable = true;
    var initialTimerContainerHtml;

    init();

    function init() {
        initialTimerContainerHtml = $(SELECTORS.timerContainer).html();

        $(SELECTORS.timerContainer)
            .on('click', 'a', timerFormSubmitHandler)
        ;

        ajax({url: Routing.generate(TIMER_STATE_ROUTE)});
    }

    function ajax(options)
    {
        clickAndSubmitAvailable = false;

        $.ajax(options).done(function (data) {
            $(SELECTORS.timerContainer)
                .html(data.content ? data.content : initialTimerContainerHtml);
            if (data.timerData !== undefined) {
                updateTimer(data.timerData);
            }

            $(SELECTORS.timerContainer).find('a').button();
            clickAndSubmitAvailable = true;
        }).catch(function (reason) {
            alert(reason);
            clickAndSubmitAvailable = true;
        });
    }

    function timerFormSubmitHandler(event) {
        event.stopPropagation();
        event.preventDefault();

        if (clickAndSubmitAvailable) {
            var target = $(event.target);
            var options = {
                url: target.attr('href'),
                dataType: 'json'
            };

            ajax(options);
        }
    }

    function updateTimer(data) {
        if (data.value !== undefined) {
            $(SELECTORS.timer).timer(data.value);
        } else if (data.stop) {
            $(SELECTORS.timer).timer(null);
        }
    }
});