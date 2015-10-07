<?php
function get_small_buttons_markup_arr(){
	$service_markup_arr = array(
								'facebook' => '<div class="s-single-share">
												<div class="fb-share-button" data-href="'.get_permalink().'" data-type="button"></div>
												</div>',
								'twitter' => '<div class="s-single-share">
												<a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
												</div>',
								'googleplus' => '<div class="s-single-share">
												<div class="g-plusone" data-size="medium" data-annotation="none"></div>
												</div>',
								'digg' => '',
								'reddit' => '<div class="s-single-share">
												<a href="http://www.reddit.com/submit" onclick="window.location = \'http://www.reddit.com/submit?url=\' + encodeURIComponent(window.location); return false"> <img src="http://www.reddit.com/static/spreddit7.gif" alt="submit to reddit" border="0" /> </a>
											</div>',
								'linkedin' => '<div class="s-single-share">
													<script type="IN/Share"></script>
												</div>',
								'stumbleupon' => '<div class="s-single-share">
												<su:badge layout="4"></su:badge>
												</div>',
								'tumblr' => '<div class="s-single-share">
												<a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:61px; height:20px; background:url(\'http://platform.tumblr.com/v1/share_2.png\') top left no-repeat transparent;">Share on Tumblr</a>
												</div>',
								'pinterest' => '<div class="s-single-share">
													<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"  data-pin-color="red"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_red_20.png" /></a>
													<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
												</div>',
								'email' => '<div class="s-single-share">
												<a href="mailto:?Subject='.str_replace(' ', '%20', get_the_title()).'&Body='.str_replace(' ', '%20', 'Here is the link to the article: '.get_permalink()).'" title="Email" class="s3-email"><img src="'.plugins_url( '../images/share-email.png' , __FILE__ ).'"></a>
											</div>'
							);
	return $service_markup_arr;
}
?>