$(function () {
    const SELECTORS = {
        content: '#content',
        timer: '#timer',
        timerContainer: '#timerContainer',
        timerStartButton: '#timerStartButton',
    };
    const ROUTES = {
        taskIndex: 'task_index',
        timerState: 'timer_state',
        timerStart: 'timer_start'
    };
    var clickAndSubmitAvailable = true;
    var lastContentAjaxData;

    init();

    function init() {
        $(SELECTORS.content)
            .on('click', 'a', ContentClickSubmitEventHandler)
            .on('submit', 'form', ContentClickSubmitEventHandler)
        ;

        $(SELECTORS.timerContainer)
            .on('click', 'a', timerFormSubmitHandler)
        ;

        ajax({url: Routing.generate(ROUTES.timerState)}, SELECTORS.timerContainer);
        ajax({'url': Routing.generate(ROUTES.taskIndex)}, SELECTORS.content, true);

    }

    function ajax(options, containerSelector, shouldRefreshLastAjaxData=false)
    {
        clickAndSubmitAvailable = false;

        $.ajax(options).done(function (data) {
            $(containerSelector).html(data.content);

            if (shouldRefreshLastAjaxData) {
                lastContentAjaxData = data;
            }
            if (data.timerData !== undefined) {
                updateTimer(data.timerData);
            }

            $(SELECTORS.timerContainer).find('a').button();
            taskShowPageLoadedChecker();
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

            ajax(options, SELECTORS.timerContainer);
        }
    }

    function ContentClickSubmitEventHandler(event) {
        event.stopPropagation();
        event.preventDefault();

        if (clickAndSubmitAvailable) {
            var target = $(event.target);
            var options = {dataType: 'json'};

            if (target.is('a')) {
                options.url = target.attr('href');
            } else if (target.is('form')) {
                options.url = target.attr('action');
                options.type = target.attr('method');
                options.data = target.serialize();
            }

            ajax(options, SELECTORS.content, true);
        }
    }

    function taskShowPageLoadedChecker() {
        if (taskShowPageLoadedInContent()) {
            $(SELECTORS.timerStartButton)
                .attr(
                    'href',
                    Routing.generate(ROUTES.timerStart, {'task': lastContentAjaxData.shownTaskId})
                );
            $(SELECTORS.timerStartButton).show();
        } else {
            $(SELECTORS.timerStartButton).hide();
        }
    }

    function updateTimer(data) {
        if (data.value !== undefined) {
            $(SELECTORS.timer).timer(data.value);
        } else if (data.stop) {
            $(SELECTORS.timer).timer(null);
        }
    }

    function taskShowPageLoadedInContent() {
        return lastContentAjaxData
            && lastContentAjaxData.shownTaskId !== undefined
            && lastContentAjaxData.shownTaskId !== null;
    }
});