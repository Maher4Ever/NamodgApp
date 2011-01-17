/**
 * Namodg - Ajax contact form
 *
 *
 * Namodg ajax validation - Adapted from http://webcloud.se/log/Form-validation-with-jQuery-from-scratch/
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.3
 * @copyright Copyright (c) 2010, Maher Salam
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

            email : {
                check: function(value) {

                    if(value)
                        return testPattern(value,"([A-Za-z0-9]{1,}([-_\.&'][A-Za-z0-9]{1,}){0,}){1,}@(([A-Za-z0-9]{1,}[-]{0,1})\.){1,}[A-Za-z]{2,6}");
                    return true;
                }
            },
            captcha : {
                check: function(value) {

                    var randNums = $("#question").text().split(" + "), // Get captcha numbers
                    	captcha = (Number(randNums[0]) + Number(randNums[1]));

                    if(value == captcha)
                        return true;
                    else
                        return false;
                }
            },
            required : {

                check: function(value) {

                    if(value)
                        return true;
                    else
                        return false;
                }
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
    var Form = function(form) {

        var fields = [];
			
        form.find(':input[class]').not('[type=submit]').each(function() {

            fields.push(new Field(this));
			
        });
		
        this.fields = fields;
    }
    Form.prototype = {
        validate : function() {

            for(field in this.fields) {

                this.fields[field].validate();
            }
        },
        isValid : function() {

            for(field in this.fields) {

                if(!this.fields[field].valid) {

                    this.fields[field].field.focus();
                    return false;
                }
            }
            return true;
        }
    }

    /*
    Field factory
    */
    var Field = function(field) {

        this.field = $(field);
        this.valid = false;
        this.attach("change");
    }
    Field.prototype = {

        attach : function(event) {

            var obj = this;
            if(event == "change") {
                obj.field.bind("change",function() {
                    return obj.validate();
                });
            }
        },
        validate : function() {

            var obj = this,
            field = obj.field,
			shade = obj.field.parent(),
			tip = shade.prev();
            types = field.attr("class").split(" "),
			errors = 0;

            for (var type in types) {
                if(types[type] in {
                    required : '',
                    email : '',
                    captcha : ''
                }) {
                    var rule = $.Validation.getRule(types[type]);
					if ( ! rule.check(field.val()) ) {
						errors++;	
					}
                }
            }
            if(errors !== 0) {
				shade.addClass('error');
				tip.fadeIn();
                obj.valid = false;
            }
            else {
				shade.removeClass('error');
				tip.fadeOut();
                obj.valid = true;
            }
        }
    }

    /*
    Validation extends jQuery prototype
    */
    $.extend($.fn, {

        validation : function(options) {
			
            var validator = new Form($(this));
						
			var defaults = {
				onSuccess: function() {
					return true;
				},
				onError: function() {
					return false;
				}
			}
			
			var options = $.extend(defaults, options); 
			
            $.data($(this)[0], 'validator', validator);

            $(this).bind("submit", function(e) {
                validator.validate();
                if(!validator.isValid()) {
                    e.preventDefault();
					return options.onError();
                } else {
                    e.preventDefault();
                    return options.onSuccess();
                }
            });
        }
    });
    $.Validation = new Validation();
})(jQuery);