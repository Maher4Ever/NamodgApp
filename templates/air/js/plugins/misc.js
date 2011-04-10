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

/* Plugins
=========================================================== */

// jQuery hashchange event - v1.3 - 7/21/2010 - http://benalman.com/projects/jquery-hashchange-plugin/
(function($,e,b){var c="hashchange",h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}$.browser.msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);

// jQuery easing plugin - Only the used effects in namodg (easeOutBack, easeOutExpo) - http://gsgd.co.uk/sandbox/jquery/easing/
jQuery.extend(jQuery.easing,{easeOutBack:function(f,a,b,c,d,e){if(e==undefined)e=1.70158;return c*((a=a/d-1)*a*((e+1)*a+e)+1)+b},easeOutExpo:function(f,a,b,c,d){return a==d?b+c:c*(-Math.pow(2,-10*a/d)+1)+b}});

// Namodg Select Styler - v1 - Maher Salam
// Namodg Select Styler - v1 - Maher Salam
(function ($) {
    $.fn.extend({
        styleNamodgSelects : function (options) {

            // Set the defaults for the options' div
            var defaults = {
                    optionsTop : 0,
                    optionsRight : 0
                },

           // Extend options with user inputs
                options = $.extend(defaults, options);

            // Start rolling
            return this.each(function () {

                /*
                 * Declaring vars
                 */

                // Cache the select element and hide it
                var $selectElem = $(this).css('display', 'none'),

                // Make a div which will be used offline and then inserted to the DOM
                    $namodgSelect = $('<div class="namodg-select"></div>'),

                // Get the first selected option
                    $selectedOption = $selectElem.find('option:selected').eq(0),

                // Make a toggler wrapper then set it's status to the data.
                // The data is later to know if it's active or not becaue that's faster then checking the css display
                    $toggler = $('<a class="toggler" href="#"><div class="toggle-button"></div></a>').data('active', false).appendTo($namodgSelect),

                // Make an element which will contain the selected option
                    $selectedHolder = $('<p class="selected"></p>').text($selectedOption.text()).prependTo($toggler),

                // Make an element which will contain the options, and set status data for optimizing checking that info later on
                    $optionsHolder = $('<ul class="options"></ul>').data('hidden', true).appendTo($namodgSelect),

                // An empty array for the options
                    optionElements = [],

                // I don't think this one needs explaination :)
                    i = 0;

                /*
                 * The guts!
                 */

                $selectElem // Fill the options empty array, then remove all the non-selected options (so that only one will remain)
                    .find('option')
                        .each(function () {
                            // Remove empty options
                            if (! $.trim(this.value) ){
                                return;
                            }
                            optionElements[i++] = '<li><a class="option" href="#" index="' + i + '" data-value="' + this.value + '">' + $(this).text() + '</a></li>';
                        })
                    .not(':selected')
                        .remove()
                .end() // Back to the select element, now attach a click event to the lapel of this element (to preserve it's functionality)
                    .closest('form')
                        .find('label[for='+ $selectElem.attr('id') + ']')
                            .click(function () {
                               $selectedHolder.click();
                            });

                $optionsHolder // Fill the options container with the options, then set it's position
                    .html( optionElements.join('') )
                    .css({
                        top: 0 + options.optionsTop,
                        right: 0 + options.optionsRight
                    });


                $namodgSelect

                    // Add the offline div to the dom
                    .insertAfter($selectElem)

                    // We can't use overflow:hidden to make the div contain it's floted children
                    // because of the options' div, do we add a clear div
                    .after('<div style="clear: both" />');


                // If there are no options, disable clicks and stop!
                if ( ! optionElements.length ) {

                    $toggler
                        .click(function (e) {
                            e.preventDefault();
                        });

                    return;
                }

                $namodgSelect // Handle all needed events from the wrapper
                    .delegate('a', 'click focus blur', function (e) {

                        // Type of the event
                        var type = e.type,

                        // Declare other vars
                            id,
                            $this;

                        e.preventDefault(); // Stop default action

                        // Make an id ot the element using it's tag name and it's class name
                        // Note: Because we add a class on the active toggler, it's easier to remove it from here and the logic will still work
                        id = e.target.tagName.toLowerCase() + '.' + e.target.className.replace(' toggler-active', '');

                        switch (id) {

                            case 'p.selected': case 'div.toggle-button':

                                // Only accept 'click'  on p and div
                                if ( type != 'click') {
                                    return;
                                }

                                // Show and hide the options holder
                                if ( $optionsHolder.data('hidden') ) {

                                    $selectElem.focus();

                                    // This needs to run fast to give feedback to the user
                                    $toggler.addClass('toggler-active').data('active', true);

                                    // Show the options div
                                    $optionsHolder.stop(true, true).slideDown('fast', function () {

                                        // Sometimes fast clicking makes the toggler deavtive, so show it in that case
                                        if ( ! $toggler.data('active') ) {
                                           $toggler.addClass('toggler-active').data('active', true);
                                        }

                                    }).data('hidden', false);

                                } else {

                                    $selectElem.blur();

                                    // Hide the options div
                                    $optionsHolder.stop(true, true).slideUp(function () {

                                        // Only hide the toggler if it's active
                                        if ( $toggler.data('active') ) {
                                           $toggler.removeClass('toggler-active').data('active', false);
                                        }

                                    }).data('hidden', true);

                                }
                                break;

                            case 'a.toggler':
                                switch (type) {
                                    case 'focusin':
                                        $selectElem.focus();
                                        break;
                                    case 'focusout':
                                        // Only blur when the options div is deactive
                                        if ( $optionsHolder.data('hidden') ) {
                                            $selectElem.blur();
                                        }
                                        break;
                                    case 'click':
                                        $selectedHolder.click();
                                        $selectElem.focus();
                                }
                                break;

                           case 'a.option':
                               // Stop accept click events
                               if ( type != 'click') {
                                    return;
                                }

                                // cache this element
                                $this = $(this);

                                // Change the value of the selected option
                                $selectedOption.val( $this.data('value') );

                                // Trigger a change event
                                $selectElem.change();

                                // Change the text of the fake select and trigger a click on it
                                $selectedHolder.text( $this.text() ).click();

                                break;
                        }
                    })

                    // Hide the options menu when there is a click outside
                    $(document).click(function () {

                        // Do nothing if the options menu is hidden or being slided
                        if ( $optionsHolder.data('hidden') || $optionsHolder.is(':animated') ) {
                            return;
                        }

                        // Trigger a click on the select holder
                       $selectedHolder.click();
                    })
            });
        }
    });

}(jQuery));