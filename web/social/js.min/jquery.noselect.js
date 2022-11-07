(function($){

$.fn.disableSelection = function() {
    return this.each(function() {           
        $(this).attr('unselectable', 'on')
               .css({
                   '-moz-user-select':'none',
                   '-webkit-user-select':'none',
                   'user-select':'none',
                   '-ms-user-select':'none'
               })
               .each(function() {
                   this.onselectstart = function() { return false; };
               });
    });
};

$.fn.enableSelection = function() {
    return this.each(function() {           
        $(this).removeAttr('unselectable')
               .css({
                   '-moz-user-select':'',
                   '-webkit-user-select':'',
                   'user-select':'',
                   '-ms-user-select':''
               })
               .each(function() {
                   this.onselectstart = function() { return true; };
               });
    });
};

})(jQuery);