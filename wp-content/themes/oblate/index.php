<?php get_header(); ?>

    <div class="container">
        <header class="search-enabled">
			<h1>Articles</h1>
			<form id="search-form" role="search" class="search-form" method="get" action="<?php echo home_url( '/' ); ?>">
                <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
				<div class="search-wrapper">
					<input id="filter" type="search" placeholder="<?php echo esc_attr_x( 'Filter', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>"
		name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>">
					<i class="fas fa-search search-icon"></i>
				</div>
            </form>
        </header>
        
        <section class="list">
            <div class="lead" id="none-found">No results found.</div>

            <?php 
            if ( have_posts() ) : while ( have_posts() ) : the_post(); 

                get_template_part( 'content', get_post_format() );

            endwhile; endif; ?>

            </div>
        </section>

        <div class="posts-links">
            <div class="pagination-left">
                <?php previous_posts_link(); ?>
            </div>
            <div class="pagination-right">
                <?php next_posts_link(); ?>
            </div>
        </div>
    </div>

<?php get_footer();