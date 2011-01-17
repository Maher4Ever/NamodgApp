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

// jQuery hashchange event - v1.2 - 2/11/2010 - http://benalman.com/projects/jquery-hashchange-plugin/
(function($,i,b){var j,k=$.event.special,c="location",d="hashchange",l="href",f=$.browser,g=document.documentMode,h=f.msie&&(g===b||g<8),e="on"+d in i&&!h;function a(m){m=m||i[c][l];return m.replace(/^[^#]*#?(.*)$/,"$1")}$[d+"Delay"]=100;k[d]=$.extend(k[d],{setup:function(){if(e){return false}$(j.start)},teardown:function(){if(e){return false}$(j.stop)}});j=(function(){var m={},r,n,o,q;function p(){o=q=function(s){return s};if(h){n=$('<iframe src="javascript:0"/>').hide().insertAfter("body")[0].contentWindow;q=function(){return a(n.document[c][l])};o=function(u,s){if(u!==s){var t=n.document;t.open().close();t[c].hash="#"+u}};o(a())}}m.start=function(){if(r){return}var t=a();o||p();(function s(){var v=a(),u=q(t);if(v!==t){o(t=v,u);$(i).trigger(d)}else{if(u!==t){i[c][l]=i[c][l].replace(/#.*/,"")+"#"+u}}r=setTimeout(s,$[d+"Delay"])})()};m.stop=function(){if(!n){r&&clearTimeout(r);r=0}};return m})()})(jQuery,this);

// Tipsy Tooltip - http://onehackoranother.com/projects/jquery/tipsy/
(function($){$.fn.tipsy=function(options){options=$.extend({},$.fn.tipsy.defaults,options);return this.each(function(){var opts=$.fn.tipsy.elementOptions(this,options);$(this).hover(function(){$.data(this,'cancel.tipsy',true);var tip=$.data(this,'active.tipsy');if(!tip){tip=$('<div class="tipsy"><div class="tipsy-inner"/></div>');tip.css({position:'absolute',zIndex:100000});$.data(this,'active.tipsy',tip)}if($(this).attr('title')||typeof($(this).attr('original-title'))!='string'){$(this).attr('original-title',$(this).attr('title')||'').removeAttr('title')}var title;if(typeof opts.title=='string'){title=$(this).attr(opts.title=='title'?'original-title':opts.title)}else if(typeof opts.title=='function'){title=opts.title.call(this)}tip.find('.tipsy-inner')[opts.html?'html':'text'](title||opts.fallback);var pos=$.extend({},$(this).offset(),{width:this.offsetWidth,height:this.offsetHeight});tip.get(0).className='tipsy';tip.remove().css({top:0,left:0,visibility:'hidden',display:'block'}).appendTo(document.body);var actualWidth=tip[0].offsetWidth,actualHeight=tip[0].offsetHeight;var gravity=(typeof opts.gravity=='function')?opts.gravity.call(this):opts.gravity;switch(gravity.charAt(0)){case'n':tip.css({top:pos.top+pos.height,left:pos.left+pos.width/2-actualWidth/2}).addClass('tipsy-north');break;case's':tip.css({top:pos.top-actualHeight,left:pos.left+pos.width/2-actualWidth/2}).addClass('tipsy-south');break;case'e':tip.css({top:pos.top+pos.height/2-actualHeight/2,left:pos.left-actualWidth}).addClass('tipsy-east');break;case'w':tip.css({top:pos.top+pos.height/2-actualHeight/2,left:pos.left+pos.width}).addClass('tipsy-west');break}if(opts.fade){if($.browser.msie&&$.browser.version<7){tip.css({visibility:'visible'})}else{tip.css({opacity:0,display:'block',visibility:'visible'}).animate({opacity:0.8})}}else{tip.css({visibility:'visible'})}},function(){$.data(this,'cancel.tipsy',false);var self=this;setTimeout(function(){if($.data(this,'cancel.tipsy'))return;var tip=$.data(self,'active.tipsy');if(opts.fade){if($.browser.msie&&$.browser.version<7){tip.remove()}else{tip.stop().fadeOut(function(){$(this).remove()})}}else{tip.remove()}},100)})})};$.fn.tipsy.elementOptions=function(ele,options){return $.metadata?$.extend({},options,$(ele).metadata()):options};$.fn.tipsy.defaults={fade:false,fallback:'',gravity:'n',html:false,title:'title'};$.fn.tipsy.autoNS=function(){return $(this).offset().top>($(document).scrollTop()+$(window).height()/2)?'s':'n'};$.fn.tipsy.autoWE=function(){return $(this).offset().left>($(document).scrollLeft()+$(window).width()/2)?'e':'w'}})(jQuery);

// Main Javascript
$(document).ready(function() {
	
	// Define vars
	var ajax_load, $error_element, hash,
		$Inputs = $("input,textarea"),
		$main = $("#main"),
		$contact = $("#contact");
	
	// Add focus class to IE7 and before
	if ($.browser.msie && $.browser.version < 8) {
		focused = $("input, textarea");
		focused.focus(function() {
			$(this).addClass("focus");
		});
		focused.blur(function() {
			$(this).removeClass("focus");
		});
	}
	
	$('input:first').focus(); // focus the first input
	
	$('input,textarea').tipsy({gravity: 'e', fade: true});$('acronym').tipsy({gravity: 'n', fade: true}); // Tooltip trigger
	
	$contact.validation(); // Validate Form
	
	success = function() { // Ajax Send
		
		ajax_load = "<img class='loading' src='img/load.gif' alt='Ì „ «·«—”«·...' />",
		str = $contact.serialize(); // Get data but in UTF-8 to send with POST
		$main.fadeOut('normal', function() {
		
			$(this).html(ajax_load).fadeIn();
			
			$.ajax({ // Ajax request
				cache: false,
				type: "POST",
				url: "inc/send_core.php",
				data: str,
				success: function(data) {
				
					$error_element = $(data).filter("img");
					
					if ($error_element.hasClass('error_img')) {
						document.title = document.title + " - ÕœÀ Œÿ√"; // Change the title of the page
						location.hash = "error";	
					} else {
						document.title = document.title + " -  „ ≈—”«· —”«· ﬂ »‰Ã«Õ"; // Change the title of the page
						location.hash = "sent";
					}
						
					$main.fadeOut('normal', function() {	
						$(this).addClass('respond')
						.html(data)
						.fadeIn()
							.find("img").css('display', 'none').delay(250).fadeIn(1200);
					})
				}
				
			}); // End - Ajax request
		
		});
		
	}; // End -  Ajax Send 

	
	// Browser Back Button Fix 
	$(window).bind('hashchange', function() {
		 hash = window.location.hash;	 
		 if(hash !== "#sent" && hash !== "#error"){ 
			 location.reload(true);
		 }		 
	 }); // End - Browser Back Button Fix 
	
	
}); // End - Main Javascript
