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

        <blockquote>
        You taught me more than any class could have and it took me a fraction of the time because of how clearly you write and teach.
        <cite>Evan Whelan</cite>
        </blockquote>

        <div class="tab-container">
            <a href="<?php echo site_url(); ?>/category/front-end" class="tab">UI/UX</a>
            <a href="<?php echo site_url(); ?>/category/back-end" class="tab">Programming</a>
            <a href="<?php echo site_url(); ?>/category/javascript" class="tab">JavaScript</a>
            <a href="<?php echo site_url(); ?>/category/workflow" class="tab">Workflow</a>
            <a href="<?php echo site_url(); ?>/category/system" class="tab">DevOps</a>
            <a href="<?php echo site_url(); ?>/category/wordpress" class="tab">WordPress</a>
        </div>
        
        <section class="list">
            <div class="lead" id="none-found">No results found.</div>

            <?php 
            if ( have_posts() ) : while ( have_posts() ) : the_post(); 

                get_template_part( 'content', get_post_format() );

            endwhile; endif; ?>

            </div>
        </section>

    </div>

<?php get_footer();