(function($){
    $(document).ready(function () {
        const options = easyTooltipData.options;
        new $.Zebra_Tooltips($('.' + options.class), {
            max_width: options.maxWidth,
            opacity: options.opacity,
            position: options.position
        });
    });
})(jQuery);
require('jquery');
require('./zebra_tooltips')