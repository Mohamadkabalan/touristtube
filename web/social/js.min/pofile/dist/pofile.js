require=(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({"W8CkM0":[function(require,module,exports){
var fs = require('fs');
var isArray = require('lodash.isarray');

function trim(string) {
    return string.replace(/^\s+|\s+$/g, '');
}

var PO = function () {
    this.comments = [];
    this.headers = {};
    this.items = [];
};

PO.prototype.save = function (filename, callback) {
    fs.writeFile(filename, this.toString(), callback);
};

PO.prototype.toString = function () {
    var lines = [];

    if (this.comments) {
        this.comments.forEach(function (comment) {
            lines.push('# ' + comment);
        });
    }

    lines.push('msgid ""');
    lines.push('msgstr ""');

    var keys = Object.keys(this.headers);
    var self = this;
    keys.forEach(function (key) {
        lines.push('"' + key + ': ' + self.headers[key] + '\\n"');
    });

    lines.push('');

    this.items.forEach(function (item) {
        lines.push(item.toString());
        lines.push('');
    });

    return lines.join('\n');
};

PO.load = function (filename, callback) {
    fs.readFile(filename, 'utf-8', function (err, data) {
        if (err) {
            return callback(err);
        }
        var po = PO.parse(data);
        callback(null, po);
    });
};

PO.parse = function (data) {
    //support both unix and windows newline formats.
    data = data.replace(/\r\n/g, '\n');
    var po = new PO();
    var sections = data.split(/\n\n/);
    var headers = sections.shift();
    var lines = sections.join('\n').split(/\n/);

    po.headers = {
        'Project-Id-Version': '',
        'Report-Msgid-Bugs-To': '',
        'POT-Creation-Date': '',
        'PO-Revision-Date': '',
        'Last-Translator': '',
        'Language': '',
        'Language-Team': '',
        'Content-Type': '',
        'Content-Transfer-Encoding': '',
        'Plural-Forms': '',
    };

    headers.split(/\n/).reduce(function (acc, line) {
        if (acc.merge) {
            //join lines, remove last resp. first "
            line = acc.pop().slice(0, -1) + line.slice(1);
            delete acc.merge;
        }
        if (/^".*"$/.test(line) && !/^".*\\n"$/.test(line)) {
            acc.merge = true;
        }
        acc.push(line);
        return acc;
    }, []).forEach(function (header) {
        if (header.match(/^#/)) {
            po.comments.push(header.replace(/^#\s*/, ''));
        }
        if (header.match(/^"/)) {
            header = header.trim().replace(/^"/, '').replace(/\\n"$/, '');
            var p = header.split(/:/);
            var name = p.shift().trim();
            var value = p.join(':').trim();
            po.headers[name] = value;
        }
    });

    var item = new PO.Item();
    var context = null;
    var plural = 0;
    var obsoleteCount = 0;
    var noCommentLineCount = 0;

    function finish() {
        if (item.msgid.length > 0) {
            if (obsoleteCount >= noCommentLineCount) {
                item.obsolete = true;
            }
            obsoleteCount = 0;
            noCommentLineCount = 0;
            po.items.push(item);
            item = new PO.Item();
        }
    }

    function extract(string) {
        string = trim(string);
        string = string.replace(/^[^"]*"|"$/g, '');
        string = string.replace(/\\"/g, '"');
        string = string.replace(/\\\\/g, '\\');
        return string;
    }

    while (lines.length > 0) {
        var line = trim(lines.shift());
        var lineObsolete = false;
        var add = false;

        if (line.match(/^#\~/)) { // Obsolete item
            //only remove the obsolte comment mark, here
            //might be, this is a new item, so
            //only remember, this line is marked obsolete, count after line is parsed
            line = trim(line.substring(2));
            lineObsolete = true;
        }

        if (line.match(/^#:/)) { // Reference
            finish();
            item.references.push(trim(line.replace(/^#:/, '')));
        } else if (line.match(/^#,/)) { // Flags
            finish();
            var flags = trim(line.replace(/^#,/, '')).split(',');
            for (var i = 0; i < flags.length; i++) {
                item.flags[flags[i]] = true;
            }
        } else if (line.match(/^#($|\s+)/)) { // Translator comment
            finish();
            item.comments.push(trim(line.replace(/^#($|\s+)/, '')));
        } else if (line.match(/^#\./)) { // Extracted comment
            finish();
            item.extractedComments.push(trim(line.replace(/^#\./, '')));
        } else if (line.match(/^msgid_plural/)) { // Plural form
            item.msgid_plural = extract(line);
            context = 'msgid_plural';
            noCommentLineCount++;
        } else if (line.match(/^msgid/)) { // Original
            finish();
            item.msgid = extract(line);
            context = 'msgid';
            noCommentLineCount++;
        } else if (line.match(/^msgstr/)) { // Translation
            var m = line.match(/^msgstr\[(\d+)\]/);
            plural = m && m[1] ? parseInt(m[1]) : 0;
            item.msgstr[plural] = extract(line);
            context = 'msgstr';
            noCommentLineCount++;
        } else if (line.match(/^msgctxt/)) { // Context
            finish();
            item.msgctxt = extract(line);
            noCommentLineCount++;
        } else { // Probably multiline string or blank
            if (line.length > 0) {
                noCommentLineCount++;
                if (context === 'msgstr') {
                    item.msgstr[plural] += extract(line);
                } else if (context === 'msgid') {
                    item.msgid += extract(line);
                } else if (context === 'msgid_plural') {
                    item.msgid_plural += extract(line);
                }
            }
        }

        if (lineObsolete) {
            // Count obsolete lines for this item
            obsoleteCount++;
        }
    }
    finish();

    return po;
};

PO.Item = function () {
    this.msgid = '';
    this.msgctxt = null;
    this.references = [];
    this.msgid_plural = null;
    this.msgstr = [];
    this.comments = []; // translator comments
    this.extractedComments = [];
    this.flags = {};
    this.obsolete = false;
};

PO.Item.prototype.toString = function () {
    var lines = [];
    var self = this;

    // reverse what extract(string) method during PO.parse does
    var _escape = function (string) {
        string = string.replace(/\\/g, '\\\\');
        return string.replace(/"/g, '\\"');
    };

    var _process = function (keyword, text, i) {
        var lines = [];
        var parts = text.split(/\n/);
        var index = typeof i !== 'undefined' ? '[' + i + ']' : '';
        if (parts.length > 1) {
            lines.push(keyword + index + ' ""');
            parts.forEach(function (part) {
                lines.push('"' + _escape(part) + '"');
            });
        } else {
            lines.push(keyword + index + ' "' + _escape(text) + '"');
        }
        return lines;
    };

    // https://www.gnu.org/software/gettext/manual/html_node/PO-Files.html
    // says order is translator-comments, extracted-comments, references, flags

    this.comments.forEach(function (c) {
        lines.push('# ' + c);
    });

    this.extractedComments.forEach(function (c) {
        lines.push('#. ' + c);
    });

    this.references.forEach(function (ref) {
        lines.push('#: ' + ref);
    });

    var flags = Object.keys(this.flags);
    if (flags.length > 0) {
        lines.push('#, ' + flags.join(','));
    }
    var mkObsolete = this.obsolete ? '#~ ' : '';

    ['msgctxt', 'msgid', 'msgid_plural', 'msgstr'].forEach(function (keyword) {
        var text = self[keyword];
        if (text != null) {
            if (isArray(text) && text.length > 1) {
                text.forEach(function (t, i) {
                    lines = lines.concat(mkObsolete + _process(keyword, t, i));
                });
            } else {
                text = isArray(text) ? text.join() : text;
                var processed = _process(keyword, text);
                lines = lines.concat(mkObsolete + processed.join('\n' + mkObsolete));
            }
        }
    });

    return lines.join('\n');
};

module.exports = PO;

},{"fs":3,"lodash.isarray":4}],"pofile":[function(require,module,exports){
module.exports=require('W8CkM0');
},{}],3:[function(require,module,exports){

},{}],4:[function(require,module,exports){
/**
 * Lo-Dash 2.4.1 (Custom Build) <http://lodash.com/>
 * Build: `lodash modularize modern exports="npm" -o ./npm/`
 * Copyright 2012-2013 The Dojo Foundation <http://dojofoundation.org/>
 * Based on Underscore.js 1.5.2 <http://underscorejs.org/LICENSE>
 * Copyright 2009-2013 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 * Available under MIT license <http://lodash.com/license>
 */
var isNative = require('lodash._isnative');

/** `Object#toString` result shortcuts */
var arrayClass = '[object Array]';

/** Used for native method references */
var objectProto = Object.prototype;

/** Used to resolve the internal [[Class]] of values */
var toString = objectProto.toString;

/* Native method shortcuts for methods with the same name as other `lodash` methods */
var nativeIsArray = isNative(nativeIsArray = Array.isArray) && nativeIsArray;

/**
 * Checks if `value` is an array.
 *
 * @static
 * @memberOf _
 * @type Function
 * @category Objects
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if the `value` is an array, else `false`.
 * @example
 *
 * (function() { return _.isArray(arguments); })();
 * // => false
 *
 * _.isArray([1, 2, 3]);
 * // => true
 */
var isArray = nativeIsArray || function(value) {
  return value && typeof value == 'object' && typeof value.length == 'number' &&
    toString.call(value) == arrayClass || false;
};

module.exports = isArray;

},{"lodash._isnative":5}],5:[function(require,module,exports){
/**
 * Lo-Dash 2.4.1 (Custom Build) <http://lodash.com/>
 * Build: `lodash modularize modern exports="npm" -o ./npm/`
 * Copyright 2012-2013 The Dojo Foundation <http://dojofoundation.org/>
 * Based on Underscore.js 1.5.2 <http://underscorejs.org/LICENSE>
 * Copyright 2009-2013 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 * Available under MIT license <http://lodash.com/license>
 */

/** Used for native method references */
var objectProto = Object.prototype;

/** Used to resolve the internal [[Class]] of values */
var toString = objectProto.toString;

/** Used to detect if a method is native */
var reNative = RegExp('^' +
  String(toString)
    .replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    .replace(/toString| for [^\]]+/g, '.*?') + '$'
);

/**
 * Checks if `value` is a native function.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if the `value` is a native function, else `false`.
 */
function isNative(value) {
  return typeof value == 'function' && reNative.test(value);
}

module.exports = isNative;

},{}]},{},["W8CkM0"])