 /** 
  * Version 1.0
  * March 27, 2010
  *
  * Copyright (c) 2010 Wesley Bakker
  * Licensed under the GPL licenses.
  * http://www.gnu.org/licenses/gpl.txt
  **/
(function($) {
    var methods = {
        getOptionValue: function(value, elem, event) {
            if ($.isFunction(value)) {
                value = value.call(elem, event);
            }

            return value;
        },
        getCategory: function() {
            return this.nodeName;
        },
        getAction: function(event) {
            return event.type;
        },
        getLabel: function() {
            var self = $(this);
            if (self.is("a")) {
                return self.attr("href");
            }
            else if (self.is("input")) {
                return self.val();
            }
            else if (self.attr("id")) {
                return self.attr("id");
            }
            else{
                return self.text();
            }
        }
    };

    $.expr[':'].external = function(elem) {
        return (elem.host && elem.host !== location.host) === true;
    };

    $.fn.trackEvent = function(options) {
        var settings = {
            eventType : "click",
            once      : true,
            category  : methods.getCategory,
            action    : methods.getAction,
            label     : methods.getLabel,
            value     : 1
        };

        if (options) $.extend(settings, options);

        this.each(function(i) {
            var eventHandler = function(event) {
                var category = methods.getOptionValue(settings.category, this, event);
                var action   = methods.getOptionValue(settings.action  , this, event);
                var label    = methods.getOptionValue(settings.label   , this, event);
                var value    = methods.getOptionValue(settings.value   , this, event);

                //alert(category + "||" + action + "||" + label + "||" + value);
                pageTracker._trackEvent(category, action, label, value);
            };

            if (settings.once) {
                $(this).one(settings.eventType, eventHandler);
            }
            else {
                $(this).bind(settings.eventType, eventHandler);
            }
        });

        return this;
    };
})(jQuery);