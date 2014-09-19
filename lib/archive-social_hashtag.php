<!--this file is The Loop for the archive page.-->

<?php get_header(); ?>
<section id="social_hashtags">


	<?php if(have_posts()): ?>

		<div class="the-social-posts">
		<?php while(have_posts()): the_post(); ?>
			<div class="a-social-post">
				<a href="<?php the_permalink(); ?>">
					<?php
						$content = get_post_field('post_content', get_the_ID()); 
						if (strpos($content,'<img') !== false) {
    						echo $content;
						}
						//the_content(); 
					?>
				</a>
					<?php
						echo "<a href='" . get_post_meta(get_the_ID(), 'social_hashtag_user_link', true) . "'>";
								echo "@" . get_post_meta(get_the_ID(), 'social_hashtag_userhandle', true);
						echo "</a>";
						echo "<a href='" . get_post_meta(get_the_ID(), 'social_hashtag_post_link', true) . "'>";
								echo " on " . get_post_meta(get_the_ID(), 'social_hashtag_platform', true);
					?>
				</a>
				<p><?php the_title(); ?></p>
			</div>
		<?php endwhile; ?>
		</div>
		

	<?php else: ?>
		<h4>No Content was found</h4>
	<?php endif; ?>
		<p class="clear"> &nbsp; </p>
		 <?php posts_nav_link( $sep, $prelabel, $nextlabel ); ?> 
</section>
<?php get_footer(); ?>