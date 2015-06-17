<?php
/**
 * @package Brevitas
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<?php brevitas_posted_on(); ?>
		</div>
		<?php 
			// display featured image
			if ( has_post_thumbnail() ) :
				the_post_thumbnail( 'brevitas_featured_image', array( 'class' => 'featured-img' ) );
			endif;
			the_title( '<h1 class="entry-title">', '</h1>' );
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			// output the content
			the_content();
			// break into pages
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'brevitas' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( 'post' == get_post_type() ) : ?>
		<footer class="entry-footer">
			<?php
				/* translators: used between list items, there is a space after the comma */
				$category_list = get_the_category_list( __( ', ', 'brevitas' ) );
	
				/* translators: used between list items, there is a space after the comma */
				$tag_list = get_the_tag_list( '', __( ', ', 'brevitas' ) );
	
				if ( ! brevitas_categorized_blog() ) {
					// This blog only has 1 category so we just need to worry about tags in the meta text
					if ( '' != $tag_list ) {
						$meta_text = '<span class="tag-links"><i class="fa fa-tags"></i> %2$s</span>';
					} else {
						$meta_text = '';
					}
	
				} else {
					// But this blog has loads of categories so we should probably display them here
					if ( '' != $tag_list ) {
						$meta_text = '<span class="cat-links"><i class="fa fa-archive"></i> %1$s</span><br><span class="tag-links"><i class="fa fa-tags"></i> %2$s</span>';
					} else {
						$meta_text = '<span class="cat-links"><i class="fa fa-archive"></i> %1$s</span>';
					}
	
				} // end check for categories on this blog
	
				printf( $meta_text, $category_list, $tag_list );
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
