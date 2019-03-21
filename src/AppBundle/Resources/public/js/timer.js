(function ($) {
    var interval = null;

    $.fn.timer = function (timestamp) {
        if (typeof timestamp === 'number') {
            var $this = this;

            $this.text(format(timestamp));

            interval = setInterval(function () {
                $this.text(format(++timestamp));
            }, 1000);
        } else if (timestamp === null && interval !== null) {
            clearInterval(interval);
            interval = null;
        }
        return this;
    };

    function format(timestamp) {
        var seconds = timestamp % 60;
        timestamp = (timestamp - seconds) / 60;

        var minutes = timestamp % 60;
        var hours = (timestamp - minutes) / 60;

        return (hours < 10 ? '0' : '') + hours + ':' +
            (minutes < 10 ? '0' : '') + minutes + ':' +
            (seconds < 10 ? '0' : '') + seconds;
    }
})(jQuery);