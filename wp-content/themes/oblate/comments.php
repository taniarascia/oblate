<?php if ( post_password_required() ) {
	return;
} ?>

<section id="comments" class="comments-area">
	<div class="container">

		<?php	comment_form( array(
    					'title_reply' => 'Write a response'
						)); ?>

			<?php if ( have_comments() ) : ?>

			<h3 class="comments-title">
				<?php
		printf( _nx( 'responses', '%1$s responses', get_comments_number(), ''),
			number_format_i18n( get_comments_number() ), get_the_title() );
		?>
			</h3>
			<ul class="comment-list">
				<?php
		wp_list_comments( array(
			'short_ping'  => true,
			'avatar_size' => 45,
		) );
	?>
			</ul>
			<div class="comment-links">
				<div class="pagination-left">
					<?php previous_comments_link(); ?>
				</div>
				<div class="pagination-right">
					<?php next_comments_link(); ?>
				</div>
			</div>

			<p class="text-center"><a href="<?php echo get_comments_link(); ?>" class="button">Write a response</a></p>

			<?php endif; // have_comments() 

		// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

			<p class="no-comments">
				<?php _e( 'Comments are closed.' ); ?>
			</p>

			<?php endif; ?>

	</div>
</section>