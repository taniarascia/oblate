<?php 

if ( post_password_required() ) return; ?>

    <section id="comments" class="comments-area">
        <div class="container">

        <?php 
        comment_form( array(
            'title_reply'         => 'Write a response',
            'comment_notes_after' => '<p>All code will be displayed literally.</p>'
            )); ?>

        <?php 
        if ( have_comments() ) : ?>

            <h3 class="comments-title">Discussion</h3>
            <ul class="comment-list">
                <?php
                wp_list_comments( array(
                    'short_ping'  => true,
                    'avatar_size' => 0,
                ) ); ?>
            </ul>
        
            <?php
            $prev_comment_link = get_previous_comments_link();
            $next_comment_link = get_next_comments_link();
            
            if ($prev_comment_link || $next_comment_link) : ?>
                <div class="comment-links">
                    <div class="pagination-left">
                        <?php previous_comments_link(); ?>
                    </div>
                    <div class="pagination-right">
                        <?php next_comments_link(); ?>
                    </div>
                </div>
            <?php 
            endif; 
		endif;  //have_comments(); 

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments">
			<?php _e( 'Comments are closed.' ); ?>
		</p>

        <?php 
        endif; ?>
    </div>
</section>