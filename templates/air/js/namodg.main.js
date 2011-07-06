/**
 * Namodg - Ajax Forms Generator
 *
 * @desc Namodg allows developers to make ajax-driven forms easily. It uses OOP aproach,
 *       which means developers has to write less code!
 * @author Maher Salam <admin@namodg.com>
 * @link http://namodg.com
 * @copyright Copyright (c) 2010-2011, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/* Namodg Main Script
=========================================================== */

(function (window, $, undefined) {

    var document = window.document,
        location = window.location;

    var Namodg = {

        init : function () {

            // Get the name of the object and cache it
            var self = this,
                selectsStylerOptions = {};

            // Cache the content div
            self.content = $('#content');

            // Cache the form
            self.form = self.content.find('form');

            // Insert the loading form and set its object to a var
            self.loading = $('<div class="loading" />').insertAfter(self.form);
            
            // Add the loading text
            self.loading.text(self.lang.phrases['loading']);
            
            // Set all inputs to a var
            self.inputs = self.form.find(':input').not(':hidden, :submit');

            // Set the submit buttom to a var
            self.button = self.form.find(':submit');
            
            // Build the NamodgSelects options
            selectsStylerOptions['optionsTop'] = 38;
            selectsStylerOptions['options' + (self.lang.ltr ? 'Left' : 'Right')] = -6;

            // Add focus classes
            self.inputs
                .filter('select')
                    .styleNamodgSelects(selectsStylerOptions)
                .end()
                .focus(function () {
                    $(this).closest('.shade').addClass('active');
                })
                .blur(function () {
                    $(this).closest('.shade').removeClass('active');
                });

            // Add the tips
            self.coolTips();

            // Focus the first input - this has to happen after adding the tips!
            self.inputs[0].focus();

            // Add Captcha rule to the validation plugin
            $.Validation.addRule('captcha', {
                check: function(value, field) {

                    // Get the two numbers
                    var randNums = field.prev().text().split(" + "),

                    // Do simple math :)
                    	captcha = (Number(randNums[0]) + Number(randNums[1]));

                    // Return true if the value equals the captcha
                    if(value == captcha)
                        return true;
                    else
                        return false;
                }
            });

            // Attach the validation plugin
            self.form
                .validation({
                    // Handle Field Errors
                    onFieldFail: function () {
                        var shade = $(this).closest('.shade'),
                            tip = shade.prev().prev();

                        shade.addClass('error');
                        tip.fadeIn();
                    },
                    // Remove the error class if it has been set
                    onFieldSuccess: function () {
                        var shade = $(this).closest('.shade'),
                            tip;
                        
                        if ( ! shade.hasClass('error') ) {
                            return;
                        }

                        tip = shade.prev().prev();

                        shade.removeClass('error')
                        tip.fadeOut();
                    },
                    // If everythig is alright
                    onSuccess: function () {
                        return self.preSend();
                    }
                });
            
            // Browser Back Button Fix
            $(window).bind('hashchange', function () {

                // call the function to handle this event
                return self.handleHashChange();
            });

            // Preload the respond imgs when the html and imgs are already loaded
            $(window).load(function () {
                self.imgsPreloader(['templates/air/images/response/bg.png', 'templates/air/images/response/success-icon.png']);
            });

        },

        preSend : function () {

            // Get the name of the object and cache it
            var self = this,

            // Get the height of the loading div
                loadingHeight = self.getHeight(self.loading);

            // Stop multiple submission requests
            if ($.data(self.form, 'state') === 'sending') {
                return;
            }

            // Change the state of this form to the sending state
            $.data(self.form, 'state', 'sending');

            // Fix a tooltip problem where sending doesn't hide the tip
            self.inputs.trigger('blur');

            // Fade the submit button, then start the other animations
            self.button.fadeOut('fast', function () {

                // Show the transition
                self.showTransition(self.form, loadingHeight, function () {

                    // Show the loading form
                    self.loading.fadeIn();
                    
                    // Send the data to be processed
                    return self.send();

                });

            });

        },

        send : function () {

            // Get the name of the object and cache it
            var self = this;

            // Start jQuery ajax request
            $.ajax({
                    cache: true,
                    type: self.form.attr('method') || 'POST',
                    url: self.form.attr('action'),
                    data: self.form.serialize()
                })
                .done(function(response) {
                    self.handleAjaxSuccess(response);
                })
                .fail(function() {
                    self.handleAjaxErrors();
                });

        },

        handleAjaxErrors : function () {

            // Get the name of the object and cache it
            var self = this,

            // Create the error div and add it to the dom
                error = $("<div class='ajax-error' style='display: none'></div>").text( self.lang.phrases['ajax_error'] ).insertBefore(self.form),

            //	The height is the form + the error div
                errorHeight = self.getHeight(self.form) + self.getHeight(error);

            // Show the transition
            self.showTransition(self.loading, errorHeight, function () {

                // Show the form
                self.form[0].style.display = 'block';

                // Show the button
                self.button[0].style.display = 'block';

                // Show the error
                error[0].style.display = 'block';

                // hide and remove the error after some time
                window.setTimeout(function () {

                    // Hide the error div
                    error.slideUp(function () {

                        // Remove the error div from the DOM
                        $(this).remove();

                        // Make the form ready to accept new requests
                        $.data(self.form, 'state', 'ready');
                    });

                }, 3000);
            });

        },

        handleAjaxSuccess : function (data) {
            
            // Get the name of the object and cache it
            var self = this,

            // Convert the data to jQuery object
                $data = $(data),

            // Get the page title
                title = $data.filter('title').text(),

            // Get the response div, hide it, insert it into the dom and then stor it to a var
                response = $data.find('#response').css('display', 'none').insertAfter(self.loading),

            // Hide the response img to show it later
                img = response.find('img').css({
                    top: 30,
                    opacity: 0
                }),

            // Get the response height
                responseHeight = self.getHeight(response);
            
            // Change the button link to a simulation of a browser back functionality, to enable the animation
            response.find('.button').attr('href', 'javascript:history.back()');

            // Add the response var to Namodg object for further use ( with hash change )
            self.response = response;

            // Show the transition
            self.showTransition(self.loading, responseHeight, function () {

                // Show the response
                response.fadeIn('normal', function () {

                    // Fade the response img
                    img.animate({
                        top: 45,
                        opacity: 1
                    }, {
                        duration: 'slow',
                        easing: 'easeOutBack'
                    });

                });

                // Add the current page title to Namodg object to be able to revert to it later
                self.documentTitle = document.title;

                // Change the title of the page and the hash based on the class of the response
                if (response.hasClass('success')) {
                    location.hash = "sent";
                } else {
                    location.hash = "error";
                }

                // Change the document title to the new one
                document.title = title;
                
                // Make the form ready to accept new requests
                $.data(self.form, 'state', 'ready');

            });

        },

        handleHashChange : function () {

            // Get the name of the object and cache it
            var self = this,

            // Get the new hash
                hash = location.hash.replace('#', ''),

            // Are we in home ?
                home = (self.form.css('display') === 'block') ? true : false;

            // Don't change anything if we are on the contact page
            if (home) {

                // Focus the first input
                self.inputs[0].focus();

                return;
            }

            // Exit if we are not on a known hash, or the new hash is not empty
            if ( (self.currentHash !== '#error' || self.currentHash !== '#sent') && (hash.length !== 0)) {
                return;
            }

            // Change the current hash to the new one
            self.currentHash = hash;

            // Show the form
            self.showHome();
        },

        showHome: function () {

            // Get the name of the object and cache it
            var self = this,

            // Cache the form height var
                formHeight = this.getHeight(self.form);

            // Change the page title back to the default
            if (self.documentTitle && self.documentTitle !== undefined) {
                document.title = self.documentTitle;
            }

            // Reset all inputs in the form
            self.inputs
                .not('select')
                    .val('');

            // Show the transition
            self.showTransition(self.response, formHeight, function () {

                // Show the form
                self.form.fadeIn('fast', function () {

                    // Show the button
                    self.button[0].style.display = 'block';

                    // Focus the first input
                    self.inputs[0].focus();

                });

                // Remove the old response from the DOM
                self.response.remove();
                
            });
        },

        showTransition : function (obj, newHeight, callback) {

            // Get the name of the object and cache it
            var self = this, 
                height = self.getHeight(self.loading);

            // Change the height of the content div to 2/3 of the new height (for the bounce effect)
            self.content.animate({
                height: Math.floor((height * 2) / 3)
            }, {
                duration: 'normal',
                easing: 'easeOutExpo'
            });

            // Hide the loading div
            obj.fadeOut('fast', function () {

                // Change the content height to the acctual height of the new div
                self.content.animate({
                    height: newHeight
                }, {
                    duration: 'normal',
                    easing: 'easeOutExpo',
                    complete: function () {

                        // Remove the height from the content div to make it adjust itself with the animations when needed
                        self.content.removeAttr('style');

                        // Run the callback function
                        return callback();
                    }

                });
            });

        },

        getHeight : function (jQueryObj) {
            return Number(jQueryObj.height()) + this.getPadding(jQueryObj) + this.getMarign(jQueryObj);
        },

        getPadding: function (jQueryObj) {
            return parseInt(jQueryObj.css('paddingTop'), 10) + parseInt(jQueryObj.css('paddingBottom'), 10);
        },

        getMarign: function (jQueryObj) {
             return parseInt(jQueryObj.css('marginTop'), 10) + parseInt(jQueryObj.css('marginBottom'), 10);
        },

        coolTips : function () {

            var self = this;

            // Process every element with the tip function
            $.each(this.inputs, function () {

                // Store the field
                var field = $(this),

                // Declare the tip container
                    tip = {};

                // Stop the function if there is no title in the element
                if (field.attr('title').length == 0) {
                    return;
                }

                // Get and cache the current tip.
                tip = self.form.find('label[for=' + field.attr('id') + ']').prev().css('display', 'none');

                // Remove the title from the field, bind to events to show and hide the tips
                field
                    .removeAttr('title')
                    .focus(function () {
                        tip.stop(true, true).hide().fadeIn();
                    })
                    .blur(function () {
                        tip.stop(false, true).fadeOut();
                    });
            });

        },

        imgsPreloader : function (img) {

            var tmpImg,
                i = 0,
                len = img.length;

            // Check if the parameter is an array
            if ($.isArray(img)) {

                // iterate over all array's elements
                for (; i < len; i += 1) {

                    // Create a new img object
                    tmpImg = new Image();

                    // Add the source to that object to make the browser download it
                    tmpImg.src = img[i];
                }

            // If the parameter is just a string
            } else {

                // Create a new img object
                tmpImg = new Image();

                // Add the source to that object to make the browser download it
                tmpImg.src = img;

            }
        }

    };

    // Make Namodg object global
    window.Namodg = $.extend(window.Namodg, Namodg);

}(this, jQuery));

$(function() {

    // Run the script!
    Namodg.init();

})