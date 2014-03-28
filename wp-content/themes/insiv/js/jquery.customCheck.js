;(function($, wdw, doc) {
    'use strict';

    // set defaults
    var _plugin = 'customCheck',
        defaults = {
            onCheck: $.noop,
            onUncheck: $.noop
        };

    // the plugin constructor
    function Plugin(element, options) {
        this.el = element;
        this.opt = $.extend(true, {}, defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var $el = $(this.el),
                $custom = (!$el.parent().hasClass('custom-check') ? $el.wrap('<span class="custom-check" />') : $el).parent(),
                type = this._type();

            // set class of type for parent of element
            $custom.addClass(type);

            // set groupName
            this._groupName = _plugin + '_group_' + this._prop('name');

            // set visual state and bind events
            this.update();
            this._bind();
        },
        check: function() {
            // check element
            this._toggleProp('checked', true);
        },
        uncheck: function() {
            // uncheck element
            this._toggleProp('checked', false);
        },
        toggleCheck: function() {
            // toggle checked state of el
            this._toggleProp('checked');
        },
        enable: function() {
            // enable el
            this._toggleProp('disabled', false);
        },
        disable: function() {
            // disable el
            this._toggleProp('disabled', true);
        },
        toggleEnable: function() {
            // toggle disabled state of el
            this._toggleProp('disabled');
        },
        update: function() {
            var $parent = $(this.el).parent();

            // update checked visual state
            if (this._isChecked()) {
                // if el is radio button, uncheck all other radios of same name that el
                if (this._isRadio()) {
                    this._getRadioGroup().each(function() {
                        $(this).parent().removeClass('checked');
                    });
                }

                $parent.addClass('checked');
            } else {
                $parent.removeClass('checked');
            }

            // update disabled visual state
            this._isDisabled() ? $parent.addClass('disabled') : $parent.removeClass('disabled');
        },
        destroy: function() {
            var $el = $(this.el),
                $radioGroup = this._getRadioGroup(),
                destroyRadios = false;

            // verify if need remove destroy custom events on no instantiated radios
            if (this._isRadio()) {
                $radioGroup.not($el).each(function() {
                    destroyRadios = $.data(this, _plugin) ? false : true;
                    return destroyRadios;
                });
            }

            // destroy custom events on no instantiated radios
            if (destroyRadios) {
                $.data(doc, this._groupName, false);
                $radioGroup.off('.' + _plugin + 'Radio');
            }

            // remove custom events on element
            $el.unwrap('.custom-check').off('.' + _plugin);
        },
        _bind: function() {
            var t = this,
                el = t.el,
                $el = $(el),
                elName = t._prop('name'),
                doc_data = $._data(doc);

            // bind the radios that were not instantiated
            if (t._isRadio() && !$.data(doc, t._groupName)) {
                $.data(doc, t._groupName, true);

                t._getRadioGroup().on('change.' + _plugin + 'Radio', function() {
                    $('input[name="' + elName + '"]').not(':checked').parent('.custom-check').removeClass('checked');
                });
            }

            // remove default change event for add plugin change
            $el.off('change.' + _plugin + 'Radio');

            // bind necessary events on element
            var customEvts = {
                    'change': function() {
                        t.update();
                        t._callback();
                    },
                    'mousedown': function() {
                        t._active()
                    },
                    'mouseup': function() {
                        t._active(false)
                    },
                    'focus mouseenter': function() {
                        t._hover()
                    },
                    'blur mouseleave': function() {
                        t._hover(false)
                    }
                },
                customEvts_namespace = {};

            // namespacing events
            $.map(customEvts, function(fn, evt) {
                customEvts_namespace[evt + '.' + _plugin] = fn;
            });

            // attach events on element
            $el.on(customEvts_namespace);

            // set specific labels and events that need bind on plugin
            var $label = $('label[for=' + el.id + ']'),
                labelEvts = ['mouseenter', 'mouseleave', 'mousedown', 'mouseup'];

            // run events of inputs when a label, that "for" attribute reference a input instantiated, run the same event
            for (var e in labelEvts) {
                // scope evt
                !function(evt) {
                    $label.on(evt + '.' + _plugin, function() {
                        $el[evt]();
                    });
                }(labelEvts[e]);
            }

            // prevent multiple equal events on document
            if ($.isEmptyObject(doc_data)) {
                // remove all active inputs on mouseup
                $(doc).on('mouseup', function() {
                    t._active(false);
                });
            }
        },
        _callback: function() {
            // run callbacks
            this._isChecked() ? this.opt.onCheck() : this.opt.onUncheck();
        },
        _toggleProp: function(prop, val) {
            var el = this.el,

                // toggle value of property if val is not defined
                val = val === undefined
                    ? !this._prop(prop)
                    // set value to val if this is boolean
                    : typeof val == 'boolean'
                    ? val
                    : null;

            // prevent error if val dont is boolean
            if (typeof val === 'boolean') {
                // set property value
                this._prop(prop, val);
                this.update();
            }
        },
        _prop: function(prop, val) {
            // set value of prop if val is boolean
            // return state of specific prop
            return typeof val == 'boolean' ? this.el[prop] = val : this.el[prop];
        },
        _evt: function(evt, val) {
            // verify val argument to set add/remove class
            // if the value is not boolean will be set true by default
            var fn = (typeof val == 'boolean' ? val : true) ? 'addClass' : 'removeClass';

            // toggle evt class
            $(this.el).parent()[fn](evt);
        },
        _type: function(type) {
            // return type of element if argument "type" is not defined
            // if defined returns your value
            return type === undefined ? this.el.type : this.el.type == type;
        },
        _hover: function(f) {
            // toggle class 'hover' according to the actual state
            this._evt('hover', f);
        },
        _active: function(f) {
            // toggle class 'active' according to the actual state
            this._evt('active', f);
        },
        _isChecked: function() {
            // returns value of checked state
            return this._prop('checked');
        },
        _isDisabled: function() {
            // returns value of disabled state
            return this._prop('disabled');
        },
        _isRadio: function() {
            // returns a boolean telling el is or not a radio button
            return this._type('radio');
        },
        _isCheckbox: function() {
            // returns a boolean telling el is or not a checkbox
            return this._type('checkbox');
        },
        _getRadioGroup: function() {
            // if el is a radio button, returns one jQuery object with radios with same name
            if (this._type('radio')) {
                return $('input:radio[name="' + this.el.name + '"]');
            }
        }
    }

    // a plugin wrapper around the constructor
    $.fn[_plugin] = function(options) {
        var args = arguments;

        return this.each(function() {
            var data = $.data(this, _plugin),
                method = data ? data[options] : method;

            // instance the plugin
            if (!data || !args.length) {
                $.data(this, _plugin, (data = new Plugin(this, options)));

            // performs a method passing parameters if necessary
            } else if (data instanceof Plugin && typeof method === 'function') {
                method.apply(data, Array.prototype.slice.call(args, 1));

                // allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                    $.removeData(this, _plugin);
                }

            // show error on console if method not exist or is private
            } else if (!method || options.charAt(0) === '_') {
                $.error('Method ' + options + ' does not exist on jQuery.' + _plugin + ' ' + this);
            }
        });
    };
})(jQuery, window, document);