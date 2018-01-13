
/*
 * Rascals Themes Effects Scripts
 *
 * Copyright (c) 2014
 * Rascals Themes 2014
 *
 */
 


/*
 * ResVid ver. 1.0.0
 * jQuery Responsive Video Plugin
 *
 * Copyright (c) 2014
 * Rascals Themes 2014
 *
 */

(function($){

 	$.fn.extend({ 
 		
		//pass the options variable to the function
 		ResVid: function(options) {


			//Set the default values, use comma to separate the settings, example:
			var defaults = {
				syntax : ''
			}
				
			var options =  $.extend(defaults, options);

    		return $('iframe', this).each(function(i) {
				var 
					$o = options,
					$iframe = $(this);
					$players = /www.youtube.com|player.vimeo.com/;
				
				if ($iframe.attr('src').search($players) > 0) {

					// Ratio
					var $ratio = ($iframe.height() / $iframe.width()) * 100;

					// Add some CSS to iframe
					$iframe.css({
						position : 'absolute',
						top : '0',
						left : '0',
						width : '100%',
						height : '100%'
					});

					// Add wrapper element
					$iframe.wrap('<div class="video-wrap" style="width:100%;position:relative;padding-top:'+$ratio+'%" />');
				}
				
				
			
    		});
    	}
	});
	
})(jQuery);