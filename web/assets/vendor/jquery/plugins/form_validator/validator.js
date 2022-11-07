/*!
 * Validator v0.1.0
 * https://github.com/fengyuanchen/validator
 *
 * Copyright (c) 2015-2016 Fengyuan Chen
 * Released under the MIT license
 *
 * Date: 2016-04-30T03:20:27.877Z
 */

(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as anonymous module.
    define('validator', ['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node / CommonJS
    factory(require('jquery'));
  } else {
    // Browser globals.
    factory(jQuery);
  }
})(function ($) {

  'use strict';

  var NAMESPACE = 'validator';
  var EVENT_SUCCESS = 'success.' + NAMESPACE;
  var EVENT_ERROR = 'error.' + NAMESPACE;

  function isNumber(n) {
    return typeof n === 'number';
  }

  function isString(n) {
    return typeof n === 'string';
  }

  function isUndefined(n) {
    return typeof n === 'undefined';
  }

  function toArray(obj, offset) {
    var args = [];

    // This is necessary for IE8
    if (isNumber(offset)) {
      args.push(offset);
    }

    return args.slice.apply(obj, args);
  }

  function Validator(element, options) {
    this.element = element;
    this.$element = $(element);
    this.options = $.extend(true, {}, Validator.DEFAULTS, $.isPlainObject(options) && options);
    this.isCheckboxOrRadio = /checkbox|radio/.test(element.type);
    this.value = null;
    this.valid = true;
    this.init();
  }

  Validator.DEFAULTS = {
    // Type: Object
    /* e.g.: {
      minlength: 8,
      maxlength: 16
    } or {
      rangelength: [8, 16]
    } or {
      rangelength: {
        message: 'Please enter a value between 8 and 16 characters long.',
        validator: function (value) {
          return /^.{8,16}$/.test(value);
        }
      }
    } */
    rules: null,

    // The event(s) which triggers validating
    // Type: String
    trigger: '',

    // Filter the value before validate
    // Type: Function
    filter: null,

    // A shortcut of "success.validator" event
    // Type: Function
    success: null,

    // A shortcut of "error.validator" event
    // Type: Function
    error: null
  };

  Validator.PATTERNS = {
    number: /^-?\d+$/,
    email: /^[\w.!#$%&'*+\/=?^_`{|}~-]+@[\w](?:[\w-]{0,61}[\w])?(?:\.[\w](?:[\w-]{0,61}[\w])?)*$/,

    // By Scott Gonzalez: http://projects.scottsplayground.com/iri/
    url: /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,

    required: /\S+/
  };

  // validators list
  Validator.VALIDATORS = [
    // Types
    'number',
    'email',
    'url',
    'date',

    // Attributes
    'required',
    'min',
    'max',
    'minlength',
    'maxlength',
    'pattern',

    // Customs
    'range',
    'rangelength',
    'equalto'
  ];

  Validator.setDefaults = function (options) {
    $.extend(true, Validator.DEFAULTS, options);
  };

  Validator.MESSAGES = {
    // Types
    number: 'Please enter a valid number (only digits).',
    email: 'Please enter a valid email address.',
    url: 'Please enter a valid URL.',
    date: 'Please enter a valid date.',

    // Attributes
    required: 'This field is required.',
    max: 'Please enter a value less than or equal to [0].',
    min: 'Please enter a value greater than or equal to [0].',
    maxlength: 'Please enter no more than [0] characters.',
    minlength: 'Please enter at least [0] characters.',
    pattern: 'Please enter a matched value.',

    // Customs
    range: 'Please enter a value between [0] and [1].',
    rangelength: 'Please enter a value between [0] and [1] characters long.',
    equalto: 'Please enter the same value again.'
  };

  Validator.setMessages = function (options) {
    $.extend(Validator.MESSAGES, options);
  };

  Validator.prototype = {
    constructor: Validator,

    init: function () {
      this.update();
      this.bind();
    },

    bind: function () {
      var $this = this.$element;
      var options = this.options;

      if ($.isFunction(options.success)) {
        $this.on(EVENT_SUCCESS, options.success);
      }

      if ($.isFunction(options.error)) {
        $this.on(EVENT_ERROR, options.error);
      }

      if (isString(options.trigger)) {
        $this.on(options.trigger, $.proxy(this.validate, this));
      }
    },

    unbind: function () {
      var $this = this.$element;
      var options = this.options;

      if ($.isFunction(options.success)) {
        $this.off(EVENT_SUCCESS, options.success);
      }

      if ($.isFunction(options.error)) {
        $this.off(EVENT_ERROR, options.error);
      }

      if (isString(options.trigger)) {
        $this.off(options.trigger, this.validate, this);
      }
    },

    number: function (value) {
      return Validator.PATTERNS.number.test(value);
    },

    email: function (value) {
      return Validator.PATTERNS.email.test(value);
    },

    url: function (value) {
      return Validator.PATTERNS.url.test(value);
    },

    date: function (value) {
      return !isNaN(new Date(value).valueOf());
    },

    pattern: function (value, regexp) {
      return $.type(regexp) === 'regexp' && regexp.test(value);
    },

    required: function (value) {
      return this.isCheckboxOrRadio ? this.element.checked : Validator.PATTERNS.required.test(value);
    },

    min: function (value, min) {
      return parseInt(value, 10) >= min;
    },

    max: function (value, max) {
      return parseInt(value, 10) <= max;
    },

    range: function (value, range) {
      value = parseInt(value, 10);

      return $.isArray(range) && range.length === 2 && range[0] <= value && value <= range[1];
    },

    minlength: function (value, min) {
      return String(value).length >= min;
    },

    maxlength: function (value, max) {
      return String(value).length <= max;
    },

    rangelength: function (value, range) {
      var length = String(value).length;

      return $.isArray(range) && range.length === 2 && range[0] <= length && length <= range[1];
    },

    equalto: function (value, target) {
      return value === $(target).val();
    },

    // Collects attribute rules
    update: function () {
      var $this = this.$element;
      var options = this.options;
      var type = $this.attr('type');
      var validators = Validator.VALIDATORS;
      var rules = {};

      if ($.inArray(type, validators) > -1) {
        rules[type] = true;
      }

      $.each(validators, function (i, name) {
        var value = $this.attr(name);

        if (!isUndefined(value)) {
          switch (name) {
            case 'min':
            case 'max':
            case 'minlength':
            case 'maxlength':
              value = Number(value);
              break;

            case 'pattern':
              value = new RegExp(value);
              break;

            case 'range':
            case 'rangelength':
              value = value.match(/\d+/g);

              if ($.isArray(value)) {
                $.map(value, function (n) {
                  return Number(n);
                });
              } else {
                value = [];
              }

              break;
          }

          rules[name] = value;
        }

      });

      // The priority of rules option is greater than element attributes.
      options.rules = $.extend({}, rules, options.rules);
    },

    addRule: function (name, value) {
      if (isString(name)) {
        this.options.rules[name] = value;
      } else if ($.isPlainObject(name)) {
        $.each(name, $.proxy(this.addRule, this));
      }
    },

    removeRule: function (name) {
      if (isString(name)) {
        delete this.options.rules[name];
      } else if ($.isPlainObject(name)) {
        $.each(name, $.proxy(this.removeRule, this));
      }
    },

    addValidator: function (name, validator) {
      if (isString(name)) {
        if (!this.hasOwnProperty(name) && $.isFunction(validator)) {
          this[name] = validator;
          Validator.VALIDATORS.push(name);
        }
      } else if ($.isPlainObject(name)) {
        $.each(name, $.proxy(this.addValidator, this));
      }
    },

    removeValidator: function (name) {
      if (isString(name)) {
        if (this.hasOwnProperty(name) && $.isFunction(this[name])) {
          delete this[name]; // this[name] = undefined;
          $.grep(Validator.VALIDATORS, function (n) {
            return n !== name;
          });
        }
      } else if ($.isPlainObject(name)) {
        $.each(name, $.proxy(this.removeValidator, this));
      }
    },

    validate: function () {
      var $this = this.$element;
      var options = this.options;
      var value = $this.val();
      var valid = true;
      var rule = {};
      var message;

      if (!this.isCheckboxOrRadio && value === this.value) { // Not changed
        return this.valid;
      }

      this.value = value;

      if (options.filter) {
        value = options.filter.call($this[0], value);
      }

      $.each(options.rules, $.proxy(function (type, data) {
        var validator;

        if ($.isPlainObject(data)) {
          validator = data.validator;
          message = data.message;
        } else {
          validator = this[type];
          message = Validator.MESSAGES[type];
        }

        if ($.isFunction(validator)) {
          valid = validator.call(this, value, data);
        }

        rule.type = type;
        rule.data = data;

        return valid; // Breaks loop when invalid

      }, this));

      if (valid) {
        message = '';
        $this.trigger($.Event(EVENT_SUCCESS, {
          message: message,
          value: value,
          rule: rule
        }), message);
      } else {
        if (isString(message)) {
          message = message.replace(/\[\s*(\d+)\s*\]/g, function () {
            var data = rule.data;
            return $.isArray(data) ? data[arguments[1]] : data;
          });
        }

        $this.trigger($.Event(EVENT_ERROR, {
          message: message,
          value: value,
          rule: rule
        }), message);
      }

      this.valid = valid;

      return valid;
    },

    // Alias of valid, as "√"
    v: function () {
      return this.validate();
    },

    // Alias of invalid, as "×"
    x: function () {
      return !this.validate();
    },

    isValid: function () {
      return this.validate();
    },

    isInvalid: function () {
      return !this.validate();
    },

    destroy: function () {
      this.unbind();
      this.$element.removeData('validator');
    }
  };

  // Save the other validator
  Validator.other = $.fn.validator;

  // Register as jQuery plugin
  $.fn.validator = function (options) {
    var args = toArray(arguments, 1),
        result;

    this.each(function () {
      var $this = $(this),
          data = $this.data(NAMESPACE),
          fn;

      if (!data) {
        if (/destroy/.test(options)) {
          return;
        }

        $this.data(NAMESPACE, (data = new Validator(this, options)));
      }

      if (isString(options) && $.isFunction(fn = data[options])) {
        result = fn.apply(data, args);
      }
    });

    return isUndefined(result) ? this : result;
  };

  $.fn.validator.Constructor = Validator;
  $.fn.validator.setDefaults = Validator.setDefaults;
  $.fn.validator.setMessages = Validator.setMessages;
  $.fn.validator.setValidators = function (options) {
    $.extend(Validator.prototype, options);
  };

  // No conflict
  $.fn.validator.noConflict = function () {
    $.fn.validator = Validator.other;
    return this;
  };

});
