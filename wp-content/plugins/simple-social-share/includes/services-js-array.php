<?php
function get_services_js_arr( $s3_option = array() ){
	$service_js_arr = array(
								'facebook' => '<div id="fb-root"></div>
													<script>(function(d, s, id) {
												  var js, fjs = d.getElementsByTagName(s)[0];
												  if (d.getElementById(id)) return;
												  js = d.createElement(s); js.id = id;
												  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
												  fjs.parentNode.insertBefore(js, fjs);
												}(document, "script", "facebook-jssdk"));</script>',
								'twitter' => '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>',
								'googleplus' => '<script type="text/javascript">
									  				(function() {
									    				var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
									    				po.src = "https://apis.google.com/js/platform.js";
									    				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
									  				})();
													</script>',
								'digg' => '',
								'reddit' => '',
								'linkedin' => '<script src="//platform.linkedin.com/in.js" type="text/javascript">
										  				lang: en_US
													</script>',
								'stumbleupon' => '<script type="text/javascript">
													  (function() {
													    var li = document.createElement("script"); li.type = "text/javascript"; li.async = true;
													    li.src = ("https:" == document.location.protocol ? "https:" : "http:") + "//platform.stumbleupon.com/1/widgets.js";
													    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s);
													  })();
													</script>',
								'tumblr' => '',
								'pinterest' => ''
							);
	return $service_js_arr;
}
?>