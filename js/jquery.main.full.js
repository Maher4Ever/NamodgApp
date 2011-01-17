// C O O L W O R L D S . NET
//
// Script Name: coolContact - Contact Form
// Version: 1.1
// Author: Maher Salam
// Author URI: http://www.coolworlds.net 
	
// Tipsy Tooltip - http://onehackoranother.com/projects/jquery/tipsy/
(function($) {
    $.fn.tipsy = function(options) {

        options = $.extend({}, $.fn.tipsy.defaults, options);
        
        return this.each(function() {
            
            var opts = $.fn.tipsy.elementOptions(this, options);
            
            $(this).hover(function() {

                $.data(this, 'cancel.tipsy', true);

                var tip = $.data(this, 'active.tipsy');
                if (!tip) {
                    tip = $('<div class="tipsy"><div class="tipsy-inner"/></div>');
                    tip.css({position: 'absolute', zIndex: 100000});
                    $.data(this, 'active.tipsy', tip);
                }

                if ($(this).attr('title') || typeof($(this).attr('original-title')) != 'string') {
                    $(this).attr('original-title', $(this).attr('title') || '').removeAttr('title');
                }

                var title;
                if (typeof opts.title == 'string') {
                    title = $(this).attr(opts.title == 'title' ? 'original-title' : opts.title);
                } else if (typeof opts.title == 'function') {
                    title = opts.title.call(this);
                }

                tip.find('.tipsy-inner')[opts.html ? 'html' : 'text'](title || opts.fallback);

                var pos = $.extend({}, $(this).offset(), {width: this.offsetWidth, height: this.offsetHeight});
                tip.get(0).className = 'tipsy'; // reset classname in case of dynamic gravity
                tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).appendTo(document.body);
                var actualWidth = tip[0].offsetWidth, actualHeight = tip[0].offsetHeight;
                var gravity = (typeof opts.gravity == 'function') ? opts.gravity.call(this) : opts.gravity;

                switch (gravity.charAt(0)) {
                    case 'n':
                        tip.css({top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}).addClass('tipsy-north');
                        break;
                    case 's':
                        tip.css({top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}).addClass('tipsy-south');
                        break;
                    case 'e':
                        tip.css({top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}).addClass('tipsy-east');
                        break;
                    case 'w':
                        tip.css({top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}).addClass('tipsy-west');
                        break;
                }

                if (opts.fade) {
                	if ($.browser.msie && $.browser.version < 7) {
                		 tip.css({visibility: 'visible'});
                	} else {
                		tip.css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: 0.8});	
                	}
                } else {
                    tip.css({visibility: 'visible'});
                }

            }, function() {
                $.data(this, 'cancel.tipsy', false);
                var self = this;
                setTimeout(function() {
                    if ($.data(this, 'cancel.tipsy')) return;
                    var tip = $.data(self, 'active.tipsy');
                    if (opts.fade) {
                    	if ($.browser.msie && $.browser.version < 7) {
                    		tip.remove();
                    	} else {
                    		tip.stop().fadeOut(function() { $(this).remove(); });
                    	}
                    } else {
                        tip.remove();
                    }
                }, 100);

            });
            
        });
        
    };
    
    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };
    
    $.fn.tipsy.defaults = {
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        title: 'title'
    };
    
    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };
    
    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };
    
})(jQuery);
// End - Tipsy Tooltip

// Main Javascript
$(document).ready(function() {
	
	// Add focus class to IE6 and before
	if ($.browser.msie && $.browser.version < 8) {
		var focused = $("input, textarea");
		focused.focus(function(){
			$(this).addClass("focus");
		});
		focused.blur( function(){
			$(this).removeClass("focus");
		});
	}
	
	$('input:first').focus(); // focus the first input
	
	$('input,textarea').tipsy({gravity: 'e',fade: true});$('acronym').tipsy({gravity: 'n',fade: true}); // Tooltip trigger
	
	$.validator.methods.equal = function(value, element, param) {
		return value == param; // Define the type of the security value
	};

	var randNums = $("#main").find("acronym").text(); // Get the security number
	var randNum = randNums.split(" + "); // Result with the numbers as strings in array
	var num1 = randNum[0] * 1; // convert to number
	var num2 = randNum[1] * 1; // convert to number
	var securityNum = (num1 + num2);
	
	// The Validator
	$("#contact").validate({
		errorPlacement: function(error) {error.hide();}, // Hide errors' generated text and label
		rules:{security: {equal: securityNum}},
		submitHandler: function ajaxSend(){	
				// Ajax Send
				var ajax_load = "<img class='loading' src='img/load.gif' alt='Ì „ «·«—”«·...' />";
	
				var str = $("#contact").serialize(); // Get data but in UTF-8 to send with POST
				
				$("#main").fadeOut('normal', function() {
				
				$(this).html(ajax_load).fadeIn();
				
				$.ajax({
					cache: false,
					type: "POST",
					url: "send_core.php",
					data: str,
					success: function(data) {
					
					document.title = document.title + " -  „ ≈—”«· —”«· ﬂ »‰Ã«Õ"; // Change the title of the page

						$("#main")
							.addClass('success')
							.html(data)
								.find("img").css('display', 'none').fadeIn(1200);
						}
					});
				
				});
				// End - Ajax Send
		}
			
	});
	// End - The Validator
});
// End - Main Javascript