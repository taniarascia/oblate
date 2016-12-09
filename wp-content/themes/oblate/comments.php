<?php 
if ( post_password_required() ) {
	return;
} ?>

	<div id="comments" class="comments-area">

		<?php if ( have_comments() ) : ?>

			<h3 class="comments-title">
		<?php
		printf( _nx( 'Responses', '%1$s Responses', get_comments_number(), ''),
			number_format_i18n( get_comments_number() ), get_the_title() );
		?>
		</h3>
			<ul class="comment-list">
				<?php
			wp_list_comments( array(
				'short_ping'  => true,
				'avatar_size' => 50,
			) );
		?>
			</ul>
			<div class="posts-links">
				<div class="pagination-left">
					<?php previous_comments_link(); ?>
				</div>
				<div class="pagination-right">
					<?php next_comments_link(); ?>
				</div>
			</div>

			<?php endif; // have_comments() ?>

				<?php	$args = array('comment_notes_after' => '<p style="font-size: .9rem;margin-bottom:1rem;"><a href="https://en.support.wordpress.com/markdown-quick-reference/">Markdown</a> is enabled in comments. If you would like to post code in your comments, please wrap it in a <code style="font-size:.9rem;">&lt;pre&gt;&lt;code&gt;</code>. HTML/PHP code must be <a href="http://www.freeformatter.com/html-escape.html">escaped</a>. Failure to do so will make me sad. </p>'); comment_form( $args ); ?>

					<?php
			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
			?>

						<p class="no-comments">
							<?php _e( 'Comments are closed.' ); ?>
						</p>

						<?php endif; 

?>

	</div>