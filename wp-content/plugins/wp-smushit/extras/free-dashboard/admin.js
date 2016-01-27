jQuery(function() {
	var el_notice = jQuery( ".frash-notice" ),
		type = el_notice.find( "input[name=type]" ).val(),
		plugin_id = el_notice.find( "input[name=plugin_id]" ).val(),
		url_wp = el_notice.find( "input[name=url_wp]" ).val(),
		drip_plugin = el_notice.find( "input[name=drip_plugin]" ).val(),
		inp_email = el_notice.find( "input[name=email]" )
		btn_act = el_notice.find( ".frash-notice-act" ),
		btn_dismiss = el_notice.find( ".frash-notice-dismiss" )
		ajax_data = {};

	ajax_data.plugin_id = plugin_id;
	ajax_data.type = type;

	function init_email() {
		if ( ! inp_email.length ) { return; }

		// Adjust the size of the email field to its contents.
		function adjust_email_size() {
			var width, tmp = jQuery( "<span></span>" );

			tmp.addClass( "input-field" ).text( inp_email.val() );
			tmp.appendTo( "body" );
			width = parseInt( tmp.width() );
			tmp.remove();

			inp_email.width( width + 34 );
		}

		function email_keycheck( ev ) {
			if ( 13 === ev.keyCode ) {
				btn_act.click();
			} else {
				adjust_email_size();
			}
		}

		inp_email.keyup( email_keycheck ).focus().select();
		adjust_email_size();
	}

	// Display the notice after the page was loaded.
	function initialize() {
		el_notice.fadeIn( 500 );
		init_email();
	}

	// Hide the notice after a CTA button was clicked
	function remove_notice() {
		el_notice.fadeTo( 100 , 0, function() {
			el_notice.slideUp( 100, function() {
				el_notice.remove();
			});
		});
	}

	// Open a tab to rate the plugin.
	function act_rate() {
		var url = url_wp.replace( /\/plugins\//, "/support/view/plugin-reviews/" ) + "?rate=5#postform",
			link = jQuery( '<a href="' + url + '" target="_blank">Rate</a>' );

		link.appendTo( "body" );
		link[0].click();
		link.remove();
	}

	// Submit the user to our email list.
	function act_email() {
		var email = inp_email.val();

		// First create a new subscriber.
		_dcq.push([
			"identify",
			{ email: email }
		]);

		// Then trigger the specified rule.
		_dcq.push([
			"track",
			"Free plugin email course",
			{"Plugin": drip_plugin}
		]);
	}

	// Notify WordPress about the users choice and close the message.
	function notify_wordpress( action, message ) {
		el_notice.attr( "data-message", message );
		el_notice.addClass( "loading" );

		ajax_data.action = action;
		jQuery.post(
			window.ajaxurl,
			ajax_data,
			remove_notice
		);
	}

	// Handle click on the primary CTA button.
	// Either open the wp.org page or submit the email address.
	btn_act.click(function( ev ) {
		ev.preventDefault();

		switch ( type ) {
			case 'rate': act_rate(); break;
			case 'email': act_email(); break;
		}

		notify_wordpress( "frash_act", btn_act.data( "msg" ) );
	});

	// Dismiss the notice without any action.
	btn_dismiss.click(function( ev ) {
		ev.preventDefault();

		notify_wordpress( "frash_dismiss", btn_dismiss.data( "msg" ) );
	});

	window.setTimeout( initialize, 500 );
});

// Drip integration
var _dcq = _dcq || [];
var _dcs = _dcs || {};

_dcs.account = '6994213';
var dc = document.createElement( 'script' );
dc.type = 'text/javascript'; dc.async = true;
dc.src = '//tag.getdrip.com/6994213.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(dc, s);
// End of drip integration