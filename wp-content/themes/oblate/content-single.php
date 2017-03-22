<article id="post-<?php the_ID(); ?>">

	<header class="single-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<time datetime="<?php the_time( 'Y-m-d' ); ?>">
				<?php the_time( 'F j, Y' ); ?> -
					<?php comments_popup_link( 'Leave a response', '1 response', '% responses' ); ?>
			</time>
		</div>
	</header>

	<section class="single-body">
		<div class="container">
			<?php if ( has_excerpt() ) { ?>
				<div class="lead">
					<?php the_excerpt(); ?>
				</div>
				<?php } ?>

					<?php the_content(); ?>
					
							
<?php
  $posttags = get_the_tags();
  if ($posttags) {
    foreach($posttags as $tag) {
      echo $tag->name; 
    }
  } ?>
		</div>

	</section>

</article>