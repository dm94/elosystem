<?php

/*
Template Name: Match
Template Post Type: matches
*/

get_header();

?>

<div id="main-content" class="page-panel">
			<?php while ( have_posts() ) : the_post(); ?>
			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content row">
						<div class="container">
							<div class="col-lg-12">
								<div class="col-lg-6">
										<div class="panel-heading">
											<div class="row">
												<div class="columns text-left">
													<div class="huge"><?php _e('Jugador 1','elosystem'); ?></div>
												</div>
											</div>
										</div>
										<div class="panel-footer">
											Resultados de la partida
										</div>
									</div>
								</div>
								<div class="col-lg-6">
										<div class="panel-heading">
											<div class="row">
												<div class="columns text-left">
													<div class="huge"><?php _e('Jugador 2','elosystem'); ?></div>
												</div>
											</div>
										</div>
										<div class="panel-footer">
											Resultados de la partida
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>

</div> <!-- #main-content -->

<?php get_footer(); ?>