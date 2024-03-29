/*
 * jQuery scrollExtend plugin v1.0.1
 * 
 * Copyright (c) 2009 Jim Keller
 * Context - http://www.contextllc.com
 * 
 * Dual licensed under the MIT and GPL licenses.
 *
 */

//
// onScrollBeyond
//	
jQuery.fn.onScrollBeyond = function(callback, options) {
	
	var domTargetElement = this;

	//
	// Special actions
	//
	if ( callback == 'disable' ) {
		jQuery(domTargetElement).data('onScrollBeyond-disabled', true);
		return;
	}
	
	if ( callback == 'enable' ) {
		jQuery(domTargetElement).data('onScrollBeyond-disabled', false);
		return;
	}

	//
	// Main Body
	//
       	var settings = {
       		'buffer': 20,
       		'fireOnDocEnd': true,
       		'fireOnBeyondElement' : true
       	};
	

	jQuery.extend(settings, options);

	jQuery(window).bind('scroll', 
		function() {

			var fire = false;
			var jqTargetElement = jQuery(domTargetElement);

			if ( jqTargetElement.data('onScrollBeyond-disabled') == true ) {
				return; 
			}
			
			if ( settings.fireOnBeyondElement ) {
				
				// if element has scrolled off the screen, even if other elements exist below it
				if ( jQuery(document).scrollTop() > (jqTargetElement.position().top + jqTargetElement.height()) ) {
					fire = true;
				}
			
			}
			
			if ( !fire && settings.fireOnDocEnd ) {
			
				var amt_scrolled = jQuery(document).scrollTop() - jqTargetElement.position().top ;
				
				// if the amount of the element we already scrolled beyond + its top position on the document + the window height + some buffer is greater than the total doc height
				if ( (amt_scrolled + jqTargetElement.position().top + jQuery(window).height() + settings.buffer) > jQuery(document).height() ) {
					fire = true;
				}
			}
			
			if ( fire ) {
				callback.call(this, domTargetElement);
			}
			
			
		}		
	);       	

return this;

};


//
// scrollExtend
//
jQuery.fn.scrollExtend = function(options) {
	
	//
	// Special actions
	//
	if ( options == 'disable' ) {
		jQuery(this).data('scrollExtend-disabled', true);
		return;
	}
	
	if ( options == 'enable' ) {
		jQuery(this).data('scrollExtend-disabled', false);
		return;
	}
	
	
       	var settings = {
       		'url': null,
       		'beforeStart': null,
       		'onSuccess': null,
       		'target': null, 
		'loadingIndicatorEnabled': true,
       		'loadingIndicatorClass': 'scrollExtend-loading',
		'newElementClass': '',
		'ajaxSettings': {}
       	};

	var url;
	var localAjaxSettings = {};
	var ajaxSettings = settings.ajaxSettings;
	
       	jQuery.extend(settings, options);
	jQuery.extend(ajaxSettings, settings.ajaxSettings);		

	jQuery(this).onScrollBeyond(
		function(container) {
		
			var jqContainerElem = jQuery(container);

			//
			// Make sure scrollExtend wasn't explicitly disabled,
			// and that we're not already loading a new element
			//
			if ( jqContainerElem.data('scrollExtend-disabled') != true && 
					jqContainerElem.data('scrollExtendLoading') != true ) {
			
				jqContainerElem.data('scrollExtendLoading', true);
			
				if ( typeof(settings.beforeStart) == 'function' ) {
					var ret = settings.beforeStart.call(this, container);
					if ( !ret ) {
						jqContainerElem.data('scrollExtendLoading', false);
						return;
					}
				}
			
				//
				// Check the disabled flag again in case
				// it was changed during the beforeStart callback
				//
				if ( jqContainerElem.data('scrollExtend-disabled') == true ) {
					jqContainerElem.data('scrollExtendLoading', false);
					return;
				}
			

				//
				// Set the URL
				//				
				if ( typeof(settings.url) == 'function' ) {
					url = settings.url.call(this, container);
				}
				else {
					url = settings.url;
				}
			
				ajaxSettings.url = url;

				//
				// Set up our new element
				//
				var target = ( settings.target ) ? settings.target : container;
				var new_elem = ( container.is('table') ) ? jQuery('<tbody/>') : jQuery('<li></li>');
			
				if ( settings.newElementClass != '' ) {
					jQuery(new_elem).addClass( settings.newElementClass );
				}

				//
				// Add loading indicator
				//
				

				if ( settings.loadingIndicatorEnabled ) {
					var jqLoadingElem = jQuery('<li></li>');

					jqLoadingElem.addClass( settings.loadingIndicatorClass );
			
					jqLoadingElem.appendTo(target);
				}			

				//
				// Set up the AJAX request
				//
				localAjaxSettings = {
					'success': function(data, textStatus) {
						if ( data.length > 0 )
						{
							var target = ( settings.target ) ? settings.target : container;
						
							//jQuery(new_elem).html(data);
							//jQuery(new_elem).appendTo(target);
							$(target).append(data);
	
							if ( typeof(settings.onSuccess) == 'function' ) {
								settings.onSuccess.call( this, container, new_elem );
							}

						}
						
	
						jQuery(container).data('scrollExtendLoading', false);
							
						if ( settings.loadingIndicatorEnabled ) {
							jqLoadingElem.remove();
						}						
						
					}
					
				}


				jQuery.extend(ajaxSettings, localAjaxSettings);


				//
				// Run the AJAX request
				//
				jQuery.ajax( ajaxSettings );	


			}
		},
		settings
	);

       
       	return this;

};

