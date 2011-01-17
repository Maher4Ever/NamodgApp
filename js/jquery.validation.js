/*
 * C O O L W O R L D S . NET
 *
 * Script Name: coolContact - Contact Form
 * Version: 1.2
 * Date: 30/07/2010
 * Author: Maher Salam
 * Author's URL: http://www.coolworlds.net 
 * 
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
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
			security : {
			     check: function(value) {
					 
					var randNums = $("acronym").text().split(" + "), // Get the security number
						num1 = randNums[0] * 1, // convert to number
						num2 = randNums[1] * 1, // convert to number
						securityNum = (num1 + num2);
						
                   if(value == securityNum)
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
        form.find("input[class], textarea[class], select[class]").each(function() {
            
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
            if(event == "keyup") {
                obj.field.bind("keyup",function(e) {
                    return obj.validate();
                });
            }
        },
        validate : function() {
            
            var obj = this,
                field = obj.field,
                types = field.attr("class").split(" "),
                errors = [],
				newError;
			
            for (var type in types) {
				if(types[type] in { 'required':'','email':'','security':''}) {
					var rule = $.Validation.getRule(types[type]);
					if(!rule.check(field.val())) {
						errors.push(rule.msg);
					};
				}
            }
            if(errors.length) {

				field.addClass('error');
                obj.valid = false;
            } 			
            else {
				field.removeClass('error');
                obj.valid = true;
            }
        }
    }
    
    /* 
    Validation extends jQuery prototype
    */
    $.extend($.fn, {
        
        validation : function() {
            
            var validator = new Form($(this));
            $.data($(this)[0], 'validator', validator);
            
            $(this).bind("submit", function(e) {
                validator.validate();
                if(!validator.isValid()) {
                    e.preventDefault();
                } else {
					e.preventDefault();
					return success();
				}
            });
			
        }
    });
    $.Validation = new Validation();
})(jQuery);