/**
 * Namodg Validation Plugin
 *
 *
 * General-purpose jquery validation plugin - Adapted from http://webcloud.se/log/Form-validation-with-jQuery-from-scratch/
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.4
 * @copyright Copyright (c) 2011, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

(function($) {
    /*
    Validation Singleton
    */
    var Validation = function() {

        var rules = {

            required : {
                check: function(value) {

                    if( $.trim(value).length )
                        return true;
                    else
                        return false;
                },
                message : 'الحقل مطلوب'
            },
            email : {
                check: function(value) {

                    if(value)
                        return testPattern(value, "[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?");
                    return true;
                },
                message: 'الإيميل المدخل غير صحيح'
            },
            number : {
                check : function(value) {

                    if(value)
                        return testPattern(value,"[0-9\u0660-\u0669]+");
                    return true;

                },
                message: 'الحقل يقبل أرقام فقط!'
            }
        }
        var testPattern = function(value, pattern) {

            var regExp = new RegExp("^"+pattern+"$","");
            return regExp.test(value);
        }
        return {

            addRule : function(name, rule) {

                rules[name] = rule;

            },
            getRule : function(name) {

                return rules[name];
            }
        }
    }

    /*
    Form factory
    */
    var Form = function(form, options) {

        var self = this;

        self.fields = [];

        form
            .delegate(options.selector, options.events, function(e) { // attach the events

                var $elem = $(this),
                    localSelf = self,
                    fields = localSelf.fields,
                    id;

                if ( $elem.data('field-id') === undefined ) { // make a new field in case the element was added dynamically

                    id = localSelf.generateID();

                    fields[ id ] = new Field(this, id, options);

                }

                // the event was fired to validate the field, so do it!
                fields[ $elem.data('field-id') ].validate();

            })
            .find(options.selector).each(function() {

                var localSelf = self,
                    id = localSelf.generateID();

                localSelf.fields[ id ] = new Field(this, id, options);

            })
    }
    Form.prototype = {
        isFormValid : function() {
            var fields = this.fields,
                field,
                toBeFocused,
                thisField;

            for(field in fields) {
                thisField = fields[field]
                thisField.validate();
                !thisField.valid && toBeFocused === undefined && (toBeFocused = thisField.field);

            }

            if (toBeFocused) {
                toBeFocused.focus();
                return false;
            }

            return true;
        },
        generateID: function() {
            return String(Math.random()).replace( /\D/g, "" );
        }
    }

    /*
    Field factory
    */
    var Field = function(field, id, options) {

        this.field = $(field).data('field-id', id);
        this.options = options;
        this.valid = false;
    }
    Field.prototype = {
        validate : function() {

            var obj = this,
                field = obj.field,
                fieldData = field.data('validation'),
                types = fieldData && fieldData.split(' ') || [],
                error,
                errorType;

            if(!types.length) {
               return;
            }

            obj.valid = true;

            for (var type in types) {
                var rule = $.Validation.getRule(types[type]),
                    val = field.is(':input') ? field.val() : field.text(); // for contenteditable elements
                if ( !rule.check(val, field) ) {
                    error = rule.message;
                    errorType = types[type];
                    obj.valid = false;
                    break;
                }
            }

            if (obj.options.fireFieldsEvents) {
                if(obj.valid) {
                    obj.options.onFieldSuccess.call(field);
                } else {
                    obj.options.onFieldFail.call(field, error, errorType);
                }
            }
        }
    }

    /*
    Validation extends jQuery prototype
    */
    $.extend($.fn, {

        validation : function(options) {

            var $this = $(this),
                validator,
                defaults = {
                    selector: ':input[data-validation]:not([type=submit])',
                    events: 'change.namodg',
                    fireFieldsEvents: true,
                    fireFormEvents: true,
                    onFieldSuccess: function(obj) {
                        return true;
                    },
                    onFieldFail: function(obj, error, errorType) {
                        return false;
                    },
                    onSuccess: function() {
                        return true;
                    },
                    onFail: function() {
                        return false;
                    }
                }

            options = $.extend(defaults, options);


            validator = new Form($this, options);

            $this.bind('submit', function(e) {

                $this.find(options.selector).each(function() {
                    if ( ! validator.fields[ $(this).data('field-id') ] ) { // make a new field in case the element was added dynamically
                        var id = validator.generateID();
                        validator.fields[ id ] = new Field(this, id, options);
                    }
                });

                if(validator.isFormValid()) {
                    e.preventDefault();
                    return options.onSuccess.call(this);
                } else {
                    e.preventDefault();
                    return options.onFail.call(this);
                }
            });

            return this;
        }
    });
    $.Validation = new Validation();
})(jQuery);
