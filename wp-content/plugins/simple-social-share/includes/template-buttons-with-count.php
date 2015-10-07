<?php
function get_buttons_with_c_markup_arr(){
	$service_markup_arr = array(
								'facebook' => '<div class="s-single-share">
												<div class="fb-share-button" data-href="'.get_permalink().'" data-type="button_count"></div></div>',
								'twitter' => '<div class="s-single-share"><a href="https://twitter.com/share" class="twitter-share-button"></a>
												</div>',
								'googleplus' => '<div class="s-single-share">
													<div class="g-plusone" data-size="medium"></div>
													</div>',
								'digg' => '',
								'reddit' => '<div class="s-single-share">
												<script type="text/javascript" src="http://www.reddit.com/static/button/button1.js"></script>
											</div>',
								'linkedin' => '<div class="s-single-share">
													<script type="IN/Share" data-counter="right"></script>
												</div>',
								'stumbleupon' => '<div class="s-single-share">
													<su:badge layout="1"></su:badge>	
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