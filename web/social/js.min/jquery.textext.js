/**
 * jQuery TextExt Plugin
 * http://textextjs.com
 *
 * @version 1.3.1
 * @copyright Copyright (C) 2011 Alex Gorbatchev. All rights reserved.
 * @license MIT License
 */;
(function (a, b) {
    function c() {}
    function d() {}
    function e() {}
    function x(a, b) {
        typeof b == "string" && (b = b.split("."));
        var c = b.join(".").replace(/\.(\w)/g, function (a, b) {
            return b.toUpperCase()
        }),
            d = b.shift(),
            e;
        return typeof (e = a[c]) != i ? e = e : typeof (e = a[d]) != i && b.length > 0 && (e = x(e, b)), e
    }
    function y() {
        function e(a, d) {
            c.bind(a, function () {
                return d.apply(b, arguments)
            })
        }
        var a = g.apply(arguments),
            b = this,
            c = a.length === 1 ? b : a.shift(),
            d;
        a = a[0] || {};
        for (d in a) e(d, a[d])
    }
    function z(a, b) {
        return {
            input: a,
            form: b
        }
    }
    var f = (JSON || {}).stringify,
        g = Array.prototype.slice,
        h, i = "undefined",
        j = "item.manager",
        k = "plugins",
        l = "ext",
        m = "html.wrap",
        n = "html.hidden",
        o = "keys",
        p = "preInvalidate",
        q = "postInvalidate",
        r = "getFormData",
        s = "setFormData",
        t = "setInputData",
        u = "postInit",
        v = "ready",
        w = {
            itemManager: d,
            plugins: [],
            ext: {},
            html: {
                wrap: '<div class="text-core"><div class="text-wrap"/></div>',
                hidden: '<input type="hidden" />'
            },
            keys: {
                8: "backspace",
                9: "tab",
                13: "enter!",
                27: "escape!",
                37: "left",
                38: "up!",
                39: "right",
                40: "down!",
                46: "delete",
                108: "numpadEnter"
            }
        };
    if (!f) throw new Error("JSON.stringify() not found");
    h = d.prototype, h.init = function (a) {}, h.filter = function (a, b) {
        var c = [],
            d, e;
        for (d = 0; d < a.length; d++) e = a[d], this.itemContains(e, b) && c.push(e);
        return c
    }, h.itemContains = function (a, b) {
        return this.itemToString(a).toLowerCase().indexOf(b.toLowerCase()) == 0
    }, h.stringToItem = function (a) {
        return a
    }, h.itemToString = function (a) {
        return a
    }, h.compareItems = function (a, b) {
        return a == b
    }, h = c.prototype, h.init = function (b, c) {
        var d = this,
            e, f, g;
        d._defaults = a.extend({}, w), d._opts = c || {}, d._plugins = {}, d._itemManager = f = new(d.opts(j)), b = a(b), g = a(d.opts(m)), e = a(d.opts(n)), b.wrap(g).keydown(function (a) {
            return d.onKeyDown(a)
        }).keyup(function (a) {
            return d.onKeyUp(a)
        }).data("textext", d), a(d).data({
            hiddenInput: e,
            wrapElement: b.parents(".text-wrap").first(),
            input: b
        }), e.attr("name", b.attr("name")), b.attr("name", null), e.insertAfter(b), a.extend(!0, f, d.opts(l + ".item.manager")), a.extend(!0, d, d.opts(l + ".*"), d.opts(l + ".core")), d.originalWidth = b.outerWidth(), d.invalidateBounds(), f.init(d), d.initPatches(), d.initPlugins(d.opts(k), a.fn.textext.plugins), d.on({
            setFormData: d.onSetFormData,
            getFormData: d.onGetFormData,
            setInputData: d.onSetInputData,
            anyKeyUp: d.onAnyKeyUp
        }), d.trigger(u), d.trigger(v), d.getFormData(0)
    }, h.initPatches = function () {
        var b = [],
            c = a.fn.textext.patches,
            d;
        for (d in c) b.push(d);
        this.initPlugins(b, c)
    }, h.initPlugins = function (b, c) {
        var d = this,
            e, f, g, h = [],
            i;
        typeof b == "string" && (b = b.split(/\s*,\s*|\s+/g));
        for (i = 0; i < b.length; i++) f = b[i], g = c[f], g && (d._plugins[f] = g = new g, d[f] = function (a) {
                return function () {
                    return a
                }
            }(g), h.push(g), a.extend(!0, g, d.opts(l + ".*"), d.opts(l + "." + f)));
        h.sort(function (a, b) {
            return a = a.initPriority(), b = b.initPriority(), a === b ? 0 : a < b ? 1 : -1
        });
        for (i = 0; i < h.length; i++) h[i].init(d)
    }, h.hasPlugin = function (a) {
        return !!this._plugins[a]
    }, h.on = y, h.bind = function (a, b) {
        this.input().bind(a, b)
    }, h.trigger = function () {
        var a = arguments;
        this.input().trigger(a[0], g.call(a, 1))
    }, h.itemManager = function () {
        return this._itemManager
    }, h.input = function () {
        return a(this).data("input")
    }, h.opts = function (a) {
        var b = x(this._opts, a);
        return typeof b == "undefined" ? x(this._defaults, a) : b
    }, h.wrapElement = function () {
        return a(this).data("wrapElement")
    }, h.invalidateBounds = function () {
        var a = this,
            b = a.input(),
            c = a.wrapElement(),
            d = c.parent(),
            e = a.originalWidth + "px",
            f;
        a.trigger(p), f = b.outerHeight() + "px", b.css({
            width: e
        }), c.css({
            width: e,
            height: f
        }), d.css({
            height: f
        }), a.trigger(q)
    }, h.focusInput = function () {
        this.input()[0].focus()
    }, h.serializeData = f, h.hiddenInput = function (b) {
        return a(this).data("hiddenInput")
    }, h.getWeightedEventResponse = function (a, b) {
        var c = this,
            d = {}, e = 0;
        c.trigger(a, d, b);
        for (var f in d) e = Math.max(e, f);
        return d[e]
    }, h.getFormData = function (a) {
        var b = this,
            c = b.getWeightedEventResponse(r, a || 0);
        b.trigger(s, c.form), b.trigger(t, c.input)
    }, h.onAnyKeyUp = function (a, b) {
        this.getFormData(b)
    }, h.onSetInputData = function (a, b) {
        this.input().val(b)
    }, h.onSetFormData = function (a, b) {
        var c = this;
        c.hiddenInput().val(c.serializeData(b))
    }, h.onGetFormData = function (a, b) {
        var c = this.input().val();
        b[0] = z(c, c)
    }, a(["Down", "Up"]).each(function () {
        var a = this.toString();
        h["onKey" + a] = function (b) {
            var c = this,
                d = c.opts(o)[b.keyCode],
                e = !0;
            return d && (e = d.substr(-1) != "!", d = d.replace("!", ""), c.trigger(d + "Key" + a), a == "Up" && c._lastKeyDown == b.keyCode && (c._lastKeyDown = null, c.trigger(d + "KeyPress")), a == "Down" && (c._lastKeyDown = b.keyCode)), c.trigger("anyKey" + a, b.keyCode), e
        }
    }), h = e.prototype, h.on = y, h.formDataObject = z, h.init = function (a) {
        throw new Error("Not implemented")
    }, h.baseInit = function (b, c) {
        var d = this;
        b._defaults = a.extend(!0, b._defaults, c), d._core = b, d._timers = {}
    }, h.startTimer = function (a, b, c) {
        var d = this;
        d.stopTimer(a), d._timers[a] = setTimeout(function () {
            delete d._timers[a], c.apply(d)
        }, b * 1e3)
    }, h.stopTimer = function (a) {
        clearTimeout(this._timers[a])
    }, h.core = function () {
        return this._core
    }, h.opts = function (a) {
        return this.core().opts(a)
    }, h.itemManager = function () {
        return this.core().itemManager()
    }, h.input = function () {
        return this.core().input()
    }, h.val = function (a) {
        var b = this.input();
        if (typeof a === i) return b.val();
        b.val(a)
    }, h.trigger = function () {
        var a = this.core();
        a.trigger.apply(a, arguments)
    }, h.bind = function (a, b) {
        this.core().bind(a, b)
    }, h.initPriority = function () {
        return 0
    };
    var A = !1,
        B = a.fn.textext = function (b) {
            var d;
            return !A && (d = a.fn.textext.css) != null && (a("head").append("<style>" + d + "</style>"), A = !0), this.map(function () {
                var d = a(this);
                if (b == null) return d.data("textext");
                var e = new c;
                return e.init(d, b), d.data("textext", e), e.input()[0]
            })
        };
    B.addPlugin = function (a, b) {
        B.plugins[a] = b, b.prototype = new B.TextExtPlugin
    }, B.addPatch = function (a, b) {
        B.patches[a] = b, b.prototype = new B.TextExtPlugin
    }, B.TextExt = c, B.TextExtPlugin = e, B.ItemManager = d, B.plugins = {}, B.patches = {}
})(jQuery),
function (a) {
    function b() {}
    a.fn.textext.TextExtIE9Patches = b, a.fn.textext.addPatch("ie9", b);
    var c = b.prototype;
    c.init = function (a) {
        if (navigator.userAgent.indexOf("MSIE") == -1) return;
        var b = this;
        a.on({
            postInvalidate: b.onPostInvalidate
        })
    }, c.onPostInvalidate = function () {
        var a = this,
            b = a.input(),
            c = b.val();
        b.val(Math.random()), b.val(c)
    }
}(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtAjax = b, a.fn.textext.addPlugin("ajax", b);
    var c = b.prototype,
        d = "ajax.data.callback",
        e = "ajax.cache.results",
        f = "ajax.loading.delay",
        g = "ajax.loading.message",
        h = "ajax.type.delay",
        i = "setSuggestions",
        j = "showDropdown",
        k = "loading",
        l = {
            ajax: {
                typeDelay: .5,
                loadingMessage: "Loading...",
                loadingDelay: .5,
                cacheResults: !1,
                dataCallback: null
            }
        };
    c.init = function (a) {
        var b = this;
        b.baseInit(a, l), b.on({
            getSuggestions: b.onGetSuggestions
        }), b._suggestions = null
    }, c.load = function (b) {
        var c = this,
            e = c.opts(d) || function (a) {
                return {
                    q: a
                }
            }, f;
        f = a.extend(!0, {
            data: e(b),
            success: function (a) {
                c.onComplete(a, b)
            },
            error: function (a, c) {
                console.error(c, b)
            }
        }, c.opts("ajax")), a.ajax(f)
    }, c.onComplete = function (a, b) {
        var c = this,
            d = a;
        c.dontShowLoading(), c.opts(e) == 1 && (c._suggestions = a, d = c.itemManager().filter(a, b)), c.trigger(i, {
            result: d
        })
    }, c.dontShowLoading = function () {
        this.stopTimer(k)
    }, c.showLoading = function () {
        var a = this;
        a.dontShowLoading(), a.startTimer(k, a.opts(f), function () {
            a.trigger(j, function (b) {
                b.clearItems();
                var c = b.addDropdownItem(a.opts(g));
                c.addClass("text-loading")
            })
        })
    }, c.onGetSuggestions = function (a, b) {
        var c = this,
            d = c._suggestions,
            f = (b || {}).query || "";
        if (d && c.opts(e) === !0) return c.onComplete(d, f);
        c.startTimer("ajax", c.opts(h), function () {
            c.showLoading(), c.load(f)
        })
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtArrow = b, a.fn.textext.addPlugin("arrow", b);
    var c = b.prototype,
        d = "html.arrow",
        e = {
            html: {
                arrow: '<div class="text-arrow"/>'
            }
        };
    c.init = function (b) {
        var c = this,
            f;
        c.baseInit(b, e), c._arrow = f = a(c.opts(d)), c.core().wrapElement().append(f), f.bind("click", function (a) {
            c.onArrowClick(a)
        })
    }, c.onArrowClick = function (a) {
        this.trigger("toggleDropdown"), this.core().focusInput()
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtAutocomplete = b, a.fn.textext.addPlugin("autocomplete", b);
    var c = b.prototype,
        d = ".",
        e = "text-selected",
        f = d + e,
        g = "text-suggestion",
        h = d + g,
        i = "text-label",
        j = d + i,
        k = "autocomplete.enabled",
        l = "autocomplete.dropdown.position",
        m = "autocomplete.dropdown.maxHeight",
        n = "autocomplete.render",
        o = "html.dropdown",
        p = "html.suggestion",
        q = "hideDropdown",
        r = "showDropdown",
        s = "getSuggestions",
        t = "getFormData",
        u = "toggleDropdown",
        v = "above",
        w = "below",
        x = "mousedownOnAutocomplete",
        y = {
            autocomplete: {
                enabled: !0,
                dropdown: {
                    position: w,
                    maxHeight: "100px"
                }
            },
            html: {
                dropdown: '<div class="text-dropdown"><div class="text-list"/></div>',
                suggestion: '<div class="text-suggestion"><span class="text-label"/></div>'
            }
        };
    c.init = function (b) {
        var c = this;
        c.baseInit(b, y);
        var d = c.input(),
            e;
        c.opts(k) === !0 && (c.on({
            blur: c.onBlur,
            anyKeyUp: c.onAnyKeyUp,
            deleteKeyUp: c.onAnyKeyUp,
            backspaceKeyPress: c.onBackspaceKeyPress,
            enterKeyPress: c.onEnterKeyPress,
            escapeKeyPress: c.onEscapeKeyPress,
            setSuggestions: c.onSetSuggestions,
            showDropdown: c.onShowDropdown,
            hideDropdown: c.onHideDropdown,
            toggleDropdown: c.onToggleDropdown,
            postInvalidate: c.positionDropdown,
            getFormData: c.onGetFormData,
            downKeyDown: c.onDownKeyDown,
            upKeyDown: c.onUpKeyDown
        }), e = a(c.opts(o)), e.insertAfter(d), c.on(e, {
            mouseover: c.onMouseOver,
            mousedown: c.onMouseDown,
            click: c.onClick
        }), e.css("maxHeight", c.opts(m)).addClass("text-position-" + c.opts(l)), a(c).data("container", e), a(document.body).click(function (a) {
            c.isDropdownVisible() && !c.withinWrapElement(a.target) && c.trigger(q)
        }), c.positionDropdown())
    }, c.containerElement = function () {
        return a(this).data("container")
    }, c.onMouseOver = function (b) {
        var c = this,
            d = a(b.target).closest(h);
        d.is(h) && (c.clearSelected(), d.addClass(e))
    }, c.onMouseDown = function (a) {
        this.containerElement().data(x, !0)
    }, c.onClick = function (b) {
        var c = this,
            d = a(b.target).closest(h);
        (d.is(h) || d.is(j)) && c.trigger("enterKeyPress"), c.core().hasPlugin("tags") && c.val("")
    }, c.onBlur = function (a) {
        var b = this,
            c = b.containerElement(),
            d = c.data(x) === !0;
        b.isDropdownVisible() && (d ? b.core().focusInput() : b.trigger(q)), c.removeData(x)
    }, c.onBackspaceKeyPress = function (a) {
        var b = this,
            c = b.val().length > 0;
        (c || b.isDropdownVisible()) && b.getSuggestions()
    }, c.onAnyKeyUp = function (a, b) {
        var c = this,
            d = c.opts("keys." + b) != null;
        c.val().length > 0 && !d && c.getSuggestions()
    }, c.onDownKeyDown = function (a) {
        var b = this;
        b.isDropdownVisible() ? b.toggleNextSuggestion() : b.getSuggestions()
    }, c.onUpKeyDown = function (a) {
        this.togglePreviousSuggestion()
    }, c.onEnterKeyPress = function (a) {
        var b = this;
        b.isDropdownVisible() && b.selectFromDropdown()
    }, c.onEscapeKeyPress = function (a) {
        var b = this;
        b.isDropdownVisible() && b.trigger(q)
    }, c.positionDropdown = function () {
        var a = this,
            b = a.containerElement(),
            c = a.opts(l),
            d = a.core().wrapElement().outerHeight(),
            e = {};
        e[c === v ? "bottom" : "top"] = d + "px", b.css(e)
    }, c.suggestionElements = function () {
        return this.containerElement().find(h)
    }, c.setSelectedSuggestion = function (b) {
        if (!b) return;
        var c = this,
            d = c.suggestionElements(),
            f = d.first(),
            h, i;
        c.clearSelected();
        for (i = 0; i < d.length; i++) {
            h = a(d[i]);
            if (c.itemManager().compareItems(h.data(g), b)) {
                f = h.addClass(e);
                break
            }
        }
        f.addClass(e), c.scrollSuggestionIntoView(f)
    }, c.selectedSuggestionElement = function () {
        return this.suggestionElements().filter(f).first()
    }, c.isDropdownVisible = function () {
        return this.containerElement().is(":visible") === !0
    }, c.onGetFormData = function (a, b, c) {
        var d = this,
            e = d.val(),
            f = e,
            g = e;
        b[100] = d.formDataObject(f, g)
    }, c.initPriority = function () {
        return 200
    }, c.onHideDropdown = function (a) {
        this.hideDropdown()
    }, c.onToggleDropdown = function (a) {
        var b = this;
        b.trigger(b.containerElement().is(":visible") ? q : r)
    }, c.onShowDropdown = function (b, c) {
        var d = this,
            e = d.selectedSuggestionElement().data(g),
            f = d._suggestions;
        if (!f) return d.trigger(s);
        a.isFunction(c) ? c(d) : (d.renderSuggestions(d._suggestions), d.toggleNextSuggestion()), d.showDropdown(d.containerElement()), d.setSelectedSuggestion(e)
    }, c.onSetSuggestions = function (a, b) {
        var c = this,
            d = c._suggestions = b.result;
        b.showHideDropdown !== !1 && c.trigger(d === null || d.length === 0 ? q : r)
    }, c.getSuggestions = function () {
        var a = this,
            b = a.val();
        if (a._previousInputValue == b) return;
        b == "" && (current = null), a._previousInputValue = b, a.trigger(s, {
            query: b
        })
    }, c.clearItems = function () {
        this.containerElement().find(".text-list").children().remove()
    }, c.renderSuggestions = function (b) {
        var c = this;
        c.clearItems(), a.each(b || [], function (a, b) {
            c.addSuggestion(b)
        })
    }, c.showDropdown = function () {
        this.containerElement().show()
    }, c.hideDropdown = function () {
        var a = this,
            b = a.containerElement();
        a._previousInputValue = null, b.hide()
    }, c.addSuggestion = function (a) {
        var b = this,
            c = b.opts(n),
            d = b.addDropdownItem(c ? c.call(b, a) : b.itemManager().itemToString(a));
        d.data(g, a)
    }, c.addDropdownItem = function (b) {
        var c = this,
            d = c.containerElement().find(".text-list"),
            e = a(c.opts(p));
        return e.find(".text-label").html(b), d.append(e), e
    }, c.clearSelected = function () {
        this.suggestionElements().removeClass(e)
    }, c.toggleNextSuggestion = function () {
        var a = this,
            b = a.selectedSuggestionElement(),
            c;
        b.length > 0 ? (c = b.next(), c.length > 0 && b.removeClass(e)) : c = a.suggestionElements().first(), c.addClass(e), a.scrollSuggestionIntoView(c)
    }, c.togglePreviousSuggestion = function () {
        var a = this,
            b = a.selectedSuggestionElement(),
            c = b.prev();
        if (c.length == 0) return;
        a.clearSelected(), c.addClass(e), a.scrollSuggestionIntoView(c)
    }, c.scrollSuggestionIntoView = function (a) {
        var b = a.outerHeight(),
            c = this.containerElement(),
            d = c.innerHeight(),
            e = c.scrollTop(),
            f = (a.position() || {}).top,
            g = null,
            h = parseInt(c.css("paddingTop"));
        if (f == null) return;
        f + b > d && (g = f + e + b - d + h), f < 0 && (g = f + e - h), g != null && c.scrollTop(g)
    }, c.selectFromDropdown = function () {
        var a = this,
            b = a.selectedSuggestionElement().data(g);
        b && (a.val(a.itemManager().itemToString(b)), a.core().getFormData()), a.trigger(q)
    }, c.withinWrapElement = function (a) {
        return this.core().wrapElement().find(a).size() > 0
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtFilter = b, a.fn.textext.addPlugin("filter", b);
    var c = b.prototype,
        d = "filter.enabled",
        e = "filter.items",
        f = {
            filter: {
                enabled: !0,
                items: null
            }
        };
    c.init = function (a) {
        var b = this;
        b.baseInit(a, f), b.on({
            getFormData: b.onGetFormData,
            isTagAllowed: b.onIsTagAllowed,
            setSuggestions: b.onSetSuggestions
        }), b._suggestions = null
    }, c.onGetFormData = function (a, b, c) {
        var d = this,
            e = d.val(),
            f = e,
            g = "";
        d.core().hasPlugin("tags") || (d.isValueAllowed(f) && (g = e), b[300] = d.formDataObject(f, g))
    }, c.isValueAllowed = function (a) {
        var b = this,
            c = b.opts("filterItems") || b._suggestions || [],
            e = b.itemManager(),
            f = !b.opts(d),
            g;
        for (g = 0; g < c.length && !f; g++) e.compareItems(a, c[g]) && (f = !0);
        return f
    }, c.onIsTagAllowed = function (a, b) {
        b.result = this.isValueAllowed(b.tag)
    }, c.onSetSuggestions = function (a, b) {
        this._suggestions = b.result
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtFocus = b, a.fn.textext.addPlugin("focus", b);
    var c = b.prototype,
        d = "html.focus",
        e = {
            html: {
                focus: '<div class="text-focus"/>'
            }
        };
    c.init = function (a) {
        var b = this;
        b.baseInit(a, e), b.core().wrapElement().append(b.opts(d)), b.on({
            blur: b.onBlur,
            focus: b.onFocus
        }), b._timeoutId = 0
    }, c.onBlur = function (a) {
        var b = this;
        clearTimeout(b._timeoutId), b._timeoutId = setTimeout(function () {
            b.getFocus().hide()
        }, 100)
    }, c.onFocus = function (a) {
        var b = this;
        clearTimeout(b._timeoutId), b.getFocus().show()
    }, c.getFocus = function () {
        return this.core().wrapElement().find(".text-focus")
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtPrompt = b, a.fn.textext.addPlugin("prompt", b);
    var c = b.prototype,
        d = "text-hide-prompt",
        e = "prompt",
        f = "html.prompt",
        g = {
            prompt: "Awaiting input...",
            html: {
                prompt: '<div class="text-prompt"/>'
            }
        };
    c.init = function (b) {
        var c = this,
            d = "placeholder",
            h, i;
        c.baseInit(b, g), h = a(c.opts(f)), a(c).data("container", h), c.core().wrapElement().append(h), c.setPrompt(c.opts(e)), i = b.input().attr(d), i || (i = c.opts(e)), b.input().attr(d, ""), i && c.setPrompt(i), a.trim(c.val()).length > 0 && c.hidePrompt(), c.on({
            blur: c.onBlur,
            focus: c.onFocus,
            postInvalidate: c.onPostInvalidate,
            postInit: c.onPostInit
        })
    }, c.onPostInit = function (a) {
        this.invalidateBounds()
    }, c.onPostInvalidate = function (a) {
        this.invalidateBounds()
    }, c.invalidateBounds = function () {
        var a = this,
            b = a.input();
        a.containerElement().css({
            paddingLeft: b.css("paddingLeft"),
            paddingTop: b.css("paddingTop")
        })
    }, c.onBlur = function (a) {
        var b = this;
        b.startTimer("prompt", .1, function () {
            b.showPrompt()
        })
    }, c.showPrompt = function () {
        var b = this,
            c = b.input();
        a.trim(b.val()).length === 0 && !c.is(":focus") && b.containerElement().removeClass(d)
    }, c.hidePrompt = function () {
        this.stopTimer("prompt"), this.containerElement().addClass(d)
    }, c.onFocus = function (a) {
        this.hidePrompt()
    }, c.setPrompt = function (a) {
        this.containerElement().text(a)
    }, c.containerElement = function () {
        return a(this).data("container")
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtSuggestions = b, a.fn.textext.addPlugin("suggestions", b);
    var c = b.prototype,
        d = "suggestions",
        e = {
            suggestions: null
        };
    c.init = function (a) {
        var b = this;
        b.baseInit(a, e), b.on({
            getSuggestions: b.onGetSuggestions,
            postInit: b.onPostInit
        })
    }, c.setSuggestions = function (a, b) {
        this.trigger("setSuggestions", {
            result: a,
            showHideDropdown: b != 0
        })
    }, c.onPostInit = function (a) {
        var b = this;
        b.setSuggestions(b.opts(d), !1)
    }, c.onGetSuggestions = function (a, b) {
        var c = this,
            e = c.opts(d);
        e.sort(), c.setSuggestions(c.itemManager().filter(e, b.query))
    }
})(jQuery);
(function (a) {
    function b() {}
    a.fn.textext.TextExtTags = b, a.fn.textext.addPlugin("tags", b);
    var c = b.prototype,
        d = ".",
        e = "text-tags-on-top",
        f = d + e,
        g = "text-tag",
        h = d + g,
        i = "text-tags",
        j = d + i,
        k = "text-label",
        l = d + k,
        m = "text-remove",
        n = d + m,
        o = "tags.enabled",
        p = "tags.items",
        q = "html.tag",
        r = "html.tags",
        s = "isTagAllowed",
        t = "tagClick",
        u = {
            tags: {
                enabled: !0,
                items: null
            },
            html: {
                tags: '<div class="text-tags"/>',
                tag: '<div class="text-tag"><div class="text-button"><span class="text-label"/><a class="text-remove"/></div></div>'
            }
        };
    c.init = function (b) {
        this.baseInit(b, u);
        var c = this,
            d = c.input(),
            e;
        c.opts(o) && (e = a(c.opts(r)), d.after(e), a(c).data("container", e), c.on({
            enterKeyPress: c.onEnterKeyPress,
            backspaceKeyDown: c.onBackspaceKeyDown,
            preInvalidate: c.onPreInvalidate,
            postInit: c.onPostInit,
            getFormData: c.onGetFormData
        }), c.on(e, {
            click: c.onClick,
            mousemove: c.onContainerMouseMove
        }), c.on(d, {
            mousemove: c.onInputMouseMove
        })), c._originalPadding = {
            left: parseInt(d.css("paddingLeft") || 0),
            top: parseInt(d.css("paddingTop") || 0)
        }, c._paddingBox = {
            left: 0,
            top: 0
        }, c.updateFormCache()
    }, c.containerElement = function () {
        return a(this).data("container")
    }, c.onPostInit = function (a) {
        var b = this;
        b.addTags(b.opts(p))
    }, c.onGetFormData = function (a, b, c) {
        var d = this,
            e = c === 13 ? "" : d.val(),
            f = d._formData;
        b[200] = d.formDataObject(e, f)
    }, c.initPriority = function () {
        return 100
    }, c.onInputMouseMove = function (a) {
        this.toggleZIndex(a)
    }, c.onContainerMouseMove = function (a) {
        this.toggleZIndex(a)
    }, c.onBackspaceKeyDown = function (a) {
        var b = this,
            c = b.tagElements().last();
        b.val().length == 0 && b.removeTag(c)
    }, c.onPreInvalidate = function (a) {
        var b = this,
            c = b.tagElements().last(),
            d = c.position();
        c.length > 0 ? d.left += c.innerWidth() : d = b._originalPadding, b._paddingBox = d, b.input().css({
            paddingLeft: d.left,
            paddingTop: d.top
        })
    }, c.onClick = function (b) {
        function k(a, b) {
            i.data(g, a), i.find(l).text(c.itemManager().itemToString(a)), c.updateFormCache(), d.getFormData(), d.invalidateBounds(), b && d.focusInput()
        }
        var c = this,
            d = c.core(),
            e = a(b.target),
            f = 0,
            i;
        e.is(j) ? f = 1 : e.is(n) ? (c.removeTag(e.parents(h + ":first")), f = 1) : e.is(l) && (i = e.parents(h + ":first"), c.trigger(t, i, i.data(g), k)), f && d.focusInput()
    }, c.onEnterKeyPress = function (a) {
        var b = this,
            c = b.val(),
            d = b.itemManager().stringToItem(c);
        b.isTagAllowed(d) && (b.addTags([d]), b.core().focusInput())
    }, c.updateFormCache = function () {
        var b = this,
            c = [];
        b.tagElements().each(function () {
            c.push(a(this).data(g))
        }), b._formData = c
    }, c.toggleZIndex = function (a) {
        var b = this,
            c = b.input().offset(),
            d = a.clientX - c.left,
            g = a.clientY - c.top,
            h = b._paddingBox,
            i = b.containerElement(),
            j = i.is(f),
            k = d > h.left && g > h.top;
        (!j && !k || j && k) && i[(j ? "remove" : "add") + "Class"](e)
    }, c.tagElements = function () {
        return this.containerElement().find(h)
    }, c.isTagAllowed = function (a) {
        var b = {
            tag: a,
            result: !0
        };
        return this.trigger(s, b), b.result === !0
    }, c.addTags = function (a) {
        if (!a || a.length == 0) return;
        var b = this,
            c = b.core(),
            d = b.containerElement(),
            e, f;
        for (e = 0; e < a.length; e++) f = a[e], f && b.isTagAllowed(f) && d.append(b.renderTag(f));
        b.updateFormCache(), c.getFormData(), c.invalidateBounds()
    }, c.getTagElement = function (b) {
        var c = this,
            d = c.tagElements(),
            e, f;
        for (e = 0; e < d.length, f = a(d[e]); e++) if (c.itemManager().compareItems(f.data(g), b)) return f
    }, c.removeTag = function (b) {
        var c = this,
            d = c.core(),
            e;
        b instanceof a ? (e = b, b = b.data(g)) : e = c.getTagElement(b), e.remove(), c.updateFormCache(), d.getFormData(), d.invalidateBounds()
    }, c.renderTag = function (b) {
        var c = this,
            d = a(c.opts(q));
        return d.find(".text-label").text(c.itemManager().itemToString(b)), d.data(g, b), d
    }
})(jQuery);



//the custsom manager for users
//requires a function shareFriendsArray to be defined
function CustManager(){
	this.core = null;
	this.compareItems = function (item1,item2) {
		return (item1.id === item2.id);
	}
	this.filter = function (list,query) {
		var filtered = new Array();
		var current =  this.core._plugins.tags._formData;
		$.each(list,function(i,v){
			var found = false;
			$.each(current,function(i,v2){
				if( v.id == v2.id ){
					found = true;
					return false;
				}
			});
			if( found ) return ;
			if( v.name.toLowerCase().indexOf(query.toLowerCase()) !== -1 ) filtered.push(v);
		});
		return filtered;
	}
	this.init = function(core){
		this.core = core;
		CORE = core;
	}
	this.itemContains = function(item,needle){
		return ( item.name.toLowerCase().indexOf(needle.toLowerCase()) !== -1 );
	}
	this.itemToString = function(item){
		return item.name;
	}
	this.stringToItem = function(str){
		var AllFreinds = shareFriendsArray();
		var found = null;
		$.each(AllFreinds,function(i,v){
			if( v.name === str ){
				found = v;
				return false;
			}
		});
		return found;
	}
}