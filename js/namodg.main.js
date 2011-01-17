/**
 * Namodg - Ajax contact form
 *
 * 
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.3
 * @copyright Copyright (c) 2010, Maher Salam
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


/* Main Script
=========================================================== */

(function (window, $, undefined) {
	
	var document = window.document,
		location = window.location,
		Namodg = {};
	
	Namodg = {
		
		init : function () {
			
			// Get the name of the object and cache it
			var self = this;

			// Cache the content div
			self.content = $('#content');
			
			// Cache the content div's padding
			self.contentPadding = parseInt(self.content.css('paddingTop'), 10) + parseInt(self.content.css('paddingBottom'), 10);
			
			// Cache the form
			self.form = self.content.find('form');
			
			// Insert the loading form and set its object to a var
			self.loading = $('<div class="loading" />').text('الرجاء الإنتظار').insertAfter(self.form);
			
			// Set all inputs to a var
			self.inputs = self.form.find(':input').not(':hidden, :submit');
			
			// Set the submit buttom to a var
			self.button = self.form.find(':submit');
		
			// Add focus classes
			self.inputs
				.focus(function () {
					$(this).parent().addClass('active');	
				})
				.blur(function () {
					$(this).parent().removeClass('active');	
				});
		
			// Add the tips
			self.coolTips();
			
			// Focus the first input - this has to happen after adding the tips!
			self.inputs[0].focus();
			
			// Attach the validation plugin
			self.form.validation({
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
				self.imgsPreloader(['images/response/bg.png', 'images/response/sent-icon.png']);
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
				data: self.form.serialize(),
				dataType: 'json',
				error: function () { // Handle Ajax Errors
					return self.handleAjaxErrors();
				},
				success: function (data) { // Handle Ajax Success
					return self.handleAjaxSuccess(data);
				}
				
			});
				
		},
		
		handleAjaxErrors : function () {
			
			// Get the name of the object and cache it
			var self = this, 
			
			// Create the error div and add it to the dom
				error = $("<div class='ajax-error' style='display: none'>عذراً ، حدثت مشكلة عند محاولة إرسال رسالتك. الرجاء محاولة الإرسال مرة أخرى</div>").insertBefore(self.form),
				
			//	The height is the form + the error div
				errorHeight = self.getHeight(self.form) + Number(error.height());		
			
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
			
			// Insert the response to the dom and hide it
				response = $(data.html).css('display', 'none').insertAfter(self.loading),
				
			// Hide the response img to show it later
				img = response.find('img').css({ 
					top: 30,
					opacity: 0	
				}),
			
			// Get the response height	
				responseHeight = self.getHeight(response);
			
			// Add the response var to Namodg object for further use ( with hash change )
			self.response = response;
			
			// Show the transition
			self.showTransition(self.loading, responseHeight, function () {
				
				// Show the response
				response.fadeIn('fast', function () {
					
					// Fade the response img
					img.animate({
						top: 45,
						opacity: 1
					}, {duration: 'slow', easing: 'easeOutBack'});	
					
				});
				
				// Add the current page title to Namodg object to be able to revert to it later
				self.documentTitle = document.title;
				
				// Change the title of the page and the hash based on the class of the response
				if (response.hasClass('success')) {	
					document.title += " - " + data.pageTitle;
					window.location.hash = "sent";	
				} else {
					document.title += " - " + data.pageTitle;
					window.location.hash = "error";
				}
				
				// Make the form ready to accept new requests
				$.data(self.form, 'state', 'ready'); 
			
			});
			
		},
		
		handleHashChange : function () {
			
			// Get the name of the object and cache it
			var self = this,
			
			// Get the new hash
				hash = window.location.hash,
				
			// Cache the form height var
				formHeight = '';
			
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

			// Change the page title back to the default
			if (self.documentTitle && self.documentTitle !== undefined) {
				document.title = self.documentTitle;
			}
					
			// Reset all inputs in the form
			self.inputs.val('').removeAttr('checked').removeAttr('selected');
			
			// Get the height of the form
			formHeight = self.getHeight(self.form);
			
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
				
				// Change the current hash to the new one
				self.currentHash = hash;
			
			});
		},
		
		showTransition : function (obj, newHeight, callback) {
			
			var self = this, // Get the name of the object and cache it
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
						
						// Remove the height from the content div to make it adjust itself with the animations
						self.content.removeAttr('style');
				
						// Run the callback function
						return callback();
					}
						
				});
			});
			
		},
		
		getHeight : function (jQueryObj) {
			
			// Calculate the height of the content
			return Number(jQueryObj.height()) + this.contentPadding;
				
		},
		
		coolTips : function () {
			
			// Process every element with the tip function
			$.each(this.inputs, function () {
				
				var el = $(this),
					title = el.attr('title'),
					tip = '',
					thisTip = '';
				
				// Stop the function if there is no title in the element
				if (!title) {
					return;	
				}
				
				// Create the tip and add the text to it
				tip = $('<p class="tip" />').text(title);
				
				// Get and cache the current tip. while searching for it, add the tip to the DOM
				thisTip =  el.parent().before(tip).prev();
				
				// Remove the title from the element, bind to events to show and hide the tips
				el
					.removeAttr('title')
					.focus(function () {
						thisTip.stop(true, true).hide().fadeIn();
					})
					.blur(function () {
						thisTip.stop(false, true).fadeOut();
					});	
			});
				
		},
		
		imgsPreloader : function (img) {
			
			var tmpImg,
				i = 0,
				len = img.length;	
			
			// Check if the parameter is an array
			if ($.isArray(img)) {
				
				// iterate over all array's element
				for (; i < len; i += 1) {
					
					// Create a new img object
					tmpImg = new Image();
					
					// Add the source to that object so the browser will download it
					tmpImg.src = img[i];
				}
			
			// If the parameter is just a string
			} else {
				
				// Create a new img object
				tmpImg = new Image();
				
				// Add the source to that object so the browser will download it
				tmpImg.src = img;
					
			}
		}

	};
	
	// Make Namodg object global
	window.Namodg = Namodg;
	
}(this, jQuery));

$(function() {
	
	// Run the script!
	Namodg.init();
	
})